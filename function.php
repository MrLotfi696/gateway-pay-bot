<?php
# Author : Mohammad / Mrlotfi
# ID Tel : @MaMaD_NoP
# ID Channel : @ZarinSource
function random($code = NULL){
    global $Medoo;
    $rand = rand(111111,999999);
    $code = $rand;
    $check = $Medoo->has("pays",["code" => $code]);
    if($check == 0){
        return $code;
    }
}
function bankCardCheck($card='', $irCard=true){
    $card = (string) preg_replace('/\D/','',$card);
    $strlen = strlen($card);
    if($irCard==true and $strlen!=16)
        return false;
    if($irCard!=true and ($strlen<13 or $strlen>19))
        return false;
    if(!in_array($card[0],[2,4,5,6,9]))
        return false;
    
    for($i=0; $i<$strlen; $i++)
    {
        $res[$i] = $card[$i];
        if(($strlen%2)==($i%2))
        {
            $res[$i] *= 2;
            if($res[$i]>9)
                $res[$i] -= 9;        
        }
    }
    return array_sum($res)%10==0?true:false;    
}
function getip()
{
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } else {
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
    }
    return $ip;
}
function ip_info($ip)
{
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, "http://ip-api.com/csv/" . $ip . "");
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
    $exec = curl_exec($c);
    curl_close($c);
    $exp = explode(",", $exec);
    $pais = $exp[1];
    return $pais;
}
function redirect($url)
{
    if (!headers_sent()){
        header("Location: $url");
    }else{
        echo "<script type='text/javascript'>window.location.href='$url'</script>";
        echo "<noscript><meta http-equiv='refresh' content='0;url=$url'/></noscript>";
    }
    exit;
}
# Author : MaMaD / Mrlotfi
# ID Tel : @MaMaD_NoP
# ID Channel : @ZarinSource
function domin()
{
    $exp = explode("/", $_SERVER["REQUEST_URI"]);
    $url = "";
    for ($i = 1; $i < count($exp) - 1; $i++) {
        $url .= "/" . $exp[$i];
    }
    return $_SERVER["HTTP_HOST"] . $url;
}
function sendsms($username,$password,$pattern_code,$from,$tonumber,$value,$code){
    $to = array($tonumber);
    $input_data = array($value => $code);
    $url = "https://ippanel.com/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
    $handler = curl_init($url);
    curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
    curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handler);
    return $response;
}

# Author : Mohammad / Mrlotfi
# ID Tel : @MaMaD_NoP
# ID Channel : @ZarinSource