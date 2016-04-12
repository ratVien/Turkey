<?php
/**
 * Created by PhpStorm.
 * User: ratvien
 * Date: 07.04.16
 * Time: 19:10
 */


class Detail{
    public $cookiePath, $reportPage, $reportRows;
    
    function __construct($reportPage, 
                         $cookiePath = '/home/ratvien/www/ivan/cookieN.txt')
    {
        $this->cookiePath = $cookiePath;
        $this->reportPage = $reportPage;
        $this->reportRows = $this->countReportRows();
        
    }

    public function countReportRows(){
        $dom = new simple_html_dom();
        $html = $dom->load($this->reportPage);
        $rowsCount=0;
        foreach($html->find('a') as $a){

            if($a->plaintext=='DETAY'){
                $rowsCount++;
            }
        }
        echo 'Report page strings count: '. $rowsCount . PHP_EOL;
        return $rowsCount;
    }

    public function getDetailPage($i){

        $dom = new simple_html_dom();
        $dom->load($this->reportPage);
        $viewState = $dom->find('#__VIEWSTATE');
        $eventValidation = $dom->find('#__EVENTVALIDATION');
        $viewState = $viewState[0]->value;
        $eventValidation = $eventValidation[0]->value;
        $postdata = array(
            '__EVENTTARGET'    => 'ctl00$ContentPlaceHolder1$WebUserReportList1$reportViewer',
            '__EVENTARGUMENT'       => '',
            '__VIEWSTATE'      => $viewState,
            '__VIEWSTATEGENERATOR'      => "5A9868AB",
            '__EVENTVALIDATION'	=> $eventValidation,
            'ctl00$ContentPlaceHolder1$WebUserReportList1$reportViewer$ctl01$ctl05$ctl00'	=> "Select a format",
            'ctl00$ContentPlaceHolder1$WebUserReportList1$reportViewer$ctl04'	=> "Drillthrough",
            'ctl00$ContentPlaceHolder1$WebUserReportList1$reportViewer$ctl05' => '161iT0R0x'.$i.':0',
            'ctl00$ContentPlaceHolder1$WebUserReportList1$reportViewer$ctl06'	=> "1",
            'ctl00$ContentPlaceHolder1$WebUserReportList1$reportViewer$ctl07'	=> "false",
            'ctl00$ContentPlaceHolder1$WebUserReportList1$reportViewer$ctl08'	=> "false",
        );

        $ch = curl_init('https://esasweb.araskargo.com.tr/SendingCargoDetailyPage.aspx');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:45.0) Gecko/20100101 Firefox/45.0");
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath);
        $detail = curl_exec($ch);
        curl_close($ch);
        //$html = $dom->load($detail);
        //file_put_contents('1.html', $detail);
        return $detail;
    }

    public function getDetailTableString1($detail){
        //$detailFile = file_get_contents('1.html');
        $dom = new simple_html_dom();
        $dom->load($detail);

        $strTable1 = '';
        $td = $dom->find('td[colspan*=4] table tr td');
        $i=0;
        foreach ($td as $value){
            if($value->plaintext=='&nbsp;'){
                continue;
            }
            $i++;                                  //оставляем только 9 первых значений
            if($i<=9){
                $strTable1.= "'" . $value->plaintext . "',";
            }
            else break;
        }
        //echo $strTable1 . PHP_EOL;
        $strTable1 = substr($strTable1, 0, -1);
        //echo $strTable1 . PHP_EOL;
        return $strTable1;                      //Записываем значения ячеек таблицы в строку
    }

    public function getDetailTableString2($detail){
        //$detailFile = file_get_contents('1.html');
        $dom = new simple_html_dom();
        $dom->load($detail);
        $strTable2 = '';
        $table2=$dom->find('td[id*=ReportCell] table tbody tr td div table tbody tr[valign*=top]');
        $i = 0;
        foreach ($table2 as $value){                //Ищем номер строки шапки таблицы 2
            if($value->plaintext=='SON DURUMU'){
                break;
            }
            $i++;
        }
        $i++;
// Отсеиваем имена колонок, оставляем только значения и записываем их в строку
        for($row=$i; $row<=$i+5; $row++){
            $boldTd = $dom->find('td[id*=ReportCell] table tbody tr td div table tbody tr[valign*=top]', $row)->
            find('td[style*=font-weight:700]');
            $td=$dom->find('td[id*=ReportCell] table tbody tr td div table tbody tr[valign*=top]', $row)->find('td table tbody tr td');
            foreach ($td as $item){
                foreach ($boldTd as $boldItem){
                    if($item->plaintext==$boldItem->plaintext){
                        continue 2;
                    }
                }
                $strTable2.="'" . $item->plaintext . "',";
            }
        }
        $strTable2 = str_replace('&nbsp;','',$strTable2);
        $strTable2 = substr($strTable2, 0, -1);
        //echo $strTable2 . PHP_EOL;
        return $strTable2;
    }

    public function getId($detail){
        $dom = new simple_html_dom($detail);
        $id = $dom->find('td[style*=color:Red]');
        $id = $id[0]->plaintext;
        $id = substr($id,-6);
        return $id;
    }
    
}