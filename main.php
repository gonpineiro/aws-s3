<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
require 'vendor/autoload.php';
include "class/Log.php";
include "class/myphp-backup.php";
$config = require('app/config.php');

//CONFIG S3 Aws
define("S3_BUCKET", $config['s3']['bucket']);
define("S3_REGION", $config['s3']['region']);
define("S3_VERSION", $config['s3']['version']);
define("S3_KEY", $config['s3']['key']);
define("S3_SECRET", $config['s3']['secret']);

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
define("FTP_LOG_PATH_DM", $config['datamanager']['ftp_log_path']);

//CONFIG SPICEWORKS
define("SW_PATH", $config['spiceworks']['sw_path']);
define("FTP_PATH_SW", $config['spiceworks']['ftp_path']);

//CONFIG INVENTARIO
define("FTP_PATH_INV", $config['inventario']['ftp_path']);

//CONEXION FTP
$ftp_conn = ftp_connect(FTP_SRV) or die('Could not connect to: '.FTP_SRV);

//CONEXION A S3
$credentials = new Aws\Credentials\Credentials(S3_KEY, S3_SECRET);
$s3 = S3Client::factory([
  'credentials' => $credentials,
  'region' => S3_REGION,
  'version' => S3_VERSION
]);

//CARGA DE PROGRAMAS
include_once "app/datamanager/main.php";
include_once "app/spiceworks/main.php";
include_once "app/inventario/main.php";
