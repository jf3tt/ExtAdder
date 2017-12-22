<?php 

echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css\" integrity=\"sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb\" crossorigin=\"anonymous\">";

$mysql_host = "172.17.0.2";
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
$endpoint = "\[" . $extension . "]\\\\" . "\\\\n" .
	"type=endpoint\\\\" . "\\\\n" .
	"aors=" . $extension . "\\\\" . "\\\\n" .
	"auth=" . $extension . "-auth\\\\" . "\\\\n" .
	"allow=ulaw,alaw,gsm,g726\\\\" . "\\\\n" .
	"context=from-internal\\\\" . "\\\\n" .
	"callerid=" . $extension . "\\\\" . "\\\\n" .
	"dtmf_mode=rfc4733\\\\" . "\\\\n" .
	"aggregate_mwi=yes\\\\" . "\\\\n" .
	"use_avpf=no\\\\" . "\\\\n" .
	"ice_support=no\\\\" . "\\\\n" .
	"media_use_received_transport=no\\\\" . "\\\\n" .
	"trust_id_inbound=yes\\\\" . "\\\\n" .
	"media_encryption=no\\\\" . "\\\\n" .
	"media_encryption_optimistic=no\\\\" . "\\\\n" .
	"rtp_symmetric=yes\\\\" . "\\\\n" .
	"rewrite_contact=yes\\\\" . "\\\\n" .
	"force_rport=yes\\\\" . "\\\\n";
$endpoint_path = "/etc/asterisk/pjsip.endpoint_custom.conf";

// adding variables to pjsip.aor.conf
$aor = "\[" . $extension . "]\\\\" . "\\\\n" .
	"type=aor\\\\" . "\\\\n" .
	"max_contacts=1\\\\" . "\\\\n" .
	"remove_existing=yes\\\\" . "\\\\n" .
	"maximum_expiration=7200\\\\" . "\\\\n" .
	"minimum_expiration=60\\\\" . "\\\\n" .
	"qualify_frequency=60\\\\" . "\\\\n";
$aor_path =	"/etc/asterisk/pjsip.aor_custom.conf";

// adding variables to pjsip.auth.conf
$auth = "\[" . $extension . "-auth] \\\\" . "\\\\n" .
	"type=auth \\\\" . "\\\\n" . 
	"auth_type=userpass \\\\" . "\\\\n" . 
	"password=" . $password . "\\\\" . "\\\\n" . 
	"username=" . $extension . "\\\\" . "\\\\n" ;
$auth_path = ("/etc/asterisk/pjsip.auth_custom.conf");

// Adding variables to pjsip.identify.conf
$identify = '\[' .  $extension . '-identify\] \\\\'  . "\\\\n" .  
	'type=identify \\\\' . "\\\\n" .  
	'endpoint=' . $extension . "\\\\" . "\\\\n";
$identify_path =	("/etc/asterisk/pjsip.identify_custom.conf");

function gen_file($cmd, $file_path) {
				$add = ("echo -e " . $cmd .  " >> "	. $file_path . "");
				return $add;
}

//SQL Query set for sip table;

// SETTINGS FOR SIP TABLE
#		$settings = array(
#				$sipdriver = "\'sipdriver\'",
#				$secret = "\'secret\'",
#				$dtmfmode = "\'dtmfmode\'",
#				$trustpid = "\'trustpid\'",
#				$sendpid = "\'sendpid\'",
#				$qualityfreq = "\'qualityfreq\'",
#				$transport = "\'transport\'",
#				$avpf = "\'avpf\'",
#				$icesupport = "\'icesupport\'",
#				$namedcallgroup = "\'namedcallgroup\'",
#				$namedpickupgroup = "\'namedpickupgroup\'",
#				$disallow = "\'disallow\'",
#				$allow = "\'allow\'",
#				$accountcode = "\'accountcode\'",
#				$mailbox = "\'mailbox\'",
#				$max_contact = "\'maxcontact\'",
#				$media_use_received_transport = "\'media_use_received_transport\'",
#				$rtp_symmetric = "\'rtp_symmetric\'",
#				$force_rport = "\'force_rport\'",
#				$rewrite_contact = "\'rewrite_contact\'",
#				$mwi_subscription = "\'mwi_subscription\'",
#				$mediaencryption = "\'mediaencryption\'",
#				$mediaencryptionoptimistic = "\'mediaencryptionoptimistic\'",
#				$dial_sip = "\'dial\'",
#				$context = "\'context\'",
#				$account = "\'account\'",
#				$callerid = "\'callerid\'"
#		);

$settings = array(
				"\'INSERT INTO sip (id,keyword,data,flags) VALUES ('$extension', '\'dial\', '\'PJSIP/$extension\', '15');",
				"\'INSERT INTO sip (id,keyword,data,flags) VALUES ('$extension', '\'secret\', '\'$password\', '2');",
);

#foreach ($settings as $elem){
#				#echo "SETTING: " . $elem . "</br>";
#				$multiline .= $elem;
#				echo $multiline;
#};

function db_insert($extension, $name, $settings) {

// SETTINGS FOR DEVICES TABLE
		$tech = "\'pjsip\'";
		$dial = "\'PJSIP";
		$devicetype = "\'fixed\'";
		$emergency_cid = "\'NONE\'";


		function get_settings($array) {
				foreach ($array as $line) {
						#$line = " INSERT INTO sip (id,keyword,data,flags) VALUES ('$ext', '$line', '$line', '$line')\;";
						echo "line in array: " . $line . "</br>";
						$multiline .= $line;
						echo $multiline;
				};
			 	//return $multiline;
		};

		$add_devices = "use asterisk\; INSERT INTO devices (id,tech,dial,devicetype,user,description,emergency_cid) VALUES ('$extension', '$tech', '$dial''/''$extension\'', '$devicetype', '$extension', '$name', '$emergency_cid')";
				
		$query_exec = "mysql -h 127.0.0.1 -u root -e \\\"" . $add_devices . "\;" . get_settings($settings) . "\\\"" ;
		echo "</br><b>query exec: </b>" . $query_exec;
		return $query_exec;
}
// TODO 
// extensions_additional.conf

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
echo "===============================Selected host==========================</br>";
echo "Selected IP: " . $hostip . "</br>";
echo "===================================END================================</br>";

$command = ("expect -c"  . " 'spawn ssh " . $ssh_user . "@" . $hostip . ' ' . 
				gen_file($identify, $identify_path)  . " && " .
				gen_file($auth, $auth_path) . " && " . 
				gen_file($aor, $aor_path) . " && " . 
				gen_file($endpoint, $endpoint_path)  . " && " . 
				db_insert($extension, $name, $settings) . "" . ";" . 
				' expect "assword:"; send "' . $ssh_password . '\r"' . "; interact'");
echo "</br><b>CMD</b> " . $command;
exec($command, $output);
echo "<h5>==========================OUTPUT=========================</h5></br>";
foreach($output as $line) {
				echo "<i><h6>--: " . "$line" . "</i></h6>";
}
echo "</br>";
?>
