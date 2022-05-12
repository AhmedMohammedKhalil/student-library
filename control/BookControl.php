<?php

require_once 'Connect.php';


if (!isset($_SESSION)) session_start();


$bookControl = new BookControl();
if (isset($_REQUEST["getAll"])) {
    $bookControl->getAll();
}
if(isset($_REQUEST["addbook"])) {$bookControl->addbook();}
if(isset($_REQUEST["editbook"])) {$bookControl->editbook();}
if(isset($_REQUEST["CompleteAddbook"])) {$bookControl->CompleteAddbook();}
if(isset($_REQUEST["CompleteEditbook"])) {$bookControl->CompleteEditbook();}
if(isset($_REQUEST["deletebook"])) {$bookControl->deletebook();}


class BookControl{

    public function getAll() {
        global $con;

        $select = "SELECT * FROM courses ;";
        $result = $con->query($select);
        $_SESSION['courses'] = $result->fetch(PDO::FETCH_ASSOC); 

        if(!$_SESSION['courses']) {
            $_SESSION["Error_MSG"] = "You must found Courses for Adding Books, please contact with admin for add courses";
            header('Location: ../index.php');
            exit();
        }

        $select = "SELECT id FROM users where email ='{$_SESSION['email']}';";
        $result = $con->query($select);
        $user = $result->fetch(PDO::FETCH_ASSOC); 
        
        $st = $con->prepare("SELECT * from books where user_id = {$user['id']}");
        $st->execute();
        $_SESSION['books'] = $st->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        foreach($_SESSION['books'] as $b) {
            $var_book_id = "book_" . $i . "_id";
            $var_book_photo = "book_" . $i . "_photo";
            $var_book_title = "book_" . $i . "_title";
            $var_book_description = "book_" . $i . "_description";
            $var_book_status = "book_" . $i . "_status";
            $var_book_price = "book_" . $i . "_price";
            $var_book_condition = "book_" . $i . "_condition";
            $var_book_courses= "book_" . $i . "_courses";
            $_SESSION[$var_book_id] = $b['id'];
            $_SESSION[$var_book_title] = $b['title'];
            $_SESSION[$var_book_photo] = $b['photo'];
            $_SESSION[$var_book_description] = $b['description'];
            $_SESSION[$var_book_status] = $b['available'];
            $_SESSION[$var_book_condition] = $b['conditions'];
            $_SESSION[$var_book_price] = $b['price'];

            $query = "SELECT c.name as course_name From book_course as bc 
                        join books as b on b.id = bc.book_id 
                        join courses as c on c.id = bc.course_id 
                        where b.id = {$b['id']} ";
            $st = $con->prepare($query);
            $st->execute();
            $_SESSION[$var_book_courses] = $st->fetchAll(PDO::FETCH_ASSOC);

            $i++;
        }

        $_SESSION['books_num'] = $i - 1;
        $_SESSION["showbooks"] = "True";
        unset($_SESSION["addbook"], $_SESSION["editbook"]);
        header('Location: ../books_settings.php');
    }


    public function addbook() {

        $_SESSION["addbook"] = "True";
        global $con;
        $select = "SELECT * FROM courses ;";
        $result = $con->query($select);
        $_SESSION['courses'] = $result->fetchAll(PDO::FETCH_ASSOC);
       
        header('Location: ../books_settings.php');

    }


