<?php

require_once 'Connect.php';


session_start();


$registerController = new RegisterControl();

if(isset($_REQUEST["register"])) $registerController->register();
else if(isset($_REQUEST["profile"])) $registerController->profile();
else if(isset($_REQUEST["editProfile"])) $registerController->editProfile();
else if(isset($_REQUEST["CompleteEditProfile"])) $registerController->CompleteEditProfile();
else if(isset($_REQUEST["completeRegisteration"])) $registerController->completeRegistration();
else if(isset($_REQUEST["login"])) $registerController->login();
else if(isset($_REQUEST["skip"])) $registerController->skip();
else if(isset($_REQUEST["logout"])) $registerController->logout();


class RegisterControl{


    
    private $conn;
    
    public function __construct(){
        
        global $con;
        $this->conn = $con;
     
    }


    public function profile() {

        $st = "SELECT * from users where email='{$_SESSION['email']}'";
        $result = $this->conn->query($st);
        $_SESSION['user'] = $result->fetch(PDO::FETCH_ASSOC);
        if(!empty($_SESSION['user']['major_id'])) {
            $st = "SELECT name from majors where id={$_SESSION['user']['major_id']}";
            $result = $this->conn->query($st);
            $major = $result->fetch(PDO::FETCH_ASSOC);
        }
        
        $_SESSION['user']['major_name'] = $major ? $major['name'] : '';
        unset($_SESSION['editprofile']);
        header('location: ../profile.php');
    }


    public function editProfile() {
        $st = "SELECT * from users where email='{$_SESSION['email']}'";
        $result = $this->conn->query($st);
        $_SESSION['user'] = $result->fetch(PDO::FETCH_ASSOC);

        $st = "SELECT * from majors ";
        $result = $this->conn->query($st);
        $_SESSION['majors'] = $result->fetchAll(PDO::FETCH_ASSOC);


        $_SESSION['editprofile'] = 'true';
        header('location: ../profile.php');


    }

    public function CompleteEditProfile() {
        $st = "SELECT * from users where email='{$_SESSION['email']}'";
        $result = $this->conn->query($st);
        $_SESSION['user'] = $result->fetch(PDO::FETCH_ASSOC);


        $email = $this->test_input($_POST["email"]);
        $name = $this->test_input($_POST["name"]);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $photo = null;
        $phone = '';
        $major_id = 0;
        if($_SESSION['user']['type'] != 'admin') {
            $phone = $_POST["phone"];
            $major_id = $_POST["major_id"];
        }

        if (empty($email))
        {
            $_SESSION["Error_MSG"] = "You must choose an Email!";
            $_SESSION['editprofile'] = 'true';
            header('Location: ../profile.php');
        }
        if($email != $_SESSION['user']['email']) {
            if($this->existEmail($email))
            {
                $_SESSION["Error_MSG"] = "The chosen email is already exist";
                $_SESSION['editprofile'] = 'true';
                header('Location: ../profile.php');
            }
            
            else if (!$this->validEmail($email))
            {
                $_SESSION["Error_MSG"] = "The entered email is not valid, please follow this format: name@domain.tld";
                $_SESSION['editprofile'] = 'true';
                header('Location: ../profile.php');
            }
        }
        

        if (empty($name))
        {
            $_SESSION["Error_MSG"] = "You must choose an Name!";
            $_SESSION['editprofile'] = 'true';
            header('Location: ../profile.php');
        }

        if (!empty($password) && !$this->validPassword($password, $confirm_password))
        {
            $_SESSION["Error_MSG"] = "The entered password does not match the confirmed password!";
            
            $_SESSION['editprofile'] = 'true';
            header('Location: ../profile.php');
        }


        $update_user = "UPDATE users SET name = '{$name}' , email = '{$email}'  WHERE id = {$_SESSION['user']['id']}";
        $this->conn->query($update_user);

        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;

        if(isset($_FILES['photo']) && !empty($_FILES['photo']['name']))
        {
            $file_name = $_FILES['photo']['name'];
            $file_type = $_FILES['photo']['type'];
            $file_tmp_name = $_FILES['photo']['tmp_name'];
            $file_size = $_FILES['photo']['size'];
            
            
            $target_dir = "../imgs/profileImages/";
            
            if(move_uploaded_file($file_tmp_name,$target_dir.$file_name))
            {
                $photo = "./imgs/profileImages/".$file_name;
            }

            unset($_FILES['photo']);
            if($photo != null) {
                $update_user = "UPDATE users SET photo = '{$photo}' WHERE id = {$_SESSION['user']['id']}";
                $this->conn->query($update_user);
                $_SESSION["photo"] = $photo;
            }
        } 

        if($password) {
            $hash = password_hash($password,PASSWORD_DEFAULT);
            $update_user = "UPDATE users SET password = '{$hash}' WHERE id = {$_SESSION['user']['id']}";
            $this->conn->query($update_user);

        }


        if($phone != '') {
            $update_user = "UPDATE users SET phone = '{$phone}' WHERE id = {$_SESSION['user']['id']}";
            $this->conn->query($update_user);
            $_SESSION["phone"] = $phone;

        }

        if($major_id != 0) {
            $update_user = "UPDATE users SET major_id = {$major_id} WHERE id = {$_SESSION['user']['id']}";
            $this->conn->query($update_user);
            $_SESSION["major_id"] = $major_id;
        }

        $_SESSION["Success_MSG"] = "You have Updated in succesfully!, {$email}";
        $this->profile();

        


    }

