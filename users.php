```php
<!DOCTYPE html>
<html style = "font-family:Segoe UI">
<head>
<title>127.0.0.1 | Add into table `users`</title>
</head>
<body>
<?php
$conn = mysql_connect("localhost:3306", "root");
if(!$conn){die("<strong>Connection error: </strong>".mysql_error());} #нет
echo "<em><p>Connected successfully to \"127.0.0.1:3306\"</p></em>";
mysql_select_db("dhcp");
$sql = "SELECT * FROM `server_logs` WHERE eventDesc NOT LIKE \"Database Cleanup Begin\" ";
$sql .= "AND eventDesc NOT LIKE \"Expired\" AND eventDesc NOT LIKE \"Deleted\" AND eventDesc NOT LIKE \"DNS record not deleted\" ";
$sql .= "AND eventDesc NOT LIKE \"% leases expired and % leases deleted\" AND hostname NOT LIKE \"\"";#filtering like baleen all up in this biz
$retval = mysql_query($sql, $conn);
if(!$retval){die("<strong>Error: </strong>".mysql_error());}
while($row = mysql_fetch_array($retval, MYSQL_ASSOC)){
	//echo "<p>".$row["eventid"]." , ".$row["eventDate"]." , ".$row["eventTime"]." , ".$row["eventDesc"]." , ".$row["ip"]." , ".$row["hostname"]." , ".$row["mac"]."</p>";
	$dbHost="localhost:3306";$dbName="dhcp"; $usr="root";$dbpass="";#add in other username / pass if needed here
	$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;", $usr, $dbpass);
	$stmt = $pdo->prepare("INSERT INTO `users` (eventid,eventDate,eventTime,eventDesc,ip,hostname,mac)VALUES (:i,:d,:t,:s,:p,:h,:m)");
	$stmt->bindParam(":i",$row["eventid"]);$stmt->bindParam(":d",$row["eventDate"]);$stmt->bindParam(":t",$row["eventTime"]);
	$stmt->bindParam(":s",$row["eventDesc"]);$stmt->bindParam(":p",$row["ip"]);
	$stmt->bindParam(":h",$row["hostname"]);$stmt->bindParam(":m",$row["mac"]);
	$stmt->execute();
}
echo"Finished adding to database <em>\"users\"</em>";
mysql_free_result($retval);
mysql_close($conn);#The eagle has landed
?>
</body>
</html>
```
