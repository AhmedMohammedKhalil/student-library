<?php

require_once 'Connect.php';


if (!isset($_SESSION)) session_start();


$orderControl = new OrderControl();
if (isset($_REQUEST["getMyOrders"])) {
    $orderControl->getMyOrders();
}
if (isset($_REQUEST["getBookOrders"])) {
    $orderControl->getBookOrders();
}
if (isset($_REQUEST["makeOrder"])) {
    $orderControl->makeOrder();
}
if (isset($_REQUEST["acceptOrder"])) {
    $orderControl->acceptOrder();
}

if (isset($_REQUEST["rejectOrder"])) {
    $orderControl->rejectOrder();
}



class OrderControl{

    public function getMyOrders() {
        global $con;
        $select = "SELECT id FROM users where email ='{$_SESSION['email']}';";
        $result = $con->query($select);
        $user = $result->fetch(PDO::FETCH_ASSOC); 

        $select = "SELECT o.*, o.id as order_id , b.* , owners.name as owner_name , owners.email as owner_email FROM users as owners , orders as o 
                join users as u on u.id = o.user_id
                join books as b on b.id = o.book_id
                where u.id = {$user['id']} And b.user_id = owners.id ";
         $result = $con->query($select);
         $_SESSION['myOrders'] = $result->fetchAll(PDO::FETCH_ASSOC);
         header('location: ../my_orders.php');
    }


    public function getBookOrders() {
        global $con;
        $select = "SELECT id FROM users where email ='{$_SESSION['email']}';";
        $result = $con->query($select);
        $user = $result->fetch(PDO::FETCH_ASSOC); 

        $select = "SELECT b.* , o.* ,  o.id as order_id , u.name as user_name , u.photo as user_photo , u.email as user_email 
                    from books as b 
                    join users as u on u.id = b.user_id 
                    join orders as o on o.book_id = b.id
                    where b.user_id = {$user['id']}";
        $result = $con->query($select);
        $_SESSION['bookOrders'] = $result->fetchAll(PDO::FETCH_ASSOC);
        header('location: ../book_orders.php');
    }

    


    public function makeOrder() {

        global $con;
        $select = "SELECT id FROM users where email ='{$_SESSION['email']}';";
        $result = $con->query($select);
        $user = $result->fetch(PDO::FETCH_ASSOC); 

        $select = "SELECT id FROM books where id ={$_POST['book_id']};";
        $result = $con->query($select);
        $book = $result->fetch(PDO::FETCH_ASSOC); 

        $book_id = $book['id'];
        $insert = "INSERT INTO orders (user_id,book_id,status) VALUES({$user['id']},{$book_id},'wait') ;";
        $result = $con->query($insert);

        $update = "UPDATE books set available = 'not available' where id={$book_id}";
        $result = $con->query($update);



        // for recommended books
        $select = "SELECT distinct b.* from books where name like '%{$book['name']}%' 
                    or description like '%{$book['name']}%' not id = {$book['id']} ;";
            $st = $con->prepare($select);
            $st->execute();
            $_SESSION['r_books'] = $st->fetchAll(PDO::FETCH_ASSOC);
            $i = 1;
            foreach($_SESSION['r_books'] as $b) {
                $var_r_book_id = "r_book_" . $i . "_id";
                $var_r_book_photo = "r_book_" . $i . "_photo";
                $var_r_book_title = "r_book_" . $i . "_title";
                $var_r_book_description = "r_book_" . $i . "_description";
                $var_r_book_status = "r_book_" . $i . "_status";
                $var_r_book_price = "r_book_" . $i . "_price";
                $var_r_book_condition = "r_book_" . $i . "_condition";
                $var_r_book_owner_id = "r_book_" . $i . "_owner_id";

                $_SESSION[$var_r_book_id] = $b['id'];
                $_SESSION[$var_r_book_title] = $b['title'];
                $_SESSION[$var_r_book_photo] = $b['photo'];
                $_SESSION[$var_r_book_description] = $b['description'];
                $_SESSION[$var_r_book_status] = $b['available'];
                $_SESSION[$var_r_book_condition] = $b['conditions'];
                $_SESSION[$var_r_book_price] = $b['price'];
                $_SESSION[$var_r_book_owner_id] = $b['user_id'];


                $i++;
            }
            $_SESSION['recommended'] = true;
            $_SESSION['r_books_num'] = $i - 1;

            header('location: ../books.php');

    }


    public function acceptOrder() {
        
        global $con;
        
        $order_id = $_POST['order_id'];
        $update = "UPDATE orders set status ='accept' where id={$order_id}";
        $result = $con->query($update);

        $this->getBookOrders();

    }


    public function rejectOrder() {
        
        global $con;
        
        $order_id = $_POST['order_id'];

        $select = "SELECT book_id FROM orders where id ={$order_id};";
        $result = $con->query($select);
        $book_id = $result->fetch(PDO::FETCH_ASSOC)['book_id']; 



        $update = "UPDATE orders set status ='reject' where id={$order_id}";
        $result = $con->query($update);


        $update = "UPDATE books set available ='available' where id={$book_id}";
        $result = $con->query($update);

        $this->getBookOrders();

    }


    
    
}