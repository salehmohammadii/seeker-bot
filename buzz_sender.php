<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 9/24/2018
 * Time: 12:17 PM
 */
$result=sqlMethod::sqlconnect("select * from buzz WHERE text IS NOT NULL ");
while ($buzz = mysqli_fetch_array($result)) {
$lang=$buzz['lang'];
$dasteh=$buzz['dasteh'];
$text=$buzz['text'];
if ($dasteh == 1) {
    $format = "text";
} elseif ($dasteh == 2) {
    $format = "voice or audio";
} elseif ($dasteh == 4) {
    $format = "video";
} elseif ($dasteh == 5) {
    $format = "Document";
} elseif ($dasteh == 6) {
    $format = "*";
} elseif ($dasteh == 3) {
    $format = "photo";
}
$m = explode(" ", $buzz['text']);
    $tok=null;
    $result = sqlMethod::sqlconnect( "select DISTINCT * from seeker.posts WHERE and format='$format' and AND isnew=true and match(text) AGAINST('$text')  ORDER BY date DESC  ");
    telegramApi::send($this->chat_id, $result, 1, $tok, $this->text, null, null);
}
sqlMethod::sqlconnect("update posts set isnew=FALSE");
?>