    public function CompleteAddbook() {
        global $con;
        $select = "SELECT id FROM users where email ='{$_SESSION['email']}';";
        $result = $con->query($select);
        $user = $result->fetch(PDO::FETCH_ASSOC); 


        $title = $this->test_input($_POST["title"]);
        $description = $this->test_input($_POST["desc"]);
        $status = $this->test_input($_POST["status"]);
        $condition = $this->test_input($_POST["condition"]);
        $price = $this->test_input($_POST["price"]);
        $courses = $_POST["courses"];
        $photo = null;
        

        if(isset($_FILES['photo']))
        {
            $file_name = $_FILES['photo']['name'];
            $file_type = $_FILES['photo']['type'];
            $file_tmp_name = $_FILES['photo']['tmp_name'];
            $file_size = $_FILES['photo']['size'];
            
            
            $target_dir = "../imgs/books/";
            
            if(move_uploaded_file($file_tmp_name,$target_dir.$file_name))
            {
                $photo = "./imgs/books/".$file_name;
            }

            unset($_FILES['photo']);
        } 

        if (empty($title)) {
            $_SESSION["Error_MSG"] = "You must add a book title!";
            $_SESSION["addbook"] = "True";
            header('Location: ../books_settings.php');
            exit();
        }

        if (empty($description)) {
            $_SESSION["Error_MSG"] = "You must add a book description!";
            $_SESSION["addbook"] = "True";
            header('Location: ../books_settings.php');
        }

        if (empty($status)) {
            $_SESSION["Error_MSG"] = "You must add a book status!";
            $_SESSION["addbook"] = "True";
            header('Location: ../books_settings.php');
            exit();

        }

        if (empty($price)) {
            $_SESSION["Error_MSG"] = "You must add a book price!";
            $_SESSION["addbook"] = "True";
            header('Location: ../books_settings.php');
            exit();

        }


        if (empty($condition)) {
            $_SESSION["Error_MSG"] = "You must add a book condition!";
            $_SESSION["addbook"] = "True";
            header('Location: ../books_settings.php');
            exit();
        }

        if(empty($courses)) {
            $_SESSION["Error_MSG"] = "You must choose a book Courses!";
            $_SESSION["addbook"] = "True";
            header('Location: ../books_settings.php');
            exit();
        }

        

        $insert = "";
        if($photo != null) {
            $insert =  "INSERT INTO books (title,price,available,conditions,description,user_id,photo) 
            VALUES ('{$title}',{$price},'{$status}','{$condition}','{$description}',{$user['id']},'{$photo}'); ";        
        }
        else {
        $insert = "INSERT INTO books (title,price,available,conditions,description,user_id) 
                    VALUES ('{$title}',{$price},'{$status}','{$condition}','{$description}',{$user['id']}); ";
        }

        global $con;
        if(!$con->query($insert))
        {
            $_SESSION["Error_MSG"] = "There was a problem in completeing your request  \r\n {$insert}";
            $_SESSION["addbook"] = "True";
            header('Location: ../books_settings.php');
        }
        
        else
        {

            $book_id = $con->lastInsertId();
            foreach($courses as $c) {
                $insert = "INSERT INTO book_course (book_id,course_id) 
                    VALUES ({$book_id},{$c}); ";
                $con->query($insert);
            }

            $_SESSION["Success_MSG"] = "book has been added successfully!";
            unset($_SESSION["addbook"]);
            $this->getAll();
        }

    }


    public function editbook() {

        global $con;
        $book_id = $_POST['book_id'];

        $sql = "SELECT * FROM books WHERE id = {$book_id};";
        $result = $con->query($sql);
        $b = $result->fetch(PDO::FETCH_ASSOC);

        $_SESSION['book_id'] = $b['id'];
        $_SESSION['title'] = $b['title'];
        $_SESSION['desc'] = $b['description'];
        $_SESSION['status'] = $b['available'];
        $_SESSION['condition'] = $b['conditions'];
        $_SESSION['price'] = $b['price'];

        $select = "SELECT * FROM courses ;";
        $result = $con->query($select);
        $_SESSION['courses'] = $result->fetchAll(PDO::FETCH_ASSOC);

        $st = $con->prepare("SELECT * From book_course where book_id ={$book_id}");
        $st->execute();
        $_SESSION['book_courses'] = $st->fetchAll(PDO::FETCH_ASSOC);
        

        $_SESSION["editbook"] = "True";
        header('Location: ../books_settings.php');
    }

