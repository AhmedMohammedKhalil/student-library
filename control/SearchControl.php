<?php

require_once 'Connect.php';


if (!isset($_SESSION)) session_start();


$searchControl = new SearchControl();

if(isset($_REQUEST["makeSearch"])) {$searchControl->makeSearch();}


class SearchControl{

    public function makeSearch() {
        global $con;
        $search = $this->test_input($_POST['search']);
        $order = $_POST['order'];
        $type = $_POST['type'];
        unset($_SESSION['search']);

        if (empty($search)) {
            header('Location: ../search.php');
            exit();
        } 

        $string = "like '%$search%'";
        $ordering = $order == 1 ? 'ASC' : 'DESC';
        $select = "";
        switch($type) {
            case 1:
                $select = "SELECT distinct b.* from univerisities as u, book_course as bc, courses as c, majors as m , books as b
                            where u.id = m.uni_id and m.id = c.major_id and c.id = bc.course_id and bc.book_id = b.id 
                            and u.name {$string} ORDER BY b.id {$ordering};";
                break;
            case 2:
                $select = "SELECT distinct b.* from book_course as bc, courses as c, majors as m , books as b
                            where m.id = c.major_id and c.id = bc.course_id and bc.book_id = b.id 
                            and m.name {$string} ORDER BY b.id {$ordering};";
                break;
            case 3:
                $select = "SELECT distinct b.* from book_course as bc, courses as c, books as b
                            where c.id = bc.course_id and bc.book_id = b.id 
                            and c.name {$string} ORDER BY b.id {$ordering};";
                break;
            case 4:
                $select = "SELECT * from  books
                            where title {$string} ORDER BY id {$ordering};";
                    break;
            default:
                break;
        }

        $result = $con->query($select);
        $_SESSION['search'] = $result->fetchAll(PDO::FETCH_ASSOC);

        if(isset($_SESSION['email'])) {
            $select = "SELECT id FROM users where email ='{$_SESSION['email']}';";
            $result = $con->query($select);
            $_SESSION['user_id'] = $result->fetch(PDO::FETCH_ASSOC)['id']; 
        }

        $_SESSION['books'] = $_SESSION['search'];
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

        header('location: ../search.php');


    }



    private function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
}