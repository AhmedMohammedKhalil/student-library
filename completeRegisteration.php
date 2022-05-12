<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION["CompleteRegistration"])) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "Complete Register";


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Compelete Register</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'?>
	<div class="content">
    <section id ="register" class="article" style="min-height: 500px;padding-top:50px">
	
        <h1> You can now complete your profile ...</h1>
            
        <form action="Control/RegisterControl.php" method="post" enctype="multipart/form-data">
        
            <table class="register">
            <tr>	<th><label for="phone">Phone:</label> </th>	<td><input type="tel" name="phone" id="phone" placeholder="xxxxxxxx" pattern="[0-9]{8}" size ="30" maxlength="30"></td>	</tr>
            <tr>	<th><label for="photo">Photo:</label> </th>	<td><input type="file" name="photo" id="photo" size ="30" maxlength="30"></td>	</tr>
            <tr>    <th><input type="submit" name="completeRegisteration" value ="Complete Registeration"></th> <th><input type="submit" name="skip" value ="Skip for now"></th> </tr>
            </table>
        
        </form>

    </section>
    </div>

    <?php include 'layout/footer.php'; ?>

</body>

</html>