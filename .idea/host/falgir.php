<?php
$servername="localhost";
$username="mychanneladminbot";
$pass="qOA4g8EBDCbXJgjdlkD4yMsw2";
$dbname="falgir";
$token="501147364:AAFYAtMSqyNBu60H1uoKlHELh5JDSesPHGs";
$modir="249";
$conn = mysqli_connect($servername, $username, $pass, $dbname) or die ('Failed to connect to database');
mysqli_query($conn, "SET NAMES 'utf8mb4'");
mysqli_query($conn, "SET CHARACTER SET 'utf8mb4'");
mysqli_query($conn, "SET character_set_connection = 'utf8mb4'");
$telegram = json_decode(file_get_contents("php://input"));
$channel=sqlget("select step from aaza WHERE chat_id=1")['step'];
            if (isset($telegram->callback_query)) {
                $chat_id = $telegram->callback_query->message->chat->id;
                $data = $telegram->callback_query->data;
                if ($data == "ques") {
                    if ($channel == null or checkmember($channel, $chat_id) == "member") {
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "ویرایش سوالات من", 'callback_data' => 'virsoal']
                                ],
                                [
                                    ['text' => "دریافت لینک من", 'callback_data' => 'mylink']
                                ]
                            ]
                        ];
                        $question = array("1- اگر بهت بگن ۱ میلیارد برنده شدی،چقدرش به دیگران کمک میکنی؟ چرا؟", "2- تاحالا عاشق شدی،اسمش چی بوده؟",
                            "3- تو زندگیت چه فردی رو بیشتر از همه دوست داری؟چرا؟", "4- یکی از آرزو هات که هنوز برآورده نشده؟؟",
                            "5- تو زندگیت  آیا شده به‌کسی حسودی کنی و بخوای جاش باشی؟؟چرا؟؟", "6- اگر بتونی به خاطره از گذشته زندگیت پاک کنی اون چیه؟چرا؟؟؟",
                            "7- بزرگترین دروغی که گفتی چی بوده؟؟", "8- از کی بیشتر از همه نفرت داری ؟ چرا ؟؟؟");
                        $resul = mysqli_query($conn, "select * from soal WHERE chat_id=$chat_id");
                        if ($resul->num_rows > 0) {
                            while ($row = mysqli_fetch_array($resul)) {
                                $question[$row['num'] - 1] = $row['soal'];
                            }
                            $text = $question[0] . "\n\n" . $question[1] . "\n\n" . $question[2] . "\n\n" . $question[3] . "\n\n" . $question[4] . "\n\n" . $question[5] . "\n\n" . $question[6] . "\n\n" . $question[7];
                            message_inline_query($chat_id, $text, $r);
                        } else {

                            message_inline_query($chat_id, "1- اگر بهت بگن ۱ میلیارد برنده شدی،چقدرش به دیگران کمک میکنی؟ چرا؟

2- تاحالا عاشق شدی،اسمش چی بوده؟

3- تو زندگیت چه فردی رو بیشتر از همه دوست داری؟چرا؟

4- یکی از آرزو هات که هنوز برآورده نشده؟؟

5- تو زندگیت  آیا شده به‌کسی حسودی کنی و بخوای جاش باشی؟؟چرا؟؟

6- اگر بتونی به خاطره از گذشته زندگیت پاک کنی اون چیه؟چرا؟؟؟

7- بزرگترین دروغی که گفتی چی بوده؟؟

8- از کی بیشتر از همه نفرت داری ؟ چرا ؟؟؟", $r);
                        }
                    } else {
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "عضو شدم", 'callback_data' => 'ques']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id, "🍃  برای استفاده از این ربات لازم است ابتدا وارد کانال زیر شوید 

$channel $channel  📣
$channel $channel  📣

