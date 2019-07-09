<?php
session_start();

if (isset($_SESSION['logged_id'])) {
	header('Location: menu.php');
	exit();
}

if (!isset($_SESSION['correctRegistration']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['correctRegistration']);
	}
	
	//Usuwanie zmiennych pamiętających wartości wpisane do formularza
	if (isset($_SESSION['fr_login'])) unset($_SESSION['fr_login']);
	if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
	if (isset($_SESSION['fr_password1'])) unset($_SESSION['fr_password1']);
	if (isset($_SESSION['fr_password2'])) unset($_SESSION['fr_password2']);
	
	//Usuwanie błędów rejestracji
	if (isset($_SESSION['e_login'])) unset($_SESSION['e_login']);
	if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
	if (isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
	if (isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
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
	
			<a class="navbar-brand" href="menu.php"><img src="img/coins.png" width="50" height="50" class="d-inline-block mr-1 align-bottom" alt=""> GroszDoGrosza.pl</a>
			
		</nav>
		
	</header>
	
	<main>	
	
		<section>
		
			<div class="container jumbotron shadow-lg">
			
				<header>
					<h1> Witamy! </h1>
				</header>
				
				<p class="lead text-justify">Dziękujemy za rejestrację w serwisie! Możesz już zalogować się na swoje konto.</p>
				
				<hr class="my-4">
	
				<a href="index.php" class="btn btn-primary">Zaloguj się na swoje konto!</a>
				
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