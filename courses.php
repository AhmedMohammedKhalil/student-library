<?php
if (!isset($_SESSION)) session_start();

$_SESSION["header_h1"] = "Student library";

    $_SESSION["header_h2"] = "All Courses";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $_SESSION["header_h2"]?></title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

</head>
<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>
    <section class="wrapper">
    

                <section id="" class="article">

                <h1> All Courses </h1>
                <div class="flex" style="display: flex;width:100%; justify-content:space-around">
                <?php
                    if(isset( $_SESSION["courses_num"])) {
                        for ($i = 1; $i <= $_SESSION["courses_num"]; $i++) {
                            $var_course_id = "course_" . $i . "_id";
                            $var_course_name = "course_" . $i . "_name";
                            $var_course_doctors= "course_" . $i . "_doctors";       

                        ?>
                        <div style="width: 30%;text-align:center;padding: 20px 0;margin: 10px 0;background-color:#e0e6fc57">
                            
                            <div> <?php echo $_SESSION[$var_course_name]; ?> </div>
                            <div>
                                <?php 
                                    foreach($_SESSION[$var_course_doctors] as $doc_name) {
                                        echo "<span>{$doc_name['name']}</span><br/>";
                                    }
                                ?>
                            </div>
                            <div>
                                <form action="Control/HomeControl.php" method="post">
                                    <input type="hidden" name="course_id" value=" <?php
                                    echo $_SESSION[$var_course_id]; ?> ">
                                    <input type="submit" class="button button2" name="getBooks" value="show Books">
                                </form>
                            </div>
                        </div>
                <?php }
                    if($_SESSION["courses_num"] == 0) {
                        echo "<div style='text-align:center'>Not Found Courses</div>";

                    } 
                } 
                ?>
                </div>
                </section>
     

    </section>
    
    <?php include 'layout/footer.php'; ?>

</body>

</html>