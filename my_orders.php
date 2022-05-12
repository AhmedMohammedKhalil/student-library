<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION["type"]) || (isset($_SESSION["type"]) && $_SESSION['type'] == 'admin' )) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "My Orders";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>

</head>

<body>
    <?php include 'layout/header.php'; ?>
    <?php include 'MSG.php'; ?>
    <section class="wrapper">
        <section id="" class="article">

            <table id="table">
                <tr>
                    <th>Order ID</th>
                    <th>Book Photo</th>
                    <th>Book title</th>
                    <th>book price</th>
                    <th>Order Status</th>
                    <th>Order Created_at</th>
                    <th>Owner Name</th>
                    <th>Owner Email</th>
                </tr>

                <?php
                if(isset( $_SESSION["myOrders"])) {
                    foreach($_SESSION['myOrders'] as $order) {

                ?>

                        <tr>
                            <td> <?php
                                echo $order['order_id']; ?> </td>
                                <td> 
                                <?php if($order['photo'] != '') {?>
                                    <img src="<?php echo $order['photo']; ?>" alt="">
                                <?php } else {?>
                                    <img src="./imgs/books/default.png" alt="">
                                <?php }?>
                                
                            </td>
                            <td> <?php
                                echo $order['title']; ?> </td>
                            <td> <?php
                                echo $order['price']; ?> </td>
                            <td> <?php
                                echo $order['status']; ?> </td>
                            <td> <?php
                                echo $order['created_at']; ?> </td>
                            <td> <?php
                                echo $order['owner_name']; ?> </td>
                            <td> <?php
                                echo $order['owner_email']; ?> </td>
                        </tr>

                    <?php } } ?>

            </table>
        </section>
    </section>
    
    <?php include 'layout/footer.php'; ?>

</body>

</html>