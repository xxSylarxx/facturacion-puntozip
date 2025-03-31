<?php
session_start();
require_once("vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Conect\Conexion;
//clases de acceso a datos
require_once("../../vendor/autoload.php");

use Controladores\ControladorEmpresa;


$emisor = ControladorEmpresa::ctrEmisor();
/* Define database parameters here
 */
date_default_timezone_set('America/Lima');
// define("DB_USER", 'root');
// define("DB_PASSWORD", '1105gvi');
// define("DB_NAME", 'facturacionbmm');
// define("DB_HOST", 'localhost');
define("BACKUP_DIR", 'backup/'); // Comment this line to use same script's directory ('.')
define("TABLES", '*'); // Full backup
//define("TABLES", 'table1, table2, table3'); // Partial backup
define('IGNORE_TABLES', array(
    'tbl_token_auth',
    'token_auth'
)); // Tables to ignore
define("CHARSET", 'utf8');
define("GZIP_BACKUP_FILE", true); // Set to false if you want plain SQL backup files (not gzipped)
define("DISABLE_FOREIGN_KEY_CHECKS", true); // Set to true if you are having foreign key constraint fails
define("BATCH_SIZE", 1000); // Batch size when selecting rows from database in order to not exhaust system memory
// Also number of rows per INSERT statement in backup file

/**
 * The Backup_Database class
 */
class Backup_Database  extends Conexion
{
    /**
     * Host where the database is located
     */
    var $host;

    /**
     * Username used to connect to database
     */
    var $username;

    /**
     * Password used to connect to database
     */
    var $passwd;

    /**
     * Database to backup
     */
    var $dbName;

    /**
     * Database charset
     */
    var $charset;

    /**
     * Database connection
     */
    var $conn;

    /**
     * Backup directory where backup files are stored 
     */
    var $backupDir;

    /**
     * Output backup file
     */
    var $backupFile;

    /**
     * Use gzip compression on backup file
     */
    var $gzipBackupFile;

    /**
     * Content of standard output
     */
    var $output;

    /**
     * Disable foreign key checks
     */
    var $disableForeignKeyChecks;

    /**
     * Batch size, number of rows to process per iteration
     */
    var $batchSize;

    /**
     * Constructor initializes database
     */
    public function __construct($charset = 'utf8')
    {
        $this->host                    = self::HOST;
        $this->username                = self::USER;
        $this->passwd                  = self::PASSWORD;
        $this->dbName                  = self::BDNAME;
        $this->charset                 = $charset;
        $this->conn                    = $this->initializeDatabase();
        $this->backupDir               = BACKUP_DIR ? BACKUP_DIR : '.';
        $this->backupFile              = self::BDNAME . '-' . date("d-m-Y_His", time()) . '.sql';
        $this->gzipBackupFile          = defined('GZIP_BACKUP_FILE') ? GZIP_BACKUP_FILE : true;
        $this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
        $this->batchSize               = defined('BATCH_SIZE') ? BATCH_SIZE : 1000; // default 1000 rows
        $this->output                  = '';
    }

    protected function initializeDatabase()
    {
        try {
            $conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
            if (mysqli_connect_errno()) {
                throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
                die();
            }
            if (!mysqli_set_charset($conn, $this->charset)) {
                mysqli_query($conn, 'SET NAMES ' . $this->charset);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            die();
        }

        return $conn;
    }

    /**
     * Backup the whole database or just some tables
     * Use '*' for whole database or 'table1 table2 table3...'
     * @param string $tables
     */
    public function backupTables($tables = '*')
    {
        try {
            /**
             * Tables to export
             */
            if ($tables == '*') {
                $tables = array();
                $result = mysqli_query($this->conn, 'SHOW TABLES');
                while ($row = mysqli_fetch_row($result)) {
                    $tables[] = $row[0];
                }
            } else {
                $tables = is_array($tables) ? $tables : explode(',', str_replace(' ', '', $tables));
            }

            $sql = 'CREATE DATABASE IF NOT EXISTS `' . $this->dbName . '`' . ";\n\n";
            $sql .= 'USE `' . $this->dbName . "`;\n\n";

            /**
             * Disable foreign key checks 
             */
            if ($this->disableForeignKeyChecks === true) {
                $sql .= "SET foreign_key_checks = 0;\n\n";
            }

            /**
             * Iterate tables
             */
            foreach ($tables as $k => $table) {
                if (in_array($table, IGNORE_TABLES))
                    continue;
                // ===============================================================================
                // $this->obfPrint("Copia de seguridad `" . $table . "` tabla..." . str_repeat('.', 50 - strlen($table)), 0, 0);

                /**
                 * CREATE TABLE
                 */
                $sql .= 'DROP TABLE IF EXISTS `' . $table . '`;';
                $row = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE `' . $table . '`'));
                $sql .= "\n\n" . $row[1] . ";\n\n";

                /**
                 * INSERT INTO
                 */

                $row = mysqli_fetch_row(mysqli_query($this->conn, 'SELECT COUNT(*) FROM `' . $table . '`'));
                $numRows = $row[0];

                // Split table in batches in order to not exhaust system memory 
                $numBatches = intval($numRows / $this->batchSize) + 1; // Number of while-loop calls to perform

                for ($b = 1; $b <= $numBatches; $b++) {

                    $query = 'SELECT * FROM `' . $table . '` LIMIT ' . ($b * $this->batchSize - $this->batchSize) . ',' . $this->batchSize;
                    $result = mysqli_query($this->conn, $query);
                    $realBatchSize = mysqli_num_rows($result); // Last batch size can be different from $this->batchSize
                    $numFields = mysqli_num_fields($result);

                    if ($realBatchSize !== 0) {
                        $sql .= 'INSERT INTO `' . $table . '` VALUES ';

                        for ($i = 0; $i < $numFields; $i++) {
                            $rowCount = 1;
                            while ($row = mysqli_fetch_row($result)) {
                                $sql .= '(';
                                for ($j = 0; $j < $numFields; $j++) {
                                    if (isset($row[$j])) {
                                        $row[$j] = addslashes($row[$j]);
                                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                                        $row[$j] = str_replace("\r", "\\r", $row[$j]);
                                        $row[$j] = str_replace("\f", "\\f", $row[$j]);
                                        $row[$j] = str_replace("\t", "\\t", $row[$j]);
                                        $row[$j] = str_replace("\v", "\\v", $row[$j]);
                                        $row[$j] = str_replace("\a", "\\a", $row[$j]);
                                        $row[$j] = str_replace("\b", "\\b", $row[$j]);
                                        if ($row[$j] == 'true' or $row[$j] == 'false' or preg_match('/^-?[1-9][0-9]*$/', $row[$j]) or $row[$j] == 'NULL' or $row[$j] == 'null') {
                                            $sql .= $row[$j];
                                        } else {
                                            $sql .= '"' . $row[$j] . '"';
                                        }
                                    } else {
                                        $sql .= 'NULL';
                                    }

                                    if ($j < ($numFields - 1)) {
                                        $sql .= ',';
                                    }
                                }

                                if ($rowCount == $realBatchSize) {
                                    $rowCount = 0;
                                    $sql .= ");\n"; //close the insert statement
                                } else {
                                    $sql .= "),\n"; //close the row
                                }

                                $rowCount++;
                            }
                        }

                        $this->saveFile($sql);
                        $sql = '';
                    }
                }

                /**
                 * CREATE TRIGGER
                 */

                // Check if there are some TRIGGERS associated to the table
                /*$query = "SHOW TRIGGERS LIKE '" . $table . "%'";
                $result = mysqli_query ($this->conn, $query);
                if ($result) {
                    $triggers = array();
                    while ($trigger = mysqli_fetch_row ($result)) {
                        $triggers[] = $trigger[0];
                    }
                    
                    // Iterate through triggers of the table
                    foreach ( $triggers as $trigger ) {
                        $query= 'SHOW CREATE TRIGGER `' . $trigger . '`';
                        $result = mysqli_fetch_array (mysqli_query ($this->conn, $query));
                        $sql.= "\nDROP TRIGGER IF EXISTS `" . $trigger . "`;\n";
                        $sql.= "DELIMITER $$\n" . $result[2] . "$$\n\nDELIMITER ;\n";
                    }
                    $sql.= "\n";
                    $this->saveFile($sql);
                    $sql = '';
                }*/

                $sql .= "\n\n";
                //=================================================================================================
                // $this->obfPrint('OK');
            }

            /**
             * Re-enable foreign key checks 
             */
            if ($this->disableForeignKeyChecks === true) {
                $sql .= "SET foreign_key_checks = 1;\n";
            }

            $this->saveFile($sql);

            if ($this->gzipBackupFile) {
                $this->gzipBackupFile();
            } else {
                $this->obfPrint('Copia de seguridad satisfactoria en ' . $this->backupDir . '/' . $this->backupFile, 1, 1);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Save SQL to file
     * @param string $sql
     */
    protected function saveFile(&$sql)
    {
        if (!$sql) return false;

        try {

            $files = glob($this->backupDir . '/*'); //obtenemos el nombre de todos los ficheros
            foreach ($files as $file) {
                $lastModifiedTime = filemtime($file);
                $currentTime = time();
                $timeDiff = abs($currentTime - $lastModifiedTime) / (10 * 60); //en horas
                if (is_file($file) && $timeDiff > 10)
                    unlink($file); //elimino el fichero
            }
            if (!file_exists($this->backupDir)) {
                mkdir($this->backupDir, 0777, true);
            }

            file_put_contents($this->backupDir . '/' . $this->backupFile, $sql, FILE_APPEND | LOCK_EX);
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }

        return true;
    }

    /*
     * Gzip backup file
     *
     * @param integer $level GZIP compression level (default: 9)
     * @return string New filename (with .gz appended) if success, or false if operation fails
     */
    protected function gzipBackupFile($level = 9)
    {
        if (!$this->gzipBackupFile) {
            return true;
        }

        $source = $this->backupDir . '/' . $this->backupFile;
        $dest =  $source . '.gz';

        $this->obfPrint('Destido del archivo comprimido en ' . $this->backupDir . '... ', 1, 0);

        $mode = 'wb' . $level;
        if ($fpOut = gzopen($dest, $mode)) {
            if ($fpIn = fopen($source, 'rb')) {
                while (!feof($fpIn)) {
                    gzwrite($fpOut, fread($fpIn, 1024 * 256));
                }
                fclose($fpIn);
            } else {
                return false;
            }
            gzclose($fpOut);
            if (!unlink($source)) {
                return false;
            }
        } else {
            return false;
        }

        // $this->obfPrint('OK');
        return $dest;
    }

    /**
     * Prints message forcing output buffer flush
     *
     */
    public function obfPrint($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1)
    {
        if (!$msg) {
            return false;
        }

        if ($msg != 'OK' and $msg != 'KO') {
            $msg = date("Y-m-d H:i:s") . ' - ' . $msg;
        }
        $output = '';

        if (php_sapi_name() != "cli") {
            $lineBreak = "<br />";
        } else {
            $lineBreak = "\n";
        }

        if ($lineBreaksBefore > 0) {
            for ($i = 1; $i <= $lineBreaksBefore; $i++) {
                $output .= $lineBreak;
            }
        }

        $output .= $msg;

        if ($lineBreaksAfter > 0) {
            for ($i = 1; $i <= $lineBreaksAfter; $i++) {
                $output .= $lineBreak;
            }
        }


        // Save output for later use
        $this->output .= str_replace('<br />', '\n', $output);

        echo $output;


        if (php_sapi_name() != "cli") {
            if (ob_get_level() > 0) {
                ob_flush();
            }
        }

        $this->output .= " ";

        flush();
    }

    /**
     * Returns full execution output
     *
     */
    public function getOutput()
    {
        return $this->output;
    }
    /**
     * Returns name of backup file
     *
     */
    public function getBackupFile()
    {
        if ($this->gzipBackupFile) {
            return $this->backupDir . '/' . $this->backupFile . '.gz';
        } else
            return $this->backupDir . '/' . $this->backupFile;
    }

    /**
     * Returns backup directory path
     *
     */
    public function getBackupDir()
    {
        return $this->backupDir;
    }
    public function getBackupDirFile()
    {
        return $this->backupFile;
    }

    /**
     * Returns array of changed tables since duration
     *
     */
    public function getChangedTables($since = '1 day')
    {
        $query = "SELECT TABLE_NAME,update_time FROM information_schema.tables WHERE table_schema='" . $this->dbName . "'";

        $result = mysqli_query($this->conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }
        if (empty($resultset))
            return false;
        $tables = [];
        for ($i = 0; $i < count($resultset); $i++) {
            if (in_array($resultset[$i]['TABLE_NAME'], IGNORE_TABLES)) // ignore this table
                continue;
            if (strtotime('-' . $since) < strtotime($resultset[$i]['update_time']))
                $tables[] = $resultset[$i]['TABLE_NAME'];
        }
        return ($tables) ? $tables : false;
    }
}


/**
 * Instantiate Backup_Database and perform backup
 */

// Report all errors
error_reporting(E_ALL);
// Set script max execution time
set_time_limit(900); // 15 minutes

if (php_sapi_name() != "cli") {
    echo '<div style="font-family: monospace;">';
}

$backupDatabase = new Backup_Database(CHARSET);

// Option-1: Backup tables already defined above
$result = $backupDatabase->backupTables(TABLES) ? 'OK' : 'KO';

// Option-2: Backup changed tables only - uncomment block below
/*
$since = '1 day';
$changed = $backupDatabase->getChangedTables($since);
if(!$changed){
  $backupDatabase->obfPrint('No tables modified since last ' . $since . '! Quitting..', 1);
  die();
}
$result = $backupDatabase->backupTables($changed) ? 'OK' : 'KO';
*/
$dire = $backupDatabase->getBackupDir();

$dirfile = $backupDatabase->getBackupDirFile();


$backupDatabase->obfPrint('Reesultado de la copia de seguridad: ' . $result, 1);

// Use $output variable for further processing, for example to send it by email
$output = $backupDatabase->getOutput();



if (php_sapi_name() != "cli") {
    echo '</div>';
}


$archivo = $dire . $dirfile . '.gz';


ob_start();

$mail = new PHPMailer(true);

$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 0;                      // Enable verbose debug output
    $mail->Debugoutput = 'html';
    if ($emisor['tipo_envio'] == 'smtp') {
        $mail->isSMTP();
    }                       // Send using SMTP
    if ($emisor['tipo_envio'] == 'mail') {
        $mail->isMAIL();
    }                      // Send using     
    $mail->Host       = $emisor['servidor'];                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $emisor['correo_ventas'];                     // SMTP username
    $mail->Password   = $emisor['contrasena'];                               // SMTP password
    $mail->SMTPSecure = $emisor['seguridad'];         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = $emisor['puerto'];                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom($emisor['correo_ventas'], 'Backup-' . $emisor['nombre_comercial']);
    $mail->setFrom($emisor['correo_ventas'], 'Backup-' . $emisor['nombre_comercial']);

    $mail->addAddress($emisor['correo_ventas']);     // Add a recipient

    $mail->addAttachment($archivo);

    // Add attachments

    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $message = "<h3>BACKUP LISTO</h3>";
    // Content
    $mail->isHTML(true);
    $mail->MsgHTML($message);                                  // Set email format to HTML
    $mail->Subject = 'Backup - ' . date("d-m-Y H:i:s");
    $mail->Body    = $message; //'<h1>Gracias</h1><br> Se le adjunta su comprobante </body></html>';
    $mail->AltBody = $message; //'<img src="https://dge4uaysoh8oy.cloudfront.net/lp/11/images/Animated%20newsletter%20with%20GIF%20banners.gif" >
    //This is the body in plain text for non-HTML mail clients';

    $mail->CharSet = 'UTF-8';
    $mail->send();
    echo '<p></p><i class="fas fa-database"></i> SE ENVIÓ UNA COPIA DE SEGURIDA AL CORREO QUE ESTABLECIÓ';
} catch (Exception $e) {
    echo "Mensaje no enviado. Error: Revise su configuración";
}
