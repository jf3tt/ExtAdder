<?php 

$hostname = htmlspecialchars($_POST['hostname']);
$ip = htmlspecialchars($_POST['ip']);
$ssh_user = htmlspecialchars($_POST['ssh_user']);
$ssh_password = htmlspecialchars($_POST['ssh_password']);

$text = htmlspecialchars($_POST['text']);




//EXAMPLE
// expect -c 'spawn ssh root@172.17.0.4 "echo succesfull > /testssh"; expect "assword:"; send "f416ss\r"; interact'

//$comma = ("expect -c"  . " 'spawn ssh " . $ssh_user . "@" . $ip . ' "echo succesfull > /testssh";' . 
//				' expect "assword:"; send "' . $ssh_password . '\r"' .
//				"; interact'");

#$command = ("expect -c"  . " 'spawn ssh " . $ssh_user . "@" . $ip . ' "' . $text  . '"' . ';'  .  
#				' expect "assword:"; send "' . $ssh_password . '\r"' .
#				"; interact'");
#echo $command;
#exec($command);

// CONNECTING TO MYSQL
$mysql_host = "172.17.0.2";
$mysql_user = "root";
$mysql_password = "f416ss";
$mysql_db = "asterisk";

$CreateTable = ("CREATE TABLE Hosts (HostName varchar(50), HostIP varchar(15), HostPort int(5), HostUser varchar(100), HostPass varchar(200) )");

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$insert = "INSERT INTO Hosts (HostName, HostIP, HostPort, HostUser, HostPass) VALUES ('$hostname','$ip', 22, '$ssh_user', '$ssh_password')"; 

if ($conn->query($insert)=== TRUE) {
				echo "New record added </br>";
				echo "<a href=\"/\">Add extension</a>";
} else {
				echo "Error: " . $insert . "<br>" . $conn->error;
}

?>
