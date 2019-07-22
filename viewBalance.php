<?php
session_start();
	
if (!isset($_SESSION['logged_id'])) {
	header('Location: index.php');
	exit();
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
	
</head>

<body>

	<!-- Modal -->
	<div class="modal fade" id="customPeriodModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalTitle">Wybierz okres bilansu</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="dateStart"> Początek </label>
						<input type="text" class="form-control" id="dateStart" name="dateStart" required>
						<div id="dateStartComment" class="error"></div>
					</div>
					<div class="form-group">
						<label for="dateEnd"> Koniec </label>
						<input type="text" class="form-control" id="dateEnd" name="dateEnd" required>
						<div id="dateEndComment" class="error"></div>
						<div id="bothDatesComment" class="error"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id="customBalanceConfirm">Potwierdź</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Anuluj</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal end -->

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
					
					<li class="nav-item">
						<a class="nav-link" href="addExpense.php"> Dodaj wydatek </a>
					</li>
					
					<li class="nav-item active">
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
		
			<div class="row container-fluid mx-auto mb-4 px-0 px-sm-3">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-body">
							<h1 class="card-title d-inline-block">Bilans</h1>
							<div class="dropdown float-right">
								<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Okres
								</button>
								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="#" id="currentMonth">Bieżący miesiąc</a>
									<a class="dropdown-item" href="#" id="lastMonth">Poprzedni miesiąc</a>
									<a class="dropdown-item" href="#" id="currentYear">Bieżący rok</a>
									<div class="btn dropdown-item" data-toggle="modal" data-target="#customPeriodModal">Niestandardowy</div>
								</div>
							</div>
							<h3 id="period">za okres: </h3>
						</div>
					</div>
				</div>
			</div>
		
			<div class="row container-fluid mx-auto">
			
				<div class="col-md-6 mb-4 px-0 px-sm-3">
					<div class="card">
						<div class="card-body">
							<h2 class="card-title text-center">Przychody</h2>
							<table class="table table-striped">
								<thead>
									<tr>
										<th scope="col" style="width: 50%">kategoria</th>
										<th scope="col" style="width: 50%">suma</th>
									</tr>
								</thead>
								<tbody id="incomesGenerally">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div class="col-md-6 mb-4 px-0 px-sm-3">
					<div class="card">
						<div class="card-body text-center">
							<h2 class="card-title">Wydatki</h2>
							<table class="table table-striped">
								<thead>
									<tr>
										<th scope="col" style="width: 50%">kategoria</th>
										<th scope="col" style="width: 50%">suma</th>
									</tr>
								</thead>
								<tbody id="expensesGenerally">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			</div>
			
			<div class="row container-fluid mx-auto">
			
				<div class="col-md-6 mb-4 px-0 px-sm-3">
					<div class="card">
						<div class="card-body">
							<h2 class="card-title text-center">Bilans</h2>
							<table class="table table-striped">
								<thead>
									<tr>
										<th scope="col" style="width: 50%">kategoria</th>
										<th scope="col" style="width: 50%">kwota</th>
									</tr>
								</thead>
								<tbody id="balance">
									
								</tbody>
							</table>
							<h5 class="text-center font-weight-bold" id="balanceComment">Gratulacje! Świetnie dysponujesz finansami!</h5>
						</div>
					</div>
				</div>
				
				<div class="col-md-6 mb-4 px-0 px-sm-3">
					<div class="card">
						<div class="card-body">
							<h2 class="card-title text-center">Wydatki - proporcje</h2>
							<div id="chartdiv"></div>
						</div>
					</div>
				</div>
				
			</div>
			
			<div class="row container-fluid mx-auto">
			
				<div class="col-md-6 mb-4 px-0 px-sm-3">
					<div class="card">
						<div class="card-body responsiveTable">
							<h2 class="card-title text-center">Przychody - szczegółowo</h2>
							<table class="table table-striped table-sm">
								<thead>
									<tr>
										<th scope="col" style="width: 20%">data</th>
										<th scope="col" style="width: 20%">kategoria</th>
										<th scope="col" style="width: 40%">komentarz</th>
										<th scope="col" style="width: 20%">kwota</th>
									</tr>
								</thead>
								<tbody id="incomesInDetail">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div class="col-md-6 mb-4 px-0 px-sm-3">
					<div class="card">
						<div class="card-body responsiveTable">
							<h2 class="card-title text-center">Wydatki - szczegółowo</h2>
							<table class="table table-striped table-sm">
								<thead>
									<tr>
										<th scope="col" style="width: 20%">data</th>
										<th scope="col" style="width: 20%">kategoria</th>
										<th scope="col" style="width: 40%">komentarz</th>
										<th scope="col" style="width: 20%">kwota</th>
									</tr>
								</thead>
								<tbody id="expensesInDetail">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				
			</div>
			
			<div id="userID" hidden><?php echo $_SESSION['logged_id']; ?></div>
		
		</section>
		
	</main>
	</div>
	
	<footer class="page-footer position-absolute font-small bg-primary text-white mt-auto border-top">

	  <div class="footer-copyright text-center py-3">
		GroszDoGrosza.pl © 2019; Icons made by <a href="https://www.flaticon.com/authors/smashicons" class="badge badge-secondary" title="Smashicons">Smashicons</a> from <a href="https://www.flaticon.com/" class="badge badge-secondary" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" class="badge badge-secondary" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>
	  </div>
	  
	</footer>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	<script src="https://www.amcharts.com/lib/4/core.js"></script>
	<script src="https://www.amcharts.com/lib/4/charts.js"></script>
	<script src="https://www.amcharts.com/lib/4/themes/moonrisekingdom.js"></script>
	<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
	<script src='viewBalance.js'></script>;
	
</body>
</html>