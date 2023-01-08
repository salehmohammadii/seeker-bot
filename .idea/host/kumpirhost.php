<?php
$servername="localhost";
$username="mychanneladminbot";
$pass="qOA4g8EBDCbXJgjdlkD4yMsw2";
$dbname="kumpir";
$token="485634710:AAFYzYXYJIX9hI4IgaInlEY7U3EvkrBy5ck";
$zohre="";
$frosh="1213620";
$conn = mysqli_connect($servername, $username, $pass, $dbname) or die ('Failed to connect to database');
mysqli_query($conn, "SET NAMES 'utf8'");
mysqli_query($conn, "SET CHARACTER SET 'utf8'");
mysqli_query($conn, "SET character_set_connection = 'utf8'");

$telegram = json_decode(file_get_contents('php://input'));
if (isset($telegram->callback_query)) {
    $chat_id = $telegram->callback_query->message->chat->id;
    $data = $telegram->callback_query->data;
    if ($chat_id==$zohre){
        if ($data=="start") {
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "مشاهده مبلغ کل فروش", 'callback_data' => 'frosh']
                    ],
                    [
                        ['text' => "تسویه", 'callback_data' => 'tasvieh']
                    ]
                ]
            ];
            message_inline_query($chat_id,"چی چی میخی خواهر؟",$r);
        }elseif ($data=="showfrosh"){
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $a=sqlget("select token from last WHERE id=3")['token'];
            sendmessage($zohre,$a);
        }elseif ($data=="tasvieh"){
            sqlconnect("update aaza set step='tasvie' WHERE chat_id=$chat_id");
            sendmessage($chat_id,"مبلغ تسویه شده را وارد نمایید");
        }
    }elseif ($chat_id==$frosh){
        if ($data=="start"){
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"خانه 🏠");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "افزودن به منو", 'callback_data' => 'addmenu']
                    ],
                    [

                        ['text' => "شروع به کار رستوران", 'callback_data' => 'open']
                    ],
                    [
                        ['text' => "تعطیلی رستوران", 'callback_data' => 'close']
                    ],
                    [
                        ['text' => "مشاهده منو 📜", 'callback_data' => 'show']
                    ],
                    [
                        ['text' => "مشاهده مبلغ کل فروش", 'callback_data' => 'pool']
                    ]
                ]
            ];
            message_inline_query($chat_id,"چه خدمتی در نظر دارید",$r);
        }elseif ($data=="addmenu"){
            answer_inline($telegram->callback_query->id,"افزودن به منو");
            sqlconnect("update aaza set step='axmenu' WHERE chat_id=$frosh");
            sendmessage($frosh,"عکس غذای مورد نظر را ارسال کنید.");
        }elseif (strstr($data,"editgh")){
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlconnect("update aaza set step='editgh".$a."' WHERE chat_id=$frosh");
            sendmessage($chat_id,"قیمت جدید را وارد کنید.");
        }elseif (strstr($data,"editax")){
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlconnect("update aaza set step='editax".$a."' WHERE chat_id=$frosh");
            sendmessage($chat_id,"عکس جدید را ارسال کنید.");
        }
        elseif ($data=="open"){
            answer_inline($telegram->callback_query->id,"مغازه باز شد");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            sqlconnect("update menu set hide=FALSE");
            sendmessage($chat_id,"از الان منو به صورت کامل برای مشتریان به نمایش در خواهد امد.");
        }elseif ($data=="close"){
            sqlconnect("delete from sefareshat");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"خسته نباشید");
            sqlconnect("update menu set hide=TRUE ");
            sendmessage($chat_id,"از الان دیگه سفارشی به مشتری نشان داده نمیشود.");
        }elseif ($data=="show"){
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"مشاهده منو 📜");
            $result=mysqli_query($conn,"select * from menu WHERE gheymat IS NOT NULL");
            send($result,$chat_id);
        }elseif ($data=="pool"){
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $a=sqlget("select token from last WHERE id=3")['token'];
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "خانه 🏠", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id,"$a",$r);
        }
    }else {
        if ($data == "show") {
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"مشاهده منو 📜");
            $result = mysqli_query($conn, "SELECT * FROM menu WHERE hide=FALSE ");
            send($result, $chat_id);
        } elseif ($data == "start") {
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"خانه 🏠");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "مشاهده منو 📜", 'callback_data' => 'show']
                    ],
                    [
                        ['text' => "انتقاد و پیشنهاد 📨", 'callback_data' => 'enteghad']
                    ],
                    [
                        ['text' => "ثبت نام 📇", 'callback_data' => 'login']
                    ],
                    [
                        ['text' => "راهنما 📒", 'callback_data' => 'help']
                    ],

                ]
            ];
            sqlconnect("delete from sefareshat WHERE chat_id=$chat_id");
            message_inline_query($chat_id, "ما هستیم تا شما بهترین خدمات را دریافت کنید. 😍😌
از اعتماد شما بی نهایت سپاسگذاریم. 🙏😊

با یه غذای سالم و خوشمزه و جدید، به خانه ی شما آمده ایم تا میزبان لحظه های شاد و خاطره انگیز شما باشیم.🌹", $r);
        }elseif($data=="help"){
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id,"راهنما 📒");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "خانه 🏠", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id,"راهنما 📒

🔶 مشتری گرامی، به منظور خدمات دهی بهتر، لوکیشن و آدرس خود را دقیق وارد نمائید. 🙏
آدرس دقیق، شامل موارد زیر میباشد:
نام خیابان اصلی، نام خیابان فرعی، نام و شماره کوچه یا بن بست، پلاک، طبقه، واحد

