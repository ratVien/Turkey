<?php
/**
 * Created by PhpStorm.
 * User: ratvien
 * Date: 08.04.16
 * Time: 14:00
 */

class GetFile{
    public function getCaptchaFile(
        $urlFile = 'https://esasweb.araskargo.com.tr/Security.aspx',
        $cookiePath='/home/ratvien/www/ivan/cookieN.txt')
    {
        $ch = curl_init($urlFile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiePath);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0");
        $captchaFileCode = curl_exec($ch);
        curl_close($ch);
        return $captchaFileCode;
    }
}