☑️ بعد از عضویت در کانال میتوانید از دکمه ها استفاده کنید", $r);
                    }
                }elseif (strstr($data,"show")) {
                    $a = preg_replace("/[^0-9]/", '', $data);
                    $from = $chat_id;
                    $chat_id = $a;


                    if ($channel == null or checkmember($channel, $chat_id) == "member") {
                        $q = "";
                        $re = mysqli_query($conn, "select * from answer WHERE chat_id=$chat_id");
                        while ($row = mysqli_fetch_array($re)) {
                            $q = $q . "\n" . "$row[1]. $row[2]";
                        }
                        htmlmessage($from, "کاربر                             <a href=\"tg://user?id=$chat_id\">$chat_id</a>
 به همه ی سوالات جواب داد. دهن لق هم خودتی!!!");
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "میخوام سوالامو عوض کنم", 'callback_data' => 'virsoal']
                                ],
                                [
                                    ['text' => "میخوام سوالامو ببینم", 'callback_data' => 'ques']
                                ],
                            ]
                        ];
                        message_inline_query($from, $q, $r);
                        sqlconnect("delete from answer WHERE chat_id=$chat_id");
                    }
                }elseif ($data == "start") {
                    answer_inline($telegram->callback_query->id, "");
                    if ($channel == null or checkmember($channel, $chat_id) == "member") {
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "دریافت لینک من", 'callback_data' => 'mylink']
                                ],
                                [
                                    ['text' => "مشاهده سوالات پیش فرض", 'callback_data' => 'ques']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id, "به ربات اعتراف گیر خوش اومدی😂😂
          این ربات یه نوع ربات اعتراف گیری هستش
سوالاتتو تو ربات ذخیره کن و لینک مخصوص خودتو بگیر و برای دوستات بفرست تا جواب سوالاتتو بگیری😜

لینک اختصاصیتو بگیر و برای دوستات فوروارد کن و ازشون بخواه تا فالشونو بگیرن و از آیندشون باخبر بشن..یکمی هم از ربات تعریف کن و بگو همه چیو درست جواب میده و خیلی عالیه و حتما امتحان کنن، تا به سوالاتون جواب بدن😜
    بعضی دوستان میان میگن چرا جواب سوالا بهم ارسال نمیشه.. دقت کنین لینک مخصوص خودتونو برای دوستاتون ارسال کنید نه لینکی که از دوستتون گرفتین
    فقط حواست باشه اگه میخوایی پیام ها بهت ارسال بشن حتما باید تو کانال ما عضو باشی
    قبل از هر چیزی سوال های مخصوص خودت رو درست کن تا همونا از دوستات پرسیده بشن وگرنه سوال های پیش فرض ما پرسیده میشن.", $r);
                    } else {
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "عضو شدم", 'callback_data' => 'start']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id, "🍃  برای استفاده از این ربات لازم است ابتدا وارد کانال زیر شوید 

$channel $channel  📣
$channel $channel  📣

