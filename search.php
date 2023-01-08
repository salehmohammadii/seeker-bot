<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 9/20/2018
 * Time: 6:13 PM
 */

class search
{
    private $text;
    private $lang;
    private $chat_id;

    function __construct($chat_id, $text, $lang)
    {
        $this->chat_id = $chat_id;
        $this->text = $text;
        $this->lang = $lang;
    }

    function normalSearch()
    {
        if ($this->lang == 1) {
            telegramApi::sendmessage($this->chat_id, "در حال جستجو برای $this->text");
        } else {
            telegramApi::sendmessage($this->chat_id, "searching for $this->text");
        }
        $m = explode(" ", $this->text);
        sqlMethod::sqlconnect("insert into seeker.search (text) VALUES (n'$this->text')");
        $tok = sqlMethod::sqlget("SELECT Max(id) FROM seeker.search")['id'];
        if (count($m) == 1) {
            $result = sqlMethod::sqlconnect("select DISTINCT * from posts  ON WHERE and match(text) AGAINST('$this->text')  ORDER BY date DESC");
            telegramApi::send($this->chat_id, $result, 1, $tok, $this->text, "aaaaaaa", "aaaaaaaaa");
        } elseif (count($m) == 2) {
            $text1 = $m[0];
            $text2 = $m[1];
            $result = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts WHERE match(text) AGAINST('$text1,$text2')  ORDER BY date DESC");
            telegramApi::send($this->chat_id, $result, 1, $tok, "$text1", $text2, "aaaaaaaaaaaaaaaaaaaaaa");
        } elseif (count($m) == 3) {
            $text1 = $m[0];
            $text2 = $m[1];
            $text3 = $m[2];
            $result = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts WHERE match(text) AGAINST('$text1,$text2,$text3')  ORDER BY date DESC");
            telegramApi::send($this->chat_id, $result, 1, $tok, "$text1", $text2, $text3);

        }
    }

    function advanceSearch($dasteh, $day)
    {
        $b=null;
        $tok = 111111;
$d=1;
        if ($this->lang == 1) {
            telegramApi::sendmessage($this->chat_id, "در حال جستجو برای $this->text");
        } else {
            telegramApi::sendmessage($this->chat_id, "searching for $this->text");
        }
        if ($dasteh == 1) {
            $b = "text";
        } elseif ($dasteh == 2) {
            $b = "voice or audio";
        } elseif ($dasteh == 4) {
            $b = "video";
        } elseif ($dasteh == 5) {
            $b = "Document";
        } elseif ($dasteh == 6) {
            $b = "*";
        } elseif ($dasteh == 3) {
            $b = "photo";
        }
        if ($day == 1) {
            $d = 1;
        } elseif ($day == 2) {
            $d = 3;
        } elseif ($day == 3) {
            $d = 7;
        } elseif ($day == 4) {
            $d = 30;
        } elseif ($day == 5) {
            $d = 9999999;
        }
        $m = explode(" ", $this->text);
        if (count($m) == 1) {
            $result = sqlMethod::sqlconnect("select DISTINCT * from seeker.posts WHERE format='$b' and datediff(date,curdate())<$d and match(text) AGAINST('$this->text')  ORDER BY date DESC ");
            if ($result->num_rows > 10) {
                sqlMethod::sqlconnect("insert into seeker.search (text,format,day) VALUES (N'$this->text',$b,$d)");
                $tok = sqlMethod::sqlget("SELECT Max(id) FROM seeker.search")['id'];
            }
            telegramApi::send($this->chat_id, $result, 1, $tok, $this->text, null, null);
        } elseif (count($m) == 2) {
            $text1 = $m[0];
            $text2 = $m[1];
            $result = sqlMethod::sqlconnect("select DISTINCT * from seeker.posts WHERE and format='$b' and datediff(date,curdate())<$d and match(text) AGAINST('$text1,$text2')  ORDER BY date DESC");
            if ($result->num_rows > 10) {
                sqlMethod::sqlconnect("insert into seeker.search (text,format,day) VALUES (N'$this->text',$b,$d)");
                $tok = sqlMethod::sqlget("SELECT Max(id) FROM seeker.search")['id'];
            }
            telegramApi::send($this->chat_id, $result, 1, $tok, $text1, $text2, "aaaaaaaaaaaaaa");
        } elseif (count($m) == 3) {
            $text1 = $m[0];
            $text2 = $m[1];
            $text3 = $m[2];
            $result = sqlMethod::sqlconnect("select DISTINCT * from posts WHERE  and format='$b' and datediff(date,curdate())<$d and match(text) AGAINST('$text1,$text2,$text3')  ORDER BY date DESC");
            if ($result->num_rows > 10) {
                sqlMethod::sqlconnect("insert into seeker.search (text,format,day) VALUES (N'$this->text',$b,$d)");
                $tok = sqlMethod::sqlget("SELECT Max(id) FROM seeker.search")['id'];
            }
            telegramApi::send($this->chat_id, $result, 1, $tok, $text1, $text2, $text3);
        } else {
            if ($this->lang == 1) {
                telegramApi::sendmessage($this->chat_id, "حداکثر تعداد کلمات مجاز برای جستجو ۳ است.");
            } else {
                telegramApi::sendmessage($this->chat_id, "the maximum word for search is 3");
            }
        }
    }

