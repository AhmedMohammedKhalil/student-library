<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION["type"]) || (isset($_SESSION["type"]) && $_SESSION['type'] != 'admin' )) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "Univerisities Settings";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Univerisity Settings</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>
    <section class="wrapper">
    <?php
        if (isset($_SESSION["addUniveristy"])) { ?>

            <section id="UniveristiesList" class="article">

                <h1> Add New Univeristy</h1>

                <form action="Control/UniveristyControl.php" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <th><label for="name"> Name:</label></th>
                            <td><input type="text" name="univeristy_name" size="30" maxlength="30"></td>
                        </tr>
                        <tr>
                            <th><label for="Description"> Description:</label></th>
                            <td><textarea rows="10" cols="60" name="univeristy_desc" id="Univeristy_desc"></textarea>
                                <script>
                                    ClassicEditor
                                        .create(document.querySelector('#Univeristy_desc'))
                                        .catch(error => {
                                            console.error(error);
                                        });
                                </script>
                            </td>
                        </tr>
                        <tr>
                                <th><label for="Photo"> Photo:</label></th>
                                <td><input type="file" name="photo" size="30" maxlength="30"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;"><input type="submit" name="CompleteAddUniveristy"
                                                                            class="button button2" value="Add Univeristy">
                            </td>
                        </tr>
                    </table>

                </form>
            </section>

            <?php
            unset($_SESSION["showUniveristies"]);
        } else { if (isset($_SESSION["editUniveristy"])) { ?>

                <section id="UniveristiesList" class="article">

                    <h1>Update Univeristy (<?php
                        echo $_SESSION['univeristy_name'] ?>) </h1>

                    <form action="Control/UniveristyControl.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="univeristy_id" value="<?php
                        echo $_SESSION['univeristy_id'] ?>">
                        <table>
                            <tr>
                                <th>Name:</th>
                                <td><input type="text" name="univeristy_name" value="<?php
                                    echo $_SESSION['univeristy_name'] ?>" size="30" maxlength="30"></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td><textarea rows="10" cols="60" name="univeristy_desc" id="Univeristy_desc"><?php
                                        echo $_SESSION['univeristy_desc']; ?></textarea>
                                    <script>
                                        ClassicEditor
                                            .create(document.querySelector('#Univeristy_desc'))
                                            .catch(error => {
                                                console.error(error);
                                            });
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <th>photo:</th>
                                <td><input type="file" name="photo" size="30" maxlength="30"></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;"><input type="submit" name="CompleteEditUniveristy"
                                                                                class="button button2" value="Edit Univeristy">
                                </td>
                            </tr>
                        </table>

                    </form>

                </section>

                <?php
                unset($_SESSION["showUniveristies"]);
        } else { ?>

                <section id="" class="article">

                    <h1> Univeristies List <a class="button button2" href="Control/UniveristyControl.php?addUniveristy">Add Univeristy</a></h1>
                    <table id="table">
                        <tr>
                            <th>Univeristy ID</th>
                            <th>photo</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Control</th>
                        </tr>

                        <?php
                        if(isset( $_SESSION["Univeristies_num"])) {
                        for ($i = 1; $i <= $_SESSION["Univeristies_num"]; $i++) {
                            $var_Univeristy_id = "Univeristy_" . $i . "_id";
                            $var_Univeristy_photo = "Univeristy_" . $i . "_photo";
                            $var_Univeristy_name = "Univeristy_" . $i . "_name";
                            $var_Univeristy_desc = "Univeristy_" . $i . "_desc";
                            ?>

                            <form action="Control/UniveristyControl.php" method="post" enctype="multipart/form-data">
                                <tr>
                                    <td> <?php
                                        echo $_SESSION[$var_Univeristy_id]; ?> </td>
                                    
                                    <td> 
                                        <?php if($_SESSION[$var_Univeristy_photo] != '') {?>
                                            <img src="<?php echo $_SESSION[$var_Univeristy_photo]; ?>" alt="">
                                        <?php } else {?>
                                            <img src="./imgs/univeristies/default.jpg" alt="">
                                        <?php }?>
                                        
                                    </td>
                                    <td> <?php
                                        echo $_SESSION[$var_Univeristy_name]; ?> </td>
                                    <td><p><?php
                                            echo htmlspecialchars_decode(stripslashes($_SESSION[$var_Univeristy_desc])); ?></p></td>
                                    
                                    <td>
                                        <input type="hidden" name="univeristy_id" value=" <?php
                                        echo $_SESSION[$var_Univeristy_id]; ?> ">
                                        <input type="submit" name="editUniveristy" value="Edit Univeristy">
                                        <input type="submit" name="deleteUniveristy" value="Delete Univeristy">
                                    </td>
                                </tr>
                            </form>

                            <?php
                        } } ?>

                    </table>
                </section>
                <?php
            }
        } ?>

    </section>
    
    <?php include 'layout/footer.php'; ?>

</body>

</html>