<?php

require_once 'Connect.php';


if (!isset($_SESSION)) session_start();


$HomeControl = new HomeControl();
if (isset($_REQUEST["getUniverisities"])) {
    $HomeControl->getUniverisities();
}

if (isset($_REQUEST["getMajors"])) {
    $HomeControl->getMajors();
}

if (isset($_REQUEST["getCourses"])) {
    $HomeControl->getCourses();
}

if (isset($_REQUEST["getBooks"])) {
    $HomeControl->getBooks();
}

if (isset($_REQUEST["getAllBooks"])) {
    $HomeControl->getAllBooks();
}



class HomeControl{
    public function getUniverisities() {
        global $con;
        $st = $con->prepare('SELECT * From univerisities');
        $st->execute();
        $_SESSION['univerisities'] = $st->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        foreach($_SESSION['univerisities'] as $u) {
            $var_Univeristy_id = "Univeristy_" . $i . "_id";
            $var_Univeristy_photo = "Univeristy_" . $i . "_photo";
            $var_Univeristy_name = "Univeristy_" . $i . "_name";
            $var_Univeristy_desc = "Univeristy_" . $i . "_desc";
            $_SESSION[$var_Univeristy_id] = $u['id'];
            $_SESSION[$var_Univeristy_photo] = $u['photo'];
            $_SESSION[$var_Univeristy_name] = $u['name'];
            $_SESSION[$var_Univeristy_desc] = $u['description'];
            $i++;
        }

        $_SESSION['Univeristies_num'] = $i - 1;
        header('Location: ../unverisities.php');
    }


    public function getMajors() {
        $uni_id = $_POST['univeristy_id'];
        global $con;
        $st = $con->prepare("SELECT * From majors where uni_id = {$uni_id} ");
        $st->execute();
        $_SESSION['majors'] = $st->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        foreach($_SESSION['majors'] as $m) {
            $var_major_id = "major_" . $i . "_id";
            $var_major_name = "major_" . $i . "_name";
            $var_major_desc = "major_" . $i . "_desc";

            $_SESSION[$var_major_id] = $m['id'];
            $_SESSION[$var_major_name] = $m['name'];
            $_SESSION[$var_major_desc] = $m['description'];
            $i++;
        }

        $_SESSION['majors_num'] = $i - 1;
        header('Location: ../majors.php');
    }


    public function getCourses() {
        $major_id = $_POST['major_id'];
        global $con;
        $st = $con->prepare("SELECT * From courses where major_id = {$major_id} ");
        $st->execute();
        $_SESSION['courses'] = $st->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        foreach($_SESSION['courses'] as $c) {
            $var_course_id = "course_" . $i . "_id";
            $var_course_name = "course_" . $i . "_name";
            $var_course_doctors= "course_" . $i . "_doctors";

            $_SESSION[$var_course_id] = $c['id'];
            $_SESSION[$var_course_name] = $c['name'];

            $st = $con->prepare("SELECT name From doctor_names where course_id ={$c['id']}");
            $st->execute();
            $_SESSION[$var_course_doctors] = $st->fetchAll(PDO::FETCH_ASSOC);

            $i++;
        }

        $_SESSION['courses_num'] = $i - 1;
        header('Location: ../courses.php');
    }


    public function getBooks() {
        global $con;
        if(isset($_SESSION['email'])) {
            $select = "SELECT id FROM users where email ='{$_SESSION['email']}';";
            $result = $con->query($select);
            $_SESSION['user_id'] = $result->fetch(PDO::FETCH_ASSOC)['id']; 
        }
        
        unset($_SESSION['recomended']);

        $course_id = $_POST['course_id'];
        $st = $con->prepare("SELECT b.* From book_course as bc join books as b on b.id = bc.book_id where course_id = {$course_id} ");
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
            $var_book_owner_id = "book_" . $i . "_owner_id";

            $_SESSION[$var_book_id] = $b['id'];
            $_SESSION[$var_book_title] = $b['title'];
            $_SESSION[$var_book_photo] = $b['photo'];
            $_SESSION[$var_book_description] = $b['description'];
            $_SESSION[$var_book_status] = $b['available'];
            $_SESSION[$var_book_condition] = $b['conditions'];
            $_SESSION[$var_book_price] = $b['price'];
            $_SESSION[$var_book_owner_id] = $b['user_id'];

            $i++;
        }

        $_SESSION['books_num'] = $i - 1;
        header('Location: ../books.php');
    }


    public function getAllBooks() {
        global $con;
        unset($_SESSION['recomended']);
        if(isset($_SESSION['email'])) {
            $select = "SELECT id FROM users where email ='{$_SESSION['email']}';";
            $result = $con->query($select);
            $user = $result->fetch(PDO::FETCH_ASSOC); 
            $_SESSION['major_id'] = $user['major_id'];
            $_SESSION['user_id'] = $user['id']; 
        }


        $st = $con->prepare("SELECT * From books ");
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
            $var_book_owner_id = "book_" . $i . "_owner_id";

            $_SESSION[$var_book_id] = $b['id'];
            $_SESSION[$var_book_title] = $b['title'];
            $_SESSION[$var_book_photo] = $b['photo'];
            $_SESSION[$var_book_description] = $b['description'];
            $_SESSION[$var_book_status] = $b['available'];
            $_SESSION[$var_book_condition] = $b['conditions'];
            $_SESSION[$var_book_price] = $b['price'];
            $_SESSION[$var_book_owner_id] = $b['user_id'];


            $i++;
        }

        $_SESSION['books_num'] = $i - 1;



        // for recommend books

        if(isset($_SESSION['major_id']) && !empty($_SESSION['major_id'])) {
            $select = "SELECT distinct b.* from book_course as bc, courses as c, majors as m , books as b
                        where m.id = c.major_id and c.id = bc.course_id and bc.book_id = b.id 
                        and m.id = {$_SESSION['major_id']} ;";
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

            $_SESSION['r_books_num'] = $i - 1;
        }


        header('Location: ../books.php');
    }
 }