🔶محدوده خدمات رسانی فروشگاه، به صورت زیر 👇 گروه بندی شده که هر گروه دارای تعرفه خاص خود میباشد. 😊

1⃣ گروه یک: شعاع 1 کیلومتری فروشگاه = ارسال رایگان

2⃣ گروه دو: شعاع 1 تا 3 کیلومتری فروشگاه = هزینه پیک 3500 تومان

3⃣ گروه سه: شعاع بیشتر از 3 کیلومتری فروشگاه : فقط برای فاکتورهای بالای 30 هزار تومان خدمات ارائه میشود که هزینه پیک شامل 50 درصد تخفیف میباشد.",$r);
        }elseif ($data=="login"){
            answer_inline($telegram->callback_query->id,"ثبت نام 📇");
            if (checktemp($chat_id)==false) {
                sqlconnect("update aaza set step='namelog' WHERE chat_id=$chat_id");
                sendmessage($chat_id, "لطفا نام و نام خانوادگی خود را ارسال فرمایید.");
            }else{
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "خانه 🏠", 'callback_data' => 'start']
                        ]
                    ]
                ];
                message_inline_query($chat_id,"اطلاعات شما قبلا ثبت شده است.",$r);
            }
        }
        elseif ($data=="enteghad"){
            answer_inline($telegram->callback_query->id,"");
            sqlconnect("update aaza set step='enteghad' WHERE chat_id=$chat_id");
            sendmessage($chat_id,"پیشنهاد یا انتقاد خود را ارسال کنید. 🙏😊");
        }
        elseif ($data == "edit") {
            answer_inline($telegram->callback_query->id,"");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "ویرایش شماره همراه 📝", 'callback_data' => 'mobile']
                    ],
                    [
                        ['text' => "ویرایش آدرس 📝", 'callback_data' => 'addres']
                    ],
                    [
                        ['text' => "ویرایش لوکیشن 🔍", 'callback_data' => 'look']
                    ],
                    [
                        ['text' => "خانه 🏠", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id, "مایل به ویرایش کدام یک از قسمت های زیر هستید؟", $r);
        } elseif ($data == "mobile") {
            answer_inline($telegram->callback_query->id,"ویرایش شماره همراه 📝");
            sqlconnect("update aaza set step='editmobile' WHERE  chat_id=$chat_id");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "خانه 🏠", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id, "شماره همراه جدید را وارد کنید", $r);
        } elseif ($data == "address") {
            answer_inline($telegram->callback_query->id,"ویرایش آدرس 📝");
            sqlconnect("update aaza set step='editadd' WHERE  chat_id=$chat_id");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "خانه 🏠", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id, " ادرس جدید را ارسال کنید.", $r);
        } elseif ($data == "look") {
            answer_inline($telegram->callback_query->id,"ویرایش لوکیشن 🔍");
            sqlconnect("update aaza set step='editlook' WHERE  chat_id=$chat_id");
            $r = ['keyboard' => [
                [
                    ['text' => "ارسال لوکیشن من",'request_location'=>true]
                ]
            ]
            ];
            getlocation($chat_id, "لطفا ابتدا موقعیت یاب (gps) گوشی خود را روشن نموده و سپس با استفاده از دکمه ی زیر مکان خود را اراسال کنید.", $r);
        } elseif (strstr($data,"addkh")) {
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            answer_inline($telegram->callback_query->id, "سفارش غذا 📋");
            $a = preg_replace("/[^0-9]/", '', $data);
            if (checksefaresh($chat_id, $a)) {
                sqlconnect("update sefareshat set tedad=tedad+1 WHERE chat_id=$chat_id AND token=$a");
            } else {
                sqlconnect("insert into sefareshat VALUES ($chat_id,$a,1)");
            }
        } elseif ($data == "takmil") {
            answer_inline($telegram->callback_query->id,"ویرایش سبد خرید 🛒");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $r = ['inline_keyboard' => []];
            $i=0;
            $result = mysqli_query($conn, "select * from sefareshat WHERE chat_id=$chat_id");
            while ($row = mysqli_fetch_array($result)) {
                $tedad = $row['tedad'];
                $token1 = $row['token'];
                $name = sqlget("select name from menu WHERE token=$token1")['name'];
                $r['inline_keyboard'][][]=['text' => $tedad." $name", 'callback_data' => $tedad.''];
                $r['inline_keyboard'][][] = ['text' => "➖", 'callback_data' => 'dele'.$token1.''];
                $r['inline_keyboard'][$i+1][]=['text' => "➕", 'callback_data' => 'afzo'.$token1.''];
                $i=$i+2;
            }
            $r['inline_keyboard'][][] = ['text' => "تکمیل خرید 🖊", 'callback_data' => 'takmil1'];
            message_inline_query($chat_id, "لیست سفارشات شما در زیر قابل مشاهده است، با کلیک بر روی دکمه ها میتوانید تعداد را تغییر دهید. 👇
👇افزایش تعداد                   👇کاهش تعداد", $r);
        } elseif (strstr($data, "dele")) {
            answer_inline($telegram->callback_query->id, "⛔️");
            $i=0;
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlconnect("update sefareshat set tedad=tedad-1 WHERE chat_id=$chat_id AND token=$a");
            $r = ['inline_keyboard' => []];
            $result = mysqli_query($conn, "select * from sefareshat WHERE chat_id=$chat_id");
            while ($row = mysqli_fetch_array($result)) {
                $tedad = $row['tedad'];
                if ($tedad > 0) {
                    $token1 = $row['token'];
                    $name = sqlget("select name from menu WHERE token=$token1")['name'];
                    $r['inline_keyboard'][][]=['text' => $tedad." $name", 'callback_data' => $tedad.''];
                    $r['inline_keyboard'][][] = ['text' => "➖", 'callback_data' => 'dele'.$token1.''];
                    $r['inline_keyboard'][$i+1][]=['text' => "➕", 'callback_data' => 'afzo'.$token1.''];
                    $i=$i+2;
                }
            }
            $r['inline_keyboard'][][] = ['text' => "تکمیل خرید 🖊", 'callback_data' => 'takmil1'];
            editMessageReplyMarkup($telegram->callback_query->message->message_id, $chat_id, $r);
        }elseif (strstr($data,"afzo")){
            answer_inline($telegram->callback_query->id,"✅");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlconnect("update sefareshat set tedad=tedad+1 WHERE chat_id=$chat_id AND token=$a");
            $r = ['inline_keyboard' => []];
            $i=0;
            $result = mysqli_query($conn, "select * from sefareshat WHERE chat_id=$chat_id");
            while ($row = mysqli_fetch_array($result)) {
                $tedad = $row['tedad'];
                $token1 = $row['token'];
                $name = sqlget("select name from menu WHERE token=$token1")['name'];
                $r['inline_keyboard'][][]=['text' => $tedad." $name", 'callback_data' => $tedad.''];
                $r['inline_keyboard'][][] = ['text' => "➖", 'callback_data' => 'dele'.$token1.''];
                $r['inline_keyboard'][$i+1][]=['text' => "➕", 'callback_data' => 'afzo'.$token1.''];
                $i=$i+2;
            }
            $r['inline_keyboard'][][] = ['text' => "تکمیل خرید 🖊", 'callback_data' => 'takmil1'];
            editMessageReplyMarkup($telegram->callback_query->message->message_id, $chat_id, $r);
        }
        elseif ($data == "takmil1") {
            answer_inline($telegram->callback_query->id,"تکمیل خرید 🖊");
            $q=null;
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $f=0;
            $result = mysqli_query($conn, "select * from sefareshat WHERE chat_id=$chat_id");
            $q="";
            while ($row = mysqli_fetch_array($result)) {


                $tedad = $row['tedad'];
                $token1 = $row['token'];
                $a = sqlget("select gheymat,name from menu WHERE token=$token1");
                $gheymat=$a['gheymat'];
                $name=$a['name'];
                $z="
✍️ نام: $name
💰 قیمت: $gheymat
🗒 تعداد: $tedad
________________________________
";
                $q=$q.$z;
                $f=$gheymat*$tedad+$f;
            }
            $q=$q."💵 قیمت کل سفارش برابر است با $f
 ";
            $r=[
                'inline_keyboard' => [
                    [
                        ['text' => "تایید ✅", 'callback_data' => 'taed']
                    ],
                    [
                        ['text' => "ویرایش سبد خرید 🛒", 'callback_data' => 'takmil']
                    ]
                ]
            ];
            message_inline_query($chat_id,"لیست سفارشات شما به صورت زیر میباشد. 👇

$q
🔴 هزینه پیک تا شعاع 1/5 کیلومتری فروشگاه، رایگان میباشد؛ چنانچه آدرس شما در فواصل بیشتری نسبت به فروشگاه باشد، هزینه پیک بعد از دریافت آدرس محاسبه و منظور میگردد. 😊

در صورت تائید از دکمه زیر استفاده کنید.",$r);
        }elseif ($data=="taed"){
            answer_inline($telegram->callback_query->id,"تایید ✅");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            if(checktemp($chat_id)){
                sendmessage($chat_id,"طبق آخرین سفارش ثبت شده در ربات، اطلاعات شما به صورت زیر است. 👇
برای ویرایش اطلاعات خود، میتوانید از دکمه های زیر استفاده کنید. 👇😊");
                $b=sqlget("select * from temp WHERE  chat_id=$chat_id");
                $phone=$b['phone'];
                $adres=$b['adress'];
                sendlook($chat_id,$b['lati'],$b['longi']);
                $r=[
                    'inline_keyboard' => [
                        [
                            ['text' => "تایید ✅", 'callback_data' => 'areh']
                        ],
                        [
                            ['text' => " ویرایش شماره همراه 📝", 'callback_data' => 'virshomare']
                        ],
                        [
                            ['text' => " ویرایش آدرس و لوکیشن 🗺", 'callback_data' => 'virlook']
                        ]
                    ]
                ];
                message_inline_query($chat_id,"📱 شماره همراه: $phone

🗺  آدرس: $adres",$r);
            }else{
                sqlconnect("update aaza set step='namesef' WHERE chat_id=$chat_id");
                sendmessage($chat_id,"لطفا نام و نام خانوادگی خود را ارسال کنید.🍃🌹");
            }
        }elseif ($data=="areh"){
            answer_inline($telegram->callback_query->id,"تایید ✅");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $b=sqlget("select * from temp WHERE  chat_id=$chat_id");
            $flag=true;
            $f=0;
            $q="متاسفانه چند دقیقه پیش  غذاهایی که در زیر مشاهده میکنید به اتمام رسیدن برای مشاهده مجدد منو و سفارش غذای جایگزین دکمه مشاهده منو و برای دریافت بقیه ی غذا ها گزینه تایید را بزنید";
            $result=mysqli_query($conn,"select * from sefareshat WHERE chat_id=$chat_id");
            $list="";
            $z="";
            while ($row=mysqli_fetch_array($result)){
                $token1=$row['token'];
                $mojod=sqlget("select * from menu WHERE token=$token1");
                $name=$mojod['name'];
                $tedad=$row['tedad'];
                $gheymat=$mojod['gheymat'];
                $list=" 
  ✍️ نام : $name
  💰قیمت : $gheymat تومان
🔑تعداد: $tedad  
________________________________";
                $z=$z.$list;
                $f=$gheymat*$tedad+$f;
                if ($mojod['hide']==true){
                    $flag=false;
                    sqlconnect("delete from sefareshat WHERE chat_id=$chat_id AND token=$token1");
                    $q=$q."
                        $name
                        _____________________
                        ";
                }
            }
            $nam=$b['name'];
            $phon=$b['phone'];
            $adres=$b['adress'];
            $z=$z."
                        قیمت کل برابر است با $f
                  مشخصات خریدار به صورت زیر است:
                   نام: $nam
                   تلفن: $phon
                   ادرس: $adres";
            if ($flag==true){
                $dis=getDistanceBetweenPointsNew($b['lati'],$b['longi']);
                if ($dis<=1900) {
                    sendlook($frosh,$b['lati'],$b['longi']);
                    $r = [
                        'inline_keyboard' => [
                            [
                                ['text' => "خانه 🏠", 'callback_data' => 'start']
                            ]
                        ]
                    ];
                    sqlconnect("update last set token=token+$f WHERE id=3");
                    message_inline_query($chat_id,"سفارشات ثبت شده شما، با پیک ارسال خواهد شد.
هزینه پیک را مهمان ما هستید. 😊",$r);
                    message_inline_query($frosh,"سفارش جدید ثبت شد.
                          $z",$r);
                    message_inline_query($zohre,"سفارش جدید ثبت شد.
                          $z",$r);
                    sqlconnect("delete from sefareshat WHERE chat_id=$chat_id");
                }elseif($dis>4000){
                    if ($f<30000) {
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "سفارش غذای بیشتر", 'callback_data' => 'show']
                                ],
                                [
                                    ['text' => "انصراف", 'callback_data' => 'start']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id, "مشتری گرامی، در صورتیکه مبلغ فاکتور شما بالای 30 هزار تومان باشد، خدمات فروشگاه ما شامل حالتان خواهد شد و 50 درصد هزینه پیک را مهمان ما خواهید بود در غیر این صورت، به علت فاصله زیاد نسبت به فروشگاه، از ارائه خدمات به شما معذوریم. 😔
لطفا دوباره سفارش خود را بررسی کنید و به سفارش خود بیفزایید یا سفارش خود را لغو کنید. 🙏", $r);
                    }else{
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "خانه 🏠", 'callback_data' => 'start']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id,"سفارشات شما با پیک معمولی فرستاده خواهد شد، 50 درصد هزینه پیک را فروشگاه پرداخت میکند و 50 درصد مابقی به عهده خودتان میباشد. 😊",$r);
                        sendlook($frosh,$b['lati'],$b['longi']);
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "خانه 🏠", 'callback_data' => 'start']
                                ]
                            ]
                        ];
                        sqlconnect("update last set token=token+$f WHERE id=3");
                        message_inline_query($frosh,"سفارش جدید ثبت شد.با پیک معمولی ارسال شود.
                          $z",$r);
                        message_inline_query($zohre,"سفارش جدید ثبت شد.
                          $z",$r);
                        sqlconnect("delete from sefareshat WHERE chat_id=$chat_id");
                    }
                }else{
                    sendlook($modir,$b['lati'],$b['longi']);
                    $r = [
                        'inline_keyboard' => [
                            [
                                ['text' => "خانه 🏠", 'callback_data' => 'start']
                            ]
                        ]
                    ];
                    sqlconnect("update last set token=token+$f WHERE id=3");
                    message_inline_query($chat_id,"سفارشات شما با پیک فرستاده خواهد شد، هزینه پیک برای شما مبلع 3500 تومان میباشد. 😊",$r);
                    message_inline_query($frosh,"سفارش جدید ثبت شد و هزینه پیک برابر است با ۳۵۰۰.
                          $z",$r);
                    message_inline_query($zohre,"سفارش جدید ثبت شد.
                          $z",$r);
                    sqlconnect("delete from sefareshat WHERE chat_id=$chat_id");

                }
            }else{
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "سفارش غذای جایگزین 📜", 'callback_data' => 'start']
                        ],
                        [
                            ['text' => "دریافت سفارشات موجود✅", 'callback_data' => 'areh']
                        ],
                    ]
                ];
                message_inline_query($chat_id,"با عرض پوزش در زمانی که شما در حال تکمیل اطلاعات و سفارش بودید غذاهایی که در لیست زیر مشاهده میکنید تمام شد.😔🙈
میتوانید جایگزین غذاهای تمام شده غذای جدید سفارش دهید یا بقیه غذاهایتان که هنوز موجودند را دریافت کنید.🌹🌷

                $q",$r);
            }
        }
        elseif ($data=="virshomare"){
            answer_inline($telegram->callback_query->id,"ویرایش شماره همراه 📝");
            sqlconnect("update aaza set step='editphone' WHERE chat_id=$chat_id");
            sendmessage($chat_id,"لطفا شماره همراه جدید را وارد کنید. 🙏");
        }elseif ($data=="virlook"){
            answer_inline($telegram->callback_query->id,"");
            sqlconnect("update aaza set step='editlook' WHERE chat_id=$chat_id");
            $r = ['keyboard' => [
                [
                    ['text' => "ارسال لوکیشن من📍",'request_location'=>true]
                ]
            ]
            ];
            getlocation($chat_id, "لطفا ابتدا موقعیت یاب (gps) گوشی خود را روشن نموده و سپس با استفاده از دکمه ی زیر مکان خود را اراسال کنید.", $r);

        }
    }
}elseif (isset($telegram->message->photo) == true) {
    $chat_id = $telegram->message->from->id;
    $photo = $telegram->message->photo[0]->file_id;
    $a=sqlget("select step from aaza WHERE chat_id=$chat_id")['step'];
    if ($a=="axmenu"){
        sqlconnect("update aaza set step=NULL WHERE chat_id=$frosh");
        $a=sqlget("select * from last WHERE id=2")['token'];
        $a++;
        sqlconnect("delete from menu where gheymat IS NULL OR name IS NULL OR tozihat IS NULL");
        sqlconnect("insert into menu VALUES (NULL ,NULL ,NULL ,'$photo',TRUE ,$a)");
        sqlconnect("update last set token=$a WHERE id=2");
        sendmessage($chat_id,"نام غذای مورد نظر را ارسال کنید");
        sqlconnect("update aaza set step='namemenu' WHERE chat_id=$chat_id");
    }elseif (strstr($a,"editax")){
        $a = preg_replace("/[^0-9]/", '', $a);
        sqlconnect("update aaza set step=NULL WHERE chat_id=$frosh");
        sqlconnect("update menu set file='$photo' WHERE token=$token");
        sendmessage($frosh,"عکس مورد نظر با موفقیت تغییر کرد");
    }
} elseif (isset($telegram->message->location) == true) {
    $long=$telegram->message->location->longitude;
    $lati=$telegram->message->location->latitude;
    $chat_id = $telegram->message->from->id;
    $a=sqlget("select step from aaza WHERE chat_id=$chat_id")['step'];
    if ($a=="looksef"){
        sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
        $flag=true;
        $f=0;
        $q="متاسفانه چند دقیقه پیش  غذاهایی که در زیر مشاهده میکنید به اتمام رسیدن برای مشاهده مجدد منو و سفارش غذای جایگزین دکمه مشاهده منو و برای دریافت بقیه ی غذا ها گزینه تایید را بزنید";
        sqlconnect("update temp set lati='$lati',longi='$long' WHERE chat_id=$chat_id");
        $b=sqlget("select * from temp WHERE  chat_id=$chat_id");
        $flag=true;
        $result=mysqli_query($conn,"select * from sefareshat WHERE chat_id=$chat_id");
        $list="";
        $z="";
        while ($row=mysqli_fetch_array($result)){
            $token1=$row['token'];
            $mojod=sqlget("select * from menu WHERE token=$token1");
            $name=$mojod['name'];
            $tedad=$row['tedad'];
            $gheymat=$mojod['gheymat'];
            $list=" 
  ✍️ نام : $name
  💰قیمت : $gheymat تومان
🔑تعداد: $tedad  
________________________________";
            $f=$gheymat*$tedad+$f;
            $z=$z.$list;
            if ($mojod['hide']==true){
                $flag=false;
                sqlconnect("delete from sefareshat WHERE chat_id=$chat_id AND token=$token1");
                $q=$q."
                        $name
                        _____________________
                        ";
            }
        }
        $nam=$b['name'];
        $phon=$b['phone'];
        $adres=$b['adress'];
        $z=$z."
                    قیمت کل برابر است با $f
                  مشخصات خریدار به صورت زیر است:
                   نام: $nam
                   تلفن: $phon
                   ادرس: $adres";
        if ($flag==true){
            $dis=getDistanceBetweenPointsNew($b['lati'],$b['longi']);
            if ($dis<=1900) {
                sendlook($frosh,$b['lati'],$b['longi']);
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "خانه 🏠", 'callback_data' => 'start']
                        ]
                    ]
                ];
                sqlconnect("update last set token=token+$f WHERE id=3");
                message_inline_query($chat_id,"سفارشات ثبت شده شما، با پیک ارسال خواهد شد.
هزینه پیک را مهمان ما هستید. 😊",$r);
                message_inline_query($frosh,"سفارش جدید ثبت شد.
                          $z",$r);
                message_inline_query($zohre,"سفارش جدید ثبت شد.
                          $z",$r);
                sqlconnect("delete from sefareshat WHERE chat_id=$chat_id");
            }elseif($dis>4000){
                if ($f<30000) {
                    $r = [
                        'inline_keyboard' => [
                            [
                                ['text' => "ویرایش لوکیشن 🔍", 'callback_data' => 'look']
                            ],
                            [
                                ['text' => "انصراف", 'callback_data' => 'start']
                            ]
                        ]
                    ];
                    message_inline_query($chat_id, "مشتری گرامی، در صورتیکه مبلغ فاکتور شما بالای 30 هزار تومان باشد، خدمات فروشگاه ما شامل حالتان خواهد شد و 50 درصد هزینه پیک را مهمان ما خواهید بود در غیر این صورت، به علت فاصله زیاد نسبت به فروشگاه، از ارائه خدمات به شما معذوریم. 😔
لطفا دوباره سفارش خود را بررسی کنید و به سفارش خود بیفزایید یا سفارش خود را لغو کنید. 🙏", $r);
                }else{
                    $r = [
                        'inline_keyboard' => [
                            [
                                ['text' => "خانه 🏠", 'callback_data' => 'start']
                            ]
                        ]
                    ];
                    message_inline_query($chat_id,"سفارشات شما با پیک معمولی فرستاده خواهد شد، 50 درصد هزینه پیک را فروشگاه پرداخت میکند و 50 درصد مابقی به عهده خودتان میباشد. 😊",$r);
                    sendlook($frosh,$b['lati'],$b['longi']);
                    $r = [
                        'inline_keyboard' => [
                            [
                                ['text' => "خانه 🏠", 'callback_data' => 'start']
                            ]
                        ]
                    ];
                    sqlconnect("update last set token=token+$f WHERE id=3");
                    message_inline_query($frosh,"سفارش جدید ثبت شد.با پیک معمولی ارسال شود.
                          $z",$r);
                    message_inline_query($zohre,"سفارش جدید ثبت شد.
                          $z",$r);
                    sqlconnect("delete from sefareshat WHERE chat_id=$chat_id");
                }
            }else{
                sendlook($modir,$b['lati'],$b['longi']);
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "خانه 🏠", 'callback_data' => 'start']
                        ]
                    ]
                ];
                sqlconnect("update last set token=token+$f WHERE id=3");
                message_inline_query($chat_id,"سفارشات شما با پیک فرستاده خواهد شد، هزینه پیک برای شما مبلع 3500 تومان میباشد. 😊",$r);
                message_inline_query($frosh,"سفارش جدید ثبت شد و هزینه پیک برابر است با ۳۵۰۰.
                          $z",$r);
                message_inline_query($zohre,"سفارش جدید ثبت شد.
                          $z",$r);
                sqlconnect("delete from sefareshat WHERE chat_id=$chat_id");

            }
        }else{
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "مشاهده منو 📜", 'callback_data' => 'start']
                    ],
                    [
                        ['text' => "دریافت سفارشات موجود✅", 'callback_data' => 'areh']
                    ],
                ]
            ];
            message_inline_query($chat_id,"با عرض پوزش در زمانی که شما در حال تکمیل اطلاعات و سفارش بودید غذاهایی که در لیست زیر مشاهده میکنید تمام شد.😔🙈
میتوانید جایگزین غذاهای تمام شده غذای جدید سفارش دهید یا بقیه غذاهایتان که هنوز موجودند را دریافت کنید.🌹🌷

                $q",$r);
        }
    }elseif ($a=="editlook"){
        sqlconnect("update temp set lati='$lati',longi='$long' WHERE chat_id=$chat_id");
        sqlconnect("update aaza set step='editaddres' WHERE chat_id=$chat_id");
        sendmessage($chat_id,"آدرس خود را به صورت کامل و دقیق ارسال کنید.");
    }elseif ($a=="looklog"){
        sqlconnect("update temp set lati='$lati',longi='$long' WHERE chat_id=$chat_id");
        sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
        $r = [
            'inline_keyboard' => [
                [
                    ['text' => "خانه 🏠", 'callback_data' => 'start']
                ]
            ]
        ];
        message_inline_query($chat_id,"ثبت نام با موفقیت انجام شد.",$r);
    }
}elseif (isset($telegram->message)){
    $text = $telegram->message->text;
    $text=urldecode($text);
    $chat_id = $telegram->message->from->id;
    $a=sqlget("select step from aaza WHERE chat_id=$chat_id")['step'];
    if ($chat_id==$zohre){
        if ($text=="/start") {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "مشاهده مبلغ کل فروش", 'callback_data' => 'frosh']
                    ],
                    [
                        ['text' => "تسویه", 'callback_data' => 'tasvieh']
                    ]
                ]
            ];
            message_inline_query($chat_id, "چی چی میخی خواهر؟", $r);
        }elseif ($a=="tasvieh"){
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            sqlconnect("update last set token=token-$text WHERE id=3");
            sendmessage($zohre,"تسویه شد");
        }
    }elseif ($chat_id==$frosh){
        if ($text=="/start"){
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "افزودن به منو", 'callback_data' => 'addmenu']
                    ],
                    [
                        ['text' => "شروع به کار رستوران", 'callback_data' => 'open']
                    ],
                    [
                        ['text' => "تعطیلی رستوران", 'callback_data' => 'close']
                    ],
                    [
                        ['text' => "مشاهده منو 📜", 'callback_data' => 'show']
                    ],
                    [
                        ['text' => "مشاهده مبلغ کل فروش", 'callback_data' => 'pol']
                    ]
                ]
            ];
            message_inline_query($chat_id,"چه خدمتی در نظر دارید",$r);
        }elseif (strstr($a,"editgh")){
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $a = preg_replace("/[^0-9]/", '', $a);
            sqlconnect("update menu set gheymat='$text' WHERE token=$a");
            sendmessage($frosh,"قیمت جدید ثبت شد");
        }

        elseif ($a=="namemenu"){
            $a=sqlget("select * from last WHERE id=2")['token'];
            sqlconnect("update menu set name=N'$text' WHERE token=$a");
            sqlconnect("update aaza set step='tozihmenu' WHERE chat_id=$chat_id");
            sendmessage($chat_id,"لطفا در مورد مواد استفاده شده در غذا توضیحاتی ارسال کنید.");
        }elseif ($a=="tozihmenu"){
            $a=sqlget("select * from last WHERE id=2")['token'];
            sqlconnect("update menu set tozihat=N'$text' WHERE token=$a");
            sqlconnect("update aaza set step='gheymmenu' WHERE chat_id=$chat_id");
            sendmessage($chat_id,"لطفا قیمت غذای مورد نظر را ارسال کنید.");
        }elseif ($a=="gheymmenu"){
            $a=sqlget("select * from last WHERE id=2")['token'];
            sqlconnect("update menu set gheymat='$text',hide=FALSE WHERE token=$a");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "خانه 🏠", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id,"غذا با موفقیت به منو اضافه شد",$r);
        }
    }else{
        if ($text=="/start"){
            if (checkaaza($chat_id)==false){
                sqlconnect("insert into aaza VALUES ($chat_id,NULL )");
            }

            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "مشاهده منو 📜", 'callback_data' => 'show']
                    ],
                    [
                        ['text' => "انتقاد و پیشنهاد 📨", 'callback_data' => 'enteghad']
                    ],
                    [
                        ['text' => "ثبت نام", 'callback_data' => 'login']
                    ],
                    [
                        ['text' => "راهنما 📒", 'callback_data' => 'help']
                    ],

                ]
            ];
            message_inline_query($chat_id,"سلام🙋

به فروشگاه ⭐️\"خانه سیب زمینی\"⭐️ خوش آمدید.

⚜️ مفتخریم که برای اولین بار، در شهر زیبای اصفهان 🇮🇷 با غذای ترکی 🇹🇷 \" کمپیر\" \"kumpir\" میزبان شما عزیزان هستیم.

⚜️ از اینکه ما را به عنوان میزبان خود انتخاب کرده اید، بی نهایت سپاسگذاریم. 🙏😊

⚜️ تمام سعی ما بر این است که لحظاتی خوش در کنار خانواده و دوستان خود داشته باشید. 🌹

⚜️این ربات به شما کمک میکند که غذای دلخواه خود را، به راحتی از منو فروشگاه انتخاب کرده و سفارش دهید.😊

⚜️برای ارتباط با ما، صفحه زیر را در اینستاگرام دنبال کنید.🙏👇

📱https://www.instagram.com/potato_home_esfahan/",$r);

        }else{
            $a=sqlget("select step from aaza WHERE chat_id=$chat_id")['step'];
            if ($a=="namesef"){
                sqlconnect("insert into temp VALUES ($chat_id,N'$text',NULL ,NULL ,NULL ,NULL )");
                sendmessage($chat_id,"لطفا شماره تلفن خود را ارسال کنید");
                sqlconnect("update aaza set step='phonesef' WHERE chat_id=$chat_id");
            }elseif ($a=="namelog"){
                sqlconnect("insert into temp VALUES ($chat_id,N'$text',NULL ,NULL ,NULL ,NULL )");
                sendmessage($chat_id,"لطفا شماره تلفن خود را ارسال کنید");
                sqlconnect("update aaza set step='phonelog' WHERE chat_id=$chat_id");
            }elseif ($a=="phonelog"){
                sqlconnect("update temp set phone=N'$text' WHERE chat_id=$chat_id");
                sqlconnect("update aaza set step='adreslog' WHERE chat_id=$chat_id");
                sendmessage($chat_id,"لطفا ادرس دقیق خود را ارسال کنید");
            }elseif ($a=="addreslog"){
                sqlconnect("update temp set adress='$text' WHERE chat_id=$chat_id");
                sqlconnect("update aaza set step='looklog' WHERE chat_id=$chat_id");
                $r = ['keyboard' => [
                    [
                        ['text' => "ارسال لوکیشن من",'request_location'=>true]
                    ]
                ]
                ];
                getlocation($chat_id, "لطفا ابتدا موقعیت یاب (gps) گوشی خود را روشن نموده و سپس با استفاده از دکمه ی زیر مکان خود را اراسال کنید.", $r);
            }
            elseif ($a=="enteghad"){
                sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
                sendmessage($zohre,$text);
                sendmessage($chat_id,"پیام شما به صورت ناشناس به مدیریت فروشگاه ارسال شد. 📩
                                                  با تشکر 🙏");
            }
            elseif ($a=="phonesef"){
                sqlconnect("update temp set phone=N'$text' WHERE chat_id=$chat_id");
                sqlconnect("update aaza set step='adressef' WHERE chat_id=$chat_id");
                sendmessage($chat_id,"لطفا ادرس دقیق خود را ارسال کنید");
            }
            elseif ($a=="adressef"){
                sqlconnect("update temp set adress='$text' WHERE chat_id=$chat_id");
                sqlconnect("update aaza set step='looksef' WHERE chat_id=$chat_id");
                $r = ['keyboard' => [
                    [
                        ['text' => "ارسال لوکیشن من",'request_location'=>true]
                    ]
                ]
                ];
                getlocation($chat_id, "لطفا ابتدا موقعیت یاب (gps) گوشی خود را روشن نموده و سپس با استفاده از دکمه ی زیر مکان خود را اراسال کنید.", $r);
            }elseif ($a=="editphone"){
                sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
                sqlconnect("update temp set phone='$text' WHERE chat_id=$chat_id");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "ویرایش آدرس 📝", 'callback_data' => 'virlook']
                        ],
                        [
                            ['text' => "تایید ✅", 'callback_data' => 'areh']
                        ]
                    ]
                ];
                message_inline_query($chat_id,"شماره همراه جدید شما ثبت شد. 😊✅",$r);
            }elseif ($a=="editaddres"){
                sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
                sqlconnect("update temp set adress=N'$text' WHERE chat_id=$chat_id");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "ویرایش شماره همراه 📝", 'callback_data' => 'virphone']
                        ],
                        [
                            ['text' => "تایید ✅", 'callback_data' => 'areh']
                        ]
                    ]
                ];
                message_inline_query($chat_id,"آدرس شما ثبت شد. 😊
برای ویرایش شماره همراه از دکمه زیر استفاده کنید، در صورت اطمینان از اطلاعات دکمه تائید را برای ارسال نهایی کلیک کنید. 😊",$r);

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
    $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chat_id&text=$text&reply_markup=" . $encodedMarkup;
    file_get_contents($url);
}

