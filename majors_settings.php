<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION["type"]) || (isset($_SESSION["type"]) && $_SESSION['type'] != 'admin' )) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "Majors Settings";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>major Settings</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>
    <section class="wrapper">
    <?php
        if (isset($_SESSION["addmajor"])) { ?>

            <section id="MajorsList" class="article">

                <h1> Add New major</h1>

                <form action="Control/MajorControl.php" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <th><label for="major_name">Name:</label></th>
                            <td><input type="text" name="major_name" id="major_name" placeholder="Major Name" size="30" maxlength="30"></td>
                        </tr>
                        
                        <tr>
                            <th><label for="uni_id">choose univerisities :</label></th>
                            <td>
                            <select name="uni_id" id="uni_id" style="width: 100%;">
                                    <?php
                                        foreach($_SESSION['unis'] as $uni) { ?>
                                            <option value ='<?php echo $uni["id"] ?>'><?php echo $uni['name'] ?></option> 
                                     <?php }?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="major_desc">Description:</label></th>
                            <td><textarea rows="10" cols="60" name="major_desc" id="major_desc" placeholder="Major Description"></textarea>
                                <script>
                                    ClassicEditor
                                        .create(document.querySelector('#major_desc'))
                                        .catch(error => {
                                            console.error(error);
                                        });
                                </script>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;"><input type="submit" name="CompleteAddmajor"
                                                                            class="button button2" value="Add major">
                            </td>
                        </tr>
                    </table>

                </form>
            </section>

            <?php
            unset($_SESSION["showMajors"]);
        } else { if (isset($_SESSION["editmajor"])) { ?>

                <section id="MajorsList" class="article">

                    <h1>Update major (<?php
                        echo $_SESSION['major_name'] ?>) </h1>

                    <form action="Control/majorControl.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="major_id" value="<?php
                        echo $_SESSION['major_id'] ?>">
                        <table>
                            <tr>
                                <th><label for="major_name">Name:</label></th>
                                <td><input type="text" name="major_name" id="major_name" value="<?php
                                    echo $_SESSION['major_name'] ?>" size="30" maxlength="30"></td>
                            </tr>
                            
                            <tr>
                                <th><label for="uni_id">choose univerisities :</label></th>
                                <td>
                                <select name="uni_id" id="uni_id" style="width: 100%;">
                                        <?php
                                            foreach($_SESSION['unis'] as $uni) {
                                                if($uni['id'] == $_SESSION['major_uni_id'])
                                                    echo "<option value='{$uni["id"]}' selected>{$uni['name']}</option>";
                                                else
                                                    echo "<option value='{$uni["id"]}'>{$uni['name']}</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="major_desc">Description:</label></th>
                                <td><textarea rows="10" cols="60" name="major_desc" id="major_desc"><?php
                                        echo $_SESSION['major_desc']; ?></textarea>
                                    <script>
                                        ClassicEditor
                                            .create(document.querySelector('#major_desc'))
                                            .catch(error => {
                                                console.error(error);
                                            });
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;"><input type="submit" name="CompleteEditmajor"
                                                                                class="button button2" value="Edit major">
                                </td>
                            </tr>
                        </table>

                    </form>
                    

                </section>

                <?php
                unset($_SESSION["showMajors"]);
        } else { ?>

                <section id="" class="article">

                    <h1> Majors List <a class="button button2" href="Control/majorControl.php?addmajor">Add major</a></h1>
                    <table id="table">
                        <tr>
                            <th>major ID</th>
                            <th>univeristy name</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Control</th>
                        </tr>

                        <?php
                        if(isset( $_SESSION["Majors_num"])) {
                        for ($i = 1; $i <= $_SESSION["Majors_num"]; $i++) {
                            $var_major_id = "major_" . $i . "_id";
                            $var_major_name = "major_" . $i . "_name";
                            $var_major_desc = "major_" . $i . "_desc";
                            $var_major_uni_name = "major_" . $i . "_uni_name";

                            ?>

                            <form action="Control/majorControl.php" method="post" enctype="multipart/form-data">
                                <tr>
                                    <td> <?php
                                        echo $_SESSION[$var_major_id]; ?> </td>
                                    
                                    <td> 
                                    <?php
                                        echo $_SESSION[$var_major_uni_name]; ?> 
                                    </td>
                                    <td> <?php
                                        echo $_SESSION[$var_major_name]; ?> </td>
                                    <td><p><?php
                                            echo htmlspecialchars_decode(stripslashes($_SESSION[$var_major_desc])); ?></p></td>
                                    
                                    <td>
                                        <input type="hidden" name="major_id" value=" <?php
                                        echo $_SESSION[$var_major_id]; ?> ">
                                        <input type="submit" name="editmajor" value="Edit major">
                                        <input type="submit" name="deletemajor" value="Delete major">
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