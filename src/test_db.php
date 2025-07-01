<?php

try {
    $host = 'freemarket-mysql';
    $dbname = 'laravel_db';
    $user = 'root';
    $pass = 'root';
    
    // PDO接続のテスト
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "PDO接続成功\n";
    
    // MySQLiのテスト
    $mysqli = new mysqli($host, $user, $pass, $dbname);
    if ($mysqli->connect_error) {
        throw new Exception("MySQLi接続エラー: " . $mysqli->connect_error);
    }
    echo "MySQLi接続成功\n";
    
    // PDOドライバーの情報
    echo "\nPDOドライバー一覧:\n";
    print_r(PDO::getAvailableDrivers());
    
    // PHP拡張モジュールの情報
    echo "\nPHP拡張モジュール一覧:\n";
    print_r(get_loaded_extensions());
    
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . "\n";
    echo "エラートレース:\n" . $e->getTraceAsString() . "\n";
} 