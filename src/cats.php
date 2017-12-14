<?php 

// CONNECTING TO MYSQL
$mysql_host = "127.0.0.1:3306";
$mysql_user = "jangofett";
$mysql_password = "";
$mysql_db = "phpmonkey";

$conn = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_db);

$catID = 1;
$catName = "pixel";
$catAge = 2;
$owner = "OWNER";

$name = htmlspecialchars($_POST['name']);
$age = htmlspecialchars($_POST['age']);
$owner = htmlspecialchars($_POST['owner']);

$insert = "INSERT INTO cats (catID, catName, catAge, owner) VALUES ('$catID', '$name', '$age', '$owner')";

if ($conn->connect_errno) {
		echo "Номер_ошибки: " . $conn->connect_errno . "</br>";
		echo "Ошибка: " . $conn->connect_error . "\n";
		exit;
}			
$showcats = "SELECT * FROM cats";
echo "hello Haver </br>";
if ($conn->query($insert) === TRUE) {
		echo "query succesful </br>";
} else {
		echo "error" . $conn->error;
}


$result = $conn->query($showcats);

if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
				echo "ID " . $row ["catID"] . " Name " . $row["catName"] . " Owner " . $row["owner"] . " Age " . $row["catAge"] . "</br>";
		}
}
else {
		echo "no result";
}

$conn->close();

?>
