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
            $r = ['inline_keyboard' => [[['text' => "Search๐", 'callback_data' => 'search']], [['text' => "Buzz Me! ๐", 'callback_data' => 'gosh']], [['text' => "Advanced Search๐", 'callback_data' => 'searchp']], [['text' => "I want see๐", 'callback_data' => 'see']], [['text' => "Help๐ก", 'callback_data' => 'help']],]];
            telegramApi::message_inline_query($this->chat_id, "Hi ๐๐ป๐
I'm seeker 
I can search telegram content  for any thing you want ๐
Just tell me what you want and i will find it for you ๐

 It's Seeker 
 Finder Of Truth !", $r);
        } else {
            sqlMethod::sqlconnect("update user set lang=1 WHERE chat_id=$this->chat_id");
            $r = ['inline_keyboard' => [[['text' => "ุฌุณุชุฌู๐", 'callback_data' => 'search']], [['text' => "ฺฏูุด ุจู ุฒูฺฏ๐", 'callback_data' => 'gosh']], [['text' => "ุฌุณุชุฌู? ูพ?ุดุฑูุชู๐", 'callback_data' => 'searchp']], [['text' => "ู?ุฎูุงู ุจุจ?ูู๐", 'callback_data' => 'see']], [['text' => "ุฑุงูููุง๐ก", 'callback_data' => 'help']],]];
            telegramApi::message_inline_query($this->chat_id, "ุณูุงู    ๐๐
ูู ุฑุจุงุช ุณ?ฺฉุฑ (ุฌุณุชุฌูฺฏุฑ) ูุณุชู  
ูู ู?ุชููู ุชู? ูุทุงูุจ ุชูฺฏุฑุงู ุฌุณุชุฌู ฺฉูู ๐ 
 ููุท ฺฉุงู?ู ุจูู ุจฺฏ? ุฏูุจุงู ฺ? ู?ฺฏุฑุฏ? ุชุง ุจุฑุงุช ูพ?ุฏุงุด ฺฉูู ๐ค  
ูู ูุซู ?ู ฺฏูฺฏู ุจุฑุง? ูุญุชูุง? ุชูฺฏุฑุงู ูุณุชู ๐", $r);
        }
    }
}