<?php
 	require_once "database.php";
	
	try{
		$query = $database->prepare("
			SELECT inc.id, inc.date, cat.name as category, inc.comment, inc.amount
			FROM `incomes` AS inc, `income_categories` AS cat
			WHERE inc.user_id = :userID
			AND inc.category = cat.id
			AND inc.date BETWEEN :dateStart AND :dateEnd
			ORDER BY inc.date
		");
		$query->bindParam(':userID', $_POST['userID']);
		$query->bindParam(':dateStart', $_POST['periodStart']);
		$query->bindParam(':dateEnd', $_POST['periodEnd']);
		$query->execute();
		$userIncomesData = $query->fetchAll();
	}
	catch(PDOException $error){
		echo '<span style="color:red;">Błąd serwera. Przepraszamy. Spróbuj ponownie później.</span>';
		echo "Error: " . $error->getMessage();
	}
	
	/*
	SELECT inc.id, inc.date, cat.name, inc.comment, inc.amount
	FROM `incomes` as inc, `income_categories` as cat
	WHERE inc.user_id=1 AND inc.category=cat.id
	AND inc.date BETWEEN '2019-06-01' AND '2019-07-30'
	*/
		
	echo json_encode($userIncomesData);
?>