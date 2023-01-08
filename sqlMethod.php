<?php
/**
 * Created by PhpStorm.
 * User: saleh
 * Date: 9/16/18
 * Time: 6:50 PM
 */

class sqlMethod
{

    private static function getconnection()
    {
        $servername = "localhost";
        $username = "root";
        $pass = null;
        $dbname = "myseeker";
        $conn = mysqli_connect($servername, $username, $pass, $dbname) or die ('Failed to connect to database');
        mysqli_query($conn, "SET NAMES 'utf8mb4'");
        mysqli_query($conn, "SET CHARACTER SET 'utf8mb4'");
        mysqli_query($conn, "SET character_set_connection = 'utf8mb4'");
        return $conn;
    }

    public static function sqlconnect($sql)
    {
        $conn = self::getconnection();
        $result = mysqli_query($conn, $sql);
        return $result;
    }


    public static function sqlget($sql)
    {
        $conn = self::getconnection();
        $result = mysqli_query($conn, $sql);
        $a = mysqli_fetch_array($result);
        return $a;
    }

    public static function checksearch($text)
    {
        $sql = "SELECT text FROM search WHERE text='$text'";
        $result = sqlMethod::sqlget($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}