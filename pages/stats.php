
<?php
if (isset($_GET['g']) && !isset($_GET['p'])) {
	$isPlayer = false;
	$id = vtxt($_GET['g']);
	
	$sql = 'SELECT * FROM guilds WHERE id=:id';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$target = $stmt->fetch();
	unset($stmt);
	
	if ($target == NULL) {
		$isPlayer = true;
		$target = getUser($_SESSION['id']);
		$damage = getPlayerDamage($target['id']);
		if (is_array($damage))
			$dam = $damage[0].' - '.$damage[1].' (~'.floor(avg($damage)).')';
		else
			$dam = $damage;
		
		$health = getPlayerHealth($target['id']);
		$resist = getPlayerResist($target['id'], $target['id']);
		$dodgeM = getPlayerDodge2($target['id'], 'MAG');
		$dodgeL = getPlayerDodge2($target['id'], 'ŁOTR');
		$dodgeW = getPlayerDodge2($target['id'], 'WOJOWNIK');
		
		$critical = getPlayerCritical($target['id'], $target['id']);
		
	} else {
		$sql = 'SELECT Count(*) AS ilosc FROM users WHERE guild=:gid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':gid', $id, PDO::PARAM_INT);
		$stmt->execute();
		$pcount = $stmt->fetch();
		unset($stmt);
		
		$sql = 'SELECT login FROM users WHERE id =:ownerid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':ownerid', $target['ownerid'], PDO::PARAM_INT);
		$stmt->execute();
		$leader = $stmt->fetch();
		unset($stmt);
	}
} elseif (isset($_GET['p']) && !isset($_GET['g']) && $_GET['p'] != $_SESSION['id']) {
	$isPlayer = true;
	$target = getUser(vtxt($_GET['p']));
	if ($target == NULL)
		$target = getUser($_SESSION['id']);
	$damage = getPlayerDamage($target['id']);
	if (is_array($damage))
		$dam = $damage[0].' - '.$damage[1].' (~'.floor(avg($damage)).')';
	else
		$dam = $damage;
	
	$health = getPlayerHealth($target['id']);
	$resist = getPlayerResist($target['id'], $target['id']);
	$dodgeM = getPlayerDodge2($target['id'], 'MAG');
	$dodgeL = getPlayerDodge2($target['id'], 'ŁOTR');
	$dodgeW = getPlayerDodge2($target['id'], 'WOJOWNIK');
	$critical = getPlayerCritical($target['id'], $target['id']);
	
} else {
	$isPlayer = true;
	$target = getUser($_SESSION['id']);
	$damage = getPlayerDamage($target['id']);
	if (is_array($damage))
		$dam = $damage[0].' - '.$damage[1].' (~'.floor(avg($damage)).')';
	else
		$dam = $damage;
	
	$health = getPlayerHealth($target['id']);
	$resist = getPlayerResist($target['id'], $target['id']);
	$dodgeM = getPlayerDodge2($target['id'], 'MAG');
	$dodgeL = getPlayerDodge2($target['id'], 'ŁOTR');
	$dodgeW = getPlayerDodge2($target['id'], 'WOJOWNIK');
	
	$critical = getPlayerCritical($target['id'], $target['id']);
}

if ($isPlayer): ?>
<div class="panel-heading">
	<h3 class="panel-title"><b>Statystyki</b></h3>
</div>
<div class="panel-body" style="width: 35%; float: left;">
	<div class="panel panel-default">
		<div class="panel-body"><?=avatar($target['id']);?></div>
	</div>
</div>
<div class="panel-body" style="width: 65%; float: right;">
	<div class="well">
		<legend>Informacje o użytkowniku: <b><?=$target['login'];?></b></legend>
		<table width="100%">
			<tr>
				<td style="border-bottom:dashed 1px #000">Zdrowie:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$health;?></span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Obrażenia:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$dam;?></span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Uniki przeciw magom:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$dodgeM;?>%</span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Uniki przeciw łotrom:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$dodgeL;?>%</span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Uniki przeciw wojownikom:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$dodgeW;?>%</span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Pancerz:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$resist;?>%</span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Cios krytyczny:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$critical;?>%</span></td>
			</tr>
		</table>
	</div>
	<?php if (isset($_GET['p']) && $_GET['p'] != $_SESSION['id']): ?>
		<?=($target['status'] == 0 && $target['pos_x'] == $user['pos_x'] && $target['pos_y'] == $target['pos_y']) ? '<a href="index.php?a=arena&nick='.$target['login'].'" class="btn btn-primary">Walcz</a>' : '';?>
	<?php endif; ?>
</div>
<?php else: ?>
<div class="panel-heading">
	<h3 class="panel-title">Statystyki gildii <b><?=$target['tag'];?> <?=$target['name'];?></b></h3>
</div>
<div class="panel-body" style="width: 35%; float: left;">
	<div class="panel panel-default">
		<div class="panel-body"><?=guild_avatar($target['id']);?></div>
	</div>
</div>
<div class="panel-body" style="width: 65%; float: right;">
	<div class="well">
		<legend>Informacje o ugrupowaniu</legend>
		<table width="100%">
			<tr>
				<td style="border-bottom:dashed 1px #000">Nazwa:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$target['name'];?></span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Tag:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$target['tag'];?></span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Lider:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$leader['login'];?></span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Członków:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pcount['ilosc'];?></span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Poziom:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$target['lvl'];?></span></td>
			</tr>
			<tr>
				<td style="border-bottom:dashed 1px #000">Reputacja:</td>
				<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$target['rep'];?></span></td>
			</tr>
		</table>
	</div>
	<?=(isset($_GET['g']) && $user['guild'] == 0) ? '<a href="index.php?a=guild&s=join&gtag='.$target['tag'].'" class="btn btn-primary">Aplikuj</a>' : ''; ?>
</div>
<?php endif; ?>