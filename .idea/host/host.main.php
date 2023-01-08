<?php
/**
 * Created by PhpStorm.
 * User: saleh
 * Date: 1/12/18
 * Time: 8:30 PM
 */
$servername="localhost";
$username="mychanneladminbot";
$pass="qOA4g8EBDCbXJgjdlkD4yMsw2";
$dbname="mychanneladmin";
$token="483977094:AAHg9X4NkMbbWxj_B3N85L_reu105yRVcTU";
$modir="249601731";
$poshtiban="249601731";
$id="517610206";

$conn = mysqli_connect($servername, $username, $pass, $dbname) or die ('Failed to connect to database');
mysqli_query($conn, "SET NAMES 'utf8mb4'");
mysqli_query($conn, "SET CHARACTER SET 'utf8mb4'");
mysqli_query($conn, "SET character_set_connection = 'utf8mb4'");
$telegram = json_decode(file_get_contents("php://input"));
if (isset($telegram->callback_query)) {
    $chat_id = $telegram->callback_query->message->chat->id;
    $data = $telegram->callback_query->data;
    if ($chat_id == $modir) {
        if ($data == "start") {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "تعداد کانال های vip", 'callback_data' => 'vip']
                    ],
                    [
                        ['text' => "تعداد کانال های غیر فعال", 'callback_data' => 'novip']
                    ],
                    [
                        ['text' => "ارسال پیام به کانال های عیر فعال", 'callback_data' => 'tabligh']
                    ],
                    [
                        ['text' => "تعداد اعضا ربات", 'callback_data' => 'rownum']
                    ],
                    [
                        ['text' => "ارسال پیام به اعضا", 'callback_data' => 'dc']
                    ]
                ]
            ];
            message_inline_query($modir, "انتحخاب کنید", $r);
        } elseif ($data == "vip") {
            $result = mysqli_query($conn, "SELECT * FROM channel WHERE vip=1");
            sendmessage($modir, "$result->num_rows");
        } elseif ($data == "novip") {
            $result = mysqli_query($conn, "SELECT * FROM channel WHERE vip=0");
            sendmessage($modir, "$result->num_rows");
        } elseif ($data == "tabligh") {
            mysqli_query($conn, "update aaza set step='tabligh' WHERE chat_id=$modir");
            sendmessage($modir, "تبلیغ را ارسال کنید");
        } elseif ($data == "rownum") {
            $result = mysqli_query($conn, "SELECT * FROM aaza");
            sendmessage($modir, "$result->num_rows");
        } elseif ($data == "dc") {
            mysqli_query($conn, "update aaza set step='dc' WHERE chat_id=$modir");
            sendmessage($modir, "پیام را ارسال کنید");
        }
    } else {
        if ($data == "start") {
            answer_inline($telegram->callback_query->id, "منوی اصلی🏡");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "ایول چه خوب🙊ثبت کن کانالمو😍", 'callback_data' => 'newch']
                    ],
                    [
                        ['text' => "منوی کاربری😎", 'callback_data' => 'menue']
                    ],
                    [
                        ['text' => "راهنما🤔", 'callback_data' => 'help']
                    ]
                ]
            ];
            message_inline_query($chat_id, "سلام دوست عزیز😍 
من ادمین یار تلگرام هستم.
یعنی چی؟؟؟🤔
 یعنی میتونم تو مدیریت کانال کمکت کنم🙊😍
حتما داری میگی چه جوری؟🤔
الان بهت میگم😝
فقط کافیه کانالت رو ثبت کنی و بعد چند تا کانال بهم معرفی کنی تا پست های اون کانال ها رو تو کانالت اونم با آیدی کانال خودت بذارم😯🙊😍", $r);
        }elseif (strstr($data,"ans")){
            $id = preg_replace("/[^0-9]/", '', $c);
            sendmessage($poshtiban,"پاسخ را ارسال کنید.");
            sqlconnect("update aaza set step='answer$id' WHERE chat_id=$chat_id;");
        }
        elseif ($data=="help"){
            answer_inline($telegram->callback_query->id, "راهنما");
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "فوروارد معمولی چجوریه؟😓", 'callback_data' => 'help1']
                    ],
                    [
                        ['text' => "فرق بین حساب های معمولی و ویژه چیه؟🤑", 'callback_data' => 'help3']
                    ],
                    [
                        ['text' => "سوالم اینجا نبود😒", 'callback_data' => 'help5']
                    ]
                ]
            ];
            message_inline_query($chat_id,"تو چه موضوغی به کمکم احتیاج داری؟🤓",$r);
        }elseif (strstr($data,"help")){
            $id = preg_replace("/[^0-9]/", '', $data);
            if ($id==1){
                $file="AgADBAADg6wxG5UVyVE7FnVLGR-EjftTiRoABI5enGq0ixM_WgQAAgI";
                $text="لطفا عکس با دقت مطالعه شود";
                sendphoto($chat_id,$text,$file);
            }elseif ($id==3){
                $file="AgADBAADhawxG5UVyVHTL0ZskJqoqnna-RkABPmjH88ILOuCTPIEAAEC";
                $text="تفاوت حساب های ویژه و معمولی به صورت مقابل است.میزان تبلیغ ها حداکثر روزانه یک عدد است.";
                sendphoto($chat_id,$text,$file);
            }elseif ($id==4){
                sqlconnect("update aaza set step='soal' WHERE chat_id=$chat_id");
                sendmessage($chat_id,"دوست عزیز سوالت رو بگو تا من در اسرع وقت پاسخ شمارو بدم 😊");
            }
        }
        elseif ($data == "newch") {
            answer_inline($telegram->callback_query->id, "ثبت نام کانال");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $result = mysqli_query($conn, "select * from channel WHERE chat_idm=$chat_id");
            if ($result->num_rows == 1) {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "منوی کاربری😎", 'callback_data' => 'menue']
                        ],
                        [
                            ['text' => "منوی اصلی🏡", 'callback_data' => 'start']
                        ]
                    ]
                ];
                message_inline_query($chat_id, "دوست عزیزم شما نمیتونی دوتا کانال ثبت کنی ☹️
هر نفر فقط میتونه یه کانال ثبت کنه❤️
ولی میتونی با یک‌اکانت دیگه کانال دیگه خودتو ثبت کنی🌹", $r);
            } else {
                sendmessage($chat_id, "خب حالا که میخوای کمکت کنم برو یکی از پستای کانالت رو فوروارد کن واسه من☺️
البته اینو بگم که اگر موبوگرام و یا برنامه ای غیر از تلگرام اصلی داری پست کانالت رو برام فوروارد کن❤️
اگه نمیدونی چجوری باید فوروارد معمولی بکنی یه لحظه برو راهنما رو مطالعه کن که به مشکل نخوری😊🌷");
                sqlconnect("update aaza set step='sabtch' WHERE chat_id=$chat_id");
            }
        } elseif ($data == "menue") {
            answer_inline($telegram->callback_query->id, "منوی کاربری😎");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $result = mysqli_query($conn, "select * from channel WHERE chat_idm=$chat_id");
            if ($result->num_rows == 1) {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "مشاهده جزییاته حساب📝", 'callback_data' => 'joziyat']
                        ],
                        [
                            ['text' => "ارتقا یا تمدید حساب ویژه💰", 'callback_data' => 'vip']
                        ],
                        [
                            ['text' => "افزودن کانال های مرجع📎", 'callback_data' => 'addch']
                        ],
                        [
                            ['text' => "ویرایش کانال های مرجع🖍", 'callback_data' => 'changech']
                        ],
                        [
                            ['text' => "گزارش مشکل⛔️", 'callback_data' => 'report']
                        ]
                    ]
                ];
                message_inline_query($chat_id, "✅برای مشاهده اطلاعات حساب از جمله نوع حساب و تاریخ اتمام ویژه بودن حساب دکمه مشاهده جزییات بزن
 
✅برای ارتقا یا تمدید vip بودن حساب  دکمه ارتقا یا تمدید حساب های ویژه بزن

 ✅برای ویرایش یا افزودن کانال های مرجع برای کانالت دکمه ویرایش کانال های منبع رو بزن

✅ برای گزارش هرگونه مشکل در ربات دکمه گزارش مشکل رو بزن", $r);
            } else {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "میخوام کانالمو ثبت کنم☺️", 'callback_data' => 'newch']
                        ]
                    ]
                ];
                message_inline_query($chat_id, "گله من🌹 هنوز کانال ثبت نکردی
                             اول باید کانالتو ثبت کنی تا بعد منوی کاربری برات فعال بشه😌", $r);
            }
        } elseif ($data == "joziyat") {
            answer_inline($telegram->callback_query->id, "جزییاته حساب");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $a = sqlget("select * from channel WHERE chat_idm=$chat_id");
            $title = $a['id'];
            if ($a['vip'] == 1) {
                $date = $a['vipdate'];
                sendmessage($chat_id, "حساب شما تا تاریخ $date  ویژه محسوب میشه و بعد از اون باید دوباره واسه ویژه کردن حسابت اقدام‌کنی🌹🍃 ");
            } else {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "ارتقای حساب💰", 'callback_data' => 'vip']
                        ]
                    ]
                ];
                message_inline_query($chat_id, "سلام دوستم🌷
 حساب شما الان ویژه نیست برای فعال کردن حسابت دکمه زیر رو بزن☺️👇", $r);
            }
        } elseif ($data == "vip") {
            answer_inline($telegram->callback_query->id, "ویرایش 🖍");
            $url=linkpar();
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "ارتقای حساب💰", 'url' => '$url']
                    ]
                ]
            ];
            message_inline_query($chat_id,"برای رفتن به صفحه درگاه پرداخت بر روی دکمه زیر کلیک فرمایید.",$r);


        } elseif ($data == "changech") {
            answer_inline($telegram->callback_query->id, "ویرایش کانال های مرجع🖍");
            $r=null;
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $result = mysqli_query($conn, "select * from channelc WHERE chat_idm=$chat_id");
            if ($result->num_rows == 0) {
                sendmessage($chat_id, "هنوز کانال مرجعی ثبت نکردی دوسته خوبم🙈");
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    $r['inline_keyboard'][][] = ['text' => "" . $row['username'], 'callback_data' => 'del' . $row['id'] . ''];
                }
                $r['inline_keyboard'][][] = ['text' => "منوی اصلی🏡", 'callback_data' => 'start'];
                message_inline_query($chat_id, "لیست کانال های مرجع شما به صورت زیر میباشد 👇☺️
برای حذف هر کدوم کافیه روی اسم اون کانال بزنی🌷", $r);
            }
        } elseif ($data == "report") {
            answer_inline($telegram->callback_query->id, "گزارش مشکل");
            sendmessage($chat_id, "مشکلت رو کامل برام توضیح بده تا سریع حلش کنم❤️🌷
عکس یا وویس اگه بفرسی به دستم نمیرسه تویه متن توضیح بده😁😁");
            sqlconnect("update aaza set step='report' WHERE chat_id=$chat_id");
        } elseif (strstr($data, "del")) {
            answer_inline($telegram->callback_query->id, "پاک شد.");
            $r=null;
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $a = strlen($data);
            $a = $a - 3;
            $b = substr($data, 3, $a);
            sqlconnect("delete from channelc WHERE chat_idm=$chat_id AND id='$b'");
            $result = mysqli_query($conn, "select * from channelc WHERE chat_idm=$chat_id");
            while ($row = mysqli_fetch_array($result)) {
                $r['inline_keyboard'][][] = ['text' => "" . $row['username'], 'callback_data' => 'del' . $row['id'] . ''];
            }
            $r['inline_keyboard'][][] = ['text' => "افزودن کانال مرجع📎", 'callback_data' => 'addch'];
            $r['inline_keyboard'][][] = ['text' => "منوی اصلی🏡", 'callback_data' => 'start'];
            editMessageReplyMarkup($telegram->callback_query->message->message_id, $chat_id, $r);
        } elseif ($data == "addch") {
            answer_inline($telegram->callback_query->id, "افزودن کانال مرجع📎");
            sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
            $a = sqlget("select vip from channel WHERE chat_idm=$chat_id")['vip'];
            $result = mysqli_query($conn, "select * from channelc WHERE chat_idm=$chat_id");
            if ($a == true and $result->num_rows <= 6) {
                sqlconnect("update aaza set step='addchc' WHERE chat_id=$chat_id");
                sendmessage($chat_id, "یکی از پیام های کانالی که میخوای رو مثل قبل برام فوروارد کن تا اضافش کنم😇🤗

⭕️فقط دقت کن اون پیام خودش از جایی فوروارد نشده باشه😝

اگه یادت رفته چجوری باید فوروارد کنی برو راهنما رو بخون😚😚");
            } elseif ($a == true and $result->num_rows == 7) {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "ویرایش کانال های مرجع🖍", 'callback_data' => 'changech']
                        ],
                        [
                            ['text' => "منوی اصلی🏡", 'callback_data' => 'start']
                        ]
                    ]
                ];
                message_inline_query($chat_id, "متاسفم😢
بیشتر از ۷ تا کانال نمیتونی به عنوان کانال مرجع ثبت کنی و الان ۷ تا پر شده😉

 ولی میتونی یکی از کانال ها رو جایگزین یکی دیگه کنی🙃 
کافیه مراحل زیر رو انجام بدی👇
1️⃣ ویرایش کانال رو بزن

2️⃣ یکی از کانال های مرجع رو حذف کن
3️⃣کانال جدید رو وارد کن

  برای ارتقا حساب و تبدیل به اکانت ویژه ⬅️ارتقای حساب➡️ را انتخاب کن.", $r);
            } elseif ($a == false and $result->num_rows <= 3) {
                sqlconnect("update aaza set step='addchc' WHERE chat_id=$chat_id");
                sendmessage($chat_id, "یکی از پیام های کانالی که میخوای رو مثل قبل برام فوروارد کن تا اضافش کنم😇🤗

⭕️فقط دقت کن اون پیام خودش از جایی فوروارد نشده باشه😝

اگه یادت رفته چجوری باید فوروارد کنی برو راهنما رو بخون😚😚");
            } elseif ($a == false and $result->num_rows >= 4) {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "ویرایش کانال های مرجع🖍", 'callback_data' => 'changech']
                        ],
                        [
                            ['text' => "ارتقای حساب", 'callback_data' => 'vip']
                        ],
                        [
                            ['text' => "منوی اصلی🏡", 'callback_data' => 'start']
                        ]
                    ]
                ];
                message_inline_query($chat_id, "متاسفانه حساب شما ویژه نیست و حساب های معمولی تنها قادر به اضافه کردن حداکثر ۴ کانال به عنوان مرجع میباشند برای جایگزین کردن یک کانال ابتدا گزینه ویرایش را لمس کنید و پس از حذف یک یا چند کانال سپس تلاش کنید و برای ارتقای حساب و تبدیل به اکانت ویژه ارتقای حساب را انتخاب کنید.", $r);
            }
        }
    }
}else {
    $chat_id = $telegram->message->from->id;
    if ($chat_id == $id) {
        if ($telegram->message->from->id == $id) {
            if (isset($telegram->message->sticker)) {

            } else {
                if (isset($telegram->message->caption)) {
                    $text = $telegram->message->caption;
                } else {
                    $text = null;
                }
                if (isset($telegram->message->forward_from_chat)) {
                    $channel_id = $telegram->message->forward_from_chat->id;
                    if (isset($telegram->message->voice)) {
                        $file = $telegram->message->voice->file_id;
                        $format = "voice";
                    } elseif (isset($telegram->message->photo)) {
                        $file = $telegram->message->photo[0]->file_id;
                        $format = "photo";
                    } elseif (isset($telegram->message->document)) {
                        $file = $telegram->message->document->file_id;
                        $format = "document";
                    } elseif (isset($telegram->message->video)) {
                        $file = $telegram->message->video->file_id;
                        $format = "video";
                    } elseif (isset($telegram->message->audio)) {
                        $file = $telegram->message->audio->file_id;
                        $format = "audio";
                    } else {
                        $file = null;
                        $format = "text";
                    }
                    sqlconnect("insert into posts VALUES ('$channel_id',N'$text','$file','$format')");
                }
            }
        }
    }
    $step = sqlget("select step from aaza WHERE chat_id=$chat_id")['step'];
    if ($step == "sabtch") {

        $ch_user=$telegram->message->forward_from_chat->username;
        sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
        $channel_id = $telegram->message->forward_from_chat->id;
        $channel_name = $telegram->message->forward_from_chat->title;

        $r = [
            'inline_keyboard' => [
                [
                    ['text' => "افزودن کانال مرجع📎", 'callback_data' => 'addch']
                ],
                [
                    ['text' => "منوی اصلی🏡", 'callback_data' => 'start']
                ]
            ]
        ];
        sqlconnect("insert into channel VALUES ($chat_id,'$channel_id',0,NULL,N'$channel_name','$ch_user')");
        message_inline_query($chat_id, "خب دوست گلم کانال $channel_name  با موفقیت ثبت شد😍
حالا پست کانال هایی که میخوای متناش رو بذارم تو کانالت هم برام فوروارد کن☺️
فقط قبلش دکمه ثبت کانال مرجع رو بزن😝", $r);
    } elseif ($step == "addchc") {
        sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
        $channel_id = $telegram->message->forward_from_chat->id;
        $channel_name = $telegram->message->forward_from_chat->title;
        $for = sqlget("select chat_idc from channel WHERE chat_idm=$chat_id")['chat_idc'];
        if (Checkchannel($channel_id, $for)) {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "گرارش مشکل", 'callback_data' => 'report']
                    ],
                    [
                        ['text' => "افزودن کانال مرجع📎", 'callback_data' => 'addch']
                    ],
                    [
                        ['text' => "منوی اصلی🏡", 'callback_data' => 'start']
                    ]
                ]
            ];
            message_inline_query($chat_id, "گله من این‌کانال قبلا ثبت شده 😃
 اگر پست های کانال را دریافت نمیکنی دکمه  تماس با پشتیبانی رو بزن و بهم اطلاع بده🙃🌹
 برای اضافه کردن یک کانال دیگه افزودن کانال مرجع را انتخاب کن😊", $r);
        } else {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "ویرایش کانال های مرجع🖍", 'callback_data' => 'changech']
                    ],
                    [
                        ['text' => "افزودن کانال مرجع📎", 'callback_data' => 'addch']
                    ],
                    [
                        ['text' => "منوی اصلی🏡", 'callback_data' => 'start']
                    ]
                ]
            ];
            if (isset($telegram->message->forward_from_chat->username)) {
                $ch_user=$telegram->message->forward_from_chat->id;
                $channel_id = $telegram->message->forward_from_chat->username;

                message_inline_query($chat_id, "خب دوست گلم کانال $channel_name با موفقیت ثبت شد 😍 از حالا به بعد پستاش میاد تو کانالت
حالا پست بقیه کانال هایی که میخوای متناش رو بذارم تو کانالت هم برام فوروارد کن☺️
فقط قبلش دکمه ثبت کانال مرجع رو بزن😝
اگه هم میخوای لیست کانال هایی که تا حالا اضافه کردی رو ببینی و چیزی ازش پاک کنی ویرایش کانال های مرجع رو بزن", $r);
                $re = mysqli_query($conn, "select * from channelc WHERE id='$channel_id'");
                if ($re->num_rows == 0) {
                    sqlconnect("insert into channelc VALUES ('$channel_id','$for',$chat_id,N'$channel_name',1,0,'$ch_user')");
                } else {
                    sqlconnect("insert into channelc VALUES ('$channel_id','$for',$chat_id,N'$channel_name',0,0,'$ch_user')");
                }
            } else {
                message_inline_query($chat_id, "کانالی که قصد اضافه کردن آن را دارید خصوصی است برای افزودن این کانال به لیست خود باید لینک ان را بدون هیچ چیز اضافه ای برای ربات ارسال کنید.", $r);
                sqlconnect("update aaza set step='link' WHERE chat_id=$chat_id");
            }
        }
    }

    if (isset($telegram->message->photo) == true) {
        $photo = $telegram->message->photo[0]->file_id;
        $caption = $telegram->message->caption;
        if ($chat_id == $modir) {
            if ($step == "tabligh") {
                sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
                $result = mysqli_query($conn, "SELECT chat_idc FROM channel WHERE vip=0");
                while ($row = mysqli_fetch_array($result)) {
                    $id = $row['chat_id'];
                    $text = urlencode($caption);
                    $url = "https://api.telegram.org/bot$token/sendPhoto?chat_id=" . $id .
                        "&photo=" . $photo . "&caption=" . $text;
                    file_get_contents($url);
                }
                sendmessage($modir, "تبلیغ به تمام کانال ها ارسال شد");
                sqlconnect("UPDATE aaza set step=NULL WHERE chat_id=$modir");
            } elseif ($step == "dc") {
                sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
                $result = mysqli_query($conn, "SELECT chat_id FROM aaza");
                while ($row = mysqli_fetch_array($result)) {
                    $id = $row['chat_id'];
                    $text = urlencode($caption);
                    $url = "https://api.telegram.org/bot$token/sendPhoto?chat_id=" . $id .
                        "&photo=" . $photo . "&caption=" . $text;
                    file_get_contents($url);
                }
                sendmessage($modir, "تبلیغ به تمام اعضا ارسال شد");
                sqlconnect("UPDATE aaza set step=NULL WHERE chat_id=$modir");
            }
        }
    } elseif (isset($telegram->message->text) == true) {
        $text = $telegram->message->text;
        if ($chat_id == $modir) {
            if ($text == "/start") {
                sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "تعداد کانال های vip", 'callback_data' => 'vip']
                        ],
                        [
                            ['text' => "تعداد کانال های غیر فعال", 'callback_data' => 'novip']
                        ],
                        [
                            ['text' => "ارسال پیام به کانال های عیر فعال", 'callback_data' => 'tabligh']
                        ],
                        [
                            ['text' => "تعداد اعضا ربات", 'callback_data' => 'rownum']
                        ],
                        [
                            ['text' => "ارسال پیام به اعضا", 'callback_data' => 'dc']
                        ]
                    ]
                ];
                message_inline_query($modir, "چه خدمتی در نظر دارید.", $r);
            }
            if ($step == "tabligh") {
                sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
                $result = mysqli_query($conn, "SELECT chat_idc FROM channel WHERE vip=0");
                while ($row = mysqli_fetch_array($result)) {
                    $id = $row['chat_id'];
                    $text = urlencode($text);
                    sendmessage($id, $text);
                }
                sendmessage($modir, "تبلیغ به تمام کانال ها ارسال شد");
                sqlconnect("UPDATE aaza set step=NULL WHERE chat_id=$modir");
            } elseif ($step == "dc") {
                $result = mysqli_query($conn, "SELECT chat_id FROM aaza");
                while ($row = mysqli_fetch_array($result)) {
                    $id = $row['chat_id'];
                    $text = urlencode($text);
                    sendmessage($id, $text);
                }
                sendmessage($modir, "تبلیغ به تمام اعضا ارسال شد");
                sqlconnect("UPDATE aaza set step=NULL WHERE chat_id=$modir");
            }
        } else {
            if ($text == "/start") {
                if (checkaaza($chat_id) == false) {
                    sqlconnect("insert into aaza VALUES ($chat_id,'.' );");
                } else {
                    sqlconnect("update aaza set step=NULL WHERE chat_id=$chat_id");
                }
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "ایول چه خوب🙊ثبت کن کانالمو😍", 'callback_data' => 'newch']
                        ],
                        [
                            ['text' => "منوی کاربری😎", 'callback_data' => 'menue']
                        ],
                        [
                            ['text' => "راهنما🤔", 'callback_data' => 'help']
                        ]
                    ]
                ];
                message_inline_query($chat_id, "سلام دوست عزیز😍 
من ادمین یار تلگرام هستم.
یعنی چی؟؟؟🤔
 یعنی میتونم تو مدیریت کانال کمکت کنم🙊😍
حتما داری میگی چه جوری؟🤔
الان بهت میگم😝
فقط کافیه کانالت رو ثبت کنی و بعد چند تا کانال بهم معرفی کنی تا پست های اون کانال ها رو تو کانالت اونم با آیدی کانال خودت بذارم😯🙊😍", $r);
            } elseif ($step == "link") {
                $for = sqlget("select * from channel WHERE chat_idm=$chat_id")['chat_idc'];
                $re = mysqli_query($conn, "select * from channelc WHERE id='$text'");
                if ($re->num_rows == 0) {
                    sqlconnect("insert into channelc VALUES ('$text','$for',$chat_id,'$text' ,1,1,'$text')");
                } else {
                    sqlconnect("insert into channelc VALUES ('$text','$for',$chat_id,'$text' ,0,1,$text)");
                }
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "ویرایش کانال های مرجع🖍", 'callback_data' => 'changech']
                        ],
                        [
                            ['text' => "افزودن کانال مرجع📎", 'callback_data' => 'addch']
                        ],
                        [
                            ['text' => "منوی اصلی🏡", 'callback_data' => 'start']
                        ]
                    ]
                ];
                message_inline_query($chat_id, "تبریک😁
کانال مورد نظرت با موفقیت ثبت  شد😍

✅اگر میخوای کانال های مرجع رو مشاهده و یا حذف کنی گزینه ویرایش کانال های مرجع رو انتخاب کن

✅ برای افزودن یک کانال مرجع دیگه گزینه افزودن کانال مرجع روانتخاب کن
", $r);
            } elseif ($step == "soal") {
                sendmessage($chat_id, "سوالتو گرفتیم در اولین فرصت مسٔولین واحد پشتیبانی جوابتو میدن🤠🤠");
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "پاسخ", 'callback_data' => 'ans' . $chat_id]
                        ]
                    ]
                ];
                message_inline_query($poshtiban, $text, $r);
            } elseif (strstr($step, "answer")) {
                $id = preg_replace("/[^0-9]/", '', $c);
                sendmessage($text, $text);
            }
        }
    }else{
        $r = [
            'inline_keyboard' => [
                [
                    ['text' => "منوی اصلی🏡", 'callback_data' => 'start']
                ]
            ]
        ];
        message_inline_query($chat_id,"نمیتونم بفهمم میخوای چی کار کنی میشه برگردی به منوی اصلی و دوباره ذرخواستتو مطرح کنی؟😅😅",$r);
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
function Checkchannel($id,$for){
    $sql = "SELECT * FROM channelc WHERE id='$id' AND forch='$for'";
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
function sendaudio($chat_id, $text, $file)
{
    global $token;
    $text=urlencode($text);
    $url = "https://api.telegram.org/bot$token/sendaudio?chat_id=" . $chat_id .
        "&audio=" . $file . "&caption=" . $text;
    $a=json_decode(file_get_contents($url));
    return $a;    }

function sendvideo($chat_id, $text, $file)
{
    global $token;
    $text=urlencode($text);
    $url = "https://api.telegram.org/bot$token/sendvideo?chat_id=" . $chat_id .
        "&video=" . $file . "&caption=" . $text;
    $a=json_decode(file_get_contents($url));
    return $a;
}

function senddocument($chat_id, $text, $file)
{
    global $token;
    $text=urlencode($text);
    $url = "https://api.telegram.org/bot$token/senddocument?chat_id=" . $chat_id .
        "&document=" . $file . "&caption=" . $text;
    $a=json_decode(file_get_contents($url));
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
function linkpar(){
    $MerchantID = 'e69f24ee-efa6-11e7-982a-000c295eb8fc'; //Required
    $Amount = 15000; //Amount will be based on Toman - Required
    $Description = 'توضیحات تراکنش تستی'; // Required
    $Email = 'UserEmail@Mail.Com'; // Optional
    $Mobile = '09123456789'; // Optional
    $CallbackURL = 'http://www.mychanneladminbot.ir/verify.php'; // Required


    $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

    $result = $client->PaymentRequest(
        [
            'MerchantID' => $MerchantID,
            'Amount' => $Amount,
            'Description' => $Description,
            'Email' => $Email,
            'Mobile' => $Mobile,
            'CallbackURL' => $CallbackURL,
        ]
    );

//Redirect to URL You can do it also by creating a form
    if ($result->Status == 100) {
        return "Location: https://sandbox.zarinpal.com/pg/StartPay/".$result->Authority;
    } else {
        echo'ERR: '.$result->Status;
    }
}






?>




?>

