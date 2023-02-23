<!DOCTYPE html>
<html>
<head>
	<title>Socket Test</title>
</head>
<body>

<?php
$host = '192.168.24.10';
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "Unable to create socket: " . socket_strerror(socket_last_error()) . PHP_EOL;
    exit(1);
}

if (socket_bind($socket, $host, $port) === false) {
    echo "Unable to bind socket to $host:$port: " . socket_strerror(socket_last_error($socket)) . PHP_EOL;
    exit(1);
}

if (socket_listen($socket, 5) === false) {
    echo "Unable to listen on socket: " . socket_strerror(socket_last_error($socket)) . PHP_EOL;
    exit(1);
}

echo "<p>Listening on $host:$port...</p>";

while(true) {
    $client_socket = socket_accept($socket);
    if ($client_socket === false) {
        echo "<p>Unable to accept client connection: " . socket_strerror(socket_last_error($socket)) . "</p>";
        continue;
    }

    // ارسال پیام "Connected" به مرورگر
    echo "<p>Connected</p>";
    ob_flush();
    flush();

    // دریافت پیام از کلاینت و نمایش آن
    while(true) {
        $input = socket_read($client_socket, 100000000);
        if ($input === false) {
            echo "<p>Unable to read input from client: " . socket_strerror(socket_last_error($client_socket)) . "</p>";
            break;
        }

        // اگر هیچ داده‌ای دریافت نشده بود، پیام خالی نشان داده نمی شود
        if(trim($input) == '') continue;

        // نمایش پیام دریافت شده
        echo "<p>Received message: $input</p>";
        ob_flush();
        flush();
    }

    // قطع ارتباط با کلاینت
    socket_close($client_socket);
}

socket_close($socket);
?>

</body>
</html>
