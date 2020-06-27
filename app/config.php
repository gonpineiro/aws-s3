<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

require 'vendor/autoload.php';
//CONFIG S3 Aws
define("S3_BUCKET", 's3.sab5.ar');
define("S3_REGION", 'sa-east-1');
define("S3_VERSION", 'latest');
define("S3_KEY", 'AKIAYFGP56IFGY22ZX7V');
define("S3_SECRET", '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');

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
define("FTP_SRV", 'localhost');
define("FTP_USER", 'gonzalo');
define("FTP_PASS", '1');

//CONFIG GLOBAL
define("TEMP_PATH", 'temp/');

/////////////////////////////////////////////////////////////////////////////////
//CONEXION FTP
//$ftp_conn = ftp_connect(FTP_SRV) or die('Could not connect to: '.FTP_SRV);

//CONEXION A S3
$credentials = new Aws\Credentials\Credentials(S3_KEY, S3_SECRET);
$s3 = S3Client::factory([
  'credentials' => $credentials,
  'region' => S3_REGION,
  'version' => S3_VERSION
]);
