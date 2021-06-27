<?php
session_start();

if (isset($_SESSION['logged_id'])) {
	header('Location: list.php');
	exit();
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
	
    <title>Panel użytkownika</title>
	
	<meta name="description" content="Oceny pracowników">
    <meta name="keywords" content="KOBNEXT,INTER-JKZ">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="/css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
	<link rel="icon" type="image/x-icon" href="/img/kbx.png" >
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="container">

        <header>
            <h2>Użytkownik</h2>
        </header>

        <main>
            <article>
                <form method="post" action="list.php">
                    <label>Login <input type="text" name="login"></label>
                    <label>Hasło <input type="password" name="pass"></label>
                    <input type="submit" value="Zaloguj się!">
					
					<?php
					if (isset($_SESSION['bad_attempt'])) {
						echo '<p>Niepoprawny login lub hasło!</p>';
						unset($_SESSION['bad_attempt']);
					}
					?>
					
                </form>
            </article>
        </main>

    </div>
</body>
</html>