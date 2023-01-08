<?php
/**
 * Created by PhpStorm.
 * User: saleh
 * Date: 9/16/18
 * Time: 6:39 PM
 */
$token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";

class telegramApi
{

    /**
     * @param mixed $token
     */

    public static function message_inline_query($chat_id, $text, $reply_markup)
    {
        $token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";
        $encodedMarkup = json_encode($reply_markup, true);
        $text = urlencode($text);
        $encodedMarkup = urlencode($encodedMarkup);
        $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chat_id&text=$text&reply_markup=" . $encodedMarkup;
        file_get_contents($url);
    }

    public static function sendmessage($chat_id, $message)
    {
        $token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";
        $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
        $a = json_decode(file_get_contents($url));
        return $a;
    }

    public static function sendphoto($chat_id, $text, $file)
    {
        $token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";
        if ($text == null or $text == "") {
            $url = "https://api.telegram.org/bot$token/sendPhoto?chat_id=" . $chat_id . "&photo=" . $file;
        } else {
            $text = urlencode($text);
            $url = "https://api.telegram.org/bot$token/sendPhoto?chat_id=" . $chat_id . "&photo=" . $file . "&caption=" . $text;
        }
        $a = json_decode(file_get_contents($url));
        return $a;
    }

    public static function sendvoice($chat_id, $text, $file)
    {
        $token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";
        $text = urlencode($text);
        $url = "https://api.telegram.org/bot$token/sendvoice?chat_id=" . $chat_id .
            "&voice=" . $file . "&caption=" . $text;
        $a = json_decode(file_get_contents($url));
        return $a;
    }

    public static function sendaudio($chat_id, $text, $file)
    {
        $token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";
        $text = urlencode($text);
        $url = "https://api.telegram.org/bot$token/sendaudio?chat_id=" . $chat_id .
            "&audio=" . $file . "&caption=" . $text;
        $a = json_decode(file_get_contents($url));
        return $a;
    }

    public static function sendvideo($chat_id, $text, $file)
    {
        $token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";
        $text = urlencode($text);
        $url = "https://api.telegram.org/bot$token/sendvideo?chat_id=" . $chat_id .
            "&video=" . $file . "&caption=" . $text;
        $a = json_decode(file_get_contents($url));
        return $a;
    }

    public static function senddocument($chat_id, $text, $file)
    {
        $token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";
        $text = urlencode($text);
        $url = "https://api.telegram.org/bot$token/senddocument?chat_id=" . $chat_id .
            "&document=" . $file . "&caption=" . $text;
        $a = json_decode(file_get_contents($url));
        return $a;
    }

    public static function answer_inline($id, $text)
    {
        $text = urlencode($text);
        $token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";
        $url = "https://api.telegram.org/bot$token/answerCallbackQuery?callback_query_id=$id&text=$text&show_alert=false";
        file_get_contents($url);
    }

    public static function forward($chat_id, $from, $m)
    {
        $token = "246098404:AAF5_RmOvkF1TRVZJOSTMyzpIRsWT4XYiRU";
        $url = "https://api.telegram.org/bot$token/forwardMessage?chat_id=$chat_id&from_chat_id=$from&show_alertmessage_id=$m";
        return file_get_contents($url);
    }


