<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION["loged-in"])) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
if(isset($_SESSION['editprofile']))
    $_SESSION["header_h2"] = "Profile Settings";
else
    $_SESSION["header_h2"] = "Profile";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
    <style>
        table{
            width: 500px;
            display: block;
            margin: auto;
        }

        form th {
            width: auto;
        }
    </style>
</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>
    <section class="wrapper">
    <?php
        if (isset($_SESSION["editprofile"])) { ?>

            <section id="editprofile" class="article">

                <h1> Edit Profile</h1>

                <form action="Control/RegisterControl.php" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <th><label for="name">Name:</label> </th>	
                            <td><input type="text" value="<?php echo $_SESSION['user']['name'] ?>" name="name" id="name" size ="30" maxlength="30" required></td>	
                        </tr>
                        <tr>	
                            <th><label for="email">Email:</label> </th>	
                            <td><input type="email" name="email" value="<?php echo $_SESSION['user']['email'] ?>" id="email" size ="30" maxlength="30" required></td>	
                        </tr>
                        <tr>	
                            <th><label for="photo">Photo:</label> </th>	
                            <td><input type="file" name="photo" id="photo" size ="30" maxlength="30" ></td>	
                        </tr>

                        <tr>	
                            <th><label for="password">Password:</label> </th>	
                            <td><input type="password" name="password" id="password" size ="30" maxlength="30"></td>	
                        </tr>
                        <tr>	
                            <th><label for="confirm_password">Confirm Password:</label> </th>	
                            <td><input type="password" name="confirm_password" id="confirm_password" size ="30" maxlength="30" ></td>	
                        </tr>
                        <?php if($_SESSION['type'] != 'admin') {?>
                        <tr>	
                            <th><label for="phone">Phone:</label> </th>	
                            <td><input type="tel" name="phone" value="<?php echo $_SESSION['user']['phone'] ?>" id="phone" placeholder="xxxxxxxx" pattern="[0-9]{8}" size ="30" maxlength="30"></td>	
                        </tr>
                        </tr>
                            <th>choose majors :</th>
                            <td>
                                <select name="major_id">
                                    <option value="0">choose Major</option>
                                    <?php
                                        foreach($_SESSION['majors'] as $major) {
                                            if($major['id'] == $_SESSION['user']['major_id'])
                                                echo "<option value='{$major["id"]}' selected>{$major['name']}</option>";
                                            else
                                                echo "<option value='{$major["id"]}'>{$major['name']}</option>";
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2" style="text-align: center;"><input type="submit" name="CompleteEditProfile"
                                                                            class="button button2" value="Edit Profile">
                            </td>
                        </tr>
                    </table>
                </form>
            </section>

            <?php
            unset($_SESSION["showProfile"]);
        } else { ?>

                <section id="" class="article" style="padding-top: 50px;width:fit-content; margin:auto;">
                    <div>
                        <h2 style="text-align:center">Info</h2>
                    </div>
                    <div>
                        <span>Name : </span> 
                        <span><?php echo $_SESSION['user']['name']?></span>
                    </div>
                    <div>
                        <span>email : </span> 
                        <span><?php echo $_SESSION['user']['email']?></span>
                    </div>
                    <div>
                        <span>type : </span> 
                        <span><?php echo $_SESSION['user']['type']?></span>
                    </div>
                    <?php if($_SESSION['type'] != 'admin') {?>
                        <?php if($_SESSION['user']['phone'] != '') {?>
                            <div>
                                <span>phone : </span> 
                                <span><?php echo $_SESSION['user']['phone']?></span>
                            </div>
                        <?php }?>
                        <?php if($_SESSION['user']['major_name'] != '') {?>

                        <div>
                            <span>major : </span> 
                            <span><?php echo $_SESSION['user']['major_name']?></span>
                        </div>
                    <?php }} ?>
                    <div style="margin-top: 20px;text-align:center">
                        <a class="button button2" href="Control/RegisterControl.php?editProfile">Edit Profile</a>
                    </div>
                </section>
        <?php } ?>

    </section>
    
    <?php include 'layout/footer.php'; ?>

</body>

</html>