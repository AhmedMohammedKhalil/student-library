<?php
if (!isset($_SESSION)) session_start();

require_once 'Control/AboutControl.php';
$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "Home";

$aboutControl = new AboutControl();
$aboutControl->getAll();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>

    <div class="content">
        <?php if($_SESSION['aboutus']) { ?>
        <div class="aboutus" style="min-height: 600px;">
            <h2>About us</h2>
            <?php  foreach($_SESSION['aboutus'] as $a) {
                echo "<div>{$a['text']}</div>";
            }
            ?>
        </div>
        <?php } ?>


        <?php if(!isset($_SESSION["loged-in"])) { include 'layout/nav.php'; include 'MSG.php'?>	
	
        <section id ="register" class="article" style="padding: 50px 0;">
            <h1> Register Now ...</h1>

            
            <form action="Control/RegisterControl.php" method="post" >
            
            <table>
            <tr>	<th><label for="name">Name:</label> </th>	<td><input type="text" name="name" placeholder="name" id="name" size ="30" maxlength="30" required></td>	</tr>
            <tr>	<th><label for="email">Email:</label> </th>	<td><input type="email" name="email" placeholder="email" id="email" size ="30" maxlength="30" required></td>	</tr>
            <tr>	<th><label for="type">Type:</label> </th>
                    <td style="width: 100%;">
                        <select name="type" id="type" style="width: 100%;">
                            <option value="student">Student</option>
                            <option value="doctor">Doctor</option>
                        </select>
                    </td>
            </tr>
            <tr>	<th><label for="password">Password:</label> </th>	<td><input type="password" name="password" id="password" placeholder="password" size ="30" maxlength="30" required></td>	</tr>
            <tr>	<th><label for="confirm_password">Confirm Password:</label> </th>	<td><input type="password" name="confirm_password" id="confirm_password" placeholder="confrim password" size ="30" maxlength="30" required></td>	</tr>
            <tr> <td colspan="2"><input type="submit" name="register" value ="Register"></td> </tr>
            </table>
            
            </form>
            
            <a href="login.php" style="display:block"> Already Registered? Login here.. </a>

        </section>
            
        <?php }?>
    </div>




    <?php include 'layout/footer.php'; ?>

</body>

</html>