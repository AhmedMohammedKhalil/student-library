	<div class="header">
		<div class="heading">
			<?php if (isset($_SESSION["header_h1"])) { ?><h1> <?php echo $_SESSION["header_h1"]; ?> </h1> <?php unset($_SESSION["header_h1"]);
																										} ?>
			<?php if (isset($_SESSION["header_h2"])) { ?><h1> <?php echo $_SESSION["header_h2"]; ?> </h1> <?php unset($_SESSION["header_h2"]);
																										} ?>
		</div>
	</div>
	<?php include 'nav.php'?>
