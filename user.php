<?php
/**
 * Created by PhpStorm.
 * User: saleh
 * Date: 9/16/18
 * Time: 8:16 PM
 */

class user
{
    private $chat_id;

    function __construct($chat_id)
    {
        $this->chat_id = json_decode($chat_id);

    }

    function addToDatabase()
    {
        sqlMethod::sqlconnect("insert into user VALUE ($this->chat_id,NULL ,NULL )");
    }

    function userexist()
    {
        $sql = "SELECT chat_id FROM user WHERE chat_id=$this->chat_id";
        $result = sqlMethod::sqlget($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function getstep()
    {
        $step = sqlMethod::sqlget("select step from user WHERE chat_id=$this->chat_id")['step'];
        return $step;
    }

    function getlanguage()
    {
        $lang = sqlMethod::sqlget("select lang from user WHERE chat_id=$this->chat_id")['lang'];
        return $lang;
    }

    function setstep($step)
    {
        if ($step != "null") {
            sqlMethod::sqlconnect("update user set step='$step' WHERE chat_id=$this->chat_id");
        } else {
            sqlMethod::sqlconnect("update user set step=NULL WHERE chat_id=$this->chat_id");
        }
    }

    static function get_user_count()
    {
        $result = sqlMethod::sqlconnect("SELECT chat_id FROM user");
        $a = $result->num_rows;
        return $a;
    }

    function setlang($lang)
    {
        sqlMethod::sqlconnect("update user set lang=$lang WHERE chat_id=$this->chat_id");
        if ($lang == 2) {
            $r = ['inline_keyboard' => [[['text' => "Search🔎", 'callback_data' => 'search']], [['text' => "Buzz Me! 🔔", 'callback_data' => 'gosh']], [['text' => "Advanced Search🔎", 'callback_data' => 'searchp']], [['text' => "I want see👀", 'callback_data' => 'see']], [['text' => "Help💡", 'callback_data' => 'help']],]];
            telegramApi::message_inline_query($this->chat_id, "Hi 🖐🏻😊
I'm seeker 
I can search telegram content  for any thing you want 🔎
Just tell me what you want and i will find it for you 😉

 It's Seeker 
 Finder Of Truth !", $r);
        } else {
            sqlMethod::sqlconnect("update user set lang=1 WHERE chat_id=$this->chat_id");
            $r = ['inline_keyboard' => [[['text' => "جستجو🔎", 'callback_data' => 'search']], [['text' => "گوش به زنگ🔔", 'callback_data' => 'gosh']], [['text' => "جستجوی پیشرفته🔎", 'callback_data' => 'searchp']], [['text' => "میخوام ببینم👀", 'callback_data' => 'see']], [['text' => "راهنما💡", 'callback_data' => 'help']],]];
            telegramApi::message_inline_query($this->chat_id, "سلام    😊🖐
من ربات سیکر (جستجوگر) هستم  
من میتونم توی مطالب تلگرام جستجو کنم 🔎 
 فقط کافیه بهم بگی دنبال چی میگردی تا برات پیداش کنم 🤔  
من مثل یه گوگل برای محتوای تلگرام هستم 😉", $r);
        }
    }
}