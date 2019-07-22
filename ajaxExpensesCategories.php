<?php
 	require_once "database.php";
	
	try{
		$query = $database->prepare("
			SELECT cat.name AS category, SUM(exp.amount) AS amount
			FROM `expense_categories` AS cat, `expenses` AS exp
			WHERE exp.user_id = :userID
			AND exp.category = cat.id
			AND exp.date BETWEEN :dateStart AND :dateEnd
			GROUP BY exp.category
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