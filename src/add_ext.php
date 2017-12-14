<?php 

// get data from html form 

$extension = htmlspecialchars($_POST['extension']);
$name = htmlspecialchars($_POST['name']);
$cid = htmlspecialchars($_POST['cid']);
$password = htmlspecialchars($_POST['password']);
echo $extension . "</br>";
echo $name . "</br>";
echo $cid . "</br>";
echo $password . "</br>";

// adding variables to pjsip.endpoint.conf
exec("echo -n [" . $extension . "]'\n'" .
	"type=endpoint'\n'" .
	"aors=" . $extension . "'\n'" .
	"auth=" . $extension . "-auth'\n'" . 
	"allow=ulaw,alaw,gsm,g726'\n'" .
	"context=from-internal'\n'" .
	"callerid=" . $extension . "'\n'" .
	"dtmf_mode=rfc4733'\n'" .
	"aggregate_mwi=yes'\n'"	.
	"use_avpf=no'\n'" .
	"ice_support=no'\n'" .
	"media_use_received_transport=no'\n'" .
	"trust_id_inbound=yes'\n'" .
	"media_encryption=no'\n'" .
	"media_encryption_optimistic=no'\n'" .
	"rtp_symmetric=yes'\n'" .
	"rewrite_contact=yes'\n'" . 
	"force_rport=yes'\n'" .
	"'\n'" .
	" >> /home/jangofett/Coding/phpmonkey/asterisk/src/config/pjsip.endpoint.conf");

// adding variables to pjsip.aor.conf
exec("echo -n [" . $extension . "]'\n'" .
	"type=aor'\n'" . 
	"max_contacts=1'\n'" .
	"remove_existing=yes'\n'" . 
	"maximum_expiration=7200'\n'" .
	"minimum_expiration=60'\n'" . 
	"qualify_frequency=60'\n'" .
	"'\n'" .
	" >> /home/jangofett/Coding/phpmonkey/asterisk/src/config/pjsip.aor.conf");

// adding variables to pjsip.auth.conf
exec("echo -n [" . $extension . "]'\n'" .
	"type=auth'\n'" .
	"auth_type=userpass'\n'" . 
	"password=" . $password . "'\n'" . 
	"username=" . $extension . "'\n'" .
	"'\n'" .
	" >> /home/jangofett/Coding/phpmonkey/asterisk/src/config/pjsip.auth.conf");

// adding variables to pjsip.identify.conf
exec("echo -n [" . $extension . "-identify]'\n'" .
	"type=identify'\n'" .
	"endpoint=" . $extension . "'\n'" .
	"'\n'" .
	" >> /home/jangofett/Coding/phpmonkey/asterisk/src/config/pjsip.identify.conf");

// TODO 
// extensions_additional.conf



// Add configs to freepbx host
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
