<?php
ini_set("display_errors",1);
error_reporting(E_ALL);
require(__DIR__ . '/data/autoload.php');

?>
<!doctype html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <meta charset="UTF-8">
    <title>Turkey report page</title>
    <style>
        .table{
            font-size:10px;
        }
    </style>
</head>
<body>

    <h2>Turkey report</h2>
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Detail page link</th>
                <th>Column1</th>
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
                <th>Column20</th>
                <th>Column21</th>
                <th>Column22</th>
                <th>Column23</th>
                <th>Column24</th>
                <th>Column25</th>
                <th>Column26</th>
                <th>Column27</th>
                <th>Column28</th>
                <th>Column29</th>
                <th>Column30</th>
                <th>Column31</th>
                <th>Column32</th>
                <th>Column33</th>
                <th>Column34</th>
                <th>Column35</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $reportTableName = 'exceltable';
            $db = new DB();
            $pdo = $db->getConnection();
            //$result = array();
            $i=0;
            $q = $pdo->query("SELECT * FROM {$reportTableName}", PDO::FETCH_ASSOC);
            while( $r = $q->fetch() ) {
                $i++;
                echo '<tr>';
                echo "<td>{$i}</td>";
                echo "<td><a href='ditailPage.php?id={$r['id']}' >Detail</a></td>";
                foreach ($r as $item){
                    echo "<td>{$item}</td>";
                }
                echo '</tr>';
                }?>
            </tbody>
        </table>
    <?php
    /*
    $reportTableName = 'exceltable';
    $db = new DB();
    $pdo = $db->getConnection();
    //$result = array();
    $i=0;
    $q = $pdo->query("SELECT * FROM {$reportTableName}", PDO::FETCH_ASSOC);
    while( $r = $q->fetch() ) {
        $i++;
            foreach ($r as $item){
                echo $item . '<br>';
            }
    }
    */
?>
</body>
</html>
