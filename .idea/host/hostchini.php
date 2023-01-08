<?php
$servername="localhost";
$username="mychanneladminbot";
$pass="qOA4g8EBDCbXJgjdlkD4yMsw2";
$dbname="chini";
$token="498411789:AAG7OHY8yHsRJMrwAvkcJnqb_-ZIVwnPiMQ";
$modir="70920361";
$conn = mysqli_connect($servername, $username, $pass, $dbname) or die ('Failed to connect to database');
mysqli_query($conn, "SET NAMES 'utf8'");
mysqli_query($conn, "SET CHARACTER SET 'utf8'");
mysqli_query($conn, "SET character_set_connection = 'utf8'");
$telegram = json_decode(file_get_contents('php://input'));

if (isset($telegram->callback_query)==true) {
    echo "salam";
    $chat_id = $telegram->callback_query->message->chat->id;
    $data = $telegram->callback_query->data;
    if ($chat_id == $modir) {
        if ($data == "start") {
            sqlconnect("update aaza set step='null' WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"خانه🏡");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "✍🏻افزودن جنس", 'callback_data' => 'add']
                    ],
                    [
                        ['text' => "تعداد اعضا", 'callback_data' => 'row']
                    ],
                    [
                        ['text' => "ارسال تبلیغ به تمام اعضا", 'callback_data' => 'tabligh']
                    ],

                    [
                        ['text' => "جستجو🔍", 'callback_data' => 'serch']
                    ]
                ]
            ];
            message_inline_query($modir, "چه خدمتی در نظر دارید؟؟", $r);
        }elseif ($data=="row"){
            sqlconnect("update aaza set step='null' WHERE chat_id=$chat_id");
            $result=mysqli_query($conn,"select * from aaza");
            $a=$result->num_rows;
            sendmessage($modir,"تعداد اعضا ربات برابر است با $a");
        }elseif ($data=="tabligh"){
            sqlconnect("update aaza set aaza.step='tabligh' WHERE aaza.chat_id=$chat_id");
            sendmessage($modir,"تبلیغ مورد نظر را ارسال کنید");
        }elseif (strstr($data,"edit")){
            answer_inline($telegram->callback_query->id,"ویرایش");
            sqlconnect("update aaza set step='null' WHERE chat_id=$chat_id");
            $a = preg_replace("/[^0-9]/", '', $data);
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "ویرایش نام", 'callback_data' => 'name'.$a]
                    ],
                    [
                        ['text' => "ویرایش عکس", 'callback_data' => 'ax'.$a]
                    ],
                    [
                        ['text' => "ویرایش توضیحات", 'callback_data' => 'tozih'.$a]
                    ],
                    [
                        ['text' => "ویرایش قیمت", 'callback_data' => 'ghey'.$a]
                    ]
                ]
            ];
            message_inline_query($chat_id,"مایل به ویرایش کدام یک از قسمت های زیر هستید؟",$r);
        }elseif (strstr($data,"nam")){
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlconnect("update aaza set step='editnam$a' WHERE chat_id=$chat_id");
            sendmessage($chat_id,"نام جدید را وارد نمایید");
        }elseif (strstr($data,"ax")){
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlconnect("update aaza set step='editax$a' WHERE chat_id=$chat_id");
            sendmessage($chat_id,"عکس جدید جدید را ارسال نمایید");
        }elseif (strstr($data,"tozih")){
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlconnect("update aaza set step='edittozih$a' WHERE chat_id=$chat_id");
            sendmessage($chat_id,"نام جدید را وارد نمایید");
        }elseif (strstr($data,"ghey")){
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlconnect("update aaza set step='editghey$a' WHERE chat_id=$chat_id");
            sendmessage($chat_id,"نام جدید را وارد نمایید");
        }elseif (strstr($data,"dele")){
            answer_inline($telegram->callback_query->id,"حذف جنس");
            sqlconnect("update aaza set step='null' WHERE chat_id=$chat_id");
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlconnect("delete from ajnas WHERE token=$a");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "خانه", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id,"جنس مورد نظر با موفقیت حذف شد",$r);
        }elseif ($data == "add") {
            answer_inline($telegram->callback_query->id,"افزودن جنس");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "آرکوپال🍛", 'callback_data' => 'ldast1']
                    ],
                    [
                        ['text' => "🍽ظروف چینی", 'callback_data' => 'ldast2']
                    ],
                    [
                        ['text' => "کریستال های نقش دار🍧", 'callback_data' => 'ldast3']
                    ],
                    [
                        ['text' => "ظروف چینی قابل استفاده در فر🍲", 'callback_data' => 'ldast4']
                    ],
                    [
                        ['text' => "بلور🍷", 'callback_data' => 'ldast5']
                    ],
                    [
                        ['text' => "ظروف سرامیکی🍵", 'callback_data' => 'ldast6']
                    ],
                    [
                        ['text' => "ظروف کریستال🍧 ", 'callback_data' => 'ldast7']
                    ],
                    [
                        ['text' => "ظروف بلور کار شده🍛", 'callback_data' => 'ldast8']
                    ],
                        [
                            ['text' => "ظروف شیرینی خوری چند طبقه فلزی🍱", 'callback_data' => 'ldast10']
                        ]
                    ]
                ];
            message_inline_query($chat_id, "لطفا دسته بندی مورد نظر را انتخاب فرمایید", $r);
            sqlconnect("update aaza set step='null' WHERE chat_id=$chat_id");
        } elseif (strstr($data, "ldast")) {
            answer_inline($telegram->callback_query->id,"دسته بندی انتخاب شد.");
            $a = preg_replace("/[^0-9]/", '', $data);
            $t = sqlget("SELECT token FROM last WHERE id=1")['token'];
            sqlconnect("insert into ajnas VALUES ($t,null,null,NULL ,NULL ,NULL ,TRUE ,$a)");
            sqlconnect("UPDATE last SET token=token+1 WHERE id=1");
            sendmessage($chat_id, "لطفا نام کالا را وارد نمایید");
            sqlconnect("update aaza set step='name' where chat_id=$modir");
        } elseif ($data == "serch") {
            answer_inline($telegram->callback_query->id,"جستجو🔍");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "آرکوپال🍛", 'callback_data' => 'sdast1']
                    ],
                    [
                        ['text' => "🍽ظروف چینی", 'callback_data' => 'sdast2']
                    ],
                    [
                        ['text' => "کریستال های نقش دار🍧", 'callback_data' => 'sdast3']
                    ],
                    [
                        ['text' => "ظروف چینی قابل استفاده در فر🍲", 'callback_data' => 'sdast4']
                    ],
                    [
                        ['text' => "بلور🍷", 'callback_data' => 'sdast5']
                    ],
                    [
                        ['text' => "ظروف سرامیکی🍵", 'callback_data' => 'sdast6']
                    ],
                    [
                        ['text' => "ظروف کریستال🍧 ", 'callback_data' => 'sdast7']
                    ],
                    [
                        ['text' => "ظروف بلور کار شده🍛", 'callback_data' => 'sdast8']
                    ],
