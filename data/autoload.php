<?php
/**
 * Created by PhpStorm.
 * User: ratvien
 * Date: 05.04.16
 * Time: 12:47
 */


/**
* $class_name - имя подключаемого класса
*/

function __autoload($class_name)
{
    $arPath = array(
        __DIR__ . "/../classes",
        __DIR__ . "/../classes/PHPExcel",
    );
    foreach ($arPath as $path) {
        $class_file = "{$path}/{$class_name}.php";
        if (file_exists($class_file)) {
            require($class_file);
            break;
        }
    }
}
    