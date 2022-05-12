<?php
if (!isset($_SESSION)) session_start();

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "Search";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>
    <section class="wrapper">

            <section id="Search" class="article" style="margin-bottom: 50px;">

                <h1> Search About Books </h1>

                <form action="Control/SearchControl.php" method="post">
                    <table style="margin:auto">
                        <tr>
                            <th><label for="search">Search:</label></th>
                            <td><input type="text" name="search" style="width:100%;box-sizing:border-box"></td>
                        </tr>
                        <tr>
                            <th>choose Type :</th>
                            <td>
                                <select name="type" style="width: 100%;">
                                    <option value="1">Univeristy Name</option>
                                    <option value="2">Major Name</option>
                                    <option value="3">Course Name</option>
                                    <option value="4" selected>Book Name</option>

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>choose Order :</th>
                            <td>
                                <select name="order" style="width: 100%;">
                                    <option value="1" selected>Ascending</option>
                                    <option value="2">Descending</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;"><input type="submit" name="makeSearch"
                                                                            class="button button2" value="Make Search">
                            </td>
                        </tr>
                    </table>

                </form>
            </section>

            <?php if(isset($_SESSION['search'])) { ?>
                <section id="" class="article">
                    <h1> All Books </h1>
                    <div class="flex" style="display: flex;width:100%; justify-content:space-around">
                    <?php
                        if(isset( $_SESSION["books_num"])) {
                            $flag = true;
                        for ($i = 1; $i <= $_SESSION["books_num"]; $i++) {
                            $var_book_id = "book_" . $i . "_id";
                            $var_book_photo = "book_" . $i . "_photo";
                            $var_book_title = "book_" . $i . "_title";
                            $var_book_status = "book_" . $i . "_status";
                            $var_book_condition = "book_" . $i . "_condition";
                            $var_book_price = "book_" . $i . "_price";
                            $var_book_description = "book_" . $i . "_description";
                            $var_book_owner_id = "book_" . $i . "_owner_id";

                            if($_SESSION[$var_book_status] == 'available') {
                                $flag = false;
                            ?>
                            <div style="width: 30%;text-align:center;padding: 20px 0;margin: 10px 0;background-color:#e0e6fc57">
                            <form action="Control/OrderControl.php" method="post">

                                <div>
                                    <?php if($_SESSION[$var_book_photo] != '') {?>
                                        <img src="<?php echo $_SESSION[$var_book_photo]; ?>" style="width: 90%;" alt="">
                                    <?php } else {?>
                                        <img src="./imgs/books/default.png" style="width: 90%;" alt="">
                                    <?php }?>
                                    
                                </div>
                                <div> <?php echo $_SESSION[$var_book_title]; ?> </div>
                                <div> <?php echo $_SESSION[$var_book_condition]; ?> </div>
                                <div> <?php echo $_SESSION[$var_book_price]; ?> KW</div>

                                <div>
                                    <?php echo htmlspecialchars_decode(stripslashes($_SESSION[$var_book_description])); ?>
                                </div>
                                <?php if(isset($_SESSION['type']) && $_SESSION['type'] != 'admin') {?>
                                <div>
                                    <?php if($_SESSION['user_id'] != $_SESSION[$var_book_owner_id]) {?>
                                            <input type="hidden" name="book_id" value="<?php echo $_SESSION[$var_book_id]; ?>">
                                            <input type="submit" class="button button2" name="makeOrder" value="Make Order">
                                    <?php }?>
                                </div>
                                <?php }?>
                            </form>

                            </div>
                            <?php } 
                            ?>
                    <?php }
                        if($_SESSION["books_num"] == 0 || $flag == true) {
                            echo "<div style='text-align:center'>Not Found books</div>";
                        }
                    }
                    ?>
                    </div>
                </section>
            <?php } ?>

    </section>
    
    <?php include 'layout/footer.php'; ?>

</body>

</html>