☑️ بعد از عضویت در کانال میتوانید از دکمه ها استفاده کنید", $r);
                    }
                }elseif ($data == "mylink") {
                    answer_inline($telegram->callback_query->id, "لینک من🏡");

                    if ($channel==null or checkmember($channel,$chat_id)=="member") {

                    htmlmessage($chat_id, "این ربات از 8 تا سوال میپرسه،اگر به این سوالات پاسخ درست بدی، اتفاقات  که در آینده قراره بیوفته واست رو شرح میده🤗اگر شگفت زده نشدی 😏 دیگه امتحان نکن☺️

    💫اگر به فال و این موضوعات اعتقاد نداری ،من بهت پیشنهاد میکنم کمی وقت بذاری وبه سوالات پاسخ بدی ، مطمعن باش جوابی بهت میده که شگفت زده میشی 😲 و قدرت فال و ربات رو میبینی 


    💥لطفا پس از دریافت جواب ،خونسردی خودتون رو حفظ کنید و اگر رضایت داشتین ربات به دوستان خودتون معرفی کنید ❤️👇👇👇
                <a href=\"http://telegram.me/newfaalgirBot?start=$chat_id\">http://telegram.me/newfaalgirBot?start</a>
");
                    sendmessage($chat_id, "پیام بالا رو به دوستات فوروارد کن و ازشون بخواه تا فالشونو بگیرن و از آیندشون باخبر بشن..یکمی هم از ربات تعریف کن و بگو همه چیو درست جواب میده و خیلی عالیه و حتما امتحان کنن، تا به سوالاتون جواب بدن😜
    بعضی دوستان میان میگن چرا جواب سوالا بهم ارسال نمیشه.. دقت کنین لینک مخصوص خودتونو برای دوستاتون ارسال کنید نه لینکی که از دوستتون گرفتین
    فقط حواست باشه اگه میخوایی پیام ها بهت ارسال بشن حتما باید تو کانال ما عضو باشی");
                    } else {
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "عضو شدم", 'callback_data' => 'mylink']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id, "🍃  برای استفاده از این ربات لازم است ابتدا وارد کانال زیر شوید 

$channel $channel  📣
$channel $channel  📣

☑️ بعد از عضویت در کانال میتوانید از دکمه ها استفاده کنید", $r);
                    }
                    } elseif ($data == "virsoal") {
                    answer_inline($telegram->callback_query->id, "ویرایش سوالات");
                    if ($channel==null or checkmember($channel,$chat_id)=="member") {

                        $r = [
                        'inline_keyboard' => [
                            [
                                ['text' => "سوال ۲", 'callback_data' => 'edit2'], ['text' => "سوال ۱", 'callback_data' => 'edit1']
                            ],
                            [
                                ['text' => "سوال ۴", 'callback_data' => 'edit4'], ['text' => "سوال ۳", 'callback_data' => 'edit3']
                            ],
                            [
                                ['text' => "سوال ۶", 'callback_data' => 'edit6'], ['text' => "سوال ۵", 'callback_data' => 'edit5']
                            ],
                            [
                                ['text' => "سوال ۸", 'callback_data' => 'edit8'], ['text' => "سوال ۷", 'callback_data' => 'edit7']
                            ],
                        ]
                    ];
                    message_inline_query($chat_id, "کدوم سوالو میخوای عوض کنی؟", $r);
                    } else {
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "عضو شدم", 'callback_data' => 'virsoal']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id, "🍃  برای استفاده از این ربات لازم است ابتدا وارد کانال زیر شوید 

$channel $channel  📣
$channel $channel  📣

☑️ بعد از عضویت در کانال میتوانید از دکمه ها استفاده کنید", $r);
                    }
                } elseif (strstr($data, "edit")) {
                    $a = preg_replace("/[^0-9]/", '', $data);
                    if ($channel==null or checkmember($channel,$chat_id)=="member") {
                    answer_inline($telegram->callback_query->id, "ویرایش");
                    sendmessage($chat_id, "سوالی که میخوای جایگزین کنم رو بفرس برام.");
                    sqlconnect("update aaza set step='$data'");
                        } else {
                            $r = [
                                'inline_keyboard' => [
                                    [
                                        ['text' => "عضو شدم", 'callback_data' => 'edit'.$a]
                                    ]
                                ]
                            ];
                            message_inline_query($chat_id, "🍃  برای استفاده از این ربات لازم است ابتدا وارد کانال زیر شوید 

$channel $channel  📣
$channel $channel  📣

☑️ بعد از عضویت در کانال میتوانید از دکمه ها استفاده کنید", $r);
                        }
                }elseif ($data=="answer") {
                    answer_inline($telegram->callback_query->id, "پاسخگویی");
                        if ($channel==null or checkmember($channel,$chat_id)=="member") {
                            sqlconnect("update aaza set step='answer1' WHERE chat_id=$chat_id");
                        $from = sqlget("select * from aaza WHERE chat_id=$chat_id")['from'];
                        $soal = sqlget("select soal from soal WHERE chat_id=$from AND num=1");
                        if ($soal != null) {
                            sendmessage($chat_id, $soal);
                        } else {
                            sendmessage($chat_id, "1- اگر بهت بگن ۱ میلیارد برنده شدی،چقدرش به دیگران کمک میکنی؟ چرا؟");
                        }
                        } else {
                            $r = [
                                'inline_keyboard' => [
                                    [
                                        ['text' => "عضو شدم", 'callback_data' => 'answer']
                                    ]
                                ]
                            ];
                            message_inline_query($chat_id, "🍃  برای استفاده از این ربات لازم است ابتدا وارد کانال زیر شوید 

$channel $channel  📣
$channel $channel  📣

☑️ بعد از عضویت در کانال میتوانید از دکمه ها استفاده کنید", $r);
                        }
                }
            } elseif (isset($telegram->message->text) == true) {
                $chat_id = $telegram->message->from->id;
                $text = $telegram->message->text;
                $message_id = $telegram->message->message_id;
                if ($text == "/start") {
                    if (checkaaza($chat_id) == false) {
                        sqlconnect("insert into aaza VALUES ($chat_id,NULL,NULL )");
                    }
                    if ($channel==null or checkmember($channel,$chat_id)=="member") {

                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "دریافت لینک من", 'callback_data' => 'mylink']
                                ],
                                [
                                    ['text' => "مشاهده سوالات پیش فرض", 'callback_data' => 'ques']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id, "به ربات اعتراف گیر خوش اومدی😂😂
          این ربات یه نوع ربات اعتراف گیری هستش
سوالاتتو تو ربات ذخیره کن و لینک مخصوص خودتو بگیر و برای دوستات بفرست تا جواب سوالاتتو بگیری😜

لینک اختصاصیتو بگیر و برای دوستات فوروارد کن و ازشون بخواه تا فالشونو بگیرن و از آیندشون باخبر بشن..یکمی هم از ربات تعریف کن و بگو همه چیو درست جواب میده و خیلی عالیه و حتما امتحان کنن، تا به سوالاتون جواب بدن😜
    بعضی دوستان میان میگن چرا جواب سوالا بهم ارسال نمیشه.. دقت کنین لینک مخصوص خودتونو برای دوستاتون ارسال کنید نه لینکی که از دوستتون گرفتین
    فقط حواست باشه اگه میخوایی پیام ها بهت ارسال بشن حتما باید تو کانال ما عضو باشی
    قبل از هر چیزی سوال های مخصوص خودت رو درست کن تا همونا از دوستات پرسیده بشن وگرنه سوال های پیش فرض ما پرسیده میشن.", $r);
                    }else{
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "عضو شدم", 'callback_data' => 'start']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id,"🍃  برای استفاده از این ربات لازم است ابتدا وارد کانال زیر شوید 

$channel $channel  📣
$channel $channel  📣

☑️ بعد از عضویت در کانال میتوانید از دکمه ها استفاده کنید",$r);
                    }

                    } elseif (strstr($text, "/start")) {
                    $a = preg_replace("/[^0-9]/", '', $text);
                    if ($channel==null or checkmember($channel,$chat_id)=="member") {
                        if (checkaaza($chat_id) == false) {
                            sqlconnect("insert into aaza VALUES ($chat_id,NULL,$a )");

                        } else {
                            sqlconnect("update aaza set `from`=$a WHERE chat_id=$chat_id");
                        }
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "بریم جواب بدیم", 'callback_data' => 'answer']
                                ]
                            ]
                        ];
                        message_inline_query($chat_id, "
سلام دوست من 
این ربات از 8 تا سوال میپرسه،اگر به این سوالات پاسخ درست بدی، اتفاقات  که در آینده قراره بیوفته واست رو شرح میده🤗اگر شگفت زده نشدی 😏 دیگه امتحان نکن☺️

💫اگر به فال و این موضوعات اعتقاد نداری ،من بهت پیشنهاد میکنم کمی وقت بذاری وبه سوالات پاسخ بدی ، مطمعن باش جوابی بهت میده که شگفت زده میشی 😲 و قدرت فال و ربات رو میبینی 


💥لطفا پس از دریافت جواب ،خونسردی خودتون رو حفظ کنید و اگر رضایت داشتین ربات به دوستان خودتون معرفی کنید ❤️", $r);
                    }else{
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "عضو شدم", 'callback_data' => 'start'.$a]
                                ]
                            ]
                        ];
                        message_inline_query($chat_id,"🍃  برای استفاده از این ربات لازم است ابتدا وارد کانال زیر شوید 

$channel $channel  📣
$channel $channel  📣

☑️ بعد از عضویت در کانال میتوانید از دکمه ها استفاده کنید",$r);
                    }
                    } else {
                    $a = sqlget("select step from aaza WHERE chat_id=$chat_id")['step'];
                    if (strstr($a, "edit")) {
                        sqlconnect("update aaza set step=NULL  WHERE chat_id=$chat_id");
                        $b = preg_replace("/[^0-9]/", '', $a);
                        $text = "$b-$text";
                        if (checkques($chat_id, $b) != false) {
                            sqlconnect("update soal set soal=N'$text' WHERE chat_id=$chat_id AND num=$b");

                        } else {
                            sqlconnect("insert into soal VALUES ($chat_id,$b,N'$text')");
                        }
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "بقیه سوالا رو هم میخوام عوض کنم.", 'callback_data' => 'virsoal']
                                ],
                                [
                                    ['text' => "سوالامو ببینم", 'callback_data' => 'ques']
                                ],
                            ]
                        ];
                        message_inline_query($chat_id, "سوال شماره $b عوض شد دیگه چی کار کنیم؟", $r);
                    } elseif (strstr($a, "answer")) {
                        $b = preg_replace("/[^0-9]/", '', $a);
                        sqlconnect("insert into answer VALUES ($chat_id,$b,N'$text')");
                        if ($b < 8) {
                            $b++;
                            sqlconnect("update aaza set step='answer$b' WHERE chat_id=$chat_id");
                            $question = array("1- اگر بهت بگن ۱ میلیارد برنده شدی،چقدرش به دیگران کمک میکنی؟ چرا؟", "2- تاحالا عاشق شدی،اسمش چی بوده؟",
                                "3- تو زندگیت چه فردی رو بیشتر از همه دوست داری؟چرا؟", "4- یکی از آرزو هات که هنوز برآورده نشده؟؟",
                                "5- تو زندگیت  آیا شده به‌کسی حسودی کنی و بخوای جاش باشی؟؟چرا؟؟", "6- اگر بتونی به خاطره از گذشته زندگیت پاک کنی اون چیه؟چرا؟؟؟",
                                "7- بزرگترین دروغی که گفتی چی بوده؟؟", "8- از کی بیشتر از همه نفرت داری ؟ چرا ؟؟؟");
                            $from = sqlget("select * from aaza WHERE chat_id=$chat_id")['from'];
                            $soal = sqlget("select soal from soal WHERE chat_id=$from AND num=$b");
                            if ($soal != null) {
                                sendmessage($chat_id, $soal);
                            } else {
                                sendmessage($chat_id, $question[$b - 1]);
                            }
                        } else {
                            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
                            $r = [
                                'inline_keyboard' => [
                                    [
                                        ['text' => "دریافت لینک من", 'callback_data' => 'mylink']
                                    ],
                                    [
                                        ['text' => "مشاهده سوالات پیش فرض", 'callback_data' => 'ques']
                                    ]
                                ]
                            ];
                            sendmessage($chat_id, "نتیجه فال شما به این صورت میباشد👇👇👇");
                            sendmessage($chat_id, "به ربات اعتراف گیر خوش اومدی😂😂
          این ربات یه نوع ربات اعتراف گیری هستش و هر سوالی که جواب دادین مستقیم به کسی که این ربات و بهتون معرفی کرد ارسال شده و ایشون سوالاتی که نمیتونست مستقیم ازتون بپرسه رو اینجا پرسیده جوابشو گرفت😂
          حالا دیگه اتفاقیه که افتاده و نمیشه کاریش کرد😜
          اگه تو هم سوالی داری که میخوایی از کسی بپرسی با این ربات میتونی سوالاتتو تو ربات ذخیره کنی و بعد لینک مخصوص خودتو بگیری و به دوستات بفرستی تا سوالاتتو جواب بدن و جوابشون مستقیم به تو ارسال بشه😍️");
                            message_inline_query($chat_id, "سوالاتتو تو ربات ذخیره کن و لینک مخصوص خودتو بگیر و برای دوستات بفرست تا جواب سوالاتتو بگیری😜

      🚫دوست من مسولیت استفاده این ربات برعهده خود شماست 
      این ربات جنبه فان داره هر گونه مشکلی به وجود بیاد بر عهده خود شماست 🌹
      امیدوارم لذت ببرید", $r);
                            $from = sqlget("select * from aaza WHERE chat_id=$chat_id")['from'];
                            if ($channel==null or checkmember($channel,$chat_id)=="member") {
                            $q = "";
                            $re = mysqli_query($conn, "select * from answer WHERE chat_id=$chat_id");
                            while ($row = mysqli_fetch_array($re)) {
                                $q = $q . "\n" . "$row[1]. $row[2]";
                            }
                            $q = $q . "\n\n" . "8.$text";
                            htmlmessage($from, "کاربر                             <a href=\"tg://user?id=$chat_id\">$chat_id</a>
 به همه ی سوالات جواب داد. دهن لق هم خودتی!!!");
                            $r = [
                                'inline_keyboard' => [
                                    [
                                        ['text' => "میخوام سوالامو عوض کنم", 'callback_data' => 'virsoal']
                                    ],
                                    [
                                        ['text' => "میخوام سوالامو ببینم", 'callback_data' => 'ques']
                                    ],
                                ]
                            ];
                            message_inline_query($from, $q, $r);
    sqlconnect("delete from answer WHERE chat_id=$chat_id");

}else{
                                $r = [
                                    'inline_keyboard' => [
                                        [
                                            ['text' => "عضو شدم", 'callback_data' => 'show'.$a]
                                        ]
                                    ]
                                ];
                                message_inline_query($chat_id,"یک نفر با لینکت به سوالا جواب داده اگه میخوای جواباشو ببینی تو کانال ما عضو شو و بعد با دکمه ی پایینی جوابا رو ببین 

$channel $channel  📣
$channel $channel  📣

☑️ بعد از عضویت در کانال میتوانید از دکمه ها استفاده کنید",$r);
                            }
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
    $text=urlencode($text);
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
function checkmember($chat_id,$userid){
    global $token;
    $url = "https://api.telegram.org/bot$token/getChatMember?chat_id=$chat_id&user_id=$userid";
    $a=json_decode(file_get_contents($url));
    return $a->result->status;
}
function checkques($chat_id,$num){
    global $conn;
$a=    mysqli_query($conn,"select * from soal WHERE chat_id=$chat_id AND num=$num");
if ($a->num_rows==0){
    return false;
}else{
    return true;
}
}
function checkall($chat_id){
    global $conn;
    $a=    mysqli_query($conn,"select * from soal WHERE chat_id=$chat_id");
    if ($a->num_rows==0){
        return false;
    }else{
        return true;
    }
}
function htmlmessage($chat_id,$text){
    global $token;
    $url = "https://api.telegram.org/bot$token/sendmessage?chat_id=$chat_id&text=$text&parse_mode=html";
    file_get_contents($url);
}
