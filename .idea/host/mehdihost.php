<?php
/**
 * Created by PhpStorm.
 * User: saleh
 * Date: 1/28/18
 * Time: 6:13 PM
 */
ob_start();
set_time_limit(0);
$servername="localhost";
$username="mychanneladminbot";
$pass="qOA4g8EBDCbXJgjdlkD4yMsw2";
$dbname="momtaz";
$token="512355860:AAGNJ0D2uK1Z1xhlvyhMkjivCj9hj5Va82E";
$group="-1001252715689";
$group="-1001252715689";
$modir="338358457";
//$modir="249601731";
    $conn = mysqli_connect($servername, $username, $pass, $dbname) or die ('Failed to connect to database');
    mysqli_query($conn, "SET NAMES 'utf8mb4_persian_ci'");
    mysqli_query($conn, "SET CHARACTER SET 'utf8mb4_persian_ci'");
    mysqli_query($conn, "SET character_set_connection = 'utf8mb4_persian_ci'");
$telegram = json_decode(file_get_contents("php://input"));
            if (isset($telegram->callback_query)) {
                $chat_id = $telegram->callback_query->message->chat->id;
                $data = $telegram->callback_query->data;
                if ($data=="start"){
                    answer_inline($telegram->callback_query->id, "منوی اصلی");
                    $r = [
                        'inline_keyboard' => [
                            [
                                ['text' => "افزودن ادمین", 'callback_data' => 'add']
                            ],
                            [
                                ['text' => "حذف ادمین", 'callback_data' => 'delete']
                            ]
                        ]
                    ];
                    message_inline_query($chat_id,"چه خدمتی در نظر دارید؟",$r);
                }elseif ($data=="add"){
                    answer_inline($telegram->callback_query->id, "افزودن مدیر");
                    sqlconnect("update step set st='add' WHERE id=1");
                    sendmessage($chat_id,"لطفا یکی از پیام های مدیری که قصد اضافه کردن ان را دارید را فوروارد فرمایید.");
                }elseif ($data=="delete"){
                    answer_inline($telegram->callback_query->id, "حذف مدیر");
                    $r = ['inline_keyboard' => []];
                    $a=mysqli_query($conn,"select * from admin");
                    while ($row=mysqli_fetch_array($a)){
                        $r['inline_keyboard'][][] = ['text' => "" . $row['name'], 'callback_data' => 'dele'.$row['chat_id']];
                    }
                    $r['inline_keyboard'][][]=['text' => "منوی اصلی", 'callback_data' => 'start'];
                    message_inline_query($chat_id,"برای حذف هر کدام از ادمین های گروه کافی است تنها یک بار بر روی نام شخص مورد نظر کلیک نمایید",$r);
                }elseif (strstr($data,"dele")){
                    answer_inline($telegram->callback_query->id, "با موفقیت حذف شد");
                    $a = preg_replace("/[^0-9]/", '', $data);
                    sqlconnect("delete from admin WHERE chat_id=$a");
                    $r = ['inline_keyboard' => []];
                    $a=mysqli_query($conn,"select * from admin");
                    while ($row=mysqli_fetch_array($a)){
                        $r['inline_keyboard'][][] = ['text' => "" . $row['name'], 'callback_data' => 'dele'.$row['chat_id']];
                    }
                    $r['inline_keyboard'][][]=['text' => "منوی اصلی", 'callback_data' => 'start'];
                    editMessageReplyMarkup($telegram->callback_query->message->message_id,$chat_id,$r);
                }
            }else {
                $chat_id = $telegram->message->from->id;
                if ($telegram->message->chat->id == $group) {
                    $message_id = $telegram->message->message_id;
                    if (isset($telegram->message->left_chat_member)) {
                        delete($message_id);
                    } elseif (isset($telegram->message->new_chat_member)) {
                        delete($message_id);
                    } else {
                        if (checkadmin($chat_id) == false) {
                            delete($message_id);
                        }
                    }
                } elseif ($chat_id == $modir) {
                    $text = $telegram->message->text;
                    if ($text == "/start") {
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "افزودن ادمین", 'callback_data' => 'add']
                                ],
                                [
                                    ['text' => "حذف ادمین", 'callback_data' => 'delete']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id, "چه خدمتی در نظر دارید؟", $r);
                    } else {
                        $step = sqlget("SELECT st FROM step WHERE id=1")['st'];
                        if ($step == "add") {
                            if (isset($telegram->message->forward_from)) {
                                $admin_id = $telegram->message->forward_from->id;
                                $admin_fname = $telegram->message->forward_from->first_name;
                                if (isset($telegram->message->forward_from->last_name)) {
                                    $admin_lname = $telegram->message->forward_from->last_name;
                                    $admin_name = $admin_fname . " " . $admin_lname;
                                } else {
                                    $admin_name = $admin_fname;
                                }
                                sqlconnect("insert into admin VALUES ($admin_id,'$admin_name')");
                                $r = [
                                    'inline_keyboard' => [
                                        [
                                            ['text' => "منوی اصلی", 'callback_data' => 'start']
                                        ]
                                    ]
                                ];
                                message_inline_query($chat_id, "$admin_name با موفقیت به ادمین های گروه اضافه شد و از این پس میتواند به گروه پیام ارسال کند.", $r);
                            } else {
                                sendmessage($chat_id, "لطفا پیام کاربر مورد نظر را به صورت درست فوروارد فرمایید");
                            }
                        }
                    }
                }
            }
