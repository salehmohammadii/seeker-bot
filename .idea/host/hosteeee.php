<?php
ini_set('memory_limit', '-1');
$servername="localhost";
$username="mychanneladminbot";
$pass="qOA4g8EBDCbXJgjdlkD4yMsw2";
$dbname="mychanneladmin";
$id=0;
$conn = mysqli_connect($servername, $username, $pass, $dbname);
mysqli_query($conn, "SET NAMES 'utf8mb4'");
mysqli_query($conn, "SET CHARACTER SET 'utf8mb4'");
mysqli_query($conn, "SET character_set_connection = 'utf8mb4'");
require '/home/mychanneladminbot/public_html/MadelineProtoBot/MadelineProto/vendor/autoload.php';
$arr = [
    'MTPROTO_SETTINGS' => ['app_info' => ['api_id' => '84403', 'api_hash' => '485ba9e56f5d549078730fb5452f15a3']]

];
if (file_exists("/home/mychanneladminbot/public_html/session.madeline")) {
    $MadelineProto = \danog\MadelineProto\Serialization::deserialize('/home/mychanneladminbot/public_html/session.madeline');
} else {
    $MadelineProto = new \danog\MadelineProto\API($arr['MTPROTO_SETTINGS']);
    $MadelineProto->phone_login('+989304176618');
    sleep(70);
    $myfile = fopen("/home/mychanneladminbot/public_html/update.txt", "r") or die("Unable to open file!");
    $authorization = $MadelineProto->complete_phone_login(fread($myfile, filesize("/home/mychanneladminbot/public_html/update.txt")));
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
    $MadelineProto->session = '/home/mychanneladminbot/public_html/session.madeline';
    $MadelineProto->serialize('/home/mychanneladminbot/public_html/session.madeline');
}
try{
$result=mysqli_query($conn,"select * from channelc WHERE isnew=TRUE AND islink=0");
while ($row=mysqli_fetch_array($result)){
    $id=$row['link'];
    $Updates = $MadelineProto->channels->joinChannel(['channel' => $id, ]);
    mysqli_query($conn,"update channelc set isnew=FALSE WHERE link='$id'");
}
$offs=mysqli_query($conn,"select * from lui WHERE id=2");
    $offset=mysqli_fetch_array($offs)['last'];
$offset++;
    $updates = $MadelineProto->API->get_updates(['offset' => $offset, 'limit' => 100, 'timeout' => 1]);
    foreach ($updates as $update) {
echo "پیام بووووووووووووووووووووووووووووووووووووووووووووووووووووووووووووود";
        $offset = $update['update_id'];
        mysqli_query($conn,"update lui set last=$offset WHERE id=2");
if ($update['update']['_']=="updateNewChannelMessage") {
    if ($update['update']['message']['_'] == "message") {
        if (array_key_exists("media", $update['update']['message'])) {
            $me[] = $update['update']['message']['id'];
            $id = "-100" . $update['update']['message']['to_id']['channel_id'];
            $Updates = $MadelineProto->messages->forwardMessages(['from_peer' => "$id", 'id' => $me, 'to_peer' => "@mychanneladminbot",]);
            $me = null;
        } else {
            $id = "-100" . $update['update']['message']['to_id']['channel_id'];
            mysqli_query($conn, "insert into posts VALUE ('$id','" . $update['update']['message']['message'] . "',Null,'text')");
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
        mysqli_query($conn,"delete from channelc WHERE id='$id'");
    }else{
        $token="341659197:AAEH5EbNZAx-pEmFrlNnczRVLjsCUS9H7Xg";
$chat_id=249601731;
$message=$c."                            ";
        $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
        $a=json_decode(file_get_contents($url));
    }
}
