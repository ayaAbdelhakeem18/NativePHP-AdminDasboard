<?php
$dsn="mysql:host=localhost;dbname=example;";
// the road
$user="root";  

try {
    
$connect= new PDO($dsn,$user);
$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){
echo "failed to connect data base" . $e->getMessage(); 
}
?>