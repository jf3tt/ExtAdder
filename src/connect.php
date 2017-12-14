<?php 

$ip = htmlspecialchars($_POST['ip']);
$ssh_user = htmlspecialchars($_POST['ssh_user']);
$ssh_password = htmlspecialchars($_POST['ssh_password']);
$text = htmlspecialchars($_POST['text']);
//EXAMPLE
// expect -c 'spawn ssh root@172.17.0.4 "echo succesfull > /testssh"; expect "assword:"; send "f416ss\r"; interact'


//$comma = ("expect -c"  . " 'spawn ssh " . $ssh_user . "@" . $ip . ' "echo succesfull > /testssh";' . 
//				' expect "assword:"; send "' . $ssh_password . '\r"' .
//				"; interact'");

$command = ("expect -c"  . " 'spawn ssh " . $ssh_user . "@" . $ip . ' "' . $text  . '"' . ';'  .  
				' expect "assword:"; send "' . $ssh_password . '\r"' .
				"; interact'");
echo $command;
exec($command);
#exec($test);

?>
