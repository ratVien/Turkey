<?php
/**
 * Created by PhpStorm.
 * User: ratvien
 * Date: 12.04.16
 * Time: 13:47
 */
ini_set("display_errors",1);
error_reporting(E_ALL);
require(__DIR__ . '/data/autoload.php');
$id = $_GET['id'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
<div class="container">
    <h2>Detail №<?=$id?></h2>
    <h3>Final status</h3>
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>id</th>
            <th>Column2</th>
            <th>Column3</th>
            <th>Column4</th>
            <th>Column5</th>
            <th>Column6</th>
            <th>Column7</th>
            <th>Column8</th>
            <th>Column9</th>
            <th>Column10</th>
        </tr>
        <?php
        $detailTableName1 = 'final_status';
        $detailTableName2 = 'waybill_info';
        $db = new DB();
        $pdo = $db->getConnection();
        $q = $pdo->query("SELECT * FROM {$detailTableName1}  WHERE id={$id}", PDO::FETCH_ASSOC);
         $r = $q->fetch();
            echo '<tr>';
            foreach ($r as $item){
                echo "<td>{$item}</td>";
            }
            echo '</tr>';
        ?>

        </thead>
        </table>

        <h3>Waybill information</h3>
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>id</th>
            <th>Column2</th>
            <th>Column3</th>
            <th>Column4</th>
            <th>Column5</th>
            <th>Column6</th>
            <th>Column7</th>
            <th>Column8</th>
            <th>Column9</th>
            <th>Column10</th>
        </tr>
        </thead>
        <?php
        $q = $pdo->query("SELECT * FROM {$detailTableName2}  WHERE id={$id}", PDO::FETCH_ASSOC);
        $r = $q->fetch();
        echo '<tr>';
        foreach ($r as $item){
            echo "<td>{$item}</td>";
        }
        echo '</tr>';
        ?>
    </table>
    <!--<table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>id</th>
            <th>Column2</th>
            <th>Column3</th>
            <th>Column4</th>
            <th>Column5</th>
            <th>Column6</th>
            <th>Column7</th>
            <th>Column8</th>
            <th>Column9</th>
            <th>Column10</th>
            <th>Column11</th>
            <th>Column12</th>
            <th>Column13</th>
            <th>Column14</th>
            <th>Column15</th>
            <th>Column16</th>
            <th>Column17</th>
            <th>Column18</th>
            <th>Column19</th>
        </tr>
        </thead>
        <?php/*
        $q = $pdo->query("SELECT DISTINCT * FROM final_status LEFT JOIN waybill_info
                      ON final_status.id=waybill_info.id WHERE final_status.id = {$id}");
        $r = $q->fetch();
        //var_dump($r);
        echo '<tr>';
            foreach ($r as $item){
            echo "<td>{$item}</td>";
            }
            echo '</tr>';
*/
        ?>-->

</div>
</body>
</html>
