<?php 

// CONNECTING TO MYSQL
$mysql_host = "172.17.0.2";
$mysql_user = "root";
$mysql_password = "f416ss";
$mysql_db = "asterisk";

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_db);

$name = htmlspecialchars($_POST['name']);

#$insert = "INSERT INTO cats (catID, catName, catAge, owner) VALUES ('$catID', '$name', '$age', '$owner')";

if ($conn->connect_errno) {
		echo "Error number: " . $conn->connect_errno . "</br>";
		echo "Error description: " . $conn->connect_error . "\n";
		exit;
}

echo "<a href='add_ext.html'>Add extension</a> </br> ";

$showext = "SELECT * FROM sip";
echo "<table border=1 style=width:100%>";
	echo "<tr>";
		echo "<th>id</th>";
		echo "<th>keyword</th>";
		echo "<th>data</th>";
		echo "<th>flags</th>";
	echo "</tr>"; 
$result = $conn->query($showext);
if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
					echo "<tr>";
						echo "<td> {$row["id"]}</td>";
						echo "<td> {$row["keyword"]}</td>";
						echo "<td> {$row["data"]}</td>";
						echo "<td> {$row["flags"]}</td>";
					echo "</tr>";
		}
}
echo "</table>";

#if ($conn->query($insert) === TRUE) {
#		echo "query succesful </br>";
#} else {
#		echo "error" . $conn->error;
#}
#
#$result = $conn->query($showcats);
#
#if ($result->num_rows > 0) {
#		while ($row = $result->fetch_assoc()) {
#				echo "ID " . $row ["catID"] . " Name " . $row["catName"] . " Owner " . $row["owner"] . " Age " . $row["catAge"] . "</br>";
#		}
#}
#else {
#		echo "no result";
#}

$conn->close();

?>
