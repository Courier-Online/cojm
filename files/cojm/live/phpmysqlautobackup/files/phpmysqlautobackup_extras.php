<?php

/*
    COJM Courier Online Operations Management
	phpmysqlautobackup_extras.php 
    Copyright (C) 2018 S.Young cojm.co.uk

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/



ini_set('memory_limit','256M');


// echo ' php extras ln 3 ';

/*******************************************************************************************
    phpMySQLAutoBackup  -  Author:  http://www.DWalker.co.uk - released under GPL License
           For support and help please try the forum at: http://www.dwalker.co.uk/forum/
********************************************************************************************
Version    Date              Comment
0.2.0      7th July 2005     GPL release
0.3.0      June 2006  Upgrade - added ability to backup separate tables
0.4.0      Dec 2006   removed bugs/improved code
1.4.0      Dec 2007   improved faster version
1.5.0      Dec 2008   improved and added FTP backup to remote site
1.5.4      Nov 2009   Version printed in email
1.5.5      Feb 2011  more options for config added - email reports only and/or backup, save backup file to local and/or remote server.
                                Reporter added: email report of last 6 (or more) backup stats (date, total bytes exported, total lines exported) plus any errors
                                MySQL error reporting added  and Automated version checker added
1.6.0      Dec 2011  PDO version
1.6.1      April 2012 - CURLOPT_TRANSFERTEXT turned off (to stop garbaging zip file on transfer) and bug removed from write_back
1.6.2      Sept 2012 - updated newline to constant:  NEWLINE
1.6.3      Oct 2012 - corrected bug with CONSTRAINT and added CHARSET - bug fix code gratefully received from: vit.bares@gmail.com
********************************************************************************************/
$phpMySQLAutoBackup_version="1.6.3";
// ---------------------------------------------------------
function has_data($value) {
    if (is_array($value)) return (sizeof($value) > 0)? true : false;
    else return (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) ? true : false;
}

function xmail ($to_emailaddress,$from_emailaddress, $subject, $content, $file_name, $backup_type, $ver) {
    $mail_attached = "";
    $boundary = "----=_NextPart_000_01FB_010".md5($to_emailaddress);
    $mail_attached.="--".$boundary.NEWLINE
                        ."Content-Type: application/octet-stream;".NEWLINE." name=\"$file_name\"".NEWLINE
                        ."Content-Transfer-Encoding: base64".NEWLINE
                        ."Content-Disposition: attachment;".NEWLINE." filename=\"$file_name\"".NEWLINE.NEWLINE
                        .chunk_split(base64_encode($content)).NEWLINE;
    $mail_attached .= "--".$boundary."--".NEWLINE;
    $add_header ="MIME-Version: 1.0".NEWLINE."Content-Type: multipart/mixed;".NEWLINE." boundary=\"$boundary\" ".NEWLINE;
    $mail_content="--".$boundary.NEWLINE."Content-Type: text/plain; ".NEWLINE." charset=\"iso-8859-1\"".NEWLINE."Content-Transfer-Encoding: 7bit".NEWLINE.NEWLINE."BACKUP Successful...".NEWLINE.NEWLINE."Please see attached for your zipped Backup file; $backup_type ".NEWLINE."If this is the first backup then you should test it restores correctly to a test server.".NEWLINE.NEWLINE." phpMySQLAutoBackup (version $ver) is developed by http://www.dwalker.co.uk/ ".NEWLINE.NEWLINE." Have a good day now you have a backup of your MySQL db  :-) ".NEWLINE.NEWLINE." Please consider making a donation at: ".NEWLINE." http://www.dwalker.co.uk/make_a_donation.php ".NEWLINE." (every penny or cent helps)".NEWLINE.$mail_attached;
    return mail($to_emailaddress, $subject, $mail_content, "From: $from_emailaddress".NEWLINE."Reply-To:$from_emailaddress".NEWLINE.$add_header);
}

function write_backup($gzdata, $backup_file_name) {
	$filename = BALOCATION."backups/".date("Y").'-'.date("m")."/".$backup_file_name;
    $dirname = dirname($filename);
    if (!is_dir($dirname)) {
        mkdir($dirname, 0755, true);
    }
	
    $errors=' <br /> 84 write_backup *********************************************************** ';
    $fp = fopen(BALOCATION."backups/".date("Y").'-'.date("m")."/".$backup_file_name, "w");
    $errors.=fwrite($fp, $gzdata).' bytes to ' . BALOCATION."backups/".date("Y").'-'.date("m")."/".$backup_file_name;
    fclose($fp);

    if (file_exists(BALOCATION."backups/".date("Y").'-'.date("m")."/".$backup_file_name)) { 
        $errors.=NEWLINE. ' FILE EXISTS ';
    } else {
        $errors.=NEWLINE. ' FILE DOES NOT EXIST ';
    }


    
    //check folder is protected - stop HTTP access
    if (!file_exists(BALOCATION."backups/".date("Y").'-'.date("m")."/.htaccess")) {
        $fp = fopen(BALOCATION."backups/".date("Y").'-'.date("m")."/.htaccess", "w");
        fwrite($fp, "deny from all");
        fclose($fp);
    }
    //  delete_old_backups();   ////     EDITED DIRECTORY SO NO DELETING OLD BACKUPS
    return $errors;

}
 
