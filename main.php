<?php
date_default_timezone_set ('America/Argentina/Buenos_Aires');
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
require 'vendor/autoload.php';
include "class/Log.php";
include "class/myphp-backup.php";
include "functions/fileExist.php";
$config = require('app/config.php');

//CONFIG S3 Aws
define("S3_BUCKET", $config['s3']['bucket']);
define("S3_REGION", $config['s3']['region']);
define("S3_VERSION", $config['s3']['version']);
define("S3_KEY", $config['s3']['key']);
define("S3_SECRET", $config['s3']['secret']);

//CONFIG MYSQL (GLOBAL)
// define("DB_USER", 'root');
// define("DB_PASSWORD", 'root');
// define("DB_NAME", 'inventario');
// define("DB_HOST", 'localhost');
define("BACKUP_DIR", 'temp'); // Comment this line to use same script's directory ('.')
define("TABLES", '*'); // Full backup
//define("TABLES", 'table1, table2, table3'); // Partial backup
define("CHARSET", 'utf8');
define("GZIP_BACKUP_FILE", true); // Set to false if you want plain SQL backup files (not gzipped)
define("DISABLE_FOREIGN_KEY_CHECKS", true); // Set to true if you are having foreign key constraint fails
define("BATCH_SIZE", 1000); // Batch size when selecting rows from database in order to not exhaust system memory
                            // Also number of rows per INSERT statement in backup file

//CONFIG FTP
define("FTP_SRV", $config['ftp']['server']);
define("FTP_USER", $config['ftp']['user']);
define("FTP_PASS", $config['ftp']['password']);

//CONFIG GLOBAL
define("TEMP_PATH", $config['global']['temp_path']);

//CONFIG DATAMANAGER
define("COPY_PATH", $config['datamanager']['copy_path']);
define("DB_PATH", $config['datamanager']['db_path']);
define("PR_PATH", $config['datamanager']['proformas_path']);
define("RE_PATH", $config['datamanager']['recibos_path']);
define("FTP_PATH_DM", $config['datamanager']['ftp_path']);
define("LOG_PATH_DM", $config['datamanager']['log_path']);
define("S3_PATH_DM", $config['datamanager']['s3_path']);

//CONFIG SPICEWORKS
define("SW_PATH", $config['spiceworks']['sw_path']);
define("FTP_PATH_SW", $config['spiceworks']['ftp_path']);
define("LOG_PATH_SW", $config['spiceworks']['log_path']);
define("S3_PATH_SW", $config['spiceworks']['s3_path']);

//CONFIG INVENTARIO
define("FTP_PATH_INV", $config['inventario']['ftp_path']);
define("LOG_PATH_INV", $config['inventario']['log_path']);
define("S3_PATH_INV", $config['inventario']['s3_path']);

//CONFIG MVP
define("FTP_PATH_MVP", $config['mvp']['ftp_path']);
define("LOG_PATH_MVP", $config['mvp']['log_path']);
define("S3_PATH_MVP", $config['mvp']['s3_path']);

//CONEXION FTP
$ftp_conn = ftp_connect(FTP_SRV) or die('Could not connect to: '.FTP_SRV);

//CONEXION A S3
$credentials = new Aws\Credentials\Credentials(S3_KEY, S3_SECRET);
$s3 = S3Client::factory([
  'credentials' => $credentials,
  'region' => S3_REGION,
  'version' => S3_VERSION
]);
fileExist(TEMP_PATH);
//CARGA DE PROGRAMAS
// include_once "app/datamanager/main.php";
// include_once "app/spiceworks/main.php";
include_once "app/inventario/main.php";
sleep(1);
include_once "app/mvp/main.php";