[
                    ['text' => "ظروف شیرینی خوری چند طبقه فلزی🍱", 'callback_data' => 'sdast10']
] 
               ]
            ];
            message_inline_query($modir, "دسته بندی مورد نظر را انتخاب نمایید", $r);
        } elseif (strstr($data, "sdast")) {
            answer_inline($telegram->callback_query->id,"دسته بندی انتخاب شد");
            $a = preg_replace("/[^0-9]/", '', $data);
            $result = mysqli_query($conn, "select * from ajnas WHERE dasteh=$a and hide=false");
            send($result, $modir);
        }
    }else {
        if ($data == "start") {
            sqlconnect("update aaza set step='null' WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"خانه🏡");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "جستجو بر اساس قیمت🔍", 'callback_data' => 'gheymat']
                    ],
                    [
                        ['text' => "جستجو بر اساس دسته بندی ها🔑", 'callback_data' => 'dasteh']
                    ],
                    [
                        ['text' => "تماس با ما📞", 'callback_data' => 'help']
                    ]
                ]
            ];
            message_inline_query($chat_id,"با سلام✋️
بسیار مفتخریم که ما را برای خرید اجناستان انتخاب کردید برای خرید ابتدا در میان اجناس جستجو کنید.🔍
در اینجا ما دو نوع جستجو داریم.
نوع اول بر اساس قیمت که میتونید تمام اجناس بین محدوده قیمت مد نظرتون رو مشاهده کنید و در صورت تمایل با استفاده از دکمه ای که زیر هر  جنس مشاهده خواهید کرد اون جنس رو به سبد خریدتون اضافه کنید و در پایان مشخصاتتون را وارد کنید تا اجناس برای شما ارسال بشوند و هزینه درب منزل پرداخت انجام میشود.
و اما نوع دوم که به صورت جستجو میان دسته بندی های اجناس هست.
امیدواریم از خدمات ما رضایت کافی رو داشته باشید❤️
برای ارائه هر گونه نظر یا گزارش هر مشکلی از گزینه تماس با ما استفاده نمایید🌹🌹",$r);
        }elseif (strstr($data,"addkh")){
            sqlconnect("update aaza set step='null' WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"به سبد اضافه شد");
            $a = preg_replace("/[^0-9]/", '', $data);
            $b=sqlget("select token from aaza WHERE chat_id=$chat_id")['token'];
            $c=$b.",$a";
            sqlconnect("update aaza set token='$c' WHERE aaza.chat_id=$chat_id");
        }elseif ($data=="takmil"){
             answer_inline($telegram->callback_query->id,"تایید سبد خرید");
            $r = ['inline_keyboard' => []];
            $s=sqlget("select token from aaza WHERE chat_id=$chat_id")['token'];
            $r = ['inline_keyboard' => []];
            $b=explode(" ",$s);
            $s=str_replace(","," ",$s);
            $z=0;
            $i=0;
            while (strstr($s,"1")) {
                $b = explode(" ", $s);
                while (strlen($b[$i])!=4) {
                    $i++;
                }
                foreach ($b as $m) {
                    if ($b[$i] == $m) {
                        $z++;
                    }
                }
                $name = sqlget("select name from ajnas WHERE token=$b[$i]")['name'];
                if ($name != null) {
                    $r['inline_keyboard'][][] = ['text' => "$z " . $name, 'callback_data' => 'dele' . $b[$i] . ''];
                }
                $z = 0;
                $s = str_replace($b[$i], "", $s);

                $i=0;
            }
         
      $r['inline_keyboard'][][] = ['text' => "تکمیل خرید", 'callback_data' => 'takmil1'];
            message_inline_query($chat_id,"لیست اجناس داخل سبد شما به صورت زیر است برای حذف هر کدام کافی است یک بار بر روی نام آن بزنید و در پایان میتوانید با استفاده از گزینه تکمیل خرید خریدتان را کامل کنید",$r);
        }elseif ($data=="takmil1"){
            answer_inline($telegram->callback_query->id,"تکمیل خرید");
             $s=sqlget("select token from aaza WHERE chat_id=$chat_id")['token'];
            $q="لیست اجناس شما به صورت زیر است:";
         

            $s=str_replace(","," ",$s);
            $b=explode(" ",$s);
            $o=0;
            $i=0;
            $f=0;
            while (strstr($s,"1")) {
                $b = explode(" ", $s);
                while (strlen($b[$i]) != 4) {
                    $i++;
 if ($i>count($b)){
                        break;
                    }
                }
                foreach ($b as $m) {
                    if ($b[$i] == $m) {
                        $o++;
                    }
                }
                $name = sqlget("select * from ajnas WHERE token=$b[$i]");
                $x=$name['name'];
                $y=$name['gheymat'];


                $z=" 
  ✍️ نام : $x
  💰قیمت : $y تومان
🔑تعداد: $o  
________________________________";
                $q=$q.$z;
                $f=$y*$o+$f;
                $o=0;
                $s = str_replace($b[$i], "", $s);
                $i=0;
            }
            $q=$q."\n"."💎قیمت کل اجناس برابر است با $f تومان";
            $r=[
                'inline_keyboard' => [
                    [
                        ['text' => "ویرایش سبد خرید", 'callback_data' => 'takmil']
                    ]
                ]
            ];
            message_inline_query($chat_id,"لیست اجناس شما به صورت زیر است: \n $q \n در صورت تایید نام خود را وارد نمایید و برای ویرایش سبد خرید بر روی دکمه ی زیر کلیک نمایید. ",$r);
            sqlconnect("update aaza set step='name' WHERE aaza.chat_id=$chat_id");

        }elseif (strstr($data,"dele")){
            $a = preg_replace("/[^0-9]/", '', $data);
            $s= sqlget("select token from aaza WHERE chat_id=$chat_id")['token'];
            $s=str_replace(",$a","",$s);
            sqlconnect("update aaza set token='$s' WHERE aaza.chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"با موفقیت حذف شد.");
        }
        elseif ($data=="dasteh"){
            answer_inline($telegram->callback_query->id,"جستجو بر اساس دسته بندی🔑");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "آرکوپال🍛", 'callback_data' => 'sdast1']
                    ],
                    [
                        ['text' => "🍽ظروف چینی", 'callback_data' => 'sdast2']
                    ],
                    [
                        ['text' => "کریستال های نقش دار🍧", 'callback_data' => 'sdast3']
                    ],
                    [
                        ['text' => "ظروف چینی قابل استفاده در فر🍲", 'callback_data' => 'sdast4']
                    ],
                    [
                        ['text' => "بلور🍷", 'callback_data' => 'sdast5']
                    ],
                    [
                        ['text' => "ظروف سرامیکی🍵", 'callback_data' => 'sdast6']
                    ],
                    [
                        ['text' => "ظروف کریستال🍧", 'callback_data' => 'sdast7']
                    ],
                    [
                        ['text' => "ظروف بلور کار شده🍛", 'callback_data' => 'sdast8']
                    ],

                    [
                        ['text' => "ظروف شیرینی خوری چند طبقه فلزی🍱", 'callback_data' => 'sdast10']
                    ]
                ]
            ];

            message_inline_query($chat_id, "دسته بندی مورد نظر را انتخاب نمایید", $r);
        } elseif (strstr($data, "sdast")) {
            answer_inline($telegram->callback_query->id,"جستجو آغاز شد🔍");
            $a = preg_replace("/[^0-9]/", '', $data);
            $result = mysqli_query($conn, "select * from ajnas WHERE dasteh=$a and hide=false");
            send($result,$chat_id);
        }
        elseif ($data == "gheymat") {
            answer_inline($telegram->callback_query->id,"جستجو بر اساس قیمت.🔍");
            sqlconnect("update aaza set step='maxgh' WHERE chat_id=$chat_id");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "حداکثر قیمت مهم نیست", 'callback_data' => 'notmax']
                    ],
                    [
                        ['text' => "خانه🏡", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id, "لطفا حداکثر قیمت موردنظرتان را وارد نمایید. و اگر حداکثر قیمت برای شما مهم نیست از دکمه ای که در پایین مشاهده میکنید استفاده نمایید ", $r);
        } elseif ($data == "notmax") {
            answer_inline($telegram->callback_query->id,"حداکثر قیمت مهم نیست");
            sqlconnect("update aaza set step='mingh',maxp=99999999 WHERE chat_id=$chat_id");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "حداقل قیمت مهم نیست", 'callback_data' => 'notmin']
                    ],
                    [
                        ['text' => "خانه🏡", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id, "لطفا حداقل قیمت موردنظرتان را وارد نمایید. و اگر حداقل قیمت برای شما مهم نیست از دکمه ای که در پایین مشاهده میکنید استفاده نمایید ", $r);
        }elseif ($data=="tamas"){
            answer_inline($telegram->callback_query->id,"تماس با ما");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "خانه", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id,"برای هر گونه سوال و یا گزارش مشکل در مورد نحوه کار کرد ربات با آیدی @saleh7676 تماس بگیرید.",$r);
        }
        elseif ($data == "notmin") {
            answer_inline($telegram->callback_query->id,"حداقل قیمت مهم نیست");
            $a = sqlget("select maxp from aaza WHERE chat_id=$chat_id")['maxp'];
            $result = mysqli_query($conn,"select * from ajnas where gheymat<$a and hide=FALSE ");
            send($result,$chat_id);
            sqlconnect("update aaza set step=null ,maxp=NULL WHERE chat_id=$chat_id");
        }
    }
} elseif (isset($telegram->message->photo) == true) {
    $chat_id = $telegram->message->from->id;
    if ($chat_id == $modir) {
        $step=sqlget("select step from aaza WHERE chat_id=$modir")['step'];
        if ($step=="ax"){
            $photo = $telegram->message->photo[0]->file_id;
            $last = sqlget("SELECT token FROM last WHERE id=1")['token'];
            $last--;
            sqlconnect("update ajnas set file='$photo',hide=FALSE WHERE token=$last");
            $result=mysqli_query($conn,"select * from ajnas WHERE token=$last");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "خانه", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id,"جنس مورد نظر با موفقیت ثبت شد",$r);
            sqlconnect("update aaza set step=NULL WHERE chat_id=$modir");
        }elseif (strstr($step,"editax")){
            $photo = $telegram->message->photo[0]->file_id;
            $a = preg_replace("/[^0-9]/", '', $step);
            sqlconnect("update ajnas set file='$photo' WHERE token=$a");
        }
        elseif ($step=="tabligh"){
            $photo = $telegram->message->photo[0]->file_id;
            $caption = $telegram->message->caption;
            $result = mysqli_query($conn, "SELECT chat_id FROM aaza");
            while ($row = mysqli_fetch_array($result)) {
                $id = $row['chat_id'];
                $text = urlencode($caption);
                $url = "https://api.telegram.org/bot$token/sendPhoto?chat_id=" . $id .
                    "&photo=" . $photo . "&caption=" . $text;
                file_get_contents($url);
            }
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "خانه", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($modir, "تبلیغ به تمام اعضا ارسال شد",$r);
            sqlconnect("update aaza set aaza.step=null WHERE aaza.chat_id=$chat_id");
        }
    }
} elseif (isset($telegram->message->text) == true) {

    $text = $telegram->message->text;
    $chat_id = $telegram->message->from->id;


    if ($chat_id == $modir) {
        if ($text=="/start") {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "✍🏻افزودن جنس", 'callback_data' => 'add']
                    ],
                    [
                        ['text' => "تعداد اعضا", 'callback_data' => 'row']
                    ],
                    [
                        ['text' => "ارسال تبلیغ به تمام اعضا", 'callback_data' => 'tabligh']
                    ],

                    [
                        ['text' => "جستجو🔍", 'callback_data' => 'serch']
                    ]
                ]
            ];
            message_inline_query($modir, "چه خدمتی در نظر دارید؟؟", $r);
        }else {
            $step = sqlget("select step from aaza WHERE chat_id=$chat_id")['step'];
            if ($step == "name") {
                $last = sqlget("SELECT token FROM last WHERE id=1")['token'];
                $last--;
                sqlconnect("update ajnas set name=N'$text' WHERE token=$last");
                sqlconnect("update aaza set step='gheymat' WHERE chat_id=$modir");
                sendmessage($modir, "لطفا قیمت جنس مورد نظر را وارد نمایید");
            }elseif (strstr($step,"editnam")){
                $a = preg_replace("/[^0-9]/", '', $step);
                sqlconnect("update ajnas set name='$text' WHERE token=$a");
                sendmessage($chat_id,"نام جدید ثبت شد");
            }elseif (strstr($step,"edittozih")){
                $a = preg_replace("/[^0-9]/", '', $step);
                sqlconnect("update ajnas set tozihat='$text' WHERE token=$a");
                sendmessage($chat_id,"توضیحات جدید ثبت شد");
            }elseif (strstr($step,"editghey")){
                $a = preg_replace("/[^0-9]/", '', $step);
                sqlconnect("update ajnas set gheymat='$text' WHERE token=$a");
                sendmessage($chat_id,"قیمت جدید ثبت شد");
            } elseif ($step == "gheymat") {
                $last = sqlget("SELECT token FROM last WHERE id=1")['token'];
                $last--;
                $en_num = array('0','1','2','3','4','5','6','7','8','9');
                $fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
                $text=str_replace($fa_num,$en_num,$text);
                sqlconnect("update ajnas set gheymat=N'$text' WHERE token=$last");
                sqlconnect("update aaza set step='tozihat' WHERE chat_id=$modir");
                sendmessage($modir, "لطفا توضیحاته مختصری در مورد  جنس مورد نظر ارسال نمایید");
            }elseif ($step=="tabligh"){
                sqlconnect("update aaza set step=NULL WHERE chat_id=$modir");
                $result = mysqli_query($conn, "SELECT chat_id FROM aaza");
                while ($row = mysqli_fetch_array($result)) {
                    $id = $row['chat_id'];
                    sendmessage($id, $text);
                }
                sendmessage($modir, "تبلیغ به تمام اعضا ارسال شد");
            }
            elseif ($step == "tozihat") {
                $last = sqlget("SELECT token FROM last WHERE id=1")['token'];
                $last--;
                sqlconnect("update ajnas set tozihat=N'$text' WHERE token=$last");
                sqlconnect("update aaza set step='ax' WHERE chat_id=$modir");
                sendmessage($modir, "لطفا عکس جنس مورد نظر را ارسال نمایید");
            }
        }
    }else{
        if ($text == "/start") {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "جستجو بر اساس قیمت🔍", 'callback_data' => 'gheymat']
                    ],
                    [
                        ['text' => "جستجو بر اساس دسته بندی ها🔑", 'callback_data' => 'dasteh']
                    ],
                    [
                        ['text' => "تماس با ما", 'callback_data' => 'tamas']
                    ]
                ]
            ];
            message_inline_query($chat_id,"با سلام✋️
بسیار مفتخریم که ما را برای خرید اجناستان انتخاب کردید برای خرید ابتدا در میان اجناس جستجو کنید.🔍
در اینجا ما دو نوع جستجو داریم.
نوع اول بر اساس قیمت که میتونید تمام اجناس بین محدوده قیمت مد نظرتون رو مشاهده کنید و در صورت تمایل با استفاده از دکمه ای که زیر هر  جنس مشاهده خواهید کرد اون جنس رو به سبد خریدتون اضافه کنید و در پایان مشخصاتتون را وارد کنید تا اجناس برای شما ارسال بشند و هزینه درب منزل پرداخت بشه.
و اما نوع دوم که به صورت جستجو میان دسته بندی های اجناس هست.
امیدواریم از خدمات ما رضایت کافی رو داشته باشید❤️
برای ارائه هر گونه نظر یا گزارش هر مشکلی از گزینه تماس با ما استفاده نمایید🌹🌹",$r);
            if (checkaaza($chat_id)){

            }else{
                sqlconnect("insert into aaza VALUES ($chat_id,NULL ,NULL ,NULL ,NULL ,NULL ,NULL ,NULL )");
            }
        }else {
            $step = sqlget("select step from aaza WHERE chat_id=$chat_id")['step'];
            if ($step == "maxgh") {
                $en_num = array('0','1','2','3','4','5','6','7','8','9');
                $fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
                $text=str_replace($fa_num,$en_num,$text);
                sqlconnect("update aaza set maxp='$text',step='mingh' WHERE chat_id=$chat_id");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "حداکثر قیمت مهم نیست", 'callback_data' => 'notmin']
                        ],
                        [
                            ['text' => "خانه🏡", 'callback_data' => 'start']
                        ]
                    ]
                ];
                message_inline_query($chat_id, "لطفا حداقل قیمت موردنظرتان را وارد نمایید. و اگر حداقل قیمت برای شما مهم نیست از دکمه ای که در پایین مشاهده میکنید استفاده نمایید ", $r);
            }elseif ($step=="name"){
                sqlconnect("update aaza set step='phone',name=N'$text' WHERE aaza.chat_id=$chat_id");
                sendmessage($chat_id,"لطفا شماره تماس خود را وارد کنید.");
            }elseif ($step=="phone"){
                sqlconnect("update aaza set step='adres',phone=N'$text' WHERE aaza.chat_id=$chat_id");
                sendmessage($chat_id,"لطفا ادرس خود را وارد کنید.");
            }elseif ($step=="adres"){
                sqlconnect("update aaza set step=NULL ,address=N'$text' WHERE aaza.chat_id=$chat_id");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "خانه🏡", 'callback_data' => 'start']
                        ]
                    ]
                ];
                message_inline_query($chat_id,"با تشکر از خریدتان اطلاعات شما با موفقیت ثبت شد و در خرید های بعدی نیاز به ثبت اطلاعات نیست.اجناس خریداری شده در اسرع وقت برای شما ارسال میشود در صورت بروز هر گونه مشکل با شماره 084374343 تماس حاصل فرمایید.",$r);
                $f=0;
                $q=null;
                $s=sqlget("select * from aaza WHERE chat_id=$chat_id");
                $b=explode(",",$s['token']);
                $e=count($b);
                for ($i=1;$i<$e;$i++) {
                    $name = sqlget("select * from ajnas WHERE token=$b[$i]");
                    $x=$name['name'];
                    $y=$name['gheymat'];
                    $z=" ✍️ نام : $x
💰  قیمت : $y تومان
________________________________
";
                    $q=$q.$z;
                    $f=$f+$name['gheymat'];
                }
                $q=$q."💎قیمت کل اجناس برابر است با $f تومان";

                $nam=$s['name'];
                $phone=$s['phone'];
                $add=$s['address'];
                $te="خرید جدید ثبت شد مشخصات اجناس خریداری شده عبارت اند از: 
                                $q
