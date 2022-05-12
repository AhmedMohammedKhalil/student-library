<?php


try {
$con = new PDO('mysql:host=localhost;dbname=students_library', 'root', '');
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $con;
}
catch(PDOException $e) {
    echo $e->getMessage();
exit();
}

