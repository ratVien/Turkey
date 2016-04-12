<?php
/**
 * Created by PhpStorm.
 * User: ratvien
 * Date: 04.04.16
 * Time: 10:54
 */
require(__DIR__ . '/../data/simple_html_dom.php');

class ParseFile{
    public $cookiePath,
        $beginDate,
        $endDate,
        $reportPath;

    function __construct($beginDate = '08/04/2016',
                         $endDate = '08/04/2016', 
                         $cookiePath = '/home/ratvien/www/ivan/cookieN.txt',
                         $reportPath = '/home/ratvien/report/report.xls'){
        $this->beginDate = $beginDate;
        $this->endDate = $endDate;
        $this->cookiePath = $cookiePath;
        $this->reportPath = $reportPath;
        $this->Auth();
    }

    public function Auth(){
        $file = new GetFile();
        $captchaFile = $file->getCaptchaFile();
        $captcha = new AntiCaptcha();
        $captchaText = $captcha->getCaptchaText($captchaFile);
        echo 'Got Captcha! ' . $captchaText . PHP_EOL;
        $url = "https://esasweb.araskargo.com.tr/framemenu.aspx";
        $postdata = array(
            '__EVENTTARGET'    => '',
            '__EVENTARGUMENT'       => '',
            '__VIEWSTATE'      => '/wEPDwULLTE4NjQzOTQyMTFkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYBBQhidG5FbnRlcpdfyF0iLgvMTpLugLpURYyzhTRu',
            '__VIEWSTATEGENERATOR'      => "99025D84",
            '__EVENTVALIDATION'	=> "/wEWBQKrusDCCQKl1bKzCQK1qbSRCwLS3s26CgKXt8D4AdvC3OnfdkF9JwgPDtznNUgiJKv0",
            'txtUserName'	=> "t2wtrade.kargo@outlook.com",
            'txtPassword'	=> "2WTRADE56789",
            'txtSecurityText' => $captchaText,
            'btnEnter.x'	=> "0",
            'btnEnter.y'	=> "0",
        );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0");
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath);
        $data = curl_exec($ch);
        curl_close($ch);

    }

    public function getReportPage(){
        // Выбор параметров отчета о доставке
        $ch = curl_init('https://esasweb.araskargo.com.tr/SendingCargoCriteriaPage.aspx');

        $postData = array(
            '__EVENTTARGET'    => 'ctl00$ContentPlaceHolder1$LinkButtonSendingCargoAll',
            '__EVENTARGUMENT'       => '',
            '__LASTFOCUS:'  => '',
            '__VIEWSTATE'      => '/wEPDwULLTE4OTc2MDYyNDEPFgQeCl9GaXJzdERhdGUFCjAxLzAyLzIwMTYeCV9MYXN0RGF0ZQUKMzEvMDMvMjAxNhYCZg9kFgICAw9kFgICAw9kFgoCAw9kFg4CAQ8PFgIeBFRleHQFLFQyV1RSQURFIEtPWk1FVMSwSyDDnFLDnE5MRVLEsCBTQU4uIFZFIEEuxZ4uZGQCAw8PFgIfAgUOU2V2ayBBZHJlc2xlcmlkZAIFDxBkZBYBZmQCBw88KwANAgAPFgYeE0F1dG9HZW5lcmF0ZUNvbHVtbnNoHgtfIURhdGFCb3VuZGceC18hSXRlbUNvdW50AgFkARAWAgIBAgIWAhQrAAUWBh4JRGF0YUZpZWxkBQtBRERSRVNTTkFNRR4KSGVhZGVyVGV4dAUJQURSRVMgQURJHg5Tb3J0RXhwcmVzc2lvbgULQUREUkVTU05BTUVkFgQeD0hvcml6b250YWxBbGlnbgsqKVN5c3RlbS5XZWIuVUkuV2ViQ29udHJvbHMuSG9yaXpvbnRhbEFsaWduAR4EXyFTQgKAgARkZBQrAAUWBh8GBQxUQVJHRVRVTklUSUQfBwUUQkHEnkxJIE9MRFXEnlUgxZ5VQkUfCAUMVEFSR0VUVU5JVElEZBYEHwkLKwQBHwoCgIAEZGQWAmZmFgJmD2QWBAIBD2QWBmYPZBYCAgEPFgIfAgVtPGlucHV0IHR5cGU9Y2hlY2tib3ggY2hlY2tlZCB2YWx1ZT0nMUQ1QzQ5MzQ0M0FCREY0RDhDQUQwNzhGQjE5RURGNUQnIGlkPUNoZWNrYm94R3JvdXAgIG5hbWU9Q2hlY2tib3hHcm91cCAgPmQCAQ8PFgIfAgU0VDJXVFJBREUgS09aTUVUxLBLICYjMjIwO1ImIzIyMDtOTEVSxLAgU0FOLiBWRSBBLsWeLmRkAgIPDxYCHwIFBkJFWU1FUmRkAgIPDxYCHgdWaXNpYmxlaGRkAg0PZBYCZg9kFgICAQ8QZGQWAWZkAh8PZBYCZg9kFgICAQ8QDxYGHg1EYXRhVGV4dEZpZWxkBQtQUk9KRUNUTkFNRR4ORGF0YVZhbHVlRmllbGQFCVBST0pFQ1RJRB8EZ2RkFgFmZAIhDxYCHwIF3AMNCiAgICAgICAgICAgIDxzY3JpcHQgbGFuZ3VhZ2U9amF2YXNjcmlwdD4NCiAgICAgICAgICAgICAgICANCiAgICAgICAgICAgICAgICBmdW5jdGlvbiBDaGVja2VkQWxsQ2hlY2tCb3goKQ0KICAgICAgICAgICAgew0KICAgICAgICAgICAgICAgIGZvciAodmFyIGk9MDtpPGRvY3VtZW50LmFzcG5ldEZvcm0uZWxlbWVudHMubGVuZ3RoO2krKykNCgkJCSAgICB7DQogICAgICAgICAgICAgICAgICAgIHZhciBlID0gZG9jdW1lbnQuYXNwbmV0Rm9ybS5lbGVtZW50c1tpXTsNCiAgICAgICAgICAgICAgICAgICAgaWYgKChlLm5hbWUgIT0gJ2Noa0FsbCcpICYmIChlLnR5cGU9PSdjaGVja2JveCcpKQ0KCQkJCQl7DQoJCQkJCQllLmNoZWNrZWQgPSBkb2N1bWVudC5hc3BuZXRGb3JtLmNoa0FsbC5jaGVja2VkOw0KCQkJCQl9DQoJCQkgICAgfQ0KICAgICAgICAgICAgfQ0KDQogICAgICAgICAgICA8L3NjcmlwdD4gDQoNCiAgICAgICAgICAgIGQCEw8WAh4JaW5uZXJodG1sBWY8c2NyaXB0PmFsZXJ0KCdCYcWfbGFuZ8Sxw6cgdGFyaWhpIGlsZSBCaXRpxZ8gdGFyaWhpbmluIGfDvG4gZmFya8SxIDEgYXlkYW4gZmF6bGEgb2xhbWF6ICEnKTs8L3NjcmlwdD5kAhkPDxYEHwIFG0fDtm5kZXJpbGVuIEthcmdvbGFyIChBVk9OKR8LaGRkAhsPDxYEHwIFP0fDtm5kZXJpbGVuIEthcmdvbGFyIChBVk9OIMOWWkVUIA0KICAgICAgICAgICAgICAgICAgIFRBQkxPREFOKR8LaGRkAh0PDxYEHwIFEVNtcyBSYXBvcnUgKEFWT04pHwtoZGQYAgUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgIFYWN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkVXNlckNvbnRyb2xBZGRyZXNzQ3JpdGVyaWExJFdlYlVzZXJDb250cm9sQ2FsZW5kZXIxJEltYWdlQnV0dG9uQ2FsZW5kZXIFYWN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkVXNlckNvbnRyb2xBZGRyZXNzQ3JpdGVyaWExJFdlYlVzZXJDb250cm9sQ2FsZW5kZXIyJEltYWdlQnV0dG9uQ2FsZW5kZXIFRWN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkVXNlckNvbnRyb2xBZGRyZXNzQ3JpdGVyaWExJEdyaWR2aWV3QWNjb3VudA88KwAKAQgCAWS230IQoQnttquFvoGuf4gAdel22Q==',
            '__VIEWSTATEGENERATOR'      => "4B4A5F88",
            '__EVENTVALIDATION'	=> "/wEWFAKYgYSGDQK59MLeBQKi5eTyAwK6nZTfDwLG3ZGIDgLRgKeQDQKY9eyPCwKj+PPJCALX/8mBAwLiqOnHCAK7goiKBAKS/JOLDQKY3tSxCQKY3tyxCQLowazTAwKwvKavDAK/4qXvAwKttt6EAwKL1cuYBgKA5ILJD7HX7Or5vHC2m64gNbdI3jfhpIkU",
            'ctl00$ContentPlaceHolder1$UserControlAddressCriteria1$RadioButtonList1'	=> "Açık Adresler Gelsin",
            'CheckboxGroup'	=> "1D5C493443ABDF4D8CAD078FB19EDF5D",
            'ctl00$ContentPlaceHolder1$UserControlAddressCriteria1$WebUserControlCalender1$TextBoxBeginDate' => $this->beginDate,
            'ctl00$ContentPlaceHolder1$UserControlAddressCriteria1$WebUserControlCalender2$TextBoxBeginDate'	=> $this->endDate,
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath);
        $reportPage = curl_exec($ch);
        curl_close($ch);
        return $reportPage;
    }

    public function getExcelLink($reportPage){
        preg_match('/Reserved.ReportViewerWebControl.axd\?[^\s]*Format=/', $reportPage, $link);
        return $link[0];
    }

    public function getExcelFile($link){
        $link = 'https://esasweb.araskargo.com.tr/'.$link.'EXCEL';
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0");
        $file = curl_exec($ch);
        curl_close($ch);
        //$file = file_get_contents($link);
        file_put_contents($this->reportPath,$file);
        echo "Файл report.xls создан в папке report\r\n";
    }
}