    function skip() {
        header('Location: ../index.php');
    }
    
    function register()
    {
        $type = $this->test_input($_POST["type"]);
        $name = $this->test_input($_POST["name"]);
        $email = $this->test_input($_POST["email"]);
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        
        if (empty($email))
        {
            $_SESSION["Error_MSG"] = "You must choose an Email!";
            
            header('Location: ../index.php#register');
        }
        
        else if (empty($password))
        {
            $_SESSION["Error_MSG"] = "You must choose a password!";
            
            header('Location: ../index.php#register');
        }
        
        
        
        
        else if($this->existEmail($email))
        {
            $_SESSION["Error_MSG"] = "The chosen email is already registered";
            
            header('Location: ../index.php#register');
        }
        
        else if (!$this->validEmail($email))
        {
            $_SESSION["Error_MSG"] = "The entered email is not valid, please follow this format: name@domain.tld";
            
            header('Location: ../index.php#register');
        }
        

        
        
        
        else if (!$this->validPassword($password, $confirm_password))
        {
            $_SESSION["Error_MSG"] = "The entered password does not match the confirmed password!";
            
            header('Location: ../index.php#register');
        }
        
        else
        {
            
            $hash = password_hash($password,PASSWORD_DEFAULT);
            
            $insert_new_user = "INSERT INTO users (name,email, password, type) VALUES ('{$name}','{$email}', '{$hash}', '{$type}'); ";
            if(!$this->conn->query($insert_new_user))
            {
                $_SESSION["Error_MSG"] = "There was a problem in completeing your request  \r\n {$insert_new_user}";
                                
                header('Location: ../index.php#register');
            }
            
            else
            {
                
                $_SESSION['loged-in'] = true;
                $_SESSION["name"] = $name;
                $_SESSION["Success_MSG"] = "You have been succesfully registered in our website, {$email}!";
                $_SESSION["email"] = $email;
                $_SESSION["CompleteRegistration"] = "True";
                $_SESSION["type"] = $type;
                    
                header('Location: ../completeRegisteration.php');
            }
            
        }

    }
    
