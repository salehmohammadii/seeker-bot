<?php
ini_set('memory_limit', '-1');

$id=0;
require '/home/saleh/MadelineProtoBot/MadelineProto/vendor/autoload.php';
$arr = [
    'MTPROTO_SETTINGS' => ['app_info' => ['api_id' => '146424', 'api_hash' => '1d6aed4703fca6f41c52a2a415f5a473']]

];
if (file_exists("session.madeline")) {
    $MadelineProto = \danog\MadelineProto\Serialization::deserialize('session.madeline');
} else {
    $MadelineProto = new \danog\MadelineProto\API($arr['MTPROTO_SETTINGS']);
    $MadelineProto->phone_login('+989304176618');
    sleep(70);
    $myfile = fopen("update.txt", "r") or die("Unable to open file!");
    $authorization = $MadelineProto->complete_phone_login(fread($myfile, filesize("update.txt")));
    if ($authorization['_'] === 'account.noPassword') {
        logger('a');
        throw new \danog\MadelineProto\Exception('2FA is enabled but no password is set!');
    }
    if ($authorization['_'] === 'account.password') {
        logger('b');
        $authorization = $MadelineProto->complete_2fa_login(readline('Please enter your password (hint ' . $authorization['hint'] . '): '));
    }
    if ($authorization['_'] === 'account.needSignup') {
        logger('c');
        $authorization = $MadelineProto->complete_signup(readline('Please enter your first name: '), readline('Please enter your last name (can be empty): '));
    }
    $MadelineProto->session = 'session.madeline';
    $MadelineProto->serialize('session.madeline');
}
try{
    $result=sqlMethod::sqlconnect("select * from channel");
    while ($row=mysqli_fetch_array($result)){
        $id=$row['id'];
        $Update1 = $MadelineProto->channels->joinChannel(['channel' => $id, ]);
        sqlMethod::sqlconnect("update channel set isnew=FALSE WHERE id='$id'");
    }
    $offset=sqlMethod::sqlget("select `group` from channel WHERE id='a'")['dasteh'];
    $offset++;
    sqlMethod::sqlconnect("update channel set `group`=`group`+1 WHERE id='a'");
    $updates = $MadelineProto->API->get_updates(['offset' => $offset, 'limit' => 100, 'timeout' => 1]);
    foreach ($updates as $update) {
        $offset = $update['update_id'];
        sqlMethod::sqlconnect("update channel set `group`=$offset WHERE id='a'");
        if ($update['update']['_']=="updateNewChannelMessage") {
            if ($update['update']['message']['_'] == "message") {
                if (array_key_exists("media", $update['update']['message'])) {
                    $me[] = $update['update']['message']['id'];
                    $id =  $update['update']['message']['to_id']['channel_id'];
                    $Updates = $MadelineProto->messages->forwardMessages(['from_peer' => "$id", 'id' => $me, 'to_peer' => "@myseekerbot",]);
                    $me = null;
                } else {
                    $id =$update['update']['message']['to_id']['channel_id'];
                    $channel=sqlMethod::sqlget("select * from channel WHERE id='$id'");
                    $date=$update['update']['message']['date'];
                    sqlMethod::sqlconnect( "insert into posts VALUE ('$id','" . $update['update']['message']['message'] . "',Null,'text',$channel[2],$channel[1],$date,true,$channel[4]");
                }
            }
        }
    }

} catch
(Exception $e) {
    $c = $e->getMessage();
    if (strstr($c, "Flood_wait")) {
        $id = preg_replace("/[^0-9]/", '', $c);
        sleep($id);
    }elseif (strstr($c,"hash")){
        sqlmethod::sqlconnect("delete from channel WHERE id='$id'");
    }else{
        $token="341659197:AAEH5EbNZAx-pEmFrlNnczRVLjsCUS9H7Xg";
        $chat_id=249601731;
        $message="robot rideh";
        $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
        $a=json_decode(file_get_contents($url));
    }
}