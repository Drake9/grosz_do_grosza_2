<?php
session_start();
	
if (!isset($_SESSION['logged_id'])) {
	header('Location: index.php');
	exit();
}
else{
	require_once 'database.php';	
	try{
		$query = $database->prepare('SELECT id, name FROM expense_categories WHERE user_id = :logged_id');
		$query->bindParam(':logged_id', $_SESSION['logged_id']);
		$query->execute();
		$userExpenseCategories = $query->fetchAll();
	}
	catch(PDOException $error){
		echo "Error: " . $error->getMessage();
	}
	
	try{
		$query = $database->prepare('SELECT id, name FROM payment_methods WHERE user_id = :logged_id');
		$query->bindParam(':logged_id', $_SESSION['logged_id']);
		$query->execute();
		$userPaymentMethods = $query->fetchAll();
	}
	catch(PDOException $error){
		echo "Error: " . $error->getMessage();
	}
}

if(isset($_POST['amount'])){
	
	$correctExpenseData = true;
	
	$amount = $_POST['amount'];
	$date = $_POST['date'];
	$expenseCategory = $_POST['category'];
	$paymentMethod = $_POST['paymentMethod'];
	$comment = $_POST['comment'];
	
	if($amount <= 0){
		$correctExpenseData = false;
		$_SESSION['e_amount'] = "Kwota musi być większa od zera!";
	}
	
	if($correctExpenseData == true){
		try{
			$query = $database->prepare('INSERT INTO expenses VALUES (NULL, :userID, :amount, :date, :category, :method, :comment)');
			$query->bindParam(':userID', $_SESSION['logged_id']);
			$query->bindParam(':amount', $amount);
			$query->bindParam(':date', $date);
			$query->bindParam(':category', $expenseCategory);
			$query->bindParam(':method', $paymentMethod);
			$query->bindParam(':comment', $comment);
			$query->execute();
		}
		catch(PDOException $error){
			echo "Error: " . $error->getMessage();
		}
		
		if(!isset($error)){
			$_SESSION['expenseAdded'] = true;
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
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link href="main.css" rel="stylesheet" type="text/css" />
	<link href="https://fonts.googleapis.com/css?family=Exo:400,700&display=swap&subset=latin-ext" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
	<link rel="stylesheet" href="jquery-ui/jquery-ui.min.css">
	<script src="jquery-ui/external/jquery/jquery.js"></script>
	<script src="jquery-ui/jquery-ui.min.js"></script>
	<script src="jquery-ui/datepicker-pl.js"></script>
	
	<script>
	$( function() {
		$.datepicker.setDefaults( $.datepicker.regional[ "pl" ] );
		$( "#date" ).datepicker({
			
			dateFormat: "yy-mm-dd"
		});
	} );
	</script>
	
</head>

<body>

	<div id="wrap">

	<header class="sticky-top">
	
		<nav class="navbar navbar-dark bg-primary border-bottom shadow mb-5 navbar-expand-lg">
	
			<a class="navbar-brand" href="menu.php"><img src="img/coins.png" width="30" height="30" class="d-inline-block mr-1 align-bottom" alt=""> GroszDoGrosza.pl </a>
			
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>
		
			<div class="collapse navbar-collapse" id="mainmenu">
			
				<ul class="navbar-nav mr-auto">
				
					<li class="nav-item">
						<a class="nav-link" href="addIncome.php"> Dodaj przychód </a>
					</li>
					
					<li class="nav-item active">
						<a class="nav-link" href="addExpense.php"> Dodaj wydatek </a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="viewBalance.php"> Przeglądaj bilans </a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="settings.php"> Ustawienia </a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="logout.php"> Wyloguj się </a>
					</li>
				
				</ul>
			
			</div>
			
		</nav>
		
	</header>
	
	
	<main>	
	
		<section>
		
			<div class="container jumbotron shadow-lg">
			
				<header>
					<h1>
						<?php
							if(!isset($_SESSION['expenseAdded'])){
								echo "Dodawanie wydatku";
							}
							else{
								echo "Dodano wydatek. Czy chcesz dodać kolejny?";
								unset($_SESSION['expenseAdded']);
							}
						?>
					</h1>
				</header>
				
				<hr class="my-4">
				
				<form method="post" enctype="multipart/form-data">
				
					<div class="form-group">
						<label for="amount"> Kwota wydatku </label>
						<input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0.01" required>
						<?php
							if(isset($_SESSION['e_amount'])){
								echo '<div class="error">'.$_SESSION['e_amount'].'</div>';
								unset($_SESSION['e_amount']);
							}
						?>
					</div>
				
					<div class="form-group">
						<label for="date"> Data </label>
						<input type="text" class="form-control" id="date" name="date" required>
					</div>
				
					<div class="form-group">
						<label for="category"> Kategoria </label>
						<select class="custom-select form-control" id="category" name="category" required>
							<option value=""> Wybierz kategorię </option>
							<?php
								foreach($userExpenseCategories as $category){
									echo '<option value='.$category['id'].'> '.$category['name'].' </option>';
								}
							?>
						</select>
					</div>
					
					<label class="radio control-label"> Sposób płatności </label>
					<div class="form-group form-control" id="radioPanel">
						<?php
							foreach($userPaymentMethods as $method){
								echo '
									<div class="form-check">
										<input class="form-check-input" type="radio" name="paymentMethod" id='.str_replace(' ', '', $method['name']).' value='.$method['id'].' required>
										<label class="form-check-label" for='.str_replace(' ', '', $method['name']).'> '.$method['name'].' </label>
									</div>
								';
							}
						?>
					</div>
					
					<div class="form-group">
						<label for="comment"> Komentarz (opcjonalnie) </label>
						<textarea class="form-control" name="comment" id="comment" rows="2" cols="50" maxlength="100" minlength="10"></textarea>
					</div>
					
					<button type="submit" class="btn btn-success"> Dodaj </button>
					<button type="reset" class="btn btn-warning mx-sm-3"> Wyczyść </button>
					<a class="btn btn-danger" href="menu.html"> Anuluj </a>
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
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	<script src="addTransaction.js"></script>
	
</body>
</html>