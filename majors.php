<?php
if (!isset($_SESSION)) session_start();

$_SESSION["header_h1"] = "Student library";

    $_SESSION["header_h2"] = "All Majors";

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

                <h1> All Majors </h1>
                <div class="flex" style="display: flex;width:100%; justify-content:space-around;flex-wrap: wrap;">
                <?php
                    if(isset( $_SESSION["majors_num"])) {
                        for ($i = 1; $i <= $_SESSION["majors_num"]; $i++) {
                            $var_major_id = "major_" . $i . "_id";
                            $var_major_name = "major_" . $i . "_name";
                            $var_major_desc = "major_" . $i . "_desc";

                        ?>
                        <div style="width: 30%;text-align:center;padding: 20px 0;margin: 10px 0;background-color:#e0e6fc57">
                            
                            <div> <?php echo $_SESSION[$var_major_name]; ?> </div>
                            <div>
                                <p><?php echo htmlspecialchars_decode(stripslashes($_SESSION[$var_major_desc])); ?></p>
                            </div>
                            <div>
                                <form action="Control/HomeControl.php" method="post">
                                    <input type="hidden" name="major_id" value=" <?php
                                    echo $_SESSION[$var_major_id]; ?> ">
                                    <input type="submit" class="button button2" name="getCourses" value="show Courses">
                                </form>
                            </div>
                        </div>
                <?php } 
                    if($_SESSION["majors_num"] == 0) {
                        echo "<div style='text-align:center'>Not Found Majors</div>";
                    }
                } 
                ?>
                </div>
                </section>
        

                

    </section>
    
    <?php include 'layout/footer.php'; ?>

</body>

</html>