function delete($message){
    global $group;
    $url="https://api.telegram.org/bot341659197:AAEH5EbNZAx-pEmFrlNnczRVLjsCUS9H7Xg/deleteMessage?chat_id=$group&message_id=$message";
    file_get_contents($url);
}
function checkadmin($chat_id)
{
    global $modir;
    $sql = "SELECT chat_id FROM admin WHERE chat_id=$chat_id";
    $result = sqlget($sql);
    if ($result or $chat_id==$modir) {
        return true;
    } else {
        return false;
    }
}
function sendmessage($chat_id, $message)
{
    global $token;
    $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
    $a=json_decode(file_get_contents($url));
    return $a;    }
function editMessageReplyMarkup($m_id, $chat_id, $re){
    $e = json_encode($re, true);
    global $token;
    $url = "https://api.telegram.org/bot$token/editMessageReplyMarkup?chat_id=$chat_id&message_id=$m_id&reply_markup=$e";
    file_get_contents($url);
}

function answer_inline($id, $text)
{
    $text = urlencode($text);
    global $token;
    $url = "https://api.telegram.org/bot$token/answerCallbackQuery?callback_query_id=$id&text=$text&show_alert=false";
    file_get_contents($url);
}
function sqlget($sql)
{
    global $conn;
    $result = mysqli_query($conn, $sql);
    $a = mysqli_fetch_array($result);
    return $a;
}

function message_inline_query($chat_id, $text, $reply_markup)
{
    global $token;
    $encodedMarkup = json_encode($reply_markup, true);
    $text=urlencode($text);
    $encodedMarkup=urlencode($encodedMarkup);
    $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chat_id&text=$text&reply_markup=" . $encodedMarkup;
    file_get_contents($url);
}

function getupdates()
{
    global $telegram;
    $last = sqlget("SELECT last FROM lui WHERE id=1")['last'];
    $last++;
    global $token;
    $url = 'https://api.telegram.org/bot' . $token . '/getupdates?offset=' . $last;
    $telegram = json_decode(file_get_contents($url));
    $count = count($telegram->result);
    if ($count > 0) {
        $l = $telegram->result[$count - 1]->update_id;
        $sql = "UPDATE lui SET last=" . $l . " WHERE id=1;";
        sqlconnect($sql);
    }
    return $telegram;
}

function sqlconnect($sql)
{
    global $conn;
    mysqli_query($conn, $sql);
}
