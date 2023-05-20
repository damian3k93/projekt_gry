<?php
	ob_start();
	session_start();
	require_once('functions/gameFunctions.php');
	require_once('cron/cron.php');

	if (!empty($_SESSION['id'])) {
		checkUser($_SESSION['id']);
		$user = getUser($_SESSION['id']);
		checkWork(time());
		checkTrip(time());
		checkInventoryStamina();
		if (!empty($_GET['skill'])) addSkill($_GET['skill']);
		addLevel();
	} else {
		$user = array(); // Czyszczenie zmiennej gracza
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<script src="http://cdn.static.w3big.com/libs/jquery/2.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="css/style.css">
		<script src="js/jquery.min.js"></script>
		<title>Heroes</title>
	</head>
	<body style="background-color: gray;">
		<div class="container">
			<br/>
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					<a class="navbar-brand" href="index.php">HEROES</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<?php menubar($user) ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="google.pl">#Damian Milewski</a></li>
					</ul>
				</div>
			</nav>
			<?php statusbar($user); ?>
			<div class="panel panel-default" style="width: 70%; float: left;">
				<?php
				if (empty($_GET)) $_GET['a'] = 'home';
				
				if (!empty($user['id'])) {
					switch($_GET['a']){ // Funkcja wybierania pliku do załadowania
						case 'home': require_once('pages/home.php'); break; // Strona główna
						case 'table': require_once('pages/table.php'); break; // Strona główna rozgrywki
						case 'settings': require_once('pages/settings.php'); break; // Strona z ustawieniami konta
						case 'stats': require_once('pages/stats.php'); break; // Strona z statystykami gracza
						case 'tavern': require_once('pages/tavern.php'); break; // Strona karczmy
						case 'work': require_once('pages/work.php'); break; // Strona pracy
						case 'mail': require_once('pages/mail.php'); break; // Strona poczty
						case 'map': require_once('pages/map.php'); break; // Strona z mapą
						case 'loc': require_once('pages/loc.php'); break; // Strona z info o lokacji
						case 'trip': require_once('pages/trip.php'); break; // Strona wyprawy
						case 'rank': require_once('pages/rank.php'); break; // Strona z rankingiem
						case 'inv': require_once('pages/inventory.php'); break; // Strona ekwipunku
						case 'shop': require_once('pages/shop.php'); break; // Strona sklepu
						case 'arena': require_once('pages/arena.php'); break; // Arena
						case 'blacksmith': require_once('pages/blacksmith.php'); break; // Kowal
						case 'changelog': require_once('pages/changelog.php'); break; // Pełna lista zmian (changelog)
						case 'version': require_once('pages/version.php'); break; // Strona o wersji (changelog)
						case 'log_out': // Wyloguj
							$_SESSION = array();
							$_COOKIE = array();
							session_destroy();
							header("Location: index.php"); // Przeniesienie na stronę główną
						break;
						default:
							require_once('pages/table.php'); 
							$_GET['a'] = 'table';
						break;
					}
				} else {
					switch($_GET['a']){ 
						case 'home': require_once('pages/home.php'); break;
						case 'login': require_once('pages/login.php'); break; // Strona logowania
						case 'register': require_once('pages/register.php'); break; // Strona rejestracji
						case 'changelog': require_once('pages/changelog.php'); break; // Pełna lista zmian (changelog)
						case 'version': require_once('pages/version.php'); break; // Strona o wersji (changelog)
						default: require_once('pages/home.php'); break;
					}
				}
				?>
			</div>
			<!-- Kolumna poboczna -->
			<?php
			sideColumn($user);
			//cookie_info();
			?>
		</div>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>

<?php
	$db = null; // Zamknięcie połączenia z bazą danych
	ob_end_flush();
?>