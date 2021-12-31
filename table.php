<?php
require_once "index.php";
class database extends mysqli{
    public function crtable($mysqli){
        $res = $mysqli->multi_query("
            CREATE TABLE `user` ( 
	        `id` BIGINT(25) NOT NULL, 
	        `step` VARCHAR(200) DEFAULT NULL ,
	        `phone` VARCHAR(20) DEFAULT NULL ,
	        `card-pen` VARCHAR(100) NOT NULL ,
	        PRIMARY KEY (`id`)) CHARSET=utf8mb4;

            CREATE TABLE `pays` (
	        `id` BIGINT(25) NOT NULL,
            `step` VARCHAR(100) NOT NULL,
	        `code` BIGINT(25) NOT NULL,
	        `ip` VARCHAR(200) NOT NULL DEFAULT 0,
	        `paycode` VARCHAR(100) NOT NULL DEFAULT 0,
	        `phone` VARCHAR(20) NOT NULL DEFAULT 0,
	        `amount` BIGINT(25) NOT NULL,
	        `time` VARCHAR(100) DEFAULT NULL,
	        `date` VARCHAR(100) DEFAULT NULL,
            PRIMARY KEY (`code`)) CHARSET=utf8mb4;
	        
	        CREATE TABLE `payment` (
	        `file` VARCHAR(100) NOT NULL,
            `code` VARCHAR(100) NOT NULL,
            `name` VARCHAR(100) NOT NULL,
            `off` BIGINT(25) NOT NULL,
            PRIMARY KEY (`file`)) CHARSET=utf8mb4;
    
	        CREATE TABLE `pay` (
  	        `id` BIGINT(25) NOT NULL,
	        `amount` BIGINT(20) NOT NULL,
	        `des` TEXT NOT NULL,
	        `code` BIGINT(25) NOT NULL,
            PRIMARY KEY (`code`)) CHARSET=utf8mb4;

	        CREATE TABLE `setting` (
	        `id` BIGINT(25) NOT NULL, 
  	        `smsuser` VARCHAR(100) NOT NULL,
	        `smspass` VARCHAR(100) NOT NULL,
	        `smsid` VARCHAR(100) NOT NULL,
	        `sms` INT(20) NOT NULL,
            PRIMARY KEY (`id`)) CHARSET=utf8mb4;

            INSERT INTO `payment`(`file`, `code`, `name`, `off`) VALUES ('zarinpal','0','زرین پال','0'),('idpay','0','ایدی پی','0'),('nextpay','0','نکست پی','0');

            INSERT INTO `setting`(`id`, `smsuser`, `smspass`, `smsid`, `sms`) VALUES ('1','0','0','0','0');
        ");
        if ($res == 1) {
            return "OK";
        }
        return "false";
    }
}
$creat = new database();
$mysqli = new mysqli($host, $user_database, $pass_database, $Name_database);
$mysqli->set_charset("utf8mb4");
$result = $creat->crtable($mysqli);
if ($result == "OK"){
    exit("<h1 style='text-align: center;margin-top:30px'>ربات فعال است</a></h1>");
}