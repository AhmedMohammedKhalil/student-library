<?php

require_once 'Connect.php';


if (!isset($_SESSION)) session_start();


$courseControl = new courseControl();
if (isset($_REQUEST["getAll"])) {
    $courseControl->getAll();
}
if(isset($_REQUEST["addcourse"])) {$courseControl->addcourse();}
if(isset($_REQUEST["editcourse"])) {$courseControl->editcourse();}
if(isset($_REQUEST["CompleteAddcourse"])) {$courseControl->CompleteAddcourse();}
if(isset($_REQUEST["CompleteEditcourse"])) {$courseControl->CompleteEditcourse();}
if(isset($_REQUEST["deletecourse"])) {$courseControl->deletecourse();}


class courseControl{

    public function getAll() {
        global $con;

        $select = "SELECT * FROM majors ;";
        $result = $con->query($select);
        $_SESSION['majors'] = $result->fetch(PDO::FETCH_ASSOC); 

        if(!$_SESSION['majors']) {
            $_SESSION["Error_MSG"] = "You must add major first";
            header('Location: ../majors_settings.php');
            exit();
        }

        $st = $con->prepare('SELECT c.* , m.name as major_name From courses as c join majors as m on m.id = c.major_id ');
        $st->execute();
        $_SESSION['courses'] = $st->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        foreach($_SESSION['courses'] as $c) {
            $var_course_id = "course_" . $i . "_id";
            $var_course_name = "course_" . $i . "_name";
            $var_course_major_name = "course_" . $i . "_major_name";
            $var_course_doctors= "course_" . $i . "_doctors";

            $_SESSION[$var_course_id] = $c['id'];
            $_SESSION[$var_course_name] = $c['name'];
            $_SESSION[$var_course_major_name] = $c['major_name'];

            $st = $con->prepare("SELECT name From doctor_names where course_id ={$c['id']}");
            $st->execute();
            $_SESSION[$var_course_doctors] = $st->fetchAll(PDO::FETCH_ASSOC);

            $i++;
        }

        $_SESSION['courses_num'] = $i - 1;
        $_SESSION["showcourses"] = "True";
        unset($_SESSION["addcourse"], $_SESSION["editcourse"]);
        header('Location: ../courses_settings.php');
    }


    public function addcourse() {

        $_SESSION["addcourse"] = "True";
        global $con;
        $select = "SELECT * FROM majors ;";
        $result = $con->query($select);
        $_SESSION['majors'] = $result->fetchAll(PDO::FETCH_ASSOC);
       
        header('Location: ../courses_settings.php');

    }


    public function CompleteAddcourse() {
        $name = $this->test_input($_POST["course_name"]);
        $major_id = $_POST["major_id"];
        $doctor_1_name = $this->test_input($_POST["doctor_1_name"]);
        $doctor_2_name = $this->test_input($_POST["doctor_2_name"]);
        $doctor_3_name = $this->test_input($_POST["doctor_3_name"]);



        if (empty($name)) {
            $_SESSION["Error_MSG"] = "You must add a course Name!";
            $_SESSION["addcourse"] = "True";
            header('Location: ../courses_settings.php');
        }


        

        if (empty($doctor_1_name)) {
            $_SESSION["Error_MSG"] = "You must add a doctor 1 Name!. its Required";
            $_SESSION["addcourse"] = "True";
            header('Location: ../courses_settings.php');
        }
        
        $insert = "INSERT INTO courses (name,major_id) VALUES ('{$name}',{$major_id}); ";
        

        global $con;
        if(!$con->query($insert))
        {
            $_SESSION["Error_MSG"] = "There was a problem in completeing your request  \r\n {$insert}";
            $_SESSION["addcourse"] = "True";
            header('Location: ../courses_settings.php');
        }
        
        else
        {

            $course_id = $con->lastInsertId();
            $insert = "INSERT INTO doctor_names (name,course_id) VALUES ('{$doctor_1_name}',{$course_id}); ";
            $con->query($insert);
            if(!empty($doctor_2_name)) {
                $insert = "INSERT INTO doctor_names (name,course_id) VALUES ('{$doctor_2_name}',{$course_id}); ";
                $con->query($insert);
            }
            if(!empty($doctor_3_name)) {
                $insert = "INSERT INTO doctor_names (name,course_id) VALUES ('{$doctor_3_name}',{$course_id}); ";
                $con->query($insert);
            }

            $_SESSION["Success_MSG"] = "course has been added successfully!";
            unset($_SESSION["addcourse"]);
            $this->getAll();
        }

    }


