<?php

require_once 'Connect.php';


if (!isset($_SESSION)) session_start();


$aboutControl = new AboutControl();
if (isset($_REQUEST["getAll"])) {
    $aboutControl->getAll();
}
if(isset($_REQUEST["Aboutsetting"])) {$aboutControl->Aboutsetting();}
if(isset($_REQUEST["editAbout"])) {$aboutControl->editAbout();}


class AboutControl{

    public function getAll() {
        global $con;
        $st = $con->prepare('SELECT * From aboutus');
        $st->execute();
        $_SESSION['aboutus'] = $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Aboutsetting() {
        global $con;
        $st = $con->prepare('SELECT * From aboutus');
        $st->execute();
        $_SESSION['aboutus'] = $st->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        unset($_SESSION["para_1_text"], $_SESSION["para_2_text"]);
        foreach($_SESSION['aboutus'] as $a) {
            $var_text = "para_" . $i . "_text";
            $_SESSION[$var_text] = $a['text'];
            $i++;
        }
        header('location: ../aboutus_settings.php');
    }


    public function editAbout() {

        global $con;
        $this->delAboutUs();
        for ($i = 1; $i <= 2; $i++) {
            $var_text = "para_" . $i . "_text";
            $text = htmlspecialchars_decode($_POST[$var_text]);
            $text = trim(strip_tags($text), "&nbsp;");
            if(!empty($text)) {
                $st = $con->prepare("INSERT INTO aboutus (Text) VALUES ('{$_POST[$var_text]}')");

                if (!$st->execute()) {
                    $this->Aboutsetting();
                }
            }
            
        }
        $this->Aboutsetting();
    }

    public function delAboutUs() {
        global $con;
        $st = $con->prepare('DELETE FROM aboutus');
        $st->execute();
    }
}