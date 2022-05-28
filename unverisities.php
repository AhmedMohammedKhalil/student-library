<?php
if (!isset($_SESSION)) session_start();

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "All Univerisities";

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
            <h1> All Univeristies </h1>
            <div class="flex" style="display: flex;width:100%; justify-content:space-around;flex-wrap: wrap;">
            <?php
                if(isset( $_SESSION["Univeristies_num"])) {
                for ($i = 1; $i <= $_SESSION["Univeristies_num"]; $i++) {
                    $var_Univeristy_id = "Univeristy_" . $i . "_id";
                    $var_Univeristy_photo = "Univeristy_" . $i . "_photo";
                    $var_Univeristy_name = "Univeristy_" . $i . "_name";
                    $var_Univeristy_desc = "Univeristy_" . $i . "_desc";
                    ?>
                    <div style="width: 30%;text-align:center;padding: 20px 0;margin: 10px 0;background-color:#e0e6fc57">
                        <div> 
                            <?php if($_SESSION[$var_Univeristy_photo] != '') {?>
                                <img src="<?php echo $_SESSION[$var_Univeristy_photo]; ?>" style="width: 100%;" alt="">
                            <?php } else {?>
                                <img src="./imgs/univeristies/default.jpg" style="width: 100%;" alt="">
                            <?php }?>
                            
                        </div>
                        <div> <?php echo $_SESSION[$var_Univeristy_name]; ?> </div>
                        <div>
                            <p><?php echo htmlspecialchars_decode(stripslashes($_SESSION[$var_Univeristy_desc])); ?></p>
                        </div>
                        <div>
                            <form action="Control/HomeControl.php" method="post">

                                <input type="hidden" name="univeristy_id" value=" <?php
                                echo $_SESSION[$var_Univeristy_id]; ?> ">
                                <input type="submit" class="button button2" name="getMajors" value="show Majors">
                            </form>
                        </div>
                    </div>
            <?php } 
                if($_SESSION["Univeristies_num"] == 0) {
                    echo "<div style='text-align:center'>Not Found Univeristies</div>";
                }
            } ?>
            </div>
        </section>

    </section>
    
    <?php include 'layout/footer.php'; ?>

</body>

</html>