function getupdates()
{
    global $telegram;
    $last = sqlget("SELECT token FROM last WHERE id=1")['token'];
    $last++;
    global $token;
    $url = 'https://api.telegram.org/bot' . $token . '/getupdates?offset=' . $last;
    $telegram = json_decode(file_get_contents($url));
    $count = count($telegram->result);
    if ($count > 0) {
        $l = $telegram->result[$count - 1]->update_id;
        $sql = "UPDATE last SET token=" . $l . " WHERE id=1;";
        sqlconnect($sql);
    }
    return $telegram;
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
    global $frosh;
    if ($chat_id !=$frosh and $result->num_rows >= 1) {
        sendmessage($chat_id, "لیست غذاهایی که هم اکنون در فروشگاه آماده سرو است به صورت زیر میباشد.👇
1⃣ برای سفارش هر غذا میتوانید به هر تعداد که مدنظر دارید بر روی دکمه پایین هر غذا کلیک کنید.(✅برای مثال برای سفارش سه رول سیب زمینی باید سه بار بر روی دکمه پایین رول کلیک کنید)
2⃣در پایان میتوانید بر روی دکمه تکمیل خرید کلیک کنید تا فاکتور شما صادر شود و لیست سفارشات خود را مشاهده کنید.🙏
3⃣ در صورتی که در سفارشات خود دچار اشتباه شدید میتوانید در مرحله بعدی فاکتور خود را ویرایش کنید.☺️❤️");
    } elseif ($result->num_rows == 0) {
        sendmessage($chat_id, "با عرض پوزش هم اکنون فروشگاه آماده سرویس دهی نمیباشد.🙈
امیدواریم در فرصتی دیگر دوباره ما را لایق ارائه خدمات بدانید🌷🌺");
    }
    if ($result->num_rows >= 1){
        while ($row = mysqli_fetch_array($result)) {
            $tozihat = $row['tozihat'];
            $name = $row['name'];
            $file = $row['file'];
            $gheymat = $row['gheymat'];
            $to = $row['token'];
            $hide=$row['hide'];
            if ($chat_id == $frosh) {
                if ($hide==true){
                    $r = [
                        'inline_keyboard' => [
                            [
                                ['text' => "اعلام موجود", 'callback_data' => $to . 'hide']
                            ],
                            [
                                ['text' => " ویرایش قیمت", 'callback_data' => $to . 'editgh']
                            ],
                            [
                                ['text' => " ویرایش عکس", 'callback_data' => $to . 'editax']
                            ]
                        ]
                    ];
                }else {
                    $r = [
                        'inline_keyboard' => [
                            [
                                ['text' => "اعلام اتمام", 'callback_data' => $to . 'hide']
                            ],
                            [
                                ['text' => " ویرایش قیمت", 'callback_data' => $to . 'editgh']
                            ],
                            [
                                ['text' => " ویرایش عکس", 'callback_data' => $to . 'editax']
                            ]
                        ]
                    ];
                }
            } else {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "سفارش غذا 📋", 'callback_data' => $to . 'addkh']
                        ]
                    ]
                ];
            }
            $text = "🥔 نام: $name
            
👨‍🍳 شامل: $tozihat

💰 قیمت: $gheymat";
            sendphoto($chat_id, $text, $file, $r);
        }
        if ($chat_id == $frosh) {
            $r = [
                'inline_keyboard' => [

                    [
                        ['text' => "خانه 🏠", 'callback_data' => 'start']
                    ]
                ]
            ];
            $e = $result->num_rows;
            $text = "تعداد غذاهای آماده سرو $e عدد میباشد.";
            message_inline_query($chat_id, $text, $r);
        } else {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "تکمیل خرید 🛒", 'callback_data' =>'takmil']
                    ],
                    [
                        ['text' => "خانه 🏠", 'callback_data' => 'start']
                    ]
                ]
            ];
            $e = $result->num_rows;
            $text = "تعداد غذاهای آماده سرو $e عدد میباشد.";
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
function checksefaresh($chat_id,$token){
    $sql = "SELECT chat_id FROM sefareshat WHERE chat_id=$chat_id AND token=$token";
    $result = sqlget($sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}
function editMessageReplyMarkup($m_id, $chat_id, $re){
    $e = json_encode($re, true);
    global $token;
    $url = "https://api.telegram.org/bot$token/editMessageReplyMarkup?chat_id=$chat_id&message_id=$m_id&reply_markup=$e";
    file_get_contents($url);
}
function sendlook($chat_id,$lati,$long){
    global $token;
    $url = "https://api.telegram.org/bot$token/sendLocation?chat_id=$chat_id&latitude=$lati&longitude=$long";
    file_get_contents($url);


}
function checktemp($chat_id){
    $sql = "SELECT chat_id FROM temp WHERE chat_id=$chat_id";
    $result = sqlget($sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}
function getDistanceBetweenPointsNew( $latitude2, $longitude2)
{
    $latitude1=32.631162;
    $longitude1=51.652981;
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;

    $kilometers = $miles * 1.609344;

    $meters = $kilometers * 1000;

    return $meters;

}
function getlocation($chat_id,$text,$r){
    global $token;
    $encodedMarkup = json_encode($r, true);
    $text = urlencode($text);
    $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chat_id&text=$text&reply_markup=" .$encodedMarkup;
    file_get_contents($url);
}
