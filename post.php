<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 9/21/2018
 * Time: 12:28 AM
 */

class post
{
static function addToDatabase($telegram){
    $channel_id ="@". $telegram->message->forward_from_chat->username;
    $d = $telegram->message->forward_date;
    $text=null;
    $date = date("Y-m-d", $d);
    $s = sqlMethod::sqlget("select * from seeker.channel WHERE id='$channel_id'");
    $star = $s['star'];
    $dasteh = $s['dasteh'];
    $lang=$s['lang'];

    if (isset($telegram->message->voice)) {
        $format = "voice";
        $file = $telegram->message->voice->file_id;
    } elseif (isset($telegram->message->photo)) {
        $format = "photo";
        $file = $telegram->message->photo[0]->file_id;
        $text = $telegram->message->caption;
    } elseif (isset($telegram->message->document)) {
        $text = $telegram->message->caption;
        $format = "document";
        $file = $telegram->message->document->file_id;
    } elseif (isset($telegram->message->video)) {
        $text = $telegram->message->caption;
        $format = "video";
        $file = $telegram->message->video->file_id;
    } elseif (isset($telegram->message->audio)) {
        $format = "audio";
        $file = $telegram->message->audio->file_id;
    } else {
        $text = $telegram->message->text;
        $format = "text";
        $file = null;
    }

    sqlMethod::sqlconnect("insert into seeker.posts VALUES ('$channel_id',N'$text','$file','$format',$star,$dasteh,'$date',TRUE,$lang)");
}
}