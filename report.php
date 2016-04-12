<?php
/**
 * Created by PhpStorm.
 * User: ratvien
 * Date: 12.04.16
 * Time: 11:52
 */

ini_set("display_errors",1);
error_reporting(E_ALL);
require(__DIR__ . '/data/autoload.php');


require_once "./../PHPExcel/Classes/PHPExcel.php";
//require_once (__DIR__ . '/data/simple_html_dom.php');
// Открываем файл

//$parse = new ParseFile;
//$parse->Auth();
//$reportPage = $parse->getReportPage();
//echo $parse->getDetail2($reportPage);

$parse = new ParseFile();
$reportPage = $parse->getReportPage();
//file_put_contents('2.html', $reportPage);
//$detailPage = file_get_contents('1.html');
//$reportPage = file_get_contents('2.html');

$parse->getExcelFile($parse->getExcelLink($reportPage));
$report = new Report();
$report->ExcelToDb();
$report->detailReportToDb($reportPage);