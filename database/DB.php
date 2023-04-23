<?php

namespace Database;

use PDO;
use PDOStatement;

class DB
{
    private static ?self $pdoObj = null;
    private static ?PDO $connect = null;
    public static int $number = 0;

    private const  BD_DRIVER = "pgsql";
    private const  BD_HOST = "localhost";
    private const  BD_NAME = "pugs";
    private const  BD_USERNAME = "postgres";
    private const  BD_PASS = "root";

    public static function connect():PDO
    {
        if (!self::$pdoObj){
            self::$number++;
            $dns = self::BD_DRIVER . ':host=' . self::BD_HOST . ';dbname=' . self::BD_NAME;
            self::$pdoObj = new self();
            self::$connect = new PDO(
                $dns, self::BD_USERNAME, self::BD_PASS
            );
            self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$connect;
    }

    public static function select($query, array $params = null):PDOStatement
    {
        $connect = self::connect();
        $prepare = $connect->prepare($query);
        $prepare->execute($params);
        return $prepare;
    }

    public static function insert($query, array $params):int
    {
        $connect = self::connect();
        $prepare = $connect->prepare($query);
        $prepare->execute($params);
        return $connect->lastInsertId();
    }

    public static function update($query, array $params):array
    {
        $connect = self::connect();
        $prepare = $connect->prepare($query);
        $prepare->execute($params);
        return $prepare->fetchAll();
    }

    public static function delete($query, array $params):PDOStatement
    {
        $connect = self::connect();
        $prepare = $connect->prepare($query);
        $prepare->execute($params);
        return $prepare;
    }
}