    public static function send($chat_id, $result, $page, $tok, $text, $text1, $text2)
    {
        global $lang;
        $c = 0;
        $q = "";
        $d = (($page - 1) * 10);
        $e = $result->num_rows;
        global $modir;
        if ($result->num_rows == 0 and $chat_id == $modir) {
            self::sendmessage($chat_id, "تا کنون کسی اقدام به سرچ نکرده");
        } else {
            if ($chat_id == $modir and sqlMethod::sqlget("select step from user WHERE chat_id=$modir")['step'] == "max") {
                while ($row = mysqli_fetch_array($result)) {
                    $c++;
                    if ($c <= $page * 10 and $c > ($page - 1) * 10) {
                        $d++;
                        $z = $row['count'] . "\n";
                        $y = $row['text'];
                        $m = $z . $y . "\n________________________________\n";
                        $q = $q . $m;
                    } elseif ($c > $page * 10) {
                        break;
                    }
                }
                $reply_markup = [
                    'inline_keyboard' => [
                        [
                            ['text' => "خانه", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, $q, $reply_markup);
            } elseif ($chat_id == $modir) {
                while ($row = mysqli_fetch_array($result)) {
                    $c++;
                    if ($c <= $page * 20 and $c > ($page - 1) * 20) {
                        $d++;
                        $z = $row['id'] . "\n";
                        $x = $row['start'] . "\n";
                        $y = $row['lastvis'] . "";
                        $m = $z . $x . $y . "\n________________________________\n";
                        $q = $q . $m;
                    } elseif ($c > $page * 10) {
                        break;
                    }
                }
                $z = $page + 1;
                $reply_markup = [
                    'inline_keyboard' => [
                        [
                            ['text' => "صفحه بعد " . $row['like1'] . " ", 'callback_data' => 'list' . $z . ' ']
                        ],
                        [
                            ['text' => "خانه", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, $q, $reply_markup);
            }
        }
        if ($chat_id != $modir) {
            if ($result->num_rows == 0) {
                if ($lang == 1) {
                    telegramApi::sendmessage($chat_id, "نتیجه ای یافت نشد");
                } else {
                    telegramApi::sendmessage($chat_id, "No result Found");
                }
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    $c++;

                    if ($c <= $page * 10 and $c > ($page - 1) * 10) {
                        $d++;
                        $t = str_replace(["$text", "$text1", $text2], ["#$text", "#$text1", "#$text2",], $row['text']);
                        if ($row['format'] == "text") {
                            $a = telegramApi::sendmessage($chat_id, $t);
                        } else {
                            if ($row['format'] == "voice") {
                                $a = telegramApi::sendvoice($chat_id, $t, $row['file']);
                            } elseif ($row['format'] == "video") {
                                $a = telegramApi::sendvideo($chat_id, $t, $row['file']);
                            } elseif ($row['format'] == "photo") {
                                $a = telegramApi::sendphoto($chat_id, $t, $row['file']);
                            } elseif ($row['format'] == "audio") {
                                $a = telegramApi::sendaudio($chat_id, $t, $row['file']);
                            } elseif ($row['format'] == "document") {
                                $a = telegramApi::senddocument($chat_id, $t, $row['file']);
                            }
                        }
                        if ($a->ok == false or is_null($a) == true) {
                            $c--;
                            $e--;
                            $d--;
                        }
                    } elseif ($c > $page * 10) {
                        break;
                    }
                }
                if ($result->num_rows <= $page * 10) {
                    $a = ($page - 1) * 10;
                    if ($lang == 1) {
                        $text = "نتیجه $a تا $e \nتعداد کل نتایج برابر است با: $e";
                        $r = [
                            'inline_keyboard' =>
                                [
                                    [
                                        ['text' => "خانه", 'callback_data' => 'start']
                                    ]
                                ]
                        ];
                    } else {
                        $text = "Result(s) $a to $e
Total Result(s): $e";
                        $r = [
                            'inline_keyboard' =>
                                [
                                    [
                                        ['text' => "Home🏡", 'callback_data' => 'start']
                                    ]
                                ]
                        ];
                    }
                    telegramApi::message_inline_query($chat_id, $text, $r);
                } else {
                    if ($c >= $page * 10 or $c == $result->num_rows) {
                        $a = ($page - 1) * 10;
                        $a = $a + 1;
                        if ($lang == 1) {
                            $text = "نتیجه از $a تا $d\nتعداد کل نتایج: $e \n برای دیدن نتایج بیشتر صفحه بعدی را انتخاب کنید ";
                        } else {
                            $text = "Result(s) $a to $d
Total Result(s): $e
Select next page to find more results !";
                        }
                        if ($result->num_rows > ($page * 10)) {
                            $a1 = $page + 1;
                            if ($lang == 1) {
                                $r = [
                                    'inline_keyboard' => [
                                        [
                                            ['text' => "صفحه بعدی", 'callback_data' => '' . $a1 . 'to' . $tok . '']
                                        ],
                                        [
                                            ['text' => "خانه", 'callback_data' => 'start']
                                        ]
                                    ]
                                ];
                            } else {
                                $r = [
                                    'inline_keyboard' => [
                                        [
                                            ['text' => "Next Page", 'callback_data' => '' . $a1 . 'to' . $tok . '']
                                        ],
                                        [
                                            ['text' => "Home🏡", 'callback_data' => 'start']
                                        ]
                                    ]
                                ];
                            }
                        } else {
                            if ($lang == 1) {
                                $r = [
                                    'inline_keyboard' =>
                                        [
                                            ['text' => "خانه 🏡", 'callback_data' => 'start']
                                        ]
                                ];
                            } else {
                                $r = [
                                    'inline_keyboard' =>
                                        [
                                            ['text' => "Home🏡", 'callback_data' => 'start']
                                        ]
                                ];
                            }
                        }
                        telegramApi::message_inline_query($chat_id, $text, $r);
                    }
                }
            }
        }
    }
}