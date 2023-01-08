<?php
/**
 * Created by PhpStorm.
 * User: saleh
 * Date: 12/12/17
 * Time: 4:49 PM
 */
$servername="localhost";
$username="mychanneladminbot";
$pass="qOA4g8EBDCbXJgjdlkD4yMsw2";
$dbname="mychanneladmin";
$token="483977094:AAHg9X4NkMbbWxj_B3N85L_reu105yRVcTU";
$conn = mysqli_connect($servername, $username, $pass, $dbname) or die ('Failed to connect to database');
mysqli_query($conn, "SET NAMES 'utf8mb4'");
$channelc_rows=null;
$channel_rows=null;
$post_row=null;
$text=null;
mysqli_query($conn, "SET CHARACTER SET 'utf8mb4'");
mysqli_query($conn, "SET character_set_connection = 'utf8mb4'");
$post_result=mysqli_query($conn,"select * from posts");
while ($post_row=mysqli_fetch_array($post_result)) {
    $text = $post_row['text'];
    $channelid = $post_row['channel-id'];
    $text=strtolower($text);
    $res = mysqli_query($conn, "select * from channelc WHERE id='$channelid'");
    if ($res->num_rows > 0) {
        while ($channelc_rows=mysqli_fetch_array($res)){
            $chat_id_modir=$channelc_rows['chat_idm'];
            $channel_result=mysqli_query($conn, "select * from channel WHERE chat_idm='$chat_id_modir'");
            $channel_rows=mysqli_fetch_array($channel_result);
            $flag = true;
            while (strstr($text,"@")==true) {
                if ($flag==true) {
                    $channel_id = $channelc_rows['link'];
                    $channel_id=strtolower($channel_id);
                    $a = explode("@", $text);
                    $b = explode(" ", $a[1]);
                    if ("@" . $b[0] != $channel_id) {
                        $flag = false;
                    } else {
                        $text = str_replace("@" . $b[0], "", $text);
                    }
                }else{
                    break;
                }
            }
            if ($flag == true) {
                while (strstr($text, "telegram.me")) {
                    if ($flag==true) {
                        $text = strtolower($text);
                        $a = explode("telegram.me", $text);
                        $b = explode(" ", $a[1]);
                        $b = str_replace("/", "", $b[0]);
                        $channel_id = "Pe_Mesle_Paeiz";
                        $channel_id = strtolower($channel_id);
                        if ("@" . $b != $channel_id) {
                            $flag = false;
                        } elseif (strstr($text,"https://telegram.me/")){
                            $text = str_replace("https://telegram.me/" . $b, "", $text);
                        }elseif (strstr($text,"http://telegram.me/")) {
                            $text = str_replace("https://telegram.me/" . $b, "", $text);
                        }elseif (strstr($text,"telegram.me/")) {
                            $text = str_replace("telegram.me/" . $b, "", $text);
                        }
                    }else{
                        break;
                    }
                }
            }
            if ($flag==true){
                while (strstr($text, "t.me")) {
                    if ($flag==true) {
                        $text = strtolower($text);
                        $a = explode("t.me", $text);
                        $b = explode(" ", $a[1]);
                        $b = str_replace("/", "", $b[0]);
                        $channel_id = "@Pe_Mesle_Paeiz";
                        $channel_id = strtolower($channel_id);
                        if ("@" . $b != $channel_id) {
                            $flag = false;
                        } elseif (strstr($text,"https://t.me/")){
                            $text = str_replace("https://t.me/" . $b, "", $text);
                        }elseif (strstr($text,"http://t.me/")) {
                            $text = str_replace("https://t.me/" . $b, "", $text);
                        }elseif (strstr($text,"t.me/")) {
                            $text = str_replace("t.me/" . $b, "", $text);
                        }
                    }else{
                        break;
                    }
                }
            }
            if ($flag==true) {
                $channel_id=$channel_rows['link'];
                $format = $post_row['format'];
                $text = $text . "\n $channel_id";
                if ($format == "text") {
                    sendmessage($channel_rows['chat_idc'], $text);
                } elseif ($format == "photo") {
                    sendphoto($channel_rows['chat_idc'], $text, $post_row['file']);
                } elseif ($format == "voice") {
                    sendvoice($channel_rows['chat_idc'], $text, $post_row['file']);
                } elseif ($format == "audio") {
                    sendaudio($channel_rows['chat_idc'], $text, $post_row['file']);
                } elseif ($format == "video") {
                    sendvideo($channel_rows['chat_idc'], $text, $post_row['file']);
                } elseif ($format == "document") {
                    senddocument($channel_rows['chat_idc'], $text, $post_row['file']);
                }
            }
        }
    }
    $post_channel=$post_row['channel-id'];
    $post_text=$post_row['text'];
    mysqli_query($conn,"delete from posts WHERE `channel-id`='$post_channel' AND text='$post_text'");
}
echo "checked";
function sendmessage($chat_id, $message)
{
    global $token;
    $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
    $a=json_decode(file_get_contents($url));
    return $a;    }

function sendphoto($chat_id, $text, $file)
{
    global $token;
    $text=urlencode($text);
    $url = "https://api.telegram.org/bot$token/sendPhoto?chat_id=" . $chat_id .
        "&photo=" . $file . "&caption=" . $text;
    $a=json_decode(file_get_contents($url));
    return $a;
}

function sendvoice($chat_id, $text, $file)
{
    global $token;
    $text = urlencode($text);
    $url = "https://api.telegram.org/bot$token/sendvoice?chat_id=" . $chat_id .
        "&voice=" . $file . "&caption=" . $text;
    $a = json_decode(file_get_contents($url));
    return $a;
}
function sendaudio($chat_id, $text, $file)
{
    global $token;
    $text=urlencode($text);
    $url = "https://api.telegram.org/bot$token/sendaudio?chat_id=" . $chat_id .
        "&audio=" . $file . "&caption=" . $text;
    $a=json_decode(file_get_contents($url));
    return $a;    }

function sendvideo($chat_id, $text, $file)
{
    global $token;
    $text=urlencode($text);
    $url = "https://api.telegram.org/bot$token/sendvideo?chat_id=" . $chat_id .
        "&video=" . $file . "&caption=" . $text;
    $a=json_decode(file_get_contents($url));
    return $a;
}

function senddocument($chat_id, $text, $file)
{
    global $token;
    $text=urlencode($text);
    $url = "https://api.telegram.org/bot$token/senddocument?chat_id=" . $chat_id .
        "&document=" . $file . "&caption=" . $text;
    $a=json_decode(file_get_contents($url));
    return $a;
}
?>
