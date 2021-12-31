<?php

require_once("index.php");
require_once("function.php");
require_once("functionpay.php");

$domin = domin();

$nextpay 	    = new nextpay();
$select_next    = $Medoo->select("payment","*",['file' => "nextpay"])[0];
$token_nextpay  = $select_next["code"];

$zarinpal       = new zarinpal();
$select_zarin   = $Medoo->select("payment","*",['file' => "zarinpal"])[0];
$token_zarinpal = $select_zarin["code"];

$idpay 	        = new idpay();
$select_idpay   = $Medoo->select("payment","*",['file' => "idpay"])[0];
$token_idpay    = $select_idpay["code"];

if (isset($_GET["nextpay"]) && isset($_GET["get"]) && isset($_GET["code"])){
    $code           = $_GET["code"];    
    $select_pays    = $Medoo->select("pays","*",['code' => $_GET["code"]])[0];
    $from_id        = $select_pays["id"];
    $user           = $Medoo->select('user','*',['id' => $from_id])[0];
    $search         = $Medoo->has("pays",["code" => $code]);
    if ($search)
    {
        $Phone          = $select_pays["phone"];
        $Amount         = $select_pays["amount"];
        $Order_ID       = $_GET["code"];
        $card           = $user["card-pen"];
        $CallbackURL    = "https://" . $domin . "/payment.php?backnextpay";

        $ip         = $nextpay->getip();
        $Medoo->update("pays", ["ip" => $ip],["code" => $code]);
        $result     = $nextpay->request($token_nextpay, $Order_ID, $Amount, $card, $phone, $CallbackURL, $ip);
        if ($select_pays["step"] == "pay")
        {
            if (isset($result["code"]) && $result["code"] == 100)
            {
                // Success and redirect to pay
                $nextpay->redirect($result["StartPay"]);
            } else {
                // error
                echo "خطا در ایجاد تراکنش";
                echo "<br />کد خطا : " . $result["code"];
                echo "<br />تفسیر و علت خطا : " . $result["Message"]["FarsiText"];
            }
        } else {
            die("<h1 style=\"text-align: center;margin-top:30px\">2درخواست نامعتبر است</h1>");
        }
    } else {
        die("<h1 style=\"text-align: center;margin-top:30px\">1درخواست نامعتبر است</h1>");
    }
} else {
if (isset($_GET["backnextpay"])){
    $code           = $_GET["order_id"];
    $select_pays    = $Medoo->select("pays","*",['code' => $code])[0];
    $from_id        = $select_pays["id"];
    $user           = $Medoo->select('user','*',['id' => $from_id])[0];
    $select_pay     = $Medoo->select("pay","*",['id' => $from_id, 'code' => $code])[0];

    $Amount         = $_GET["amount"]; // مبلغ تراکنش
    $trans_id       = $_GET["trans_id"]; // وضعیت تراکنش
    $np_status      = $_GET["np_status"]; // کد رهگیری ایدی پی
    $order_id       = $_GET["order_id"]; // شماره سفارش پذیرنده

    $result = $nextpay->verify($token_nextpay, $Amount, $trans_id, $np_status);

    if ($select_pays["step"] == "pay")
    {
        if (isset($result["code"]) && $result["code"] == 0 && $np_status == "OK" )
        {
            // Success
            echo "<h1 style=\"text-align: center;margin-top:30px\">تراکنش موفقیت آمیز بود</h1>";
            $pay = $result["Shaparak_Ref_Id"];
            $card = $result["card_holder"];
            $des = $select_pay['des'];
            $ip = $select_pays['ip'];
            $number = $select_pays['phone'];
            $name = $ZarinSource->getChatMember($from_id);
            $Medoo->update("pays",["step" => "successful", "paycode" => $pay, "time" => $time, "date" => $date],["code" => $code]);
            $caption = "
✅ #تراکنش_موفق
⚜ تراکنش باموفقیت انجام شد !
➖ جزییات تراکنش شما 👇
📌 شماره پیگیری درگاه : $pay
📌 مبلغ : $Amount هزار تومان
📌 توضحات تراکنش : $des
📌 شماره موبایل : $number
〰〰〰〰〰〰〰〰〰〰
با تشکر از پرداخت شما 🌹
                                ";
            $ZarinSource->sendMessage($from_id, $caption);
# Author : MaMaD / Mrlotfi
# ID Tel : @MaMaD_NoP
# ID Channel : @ZarinSource
            $caption = "
✅ #تراکنش_موفق
⚜ تراکنش کاربر باموفقیت انجام شد !
➖ جزییات تراکنش کاربر 👇
📌 شماره پیگیری درگاه : $pay
📌 مبلغ : $Amount هزار تومان
📌 توضیحات تراکنش : $des
📌 شماره موبایل کاربر : $number
📌 شماره کارت کاربر : $card
📌 ایپی کاربر : $ip
📌 ساعت : $time
📌 تاریخ : $date

📌 تراکنش های دیگر کاربر : 
#pay_$from_id
➖ جزییات حساب کاربر 👇
📌 نام کاربر : <a href = 'tg://user?id=" . $from_id . "'>" . $name . "</a>
📌 شناسه کاربر : <code>$from_id</code>
📌شماره موبایل کاربر : $number
〰〰〰〰〰〰〰〰〰
                                ";
            $ZarinSource->sendMessage($Admin[0], $caption);
        } else {
            // error
            echo "پرداخت با ارور مواجه شد";
            echo "<br />کد خطا : ". $result["code"];
            echo "<br />تفسیر و علت خطا : ". $result["Message"]["FarsiText"];
        }
    } else {
        die("<h1 style=\"text-align: center;margin-top:30px\">3درخواست نامعتبر است</h1>");
    }
}
}

