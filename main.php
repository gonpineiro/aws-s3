<?php

require 'app/config.php';
include "class/Log.php";
include "class/myphp-backup.php";
include "functions/fileExist.php";

fileExist(TEMP_PATH);

//CARGA DE PROGRAMAS
include_once "app/datamanager/main.php";
//include_once "app/spiceworks/main.php";
//include_once "app/inventario/main.php";
//sleep(1);
//include_once "app/mvp/main.php";
unlink(TEMP_PATH.$name_zip);
