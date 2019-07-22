<?php
 	require_once "database.php";
	
	try{
		$query = $database->prepare("
			SELECT exp.id, exp.date, cat.name as category, exp.comment, exp.amount
			FROM `expenses` AS exp, `expense_categories` AS cat
			WHERE exp.user_id = :userID
			AND exp.category = cat.id
			AND exp.date BETWEEN :dateStart AND :dateEnd
			ORDER BY exp.date
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