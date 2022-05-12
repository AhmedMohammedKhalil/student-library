<div class="nav">

    <div class="left">
        <ul>
            <li><a href="index.php">Home </a></li>
            <li><a href="./Control/HomeControl.php?getUniverisities">Univeristies</a></li>
            <li><a href="./Control/HomeControl.php?getAllBooks">Books </a></li>
            <li><a href="./search.php">Search </a></li>
        </ul>

    </div>
    <div class="right">
        <a href="./index.php"><img src="imgs/logo.jpg" alt="Logo: student-library logo" class="logo" /></a>
    </div>
</div>
<div class="nav topnav">
    <div class="right">
        <ul>

        <?php if(isset($_SESSION['type']) && $_SESSION["type"] == "admin") {?>

            <li><a href="./Control/AboutControl.php?Aboutsetting"> About Settings </a></li>
            <li><a href="./Control/UniveristyControl.php?getAll"> Univeristy Settings </a></li>
            <li><a href="./Control/MajorControl.php?getAll"> Major Settings </a></li>
            <li><a href="./Control/CourseControl.php?getAll"> Course Settings </a></li>
        <?php } ?>

        <?php if(isset($_SESSION['type']) && ($_SESSION["type"] == "student" || $_SESSION["type"] == "doctor") ) {?>

        <li><a href="./Control/BookControl.php?getAll"> My Books </a></li>
        <li><a href="./Control/OrderControl.php?getBookOrders"> Book Orders </a></li>
        <li><a href="./Control/OrderControl.php?getMyOrders"> My Orders </a></li>
        <?php } ?>

        <?php if(isset($_SESSION["loged-in"])) {?>
            <li><a href="./Control/RegisterControl.php?profile"> Profile </a></li>
            <li><a href="./Control/RegisterControl.php?logout" >Logout</a></li>
        <?php } else {?> 

            <li><a href="login.php"> Login </a></li>
            <li><a href="index.php#register"> Register </a></li>
        <?php }?>
        </ul>

    </div>
</div>
<?php if(isset($_SESSION["loged-in"])) {?>
    
    <div class="nav" style="background-color: #9bb3c5;
">
   
           <div class="left"> 
           
           
          
           <ul>
               <li>
                <?php  if (isset($_SESSION['photo']) && $_SESSION["photo"] != null) { ?>
                    <img class="profile-photo" alt="profile photo" src="<?php echo $_SESSION["photo"]; ?>" > 
                <?php  } ?>
               </li>
               <li> Welcome: <?php echo $_SESSION["name"];?> </li>
               <li> Email: <?php echo $_SESSION["email"];?> ! </li>
           </ul>
                               
           </div>
           
           
           <div class="right">
   
           <ul>
               <li> You are logged in as: <?php echo $_SESSION["type"];?> </li>
           </ul>
           
           </div>
       
           </div>
    <?php }?>