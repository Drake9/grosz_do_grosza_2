<?php
session_start();

require_once 'database.php';

if (!isset($_SESSION['logged_id'])) {

	if (isset($_POST['login'])) {
		
		$login = filter_input(INPUT_POST, 'login');
		$password = filter_input(INPUT_POST, 'password');
		
		//echo $login . " " .$password;
		
		$userQuery = $db->prepare('SELECT id, password FROM users WHERE login = :login');
		$userQuery->bindValue(':login', $login, PDO::PARAM_STR);
		$userQuery->execute();
		
		//echo $userQuery->rowCount();
		
		$user = $userQuery->fetch();
		
		//echo "<br>";
		//echo $user['id'] . "<br>" . $user['password'] . "<br>";
		//echo password_hash($password, PASSWORD_DEFAULT);
		
		if ($user && password_verify($password, $user['password'])) {
			$_SESSION['logged_id'] = $user['id'];
			unset($_SESSION['bad_attempt']);
		} else {
			$_SESSION['bad_attempt'] = true;
			header('Location: index.php');
			exit();
			//echo "<br>BAD ATTEMPT!";
		}
			
	} else {
		
		header('Location: index.php');
		exit();
	}
}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Grosz do grosza</title>
	
	<meta name="description" content="Aplikacja internetowa do planowania domowego budżetu" />
	<meta name="keywords" content="budżet, oszczędzanie, pieniądze, planowanie, wydatki" />
	<meta name="author" content="Marcin Zapała" />
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link href="main.css" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Exo:400,700&display=swap&subset=latin-ext" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
</head>

<body>

	<div id="wrap">
	<header class="sticky-top">
	
		<nav class="navbar navbar-dark bg-primary border-bottom shadow mb-5">
	
			<a class="navbar-brand" href="menu.html"><img src="img/coins.png" width="50" height="50" class="d-inline-block mr-1 align-bottom" alt=""> GroszDoGrosza.pl</a>
			
		</nav>
		
	</header>
	
	<main>	
	
		<section>
		
			<div class="container jumbotron shadow-lg">
			
				<header>
					<h1> Menu </h1>
				</header>
				
				<hr class="my-4">
				
				<a href="addIncome.php" class="btn btn-primary btn-lg btn-block my-3" role="button">Dodaj przychód</a>
				<a href="addExpense.php" class="btn btn-primary btn-lg btn-block my-3" role="button">Dodaj wydatek</a>
				<a href="viewBalance.php" class="btn btn-primary btn-lg btn-block my-3" role="button">Przeglądaj bilans</a>
				<a href="settings.php" class="btn btn-info btn-lg btn-block my-3" role="button">Ustawienia</a>
				<a href="logout.php" class="btn btn-secondary btn-lg btn-block my-3" role="button">Wyloguj</a>
				
			</div>
		
		</section>
		
	</main>
	</div>
	
	<footer class="page-footer font-small bg-primary text-white mt-auto border-top">

	  <div class="footer-copyright text-center py-3">
		GroszDoGrosza.pl © 2019; Icons made by <a href="https://www.flaticon.com/authors/smashicons" class="badge badge-secondary" title="Smashicons">Smashicons</a> from <a href="https://www.flaticon.com/" class="badge badge-secondary" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" class="badge badge-secondary" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>
	  </div>
	  
	</footer>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>