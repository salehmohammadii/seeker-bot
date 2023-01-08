<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 9/21/2018
 * Time: 12:01 AM
 */

class channel
{


    static function deleteChannel($id)
    {
        sqlMethod::sqlconnect("delete from channel WHERE id='$id'");
    }

    static function AddToDatabase($data)
    {
        $a = strpos($data, "@");
        $b = strlen($data);
        $d = $a - 4;
        $id = substr($data, $a, $b);
        $dasteh = substr($data, 4, $d);
        sqlMethod::sqlconnect("insert into channel VALUE ('$id',$dasteh,0,NULL ,1)");

    }

    static function setlang($data)
    {
        $lang = substr($data, 4, 1);
        $le = strlen($data);
        $d = $le - 4;
        $id = substr($data, 5, $d);
        sqlMethod::sqlconnect("update channel set lang=$lang WHERE id='$id'");
    }

    static function sendAllchannel($modir)
    {
        $a = sqlMethod::sqlconnect("SELECT * FROM channel");
        $q = "";
        $count = 0;
        while ($result = mysqli_fetch_array($a)) {
            $y = $result['id'] . "\n";
            $q = $q . $y;
            $count++;
            if ($count == 15) {
                telegramApi::sendmessage($modir, $q);
                $q = null;
            }
        }
        telegramApi::sendmessage($modir, $q);

    }
}