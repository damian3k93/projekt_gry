<?php

	$mysql_host = 'localhost'; 
	$port = '3306';
	$username = 'root';
	$password = '';
	$database = 'game';

	try
	{
		$db = new PDO('mysql:host='.$mysql_host.';dbname='.$database.';port='.$port, $username, $password,  array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}
	catch (PDOException $e)
	{
		print "Błąd połączenia z bazą!: " . $e->getMessage() . "<br/>";
		die();
	}

	function throwInfo($type, $msg, $dis = false) {
		$class = 'alert ';
		if ($dis)
			$class .= 'alert-dismissable ';
		if ($type == 'warning' || $type == 'danger' || $type == 'success' || $type == 'info')
			$class .= 'alert-'.$type;
		echo '
			<div class="'.$class.'">
		';
		if ($dis)
			echo '<button type="button" class="close" data-dismiss="alert">×</button>';
		echo '
				'.$msg.'
			</div>
		';
	}
	
	function vtxt($var) { // Funkcja zabezpieczająca dane wysyłane do bazy
		return trim($var);
	}
	
	function restoreEnergy(){
		global $db;
		
		$sql = 'UPDATE users SET energy=:energy';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':energy', 100, PDO::PARAM_INT);
		$stmt->execute();
		unset($stmt);
	}
	// informacje o graczu 
	function getUser($id){
		global $db;
		
		$sql = 'SELECT * FROM users WHERE id=:id';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$dataUser = $stmt->fetch();
		unset($stmt);
		
		return $dataUser;
	}
	
	// informacja czy gracz jest zalogowany
	function checkUser($sid){
		if(empty($sid))
			header("Location: index.php?a=login");
		else
			return $sid = (int)$sid;
	}
	// sprawdzenie czy gracz jest w pracy
	function checkWork($time){
		global $db;
		
		$zap = getUser($_SESSION['id']);
		$sql = 'SELECT * FROM work WHERE uid=:uid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uid', $zap[0], PDO::PARAM_INT);
		$stmt->execute();
		$work = $stmt->fetch(PDO::FETCH_ASSOC);
		unset($stmt);
		
		if($work && $work['start']!=0 && $time>=$work['end']){
			$location = getLocData($zap['pos_x'],$zap['pos_y']);
			
			$sql = 'UPDATE users SET status=:status, cash=:cash WHERE id=:id';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':status', 0, PDO::PARAM_INT);
			$stmt->bindValue(':cash', intval($zap['cash']+ $work['hours']*$location['re_cash']*$zap['lvl']), PDO::PARAM_INT);
			$stmt->bindValue(':id', $zap['id'], PDO::PARAM_INT);
			$stmt->execute();
			unset($stmt);
			
			$sql = 'DELETE FROM work WHERE uid=:uid';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':uid', $zap['id'], PDO::PARAM_INT);
			$stmt->execute();
			unset($stmt);	
		}
	}
	// odczytuje lokacje gracza
	function getLocData($x, $y){
		global $db;
		
		$sql = 'SELECT * FROM locations WHERE x=:x AND y=:y';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':x', $x, PDO::PARAM_INT);
		$stmt->bindValue(':y', $y, PDO::PARAM_INT);
		$stmt->execute();
		$locData = $stmt->fetch();
		unset($stmt);
		
		return $locData;
	}
	// sprawdzenie czy gracz jest w podróży
	function checkTrip($time){
		global $db;
		
		$sql = 'SELECT * FROM trips WHERE uid=:uid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
		$stmt->execute();
		$zap = $stmt->fetch(PDO::FETCH_ASSOC);
		unset($stmt);
		if($zap && $time>=$zap['end']){
			$sql = 'UPDATE users SET status=:status, pos_x=:pos_x, pos_y=:pos_y WHERE id=:id';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':status', 0, PDO::PARAM_INT);
			$stmt->bindValue(':pos_x', $zap['x'], PDO::PARAM_INT);
			$stmt->bindValue(':pos_y', $zap['y'], PDO::PARAM_INT);
			$stmt->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
			$stmt->execute();
			unset($stmt);
			
			$sql ='DELETE FROM trips WHERE uid=:uid';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':uid', $_SESSION['id'], PDO::PARAM_INT);
			$stmt->execute();
			unset($stmt);	
		}
	}
	// sprawdzenie wytrzymałości przedmiotów
	function checkInventoryStamina(){
		global $db;
		$sql = 'SELECT id FROM inventory WHERE used=:used AND stamina<:stamina';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':used', 1, PDO::PARAM_INT);
		$stmt->bindValue(':stamina', 1, PDO::PARAM_INT);
		$stmt->execute();
		while($item = $stmt->fetch(PDO::FETCH_ASSOC)){
			$sql = 'UPDATE inventory SET used=:used WHERE id=:id';
			$stmt2 = $db->prepare($sql);
			$stmt2->bindValue(':used', 0, PDO::PARAM_INT);
			$stmt2->bindValue(':id', $item['id'], PDO::PARAM_INT);
			$stmt2->execute();
		}
		unset($stmt);
		unset($stmt2);
	}
	// dodanie punktu statystyki
	function addSkill($skill){
		global $db;
		
		if($skill=='sta' or 'str' or $skill=='dex' or $skill=='intell' or $skill=='luck'){
			$zap = getUser($_SESSION['id']);
			if($zap['sp']>0){
				$skill = vtxt($skill);
				$sql = 'UPDATE users SET '.$skill.'=:skill, sp=:sp WHERE id=:id';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':skill', $zap[''.$skill.'']+1, PDO::PARAM_INT);
				$stmt->bindValue(':sp', $zap['sp']-1, PDO::PARAM_INT);
				$stmt->bindValue(':id', $zap['id'], PDO::PARAM_INT);
				$stmt->execute();
				unset($stmt);
			}
		}
	}
	// sprawdzenie stanu poziomu gracza
	function addLevel(){
		global $db;
		$zap = getUser($_SESSION['id']);
		if($zap['xp']>=$zap['max_xp'] && $zap['lvl']<100){
			$last = $zap['xp']-$zap['max_xp'];
			$xp1 = $zap['max_xp']*1.2;
			$lvl1 = $zap['lvl']+1;
			$sp1 = $zap['sp']+5;
			$sql = 'UPDATE users SET xp=:xp, max_xp=:max_xp, lvl=:lvl, sp=:sp WHERE id =:id';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':xp', $last, PDO::PARAM_INT);
			$stmt->bindValue(':max_xp', $xp1, PDO::PARAM_INT);
			$stmt->bindValue(':lvl', $lvl1, PDO::PARAM_INT);
			$stmt->bindValue(':sp', $sp1, PDO::PARAM_INT);
			$stmt->bindValue(':id', $zap['id'], PDO::PARAM_INT);
			$stmt->execute();
			unset($stmt);
		}
	}
	// wczytanie menu
	function menubar($user) {
		if (!empty($user['id'])) {
			echo '
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Postać</a>
				<ul class="dropdown-menu">
					<li><a href="index.php?a=stats">Statystyki</a></li>
					<li><a href="index.php?a=inv">Ekwipunek</a></li>
					<li><a href="index.php?a=mail">Poczta</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Miasto</a>
				<ul class="dropdown-menu">
					<li><a href="index.php?a=table">Okolica</a></li>
					<li><a href="index.php?a=work">Praca</a></li>
					<li><a href="index.php?a=arena">Arena</a></li>
					<li><a href="index.php?a=shop">Sklep</a></li>
					<li><a href="index.php?a=tavern">Karczma</a></li>
					<li><a href="index.php?a=blacksmith">Kowal</a></li>
				</ul>
			</li>
			<li><a href="index.php?a=map">Mapa</a></li>
			<li><a href="index.php?a=rank">Ranking</a></li>
			<li><a href="index.php?a=settings">Ustawienia</a></li>
			<li><a href="index.php?a=changelog">Lista zmian</a></li>
			<li><a href="index.php?a=log_out">Wyloguj się</a></li>
			';
		} else {
			echo '
			<li><a href="index.php?a=register">Zarejestruj się</a></li>
			<li><a href="index.php?a=login">Zaloguj się</a></li>
			<li><a href="index.php?a=changelog">Lista zmian</a></li>
			';
		}
	}
	
	function statusbar($user) {
		global $db;
		if (!empty($user['id'])) {
			echo '<div class="well well-sm" style="text-align: center">';
			
			$sql ='SELECT status, pos_x, pos_y FROM users WHERE id=:id';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
			$stmt->execute();
			$status = $stmt->fetch();
			unset($stmt);
			
			if ($status['status'] == 0) {
				$txt = 'przebywa';
				$location = getLocData($status['pos_x'], $status['pos_y']);
			} elseif ($status['status'] == 1) {
				$txt = 'wykonuje pracę';
				$location = getLocData($status['pos_x'], $status['pos_y']);
			} elseif ($status['status'] == 2) {
				$txt = 'odbywa wyprawę';
				//$trip = row("SELECT x, y FROM trips WHERE uid = ".$user['id']);
				$sql = 'SELECT x, y FROM trips WHERE uid=:uid';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
				$stmt->execute();
				$trip = $stmt->fetch();
				unset($stmt);
				$dest = getLocData($trip['x'], $trip['y']);
			} elseif ($status['status'] == 3) {
				$txt = '<b>przesiaduje w karczmie</b>';
			}
			
			echo 'Aktualnie Twój bohater <b>'.$txt.'</b>';
			if (isset($location))
				echo ' w <b>'.$location['name'].'</b>.';
			elseif (isset($dest))
				echo ' do <b>'.$dest['name'].'</b>';
			else
				echo '.';
			echo '</div>';
		}
	}
	
	function sideColumn($user) {
		if (!empty($user['id'])) {
			$user = getUser($user['id']); 
			require_once('functions/var.php');
			echo '
			<div class="panel panel-default" style="width: 28%; float: right;">
				<div class="panel-heading">
					<h3 class="panel-title"><b>Bohater:</b><span style="float: right;">'.$user['login'].'</span></h3>
				</div>
				<div class="panel-body">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">'.$user['class'].'<span style="float: right;">Poziom: <b>'.$user['lvl'].'</b></span></h3>
						</div>
						<div class="panel-body">
							<div class="progress">
								<div class="progress-bar progress-bar-danger" style="width: 100%;"><span>Zdrowie: '.$hp.'</span></div>
							</div>
							<div class="progress">
								<div class="progress-bar progress-bar-warning" style="width: '.$pEn.'%;"><span>Energia '.$user['energy'].' / '.$fen.'</span></div>
							</div>
							<div class="progress">
								';
								if ($user['lvl'] < 100)
									echo '<div class="progress-bar progress-bar-success" style="width: '.$pbpd.'%;"><span>Exp: '.$user['xp'].' / '.$user['max_xp'].'</span></div>';
								else
									echo '<div class="progress-bar progress-bar-success" style="width: 100%;"><span>PD: '.$user['xp'].'</span></div>';
								echo '
							</div>
							<div class="progress">
								<div class="progress-bar progress-bar-info" style="width: '.$pAp.'%;"><span>Punkty Walki: '.$user['ap'].' / '.$fap.'</span></div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$user['cash'].'" disabled>
									<span class="input-group-addon">$</span>
								</div>
								<div class="input-group">
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$user['diamonds'].'" disabled>
									<span class="input-group-addon">D</span>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Umiejętności:<span style="float: right;"><u>'.$user['sp'].' pkt.</u></span></h3>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon" style="width: 60%;">WYTRZYMAŁOŚĆ</span>
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$stats['sta'].'" disabled>
									'.$wybutton.'
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon" style="width: 60%;">SIŁA</span>
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$stats['str'].'" disabled>
									'.$sibutton.'
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon" style="width: 60%;">ZRĘCZNOŚĆ</span>
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$stats['dex'].'" disabled>
									'.$zrbutton.'
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon" style="width: 60%;">INTELIGENCJA</span>
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$stats['intell'].'" disabled>
									'.$inbutton.'
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon" style="width: 60%;">SZCZĘŚCIE</span>
									<input type="text" style="text-align: right;" class="form-control" id="disabledInput" placeholder="'.$stats['luck'].'" disabled>
									'.$szbutton.'
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br clear="both"/>
			';
		} else echo '<br clear="both"/>';
	}
	
	function cookieInfo(){
		if (empty($_COOKIE['cookies_enabled'])) {
			if (!empty($_GET['a'])) $link = '<a href="cookie.php?a='.$_GET['a'].'">'; else $link = '<a href="cookie.php">';
			echo '
			<div class="alert alert-dismissable alert-warning">
				<button type="button" class="close" data-dismiss="alert">X</button>
				<h4>Uwaga!</h4>
				<p>Ta strona korzysta z "ciasteczek", czyli danych przechowywanych w Twojej przeglądarce.
				Jeżeli nie jesteś pewien co to oznacza, przeczytaj <a href="http://wszystkoociasteczkach.pl/polityka-cookies/">
				<b>Politykę prywatności Cookies</b></a>.<br/>
				Nie chcesz widzieć tego komunikatu? Kliknij '.$link.'<b>tutaj</b></a>.</p>
			</div>
			';
		}
	}
	
	function locName($type) {
		if ($type == 'village')
			return 'Wioska';
		elseif ($type == 'city')
			return 'Miasteczko';
		elseif ($type == 'polis')
			return 'Metropolia';
	}
	
	function avatar($id) {
		if (isset($id))
			$zapytanie = getUser($id);
		else
			$zapytanie = getUser($_SESSION['id']);
		
		if (isset($zapytanie['avatar']) && $zapytanie['avatar'] == 1)
			return '<center><img width="200px" height="200px" src="avatar/'.$zapytanie['id'].'.png" alt=""/></center>';
		else
			return '<center><img width="200px" height="200px" src="avatar/avatar.png" alt=""/></center>';
	}
	
	function distance($x, $y, $ux, $uy) {
		return round(sqrt(pow(($ux - $x), 2) + pow(($uy - $y), 2)) * 100, 1);
	}
	
	function avg($arr) { // Funkcja licząca średnią
		if (!is_array($arr))
			return false;
		return array_sum($arr) / count($arr);
	}
	
	function statsItems($uid){
		global $db;
		$sql = 'SELECT * FROM inventory WHERE uid=:id AND used=:used';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $uid, PDO::PARAM_INT);
		$stmt->bindValue(':used', 1, PDO::PARAM_INT);
		$stmt->execute();
		
		
		$sumSt = array();
		$sta = $str = $dex = $intell = $luck = 0;
		while($dataItems = $stmt->fetch()){
			$sta +=$dataItems['sta'];
			$str +=$dataItems['str'];
			$dex +=$dataItems['dex'];
			$intell +=$dataItems['intell'];
			$luck +=$dataItems['luck'];
		}
		unset($stmt);
		$sumSt = array($sta, $str, $dex, $intell, $luck);
		return $sumSt;
	}
	
	function stats($id){
		$data = getUser($id);
		$sumSt = statsItems($id);
		
		$stats = array();
		
		$stats['sta'] = $data['sta'] + $sumSt[0];
		$stats['str'] = $data['str'] + $sumSt[1];
		$stats['dex'] = $data['dex'] + $sumSt[2];
		$stats['intell'] = $data['intell'] + $sumSt[3];
		$stats['luck'] = $data['luck'] + $sumSt[4];
		
		return $stats;
	}
	
	function getPlayerDamage($id) {
		global $db;
		
		if (!isset($id))
			return;
		
		$data = getUser($id);
		if (!$data)
			return;
		
		//$weapon = row("SELECT items.min_dmg, items.max_dmg FROM items INNER JOIN inventory ON items.id = inventory.obj WHERE inventory.uid = ".$data['id']." AND inventory.used = 1 AND items.type = 'weapon' LIMIT 1");
		$sql = 'SELECT items.min_dmg, items.max_dmg FROM items INNER JOIN inventory ON items.id = inventory.obj WHERE inventory.uid=:iuid AND inventory.used =:used AND items.type=:itype LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':iuid', $data['id'], PDO::PARAM_INT);
		$stmt->bindValue(':used', 1, PDO::PARAM_INT);
		$stmt->bindValue(':itype', 'weapon', PDO::PARAM_STR);
		$stmt->execute();
		$weapon = $stmt->fetch();
		unset($stmt);
		
		if ($weapon) {
			if($data['class'] == 'MAG'){
				$weapon['min_dmg'] *= (1 + $data['intell'] / 10);
				$weapon['max_dmg'] *= (1 + $data['intell'] / 10);
			}
			elseif ($data['class'] == 'ŁOTR'){
				$weapon['min_dmg'] *= (1 + $data['dex'] / 10);
				$weapon['max_dmg'] *= (1 + $data['dex'] / 10);
			}
			elseif ($data['class'] == 'WOJOWNIK'){
				$weapon['min_dmg'] *= (1 + $data['str'] / 10);
				$weapon['max_dmg'] *= (1 + $data['str'] / 10);
			}
			
			return array(round($weapon['min_dmg']), round($weapon['max_dmg']));
			
		} else
			if($data['class'] == 'MAG')
				return 1+ $data['intell'] / 10;
			elseif ($data['class'] == 'ŁOTR')
				return 1+ $data['dex'] / 10;
			elseif ($data['class'] == 'WOJOWNIK')
				return 1+ $data['str'] / 10;
	}
	
	function getPlayerDodge($uid, $opid) {
		global $db;
		
		if (!isset($uid) && !isset($opid))
			return;
		$data = getUser($uid);
		
		$sql = 'SELECT class FROM users WHERE id=:opid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':opid', $opid, PDO::PARAM_INT);
		$stmt->execute();
		$opponent = $stmt->fetch();
		unset($stmt);
		
		if (!$data)
			return;
		// mag vs łotr
		if ($data['class'] == 'MAG' && $opponent['class'] == 'ŁOTR'){
			$dodge = 5 + $data['dex'] / 5;
			if($dodge>25)
				return 25;
			else return $dodge;
		}
		// mag vs wojownik
		elseif ($data['class'] == 'MAG' && $opponent['class'] == 'WOJOWNIK'){
			$dodge = 5 + $data['str'] / 5;
			if($dodge>25)
				return 25;
			else return $dodge;
		}
		// łotr vs mag
		elseif ($data['class'] == 'ŁOTR' && $opponent['class'] == 'MAG'){
			$dodge = 10 + $data['intell'] / 5;
			if($dodge>25)
				return 25;
			else return $dodge;
		}
		// łotr vs wojownik
		elseif ($data['class'] == 'ŁOTR' && $opponent['class'] == 'WOJOWNIK'){
			$dodge = 10 + $data['str'] / 5;
			if($dodge>25)
				return 25;
			else return $dodge;
		}
		// wojownik vs mag
		elseif ($data['class'] == 'WOJOWNIK' && $opponent['class'] == 'MAG'){
			$dodge = 15 + $data['intell'] / 5;
			if($dodge>25)
				return 25;
			else return $dodge;
		}
		// wojownik vs łotr
		elseif ($data['class'] == 'WOJOWNIK' && $opponent['class'] == 'ŁOTR'){
			$dodge = 15 + $data['dex'] / 5;
			if($dodge>25)
				return 25;
			else return $dodge;
		}
		else return 15;
		
	}
	
	function getPlayerDodge2($uid, $clas) {
		global $db;
		if (!isset($uid) && !isset($clas))
			return;
		$data = getUser($uid);
		
		unset($stmt);
		
		if (!$data)
			return;
		// mag vs łotr
		if ($data['class'] == 'MAG' && $clas == 'ŁOTR'){
			$dodge = 5 + $data['dex'] / 5;
			if($dodge>25)
				return 25;
			else return round($dodge);
		}
		// mag vs wojownik
		elseif ($data['class'] == 'MAG' && $clas == 'WOJOWNIK'){
			$dodge = 5 + $data['str'] / 5;
			if($dodge>25)
				return 25;
			else return round($dodge);
		}
		// łotr vs mag
		elseif ($data['class'] == 'ŁOTR' && $clas == 'MAG'){
			$dodge = 10 + $data['intell'] / 5;
			if($dodge>25)
				return 25;
			else return round($dodge);
		}
		// łotr vs wojownik
		elseif ($data['class'] == 'ŁOTR' && $clas == 'WOJOWNIK'){
			$dodge = 10 + $data['str'] / 5;
			if($dodge>25)
				return 25;
			else return round($dodge);
		}
		// wojownik vs mag
		elseif ($data['class'] == 'WOJOWNIK' && $clas == 'MAG'){
			$dodge = 15 + $data['intell'] / 5;
			if($dodge>25)
				return 25;
			else return round($dodge);
		}
		// wojownik vs łotr
		elseif ($data['class'] == 'WOJOWNIK' && $clas == 'ŁOTR'){
			$dodge = 15 + $data['dex'] / 5;
			if($dodge>25)
				return 25;
			else return round($dodge);
		}
		else return 15;
		
	}
	
	function getPlayerCritical($uid, $opid) {
		global $db;
		
		if (!isset($uid) && !isset($opid))
			return;
		
		$data = getUser($uid);
		
		$sql = 'SELECT * FROM users WHERE id=:opid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':opid', $opid, PDO::PARAM_INT);
		$stmt->execute();
		$opponent = $stmt->fetch();
		unset($stmt);
		
		if (!$data)
			return;
		
		if ($data['class'] == 'MAG'){
			$crit = $data['luck']*5/$opponent['lvl']*2+20;
		}
		elseif ($data['class'] == 'ŁOTR'){
			$crit = $data['luck']*5/$opponent['lvl']*2+15;
		}
		elseif ($data['class'] == 'WOJOWNIK'){
			$crit = $data['luck']*5/$opponent['lvl']*2+10;
		}
		
		if($crit>50)
			return 50;
		else return round($crit);
	}
	
	function getPlayerHealth($id){
		global $db;
		if (!isset($id))
			return;
		$data = getUser($id);
		$stats = stats($id);
		
		if (!$data)
			return;
		
		if($data['class']=='MAG')
			return $hp = $stats['sta']*2*($data['lvl']+1);
		elseif ($data['class']=='ŁOTR')
			return $hp = $stats['sta']*4*($data['lvl']+1);
		elseif ($data['class']=='WOJOWNIK')
			return $hp = $stats['sta']*5*($data['lvl']+1);
	
	}
	
	function getPlayerResist($uid, $opid){
		global $db;
		
		$data = getUser($uid);
		
		$sql = 'SELECT items.resist AS res FROM items INNER JOIN inventory ON items.id = inventory.obj WHERE uid =:id AND used=:used';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $uid, PDO::PARAM_INT);
		$stmt->bindValue(':used', 1, PDO::PARAM_INT);
		$stmt->execute();
		$res = 0;

		while($s = $stmt->fetch()){
			$res +=$s['res'];
		}
		unset($stmt);

		$sql = 'SELECT lvl FROM users WHERE id =:opid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':opid', $opid, PDO::PARAM_INT);
		$stmt->execute();
		$poziom = $stmt->fetch();
		
		$resist = $res / $poziom['lvl'];
		
		if($data['class']=='MAG' && $resist > 15)
			return 15;
		elseif ($data['class']=='ŁOTR' && $resist > 25)
			return 25;
		elseif ($data['class']=='WOJOWNIK' && $resist > 35)
			return 35;
		else
			return round($resist);
	}
	
	function getPlayerWeapon($id) {
		global $db;
		if (!isset($id))
			return;
		$data = getUser($id);
		if (!$data)
			return;
		//$weapon = row("SELECT inventory.id FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE inventory.uid = ".$data['id']." AND inventory.used = 1 AND items.type = 'weapon' LIMIT 1");
		$sql = 'SELECT inventory.id FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE inventory.uid=:uid AND inventory.used=:used AND items.type=:type LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uid', $data['id'], PDO::PARAM_INT);
		$stmt->bindValue(':used', 1, PDO::PARAM_INT);
		$stmt->bindValue(':type', 'weapon', PDO::PARAM_STR);
		$stmt->execute();
		$weapon = $stmt->fetch();
		
		if ($weapon)
			return $weapon['id'];
		else
			return false;
	}
	
	function arena_template($type, $content) {
		$legend = ($type) ? 'Broniący się' : 'Atakujący';
		return '
		<div class="panel-body" style="width: 45%; float: left;">
			<div class="well">
				<legend>'.$legend.'</legend>
				<table width="100%">
					<tr>
						<td style="border-bottom:dashed 1px #000">Bohater:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['login'].'</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Zdrowie:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['max_hp'].'</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Obrażenia:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['dam'].'</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Unik:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['dodge'].'%</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Cios krytyczny:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['critical'].'%</span></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="panel-body" style="width: 45%; float: right;">
			<div class="well">
				<legend>Raport z walki</legend>
				<table width="100%">
					<tr>
						<td style="border-bottom:dashed 1px #000">Liczba rund:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['rounds'].'</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Wynik:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['score'].' pkt.</span></td>
					</tr>
					<tr>
						<td style="border-bottom:dashed 1px #000">Wygrana XP:</td>
						<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">'.$content['xp'].' pkt.</span></td>
					</tr>
					'.$content['rep'].'
				</table>
			</div>
		</div>
		<br clear="both"/>
		';
	}
	
	function sysMail($to, $title, $content, $type = false) {
		global $db;
		if (isset($to) && isset($title) && isset($content)) {
			if (!$type) {
				$to = (int)$to;
				$title = vtxt($title);
				//$content = vtxt($content);
				//call("INSERT INTO mail (from_id, to_id, type, title, content, date) VALUES (0, ".$to.", 1, '".$title."', '".$content."', now())");
				$sql = 'INSERT INTO mail (from_id, to_id, type, title, content, date) VALUES 
										 (:fid, :toid, :type, :title, :content, now())';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':fid', 0, PDO::PARAM_INT);	
				$stmt->bindValue(':toid', $to, PDO::PARAM_INT);
				$stmt->bindValue(':type', 1, PDO::PARAM_INT);
				$stmt->bindValue(':title', $title, PDO::PARAM_STR);
				$stmt->bindValue(':content', $content, PDO::PARAM_STR);
				$stmt->execute();
				
			} elseif ($type == 'arena') {
				$mail = arena_template($content[0], $content[1]);
				//call("INSERT INTO mail (from_id, to_id, type, title, content, date) VALUES (0, ".$to.", 1, '".$title."', '".$mail."', now())");
				$sql = 'INSERT INTO mail (from_id, to_id, type, title, content, date) VALUES 
										 (:fid, :toid, :type, :title, :content, now())';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':fid', 0, PDO::PARAM_INT);	
				$stmt->bindValue(':toid', $to, PDO::PARAM_INT);
				$stmt->bindValue(':type', 1, PDO::PARAM_INT);
				$stmt->bindValue(':title', $title, PDO::PARAM_STR);
				$stmt->bindValue(':content', $mail, PDO::PARAM_STR);
				$stmt->execute();
			}
		}
	}
?>