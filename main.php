<?php
ob_start();
set_time_limit(0);
$modir = "98357935";
$id = "451113352";
$telegram = json_decode(file_get_contents("php://input"));
$lang=1;
if (isset($telegram->callback_query)) {
    $chat_id = $telegram->callback_query->message->chat->id;
    $user = new user($chat_id);
    $data = $telegram->callback_query->data;
    if ($chat_id == $modir) {
        $user->setstep("null");
        if ($data == "start") {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "تعداد اعضا", 'callback_data' => 'user_count']
                    ],

                    [
                        ['text' => "افزودن کانال", 'callback_data' => 'addch']
                    ],
                    [
                        ['text' => "حذف کانال", 'callback_data' => 'delch']
                    ],
                    [
                        ['text' => "لیست کانالها", 'callback_data' => 'showch']
                    ],
                    [
                        ['text' => "خانه", 'callback_data' => 'start']
                    ]
                ]
            ];
            telegramApi::message_inline_query($modir, "چه خدمتی در نظر دارید؟؟", $r);
        } elseif ($data == "user_count") {
            $user_count = $user::get_user_count();
            telegramApi::sendmessage($chat_id, "تعداد اعضا برابر است با $user_count");
        } elseif ($data == "tabligh") {
            telegramApi::sendmessage($modir, "تبلیغ را ارسال کنید");
            $user->setstep("tablih");
        } elseif ($data == "addch") {
            $user->setstep("addch");
            telegramApi::sendmessage($chat_id, "ایدی کانال را وارد کنید");
        } elseif (strstr($data, "dast")) {
            $user->setstep("null");
            channel::AddToDatabase($data);
            $r = ['inline_keyboard' => [[['text' => "فارسی", 'callback_data' => 'lang1' . $id]], [['text' => "انگلیسی", 'callback_data' => 'lang2' . $id]]]];
            telegramApi::message_inline_query($modir, "زبان کانال را انتخاب کنید", $r);
        } elseif (strstr($data, "lang")) {
            channel::AddToDatabase($data);
            telegramApi::sendmessage($modir, "کانال با موفقیت اضافه شد");
        } elseif ($data == "showch") {
            channel::sendAllchannel($modir);
        } elseif ($data == "delch") {
            $user->setstep("delch");
            telegramApi::sendmessage($modir, "آیدی کانال مورد نظر را برای حذف انتخاب کنید");
        }
    } else {
    if ($data == "start") {
        $user->setstep("null");
        if ($lang == 1) {
            telegramApi::answer_inline($telegram->callback_query->id, "خانه");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "جستجو🔎", 'callback_data' => 'search']
                    ],
                    [
                        ['text' => "گوش به زنگ🔔", 'callback_data' => 'gosh']
                    ],
                    [
                        ['text' => "جستجوی پیشرفته🔎", 'callback_data' => 'searchp']
                    ],
                    [
                        ['text' => "میخوام ببینم👀", 'callback_data' => 'see']
                    ],
                    [
                        ['text' => "راهنما💡", 'callback_data' => 'help']
                    ],

                ]
            ];
            telegramApi::message_inline_query($chat_id, "سلام    😊🖐
من ربات سیکر (جستجوگر) هستم  
من میتونم توی مطالب تلگرام جستجو کنم 🔎 
 فقط کافیه بهم بگی دنبال چی میگردی تا برات پیداش کنم 🤔  
من مثل یه گوگل برای محتوای تلگرام هستم 😉", $r);
        } else {
            telegramApi::answer_inline($telegram->callback_query->id, "home");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "Search🔎", 'callback_data' => 'search']
                    ],
                    [
                        ['text' => "Buzz Me! 🔔", 'callback_data' => 'gosh']
                    ],
                    [
                        ['text' => "Advanced Search🔎", 'callback_data' => 'searchp']
                    ],
                    [
                        ['text' => "I want see👀", 'callback_data' => 'see']
                    ],
                    [
                        ['text' => "Help💡", 'callback_data' => 'help']
                    ],

                ]
            ];
            telegramApi::message_inline_query($chat_id, "Hi 🖐🏻😊
I'm seeker 
I can search telegram content  for any thing you want 🔎
Just tell me what you want and i will find it for you 😉

 It's Seeker 
 Finder Of Truth !", $r);
        }
    }elseif (strstr($data, "langu")) {
            $user->setstep("null");
            if ($data == "languen") {
                $user->setlang(2);
                telegramApi::answer_inline($telegram->callback_query->id, "English");
            } else {
                $user->setlang(1);
                telegramApi::answer_inline($telegram->callback_query->id, "فارسی");
            }
        } elseif ($data == "see") {
            $user->setstep("null");
            $lang = $user->getlanguage();
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "میخوام ببینم");
                $r = ['inline_keyboard' => [[['text' => "میخوام 10 تا خبر جدید بخونم 🗞📰", 'callback_data' => 'wsee1']], [['text' => "میخوام 10 تا موسیقی خوب گوش بدم 🎧🎻", 'callback_data' => 'wsee2']], [['text' => "میخوام ۱۰ تا عکس خوب ببینم 🌄📷", 'callback_data' => 'wsee3']], [['text' => "میخوام ۱۰ تا مطلب جالب بخونم 📜😎", 'callback_data' => 'wsee5']], [['text' => "میخوام ۱۰ تا جوک و کلیپ باحال ببینم 😂🥂", 'callback_data' => 'wsee6']], [['text' => "میخوام ۱۰ تا فیلم خوب دانلود کنم 🎬📽", 'callback_data' => 'wsee7']], [['text' => "میخوام ده تا آموزش خوب ببینم 🛠🤓", 'callback_data' => 'wsee8']], [['text' => "میخوام ۱۰ تا مطلب هنری ببینم 🎨🎭", 'callback_data' => 'wsee9']], [['text' => "خانه", 'callback_data' => 'start']],]];
                telegramApi::message_inline_query($chat_id, "با میخوام ببینم ... 
شما میتونید 10 تا از بهترین پست ها تلگرام در مورد موضوع دلخواه خودتون رو ببینید 👀 
لطفا یکی از موضوعات زیر رو انتخاب کنید 👇🏻", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "want see");
                $r = ['inline_keyboard' => [[['text' => "I want to read 10 news", 'callback_data' => 'wsee1']], [['text' => "I want listen to 10 good music", 'callback_data' => 'wsee2']], [['text' => "I want see 10 good picture", 'callback_data' => 'wsee3']], [['text' => "I want  read 10 intersting post", 'callback_data' => 'wsee5']], [['text' => "I want see 10 intersting clip", 'callback_data' => 'wsee6']], [['text' => "I want to download 10 good movie ", 'callback_data' => 'wsee7']], [['text' => "I want see 10 usefull training", 'callback_data' => 'wsee8']], [['text' => "I want see 10 atr piece", 'callback_data' => 'wsee9']], [['text' => "home", 'callback_data' => 'start']],]];
                telegramApi::message_inline_query($chat_id, "With 
i want see ...
You can see 10 new post form telegram in a subject of your choice 👇", $r);
            }
        } elseif (strstr($data, "wsee")) {
            telegramApi::answer_inline($telegram->callback_query->id, "👌🏻");
            $lang = $user->getlanguage();
            $user->setstep("null");
            search::WantSee($chat_id, $data, $lang);
        } elseif ($data == "gosh") {
            $user->setstep("null");
            $lang = $user->getlanguage();

            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "منوی گوش به زنگ");
                $r = ['inline_keyboard' => [[['text' => "جدید", 'callback_data' => 'goshinew'], ['text' => "لیست", 'callback_data' => 'goshilist']], [['text' => "تنظیم فعالیت ربات", 'callback_data' => 'gosh_active'], ['text' => "خانه", 'callback_data' => 'start']]]];
                telegramApi::message_inline_query($chat_id, "با گوش به زنگ شما میتونید ربات رو مامور کنید تا اگه مطلبی در مورد یه موضوع خاص توی تلگرام منتشر شد به شما اطلاع بده 👌🏻
   
میتونید کانال مدنظر خودتون رو هم معرفی کنید تا ربات اون رو هم همیشه چک کنه 🤓", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "‌Buzz Me menue");
                sqlMethod::sqlconnect("update user set step=NULL WHERE chat_id=$chat_id");
                $r = ['inline_keyboard' => [[['text' => "New", 'callback_data' => 'goshinew'], ['text' => "List", 'callback_data' => 'goshilist']], [['text' => "Set Bot Activity", 'callback_data' => 'gosh_active'], ['text' => "Home", 'callback_data' => 'start']]]];
                telegramApi::message_inline_query($chat_id, "With buzz me you can set up 3 buzz for whatever you want and if there is any post about them seeker will send it to you 😉👍🏻", $r);
            }
        $user->setstep("null");
    }elseif ($data == "gosh_new") {
            telegramApi::answer_inline($telegram->callback_query->id, "گوش به زنگ جدید");
            BuzzMe::MakeBuzzMe($chat_id, $user->getlanguage());
        } elseif ($data == "gosh_list") {
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "لیست گوش به زنگ");
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "buzz me list");
            }
            BuzzMe::show_user_Buzz_list($chat_id, $user->getlanguage());
        } elseif ($data == "gosh_active") {
            $user->setstep("null");
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "تنظیمات فعالیت ربات");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "غیر فعال", 'callback_data' => 'goshi1']
                        ],
                        [
                            ['text' => "فعال", 'callback_data' => 'goshi2']
                        ],
                        [
                            ['text' => "خانه", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "لطفا انتخاب فرمایید", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "set bot activity");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "Active", 'callback_data' => 'goshi1']
                        ],
                        [
                            ['text' => "Deactive", 'callback_data' => 'goshi2']
                        ],
                        [
                            ['text' => "Home", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "select an action.", $r);
            }
        } elseif (strstr($data, "goshi")) {
            $user->setstep("null");

        } elseif (strstr($data, "dele")) {
            $a = preg_replace("/[^0-9]/", '', $data);
            BuzzMe::delete_buzz_me($a);
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "حذف شد");
                telegramApi::sendmessage($chat_id, "گوش به زنگ مورد نظر حذف شد");
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "deleted");
                telegramApi::sendmessage($chat_id, "selected buzz me deleted");
            }
        } elseif ($data == "searchp") {
            $user->setstep("null");
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "جستجوی پیشرفته");
                $r = ['inline_keyboard' => [[['text' => "متن", 'callback_data' => 'wserch1'], ['text' => "موسیقی", 'callback_data' => 'wserch2']], [['text' => "عکس", 'callback_data' => 'wserch3'], ['text' => "ویدیو", 'callback_data' => 'wserch4']], [['text' => "فایل", 'callback_data' => 'wserch5'], ['text' => "همه", 'callback_data' => 'wserch6']]]];
                telegramApi::message_inline_query($chat_id, "لطفا نوع رسانه مورد نظر را انتخاب کنید.", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "advance search");
                $r = ['inline_keyboard' => [[['text' => "Text", 'callback_data' => 'wserch1'], ['text' => "Audio", 'callback_data' => 'wserch2']], [['text' => "Photo", 'callback_data' => 'wserch3'], ['text' => "Video", 'callback_data' => 'wserch4']], [['text' => "Document", 'callback_data' => 'wserch5'], ['text' => "Every Thing!", 'callback_data' => 'wserch6']]]];
                telegramApi::message_inline_query($chat_id, "Please Select Media type you want to search.", $r);
            }
        } elseif (strstr($data, "wserch")) {
            $user->setstep("null");
            $a = preg_replace("/[^0-9]/", '', $data);
            sqlMethod::sqlconnect("insert into search (`group`, day, format, text, chat_id) VALUES (NULL null,NULL ,NULL ,NULL ,$chat_id)");
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "دسته بندی انتخاب شد");
                $r = ['inline_keyboard' => [[['text' => "یک روز", 'callback_data' => 'long1'], ['text' => "سه روز", 'callback_data' => 'long2']], [['text' => "یک هفته", 'callback_data' => 'long3'], ['text' => "یک ماه", 'callback_data' => 'long4']], [['text' => "نامحدود", 'callback_data' => 'long5'], ['text' => "بازگشت", 'callback_data' => 'searchp']]]];
                telegramApi::message_inline_query($chat_id, "میخواهید جستجوی شما در چند وقت اخیر انجام شود؟", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "category picked");
                $r = ['inline_keyboard' => [[['text' => "1 day", 'callback_data' => 'long1'], ['text' => "3 days", 'callback_data' => 'long2']], [['text' => "1 week", 'callback_data' => 'long3'], ['text' => "1 month", 'callback_data' => 'long4']], [['text' => "from Stone age", 'callback_data' => 'long5'], ['text' => "Back", 'callback_data' => 'searchp']]]];
                telegramApi::message_inline_query($chat_id, "Please Select Media type you want to search.", $r);
            }
        } elseif (strstr($data, "long")) {
            $a = preg_replace("/[^0-9]/", '', $data);
            $user->setstep(null);
            sqlMethod::sqlconnect("update search set day=$a WHERE chat_id=$chat_id AND id=(SELECT max(id) FROM search WHERE chat_id=$chat_id)");
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "زمان انتخاب شد");
                telegramApi::sendmessage($chat_id, "🔻برای نتایج بهتر با تک کلمات جستجو کنید

لطفا عبارت مد نظر را حداکثر در سه کلمه پشت سر هم وارد کنید

مثال:
فوتبال ایران
چاوشی
استخدام در تهران");
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "time recieved");
                telegramApi::sendmessage($chat_id, "Plz enter your search in 3 keyword max for best results 

Example :
game
photo of London
Coldplay");
            }
        } elseif (strstr($data, "dast")) {
            $to = substr($data, 0, 7);
            $a = strlen($data) - 11;
            $id = substr($data, 11, $a);
            sqlMethod::sqlconnect("update Buzz set dasteh=$id WHERE id=$to");
            $a = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20"];
            if ($lang == 1) {
                $b = ["تفریحی و مسافرتی", "آشپزی", "فرهنگی و هنری", "خودرو و املاک", "سیاسی و خبری", "دانلود", "فیلم و سریال", "استخدامی", "مجله", "متفرقه", "پزشکی و سلامت", "ورزشی", "مخصوص خانم ها", "اموزشی", "موسیقی", "علم و فناوری", "مذهبی", "عکس", "جوک و کلیپ", "همه"];
            } else {
                $b = ["Fun & Traveling", "Coocking", "Art & Culture", "Cars & Housing", "News & Politics", "Download", "Show & Movies", "Employment", "Journal", "Other", "medicine and health", "Sport", "for women", "Educational", "Music", "Science and Technology", "Religious", "Religious", "Funny Stuff", "All"];
            }
            $b = str_replace($a, $b, $id);
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "$b");
                $r = ['inline_keyboard' => [[['text' => "خانه", 'callback_data' => 'start']], [['text' => "بازگشت", 'callback_data' => 'back' . $to . '']]]];
                telegramApi::message_inline_query($chat_id, " $b انتخاب شد لطفا عبارت مد نظر را وارد کنید", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "$b");
                $r = ['inline_keyboard' => [[['text' => "Home", 'callback_data' => 'start']], [['text' => "Back", 'callback_data' => 'back' . $to . '']]]];
                telegramApi::message_inline_query($chat_id, "You selected 🔻 $b 🔻
Plz enter your search in 3 keyword max for best results 

Example :
game
photo of London
Coldplay ", $r);
            }
            $user->setstep("gosh");
        } elseif (strstr($data, "back")) {
            $user->setstep("null");
            $a = preg_replace("/[^0-9]/", '', $data);
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "بازگشت");
                $r = ['inline_keyboard' => [[['text' => "تفریحی و مسافرتی", 'callback_data' => '' . $a . 'dast01'], ['text' => "آشپزی", 'callback_data' => '' . $a . 'dast02']], [['text' => "فرهنگی و هنری", 'callback_data' => '' . $a . 'dast03'], ['text' => "خودرو واملاک", 'callback_data' => '' . $a . 'dast04']], [['text' => "سیاسی و خبری", 'callback_data' => '' . $a . 'dast05'], ['text' => "دانلود", 'callback_data' => '' . $a . 'dast06']], [['text' => "فیلم و سریال", 'callback_data' => '' . $a . 'dast07'], ['text' => "استخدامی", 'callback_data' => '' . $a . 'dast08']], [['text' => "مجله", 'callback_data' => '' . $a . 'dast09'], ['text' => "متفرقه", 'callback_data' => '' . $a . 'dast10']], [['text' => "پزشکی و سلامت", 'callback_data' => '' . $a . 'dast11'], ['text' => "ورزشی", 'callback_data' => '' . $a . 'dast12']], [['text' => "مخصوص خانم ها", 'callback_data' => '' . $a . 'dast13'], ['text' => "آموزشی", 'callback_data' => '' . $a . 'dast14']], [['text' => "موسیقی", 'callback_data' => '' . $a . 'dast15'], ['text' => "علم و فناوری", 'callback_data' => '' . $a . 'dast16']], [['text' => "مذهبی", 'callback_data' => '' . $a . 'dast17'], ['text' => "عکس", 'callback_data' => '' . $a . 'dast18']], [['text' => "جوک و کلیپ", 'callback_data' => '' . $a . 'dast19'], ['text' => "همه", 'callback_data' => '' . $a . 'dast20']], [['text' => "خانه", 'callback_data' => 'start']]]];
                telegramApi::message_inline_query($chat_id, "دسته بندی را انتخاب کنید", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "Back");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "Fun & Traveling", 'callback_data' => '' . $a . 'dast01'], ['text' => "Coocking", 'callback_data' => '' . $a . 'dast02']
                        ],
                        [
                            ['text' => "Art & Culture", 'callback_data' => '' . $a . 'dast03'], ['text' => "Cars & Housing", 'callback_data' => '' . $a . 'dast04']
                        ],
                        [
                            ['text' => "News & Politics", 'callback_data' => '' . $a . 'dast05'], ['text' => "Download", 'callback_data' => '' . $a . 'dast06']
                        ],
                        [
                            ['text' => "Show & Movies", 'callback_data' => '' . $a . 'dast07'], ['text' => "Employment", 'callback_data' => '' . $a . 'dast08']
                        ],
                        [
                            ['text' => "Journal", 'callback_data' => '' . $a . 'dast09'], ['text' => "Other", 'callback_data' => '' . $a . 'dast10']
                        ], [['text' => "medicine and health", 'callback_data' => '' . $a . 'dast11'], ['text' => "Sport", 'callback_data' => '' . $a . 'dast12']], [['text' => "for women", 'callback_data' => '' . $a . 'dast13'], ['text' => "Educational", 'callback_data' => '' . $a . 'dast14']], [['text' => "Music", 'callback_data' => '' . $a . 'dast15'], ['text' => "Science and Technology", 'callback_data' => '' . $a . 'dast16']], [['text' => "Religious", 'callback_data' => '' . $a . 'dast17'], ['text' => "Picture", 'callback_data' => '' . $a . 'dast18']], [['text' => "Funny Stuff", 'callback_data' => '' . $a . 'dast19'], ['text' => "All", 'callback_data' => '' . $a . 'dast20']], [['text' => "Home", 'callback_data' => 'start']]]];
                telegramApi::message_inline_query($chat_id, "Pick a Category!", $r);
            }
        } elseif (strstr($data, "day")) {
            $user->setstep("null");
            $day = substr($data, 0, 1);
            $to = substr($data, 4, 7);
            sqlMethod::sqlconnect("update Buzz set exp=$day WHERE id=$to");
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "زمان ثبت شد");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "معرفی کانال", 'callback_data' => 'channel'], ['text' => "منوی گوش به زنگ", 'callback_data' => 'gosh']
                        ],
                        [
                            ['text' => "خانه", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "گوش به زنگ شما ثبت شد اگه میخواید میتونید کانال مورد نظرتون رو به ما معرفی کنید تا برای گوش به زنگ شما مرتب چک بشه", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "time recived");
                $r = ['inline_keyboard' => [[['text' => "Suggest channel", 'callback_data' => 'channel'], ['text' => "Buzz Menu", 'callback_data' => 'gosh']], [['text' => "Home", 'callback_data' => 'start']]]];
                telegramApi::message_inline_query($chat_id, "Ok ,Done 

You what a specific channals to be check out for your buzz ? 
Just submit it 👇🏻", $r);

            }
        } elseif ($data == "channel") {

            $user->setstep("channel");
            if ($lang == 1) {

                telegramApi::answer_inline($telegram->callback_query->id, "معرفی کانال");
                $r = ['inline_keyboard' => [[['text' => "خانه", 'callback_data' => 'start']]]];
                telegramApi::message_inline_query($chat_id, "آیدی کانال مورد نظر را به صورت @aaaa وارد کنید", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "suggest channel");
                $r = ['inline_keyboard' => [[['text' => "Home", 'callback_data' => 'start']]]];
                telegramApi::message_inline_query($chat_id, "plz insert youre channel ID Like:@telegrm", $r);
            }
        } elseif (strstr($data, "to")) {
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "صفحه بعدی");
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "next page");
            }
            search::show_next_page($data, $chat_id);
        } elseif ($data == "search") {
            $user->setstep("null");
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "جستجو");
                telegramApi::sendmessage($chat_id, "🔻برای نتایج بهتر با تک کلمات جستجو کنید

لطفا عبارت مد نظر را حداکثر در سه کلمه پشت سر هم وارد کنید

مثال:
فوتبال ایران
چاوشی
استخدام در تهران");
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "search");
                telegramApi::sendmessage($chat_id, "Plz enter your search in 3 keyword max for best results 

Example :
game
photo of London
Coldplay");
            }
        } elseif ($data == "tamas") {
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "تماس با ما");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "خانه", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "برای پشتیبانی فنی و هرگونه سوال درباره ربات می توانید با آی دی @alexf8 تماس بگیرید.", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "contact us");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "Home", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "If you have any question or suggestion, please contact @Alexf8.", $r);
            }
        } elseif ($data == "help") {
            $user->setstep("null");
            if ($lang == 1) {
                telegramApi::answer_inline($telegram->callback_query->id, "راهنما");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "تماس با ما", 'callback_data' => 'tamas'], ['text' => "تغییر زبان", 'callback_data' => 'change']
                        ],
                        [
                            ['text' => "خانه", 'callback_data' => 'start'], ['text' => " معرفی کانال", 'callback_data' => 'channel']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "اینجا میتونید زبان رو تغییر بدید 🔃
سوال ها، نظرات و پیشنهادات خودتون رو هم در قسمت تماس با ما مطرح کنید", $r);
            } else {
                telegramApi::answer_inline($telegram->callback_query->id, "help");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "say hi", 'callback_data' => 'tamas'], ['text' => "Change language", 'callback_data' => 'change']
                        ],
                        [
                            ['text' => "home", 'callback_data' => 'start'], ['text' => " Suggest channel", 'callback_data' => 'channel']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "You can change the language here 🔃
Or send us your thoughts with contact us button 🖖🏻", $r);
            }
        }  elseif ($data == "change") {
            telegramApi::answer_inline($telegram->callback_query->id, "change language");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "English", 'callback_data' => 'languen'], ['text' => "فارسی", 'callback_data' => 'langufa']

                    ]
                ]
            ];
            telegramApi::message_inline_query($chat_id, "Please select a language!

لطفا زبان را انتخاب کنید!", $r);
        } elseif ($data == "tamas") {
            if ($lang == 1) {
                telegramApi::sendmessage($chat_id, "صورت مشکل و انتقاد و پیشنهاد ا این ای دی تمایس بگیرید @alexf8");
            } else {
                telegramApi::sendmessage($chat_id, "تماس با ما english");
            }
        } elseif ($data == "channel") {
            $user->setstep("channel");
        }
    }
} else {

    if ($telegram->message->from->id == $id) {
        if (isset($telegram->message->sticker) == false) {
            if (isset($telegram->message->forward_from_chat)) {
                post::addToDatabase($telegram);
            }
        } elseif ($telegram->message->from->id == $modir) {
            $from = $telegram->message->forward_from_chat->id;
            $m = $telegram->message->forward_from_message_id;
            sqlMethod::sqlconnect("update user set step=NULL WHERE chat_id=$modir");
            $result = sqlMethod::sqlconnect("SELECT * FROM user");
            while ($row = mysqli_fetch_array($result)) {
                try {
                    $id = $row['chat_id'];
                    $a = telegramApi::forward($id, $from, $m);
                    if ($a == null) {
                        sqlMethod::sqlconnect("delete from user WHERE chat_id=$id");
                    }
                } catch (Exception $e) {
                    sqlMethod::sqlconnect("delete from user WHERE chat_id=$id");
                }
            }
            telegramApi::sendmessage($modir, "تبلیغ با موفقیت ارسال شد");
        } else {
            if (isset($telegram->message->photo) == true) {
                $chat_id = $telegram->message->from->id;
                $user = new user($chat_id);
            } else {
                $text = $telegram->message->text;
                $chat_id = $telegram->message->from->id;
                $user = new user($chat_id);
                if ($chat_id == $modir) {
                    $b = $user->getstep();
                    if ($b == "tabligh") {
                        $user->setstep("null");
                        $result = sqlMethod::sqlconnect("SELECT * FROM user");
                        while ($row = mysqli_fetch_array($result)) {
                            try {
                                $id = $row['chat_id'];
                                $a = telegramApi::sendmessage($id, $text);
                                if ($a == null or $a->ok == false) {
                                    sqlMethod::sqlconnect("delete from user WHERE chat_id=$id");
                                }
                            } catch (Exception $e) {
                                sqlMethod::sqlconnect("delete from user WHERE chat_id=$id");
                            }
                        }
                        telegramApi::sendmessage($modir, "تبلیغ به تمام اعضا ارسال شد");
                        sqlMethod::sqlconnect("update user set step=NULL WHERE chat_id=$modir");
                    } elseif ($b == "delch") {
                        $user->setstep("null");
                        channel::deleteChannel($text);
                        telegramApi::sendmessage($modir, "کانال مورد نظر به همراه تمام پست های آن کانال با موفقیت حذف شدند.");
                    } elseif ($b == "addch") {
                        $user->setstep("null");
                        $a = $text;
                        $r = ['inline_keyboard' => [[['text' => "تفریحی و مسافرتی", 'callback_data' => 'dast01' . $a . ''], ['text' => "آشپزی", 'callback_data' => 'dast02' . $a . '']], [['text' => "فرهنگی و هنری", 'callback_data' => 'dast03' . $a . ''], ['text' => "خودرو واملاک", 'callback_data' => 'dast04' . $a . '']], [['text' => "سیاسی و خبری", 'callback_data' => 'dast05' . $a . ''], ['text' => "دانلود", 'callback_data' => 'dast06' . $a . '']], [['text' => "فیلم و سریال", 'callback_data' => 'dast07' . $a . ''], ['text' => "استخدامی", 'callback_data' => 'dast08' . $a . '']], [['text' => "مجله", 'callback_data' => 'dast09' . $a . ''], ['text' => "متفرقه", 'callback_data' => 'dast10' . $a . '']], [['text' => "پزشکی و سلامت", 'callback_data' => 'dast11' . $a . ''], ['text' => "ورزشی", 'callback_data' => 'dast12' . $a . '']], [['text' => "مخصوص خانم ها", 'callback_data' => 'dast13' . $a . ''], ['text' => "آموزشی", 'callback_data' => 'dast14' . $a . '']], [['text' => "موسیقی", 'callback_data' => 'dast15' . $a . ''], ['text' => "علم و فناوری", 'callback_data' => 'dast16' . $a . '']], [['text' => "مذهبی", 'callback_data' => 'dast17' . $a . ''], ['text' => "عکس", 'callback_data' => 'dast18' . $a . '']], [['text' => "جوک و کلیپ", 'callback_data' => 'dast19' . $a . ''], ['text' => "همه", 'callback_data' => 'dast20' . $a . '']], [['text' => "خانه", 'callback_data' => 'start']]]];
                        telegramApi::message_inline_query($modir, "لطفا یک دسته بندی را برای کانال مورد نظر انتخاب نمایید.", $r);
                    } elseif ($text == "/start") {
                        $user->setstep("null");
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "اعضا", 'callback_data' => 'rownumber']
                                ],
                                [
                                    ['text' => "ارسال تبلیغ", 'callback_data' => 'tabligh']
                                ],
                                [
                                    ['text' => "ستاره دار کردن تبلیغ", 'callback_data' => 'setstar']
                                ],
                                [
                                    ['text' => "افزودن کانال", 'callback_data' => 'addch']
                                ],
                                [
                                    ['text' => "حذف کانال", 'callback_data' => 'delch']
                                ],
                                [
                                    ['text' => "بیشترین جستجو شده ها", 'callback_data' => 'max']
                                ],
                                [
                                    ['text' => "خانه", 'callback_data' => 'start']
                                ]
                            ]
                        ];
                        telegramApi::message_inline_query($modir, "چه خدمتی در نظر دارید؟؟", $r);
                    }
                } else {
                    if ($text == "/start") {
                        if ($user->userexist() == false) {
                            $user->addToDatabase();
                        }
                        $r = [
                            'inline_keyboard' => [
                                [
                                    ['text' => "English", 'callback_data' => 'languen'], ['text' => "فارسی", 'callback_data' => 'langufa']

                                ]
                            ]
                        ];
                        telegramApi::message_inline_query($chat_id, "Please select a language!

لطفا زبان را انتخاب کنید!", $r);

                    } else {
                        $step = $user->getstep();
                        if ($step == "search") {
                            $lang = $user->getlanguage();
                            $search = new search($chat_id, $text, $lang);
                        } elseif ($step == "gosh") {
                            BuzzMe::setBuzzMe($text,$chat_id);
                            $this->setstep("null");
                            if ($this->getlanguage() == 1) {
                                $r = ['inline_keyboard' => [[['text' => "یک روز", 'callback_data' => '1day' ], ['text' => "یک هفته", 'callback_data' => '2day']], [['text' => "یک ماه", 'callback_data' => '3day' ], ['text' => "یک سال", 'callback_data' => '4day' ]]]];
                                telegramApi::message_inline_query($this->chat_id, "تا چه زمانی میخواهید گوش به زنگ فعال باشد", $r);
                            } else {
                                $r = ['inline_keyboard' => [[['text' => "1 day", 'callback_data' => '1day'], ['text' => "1 weak", 'callback_data' => '2day' ]], [['text' => "1 Month", 'callback_data' => '3day' ], ['text' => "1 year", 'callback_data' => '4day' ]]]];
                                telegramApi::message_inline_query($this->chat_id, "How long do we go back?", $r);
                            }
                        } elseif ($step == "serchp") {
                            $user->setstep("null");
                            $a = sqlMethod::sqlget("select lang from seeker.user WHERE chat_id=$chat_id");
                            $lang = $a['lang'];
                            $search = new search($chat_id, $text, $lang);
                            $search->advanceSearch($a['daste'], $a['day']);
                        } elseif ($step == "channel") {
                            $user->setstep("null");
                            $a = $text;
                            $r = [
                                'inline_keyboard' => [
                                    [
                                        ['text' => "تفریحی و مسافرتی", 'callback_data' => 'dast01' . $a . ''], ['text' => "آشپزی", 'callback_data' => 'dast02' . $a . '']
                                    ],
                                    [
                                        ['text' => "فرهنگی و هنری", 'callback_data' => 'dast03' . $a . ''], ['text' => "خودرو واملاک", 'callback_data' => 'dast04' . $a . '']
                                    ],
                                    [
                                        ['text' => "سیاسی و خبری", 'callback_data' => 'dast05' . $a . ''], ['text' => "دانلود", 'callback_data' => 'dast06' . $a . '']
                                    ],
                                    [
                                        ['text' => "فیلم و سریال", 'callback_data' => 'dast07' . $a . ''], ['text' => "استخدامی", 'callback_data' => 'dast08' . $a . '']
                                    ],
                                    [
                                        ['text' => "مجله", 'callback_data' => 'dast09' . $a . ''], ['text' => "متفرقه", 'callback_data' => 'dast10' . $a . '']
                                    ],
                                    [
                                        ['text' => "پزشکی و سلامت", 'callback_data' => 'dast11' . $a . ''], ['text' => "ورزشی", 'callback_data' => 'dast12' . $a . '']
                                    ],
                                    [
                                        ['text' => "مخصوص خانم ها", 'callback_data' => 'dast13' . $a . ''], ['text' => "آموزشی", 'callback_data' => 'dast14' . $a . '']
                                    ],
                                    [
                                        ['text' => "موسیقی", 'callback_data' => 'dast15' . $a . ''], ['text' => "علم و فناوری", 'callback_data' => 'dast16' . $a . '']
                                    ],
                                    [
                                        ['text' => "مذهبی", 'callback_data' => 'dast17' . $a . ''], ['text' => "عکس", 'callback_data' => 'dast18' . $a . '']
                                    ],
                                    [
                                        ['text' => "جوک و کلیپ", 'callback_data' => 'dast19' . $a . ''], ['text' => "همه", 'callback_data' => 'dast20' . $a . '']
                                    ],
                                    [
                                        ['text' => "خانه", 'callback_data' => 'start']
                                    ]
                                ]
                            ];
                            telegramApi::message_inline_query($modir, $text, $r);
                            telegramApi::sendmessage($chat_id, "با تشکر از پیشنهادتان.کانال مورد نظر بعد از تایید ادمین بررسی میشود");
                        }
                    }
                }
            }
        }
    }
}
