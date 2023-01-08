<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 9/22/2018
 * Time: 9:35 PM
 */

class BuzzMe
{
    static function MakeBuzzMe($chat_id,$lang)
    {
        sqlMethod::sqlconnect("INSERT  INTO userbuzz (chat_id) VALUES ($chat_id)");
        sqlMethod::sqlconnect("insert into buzz (text, exp, dasteh, start, silent,id)SELECT NULL ,NULL ,NULL ,NULL ,NULL ,max(buzzid) FROM userbuzz");
      if ($lang == 1) {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "تفریحی و مسافرتی", 'callback_data' =>  'dast01'], ['text' => "آشپزی", 'callback_data' =>  'dast02']
                    ],
                    [
                        ['text' => "فرهنگی و هنری", 'callback_data' =>  'dast03'], ['text' => "خودرو واملاک", 'callback_data' =>  'dast04']
                    ],
                    [
                        ['text' => "سیاسی و خبری", 'callback_data' =>  'dast05'], ['text' => "دانلود", 'callback_data' =>  'dast06']
                    ],
                    [
                        ['text' => "فیلم و سریال", 'callback_data' =>  'dast07'], ['text' => "استخدامی", 'callback_data' =>  'dast08']
                    ],
                    [
                        ['text' => "مجله", 'callback_data' =>  'dast09'], ['text' => "متفرقه", 'callback_data' =>  'dast10']
                    ],
                    [
                        ['text' => "پزشکی و سلامت", 'callback_data' =>  'dast11'], ['text' => "ورزشی", 'callback_data' =>  'dast12']
                    ],
                    [
                        ['text' => "مخصوص خانم ها", 'callback_data' =>  'dast13'], ['text' => "آموزشی", 'callback_data' =>  'dast14']
                    ],
                    [
                        ['text' => "موسیقی", 'callback_data' =>  'dast15'], ['text' => "علم و فناوری", 'callback_data' =>  'dast16']
                    ],
                    [
                        ['text' => "مذهبی", 'callback_data' =>  'dast17'], ['text' => "عکس", 'callback_data' =>  'dast18']
                    ],
                    [
                        ['text' => "جوک و کلیپ", 'callback_data' =>  'dast19'], ['text' => "همه", 'callback_data' =>  'dast20']
                    ],
                    [
                        ['text' => "خانه", 'callback_data' => 'start']
                    ]
                ]
            ];
            telegramApi::message_inline_query($chat_id, "دسته بندی را انتخاب کنید", $r);
        } else {
            $r = [
                'inline_keyboard' => [
                    [
                        ['text' => "Fun & Traveling", 'callback_data' =>  'dast01'], ['text' => "Coocking", 'callback_data' =>  'dast02']
                    ],
                    [
                        ['text' => "Art & Culture", 'callback_data' =>  'dast03'], ['text' => "Cars & Housing", 'callback_data' =>  'dast04']
                    ],
                    [
                        ['text' => "News & Politics", 'callback_data' =>  'dast05'], ['text' => "Download", 'callback_data' =>  'dast06']
                    ],
                    [
                        ['text' => "Show & Movies", 'callback_data' =>  'dast07'], ['text' => "Employment", 'callback_data' =>  'dast08']
                    ],
                    [
                        ['text' => "Journal", 'callback_data' =>  'dast09'], ['text' => "Other", 'callback_data' =>  'dast10']
                    ],
                    [
                        ['text' => "medicine and health", 'callback_data' =>  'dast11'], ['text' => "Sport", 'callback_data' =>  'dast12']
                    ],
                    [
                        ['text' => "for women", 'callback_data' =>  'dast13'], ['text' => "Educational", 'callback_data' =>  'dast14']
                    ],
                    [
                        ['text' => "Music", 'callback_data' =>  'dast15'], ['text' => "Science and Technology", 'callback_data' =>  'dast16']
                    ],
                    [
                        ['text' => "Religious", 'callback_data' =>  'dast17'], ['text' => "Picture", 'callback_data' =>  'dast18']
                    ],
                    [
                        ['text' => "Funny Stuff", 'callback_data' =>  'dast19'], ['text' => "All", 'callback_data' =>  'dast20']
                    ],
                    [
                        ['text' => "Home", 'callback_data' => 'start']
                    ]
                ]
            ];
            telegramApi::message_inline_query($chat_id, "Pick a Category!", $r);
        }
    }

    static function show_user_Buzz_list($chat_id, $lang)
    {
        $r = ['inline_keyboard' => []];
        $result = sqlMethod::sqlconnect("select * from Buzz WHERE id IN (SELECT id from userbuzz WHERE chat_id=$chat_id) AND text IS NOT NULL ");
        if ($result->num_rows <= 1) {
            if ($lang == 1) {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "ثبت گوش به زنگ", 'callback_data' => 'goshinew']
                        ],
                        [
                            ['text' => "خانه", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "شما هنوز گوش به زنگی ثبت نکرده اید برای ثبت گوش به زنگ دکمه ی ثبت گوش به زنگ را انتخاب نمایید", $r);
            } else {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "new buzz me", 'callback_data' => 'goshinew']
                        ],
                        [
                            ['text' => "home", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "You don't set any buzz me to set new buzz me click on new buzz me", $r);
            }
        } else {
            while ($row = mysqli_fetch_array($result)) {
                $r['inline_keyboard'][][] = ['text' => "" . $row['text'], 'callback_data' => 'dele' . $row['id'] . ''];
            }
            if ($lang == 1) {
                $r['inline_keyboard'][][] = ['text' => "خانه", 'callback_data' => 'start'];
                telegramApi::message_inline_query($chat_id, "لیست گوش به زنگ های شما به صورت زیر است.برای حذف هر کدام کافی است بر روی دکمه مربوط به ان بزنید.", $r);
            } else {
                $r['inline_keyboard'][][] = ['text' => "home", 'callback_data' => 'start'];
                telegramApi::message_inline_query($chat_id, "you can see you're buuz me list here for delete each of them click on that", $r);
            }
        }
    }

    static function set_Buzz_activity($chat_id, $data, $lang)
    {
        $id = preg_replace("/[^0-9]/", '', $data);
        if ($id == 1) {
            if ($lang == 1) {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "فعال", 'callback_data' => 'goshi2']
                        ],
                        [
                            ['text' => "خانه", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "گوش به زنگ موقتا غیر فعال گردید", $r);
            } else {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "Active", 'callback_data' => 'goshi1']
                        ],
                        [
                            ['text' => "Home", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "The bot successfuly Deactivated!", $r);
            }

            sqlMethod::sqlconnect("update Buzz set silent=FALSE WHERE id IN (SELECT id from userbuzz WHERE chat_id=$chat_id)");
        } else {
            sqlMethod::sqlconnect("update Buzz set silent=FALSE WHERE id IN (SELECT id from userbuzz WHERE chat_id=$chat_id)");
            if ($lang == 1) {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "غیر فعال", 'callback_data' => 'goshi2']
                        ],
                        [
                            ['text' => "خانه", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "گوش به زنگ  فعال گردید", $r);
            } else {
                $r = [
                    'inline_keyboard' => [
                        [
                            ['text' => "Deactive", 'callback_data' => 'goshi2']
                        ],
                        [
                            ['text' => "Home", 'callback_data' => 'start']
                        ]
                    ]
                ];
                telegramApi::message_inline_query($chat_id, "The bot successfuly activated!", $r);
            }
            sqlMethod::sqlconnect("update Buzz set silent=FALSE WHERE id IN (SELECT id from userbuzz WHERE chat_id=$chat_id)");
        }
    }

    static function delete_buzz_me($buzz_Me_id)
    {
        sqlMethod::sqlconnect("delete from Buzz WHERE id=$buzz_Me_id");
    }

   static function setBuzzMe($text,$chat_id)
    {
        sqlMethod::sqlconnect("update seeker.Buzz set buzz.text=N'$text' WHERE id=(SELECT MAX(buzzid) FROM userbuzz WHERE chat_id=$chat_id)");
    }
}