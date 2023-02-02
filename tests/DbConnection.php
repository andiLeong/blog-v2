<?php

namespace Tests;

use Illuminate\Database\MySqlConnection;

trait DbConnection
{
    public static $connection;

    protected function buildConnection()
    {
        if (self::$connection instanceof MySqlConnection) {
            return;
        }

        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $dbname = config('database.connections.mysql.database');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $dsn = compact('user', 'password', 'dbname', 'host', 'port');
        $dsn = http_build_query($dsn, '', ';');
        $pdo = new \PDO("mysql:$dsn");
        self::$connection = new MySqlConnection($pdo);
    }
}