    public function editcourse() {

        global $con;
        $course_id = $_POST['course_id'];

        $sql = "SELECT * FROM courses WHERE id = {$course_id};";
        $result = $con->query($sql);
        $c = $result->fetch(PDO::FETCH_ASSOC);


        $select = "SELECT * FROM majors ;";
        $result = $con->query($select);
        $_SESSION['majors'] = $result->fetchAll(PDO::FETCH_ASSOC);

        $st = $con->prepare("SELECT name From doctor_names where course_id ={$course_id}");
        $st->execute();
        $doctors_name = $st->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        foreach($doctors_name as $doc) {
            $var_text = 'doctor_'.$i.'_name';
            $_SESSION[$var_text] = $doc['name'];
            $i++;
        }

        $_SESSION['course_id'] = $c['id'];
        $_SESSION['course_name'] = $c['name'];
        $_SESSION['course_major_id'] = $c['major_id'];



        $_SESSION["editcourse"] = "True";
        header('Location: ../courses_settings.php');
    }

    public function CompleteEditcourse() {
        $course_id = $this->test_input($_POST["course_id"]);
        $name = $this->test_input($_POST["course_name"]);
        $major_id = $_POST["major_id"];
        $doctor_1_name = $this->test_input($_POST["doctor_1_name"]);
        $doctor_2_name = $this->test_input($_POST["doctor_2_name"]);
        $doctor_3_name = $this->test_input($_POST["doctor_3_name"]);

        global $con;
        $st = $con->prepare("DELETE From doctor_names where course_id ={$course_id}");
        $st->execute();
        




        if (empty($name)) {
            $_SESSION["Error_MSG"] = "You must add a course Name!";
            $_SESSION["editcourse"] = "True";
            header('Location: ../courses_settings.php');
        }

        if (empty($doctor_1_name)) {
            $_SESSION["Error_MSG"] = "You must add a doctor 1 Name!. its Required";
            $_SESSION["addcourse"] = "True";
            header('Location: ../courses_settings.php');
        }

        $update = "UPDATE courses SET name='{$name}' ,major_id={$major_id} WHERE id={$course_id};";
        

        global $con;
        if(!$con->query($update))
        {
            $_SESSION["Error_MSG"] = "There was a problem in completeing your request  \r\n {$update}";
            $_SESSION["editcourse"] = "True";
            header('Location: ../courses_settings.php');
        }
        
        else
        {

            $insert = "INSERT INTO doctor_names (name,course_id) VALUES ('{$doctor_1_name}',{$course_id}); ";
            $con->query($insert);
            if(!empty($doctor_2_name)) {
                $insert = "INSERT INTO doctor_names (name,course_id) VALUES ('{$doctor_2_name}',{$course_id}); ";
                $con->query($insert);
            }
            if(!empty($doctor_3_name)) {
                $insert = "INSERT INTO doctor_names (name,course_id) VALUES ('{$doctor_3_name}',{$course_id}); ";
                $con->query($insert);
            }
            $_SESSION["Success_MSG"] = "course has been updated successfully!";
            unset($_SESSION["editcourse"]);
            $this->getAll();
        }

    }

    public function deletecourse() {
        global $con;
        $course_id = $_POST['course_id'];
        $sql = "SELECT * FROM book_course WHERE course_id = {$course_id};";
        $result = $con->query($sql);
        $books = $result->fetch(PDO::FETCH_ASSOC);


        if(!$books ) {
            $st = $con->prepare("DELETE From doctor_names where course_id ={$course_id}");
            $st->execute();

            $updatecourse = "DELETE FROM courses WHERE id={$course_id};";
            if (!$con->query($updatecourse)) {
                $_SESSION["Error_MSG"] = "There was a problem in completing your request sql = $updatecourse";
                header('Location: ../courses_settings.php');
            } else {
                $_SESSION["Success_MSG"] = "Your course has been Deleted successfully!";

                $this->getAll();
            }
        } else {
            $_SESSION["Error_MSG"] = "Cannot delete it befor remove related books in database ";
            header('Location: ../courses_settings.php');
        }
        
    }


    private function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
}