function delete_old_backups()
{
    $files=$keep=array();
    $prefix = 'mysql_'.DBNAME;
    $suffix = ".sql.gz";
    $dir = BALOCATION."backups/";                
    if ($handle = opendir($dir))
      {
       while (false !== ($file = readdir($handle)))
        {
         if ((filetype($dir.$file) == "file") && (substr($file,0,strlen($prefix)) == $prefix) && (substr($file,-strlen($suffix)) == $suffix) && (filesize($dir.$file)>0))
          {
           $files[filemtime($dir.$file)]= $file;       
          }
         }
        closedir($handle);
        krsort($files);
        $slice = min(TOTAL_BACKUP_FILES_TO_RETAIN,sizeof($files));
        if ($slice)
         {
          $erase = array_slice($files,TOTAL_BACKUP_FILES_TO_RETAIN);
          foreach ($erase as $key=>$thisOne)
           {
            unlink($dir.$thisOne);
           }
         } 
      }
}


class transfer_backup {
    public $error;

    public function transfer_data($ftp_username,$ftp_password,$ftp_server,$ftp_path,$passwdzip_file_name,$lines_exported, $backupdescription) {
		 global $transfer_backup_infotext;
        if (function_exists('curl_exec')) {
            $ch = curl_init();
            $ftp_password=REMOTEFTPPASSWD;
 
            $file=BALOCATION."backups/".date("Y").'-'.date("m").'/'.$passwdzip_file_name;
            $fp = fopen($file, "r");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "ftp://$ftp_server/".$passwdzip_file_name);
            curl_setopt($ch, CURLOPT_USERPWD, "$ftp_username:$ftp_password");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_UPLOAD, 1);
            curl_setopt($ch, CURLOPT_INFILE, $fp);
            curl_setopt($ch, CURLOPT_INFILESIZE, filesize($file));
            curl_setopt($ch, CURLOPT_TRANSFERTEXT, 0);
            curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']." - via COJM");
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);

		
            // $tempinfotext= '<br /> CURLINFO_PRIMARY_IP : '.$info['primary_ip'];
            // $tempinfotext.= '<br /> namelookup_time : '.$info['namelookup_time'];
            // $tempinfotext.= curl_error($ch);
            // $transfer_backup_infotext.= '<br /> CURLINFO_PRIMARY_IP : '.$info['primary_ip'];
            
            $actualbytes=$info['size_upload'];
            
            
            if ($info['size_upload'] >= 1073741824)
            {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            }
            elseif ($info['size_upload'] >= 1048576)
            {
                $bytes = number_format(($info['size_upload']) / 1048576, 2) . ' MB';
            }
            elseif ($info['size_upload'] >= 1024)
            {
                $bytes = number_format(($info['size_upload']) / 1024, 2) . ' kB';
            } 
            elseif ($info['size_upload'] > 1)
            {
                $bytes = $info['size_upload'] . ' bytes';
            }
            elseif ($info['size_upload'] == 1)
            {
                $bytes = $info['size_upload'] . ' byte';
            }
            else
            {
                $bytes = '0 bytes';
            }
		
	 
if ($info['speed_upload'] >= 1073741824)
        {
            $speed = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($info['speed_upload'] >= 1048576)
        {
            $speed = number_format(($info['speed_upload']) / 1048576, 2) . ' MB';
        }
        elseif ($info['speed_upload'] >= 1024)
        {
            $speed = number_format(($info['speed_upload']) / 1024, 2) . ' kB';
        } 
		elseif ($info['speed_upload'] > 1)
        {
            $speed = $info['speed_upload'] . ' bytes';
        }
        elseif ($info['speed_upload'] == 1)
        {
            $speed = $info['speed_upload'] . ' byte';
        }
        else
        {
            $speed = '0 bytes';
        }	
		
	
 $transfer_backup_infotext.=' FTPd '. $bytes.' in ' . $info['total_time'] . ' secs, avg '.$speed. '  / sec. ';	
 
 
  // ends check for curl error message
  
 $date=time();
 
 $lines=$lines_exported;
 
   $dbc = dbc::instance();
 
   $query="INSERT INTO `phpmysqlautobackup_log` (`date`, `bytes`, `lines`, `type` )
                 VALUES ('$date', '$actualbytes', '$lines', '$backupdescription' )";
       $result = $dbc->prepare($query);
       $result = $dbc->execute($result);

		
        if (empty($info['http_code'])) {
            $this->error = NEWLINE."FTP ERROR - Failed to transfer backup file to remote ftp server ".curl_error($ch); 
            $auditerror=1;
		}
        else
        {
         $http_codes = parse_ini_file(BALOCATION."http_codes.ini");
         if ($info['http_code']!=226) $this->error = NEWLINE.NEWLINE."FTP ERROR - server response: ".NEWLINE.$info['http_code']
       ." " . $http_codes[$info['http_code']] .NEWLINE."for more detail please refer to: http://www.w3.org/Protocols/rfc959/4_FileTransfer.html"                                            ;
        }
        curl_close($ch);
       }
       else $this->error = NEWLINE.NEWLINE."WARNING: FTP will not function as PHP CURL does not exist on your hosting. ";
       return $this->error;

	   $transfer_backup_infotext=$info['size_upload'].'_bytes_end_var_'.$transfer_backup_infotext;
	   
	   return $transfer_backup_infotext;



      }
}

