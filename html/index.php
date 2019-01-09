<?php

// mysqli_connect 參數：ip:port、賬號、密碼、數據庫
$link = mysqli_connect("10.10.0.1:3306", "devuser", "devpass", "test_db");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL . "</br>";
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL. "</br>";
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL. "</br>";
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL. "</br>";
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

mysqli_close($link);

$cassandraConfig = [
	'host' => '10.10.10.2',
	'port' => 9042
];

// Login Error
$result["success"] = false;
$result["message"] = "登入失敗";
$typeText = '公司人員';
$username = 'Leon';
$ip = '8.8.8.8';

$statment = "INSERT INTO logs.backend_logs (id,type,account,content,created_at,ip) VALUES 
            (".uuidv4()." , 1 , '?' , ' $typeText 帳號 $username 嘗試登入失敗!' , ".time().", '$ip' )";
//echo $statment;
cql_insert($statment);

function cql_insert($query) {
    global $cassandraConfig;
    $cluster = Cassandra::cluster()
            ->withContactPoints($cassandraConfig['host'])
            ->withPort($cassandraConfig['port'])
            ->build();
            
    $session = $cluster->connect("logs");

    $stat  = new Cassandra\SimpleStatement($query);

    $future    = $session->executeAsync($stat);  // fully asynchronous and easy parallel execution
    //$result    = $future->get();
    //return $result;
}

function cql_query($query , $keyspace = null) {
    global $cassandraConfig;
    $cluster = Cassandra::cluster()
            ->withContactPoints($cassandraConfig['host'])
            ->withPort($cassandraConfig['port'])
            ->build();
    if(is_null($keyspace)) {
        $keyspace = 'logs';
    }

    $session = $cluster->connect($keyspace);

    $stat  = new Cassandra\SimpleStatement($query);

    $future    = $session->executeAsync($stat);  // fully asynchronous and easy parallel execution
    $result    = $future->get();
    return $result;
}