if (isset($_GET["zarinpal"]) && isset($_GET["get"]) && isset($_GET["code"])){
    $code           = $_GET["code"];
    $select_pays    = $Medoo->select("pays","*",['code' => $_GET["code"]])[0];
    $from_id        = $select_pays["id"];
    $select_pay     = $Medoo->select("pay","*",['id' => $from_id, 'code' => $code])[0];
    $user           = $Medoo->select('user','*',['id' => $from_id])[0];
    $search         = $Medoo->has("pays",["code" => $code]);
    if ($search)
    {
        $Amount         = $select_pays["amount"]."0";
        $Description    = $select_pay["des"];
        $Email          = NULL;
        $Mobile         = $select_pays["phone"];
        $card_pan       = $user["card-pen"];
        $CallbackURL    = "https://" . $domin . "/payment.php?back&code=" . $_GET["code"] . "&zarinpal";

        $ip = $zarinpal->getip();
        $Medoo->update("pays", ["ip" => $ip],["code" => $code]);
        $result = $zarinpal->request($token_zarinpal, $Amount, $Description, $Email, $Mobile, $card_pan, $CallbackURL, $ip);
        if ($select_pays["step"] == "pay")
        {
            if (isset($result["code"]) && $result["code"] == 100)
            {
                // Success and redirect to pay
                $zarinpal->redirect($result["StartPay"]);
            } else {
                // error
                echo "خطا در ایجاد تراکنش";
                echo "<br />کد خطا : " . $result["code"];
                echo "<br />تفسیر و علت خطا : " . $result["Message"]["FarsiText"];
            }
        } else {
            die("<h1 style=\"text-align: center;margin-top:30px\">1درخواست نامعتبر است</h1>");
        }
    } else {
        die("<h1 style=\"text-align: center;margin-top:30px\">2درخواست نامعتبر است</h1>");
    }
} else {
if (isset($_GET["zarinpal"]) && isset($_GET["back"]) && isset($_GET["code"])){
    $code           = $_GET["code"];
    $select_pays    = $Medoo->select("pays","*",['code' => $code])[0];
    $from_id        = $select_pays["id"];
    $user           = $Medoo->select('user','*',['id' => $from_id])[0];
    $select_pay     = $Medoo->select("pay","*",['id' => $from_id, 'code' => $code])[0];

    $Amount 	= $select_pays["amount"];
    $Authority  = $_REQUEST["Authority"];
    $Status     = $_REQUEST["Status"];
    $result     = $zarinpal->verify($token_zarinpal, $Amount, $Authority, $Status);

    $code_pay = $result["code"];
    $pay = $result["RefID"];
    $card = $result["card_pan"];
    $des = $select_pay['des'];
    $ip = $select_pays['ip'];
    $number = $select_pays['phone'];
    $name = $ZarinSource->getChatMember($from_id);
    
    if ($select_pays["step"] == "pay") {
        if (isset($result["Authority"]) && $Status == "OK" && $code_pay == 100) {
            // Success
            echo "<h1 style=\"text-align: center;margin-top:30px\">تراکنش موفقیت آمیز بود</h1>";
            $Medoo->update("pays", ["step" => "successful", "paycode" => $pay, "time" => $time, "date" => $date], ["code" => $code]);
            $caption = "
✅ #تراکنش_موفق
⚜ تراکنش باموفقیت انجام شد !
➖ جزییات تراکنش شما 👇
📌 شماره پیگیری درگاه : $pay
📌 مبلغ : $Amount هزار تومان
📌 توضحات تراکنش : $des
📌 شماره موبایل : $number
〰〰〰〰〰〰〰〰〰〰
با تشکر از پرداخت شما 🌹
                                ";
            $ZarinSource->sendMessage($from_id, $caption);
# Author : MaMaD / Mrlotfi
# ID Tel : @MaMaD_NoP
# ID Channel : @ZarinSource
            $caption = "
✅ #تراکنش_موفق
⚜ تراکنش کاربر باموفقیت انجام شد !
➖ جزییات تراکنش کاربر 👇
📌 شماره پیگیری درگاه : $pay
📌 مبلغ : $Amount هزار تومان
📌 توضیحات تراکنش : $des
📌 شماره موبایل کاربر : $number
📌 شماره کارت کاربر : $card
📌 ایپی کاربر : $ip
📌 ساعت : $time
📌 تاریخ : $date

📌 تراکنش های دیگر کاربر : 
#pay_$from_id
➖ جزییات حساب کاربر 👇
📌 نام کاربر : <a href = 'tg://user?id=" . $from_id . "'>" . $name . "</a>
📌 شناسه کاربر : <code>$from_id</code>
📌شماره موبایل کاربر : $number
〰〰〰〰〰〰〰〰〰
                                ";
            $ZarinSource->sendMessage($Admin[0], $caption);
        } else {
            //print_r($result);
            // error
            echo "پرداخت ناموفق";
            echo "<br />کد خطا : " . $result["code"];
            echo "<br />تفسیر و علت خطا : " . $result["Message"]["FarsiText"];
        }
    } else {
        die("<h1 style=\"text-align: center;margin-top:30px\">درخواست نامعتبر است4</h1>");
    }
}
}

