<?php
//Channel : @ZarinSource
//Author : @MaMaD_NoP
# Author : Mohammad / Mrlotfi
require_once "function.php";
require_once "ZarinSource.php";
error_reporting(0);
date_default_timezone_set("Asia/Tehran");
$update = json_decode(file_get_contents("php://input"));
if (isset($update)) {
    $telegram_ip_ranges = [["lower" => "149.154.160.0", "upper" => "149.154.175.255"], ["lower" => "91.108.4.0", "upper" => "91.108.7.255"]];
    $ip_dec = (int) sprintf("%u", ip2long($_SERVER["REMOTE_ADDR"]));
    $ok = false;
    foreach ($telegram_ip_ranges as $telegram_ip_range) {
        if (!$ok) {
            $lower_dec = (int) sprintf("%u", ip2long($telegram_ip_range["lower"]));
            $upper_dec = (int) sprintf("%u", ip2long($telegram_ip_range["upper"]));
            if ($lower_dec <= $ip_dec && $ip_dec <= $upper_dec) {
                $ok = true;
            }
        }
    }
    if (!$ok) {
        redirect("google.com");
    }
}
/* ----- {Date} ------ */
$date = date("Y-m-d");
$time = date("H:i:s");
/* ----- {Medoo} ------ */
require 'Medoo.php';
use Medoo\Medoo;
/* ----- {TOKEN} ------ */
define('BOT_TOKEN', '*****'); // TOKEN
$ZarinSource = new ZarinSource(BOT_TOKEN);
/* ----- {Admin} ------ */
$Admin = ["*****"]; // ID Admin
$num = "*****"; // سر شماره ارسال پیامک (اگر از رادکام اس ام اس پنل دارید شماره 3000505 بزنید)
/* ----- {database} ------ */
$host ="localhost"; // do not change
$Name_database = "*****"; // name database
$user_database = "*****"; // username database
$pass_database = '*****'; // password database
$Medoo = new Medoo([
    'type' => 'mysql',
    'host' => $host,
    'database' => $Name_database,
    'username' => $user_database,
    'password' => $pass_database,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
]);
$mysqli = new mysqli($host, $user_database, $pass_database, $Name_database);
$mysqli->set_charset("utf8mb4");
/* ----- {keyboards} ------ */
$key['support'] = "پشتیبانی";
$key['panel'] = "پنل مدیریت";
$key['pay'] = "پرداخت";
$key['sharecontact'] = "ارسال شماره";
$key['back'] = "برگشت";

