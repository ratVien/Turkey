<?php
/**
 * Created by PhpStorm.
 * User: ratvien
 * Date: 04.04.16
 * Time: 10:33
 */

class DB {
    public function __construct($host = 'localhost', 
                         $dbName = 'turkey', 
                         $dbUser = 'root', 
                         $dbPass = '13Ct510$')
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
    }

    /**
     * @return PDO
     */
    public function getConnection() {
        return new PDO("mysql:host={$this->host};dbname={$this->dbName}", $this->dbUser, $this->dbPass);
    }
}