class record
{
      public function save($date, $bytes, $lines, $backupdescription)
      {
       $dbc = dbc::instance();
       $result = $dbc->prepare("SHOW TABLES LIKE 'phpmysqlautobackup_log' ");
       $rows = $dbc->executeGetRows($result);
       if(count($rows)<1)
       {
        $q="CREATE TABLE IF NOT EXISTS `phpmysqlautobackup_log` (
            `date` int(11) NOT NULL,
            `bytes` int(11) NOT NULL,
            `lines` int(11) NOT NULL,
			`type` VARCHAR(45) NOT NULL,
             PRIMARY KEY (`date`) )";
        $result = $dbc->prepare($q);
        $result = $dbc->execute($result);
       }
       $query="INSERT INTO `phpmysqlautobackup_log` (`date`, `bytes`, `lines`, `type` )
                 VALUES ('$date', '$bytes', '$lines', '$backupdescription' )";
       $result = $dbc->prepare($query);
       $result = $dbc->execute($result);
       $query="SELECT date FROM `phpmysqlautobackup_log` ORDER BY `date` DESC LIMIT 0 , ".LOG_REPORTS_MAX;
       $result = $dbc->prepare($query);
       $rows = $dbc->executeGetRows($result);

       $search_date=$rows[count($rows)-1]['date'];
       $query="delete FROM `phpmysqlautobackup_log` where date<'$search_date' ";
       $result = $dbc->prepare($query);
       $result = $dbc->execute($result);
      }

      public function get()
      {
       $dbc = dbc::instance();
       $result = $dbc->prepare("SELECT * FROM `phpmysqlautobackup_log` ORDER BY `date` DESC ");
       $rows = $dbc->executeGetRows($result);
       $report=NEWLINE." <br /> Below are the records of the last ".LOG_REPORTS_MAX." backups. <br /> ".NEWLINE."DATE and TIME (total bytes, Total lines exported) <br /> ";
       foreach ($rows as $row)
       {
        $report.= NEWLINE.strftime("%d %b %Y - %H:%M:%S",$row['date'])." (";
        $report.= number_format(($row['bytes']/1000), 2, '.', '') ." KB, ";
        $report.= number_format($row['lines'])." lines)";
		$report.= '<br />';
       }
       return ' ';
      }

}

class version
{
 public function check($version)
      {
       $newest_version=$version;
       if ($version!=$newest_version) return "*WARNING: UPGRADE REQUIRED - please visit: http://www.dwalker.co.uk/phpmysqlautobackup/ ";
      }
}

class dbc extends PDO
{
 protected static $instance;

 public function __construct()
 {
   $options = array(PDO::ATTR_PERSISTENT => true,
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".CHARSET // this command will be executed during every connection to server - suggested by: vit.bares@gmail.com
   );
   try {
        $this->dbconn = $GLOBALS['dbh'];
        return $this->dbconn;
       }
    catch (PDOException $e){ $this->reportDBError($e->getMessage()); }   
 }
 
 public function reportDBError($msg)
 {
  if (DEBUG) print_r('<div style="padding:10%;"><h3>'.nl2br($msg).'</h3></div>');
  else
  {
   if(!session_id()) session_start();
   $_SESSION['pmab_mysql_errors'] = NEWLINE.NEWLINE."MySQL error: ".$msg."\n";
  }

 }
 
 public static function instance()
 {
  if (!isset(self::$instance)) self::$instance = new self();
  return self::$instance;
 }

public function prepare($query, $options = NULL) {
  try { return $this->dbconn->prepare($query); }
   catch (PDOException $e){ $this->reportDBError($e->getMessage()); }   
 }      

 public function bindParam($query) {
  try { return $this->dbconn->bindParam($query); }
   catch (PDOException $e){ $this->reportDBError($e->getMessage()); }     
 }

 public function query($query) {
  try {
       if ($this->query($query)) return $this->fetchAll();
       else return 0;
      } 
   catch (PDOException $e){ $this->reportDBError($e->getMessage()."<hr>".$e->getTraceAsString()); } }

 public function execute($result) {//use for insert/update/delete
  try { if ($result->execute()) return $result; } 
   catch (PDOException $e){ $this->reportDBError($e->getMessage()."<hr>".$e->getTraceAsString()); }     
 }
 public function executeGetRows($result) {//use to retrieve rows of data
  try { 
       if ($result->execute()) return $result->fetchAll(PDO::FETCH_ASSOC);
       else return 0;
      }
    catch (PDOException $e){ $this->reportDBError($e->getMessage()."<hr>".$e->getTraceAsString()); }     
 }

 public function __clone()
 {  //not allowed
 }
 public function __destruct()
 {
  $this->dbconn = null;
 }
}