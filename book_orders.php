<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION["type"]) || (isset($_SESSION["type"]) && $_SESSION['type'] == 'admin' )) header('Location: ./index.php'); 

$_SESSION["header_h1"] = "Student library";
$_SESSION["header_h2"] = "Book Orders";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Orders</title>
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
                    <th>User Photo</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Book title</th>
                    <th>book price</th>
                    <th>Order Status</th>
                    <th>Order Created_at</th>
                    <th>Control</th>
                </tr>

                <?php
                if(isset( $_SESSION["bookOrders"])) {
                    foreach($_SESSION['bookOrders'] as $order) {

                ?>

                    <form action="Control/OrderControl.php" method="post" enctype="multipart/form-data">
                        <tr>
                            <td> <?php
                                echo $order['order_id']; ?> </td>
                                <td> 
                                <?php if($order['user_photo'] != '') {?>
                                    <img src="<?php echo $order['user_photo']; ?>" alt="">
                                <?php } else {?>
                                    <img src="./imgs/users/default.png" alt="">
                                <?php }?>
                                
                            </td>
                            <td> <?php
                                echo $order['user_name']; ?> </td>
                            <td> <?php
                                echo $order['user_email']; ?> </td>
                            <td> <?php
                                echo $order['title']; ?> </td>
                            <td> <?php
                                echo $order['price']; ?> </td>
                            <td> <?php
                                echo $order['status']; ?> </td>
                            <td> <?php
                                echo $order['created_at']; ?> </td>
                            
                            <td>
                                <?php if($order['status'] == 'wait') {?>
                                <input type="hidden" name="order_id" value=" <?php
                                echo $order['order_id']; ?> ">
                                <input type="submit" name="acceptOrder" value="Accept">
                                <input type="submit" name="rejectOrder" value="Reject">
                                <?php } ?>
                            </td>
                        </tr>
                    </form>

                    <?php } } ?>

            </table>
        </section>
    </section>
    
    <?php include 'layout/footer.php'; ?>

</body>

</html>