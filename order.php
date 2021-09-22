<?php
session_start();
if (!isset($_SESSION['logged_id'])) {

	if (isset($_POST['login'])) {
		exit();
	}

}
	
	$idkier = $_SESSION['logged_kier'];

    $zawod = $_POST['zawod'];
    $_SESSION['zawod'] = $zawod;

	$ocena1 = $_POST['ocena1'];
    $_SESSION['$ocena1'] = $ocena1;

	$ocena2 = $_POST['ocena2'];	
	$ocena3 = $_POST['ocena3'];
	$ocena4 = $_POST['ocena4'];	
	$ocena5 = $_POST['ocena5'];
	$ocena6 = $_POST['ocena6'];	
	$ocena7 = $_POST['ocena7'];
	$ocena8 = $_POST['ocena8'];	
	$ocena9 = $_POST['ocena9'];
	$ocena10 = $_POST['ocena10'];		
	$data_od = $_POST['dzien_od'];		
	$data_do = $_POST['dzien_do'];		
	$komentarz1 = $_POST['komentarz1'];		
	$komentarz2 = $_POST['komentarz2'];		
	$idprac = $_POST['idprac'];
	$nazwa = $_POST['nazwa'];



    $isOk = $_POST['isOk'];



                    require_once 'database.php';
					
					$sql = $db->prepare('SELECT id_prac, id_bud, id_kier FROM lista a WHERE id_kier=:idkier and id_prac=:idprac and akcept=0');
					$sql->bindValue(':idkier', $idkier, PDO::PARAM_INT);
					$sql->bindValue(':idprac', $idprac, PDO::PARAM_INT);
					$sql->execute();
					
					$user = $sql->fetch();
					
					$idbud = $user['id_bud'];
	
		
		if ($idprac > 0){

            if (trim($zawod) == '') {
                $isOk = false;
                $_SESSION['e_zawod']="zawód wymagany";
                header('Location: list.php');
                exit();
            }
            else
            {
                unset($_SESSION['e_zawod']);
            }

            if (trim($ocena1) == '0') {
                $isOk = false;
                $_SESSION['e_ocena1']="pole wymagane";
                header('Location: list.php');
                exit();
            }

            if (trim($ocena2) == '0') {
                $isOk = false;
                $_SESSION['e_ocena2']="pole wymagane";
                header('Location: list.php');
                exit();
            }

            if (trim($ocena3) == '0') {
                $isOk = false;
                $_SESSION['e_ocena3']="pole wymagane";
                header('Location: list.php');
                exit();
            }

            if (trim($ocena4) == '0') {
                $isOk = false;
                $_SESSION['e_ocena4']="pole wymagane";
                header('Location: list.php');
                exit();
            }

            if (trim($ocena5) == '0') {
                $isOk = false;
                $_SESSION['e_ocena5']="pole wymagane";
                header('Location: list.php');
                exit();
            }

            if (trim($ocena6) == '0') {
                $isOk = false;
                $_SESSION['e_ocena6']="pole wymagane";
                header('Location: list.php');
                exit();
            }

            if (trim($ocena7) == '0') {
                $isOk = false;
                $_SESSION['e_ocena7']="pole wymagane";
                header('Location: list.php');
                exit();
            }

            if (trim($ocena8) == '0') {
                $isOk = false;
                $_SESSION['e_ocena8']="pole wymagane";
                header('Location: list.php');
                exit();
            }

            if (trim($ocena9) == '0') {
                $isOk = false;
                $_SESSION['e_ocena9']="pole wymagane";
                header('Location: list.php');
                exit();
            }

            if (trim($ocena10) == '0') {
                $isOk = false;
                $_SESSION['e_ocena10']="pole wymagane";
                header('Location: list.php');
                exit();
            }


		require_once 'database.php';
		
		$userQuery = $db->prepare('UPDATE lista set akcept=1 WHERE id_prac = :idprac and  id_kier = :idkier and  id_bud = :idbud and akcept=0');
		$userQuery->bindValue(':idprac', $idprac, PDO::PARAM_INT);
		$userQuery->bindValue(':idkier', $idkier, PDO::PARAM_INT);
		$userQuery->bindValue(':idbud', $idbud, PDO::PARAM_INT);
		$userQuery->execute();	
			
		$userQuery = $db->prepare("INSERT INTO prac_ocena_out (id, samoocena, postawa, idbudowa, id_prac, st1, st2, st3, st4, st5, st6, st7, st8,
												   data_od, data_do, idkierownik, uwagi2, pow_auta, zawod)
												   VALUES(null, '$ocena9', '$ocena10', '$idbud', '$idprac', '$ocena1', '$ocena2', '$ocena3', '$ocena4',
																'$ocena5', '$ocena6', '$ocena7', '$ocena8', '$data_od', '$data_do', '$idkier', 
																'$komentarz2', '$komentarz1','$zawod') ");
																
		
		$userQuery->execute();	
		
		$sql = $db->prepare('SELECT CONCAT(nazwisko, " ", imie) as nazwa FROM lista a 
													left join pracownicy p on p.idoferty=a.id_prac 
													WHERE p.idoferty=:login
													ORDER BY nazwisko, imie');
					$sql->bindValue(':login', $idprac, PDO::PARAM_STR);
					$sql->execute();
					
					$user = $sql->fetch();


        }
		else
		{
			echo "Lista jest pusta!!!";

			header('Location: logout.php');
			
			
			exit();
		}
	

        ?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Podsumowanie pracownika</title>
</head>

<body>

	<form action="list.php" method="post" enctype="multipart/form-data">
			
			
			<div class="row">

				<label for="ocena"> Zapisano </label>
				<?php
					echo " ".$user['nazwa'];
				?>
				
			</div>
			
		</form>

	
	<a href="list.php">Powrót do strony głównej</a>
	
</body>
</html>