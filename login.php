<?php
if (!isset($_SESSION)) session_start();
if(isset($_SESSION["loged-in"])) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "Login";


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>
    <div class="content">

        <section id ="login" class="article login" style="min-height: 400px;padding:50px 0">
                <h1> Login Now ...</h1>

                
                <form action="Control/RegisterControl.php" method="post" >
                
                <table>
                <tr>	<th><label for="email">Email:</label> </th>	<td><input type="email" name="email" id="email" placeholder="email" size ="30" maxlength="30" required></td>	</tr>
                <tr>	<th><label for="password">Password:</label> </th>	<td><input type="password" name="password" id="password" placeholder="password" size ="30" maxlength="30" required></td>	</tr>
                <tr> <td colspan="2"><input type="submit" name="login" value ="Login"></td> </tr>
                </table>
                
                </form>
        </section>
    </div>

    <?php include 'layout/footer.php'; ?>

</body>

</html>