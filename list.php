<?php

	session_start();
	
	require_once 'env.php';
	
	require_once 'database.php';

if (!isset($_SESSION['logged_id'])) {

	if (isset($_POST['login'])) {

		$login = filter_input(INPUT_POST, 'login');
		$password = filter_input(INPUT_POST, 'pass');

		$_SESSION['lista'] = 0;


		$userQuery = $db->prepare('SELECT id, password, id_kier FROM users WHERE login = :login');
		$userQuery->bindValue(':login', $login, PDO::PARAM_STR);
		$userQuery->execute();

		$user = $userQuery->fetch();

		if ($user && password_verify($password, $user['password'])) {
			unset($_SESSION['bad_attempt']);
			$_SESSION['logged_id'] = $user['id'];
			$_SESSION['logged_kier'] = $user['id_kier'];
			$idkier = $user['id_kier'];

			$ip = get_client_ip();
			$time_in =  date("Y-m-d H:i:s");

			$userQuery = $db->prepare("INSERT INTO tmp (id, idid, ip, login_time) VALUES (null, '$idkier','$ip', '$time_in')");
			$userQuery->execute();
		}
		else
		{
			$_SESSION['bad_attempt'] = true;
			header('Location: index.php');
			exit();
		}

	} else {


		exit();
	}
}


?>

<!DOCTYPE html>
<html lang="pl">

<head>

	<meta charset="utf-8">
	<title>Ocena pracownika</title>
	<meta name="description" content="Ocena pracownika">
	
	<meta name="author" content="A~M">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="/css/ocena_frm.css">
    <link rel="stylesheet" href="/css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
	
</head>

<body>

<!--	<header>-->
<!--		<h5>Dokonaj oceny pracownika</h5>-->
<!--	</header>-->
	
	<main>
		<article>
			<form action="order.php" method="post" enctype="multipart/form-data">
			
			
			<div class="row">

				<label for="ocena"> Nazwisko i imie oraz budowa </label>
				<?php
					
					$sql = $db->prepare("SET CHARSET 'utf8'");
					$sql = $db->prepare("SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
					$sql = $db->prepare('SELECT a.id_prac, CONCAT(nazwisko, " ", imie) as nazwa, b.nazwa_skr as budowa,a.id_bud, a.id_kier as id_kier, data_od , data_do FROM lista a 
													left join pracownicy p on p.idoferty=a.id_prac 
													left join slbudowy b on b.idslbudowy=a.id_bud
													WHERE a.id_kier=:login and akcept=0
													ORDER BY nazwisko, imie');
					$sql->bindValue(':login', $_SESSION['logged_kier'], PDO::PARAM_STR);
					$sql->execute();
					
					
	
					if ( $sql->rowCount() > 0 )
					{
						session_start();
						
						$_SESSION['logged_bud'] = $user['id_bud'];
						$idbud = $user['id_bud'];
						
						
											
						$_SESSION['lista'] = 1;
						
						echo '<select id="ocena" name="idprac">';

						while ( $row = $sql->fetch(PDO::FETCH_ASSOC))
						{
							$dzienod = $row['data_od'];
							$dziendo = $row['data_do'];
							
							echo '<option value="'.$row['id_prac'].'">'.$row['nazwa']." Budowa ".$row['budowa'].'</option>';
						}
		
						echo '</select>';
					}
					else
					{
						?>
						<p><font color="red"><?php echo "Lista jest pusta"; ?></font></p>
						
						<?php $_SESSION['lista'] = 0;
					}
					
					echo '</div>';
					
					
					
				?>

				<div class="row">
					<div><label for="komentarz"> Pracownik oceniany jest w zawodzie </label></div>
					<textarea name="zawod" id="komentarz" rows="1" cols="40" maxlength="35"></textarea>
				</div>
				
				<div class="row">
				
					<label for="ocena"> Wiedza fachowa i umiejętności praktyczne </label>
					<select id="ocena" name="ocena1">
						<option value="0"></option>
						<option value="1">bardzo dobra</option>
						<option value="2">dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
				
				</div>
				
				<div class="row">
				
					<label for="ocena"> Jakość wykonywanej pracy </label>
					<select id="ocena" name="ocena2">
					
						<option value="1">bardzo dobra</option>
						<option value="2" selected>dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
				
				</div>
				
				<div class="row">
				
					<label for="ocena"> Wydajność pracy </label>
					<select id="ocena" name="ocena3">
					
						<option value="1">bardzo dobra</option>
						<option value="2" selected>dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
				
				</div>				

				<div class="row">
				
					<label for="ocena"> Samodzielność i inicjatywa w wykonywanej pracy </label>
					<select id="ocena" name="ocena4">
					
						<option value="1">bardzo dobra</option>
						<option value="2" selected>dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
				
				</div>

				<div class="row">
				
					<label for="ocena"> Umiejętność współpracy z otoczeniem </label>
					<select id="ocena" name="ocena5">
					
						<option value="1">bardzo dobra</option>
						<option value="2" selected>dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
				
				</div>
				
				<div class="row">
				
					<label for="ocena"> Stosunek do przełożonych i współpracowników </label>
					<select id="ocena" name="ocena6">
					
						<option value="1">bardzo dobra</option>
						<option value="2" selected>dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
				
				</div>

				<div class="row">
				
					<label for="ocena"> Zaangażowanie w pracy </label>
					<select id="ocena" name="ocena7">
					
						<option value="1">bardzo dobra</option>
						<option value="2" selected>dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
				
				</div>
				
				<div class="row">
				
					<label for="ocena"> Zdyscyplinowanie </label>
					<select id="ocena" name="ocena8">
					
						<option value="1">bardzo dobra</option>
						<option value="2" selected>dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
				
				</div>

				<div class="row">
				
					<label for="ocena"> Zdolność pracownika do samooceny </label>
					<select id="ocena" name="ocena9">
					
						<option value="TAK" selected>TAK</option>
						<option value="NIE">NIE</option>
						<option value="TAK I NIE">TAK I NIE</option>
					
					</select>
				
				</div>

				<div class="row">
				
					<label for="ocena"> Postawa pracownika </label>
					<select id="ocena" name="ocena10">
					
						<option value="ROSZCZENIOWA">ROSZCZENIOWA</option>
						<option value="UGODOWA" selected>UGODOWA</option>
						<option value="UGODOWO-ROSZCZENIOWA">UGODOWO-ROSZCZENIOWA</option>
					
					</select>
				
				</div>


				<div class="row">
					<label> Okres oceny od <input type="date" name="dzien_od" value="<?php echo $dzienod; ?>"></label>
										  <label> do <input type="date" name="dzien_do" value="<?php echo $dziendo; ?>"></label>
				</div>
				
				<div class="row">
					<div><label for="komentarz"> Powierzenie auta </label></div>
					<textarea name="komentarz1" id="komentarz" rows="1" cols="80" maxlength="125"></textarea>
				</div>

				<div class="row">
					<div><label for="komentarz"> Uwagi </label></div>
					<textarea name="komentarz2" id="komentarz" rows="2" cols="80" maxlength="250"></textarea>
				</div>
				
				<input type="hidden" name="token" value="ug3f5jgh5f7d87874fdg4ddgh">
				
				<div class="row">
					<input type="submit" value="Zapisz!">
					<input type="reset" value="Wyczyść formularz">
					<a href="logout.php" style="color: #fa5800">Wyloguj się!</a>
				</div>
				
			</form>		
			
		</article>
		
			
	</main>
  
</body>
</html>