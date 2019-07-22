<?php
 	require_once "database.php";
	
	try{
		$query = $database->prepare("
			SELECT cat.name AS category, SUM(inc.amount) AS amount
			FROM `income_categories` AS cat, `incomes` AS inc
			WHERE inc.user_id = :userID
			AND inc.category = cat.id
			AND inc.date BETWEEN :dateStart AND :dateEnd
			GROUP BY inc.category
			ORDER BY amount DESC
		");
		$query->bindParam(':userID', $_POST['userID']);
		$query->bindParam(':dateStart', $_POST['periodStart']);
		$query->bindParam(':dateEnd', $_POST['periodEnd']);
		$query->execute();
		$data = $query->fetchAll();
	}
	catch(PDOException $error){
		echo '<span style="color:red;">Błąd serwera. Przepraszamy. Spróbuj ponownie później.</span>';
		echo "Error: " . $error->getMessage();
	}
		
	echo json_encode($data);
?>