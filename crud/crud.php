<?php

/**
 * Created by PhpStorm.
 * User: svenvdz
 * Date: 16-9-2016
 * Time: 10:10
 */
class crud
{

    private $conn;

    function __construct()
    {
        require_once dirname(__FILE__)."connect.php";
        $connect = new connect();
        $this->conn = $connect->connect();
    }

    public function getDay(){
        $timestamp = date('Y-m-d G:i:s');
        echo $timestamp;
    }

}