if (isset($_GET["idpay"]) && isset($_GET["get"]) && isset($_GET["code"])){
    $code           = $_GET["code"];
    $select_pays    = $Medoo->select("pays","*",['code' => $_GET["code"]])[0];
    $from_id        = $select_pays["id"];
    $select_pay     = $Medoo->select("pay","*",['id' => $from_id, 'code' => $code])[0];
    $user           = $Medoo->select('user','*',['id' => $from_id])[0];
    $search         = $Medoo->has("pays",["code" => $code]);
    if ($search) {
        $Phone          = $user["phone"];
        $Amount         = $select_pays["amount"]."0";
        $Description    = $select_pay['des'];
        $Email          = NULL;
        $name           = NULL;
        $Order_ID       = $_GET["code"];
        $CallbackURL    = "https://" . $domin . "/payment.php?back&code=" . $_GET["code"] . "&idpay";

        $ip = $idpay->getip();
        $Medoo->update("pays", ["ip" => $ip],["code" => $code]);
        $result = $idpay->request($token_idpay, $Order_ID, $Amount, $name, $Phone, $Description, $Email, $CallbackURL, $ip);
        if ($select_pays["step"] == "pay")
        {
            if (isset($result["code"]) && $result["code"] == 100)
            {
                // Success and redirect to pay
                $idpay->redirect($result["StartPay"]);
            } else {
                // error
                echo "خطا در ایجاد تراکنش";
                echo "<br />کد خطا : " . $result["code"];
                echo "<br />تفسیر و علت خطا : " . $result["Message"]["FarsiText"];
            }
        } else {
            die("<h1 style=\"text-align: center;margin-top:30px\">درخواست نامعتبر است5</h1>");
        }
    } else {
        die("<h1 style=\"text-align: center;margin-top:30px\">6درخواست نامعتبر است</h1>");
    }
} else {
if (isset($_GET["idpay"]) && isset($_GET["back"]) && isset($_GET["code"])){
    $code           = $_GET["code"];
    $select_pays    = $Medoo->select("pays","*",['code' => $code])[0];
    $from_id        = $select_pays["id"];
    $user           = $Medoo->select('user','*',['id' => $from_id])[0];
    $select_pay     = $Medoo->select("pay","*",['id' => $from_id, 'code' => $code])[0];

    $Amount         = $select_pays["amount"]; // مبلغ تراکنش
    $status         = $_GET["status"]; // وضعیت تراکنش
    $track_id       = $_GET["track_id"]; // کد رهگیری ایدی پی
    $id             = $_GET["id"]; // کلید منحصر بفرد تراکنش
    $order_id       = $_GET["order_id"]; // شماره سفارش پذیرنده

    $result = $idpay->verify($token_idpay, $id, $order_id);

    if ($select_pays["step"] == "pay") {
        if (isset($result["code"]) && $result["code"] == 100) {
            // Success
            echo "<h1 style=\"text-align: center;margin-top:30px\">تراکنش موفقیت آمیز بود</h1>";
            $pay = $result["track_id"];
            $card = $result["card_no"];
            $des = $select_pay['des'];
            $ip = $select_pays['ip'];
            $number = $select_pays['phone'];
            $name = $ZarinSource->getChatMember($from_id);
            $Medoo->update("pays", ["step" => "successful", "paycode" => $pay, "time" => $time, "date" => $date], ["code" => $code]);
            $caption = "
✅ #تراکنش_موفق
⚜ تراکنش باموفقیت انجام شد !
➖ جزییات تراکنش شما 👇
📌 شماره پیگیری درگاه : $pay
📌 مبلغ : $Amount هزار تومان
📌 توضحات تراکنش : $des
📌 شماره موبایل : $number
〰〰〰〰〰〰〰〰〰〰
با تشکر از پرداخت شما 🌹
                                ";
            $ZarinSource->sendMessage($from_id, $caption);
# Author : MaMaD / Mrlotfi
# ID Tel : @MaMaD_NoP
# ID Channel : @ZarinSource
            $caption = "
✅ #تراکنش_موفق
⚜ تراکنش کاربر باموفقیت انجام شد !
➖ جزییات تراکنش کاربر 👇
📌 شماره پیگیری درگاه : $pay
📌 مبلغ : $Amount هزار تومان
📌 توضیحات تراکنش : $des
📌 شماره موبایل کاربر : $number
📌 شماره کارت کاربر : $card
📌 ایپی کاربر : $ip
📌 ساعت : $time
📌 تاریخ : $date

📌 تراکنش های دیگر کاربر : 
#pay_$from_id
➖ جزییات حساب کاربر 👇
📌 نام کاربر : <a href = 'tg://user?id=" . $from_id . "'>" . $name . "</a>
📌 شناسه کاربر : <code>$from_id</code>
📌شماره موبایل کاربر : $number
〰〰〰〰〰〰〰〰〰
                                ";
            $ZarinSource->sendMessage($Admin[0], $caption);
        } else {
            //print_r($result);
            // error
            echo "پرداخت با ارور مواجه شد";
            echo "<br />کد خطا : " . $result["code"];
            echo "<br />تفسیر و علت خطا : " . $result["Message"]["FarsiText"];
        }
    } else {
        die("<h1 style=\"text-align: center;margin-top:30px\">7درخواست نامعتبر است</h1>");
    }
}
}