    public function CompleteEditbook() {
        global $con;
        $book_id = $_POST['book_id'];


        $title = $this->test_input($_POST["title"]);
        $description = $this->test_input($_POST["desc"]);
        $status = $this->test_input($_POST["status"]);
        $condition = $this->test_input($_POST["condition"]);
        $price = $this->test_input($_POST["price"]);
        $courses = $_POST["courses"];
        $photo = null;
        

        if(isset($_FILES['photo']) )
        {
            
            $file_name = $_FILES['photo']['name'];
            $file_type = $_FILES['photo']['type'];
            $file_tmp_name = $_FILES['photo']['tmp_name'];
            $file_size = $_FILES['photo']['size'];
            
            
            if(!empty($file_name)) {
                $target_dir = "../imgs/books/";
            
                if(move_uploaded_file($file_tmp_name,$target_dir.$file_name))
                {
                    $photo = "./imgs/books/".$file_name;
                }

            }
            unset($_FILES['photo']);
        } 

        if (empty($title)) {
            $_SESSION["Error_MSG"] = "You must add a book title!";
            $_SESSION["editbook"] = "True";
            header('Location: ../books_settings.php');
            exit();
        }

        if (empty($description)) {
            $_SESSION["Error_MSG"] = "You must add a book description!";
            $_SESSION["editbook"] = "True";
            header('Location: ../books_settings.php');
        }

        if (empty($status)) {
            $_SESSION["Error_MSG"] = "You must add a book status!";
            $_SESSION["editbook"] = "True";
            header('Location: ../books_settings.php');
            exit();

        }

        if (empty($price)) {
            $_SESSION["Error_MSG"] = "You must add a book price!";
            $_SESSION["editbook"] = "True";
            header('Location: ../books_settings.php');
            exit();

        }


        if (empty($condition)) {
            $_SESSION["Error_MSG"] = "You must add a book condition!";
            $_SESSION["editbook"] = "True";
            header('Location: ../books_settings.php');
            exit();
        }

        if(empty($courses)) {
            $_SESSION["Error_MSG"] = "You must choose a book Courses!";
            $_SESSION["editbook"] = "True";
            header('Location: ../books_settings.php');
            exit();
        }

        

        $update = "";
        if($photo != null) {
            $update = "UPDATE books set title = '{$title}', price = {$price}, available = '{$status}', conditions = '{$condition}', description = '{$description}', photo = '{$photo}' WHERE id = {$book_id}";
                   
        }
        else {
            $update = "UPDATE books set title = '{$title}', price = {$price}, available = '{$status}', conditions = '{$condition}', description = '{$description}' WHERE id = {$book_id}";
        }

        global $con;
        if(!$con->query($update))
        {
            $_SESSION["Error_MSG"] = "There was a problem in completeing your request  \r\n {$update}";
            $_SESSION["editbook"] = "True";
            header('Location: ../books_settings.php');
        }
        
        else
        {

            $st = $con->prepare("DELETE From book_course where book_id ={$book_id}");
            $st->execute();
            foreach($courses as $c) {
                $insert = "INSERT INTO book_course (book_id,course_id) 
                    VALUES ({$book_id},{$c}); ";
                $con->query($insert);
            }

            $_SESSION["Success_MSG"] = "book has been added successfully!";
            unset($_SESSION["editbook"]);
            $this->getAll();
        }
        
    }

    public function deletebook() {
        global $con;
        $book_id = $_POST['book_id'];
        $sql = "SELECT * FROM orders WHERE book_id = {$book_id};";
        $result = $con->query($sql);
        $orders = $result->fetch(PDO::FETCH_ASSOC);

        


        if(!$orders ) {
            $deleteBook = "DELETE FROM books WHERE id={$book_id};";
            if (!$con->query($deleteBook)) {
                $_SESSION["Error_MSG"] = "There was a problem in completing your request sql = $deleteBook";
                header('Location: ../books_settings.php');
            } else {
                $_SESSION["Success_MSG"] = "Your Book has been Deleted successfully!";

                $this->getAll();
            }
        } else {
            $_SESSION["Error_MSG"] = "Cannot delete it because found related orders with it in database ";
            header('Location: ../books_settings.php');
        }
    }

    private function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
}