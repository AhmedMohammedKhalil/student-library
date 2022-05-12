<?php

require_once 'Connect.php';


if (!isset($_SESSION)) session_start();


$univeristyControl = new UniveristyControl();
if (isset($_REQUEST["getAll"])) {
    $univeristyControl->getAll();
}
if(isset($_REQUEST["addUniveristy"])) {$univeristyControl->addUniveristy();}
if(isset($_REQUEST["editUniveristy"])) {$univeristyControl->editUniveristy();}
if(isset($_REQUEST["CompleteAddUniveristy"])) {$univeristyControl->CompleteAddUniveristy();}
if(isset($_REQUEST["CompleteEditUniveristy"])) {$univeristyControl->CompleteEditUniveristy();}
if(isset($_REQUEST["deleteUniveristy"])) {$univeristyControl->deleteUniveristy();}


class UniveristyControl{

    public function getAll() {
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
        $_SESSION["showUniveristies"] = "True";
        unset($_SESSION["addUniveristy"], $_SESSION["editUniveristy"]);
        header('Location: ../univerisities_settings.php');
    }


    public function addUniveristy() {
        $_SESSION["addUniveristy"] = "True";
        header('Location: ../univerisities_settings.php');

    }


    public function CompleteAddUniveristy() {
        $name = $this->test_input($_POST["univeristy_name"]);
        $description = $this->test_input($_POST["univeristy_desc"]);
        $photo = null;
        
        if(isset($_FILES['photo']))
        {
            $file_name = $_FILES['photo']['name'];
            $file_type = $_FILES['photo']['type'];
            $file_tmp_name = $_FILES['photo']['tmp_name'];
            $file_size = $_FILES['photo']['size'];
            
            if(!empty($file_name)) {

                $target_dir = "../imgs/univeristies/";
                
                if(move_uploaded_file($file_tmp_name,$target_dir.$file_name))
                {
                    $photo = "./imgs/univeristies/".$file_name;
                }
            }

            unset($_FILES['photo']);
        } 


        if (empty($name)) {
            $_SESSION["Error_MSG"] = "You must add a univeristy Name!";
            $_SESSION["addUniveristy"] = "True";
            header('Location: ../univerisities_settings.php');
        }


        if (empty($description)) {
            $_SESSION["Error_MSG"] = "You must add a univeristy description!";
            $_SESSION["addUniveristy"] = "True";
            header('Location: ../univerisities_settings.php');
        }

        $insert = "";
        if($photo != null) {
            $insert = "INSERT INTO univerisities (name,description, photo) VALUES ('{$name}','{$description}', '{$photo}'); ";
        }
        else {
            $insert = "INSERT INTO univerisities (name,description) VALUES ('{$name}','{$description}'); ";
        } 

        global $con;
        if(!$con->query($insert))
        {
            $_SESSION["Error_MSG"] = "There was a problem in completeing your request  \r\n {$insert}";
            $_SESSION["addUniveristy"] = "True";
            header('Location: ../univerisities_settings.php');
        }
        
        else
        {
            $_SESSION["Success_MSG"] = "Univeristy has been added successfully!";
            unset($_SESSION["addUniveristy"]);
            $this->getAll();
        }

    }


    public function editUniveristy() {

        global $con;
        $univeristy_id = $_POST['univeristy_id'];

        $sql = "SELECT * FROM univerisities WHERE id = {$univeristy_id};";
        $result = $con->query($sql);
        $uni = $result->fetch(PDO::FETCH_ASSOC);

        $_SESSION['univeristy_id'] = $uni['id'];
        $_SESSION['univeristy_name'] = $uni['name'];
        $_SESSION['univeristy_desc'] = $uni['description'];

        $_SESSION["editUniveristy"] = "True";
        header('Location: ../univerisities_settings.php');
    }

    public function CompleteEditUniveristy() {
        $univeristy_id = $this->test_input($_POST["univeristy_id"]);
        $name = $this->test_input($_POST["univeristy_name"]);
        $description = $this->test_input($_POST["univeristy_desc"]);
        $photo = null;

        global $con;
        $sql = "SELECT * FROM univerisities WHERE id = {$univeristy_id};";
        $result = $con->query($sql);
        $uni = $result->fetch(PDO::FETCH_ASSOC);
        
        if(isset($_FILES['photo']))
        {
            $file_name = $_FILES['photo']['name'];
            $file_type = $_FILES['photo']['type'];
            $file_tmp_name = $_FILES['photo']['tmp_name'];
            $file_size = $_FILES['photo']['size'];
            
            
            $target_dir = "../imgs/univeristies/";
            
            if(move_uploaded_file($file_tmp_name,$target_dir.$file_name))
            {
                $photo = "./imgs/univeristies/".$file_name;
            }
           
            unset($_FILES['photo']);
        } 


        if (empty($name)) {
            $_SESSION["Error_MSG"] = "You must add a univeristy Name!";
            $_SESSION["editUniveristy"] = "True";
            header('Location: ../univerisities_settings.php');
        }


        if (empty($description)) {
            $_SESSION["Error_MSG"] = "You must add a univeristy description!";
            $_SESSION["editUniveristy"] = "True";
            header('Location: ../univerisities_settings.php');
        }

        $update = "";
        if($photo != null) {
            $update = "UPDATE univerisities SET name='{$name}' ,description='{$description}' ,photo='{$photo}' WHERE id={$univeristy_id};";
        }
        else {
            $update = "UPDATE univerisities SET name='{$name}' ,description='{$description}' WHERE id={$univeristy_id};";
        } 

        global $con;
        if(!$con->query($update))
        {
            $_SESSION["Error_MSG"] = "There was a problem in completeing your request  \r\n {$update}";
            $_SESSION["editUniveristy"] = "True";
            header('Location: ../univerisities_settings.php');
        }
        
        else
        {
            $_SESSION["Success_MSG"] = "Univeristy has been updated successfully!";
            unset($_SESSION["editUniveristy"]);
            $this->getAll();
        }

    }

    public function deleteUniveristy() {
        global $con;
        $univeristy_id = $_POST['univeristy_id'];
        $sql = "SELECT * FROM majors WHERE uni_id = {$univeristy_id};";
        $result = $con->query($sql);
        $major = $result->fetch(PDO::FETCH_ASSOC);
        if(!$major) {
            $updateUniveristy = "DELETE FROM univerisities WHERE id={$univeristy_id};";
            if (!$con->query($updateUniveristy)) {
                $_SESSION["Error_MSG"] = "There was a problem in completing your request sql = $updateUniveristy";
                header('Location: ../univerisities_settings.php');
            } else {
                $_SESSION["Success_MSG"] = "Your univeristy has been Deleted successfully!";

                $this->getAll();
            }
        } else {
            $_SESSION["Error_MSG"] = "Cannot delete it befor remove related majors in database ";
            header('Location: ../univerisities_settings.php');
        }
        
    }


    private function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    
}