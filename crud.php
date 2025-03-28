<?php
// ------------------------Connection start-----------------------------------
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "cruddb";

$conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

if($conn->connect_error){
	die ("Connection Failed: ".$conn->connect_error);
}
else{
	echo "database connected successfully <br>";
}

?>

<?php
// Include session file
session_start();
$name = $email = $address = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){
	$name = $_POST["name"];
	$email = $_POST["email"];
	$address = $_POST['address'];

	$stmt = $conn->prepare("INSERT INTO users (name,email,address) VALUES (?,?,?)");
	$stmt->bind_param("sss",$name,$email,$address);
	$stmt->execute();
	$stmt->close();
	header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<?php
$stmt=$conn->prepare("SELECT * FROM users");
$stmt->execute();
$result=$stmt->get_result();
$arr = array();
while($row=$result->fetch_assoc()){
	$arr[] = $row;
}
$stmt->close();
?>

<?php
if(isset($_GET['id'])){
	$id = $_GET['id'];
	$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
	$stmt->bind_param("i",$id);
	$stmt->execute();
	$stmt->close();
	header("Location: " . $_SERVER['PHP_SELF']);
    exit();
	
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		h1{
			text-align: center;
			color: #333;
			font-family: Arial, sans-serif;
		}
		form{
			margin: 20px;
			padding: 20px;
			border: 1px solid #ddd;
			border-radius: 5px;
			background-color: #f9f9f9;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			display: flex;
			align-items: center;
			justify-content: center;
			color:black;
		}
		th, td {
			padding: 8px 12px;
			border: 1px solid #ddd;
			text-align: left;
			width:100px;
		}
		th {
			background-color: #f2f2f2;
		}
		tr:hover {
			background-color: #f5f5f5;
		}
	</style>
</head>
<body>
	<h1><?php echo 'Todo-List'?></h1>
	<form action=<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?> method="POST">
		<label for="name">NAME:<input type="text" name="name"></label><br>
		<label for="email">EMAIL:<input type="email" name="email"></label><br>
		<label for="address">ADDRESS:<input type="text" name="address"></label><br>
		<button type=submit>Submit</button>
	</form>
	<br>
	<hr>
	<br>
	<table>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Address</th>
			<th>Action</th>
		</tr>
		<?php 
		if(count($arr)>0){
			foreach($arr as $row){
			echo '
			<tr>
				<td>'.htmlspecialchars($row['name']).'</td>
				<td>'.htmlspecialchars($row['email']).'</td>
				<td>'.htmlspecialchars($row['address']).'</td>
				<td>
				   <a href="?id='. $row['id'] .'" onclick="return confirm(\'Are you sure you want to delete this record?\');">Delete</a></td>
			</tr>';
			}
			
		}
		else{
			echo '<tr><td colspan="4">No records found</td></tr>';
		}
		?>

		<?php
		session_reset();
		?>
	</table>
</body>
</html>