$key["backadmin"] = "back";
$key["stats"] = "آمار ربات";
$key["settingpay"] = "خاموش/روشن درگاه";
$key["listpays"] = "لیست پرداخت ها";
$key["payam"] = "همگانی";
$key["pmall"] = "پیام همگانی";
$key["fwall"] = "فوروارد همگانی";
$key["payamuser"] = "پیام به کاربر";
$key["merchantcode"] = "تنظیم مرچنت کد";
$key["offidpay"] = "خاموش کردن ایدی پی";
$key["offzarin"] = "خاموش کردن زرین پال";
$key["offnextp"] = "خاموش کردن نکست پی";
$key["onidpay"] = "روشن کردن ایدی پی";
$key["onzarin"] = "روشن کردن زرین پال";
$key["onnextp"] = "روشن کردن نکست پی";
$key["merchatidpay"] = "مرچنت کد ایدی پی";
$key["merchatzarin"] = "مرچنت کد زرین پال";
$key["merchatnextp"] = "مرچنت کد نکست پی";
$key["payamak"] = "تنظیمات پیامک";
$key["usersms"] = "نام کاربری پنل";
$key["passsms"] = "رمز پنل";
$key["idpatern"] = "ایدی پترن";
$key["onsms"] = "روشن کردن پیامک";
$key["offsms"] = "خاموش کردن پیامک";
/* ----- {class} ------ */
class bot {
    function keyboard($keyboard)
    {
        global $key;
        switch ($keyboard[0]) {
            case 'sms' :
                $k=['resize_keyboard'=>true,'keyboard'=>[
                    [['text' => $key["idpatern"]]],
                    [['text' => $key["usersms"]],['text' => $key["passsms"]]],
                    [['text' => $key["onsms"]],['text' => $key["offsms"]]],
                    [['text' => $key["backadmin"]]]
                ]];
                break;
            case 'admin' :
                $k=['resize_keyboard'=>true,'keyboard'=>[
                    [['text' => $key["stats"]],['text' => $key["payam"]]],
                    [['text' => $key["payamuser"]],['text' => $key["listpays"]]],
                    [['text' => $key['merchantcode']],['text' => $key["settingpay"]],['text' => $key["payamak"]]],
                    [['text' => $key["back"]]]
                ]];
                break;
            case 'settingpay' :
                $k=['resize_keyboard'=>true,'keyboard'=>[
                    [['text' => $key["offidpay"]],['text' => $key["onidpay"]]],
                    [['text' => $key["offzarin"]],['text' => $key["onzarin"]]],
                    [['text' => $key["offnextp"]],['text' => $key["onnextp"]]],
                    [['text' => $key["backadmin"]]]
                ]];
                break;
            case 'merchantcode' :
                $k=['resize_keyboard'=>true,'keyboard'=>[
                    [['text' => $key["merchatidpay"]],['text' => $key["merchatzarin"]]],
                    [['text' => $key["merchatnextp"]],['text' => $key["backadmin"]]],
                ]];
                break;
            case 'home' :
                $k=['resize_keyboard'=>true,'keyboard'=>[
                    [['text' => $key['pay']],['text' => $key['support']]],
                ]];
                break;
            case 'back' :
                $k=['resize_keyboard'=>true,'keyboard'=>[
                    [['text' => $key['back']]]
                ]];
                break;
            case 'backadmin' :
                $k=['resize_keyboard'=>true,'keyboard'=>[
                    [['text' => $key['backadmin']]]
                ]];
                break;
            case 'request_contact' :
                $k=['keyboard'=>[
                    [['text'=> $key['sharecontact'] , 'request_contact' => true]],
                    [['text' => $key['back']]]
                ],
                    'resize_keyboard'=>true
                ];
                break;
            case 'payam' :
                $k=['keyboard'=>[
                    [['text'=> $key["pmall"]],['text' => $key["fwall"]]],
                    [['text' => $key["backadmin"]]]
                ],
                    'resize_keyboard'=>true
                ];
                break;
        }
        return $k;
    }
    function text($te)
    {
        switch ($te[0]) {
            case 'start' :
                $idbot = $te[1];
                $t = "سلام دوست عزیز
در این ربات میتوانید پرداخت خود را انجام بدهید !
برای پرداخت رو دکمه <b>پرداخت</b> بزنید !
درصورت هرگونه مشکل میتوانید از طریق دکمه <b>پشتیبانی</b> مشکل خود را بیان کنید !

@$idbot";
                break;
            case 'back' :
                $t = "به منوی اصلی برگشتید !";
                break;
            case 'senddescription':
                $t = "لطفا توضیحات خود را برای پرداخت ارسال کنید !";
                break;
            case 'sendsupport':
                $t = "📌 پیام شما با موفقیت به پشتیبانی ارسال شد ✅\n\n⏳ منتظر پاسخ از سمت پشتیبانی باشید";
                break;
            case 'support':
                $t = "⚜️ لطفا انتقاد، پیشنهاد و یا مشکل خود را برای ما ارسال کنید 👇\n\n❌ توجه داشته باشید که پیام خود را بصورت رسانه ، فایل ، عکس و متن ارسال کنید ❌\n\n➖➖➖➖➖➖➖➖➖➖➖➖➖\n‼️ برای لغو /cancel را ارسال کنید.";
                break;
            case 'error_text' :
                $t = "لطفا متن توضحات را ارسال کنید !";
                break;
            case 'send_amount' :
                $t = "مبلغ را به تومان وارد کنید !";
                break;
            case 'error_int' :
                $t = "لطفا عدد وارد کنید !";
                break;
            case 'error_amount' :
                $t = "مبلغ باید بیشتر از 1,000 تومان و کمتر از 5,000,000 تومان باشد";
                break;
            case 'backpay' :
                $t = "لطفا فیلتر شکن خود را خاموش کنید و پرداخت را انجام بدهید !";
                break;
            case 'pay' :
                $from_id = $te[1];
                $amount = $te[2];
                $code = $te[3];
                $descrtiption = $te[4];
                $t = "
لینک پرداخت برای شما ساخته شد :

شناسه : <code>$from_id</code>
مبلغ : $amount تومان
کد پیگیری : <code>$code</code>
توضیحات : $descrtiption

برای پرداخت روی دکمه زیر کلیک کنید !
                ";
                break;
            case 'error_phone' :
                $t = "شما هنوز شماره خود را ثبت نکرده اید !";
                break;
            case 'NOTiran' :
                $t = "کاربر عزیز به دلیل اینکه شماره شما برای ایران نمیباشد ما نمیتوانیم هویت شما را تایید  کنیم ❗️";
                break;
            case 'NOTphonruser' :
                $t = "🚫 شماره خود را با کمک از کیبورد زیر ارسال کنید 👇🏻";
                break;
            case 'phoneiran' :
                $t = "😃 شماره شما باموفقیت ثبت شد !\n⭕️ یکی از موارد زیر را انتخاب کنید 👇";
                break;
            case 'sendphone' :
                $from_id = $te[1];
                $phone = $te[2];
                $t = "#تایید_هویت
کاربر : <a href = 'tg://user?id=" . $from_id . "'>" . $from_id . "</a>
شماره تلفن : $phone";
                break;
            case 'admin' :
                $t = "به پنل مدیریت خوش اومدید !";
                break;
            case 'stats' :
                $users = $te[1];
                $success = $te[2];
                $unseccess = $te[3];
                $paytoday = $te[4];
                $payyes = $te[5];
                $pil = $te[6];
                $t = "
تعداد کاربران : $users
تعداد پرداخت های موفق : $success
تعداد پرداخت های ناموفق : $unseccess
تعداد پرداختی های امروز : $paytoday
تعداد پرداختی های دیروز : $payyes
مبلغ کل پرداختی ها : $pil";
                break;
            case 'not_user' :
                $t = "کاربر یافت نشد !";
                break;
            case 'select' :
                $t = "یکی از موارد زیر را انتخاب کنید !";
                break;
            case 'off_pay' :
                $t = "تا اطلاع ثانوی درگاه پرداخت خاموش میباشد !";
                break;
            case 'upmerchant' :
                $merchant = $te[1];
                $t = "لطفا مرچنت کد درگاهتون رو وارد کنید !\nمرچنت کد فعلی : $merchant";
                break;
            case 'offpay' :
                $t = "درگاه ربات خاموش شد !";
                break;
            case 'onpay' :
                $t = "درگاه ربات روشن شد !";
                break;
            case 'sendsms' :
                $t = "یک پیام حاوی کد تایید برای شما ارسال شده است\nلطفا کد را ارسال کنید";
                break;
            case 'errorsendsms' :
                $t = "لطفا مجدد شماره خود را ارسال کنید !";
                break;
            case 'error_card' :
                $t = "لطفا شماره کارت خود را ارسال کنید !";    
                break;
        }
        return $t;
    }
}
$bot = @new bot();

