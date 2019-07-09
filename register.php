<?php

	session_start();
	
	if (isset($_SESSION['logged_id'])) {
		header('Location: menu.php');
		exit();
	}
	
	if (isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$correctRegistration = true;
		
		//Sprawdź poprawność nickname'a
		$login = $_POST['login'];
		
		//Sprawdzenie długości nicka
		if ((strlen($login)<3) || (strlen($login)>20))
		{
			$correctRegistration = false;
			$_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków!";
		}
		
		if (ctype_alnum($login) == false)
		{
			$correctRegistration = false;
			$_SESSION['e_login'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
		}
		
		// Sprawdź poprawność adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email))
		{
			$correctRegistration = false;
			$_SESSION['e_email'] = "Podaj poprawny adres e-mail!";
		}
		
		//Sprawdź poprawność hasła
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if ((strlen($password1) < 8) || (strlen($password1) > 30))
		{
			$correctRegistration = false;
			$_SESSION['e_password'] = "Hasło musi posiadać od 8 do 30 znaków!";
		}
		
		if ($password1 != $password2)
		{
			$correctRegistration = false;
			$_SESSION['e_password'] = "Podane hasła nie są identyczne!";
		}	

		$pass_hash = password_hash($password1, PASSWORD_DEFAULT);
				
		
		//Weryfikacja captcha
		$secret = "6LcgpawUAAAAAOlz9oO49z-n1Quh6e51vb6qdTTj";
		
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
		
		$reply = json_decode($check);
		
		if ($reply->success == false)
		{
			$correctRegistration = false;
			$_SESSION['e_bot'] = "Potwierdź, że nie jesteś robotem!";
		}		
		
		//Zapamiętaj wprowadzone dane
		$_SESSION['fr_login'] = $login;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_password1'] = $password1;
		$_SESSION['fr_password2'] = $password2;
	
	
		require_once "database.php";
		
		//Czy email już istnieje?
		try{
		$query = $database->prepare('SELECT id FROM users WHERE email = :email');
		$query->bindValue(':email', $email, PDO::PARAM_STR);
		$query->execute();
		}
		catch(PDOException $error){
			echo "Error: " . $error->getMessage();
		}
		
		$result = $query->fetch();
		$how_many_mails = $query->rowCount();
		if($how_many_mails > 0){
			$correctRegistration = false;
			$_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu e-mail!";
		}		

		//Czy nick jest już zarezerwowany?
		try{
			$query = $database->prepare('SELECT id FROM users WHERE login = :login');
			$query->bindValue(':login', $login, PDO::PARAM_STR);
			$query->execute();
		}
		catch(PDOException $error){
			echo "Error: " . $error->getMessage();
		}
		
		$result = $query->fetch();
		$how_many_logins = $query->rowCount();
		if($how_many_logins > 0)
		{
			$correctRegistration = false;
			$_SESSION['e_nick'] = "Istnieje już użytkownik o takim loginie! Wybierz inny.";
		}
		
		/**----------       CORRECT DATA       ----------**/
		
		if($correctRegistration == true){
			
			try{
				$database->beginTransaction();
				
				$query = $database->prepare("INSERT INTO users VALUES (NULL, :login, :email, :password)");
				$query->bindParam(':login', $login);
				$query->bindParam(':email', $email);
				$query->bindParam(':password', $pass_hash);
				$query->execute();
				
				$query = $database->prepare("SELECT id FROM users WHERE login = :login");
				$query->bindParam(':login', $login);
				$query->execute();
				$result = $query->fetch();
				$userID = $result['id'];
				
				$query = $database->prepare("SELECT name FROM default_income_categories");
				$query->execute();
				$result = $query->fetchAll();
				$query = $database->prepare("INSERT INTO income_categories VALUES (NULL, :userID, :name)");
				$query->bindParam(':userID', $userID);
				foreach($result as $category){
					$query->bindParam(':name', $category['name']);
					$query->execute();
				}
				
				$query = $database->prepare("SELECT name FROM default_expense_categories");
				$query->execute();
				$result = $query->fetchAll();
				$query = $database->prepare("INSERT INTO expense_categories VALUES (NULL, :userID, :name)");
				$query->bindParam(':userID', $userID);
				foreach($result as $category){
					$query->bindParam(':name', $category['name']);
					$query->execute();
				}
				
				$query = $database->prepare("SELECT name FROM default_payment_methods");
				$query->execute();
				$result = $query->fetchAll();
				$query = $database->prepare("INSERT INTO payment_methods VALUES (NULL, :userID, :name)");
				$query->bindParam(':userID', $userID);
				foreach($result as $method){
					$query->bindParam(':name', $method['name']);
					$query->execute();
				}
				
				$database->commit();
			}
			catch(PDOException $error){
				$database->rollback();
				echo "Error: " . $error->getMessage();
			}
			
			
			if(!$error){
				$_SESSION['correctRegistration'] = true;
				header('Location: welcome.php');
			}
			
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
	
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
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
					<h1> Rejestracja </h1>
				</header>
				
				<hr class="my-4">
				
				<form method="post">
					<div class="form-group">
						<label for="inputLogin">Login:</label>
						<input type="text" class="form-control" id="inputLogin" name="login" placeholder="..." value="<?php
							if (isset($_SESSION['fr_login'])){
								echo $_SESSION['fr_login'];
								unset($_SESSION['fr_login']);
							}
						?>" required>
						<?php
							if (isset($_SESSION['e_login'])){
								echo '<div class="error">'.$_SESSION['e_login'].'</div>';
								unset($_SESSION['e_login']);
							}
						?>	
					</div>
					
					<div class="form-group">
						<label for="inputEmail1">E-mail:</label>
						<input type="email" class="form-control" id="inputEmail1" name="email" placeholder="..." value="<?php
							if (isset($_SESSION['fr_email'])){
								echo $_SESSION['fr_email'];
								unset($_SESSION['fr_email']);
							}
						?>" required>					
						<?php
							if (isset($_SESSION['e_email'])){
								echo '<div class="error">'.$_SESSION['e_email'].'</div>';
								unset($_SESSION['e_email']);
							}
						?>
					</div>
					
					<div class="form-group">
						<label for="inputPassword1">Hasło:</label>
						<input type="password" class="form-control" id="inputPassword1" name="password1" placeholder="..." value="<?php
							if (isset($_SESSION['fr_password1'])){
								echo $_SESSION['fr_password1'];
								unset($_SESSION['fr_password1']);
							}
						?>" required>					
						<?php
							if (isset($_SESSION['e_password'])){
								echo '<div class="error">'.$_SESSION['e_password'].'</div>';
								unset($_SESSION['e_password']);
							}
						?>	
					</div>
					
					<div class="form-group">
						<label for="inputPassword2">Powtórz hasło:</label>
						<input type="password" class="form-control" id="inputPassword2" name="password2" placeholder="..." value="<?php
							if (isset($_SESSION['fr_password2'])){
								echo $_SESSION['fr_password2'];
								unset($_SESSION['fr_password2']);
							}
						?>" required>
					</div>
					
					<div class="g-recaptcha form-group" data-sitekey="6LcgpawUAAAAAD_adlwN85JezYl7N6E9Yrba8WBY"></div>
					<?php
						if (isset($_SESSION['e_bot']))
						{
							echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
							unset($_SESSION['e_bot']);
						}
					?>	
					
					<button type="submit" class="btn btn-primary mt-3">Zarejestruj się</button>
				</form>
				
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