و مشخصات خریدار عبارت اند از :
                                نام: $nam
                                تلفن تماس: $phone
                                ادرس: $add";
                sendmessage($modir,"$te");
                sqlconnect("update aaza set token=NULL  WHERE aaza.chat_id=$chat_id");
            }
            elseif ($step == "mingh") {
                $en_num = array('0','1','2','3','4','5','6','7','8','9');
                $fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
                $text=str_replace($fa_num,$en_num,$text);
                $a = sqlget("select maxp from aaza WHERE chat_id=$chat_id")['maxp'];
                $result = mysqli_query($conn, "select * from ajnas WHERE gheymat<$a AND gheymat>$text AND hide=FALSE ");
                send($result, $chat_id);
            }
        }
    }
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
    $text = urlencode($text);
    $encodedMarkup=urlencode($encodedMarkup);
    $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chat_id&text=$text&reply_markup=" . $encodedMarkup;
    file_get_contents($url);
}
function sqlconnect($sql)
{
    global $conn;
    mysqli_query($conn, $sql);
}

function checkaaza($chat_id)
{
    $sql = "SELECT chat_id FROM aaza WHERE chat_id=$chat_id";
    $result = sqlget($sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function sendmessage($chat_id, $message)
{
    global $token;
    $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
    json_decode(file_get_contents($url));
}

function sendphoto($chat_id, $text, $file,$r)
{
    global $token;
    $text = urlencode($text);
    $encodedMarkup = json_encode($r, true);
    $url = "https://api.telegram.org/bot$token/sendPhoto?chat_id=" . $chat_id .
        "&photo=" . $file . "&caption=" . $text."&reply_markup=".$encodedMarkup;
    file_get_contents($url);
}
function send($result,$chat_id)
{
    global $modir;
    if ($chat_id !=$modir and $result->num_rows >= 1) {
        sendmessage($chat_id, "لیست اجناس مطابق با معیار شما به صورت زیر است برای اضافه کردن به سبد خرید بر روی دکمه ی زیر آن پست کلیک کنید و در پایان بر روی تکمیل خرید کلیک کنید.اگر جنسی را اشتباه انتخاب کردید میتوانید بغد از انتخاب دکمه ی تکمیل خرید سبد خرید خود را ویرایش نمایید.برای سفارش یک جنس به تعداد بیشتر.به اندازه ی تعدادی که مد نظر دارید بر روی دکمه ی زیر پست کلیک کنید.");
    } elseif ($result->num_rows == 0) {
        $r = [
            'inline_keyboard' => [
                [
                    ['text' => "جستجو بر اساس قیمت🔍", 'callback_data' => 'gheymat']
                ],
                [
                    ['text' => "جستجو بر اساس دسته بندی ها🔑", 'callback_data' => 'dasteh']
                ],
                [
                    ['text' => "تماس با ما📞", 'callback_data' => 'tamas']
                ]
            ]
        ];
        message_inline_query($chat_id, "متاسفانه جنسی مطابق میل شما پیدا نشد.", $r);
    }
    if ($result->num_rows >= 1){
        while ($row = mysqli_fetch_array($result)) {
            $tozihat = $row['tozihat'];
            $name = $row['name'];
            $file = $row['file'];
            $gheymat = $row['gheymat'];
            $to = $row['token'];
            if ($chat_id == $modir) {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "حذف جنس", 'callback_data' =>  'delete'.$to ]
                        ],
                        [
                            ['text' => "ویرایش", 'callback_data' => $to . 'edit']
                        ],
                    ]
                ];
            } else {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "اضافه به سبد خرید", 'callback_data' => $to . 'addkh']
                        ]
                    ]
                ];
            }
            $text = "📌نام: $name

📢توضیحات:   $tozihat

💰قیمت: $gheymat";
            sendphoto($chat_id, $text, $file, $r);
        }
        if ($chat_id == $modir) {
            $r = [
                'inline_keyboard' => [

                    [
                        ['text' => "خانه🏡", 'callback_data' => 'start']
                    ]
                ]
            ];
            $e = $result->num_rows;
            $text = "تعداد نتایج برابر است با $e";
            message_inline_query($chat_id, $text, $r);
        } else {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "تکمیل خرید", 'callback_data' =>'takmil']
                    ],
                    [
                        ['text' => "خانه🏡", 'callback_data' => 'start']
                    ]
                ]
            ];

            $e = $result->num_rows;
            $text = "تعداد نتایج برابر است با $e";
            message_inline_query($chat_id, $text, $r);
        }
    }
}
function answer_inline($id, $text)
{
    $text = urlencode($text);
    global $token;
    $url = "https://api.telegram.org/bot$token/answerCallbackQuery?callback_query_id=$id&text=$text&show_alert=false";
    file_get_contents($url);
}
