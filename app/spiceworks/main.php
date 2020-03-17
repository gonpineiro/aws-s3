<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Carbon\Carbon;
require 'config.php';

//MODULOS
require 'app/spiceworks/databases.php';
$conn_name = Carbon::now()->isoFormat('DD_MM_YYYY-hh_mm_ss')."_SW";
fileExist(LOG_PATH_SW);
$log = new Log($conn_name, LOG_PATH_SW);
// Initialize archive object


databasesSw();
$log->insert('[BK] - FIN DEL MODULO SPICEWORKS');
