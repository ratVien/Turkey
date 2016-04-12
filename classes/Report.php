<?php
/**
 * Created by PhpStorm.
 * User: ratvien
 * Date: 04.04.16
 * Time: 16:51
 */

//require_once (__DIR__ . '/../data/PHPExcel.php');

//require(__DIR__ . '../data/autoload.php');
class Report{
    public $reportPath, $reportTableName, $detailTableName;
    protected $_connection;
    
    function __construct($reportTableName = 'exceltable',
                         $reportPath = '/home/ratvien/report/report.xls'
                         )
    {
        $this->reportPath = $reportPath;
        $this->reportTableName = $reportTableName;
        $db = new DB();
        $this->_connection = $db->getConnection();
    }

    public function ExcelToDb() {
        $PHPExcel_file = PHPExcel_IOFactory::load($this->reportPath);
        $PHPExcel_file->setActiveSheetIndex(0);
        $worksheet = $PHPExcel_file->getActiveSheet();
// Строка для названий столбцов таблицы MySQL
        $columnsNameString = "";

// Количество столбцов на листе Excel
        $columnsCount = PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn());
        for ($column = 1; $column < $columnsCount; $column++) {
            $columnsNameString .=($column==6)?("id". '|'):("column". $column. ','); // Столбец 6 - называем id - остальные по номеру столбца
        }
        $columnsNameString = substr($columnsNameString, 0, -1);
        $columnsQueryNameString = str_replace(",", " TEXT NOT NULL,", $columnsNameString);
        $columnsQueryNameString = str_replace("|", " int(6) NOT NULL,", $columnsQueryNameString);
        //$db = new DB();
        //$connection = $db->getConnection();
        //$this->_connection->exec("DROP TABLE IF EXISTS exceltable");
        //echo $columnsQueryNameString;
        $this->_connection->exec("CREATE TABLE IF NOT EXISTS {$this->reportTableName} (" . $columnsQueryNameString . " TEXT NOT NULL,
                            PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $columnsNameString = str_replace("|", ",", $columnsNameString);
// Количество строк на листе Excel
        $rows_count = $worksheet->getHighestRow();
        $arrColumnsNameString = explode(',', $columnsNameString);
        //print_r($arrColumnsNameString);

// Перебираем строки листа Excel
        for ($row = 6; $row<=($rows_count-1); $row++) {
            // Строка со значениями всех столбцов в строке листа Excel
            $value_str = "";
            $rowUpdate = '';
            // Перебираем столбцы листа Excel
            for ($column = 1; $column < $columnsCount; $column++) {
                // Строка со значением объединенных ячеек листа Excel
                // Ячейка листа Excel
                $cell = $worksheet->getCellByColumnAndRow($column, $row);
                $value_str .= "'" . $cell->getCalculatedValue() . "',";
                $rowUpdate.= "{$arrColumnsNameString[$column-1]} = '{$cell->getCalculatedValue()} ',";
            }

            // Обрезаем строку, убирая запятую в конце
            $value_str = substr($value_str, 0, -1);
            $rowUpdate = substr($rowUpdate, 0, -1);
            // Добавляем строку в таблицу MySQL
            $this->_connection->exec("INSERT INTO exceltable (" . $columnsNameString . ") VALUES (" . $value_str . ")
                                      ON DUPLICATE KEY UPDATE " . $rowUpdate);
        }
    }

    public function detailTableToDb($tableName, $id, $strTable){
        $strTable = "'{$id}', {$strTable}";
        $strTable = htmlspecialchars($strTable);

        //$tableName = 'waybill_info';
        $columnStr = 'id, column1, column2, column3, column4, column5, column6, column7, column8, column9';
        //$connection = DB::getConnection();
        //$this->_connection->exec("DROP TABLE IF EXISTS {$tableName}");
        $arrColumn = array();
        //$arrStrTable = array();
        $arrColumn = explode(',', $columnStr);
        $arrStrTable = explode(',', $strTable);
        //print_r($arrStrTable);
        $rowUpdate = '';
        $i = 0;
        foreach ($arrColumn as $item){
            $rowUpdate.= "{$item} = {$arrStrTable[$i]},";
            $i++;
        }
        $rowUpdate = substr($rowUpdate, 0, -1);
        //echo $rowUpdate. PHP_EOL;
        $this->_connection->exec("INSERT INTO {$tableName} (" . $columnStr . ") VALUES (". $strTable . ")
                                  ON DUPLICATE KEY UPDATE " . $rowUpdate);
        //$this->_connection->exec("REPLACE INTO {$tableName} ({$columnStr}) VALUES ({$strTable}");
        //echo $strTable. PHP_EOL;
        //print_r($this->_connection->errorInfo());
    }
    
    public function detailReportToDb($reportPage, $tableName1 = 'final_status',
                                     $tableName2 = 'waybill_info')
    {
        $this->_connection->exec("CREATE TABLE IF NOT EXISTS {$tableName1} (
                            `id` int(6) NOT NULL,
                            `column1` text NOT NULL,
                            `column2` text NOT NULL,
                            `column3` text NOT NULL,
                            `column4` text NOT NULL,
                            `column5` text NOT NULL,
                            `column6` text NOT NULL,
                            `column7` text NOT NULL,
                            `column8` text NOT NULL,
                            `column9` text NOT NULL,
                            PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->_connection->exec("CREATE TABLE IF NOT EXISTS {$tableName2} (
                            `id` int(6) NOT NULL,
                            `column1` text NOT NULL,
                            `column2` text NOT NULL,
                            `column3` text NOT NULL,
                            `column4` text NOT NULL,
                            `column5` text NOT NULL,
                            `column6` text NOT NULL,
                            `column7` text NOT NULL,
                            `column8` text NOT NULL,
                            `column9` text NOT NULL,
                            PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $detail = new Detail($reportPage);
        $countReportRows = $detail->reportRows;
        for($i=0; $i<$countReportRows; $i++){
            $detailPage = $detail->getDetailPage($i);
            $strTable1 = $detail->getDetailTableString1($detailPage);
            $strTable2 = $detail->getDetailTableString2($detailPage);
            $id = $detail->getId($detailPage);
            $this->detailTableToDb($tableName1, $id, $strTable1);
            $this->detailTableToDb($tableName2, $id, $strTable2);
        }
    }

    public function getReport() {
        $result = array();
        $q = $this->_connection->query("SELECT * FROM {$this->reportTableName}", PDO::FETCH_ASSOC);
        while( $r = $q->fetch() ) {
            $result[$r['code']] = new City($r['code'], $r['name'],new Coordinate($r['lat'], $r['long']));
        }
        return $result;
    }
}