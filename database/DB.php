<?php

namespace Database;

use PDO;
use PDOStatement;
use App\Http\Services\Env;

class DB
{
    private static ?self $pdoObj = null;
    private static ?PDO $connect = null;
    public static int $number = 0;
    private static array $config = [];

    public static function connect():PDO
    {
        if (!self::$pdoObj){
            self::$config = Env::parse_env();
            self::$number++;
            $dns = self::$config['BD_DRIVER'] . ':host=' . self::$config['BD_HOST'] . ';dbname=' . self::$config['BD_NAME'];
            self::$pdoObj = new self();
            self::$connect = new PDO(
                $dns, self::$config['BD_USERNAME'], self::$config['BD_PASS']
            );
            self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$connect;
    }

    public static function select($query, array $params = null):PDOStatement
    {
        $connect = self::connect();
        $connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $prepare = $connect->prepare($query);
        $prepare->execute($params);
        return $prepare;
    }

    public static function insert($query, array $params):int
    {
        $connect = self::connect();
        $connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $prepare = $connect->prepare($query);
        $prepare->execute($params);
        return $connect->lastInsertId();
    }

    public static function update($query, array $params):array
    {
        $connect = self::connect();
        $connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $prepare = $connect->prepare($query);
        $prepare->execute($params);
        return $prepare->fetchAll();
    }

    public static function delete($query, array $params):PDOStatement
    {
        $connect = self::connect();
        $connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $prepare = $connect->prepare($query);
        $prepare->execute($params);
        return $prepare;
    }
}