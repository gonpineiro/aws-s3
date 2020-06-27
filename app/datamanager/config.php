<?php
use Carbon\Carbon;

// Configuration S3 Path -> S3_BUCKET/S3_PATH_DM/year/month/
$year = Carbon::now()->isoFormat('YYYY');
$month = Carbon::now()->isoFormat('MM');

//define("COPY_PATH", 'Z:/backup/Datamanager/');
define("DB_PATH", 'C:\Sistemas\SAB5\Database');
//define("PR_PATH", 'C:\Sistemas\PROFORMAS');
//define("RE_PATH", 'C:\Sistemas\RECIBOS');
define("FTP_PATH_DM", 'prueba/dm/');
define("FTP_SEND_DM", FALSE);
define("LOG_PATH_DM", 'logs/datamanager/');
define("S3_PATH_DM",  'prueba/resguardo/dm/'.$year.'/'.$month.'/');
