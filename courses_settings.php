<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION["type"]) || (isset($_SESSION["type"]) && $_SESSION['type'] != 'admin' )) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "courses Settings";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>course Settings</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>
    <section class="wrapper">
    <?php
        if (isset($_SESSION["addcourse"])) { ?>

            <section id="coursesList" class="article">

                <h1> Add New course</h1>

                <form action="Control/courseControl.php" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <th><label for="course_name">Course name:</label></th>
                            <td><input type="text" name="course_name" id="course_name" placeholder="Course Name" size="30" maxlength="30"></td>
                        </tr>
                        <tr>
                            <th><label for="major_id">choose Major:</label></th>
                            <td>
                            <select name="major_id" style="width: 100%;">
                                    <?php
                                        foreach($_SESSION['majors'] as $major) { ?>
                                            <option value ='<?php echo $major["id"] ?>'><?php echo $major['name'] ?></option> 
                                     <?php }?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="doctor_1_name">Doctor 1 Name:</label></th>
                            <td><input type="text" name="doctor_1_name" size="30" maxlength="30"></td>
                        </tr>
                        <tr>
                        <th><label for="doctor_2_name">Doctor 2 Name:</label></th>
                            <td><input type="text" name="doctor_2_name" size="30" maxlength="30"></td>
                        </tr>
                        <tr>
                        <th><label for="doctor_3_name">Doctor 3 Name:</label></th>
                            <td><input type="text" name="doctor_3_name" size="30" maxlength="30"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;"><input type="submit" name="CompleteAddcourse"
                                                                            class="button button2" value="Add course">
                            </td>
                        </tr>
                    </table>

                </form>
            </section>

            <?php
            unset($_SESSION["showcourses"]);
        } else { if (isset($_SESSION["editcourse"])) { ?>

                <section id="coursesList" class="article">

                    <h1>Update course (<?php
                        echo $_SESSION['course_name'] ?>) </h1>

                    <form action="Control/courseControl.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="course_id" value="<?php
                        echo $_SESSION['course_id'] ?>">
                        <table>
                            <tr>
                                <th><label for="course_name">Course name:</label></th>
                                <td><input type="text" name="course_name" value="<?php
                                    echo $_SESSION['course_name'] ?>" size="30" maxlength="30"></td>
                            </tr>
                            <tr>
                                <th><label for="major_id">choose Major:</label></th>
                                <td>
                                    <select name="major_id" style="width: 100%;">
                                        <?php
                                            foreach($_SESSION['majors'] as $major) {
                                                if($major['id'] == $_SESSION['course_major_id'])
                                                    echo "<option value='{$major["id"]}' selected>{$major['name']}</option>";
                                                else
                                                    echo "<option value='{$major["id"]}'>{$major['name']}</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <?php
                                for ($i = 1; $i <= 3; $i++) {
                                    $var_text = "doctor_" . $i . "_name";
                            ?>
                                <tr>
                                    <th><label for="<?php echo $var_text ?>">Doctor <?php echo $i ?> Name:</label></th>
                                    <td><input type="text" value="<?php echo $_SESSION[$var_text]  ?>" name="<?php echo $var_text ?>" id="<?php echo $var_text ?>" size="30" maxlength="30"></td>
                                </tr>
                            <?php }?>
                            <tr>
                                <td colspan="2" style="text-align: center;"><input type="submit" name="CompleteEditcourse"
                                                                                class="button button2" value="Edit course">
                                </td>
                            </tr>
                        </table>

                    </form>

                </section>

                <?php
                unset($_SESSION["showcourses"]);
        } else { ?>

                <section id="" class="article">

                    <h1> courses List <a class="button button2" href="Control/courseControl.php?addcourse">Add course</a></h1>
                    <table id="table">
                        <tr>
                            <th>course ID</th>
                            <th>major name</th>
                            <th>Name</th>
                            <th>course Doctors</th>
                            <th>Control</th>
                        </tr>

                        <?php
                        if(isset( $_SESSION["courses_num"])) {
                        for ($i = 1; $i <= $_SESSION["courses_num"]; $i++) {
                            $var_course_id = "course_" . $i . "_id";
                            $var_course_name = "course_" . $i . "_name";
                            $var_course_doctors = "course_" . $i . "_doctors";
                            $var_course_major_name = "course_" . $i . "_major_name";

                            ?>

                            <form action="Control/courseControl.php" method="post" enctype="multipart/form-data">
                                <tr>
                                    <td> <?php
                                        echo $_SESSION[$var_course_id]; ?> </td>
                                    <td> 
                                    <?php
                                        echo $_SESSION[$var_course_major_name]; ?> 
                                    </td>
                                    <td> <?php
                                        echo $_SESSION[$var_course_name]; ?> </td>
                                    <td>
                                        <?php 
                                            foreach($_SESSION[$var_course_doctors] as $doc_name) {
                                                echo "<span>{$doc_name['name']}</span><br/>";
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <input type="hidden" name="course_id" value=" <?php
                                        echo $_SESSION[$var_course_id]; ?> ">
                                        <input type="submit" name="editcourse" value="Edit course">
                                        <input type="submit" name="deletecourse" value="Delete course">
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