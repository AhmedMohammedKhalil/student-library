<?php if(isset($_SESSION["Error_MSG"])){?>
	<article id ="errorMSG" class="error">
	<p class="error"> <b>Error Messsage:</b> <?php echo $_SESSION["Error_MSG"]; ?> </p> 
	</article>
<?php unset ($_SESSION["Error_MSG"]); }

else if(isset($_SESSION["Success_MSG"])){?>
	<article id ="successMSG" class="success">
	<p class="success"> <b>Success Messsage:</b> <?php echo $_SESSION["Success_MSG"]; ?> </p> 
	</article>
<?php unset ($_SESSION["Success_MSG"]); }?>
