<?php
	require("library/config.php");
	$db = new Database();
	$f = new Functions();
	
?>

<?php require("head.php"); ?>

</head>

<body>
<div id="header">
	<div id="top">
		<div class="logo">
			<a href="/kibocms/join.php" title="Home" class="tooltip"><img src="/kibocms/preset/assets/logo.png" alt="Kibo CMS" /></a> 
		</div>
		
	</div>
</div>



<div id="bgwrap">
	
	<div id="content">
		<div id="main">
			<br />
			<br />
			<br />
			<h1>Log in</h1>
			<?php $f->getMessage(); ?>
			<form method="POST" action="login.php" class="login">
				
				<p>
					<label>Username:</label>
					<input type="text" class="text" id="login_username" name="login_username" />
				</p>
				<p>
					<label>Password:</label>
					<input type="password" class="text" id="login_password" name="login_password" />
				</p>
				
				<p class="submit">
					<input type="submit" value="Log in" class="submit" />
				</p>
			</form>
		</div>
	
		
	</div>

</body>

</html>
