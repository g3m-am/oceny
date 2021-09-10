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
<!--    <link rel="stylesheet" href="/css/main.css">-->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
	
</head>

<body>

	<main>
		<article class="row" style="color: #170505; text-decoration: #2084c2">
			<form action="order.php" method="post" enctype="multipart/form-data">
<table>

                <div>
				    <label for="ocena" style="font-size: large"> Nazwisko i imie oraz budowa </label>
                </div>

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
							
							echo '<option value="'.$row['id_prac'].'">'.$row['nazwa']." Budowa ".$row['budowa']." Data od ".$dzienod." do ".$dziendo.'</option>';
						}
		
						echo '</select>';
					}
					else
					{
						?>
						<p style="color: #815408;"><?php echo "Sorry, brak elementów listy"; ?></font></p>
						
						<?php $_SESSION['lista'] = 0;
					}
					
					echo '</div>';
					
					
					
				?>



<!-- head -->
    <p></p>
    <tr>
        <div >



            <div align="left">
                <td>
                    <label for="komentarz"  style="width:215px"> Pracownik oceniany jest w zawodzie </label>
                </td>
            </div>
            <td>
                <textarea name="zawod" id="komentarz" style="width:295px;resize:none;height:20px;"></textarea>
            </td>
    </div>

    </tr>

    <tr>
				<div >
                    <div style="text-align:left">
                        <td>
					        <label style="text-align:left" for="ocena"> Wiedza fachowa i umiejętności praktyczne</label>
                        </td>

                        <td>
					    <select id="ocena" name="ocena1" style="width: 300px">

                            <option value="0"></option>
                            <option value="1">bardzo dobra</option>
                            <option value="2">dobra</option>
                            <option value="3">dostateczna</option>
                            <option value="4">niedostateczna</option>
					
					    </select>
                        </td>
                    </div>
				</div>

    </tr>

    <tr>
				<div class="row">
                     <td>
				        <label for="ocena" style="split left"> Jakość wykonywanej pracy </label>
                    </td>

                    <td>
					<select id="ocena" name="ocena2" style="width: 300px">

                        <option value="0"></option>
						<option value="1">bardzo dobra</option>
						<option value="2">dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
                    </td>
				</div>
    </tr>
    <p></p>
    <tr>

				<div class="row">
                <td>
					<label for="ocena"> Wydajność pracy</label>
                </td>
                    <td>
					<select id="ocena" name="ocena3" style="width: 300px">

                        <option value="0"></option>
						<option value="1">bardzo dobra</option>
						<option value="2">dobra</option>
						<option value="3">dostateczna</option>
						<option value="4">niedostateczna</option>
					
					</select>
                    </td>
				</div>
    </tr>
    <p></p>
    <tr>
				<div class="row">
                    <td>
                        <label for="ocena"> Samodzielność i inicjatywa w wykonywanej pracy </label>
                    </td>

                    <td>
    					<select id="ocena" name="ocena4" style="width: 300px">

                            <option value="0"></option>
                            <option value="1">bardzo dobra</option>
                            <option value="2">dobra</option>
                            <option value="3">dostateczna</option>
                            <option value="4">niedostateczna</option>

    					</select>
                    </td>
				</div>
    </tr>
    <p></p>
    <tr>
				<div class="row">
		            <td>
					    <label for="ocena"> Umiejętność współpracy z otoczeniem </label>
                    </td>
                    <td>
					    <select id="ocena" name="ocena5" style="width: 300px">

                            <option value="0"></option>
                            <option value="1">bardzo dobra</option>
                            <option value="2">dobra</option>
                            <option value="3">dostateczna</option>
                            <option value="4">niedostateczna</option>
					
    					</select>
                    </td>
				</div>
    </tr>
    <p></p>
    <tr>
                <div class="row">
                    <td>
    					<label for="ocena"> Stosunek do przełożonych i współpracowników </label>
                    </td>

                    <td>
					    <select id="ocena" name="ocena6" style="width: 300px">

                            <option value="0"></option>
                            <option value="1">bardzo dobra</option>
                            <option value="2">dobra</option>
                            <option value="3">dostateczna</option>
                            <option value="4">niedostateczna</option>
					
					    </select>
                    </td>
				</div>
    </tr>
    <p></p>
    <tr>
				<div class="row">
                    <td>
						<label for="ocena"> Zaangażowanie w pracy </label>
                    </td>

                    <td>
					    <select id="ocena" name="ocena7" style="width: 300px">

                            <option value="0"></option>
                            <option value="1">bardzo dobra</option>
                            <option value="2">dobra</option>
                            <option value="3">dostateczna</option>
                            <option value="4">niedostateczna</option>
					
					    </select>
                    </td>
				
				</div>
    </tr>
    <p></p>
    <tr>
                <div class="row">
                    <td>
					    <label for="ocena"> Zdyscyplinowanie </label>
                    </td>

                    <td>
					    <select id="ocena" name="ocena8" style="width: 300px">

                            <option value="0"></option>
                            <option value="1">bardzo dobra</option>
                            <option value="2" >dobra</option>
                            <option value="3">dostateczna</option>
                            <option value="4">niedostateczna</option>
					
					    </select>
                    </td>
				
				</div>
    </tr>
    <p></p>
    <tr>
				<div class="row">
                    <td>
						<label for="ocena"> Zdolność pracownika do samooceny </label>
                    </td>

                    <td>
					    <select id="ocena" name="ocena9" style="width: 300px">

                        <option value="0"></option>
						<option value="TAK">TAK</option>
						<option value="NIE">NIE</option>
						<option value="TAK I NIE">TAK I NIE</option>
					
					    </select>
                    </td>
				</div>
    </tr>
    <p></p>
    <tr>
				<div class="row">
		            <td>
					    <label for="ocena"> Postawa pracownika </label>
                    </td>

                    <td>
					    <select id="ocena" name="ocena10" style="width: 300px">

                            <option value="0"></option>
                            <option value="ROSZCZENIOWA">ROSZCZENIOWA</option>
                            <option value="UGODOWA">UGODOWA</option>
                            <option value="UGODOWO-ROSZCZENIOWA">UGODOWO-ROSZCZENIOWA</option>
					
					    </select>
                    </td>
				</div>
    </tr>
    <p></p>
    <tr>
				<div class="row">
                    <td>
					    <label for="komentarz"> Powierzenie auta </label>
                    </td>
                    <td>
					<textarea name="komentarz1" id="komentarz" rows="1" style="width:295px;resize:none;height:20px;"></textarea>
                    </td>
				</div>
    </tr>
    <p></p>
    <tr>

				<div class="row">
                    <td>
					    <label for="komentarz"> Uwagi </label>
                    </td>

                    <td>
					    <textarea name="komentarz2" id="komentarz" rows="4" style="width: 295px;resize: vertical;height:50px;"></textarea>
                    </td>
				</div>
				
				<input type="hidden" name="token" value="ug3f5jgh5f7d87874fdg4ddgh">
    </tr>
    <p></p>
	<tr>
				<div class="row">
                    <td>
					    <input type="submit" value="Zapisz!"  style="width: 150px;background-color: #eeca95">
                    </td>

                    <td>
					    <input type="reset" value="Wyczyść formularz" style="width: 150px;">
<!--                    </td>-->
<!---->
<!--                    <td>-->
<!--                        <form method="get" action="logout.php">-->

<!--                            <button type="submit" style="width: 150px;">wyloguj</button>-->
<!--                        </form>-->
                    </td>
				</div>
    </tr>


</table>

                <a href="logout.php" style="color: #fa5800; ">koniec
<!--                    <img src="/img/Alien.png"  style="width:42px;height:42px;">-->
                    <i class="fa fa-download"></i>
                </a>

<!--                <button type="submit" style="font-size:24px">koniec <i class="fa fa-download"></i></button>-->

            </form>
			
		</article>



	</main>



</body>
</html>