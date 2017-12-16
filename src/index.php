<?php

$mysql_host = "172.17.0.3";
$mysql_user = "root";
$mysql_password = "f416ss";
$mysql_db = "asterisk";

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_db);
$query = "SELECT HostName from Hosts;";
$host_elem = mysqli_query($conn, $query);


?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
      <title>php monkey</title>
   </head>
   <body>
			<h4>Add extension</h4>		
			<form action="add_ext.php" method="post">
 				<p>User extension: <input type="text" name="extension" /></p>
 				<p>Display Name: <input type="text" name="name" /></p>
 				<p>Outbound CID: <input type="text" name="cid" /></p>
 				<p>Secret: <input type="password" name="password" /></p>
				<select name="hostmenu">
					<?php
						while ($row = $host_elem->fetch_assoc()){
							echo "<option>" . $row['HostName'] . "</option>";
						}
					?>
				</select>
 				<p><input type="submit" /></p>
			</form>
   </body>
</html>
