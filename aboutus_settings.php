<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION["type"]) || (isset($_SESSION["type"]) && $_SESSION['type'] != 'admin' )) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "About Settings";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About Us Settings</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>

    <div class="content">
        <div class="section">
            <div class="aboutus aboutus-setting" style="min-height: 500px;">
                <h2>About Us Setting</h2>
                <form action="./Control/AboutControl.php" method="post" enctype="multipart/form-data">
                    <table>
                        <?php
                        for ($i = 1; $i <= 2; $i++) {
                            $var_text = "para_" . $i . "_text";
                        ?>
                            <tr>
                                <th><label for="<?php echo $var_text; ?>">Paragraph <?php echo $i; ?> Text:</label></th>
                                <td> <textarea rows="10" cols="60" name="<?php echo $var_text; ?>" id="<?php echo $var_text;?>" placeholder="about us article"><?php echo $_SESSION[$var_text] ?? ''; ?></textarea>
                                    <script>
                                        ClassicEditor
                                            .create(document.querySelector('#<?php echo $var_text; ?>'))
                                            .catch(error => {
                                                console.error(error);
                                            });
                                    </script>
                                </td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td colspan="2"><input type="submit" name="editAbout" value="Update"></td>
                        </tr>

                    </table>
                </form>
            </div>
        </div>
    </div>

    <?php include 'layout/footer.php'; ?>

</body>

</html>