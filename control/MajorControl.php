<?php

require_once 'Connect.php';


if (!isset($_SESSION)) session_start();


$majorControl = new majorControl();
if (isset($_REQUEST["getAll"])) {
    $majorControl->getAll();
}
if(isset($_REQUEST["addmajor"])) {$majorControl->addmajor();}
if(isset($_REQUEST["editmajor"])) {$majorControl->editmajor();}
if(isset($_REQUEST["CompleteAddmajor"])) {$majorControl->CompleteAddmajor();}
if(isset($_REQUEST["CompleteEditmajor"])) {$majorControl->CompleteEditmajor();}
if(isset($_REQUEST["deletemajor"])) {$majorControl->deletemajor();}


class majorControl{

    public function getAll() {
        global $con;

        $select = "SELECT * FROM univerisities ;";
        $result = $con->query($select);
        $_SESSION['unis'] = $result->fetch(PDO::FETCH_ASSOC); 

        if(!$_SESSION['unis']) {
            $_SESSION["Error_MSG"] = "You must add univerisity first";
            header('Location: ../univerisities_settings.php');
            exit();
        }

        $st = $con->prepare('SELECT m.* , u.name as uni_name From majors as m join univerisities as u on u.id = m.uni_id ');
        $st->execute();
        $_SESSION['majors'] = $st->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        foreach($_SESSION['majors'] as $m) {
            $var_major_id = "major_" . $i . "_id";
            $var_major_name = "major_" . $i . "_name";
            $var_major_desc = "major_" . $i . "_desc";
            $var_major_uni_name = "major_" . $i . "_uni_name";

            $_SESSION[$var_major_id] = $m['id'];
            $_SESSION[$var_major_name] = $m['name'];
            $_SESSION[$var_major_desc] = $m['description'];
            $_SESSION[$var_major_uni_name] = $m['uni_name'];

            $i++;
        }

        $_SESSION['Majors_num'] = $i - 1;
        $_SESSION["showMajors"] = "True";
        unset($_SESSION["addmajor"], $_SESSION["editmajor"]);
        header('Location: ../majors_settings.php');
    }


    public function addmajor() {

        $_SESSION["addmajor"] = "True";
        global $con;
        $select = "SELECT * FROM univerisities ;";
        $result = $con->query($select);
        $_SESSION['unis'] = $result->fetchAll(PDO::FETCH_ASSOC);
       
        header('Location: ../majors_settings.php');

    }


    public function CompleteAddmajor() {
        $name = $this->test_input($_POST["major_name"]);
        $description = $this->test_input($_POST["major_desc"]);
        $uni_id = $_POST["uni_id"];



        if (empty($name)) {
            $_SESSION["Error_MSG"] = "You must add a major Name!";
            $_SESSION["addmajor"] = "True";
            header('Location: ../majors_settings.php');
        }


        if (empty($description)) {
            $_SESSION["Error_MSG"] = "You must add a major description!";
            $_SESSION["addmajor"] = "True";
            header('Location: ../majors_settings.php');
        }

        
        $insert = "INSERT INTO majors (name,description,uni_id) VALUES ('{$name}','{$description}',{$uni_id}); ";
        

        global $con;
        if(!$con->query($insert))
        {
            $_SESSION["Error_MSG"] = "There was a problem in completeing your request  \r\n {$insert}";
            $_SESSION["addmajor"] = "True";
            header('Location: ../majors_settings.php');
        }
        
        else
        {
            $_SESSION["Success_MSG"] = "major has been added successfully!";
            unset($_SESSION["addmajor"]);
            $this->getAll();
        }

    }


    public function editmajor() {

        global $con;
        $major_id = $_POST['major_id'];

        $sql = "SELECT * FROM majors WHERE id = {$major_id};";
        $result = $con->query($sql);
        $m = $result->fetch(PDO::FETCH_ASSOC);


        $select = "SELECT * FROM univerisities ;";
        $result = $con->query($select);
        $_SESSION['unis'] = $result->fetchAll(PDO::FETCH_ASSOC);


        $_SESSION['major_id'] = $m['id'];
        $_SESSION['major_name'] = $m['name'];
        $_SESSION['major_desc'] = $m['description'];
        $_SESSION['major_uni_id'] = $m['uni_id'];


        $_SESSION["editmajor"] = "True";
        header('Location: ../majors_settings.php');
    }

    public function CompleteEditmajor() {
        $major_id = $this->test_input($_POST["major_id"]);
        $name = $this->test_input($_POST["major_name"]);
        $description = $this->test_input($_POST["major_desc"]);
        $uni_id = $_POST["uni_id"];


        global $con;
        $sql = "SELECT * FROM majors WHERE id = {$major_id};";
        $result = $con->query($sql);
        $m = $result->fetch(PDO::FETCH_ASSOC);
        


        if (empty($name)) {
            $_SESSION["Error_MSG"] = "You must add a major Name!";
            $_SESSION["editmajor"] = "True";
            header('Location: ../majors_settings.php');
        }


        if (empty($description)) {
            $_SESSION["Error_MSG"] = "You must add a major description!";
            $_SESSION["editmajor"] = "True";
            header('Location: ../majors_settings.php');
        }

        $update = "UPDATE majors SET name='{$name}' ,description='{$description}' ,uni_id={$uni_id} WHERE id={$major_id};";
        

        global $con;
        if(!$con->query($update))
        {
            $_SESSION["Error_MSG"] = "There was a problem in completeing your request  \r\n {$update}";
            $_SESSION["editmajor"] = "True";
            header('Location: ../majors_settings.php');
        }
        
        else
        {
            $_SESSION["Success_MSG"] = "major has been updated successfully!";
            unset($_SESSION["editmajor"]);
            $this->getAll();
        }

    }

    public function deletemajor() {
        global $con;
        $major_id = $_POST['major_id'];
        $sql = "SELECT * FROM courses WHERE major_id = {$major_id};";
        $result = $con->query($sql);
        $courses = $result->fetch(PDO::FETCH_ASSOC);

        $sql2 = "SELECT * FROM users WHERE major_id = {$major_id};";
        $result = $con->query($sql);
        $users = $result->fetch(PDO::FETCH_ASSOC);


        if(!$courses && !$users ) {
            $updatemajor = "DELETE FROM majors WHERE id={$major_id};";
            if (!$con->query($updatemajor)) {
                $_SESSION["Error_MSG"] = "There was a problem in completing your request sql = $updatemajor";
                header('Location: ../majors_settings.php');
            } else {
                $_SESSION["Success_MSG"] = "Your major has been Deleted successfully!";

                $this->getAll();
            }
        } else {
            $_SESSION["Error_MSG"] = "Cannot delete it befor remove related courses and users in database ";
            header('Location: ../majors_settings.php');
        }
        
    }


    private function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
}