     static function WantSee($chat_id, $data, $lang)
    {
        $a = preg_replace("/[^0-9]/", '', $data);
        $b=null;
        if ($a == 1) {
            $b = sqlMethod::sqlconnect("SELECT DISTINCT * FROM seeker.posts JOIN channel ON posts.id = channel.id WHERE lang=$lang and `group`=05 ORDER BY date DESC");
            sqlMethod::sqlconnect("insert into seeker.search(`group`) VALUES ('05')");
        } elseif ($a == 2) {
            $b = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts JOIN channel ON posts.id = channel.id WHERE lang=$lang and `group`=15  AND format='audio' ORDER BY date DESC");
            sqlMethod::sqlconnect("insert into search(`group`,format) VALUES ('15','audio')");
        } elseif ($a == 3) {
            $b = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts JOIN channel ON posts.id = channel.id WHERE `group`=18 AND format='photo' ORDER BY date DESC");
            sqlMethod::sqlconnect("insert into search(`group`,format) VALUES ('18','photo')");
        } elseif ($a == 5) {
            $b = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts JOIN channel ON posts.id = channel.id WHERE lang=$lang and `group`=09 ORDER BY date DESC ,star DESC;");
            sqlMethod::sqlconnect("insert into search (`group`) VALUES ('09')");
        } elseif ($a == 6) {
            $b = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts JOIN channel ON posts.id = channel.id WHERE lang=$lang and `group`=19  ORDER BY date DESC ,star DESC;");
            sqlMethod::sqlconnect("insert into search(`group`) VALUES ('19')");
        }elseif ($a == 7) {
            $b = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts JOIN channel ON posts.id = channel.id WHERE lang=$lang and `group`=07 ORDER BY date DESC ,star DESC;");
            sqlMethod::sqlconnect("insert into search(`group`) VALUES ('07')");
        }elseif ($a == 8) {
            $b = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts JOIN channel ON posts.id = channel.id WHERE lang=$lang and `group`=14 ORDER BY date DESC ,star DESC;");
            sqlMethod::sqlconnect("insert into search(`group`) VALUES ('14')");
        }elseif ($a == 9) {
            $b = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts JOIN channel ON posts.id = channel.id WHERE lang=$lang and `group`=03 ORDER BY text DESC,date DESC ,star DESC;");
            sqlMethod::sqlconnect("insert into search(`group`) VALUES ('03')");
        }
        $tok = sqlMethod::sqlget("SELECT Max(id) FROM seeker.search")['id'];
        telegramApi::send($chat_id, $b, 1, $tok, null, null, null);
    }

    static function show_next_page($data, $chat_id)
    {
        $result=null;
        $text1=null;
        $text2=null;
        $text3=null;
        $sql = explode("to", $data);
        $page = $sql[0];
        $tok = $sql[1];
        $info = sqlMethod::sqlget("select * from search WHERE id=$tok");
        $group=$info['group'];
        $day=$info['day'];
        $format=$info['format'];
        $text=$info['text'];
        if ($text!=null and $group==null and $format==null and $day==null ) {
            $m = explode(" ", $text);
            if (count($m) == 1) {
                $result = sqlMethod::sqlconnect("select DISTINCT * from posts  WHERE and match(text) AGAINST('$text')  ORDER BY date DESC");
                telegramApi::send($chat_id, $result, 1, $tok, $text, "aaaaaaa", "aaaaaaaaa");
            } elseif (count($m) == 2) {
                $text1 = $m[0];
                $text2 = $m[1];
                $result = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts WHERE match(text) AGAINST('$text1,$text2')  ORDER BY date DESC");
                telegramApi::send($chat_id, $result, 1, $tok, "$text1", $text2, "aaaaaaaaaaaaaaaaaaaaaa");
            } elseif (count($m) == 3) {
                $text1 = $m[0];
                $text2 = $m[1];
                $text3 = $m[2];
                $result = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts WHERE match(text) AGAINST('$text1,$text2,$text3')  ORDER BY date DESC");
                telegramApi::send($chat_id, $result, 1, $tok, "$text1", $text2, $text3);
            }
        }elseif ($text==null){
            if ($day==null){
                if ($format==null){
                    $result = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts JOIN channel ON posts.id = channel.id WHERE   `group`=$group ORDER BY date DESC ,star DESC;");
                }else{
                    $result = sqlMethod::sqlconnect("SELECT DISTINCT * FROM posts JOIN channel ON posts.id = channel.id WHERE  `group`=$group  AND format='$format' ORDER BY date DESC");
                }
            }
        }elseif ($text!=null and $format!=null and $day!=null) {
            $m = explode(" ", $text);
            if (count($m) == 1) {
                $result = sqlMethod::sqlconnect("select DISTINCT * from seeker.posts WHERE format='$format' and datediff(date,curdate())<$day and match(text) AGAINST('$text')  ORDER BY date DESC ");
            } elseif (count($m) == 2) {
                $text1 = $m[0];
                $text2 = $m[1];
                $result = sqlMethod::sqlconnect("select DISTINCT * from seeker.posts WHERE and format='$format' and datediff(date,curdate())<$day and match(text) AGAINST('$text1,$text2')  ORDER BY date DESC");
                } elseif (count($m) == 3) {
                $text1 = $m[0];
                $text2 = $m[1];
                $text3 = $m[2];
            }
        }
        telegramApi::send($chat_id, $result, $page, $tok, $text1, $text2, $text3);
    }
}