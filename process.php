```php
<!DOCTYPE html>
<html style="font-family:Segoe UI">
 <head><title>127.0.0.1 | File Submit</title></head>
 <body>
 <?php
  $connection=mysql_connect('localhost:3306','root');
  if(!$connection){die('Could not connect to MySQL DB: ' . mysql_error());}
  echo "<p>"."Connected successfully to \"localhost:3306\""."</p>";
  if (($_FILES["file"]["error"]) > 0) {
   echo "Error: ".$_FILES["file"]["error"]."<br>";
  } else {
   echo "Upload: ".$_FILES["file"]["name"]."<br>";
   echo "Type: ".$_FILES["file"]["type"]."<br>";
   echo "Size: ".round(($_FILES["file"]["size"] / 1024), 2)." kB<br>";
   echo "Stored in: ".$_FILES["file"]["tmp_name"];
   echo "<br/><br/>";
  }
  $text = file($_FILES["file"]["tmp_name"]);$fcount=1;
  foreach($text as $line){
    if($fcount > 33){
		list($id,$date,$time,$dsc,$ip,$host,$mac) = explode(",",$line);
		$dbHost="localhost:3306";$dbName="dhcp";$usr="root";$dbpass="";
		$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;", $usr, $dbpass);
		$stmt = $pdo->prepare("INSERT INTO `server_logs` (eventid,eventDate,eventTime,eventDesc,ip,hostname,mac) values (:i,:d,:t,:s,:p,:h,:m)");
		$stmt->bindParam(":i",$id);$stmt->bindParam(":d",$date);$stmt->bindParam(":t",$time);
		$stmt->bindParam(":s",$dsc);$stmt->bindParam(":p",$ip);$stmt->bindParam(":h",$host);$stmt->bindParam(":m",$mac);
		$stmt->execute();
	} else{/*don't add lines 1-33*/}
	++$fcount;
  }
  echo "Entered data successfully";
  mysql_close($connection);
 ?>
 <br/><br/>
 <button type="button" onclick="parent.location='http://127.0.0.1/main.htm'">Add another file to the database</button>
 </body>
</html>
```
