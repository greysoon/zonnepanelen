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
        include_once dirname(__FILE__)."/connect.php";
        $connect = new connect();
        $this->conn = $connect->connect();
    }

    public function getDay(){
        //$timestamp = date('Y-m-d G:i:s');
        $timestamp = "2016-09-10 17:40:00";
        $query = $this->conn->prepare("SELECT ETotalToday FROM daydata WHERE DateTime = ?");
        $query->bind_param("s",$timestamp);
        if($query->execute()){
            $query->bind_result($ETotalToday);
            $query->fetch();
            echo "today $ETotalToday";
        }else{
            echo "an error occurred";
        }
        $query->close();
    }

    public function getWeek(){
        /*$day = date("w");
        $week_start = date("Y-m-d G:i:s'", strtotime('-'.($day).'days'));
        $week_end = date('Y-m-d G:i:s', strtotime('+'.(6-$day).'days'));*/
        $week_start = "2016-09-10 19:35:00";
        $week_end = "2016-09-10 19:40:00";

        $query = $this->conn->prepare("SELECT Sum(ETotalToday) as opbrengst FROM daydata WHERE DateTime BETWEEN ? AND ?");
        $query->bind_param("ss", $week_start, $week_end);
        if($query->execute()){
            $query->bind_result($opbrengst);
            $query->fetch();
            echo "opbrengst: " . $opbrengst;
        }else{
            echo "woops";
        }
        $query->close();
    }

    public function getMonth(){
        $month_start = date('Y-m-01 00:00:00', strtotime("this month"));
        $month_end = date('Y-m-t 00:00:00', strtotime("this month"));
        echo $month_start." ".$month_end;

        $query = $this->conn->prepare("SELECT Sum(ETotalToday) as opbrengst FROM daydata WHERE DateTime BETWEEN ? AND ?");
        $query->bind_param("ss", $month_start, $month_end);
        if($query->execute()){
            $query->bind_result($opbrengst);
            $query->fetch();
            echo "opbrengst: " . $opbrengst;
        }else{
            echo "woops";
        }
    }

    public function getTotal(){
        $query = $this->conn->prepare("SELECT Sum(ETotalToday) as opbrengst FROM daydata");
        if($query->execute()){
            $query->bind_result($opbrengst);
            $query->fetch();
            echo $opbrengst;
        }else{
            echo "woops";
        }
    }
    
    public function getWeekJSON($time, $week_start, $week_end){
        $response = array();
        $query = $this->conn->prepare("SELECT ETotalToday, DateTime FROM daydata WHERE TIME(DateTime)= ? AND DateTime BETWEEN ? AND ?");
        $query->bind_param("sss", $time, $week_start, $week_end);
        $query->execute();
        $response["opbrengst"] = $this->get_result($query);
        $query->close();
        echo json_encode($response);
    }

    public function getMonthJSON($time, $month_start, $month_end){
        $response = array();
        $query = $this->conn->prepare("SELECT ETotalToday, DateTime FROM daydata WHERE TIME(DateTime)= ? AND DateTime BETWEEN ? AND DateTime AND ?");
        $query->bind_param("sss", $time, $month_start, $month_end);
        $query->execute();
        $response["opbrengst"] = $this->get_result($query);
        $query->close();
        echo json_encode($response);
    }

    public function getTotalJSON($time){
        $response = array();
        $query = $this->conn->prepare("SELECT ETotalToday, DateTime FROM daydata WHERE TIME(DateTime) = ?");
        $query->bind_param("s",$time);
        $query->execute();
        $response["opbrengst"] = $this->get_result($query);
        $query->close();
        echo json_encode($response);
    }

    function get_result( $Statement ) {
        $RESULT = array();
        $Statement->store_result();
        for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
            $Metadata = $Statement->result_metadata();
            $PARAMS = array();
            while ( $Field = $Metadata->fetch_field() ) {
                $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
            }
            call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
            $Statement->fetch();
        }
        return $RESULT;
    }
}