    function completeRegistration()
    {
        $email = $_SESSION["email"];
        
        $phone = $this->test_input($_POST["phone"]);
        
        if(empty($phone)) $phone = ''; else $phone = "'$phone'";


        $photo = null;
        
        if(isset($_FILES['photo']))
        {
            $file_name = $_FILES['photo']['name'];
            $file_type = $_FILES['photo']['type'];
            $file_tmp_name = $_FILES['photo']['tmp_name'];
            $file_size = $_FILES['photo']['size'];
            
            
            $target_dir = "../imgs/profileImages/";
            
            if(move_uploaded_file($file_tmp_name,$target_dir.$file_name))
            {
                $photo = "./imgs/profileImages/".$file_name;
            }

            unset($_FILES['photo']);
        } 
        
        $update_user = "UPDATE users SET photo = '{$photo}', phone = '{$phone}' WHERE email = {$email}; ";
        
        if(!$this->conn->query($update_user))
        {
            $_SESSION["Error_MSG"] = "There was a problem in completeing your request.  {$update_user}";
        }
        else 
        {
            $_SESSION["phone"] = $phone ;
            $_SESSION["photo"] = $photo ;
            $_SESSION["Success_MSG"] = "You have succesfully completed your registeration in our website, {$email}!";
            $_SESSION["email"] = $email;
            
            unset($_SESSION["CompleteRegistration"]);
            
           header('Location: ../index.php');
        }

    }
    
    function login()
    {
        $email = $this->test_input($_POST["email"]);
        $password = $_POST["password"];
        
        if (empty($email))
        {
            $_SESSION["Error_MSG"] = "You must choose an Email!";
            
            header('Location: ../login.php');
        }
        
        else if (empty($password))
        {
            $_SESSION["Error_MSG"] = "You must choose a password!";
            
            header('Location: ../login.php');
        }
        
        else if (!$this->validEmail($email))
        {
            $_SESSION["Error_MSG"] = "The entered email is not valid, please follow this format: name@domain.tld";
            
            header('Location: ../login.php');
        }
                
        else
        {
            
            $get_user = "SELECT * FROM users WHERE email = '{$email}'; ";
            $result = $this->conn->query($get_user);
            $user = $result->fetch(PDO::FETCH_ASSOC);
            if(!$user)
            {
                $_SESSION["Error_MSG"] = "Incorrect Email!";
                
                header('Location: ../login.php');
            }
            
            else
            {
                
                $hash = $user["password"];
                       
                // Verify the hash against the password entered
                $verify = password_verify($password, $hash);
                
                // Print the result depending if they match
                if (!$verify) {
                    
                    //echo 'Incorrect Password!';
                    $_SESSION["Error_MSG"] = "Incorrect Password!";
                    header('Location: ../login.php');
                    
                }
                else 
                {
      
                    $_SESSION["Success_MSG"] = "You have loged in succesfully!, {$email}";
                    $_SESSION["loged-in"] = "True";
                    $_SESSION["name"] = $user["name"];
                    $_SESSION["type"] = $user["type"];
                    $_SESSION["phone"] = $user["Phone"];
                    $_SESSION["photo"] = $user["photo"];
                    $_SESSION["email"] = $email;
               
                    header('Location: ../index.php');
                }
            }
            
        }
    }
    
 
    function logout()
    {
        $email = $_SESSION["email"];

        session_destroy();
     
        $_SESSION["Success_MSG"] = "You have loged-out succesfully!, {$email}!";
        
        header('Location: ../index.php');
    }
    
    //Source: https://www.w3schools.com/php/php_form_validation.asp
    private function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    private function existEmail($email)
    {
        $sql_check_email = "SELECT * FROM users where Email ='{$email}'";
        $Result = $this->conn->query($sql_check_email);
        $user = $Result->fetch(PDO::FETCH_ASSOC);
        if ($user)  return true; 
        else  return false;
    }
    
    private function validEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
        else  return false;
    }
    
    private function validPassword($password, $confirm_password)
    {
        if ($password == $confirm_password) return true;
        else  return false;
    }
    
        
}

?>