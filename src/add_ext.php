<?php 

$mysql_host = "172.17.0.3";
$mysql_user = "root";
$mysql_password = "f416ss";
$mysql_db = "asterisk";

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_db);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
				}
// get data from html form 

$extension = htmlspecialchars($_POST['extension']);
$name = htmlspecialchars($_POST['name']);
$cid = htmlspecialchars($_POST['cid']);
$password = htmlspecialchars($_POST['password']);

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
	" >> /etc/asterisk/pjsip.endpoint.conf");

// adding variables to pjsip.aor.conf
exec("echo -n [" . $extension . "]'\n'" .
	"type=aor'\n'" . 
	"max_contacts=1'\n'" .
	"remove_existing=yes'\n'" . 
	"maximum_expiration=7200'\n'" .
	"minimum_expiration=60'\n'" . 
	"qualify_frequency=60'\n'" .
	"'\n'" .
	" >> /etc/asterisk/pjsip.aor.conf");

// adding variables to pjsip.auth.conf
exec("echo -n [" . $extension . "]'\n'" .
	"type=auth'\n'" .
	"auth_type=userpass'\n'" . 
	"password=" . $password . "'\n'" . 
	"username=" . $extension . "'\n'" .
	"'\n'" .
	" >> /etc/asterisk/pjsip.auth.conf");

// Adding variables to pjsip.identify.conf
$identify = '\[' .  $extension . '-identify\]!' .
	'type=identify!' . "" .
	'endpoint=' . $extension . '!';
$identify_path =	("/etc/asterisk/pjsip.identify.conf");

echo "identify: " . $identify . "</br>";

function gen_file($cmd, $file_path) {
		echo "cmd: " . $cmd . "</br>";
		$sed = ("sed -i 's/!/Z/g' ");
		return "echo " . "$cmd" .  " >> " . "$file_path" . " && " . "$sed" . " $file_path";
}

// TODO 
// extensions_additional.conf
// Add configs to freepbx host

$host = htmlspecialchars($_POST['hostmenu']);

$ipquery = "SELECT * FROM Hosts WHERE HostName='$host';";
$result = $conn->query($ipquery);
if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$hostip = "{$row["HostIP"]}";
			$ssh_user = "{$row["HostUser"]}";
			$ssh_password = "{$row["HostPass"]}";
		} 
} else {
		echo "Not found";
}
echo "Selected IP: " . $hostip . "</br>";

$command = ("expect -c"  . " 'spawn ssh " . $ssh_user . "@" . $hostip . ' "' . gen_file($identify, $identify_path)  . " && " . "sleep 0.5" . '"' . ';'  .  
				' expect "assword:"; send "' . $ssh_password . '\r"' . "; interact'");

exec($command);
echo $command;
?>