if (isset($update->message)) {
    $message = $update->message;
    $text = $message->text;
    $tc = $message->chat->type;
    $chat_id = $message->chat->id;
    $username = $message->chat->username;
    $from_id = $message->from->id;
    $message_id = $message->message_id;
    $photo = $message->photo;
    $audio = $message->audio;
    $video = $message->video;
    $document = $message->document;
    $contact = $message->contact;
    $contact_id = $contact->user_id;
    $contactnum = $contact->phone_number;
    $caption = $message->caption;
} else {
    if (isset($update->callback_query)) {
        $callback_query = $update->callback_query;
        $data = $callback_query->data;
        $inline_message_id = $callback_query->inline_message_id;
        $tc = $callback_query->message->chat->type;
        $chat_id = $callback_query->message->chat->id;
        $from_id = $callback_query->from->id;
        $message_id = $callback_query->message->message_id;
        $first_name = @str_replace([">", "<"], ["&gt;", "&lt;"], $callback_query->from->first_name);
        $callback_id = $callback_query->id;
        $text = $callback_query->message->text;
    }
}

if ($from_id == $Admin[0]) {
    $home = $bot->keyboard(["home"]);
    $home["keyboard"][] = [["text" => $key['panel']]];
    $home = json_encode($home);
} else {
    $home = json_encode($bot->keyboard(["home"]));
}
# Author : MaMaD / Mrlotfi
# ID Tel : @MaMaD_NoP
# ID Channel : @ZarinSource
$idbot = $ZarinSource->getMe();
$user = $Medoo->select("user","*",["id" => $from_id])[0];
$countgaton = $Medoo->count("payment",["off" => 1]);
$setting = $Medoo->select("setting","*")[0];
if ($tc == "private"){
    $check = $Medoo->has("user",["id" => $from_id]);
    if ($check){

        if ($text == "/start") {
            $ZarinSource->sendMessage($from_id,$bot->text(["start", $idbot]),$home);
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
            return false;
        }
        if ($text == "برگشت" or $text == "/cancel"){
            $ZarinSource->sendMessage($from_id,$bot->text(["back"]),$home);
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
            return false;
        }
        if ($text == $key['support']){
            $ZarinSource->sendMessage($from_id,$bot->text(["support"]),$bot->keyboard(["back"]));
            $Medoo->update("user",["step" => "support"],["id" => $from_id]);
        }

        elseif (preg_match('/sendamount-([0-9]+)/i', $user["step"] , $match)){
            if ($countgaton >= 1){
                if ($user["phone"] != NULL){
                    if($user["card-pen"] != NULL){
                        if (is_numeric($text)){
                            if ($text >= 1000 and $text <= 5000000){
                                $domin = domin();

                                $code = $match[1];
                                $Medoo->update("pay",["amount" => $text],["code" => $code]);
                                $pay = $Medoo->select("pay","*",["code" => $code])[0];

                                $phone = $user["phone"];
                                $Medoo->insert("pays",["id" => $from_id, "step" => "pay", "code" => $code, "amount" => $text, "phone" => $phone]);

                                $res = $Medoo->select("payment","*",["off" => 1]);
                                $t[] = [["text" => "👇🏻 لینک پرداخت 👇🏻", "callback_data" => "fyk"]];

                                foreach ($res as $pa) {
                                    $t[] = [["text" => "$text تومان | ".$pa["name"] , "url" => "https://" . $domin . "/" . "payment" . ".php?code=$code&".$pa["file"]."&get"]];
                                }
                                $ZarinSource->sendMessage($from_id,$bot->text(["pay",$from_id,$text,$code,$pay["des"]]),json_encode(["inline_keyboard" => $t]));
                                $ZarinSource->sendMessage($from_id,$bot->text(["backpay"]),$home);
                                $Medoo->update("user",["step" => "none"],["id" => $from_id]);
                            } else {
                                $ZarinSource->sendMessage($from_id,$bot->text(["error_amount"]),$bot->keyboard(["back"]));
                            }
                        } else {
                            $ZarinSource->sendMessage($from_id,$bot->text(["error_int"]),$bot->keyboard(["back"]));
                        }
                    } else {
                        $Medoo->update("user",["step" => "cardpen"],["id" => $from_id]);
                        $ZarinSource->sendMessage($from_id,$bot->text(["error_card"]),$bot->keyboard(["back"]));
                    }
                } else {
                    $Medoo->update("user",["step" => "verifyphone"],["id" => $from_id]);
                    $ZarinSource->sendMessage($from_id,$bot->text(["error_phone"]),$bot->keyboard(["request_contact"]));
                }
            } else {
                $ZarinSource->sendMessage($from_id,$bot->text(["off_pay"]),$home);
            }
        }
        
        if ($text == $key['pay']){
            if ($countgaton >= 1){
                if ($user["phone"] != NULL){
                    if($user["card-pen"] != NULL){
                        $ZarinSource->sendMessage($from_id,$bot->text(["senddescription"]),$bot->keyboard(["back"]));
                        $Medoo->update("user",["step" => "senddescription"],["id" => $from_id]);
                    } else {
                        $Medoo->update("user",["step" => "cardpen"],["id" => $from_id]);
                        $ZarinSource->sendMessage($from_id,$bot->text(["error_card"]),$bot->keyboard(["back"]));
                    }
                } else {
                    $Medoo->update("user",["step" => "verifyphone"],["id" => $from_id]);
                    $ZarinSource->sendMessage($from_id,$bot->text(["error_phone"]),$bot->keyboard(["request_contact"]));
                }
            } else {
                $ZarinSource->sendMessage($from_id,$bot->text(["off_pay"]),$home);
            }
        }
        
        
        elseif ($user["step"] == "senddescription"){
            if (isset($text)){
                $code = random('p');
                $Medoo->insert("pay",["id" => $from_id, "amount" => "0", "des" => $text, "code" => $code]);
                $ZarinSource->sendMessage($from_id,$bot->text(["send_amount"]),$bot->keyboard(["back"]));
                $Medoo->update("user",["step" => "sendamount-$code"],["id" => $from_id]);
            } else {
                $ZarinSource->sendMessage($from_id,$bot->text(["error_text"]),$bot->keyboard(["back"]));
            }
        }

        elseif ($user["step"] == "support"){
            $ZarinSource->ForwardMessage($from_id,$Admin[0],$message_id);
            $ZarinSource->sendMessage($from_id,$bot->text(["sendsupport"]),$home);
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
        }

        elseif($user["step"] == "cardpen"){
            if(is_numeric($text)){
                if(bankCardCheck($text)){
                    $Medoo->update("user",["card-pen" => $text],["id" => $from_id]);
                    $ZarinSource->sendMessage($from_id,"شماره کارت شما ثبت شد !",$home);
                } else {
                    $ZarinSource->sendMessage($from_id,"لطفا شماره کارت را به درستی وارد کنید !",$bot->keyboard(["back"]));
                }
            } else {
                $ZarinSource->sendMessage($from_id,"لطفا شماره کارت را به درستی وارد کنید !",$bot->keyboard(["back"]));
            }
        }
        elseif ($user["step"] == "verifyphone"){
            if ($contact_id == $from_id){
                if (strpos($contactnum, "98") === 0 || strpos($contactnum, "+98") === 0) {
                    if ($setting["sms"] == 1){
                        $contactuser = '0' . strrev(substr(strrev($contactnum), 0, 10));
                        # send sms
                        $code = rand(11111, 99999);
                        $sms = sendsms($setting["smsuser"], $setting["smspass"], $setting["smsid"], $num, $contactnum, "code", $code);
                        if (is_numeric($sms)) {
                            $ZarinSource->sendMessage($from_id,$bot->text(["sendsms"]),$bot->keyboard(["back"]));
                            $Medoo->update("user",["step" => "sendcodesms-$code-$contactuser"],["id" => $from_id]);
                        } else {
                            $ZarinSource->sendMessage($Admin[0],"مشکل در ارسال پیامک :\n$sms");
                            $ZarinSource->sendMessage($from_id,$bot->text(["errorsendsms"]),$bot->keyboard(["request_contact"]));
                        }
                    } else {
                        $phone = str_ireplace("98", "0", $contactnum);
                        $Medoo->update("user", ["phone" => $phone], ["id" => $from_id]);
                        $ZarinSource->sendMessage($from_id,$bot->text(["phoneiran"]),$home);
                        $Medoo->update("user", ["step" => "none"], ["id" => $from_id]);
                        $ZarinSource->sendMessage($Admin[0],$bot->text(["sendphone",$from_id,$phone]));
                    }
                } else {
                    $ZarinSource->sendMessage($from_id,$bot->text(["NOTiran"]),$home);
                    $Medoo->update("user", ["step" => "none"], ["id" => $from_id]);
                }
            } else {
                $ZarinSource->sendMessage($from_id,$bot->text(["NOTphonruser"]),$bot->keyboard(["request_contact"]));
                $Medoo->update("user", ["step" => "verifyphone"], ["id" => $from_id]);
            }
        }
        elseif (preg_match('/sendcodesms-([0-9]+)-([0-9]+)/i', $user["step"] , $match)) {
            $phone = $match[2];
            $code = $match[1];
            if (is_numeric($text)){
                if ($text == $code) {
                    $Medoo->update("user", ["phone" => $phone], ["id" => $from_id]);
                    $ZarinSource->sendMessage($from_id,$bot->text(["phoneiran"]),$home);
                    $Medoo->update("user", ["step" => "none"], ["id" => $from_id]);
                    $ZarinSource->sendMessage($Admin[0],$bot->text(["sendphone",$from_id,$phone]));
                } else {
                    $ZarinSource->sendMessage($from_id,"کد اشتباه است ، مجدد ارسال کنید !",$bot->keyboard(["back"]));
                }
            } else {
                $ZarinSource->sendMessage($from_id,"کد را ارسال کن !",$bot->keyboard(["back"]));
            }
        }

    } else {
        $ZarinSource->sendMessage($from_id,$bot->text(["start", $idbot]),$home);
        $Medoo->insert("user",["id" => $from_id]);
    }
    if ($from_id == $Admin[0]){
        $offI = $Medoo->select("payment","*",["file" => "idpay"])[0];
        $offZ = $Medoo->select("payment","*",["file" => "zarinpal"])[0];
        $offN = $Medoo->select("payment","*",["file" => "nextpay"])[0];
        if ($text == $key['panel'] or $text == "back"){
            $ZarinSource->sendMessage($from_id,$bot->text(["admin"]),$bot->keyboard(["admin"]));
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
            if(file_exists("install.php")){unlink("install.php");}
            return false;
        }
        if ($text == $key["stats"]){
            $users = $Medoo->count("user","id");
            $success = $Medoo->count("pays",["step" => "successful"]);
            $unseccess = $Medoo->count("pays",["step[!]" => "pay","step[!]" => "successful"]);
            $paytoday = $Medoo->count("pays",["date" => $date,"step" => "successful"]);
            $ydate = date("Y-m-d", strtotime($date) - 1);
            $payyes = $Medoo->count("pays",["date" => $ydate,"step" => "successful"]);
            $pil = $Medoo->sum("pays","amount",["step" => "successful"]);
            $ZarinSource->sendMessage($from_id,$bot->text(["stats",$users,$success,$unseccess,$paytoday,$payyes,$pil]),$bot->keyboard(["admin"]));
        }
        if ($text == $key["listpays"]){
            $search = $Medoo->select("pays","*",["step" => "successful","LIMIT" => 50]);
            foreach ($search as $info){
                $id = $info["id"]; $code = $info["code"]; $ip = $info["ip"]; $paycode = $info["paycode"]; $phone = $info["phone"];
                $amount = $info["amount"]; $time = $info["time"]; $date = $info["date"];
                $id = "<a href ='tg://user?id=$id'>$id</a>";
                $t .= "ایدی کاربر : $id\nکد پیگیری ربات : $code\nایپی کاربر : $ip\nکد پیگیری درگاه : $paycode\nشماره کاربر : $phone\nمبلغ واریز : $amount\nساعت پرداخت : $time\nتاریخ پرداخت : $date"."\n"."➖➖➖➖➖➖➖➖➖\n";
            }
            $ZarinSource->sendMessage($from_id,"لیست 50 تراکنش اخیر : \n〰️〰️〰️〰️〰️〰️〰️〰️〰️〰️"."\n".$t,$bot->keyboard(["admin"]));
        }
        if ($text == $key["payamuser"]){
            $ZarinSource->sendMessage($from_id,"ایدی عددی کاربر را ارسال کنید !",$bot->keyboard(["backadmin"]));
            $Medoo->update("user",["step" => "pmuser"],["id" => $from_id]);
        }
        if ($text == $key["payam"]){
            $ZarinSource->sendMessage($from_id,$bot->text(["select"]),$bot->keyboard(["payam"]));
            $Medoo->update("user",["step" => "payamm"],["id" => $from_id]);
        }
        if ($text == $key["merchantcode"]){
            $ZarinSource->sendMessage($from_id,"یکی از موارد زیر را انتخاب کنید !",$bot->keyboard(["merchantcode"]));
            $Medoo->update("user",["step" => "merchantcode"],["id" => $from_id]);
        }
        if ($text == $key["settingpay"]){
            $ZarinSource->sendMessage($from_id,"یکی از موارد زیر را انتخاب  کنید !",$bot->keyboard(["settingpay"]));
            $Medoo->update("user",["step" => "settingpay"],["id" => $from_id]);
        }


        if ($text == $key["payamak"]) {
            $Medoo->update("user",["step" => "payamak"],["id" => $from_id]);
            $ZarinSource->sendMessage($from_id,"یکی از موارد زیر را انتخاب کنید !",$bot->keyboard(["sms"]));
        }
        elseif ($text == $key["usersms"] and $user['step'] == "payamak"){
            $Medoo->update("user",["step" => "setusernamesms"],["id" => $from_id]);
            $username = $setting["smsuser"];
            $ZarinSource->sendMessage($from_id,"نام کاربری فعلی :\n$username\nبرای تغییر نام کاربری جدید را ارسال کنید",$bot->keyboard(["backadmin"]));
        }
        elseif ($user['step'] == "setusernamesms"){
            $ZarinSource->sendMessage($from_id,"$text ثبت شد ",$bot->keyboard(["admin"]));
            $Medoo->update("setting",["smsuser" => $text]);
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
        }
        elseif ($text == $key["passsms"] and $user['step'] == "payamak"){
            $Medoo->update("user",["step" => "setpasswordsms"],["id" => $from_id]);
            $password = $setting["smspass"];
            $ZarinSource->sendMessage($from_id,"رمز فعلی :\n$password\nبرای تغییر رمز جدید را ارسال کنید",$bot->keyboard(["backadmin"]));
        }
        elseif ($user['step'] == "setpasswordsms"){
            $ZarinSource->sendMessage($from_id,"$text ثبت شد ",$bot->keyboard(["admin"]));
            $Medoo->update("setting",["smspass" => $text]);
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
        }
        elseif ($text == $key["idpatern"] and $user['step'] == "payamak"){
            $Medoo->update("user",["step" => "setidpat"],["id" => $from_id]);
            $id = $setting["smsid"];
            $ZarinSource->sendMessage($from_id,"پترن فعلی :\n$id\nبرای تغییر کد پترن جدید را ارسال کنید\n\nدر متن پترن حتما کلمه %code% استفاده نمایید.",$bot->keyboard(["backadmin"]));
        }
        elseif ($user['step'] == "setidpat"){
            $ZarinSource->sendMessage($from_id,"$text ثبت شد ",$bot->keyboard(["admin"]));
            $Medoo->update("setting",["smsid" => $text]);
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
        }
        elseif ($text == $key["offsms"] and $user['step'] == "payamak"){
            $Medoo->update("setting",["sms" => 0]);
            $ZarinSource->sendMessage($from_id,"خاموش شد",$bot->keyboard(["admin"]));
        }
        elseif ($text == $key["onsms"] and $user['step'] == "payamak"){
            $Medoo->update("setting",["sms" => 1]);
            $ZarinSource->sendMessage($from_id,"روشن شد",$bot->keyboard(["admin"]));
        }




        elseif ($user["step"] == "merchantcode"){
            if ($text == $key["merchatidpay"]){
                $ZarinSource->sendMessage($from_id,"مرچنت کد درگاه ایدی پی خود را ارسال کنید !");
                $Medoo->update("user",["step" => "merchant-idpay"],["id" => $from_id]);
            }
            if ($text == $key["merchatzarin"]){
                $ZarinSource->sendMessage($from_id,"مرچنت کد درگاه زرین پال خود را ارسال کنید !");
                $Medoo->update("user",["step" => "merchant-zarinpal"],["id" => $from_id]);
            }
            if ($text == $key["merchatnextp"]){
                $ZarinSource->sendMessage($from_id,"مرچنت کد درگاه نکست پی خود را ارسال کنید !");
                $Medoo->update("user",["step" => "merchant-nextpay"],["id" => $from_id]);
            }
        }
        elseif (preg_match('/merchant-(nextpay|zarinpal|idpay)/i', $user["step"] , $match)){
            $na = $match[1];
            if ($na == "idpay"){
                $Medoo->update("payment",["code" => $text],["file" => $na]);
                $ZarinSource->sendMessage($from_id,"$text\nثبت شد",$bot->keyboard(["admin"]));
            }
            if ($na == "nextpay"){
                $Medoo->update("payment",["code" => $text],["file" => $na]);
                $ZarinSource->sendMessage($from_id,"$text\nثبت شد",$bot->keyboard(["admin"]));
            }
            if ($na == "zarinpal"){
                $Medoo->update("payment",["code" => $text],["file" => $na]);
                $ZarinSource->sendMessage($from_id,"$text\nثبت شد",$bot->keyboard(["admin"]));
            }
            $Medoo->update("user",['step' => "none"],["id" => $from_id]);
        }
        elseif ($user["step"] == "settingpay"){
            if ($text == $key["offidpay"]){
                $ZarinSource->sendMessage($from_id,"درگاه ایدی پی خاموش شد !",$bot->keyboard(["settingpay"]));
                $Medoo->update("payment",["off" => "0"],["file" => "idpay"]);
            }
            if ($text == $key["onidpay"]){
                $ZarinSource->sendMessage($from_id,"درگاه ایدی پی روشن شد !",$bot->keyboard(["settingpay"]));
                $Medoo->update("payment",["off" => "1"],["file" => "idpay"]);
            }
            if ($text == $key["offnextp"]){
                $ZarinSource->sendMessage($from_id,"درگاه نکست پی خاموش شد !",$bot->keyboard(["settingpay"]));
                $Medoo->update("payment",["off" => "0"],["file" => "nextpay"]);
            }
            if ($text == $key["onnextp"]){
                $ZarinSource->sendMessage($from_id,"درگاه نکست پی روشن شد !",$bot->keyboard(["settingpay"]));
                $Medoo->update("payment",["off" => "1"],["file" => "nextpay"]);
            }
            if ($text == $key["offzarin"]){
                $ZarinSource->sendMessage($from_id,"درگاه زرین پال خاموش شد !",$bot->keyboard(["settingpay"]));
                $Medoo->update("payment",["off" => "0"],["file" => "zarinpal"]);
            }
            if ($text == $key["onzarin"]){
                $ZarinSource->sendMessage($from_id,"درگاه زرین پال روشن شد !",$bot->keyboard(["settingpay"]));
                $Medoo->update("payment",["off" => "1"],["file" => "zarinpal"]);
            }
        }
        elseif ($user["step"] == "pmuser"){
            if (is_numeric($text)){
                $check = $Medoo->has("user",["id" => $text]);
                if ($check){
                    $ZarinSource->sendMessage($from_id,"پیام خود را ارسال کنید !"."\n\n"."توجه داشته باشید که شما میتوانید ویدیو ، رسانه ، عکس ، فایل و متن برای کاربر ارسال کنید\nاگر میخواهید عکس یا ویدیو یا فایل یا رسانه برای کاربر ارسال کنید حتما با کپشن ارسال کنید وگرنه فقط همان بدون کپشن ارسال میشود !",$bot->keyboard(["backadmin"]));
                    $Medoo->update("user",["step" => "pm-$text"],["id" => $from_id]);
                } else {
                    $ZarinSource->sendMessage($from_id,$bot->text(["not_user"]),$bot->keyboard(["backadmin"]));
                }
            } else {
                $ZarinSource->sendMessage($from_id,$bot->text(["error_int"]),$bot->keyboard(["backadmin"]));
            }
        }
        elseif (preg_match('/pm-([0-9]+)/i', $user["step"] , $match)){
            $id = $match[1];
            $t = "یک پیام از طرف پشتیبانی :\n\n";
            if (isset($document)){
                if (isset($caption)){
                    $file_id = $ZarinSource->update($update,document,file_id);
                    $ZarinSource->sendDocument($id,$file_id,$t.$caption);
                } else {
                    $file_id = $ZarinSource->update($update,document,file_id);
                    $ZarinSource->sendDocument($id,$file_id,$t);
                }
            }
            if (isset($video)){
                if (isset($caption)){
                    $file_id = $ZarinSource->update($update,video,file_id);
                    $ZarinSource->sendVideo($id,$file_id,$t.$caption);
                } else {
                    $file_id = $ZarinSource->update($update,video,file_id);
                    $ZarinSource->sendVideo($id,$file_id,$t);
                }
            }
            if (isset($audio)){
                if (isset($caption)){
                    $file_id = $ZarinSource->update($update,audio,file_id);
                    $ZarinSource->sendAudio($id,$file_id,$t.$caption);
                } else {
                    $file_id = $ZarinSource->update($update,audio,file_id);
                    $ZarinSource->sendAudio($id,$file_id,$t);
                }
            }
            if (isset($photo)){
                if (isset($caption)){
                    $file_id = end($update->message->photo)->file_id;
                    $ZarinSource->sendPhoto($id,$file_id,$t.$caption);
                } else {
                    $file_id = end($update->message->photo)->file_id;
                    $ZarinSource->sendPhoto($id,$file_id,$t);
                }
            }
            if (isset($text)){
                $ZarinSource->sendMessage($id,$t.$text);
            }
            $ZarinSource->sendMessage($from_id,"پیام برای کاربر ارسال شد !",$bot->keyboard(["admin"]));
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
        }
        elseif ($text == $key["pmall"] and $user["step"] == "payamm"){
            $ZarinSource->sendMessage($from_id,"پیام خود را ارسال کنید !"."\n\n"."توجه داشته باشید که شما میتوانید ویدیو ، رسانه ، عکس ، فایل و متن برای کاربران ارسال کنید\nاگر میخواهید عکس یا ویدیو یا فایل یا رسانه برای کاربران ارسال کنید حتما با کپشن ارسال کنید وگرنه فقط همان بدون کپشن ارسال میشود !",$bot->keyboard(["backadmin"]));
            $Medoo->update("user",["step" => "pmalluser"],["id" => $from_id]);
        }
        elseif ($text == $key["fwall"] and $user["step"] == "payamm"){
            $ZarinSource->sendMessage($from_id,"پیام خود را فوروارد کنید !",$bot->keyboard(["backadmin"]));
            $Medoo->update("user",["step" => "fwalluser"],["id" => $from_id]);
        }
        elseif ($user["step"] == "pmalluser"){
            $select = $Medoo->select("user","*");
            
            foreach ($select as $item){
                $id = $item["id"];
            if (isset($document)){
                if (isset($caption)){
                    $file_id = $ZarinSource->update($update,document,file_id);
                    $ZarinSource->sendDocument($id,$file_id,$caption);
                } else {
                    $file_id = $ZarinSource->update($update,document,file_id);
                    $ZarinSource->sendDocument($id,$file_id);
                }
            }
            if (isset($video)){
                if (isset($caption)){
                    $file_id = $ZarinSource->update($update,video,file_id);
                    $ZarinSource->sendVideo($id,$file_id,$t.$caption);
                } else {
                    $file_id = $ZarinSource->update($update,video,file_id);
                    $ZarinSource->sendVideo($id,$file_id);
                }
            }
            if (isset($audio)){
                if (isset($caption)){
                    $file_id = $ZarinSource->update($update,audio,file_id);
                    $ZarinSource->sendAudio($id,$file_id,$caption);
                } else {
                    $file_id = $ZarinSource->update($update,audio,file_id);
                    $ZarinSource->sendAudio($id,$file_id);
                }
            }
            if (isset($photo)){
                if (isset($caption)){
                    $file_id = end($update->message->photo)->file_id;
                    $ZarinSource->sendPhoto($id,$file_id,$caption);
                } else {
                    $file_id = end($update->message->photo)->file_id;
                    $ZarinSource->sendPhoto($id,$file_id);
                }
            }
            }
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
            $ZarinSource->sendMessage($from_id,"پیام شما باموفقیت ارسال شد !",$bot->keyboard(["admin"]));
        }
        elseif ($user["step"] == "fwalluser"){
            $select = $Medoo->select("user","*");
            foreach ($select as $item){
                $id = $item["id"];
                $ZarinSource->ForwardMessage($id,$id,$message_id);
            }
            $Medoo->update("user",["step" => "none"],["id" => $from_id]);
            $ZarinSource->sendMessage($from_id,"پیام شما باموفقیت ارسال شد !",$bot->keyboard(["admin"]));
        }
    }
} else {
    if ($tc == "group" || $tc == "channel"){
        $ZarinSource->leaveChat($chat_id);
    }
}
# Author : MaMaD / Mrlotfi
# ID Tel : @MaMaD_NoP
# ID Channel : @ZarinSource