<?php

/**
 * Created by PhpStorm.
 * User: svenvdz
 * Date: 16-9-2016
 * Time: 10:10
 */
class connect
{

    private $conn;

    function __construct()
    {

    }

    function connect(){
        $this->conn = new mysqli("localhost","root","","zonnepanelen");
        if(mysqli_connect_errno()){
            echo "something went wrong, please try again";
        }
        return $this->conn;
    }

}