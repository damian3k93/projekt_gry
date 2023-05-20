
<div class="panel-heading">
	<h3 class="panel-title">Arena</h3>
</div>
<div class="panel-body">
	<?php
	$location = getLocData($user['pos_x'], $user['pos_y']);
	if($location['id']==1 && $user['lvl']>25)
		throwInfo('danger', 'Masz za wysoki poziom, aby walczyć na arenie!', false);
	elseif ($location['id']==2 && $user['lvl']>60)
		throwInfo('danger', 'Masz za wysoki poziom, aby walczyć na arenie!', false);
	elseif ($location['id']==3 && $user['lvl']>95)
		throwInfo('danger', 'Masz za wysoki poziom, aby walczyć na arenie!', false);
	elseif ($user['status'] != 0 || $user['ap'] < 0)
		throwInfo('danger', 'Upewnij się, że spełniasz wszystkie warunki, aby wejść na arenę!', false);
	elseif (!isset($_GET['nick'])) { ?>
	<div class="well">
		<form action="index.php" method="GET" class="form-horizontal">
			<fieldset>
				<input type="hidden" name="a" value="arena">
				<div class="form-group">
					<label class="col-lg-2 control-label">Nick gracza</label>
					<div class="col-lg-10">
						<input type="text" name="nick" style="text-align: center;" class="form-control" placeholder="Wpisz nick przeciwnika">
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-6">
						<button type="submit" class="btn btn-primary">Walcz</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<?php } else {
		$nick = vtxt($_GET['nick']);
		if (empty($nick))
			throwInfo('danger', 'Nie wybrano przeciwnika!', false);
		elseif (strtolower($user['login']) == strtolower($nick))
			throwInfo('danger', 'Nie możesz walczyć ze sobą!', false);
		elseif ($user['ap'] < 1)
			throwInfo('danger', 'Nie masz siły na walkę na arenie!', false);
		
		else {
			//$enemy = row("SELECT * FROM users WHERE login = '".$nick."'");
			$sql = 'SELECT * FROM users WHERE login =:login';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':login', $nick, PDO::PARAM_STR);
			$stmt->execute();
			$enemy = $stmt->fetch();
			
			if (empty($enemy))
				throwInfo('danger', 'Podany gracz nie istnieje!', false);
			elseif ($enemy['status'] != 0)
				throwInfo('danger', 'Podany gracz jest teraz czymś zajęty!', false);
			elseif ($user['pos_x'] != $enemy['pos_x'] || $user['pos_y'] != $enemy['pos_y'])
				throwInfo('danger', 'Tego gracza nie ma w tym mieście!', false);
			elseif ($user['guild'] != 0 && $enemy['guild'] != 0 && $user['guild'] == $enemy['guild'])
				throwInfo('danger', 'Nie możesz atakować członka swojej gildii!', false);
			elseif (abs($user['lvl']-$enemy['lvl'])>10)
				throwInfo('danger', 'Za duża różnica poziomów.', false);
			else {
				$pdamage = getPlayerDamage($user['id']);
				if (is_array($pdamage))
					$pdam = $pdamage[0].' - '.$pdamage[1].' (~'.floor(avg($pdamage)).')';
				else
					$pdam = $pdamage;
					
				$edamage = getPlayerDamage($enemy['id']);
				if (is_array($edamage))
					$edam = $edamage[0].' - '.$edamage[1].' (~'.floor(avg($edamage)).')';
				else
					$edam = $edamage;
					
				$phealth = getPlayerHealth($user['id']);
				$ehealth = getPlayerHealth($enemy['id']);	
				
				$presist = getPlayerResist($user['id'], $enemy['id']);
				$eresist = getPlayerResist($enemy['id'], $user['id']);
					
				$pdodge = getPlayerDodge($user['id'], $enemy['id']);
				$edodge = getPlayerDodge($enemy['id'], $user['id']);
					
				$pcritical = getPlayerCritical($user['id'], $enemy['id']);
				$ecritical = getPlayerCritical($enemy['id'], $user['id']);
					
				$sql = 'SELECT guilds.tag AS tag FROM guilds INNER JOIN users ON guilds.id = users.guild WHERE id =:uguild';
				
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':uguild', $user['guild'], PDO::PARAM_INT);
				$stmt->execute();
				$guild = $stmt->fetch();
				unset($stmt);
					
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':uguild', $enemy['guild'], PDO::PARAM_INT);
				$stmt->execute();
				$guild2 = $stmt->fetch();
				unset($stmt);
					
				$ptag = ($user['guild'] != 0) ? $guild['tag'] : "";
				$etag = ($enemy['guild'] != 0) ? $guild2['tag'] : "";
				?>
					
				<div class="well well-sm" style="width: 45%; float: left;"><?=avatar($user['id']);?></div>
				<div class="well well-sm" style="width: 45%; float: right;"><?=avatar($enemy['id']);?></div>
					
				<div class="panel-body" style="width: 45%; float: left;">
					<div class="well">
						<legend>Atakujący</legend>
						<table width="100%">
							<tr>
								<td style="border-bottom:dashed 1px #000">Bohater:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$ptag;?> <?=$user['login'];?></span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Zdrowie:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$phealth;?></span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Obrażenia:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pdam;?></span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Pancerz:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$presist;?>%</span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Unik:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pdodge;?>%</span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Cios krytyczny:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pcritical;?>%</span></td>
							</tr>
						</table>
					</div>
				</div>
					
				<div class="panel-body" style="width: 45%; float: right;">
					<div class="well">
						<legend>Broniący się</legend>
						<table width="100%">
							<tr>
								<td style="border-bottom:dashed 1px #000">Bohater:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$etag;?> <?=$enemy['login'];?></span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Zdrowie:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$ehealth;?></span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Obrażenia:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$edam;?></span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Pancerz:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$eresist;?>%</span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Unik:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$edodge;?>%</span></td>
							</tr>
							<tr>
								<td style="border-bottom:dashed 1px #000">Cios krytyczny:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$ecritical;?>%</span></td>
							</tr>
						</table>
					</div>
				</div>
				<br clear="both"/>
					
					<?php
					$i = 0;
					$pscore = 0;
					$escore = 0;
					
					while ($phealth > 0 && $ehealth > 0) {
						$i++;
					?>
					<div class="panel panel-default">
						<div class="panel-heading" style="text-align: center;"><b>Runda: <?=$i;?></b></div>
						<div class="panel-body">
						<?php
						$pmiss = ($edodge <= rand(1, 100)) ? false : true;
						if ($pmiss)
							throwInfo('success', 'Spudłowałeś!', false);
						else {
							if (is_array($pdamage))
								$patt = rand($pdamage[0], $pdamage[1]);
							else
								$patt = rand($pdamage-1, $pdamage+1);
							
							if ($pcritical >= rand(1, 100)) {
								$patt *= 2;
								throwInfo('success', 'Uderzenie krytyczne!', false);
							}
							
							$ehealth -= $patt;
							$pscore += $patt;
							throwInfo('success', 'Zadałeś '.$patt.' obrażeń!', false);
						}
						
						if ($phealth < 1 || $ehealth < 1) {
							echo '
						</div>
					</div>
							';
							break;
						}
						
						$emiss = ($pdodge <= rand(1, 100)) ? false : true;
						if ($emiss)
							throwInfo('danger', 'Wróg spudłował!', false);
						else {
							if (is_array($edamage)) $eatt = rand($edamage[0], $edamage[1]);
							else $eatt = rand($edamage-1, $edamage+1);
							
							if ($ecritical >= rand(1, 100)) {
								$eatt *= 2;
								throwInfo('danger', 'Uderzenie krytyczne!', false);
							}
							
							$phealth -= $eatt;
							$escore += $eatt;
							throwInfo('danger', 'Wróg zadał Ci '.$eatt.' obrażeń!', false);
						}
						
						echo '
						</div>
					</div>
						';
					}
					
					if ($pscore == $escore) {
						if ($user['guild'] != 0) {
							//call("UPDATE guilds SET rep = rep + 2 WHERE id = ".$user['guild']);
							$sql = 'SELECT rep FROM guild WHERE id =:gid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':gid', $user['guild'], PDO::PARAM_INT);
							$stmt->execute();
							$rep = $stmt->execute();
							unset($stmt);
							
							$sql = 'UPDATE guilds SET rep =:rep WHERE id =:gid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':rep', $rep['rep']+2, PDO::PARAM_INT);
							$stmt->bindValue(':gid', $user['guild'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);

							$prep = '<tr>
								<td style="border-bottom:dashed 1px #000">Reputacja:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">+2</span></td>
							</tr>';
						} else $prep = '';
						if ($enemy['guild'] != 0) {
							//call("UPDATE guilds SET rep = rep + 2 WHERE id = ".$enemy['guild']);
							$sql = 'SELECT rep FROM guild WHERE id =:gid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':gid', $enemy['guild'], PDO::PARAM_INT);
							$stmt->execute();
							$rep = $stmt->execute();
							unset($stmt);
							
							$sql = 'UPDATE guilds SET rep =:rep WHERE id =:gid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':rep', $rep['rep']+2, PDO::PARAM_INT);
							$stmt->bindValue(':gid', $enemy['guild'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							$erep = '<tr>
								<td style="border-bottom:dashed 1px #000">Reputacja:</td>
								<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">+2</span></td>
							</tr>';
						} else $erep = '';
						
						$xp_remis = 5;
						
						$pmsg = array(
							'login' => $etag.' '.$enemy['login'],
							'max_hp' => getPlayerHealth($enemy['id']),
							'dam' => $edam,
							'dodge' => $edodge,
							'critical' => $ecritical,
							'rounds' => $i,
							'score' => $pscore,
							'xp' => $xp_remis,
							'rep' => $prep
						);
						
						$emsg = array(
							'login' => $ptag.' '.$user['login'],
							'max_hp' => getPlayerHealth($user['id']),
							'dam' => $pdam,
							'dodge' => $pdodge,
							'critical' => $pcritical,
							'rounds' => $i,
							'score' => $escore,
							'xp' => $xp_remis,
							'rep' => $erep
						);
						
						sysMail($user['id'], '[REMIS]Raport z walki', array(true, $pmsg), 'arena');
						sysMail($enemy['id'], '[REMIS]Raport z walki', array(false, $emsg), 'arena');
						
						//call("UPDATE users SET hp = ".floor($user['max_hp'] * 0.1).", allxp = allxp + 5, xp = xp + 5, ap = ap - 1 WHERE id = ".$user['id']);
						$sql = 'UPDATE users SET all_xp=:allxp, xp=:xp, ap=:ap WHERE id=:uid';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':allxp',$user['all_xp']+5, PDO::PARAM_INT);
						$stmt->bindValue(':xp',$user['xp']+5, PDO::PARAM_INT);
						$stmt->bindValue(':ap',$user['ap']-1, PDO::PARAM_INT);
						$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
						$stmt->execute();
						unset($stmt);
						//call("UPDATE users SET hp = ".floor($enemy['max_hp'] * 0.1).", allxp = allxp + 5, xp = xp + 5 WHERE id = ".$enemy['id']);
						$sql = 'UPDATE users SET all_xp=:allxp, xp=:xp WHERE id=:uid';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':allxp',$enemy['all_xp']+5, PDO::PARAM_INT);
						$stmt->bindValue(':xp',$enemy['xp']+5, PDO::PARAM_INT);
						$stmt->bindValue(':uid', $enemy['id'], PDO::PARAM_INT);
						$stmt->execute();
						unset($stmt);
						
						
						$sql = 'SELECT stamina FROM inventory WHERE id=:wid';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':wid', getPlayerWeapon($user['id']), PDO::PARAM_INT);
						$stmt->execute();
						$stamina = $stmt->fetch();
						unset($stmt);
						//call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($user['id']));
						$sql = 'UPDATE inventory SET stamina=:stamina WHERE id=:wid';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':stamina', $stamina['stamina']-1, PDO::PARAM_INT);
						$stmt->bindValue(':wid', getPlayerWeapon($user['id']), PDO::PARAM_INT);
						$stmt->execute();
						unset($stmt);
						
						$sql = 'SELECT stamina FROM inventory WHERE id=:wid';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':wid', getPlayerWeapon($enemy['id']), PDO::PARAM_INT);
						$stmt->execute();
						$stamina = $stmt->fetch();
						unset($stmt);
						//call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($enemy['id']));
						$sql = 'UPDATE inventory SET stamina=:stamina WHERE id=:wid';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':stamina', $stamina['stamina']-1, PDO::PARAM_INT);
						$stmt->bindValue(':wid', getPlayerWeapon($enemy['id']), PDO::PARAM_INT);
						$stmt->execute();
						unset($stmt);
						?>
						
					<div class="panel-body" style="width: 60%; margin: 0 auto;">
						<div class="well">
							<legend>REMIS!</legend>
							<table width="100%">
								<tr>
									<td style="border-bottom:dashed 1px #000">Wynik:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pscore;?> pkt.</span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Wygrana XP:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">5 pkt.</span></td>
								</tr>
								<?=($user['guild'] != 0) ? $prep : '';?>
							</table>
						</div>
					</div>
						
						<?php
					} else {
						if ($pscore > $escore) {
							if ($user['guild'] != 0) {
								//call("UPDATE guilds SET rep = rep + 4 WHERE id = ".$user['guild']);
								$sql = 'SELECT rep FROM guild WHERE id =:gid';
								$stmt = $db->prepare($sql);
								$stmt->bindValue(':gid', $user['guild'], PDO::PARAM_INT);
								$stmt->execute();
								$rep = $stmt->execute();
								unset($stmt);
							
								$sql = 'UPDATE guilds SET rep =:rep WHERE id =:gid';
								$stmt = $db->prepare($sql);
								$stmt->bindValue(':rep', $rep['rep']+10, PDO::PARAM_INT);
								$stmt->bindValue(':gid', $user['guild'], PDO::PARAM_INT);
								$stmt->execute();
								unset($stmt);
								$prep = '<tr>
									<td style="border-bottom:dashed 1px #000">Reputacja:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">+10</span></td>
								</tr>';
							}
							else $prep = '';
							
							if ($enemy['guild'] != 0) {
								//$rep = row("SELECT rep FROM guilds WHERE id = ".$enemy['guild']);
								$sql = 'SELECT rep FROM guild WHERE id =:gid';
								$stmt = $db->prepare($sql);
								$stmt->bindValue(':gid', $enemy['guild'], PDO::PARAM_INT);
								$stmt->execute();
								$rep = $stmt->execute();
								unset($stmt);
								if ($rep['rep'] >= 10) {
									//call("UPDATE guilds SET rep = rep - 4 WHERE id = ".$enemy['guild']);
									$sql = 'UPDATE guilds SET rep =:rep WHERE id =:gid';
									$stmt = $db->prepare($sql);
									$stmt->bindValue(':rep', $rep['rep']-10, PDO::PARAM_INT);
									$stmt->bindValue(':gid', $enemy['guild'], PDO::PARAM_INT);
									$stmt->execute();
									unset($stmt);
									$points = 10;
								} elseif ($rep['rep'] > 0 && $rep['rep'] < 10) {
									//call("UPDATE guilds SET rep = rep - ".$rep['rep']." WHERE id = ".$enemy['guild']);
									$sql = 'UPDATE guilds SET rep =:rep WHERE id =:gid';
									$stmt = $db->prepare($sql);
									$stmt->bindValue(':rep', 0, PDO::PARAM_INT);
									$stmt->bindValue(':gid', $enemy['guild'], PDO::PARAM_INT);
									$stmt->execute();
									unset($stmt);
									$points = $rep['rep'];
								}
								$erep = '<tr>
									<td style="border-bottom:dashed 1px #000">Reputacja:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">-'.$points.'</span></td>
								</tr>';
							}
							else $erep = '';
							
							$xp_win = 8;
							$xp_lose = 2;
							
							$pmsg = array(
								'login' => $etag.' '.$enemy['login'],
								'max_hp' => getPlayerHealth($enemy['id']),
								'dam' => $edam,
								'dodge' => $edodge,
								'critical' => $ecritical,
								'rounds' => $i,
								'score' => $pscore.' do '.$escore,
								'xp' => $xp_win,
								'rep' => $prep
							);
							
							$emsg = array(
								'login' => $ptag.' '.$user['login'],
								'max_hp' => getPlayerHealth($user['id']),
								'dam' => $pdam,
								'dodge' => $pdodge,
								'critical' => $pcritical,
								'rounds' => $i,
								'score' => $escore.' do '.$pscore,
								'xp' => $xp_lose,
								'rep' => $erep
							);
							
							sysMail($user['id'], '[WYGRANA]Raport z walki', array(true, $pmsg), 'arena');
							sysMail($enemy['id'], '[PORAŻKA]Raport z walki', array(false, $emsg), 'arena');
							
							//call("UPDATE users SET hp = ".$user['hp'].", allxp = allxp + ".($user['lvl'] * 5).", xp = xp + ".($user['lvl'] * 5).", ap = ap - 1 WHERE id = ".$user['id']);
							$sql = 'UPDATE users SET all_xp=:allxp, xp=:xp, ap=:ap WHERE id=:uid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':allxp',$user['all_xp']+8, PDO::PARAM_INT);
							$stmt->bindValue(':xp',$user['xp']+8, PDO::PARAM_INT);
							$stmt->bindValue(':ap',$user['ap']-1, PDO::PARAM_INT);
							$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							//call("UPDATE users SET hp = ".floor($enemy['max_hp'] * 0.1).", allxp = allxp + 5, xp = xp + 5 WHERE id = ".$enemy['id']);
							$sql = 'UPDATE users SET all_xp=:allxp, xp=:xp WHERE id=:uid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':allxp',$enemy['all_xp']+2, PDO::PARAM_INT);
							$stmt->bindValue(':xp',$enemy['xp']+2, PDO::PARAM_INT);
							$stmt->bindValue(':uid', $enemy['id'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							//call("UPDATE users SET hp = ".floor($enemy['max_hp'] * 0.1).", allxp = allxp + 5, xp = xp + 5 WHERE id = ".$enemy['id']);
							
							//call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($user['id']));
							//call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($enemy['id']));
							$sql = 'SELECT stamina FROM inventory WHERE id=:wid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':wid', getPlayerWeapon($user['id']), PDO::PARAM_INT);
							$stmt->execute();
							$stamina = $stmt->fetch();
							unset($stmt);
							//call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($user['id']));
							$sql = 'UPDATE inventory SET stamina=:stamina WHERE id=:wid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':stamina', $stamina['stamina']-2, PDO::PARAM_INT);
							$stmt->bindValue(':wid', getPlayerWeapon($user['id']), PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							
							$sql = 'SELECT stamina FROM inventory WHERE id=:wid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':wid', getPlayerWeapon($enemy['id']), PDO::PARAM_INT);
							$stmt->execute();
							$stamina = $stmt->fetch();
							unset($stmt);
							//call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($enemy['id']));
							$sql = 'UPDATE inventory SET stamina=:stamina WHERE id=:wid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':stamina', $stamina['stamina']-1, PDO::PARAM_INT);
							$stmt->bindValue(':wid', getPlayerWeapon($enemy['id']), PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							?>
							
					<div class="panel-body" style="width: 45%; float: left;">
						<div class="well">
							<legend>WYGRANA!</legend>
							<table width="100%">
								<tr>
									<td style="border-bottom:dashed 1px #000">Wynik:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$pscore;?> do <?=$escore;?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Wygrana XP:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">8 pkt.</span></td>
								</tr>
								<?=($user['guild'] != 0) ? $prep : '';?>
							</table>
						</div>
					</div>
					<div class="well well-sm" style="width: 45%; float: right;"><?=avatar($user['id']);?></div>
					<br clear="both"/>
							<?php
						} else {
							if ($enemy['guild'] != 0) {
								//call("UPDATE guilds SET rep = rep + 4 WHERE id = ".$enemy['guild']);
								$sql = 'SELECT rep FROM guild WHERE id =:gid';
								$stmt = $db->prepare($sql);
								$stmt->bindValue(':gid', $enemy['guild'], PDO::PARAM_INT);
								$stmt->execute();
								$rep = $stmt->execute();
								unset($stmt);
							
								$sql = 'UPDATE guilds SET rep =:rep WHERE id =:gid';
								$stmt = $db->prepare($sql);
								$stmt->bindValue(':rep', $rep['rep']+10, PDO::PARAM_INT);
								$stmt->bindValue(':gid', $enemy['guild'], PDO::PARAM_INT);
								$stmt->execute();
								unset($stmt);
								$erep = '<tr>
									<td style="border-bottom:dashed 1px #000">Reputacja:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">+4</span></td>
								</tr>';
							}
							else $erep = '';
							
							if ($user['guild'] != 0) {
								//$rep = row("SELECT rep FROM guilds WHERE id = ".$user['guild']);
								$sql = 'SELECT rep FROM guild WHERE id =:gid';
								$stmt = $db->prepare($sql);
								$stmt->bindValue(':gid', $user['guild'], PDO::PARAM_INT);
								$stmt->execute();
								$rep = $stmt->execute();
								unset($stmt);
								
								if ($rep['rep'] >= 10) {
									//call("UPDATE guilds SET rep = rep - 4 WHERE id = ".$user['guild']);
									$sql = 'UPDATE guilds SET rep =:rep WHERE id =:gid';
									$stmt = $db->prepare($sql);
									$stmt->bindValue(':rep', $rep['rep']+10, PDO::PARAM_INT);
									$stmt->bindValue(':gid', $user['guild'], PDO::PARAM_INT);
									$stmt->execute();
									unset($stmt);
									$points = 4;
								} elseif ($rep['rep'] > 0 && $rep['rep'] < 10) {
									//call("UPDATE guilds SET rep = rep - ".$rep['rep']." WHERE id = ".$user['guild']);
									$sql = 'UPDATE guilds SET rep =:rep WHERE id =:gid';
									$stmt = $db->prepare($sql);
									$stmt->bindValue(':rep', 0, PDO::PARAM_INT);
									$stmt->bindValue(':gid', $user['guild'], PDO::PARAM_INT);
									$stmt->execute();
									unset($stmt);
									$points = $rep['rep'];
								}
								$prep = '<tr>
									<td style="border-bottom:dashed 1px #000">Reputacja:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">-'.$points.'</span></td>
								</tr>';
							}
							else 
								$prep = '';
							
							$xp_win = 8;
							$xp_lose = 2;
							
							$pmsg = array(
								'login' => $etag.' '.$enemy['login'],
								'max_hp' => getPlayerHealth($enemy['id']),
								'dam' => $edam,
								'dodge' => $edodge,
								'critical' => $ecritical,
								'rounds' => $i,
								'score' => $pscore.' do '.$escore,
								'xp' => $xp_lose,
								'rep' => $prep
							);
							
							$emsg = array(
								'login' => $ptag.' '.$user['login'],
								'max_hp' => getPlayerHealth($user['id']),
								'dam' => $pdam,
								'dodge' => $pdodge,
								'critical' => $pcritical,
								'rounds' => $i,
								'score' => $escore.' do '.$pscore,
								'xp' => $xp_win,
								'rep' => $erep
							);
							
							sysMail($user['id'], '[PORAŻKA]Raport z walki', array(true, $pmsg), 'arena');
							sysMail($enemy['id'], '[WYGRANA]Raport z walki', array(false, $emsg), 'arena');
							
							$sql = 'SELECT stamina FROM inventory WHERE id=:wid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':wid', getPlayerWeapon($user['id']), PDO::PARAM_INT);
							$stmt->execute();
							$stamina = $stmt->fetch();
							unset($stmt);
							//call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($user['id']));
							$sql = 'UPDATE inventory SET stamina=:stamina WHERE id=:wid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':stamina', $stamina['stamina']-2, PDO::PARAM_INT);
							$stmt->bindValue(':wid', getPlayerWeapon($user['id']), PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							
							$sql = 'SELECT stamina FROM inventory WHERE id=:wid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':wid', getPlayerWeapon($enemy['id']), PDO::PARAM_INT);
							$stmt->execute();
							$stamina = $stmt->fetch();
							unset($stmt);
							//call("UPDATE inventory SET stamina = stamina - 1 WHERE id = ".getPlayerWeapon($enemy['id']));
							$sql = 'UPDATE inventory SET stamina=:stamina WHERE id=:wid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':stamina', $stamina['stamina']-1, PDO::PARAM_INT);
							$stmt->bindValue(':wid', getPlayerWeapon($enemy['id']), PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							?>
							
					<div class="panel-body" style="width: 45%; float: left;">
						<div class="well">
							<legend>PRZEGRANA!</legend>
							<table width="100%">
								<tr>
									<td style="border-bottom:dashed 1px #000">Wynik:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;"><?=$escore;?> do <?=$pscore;?></span></td>
								</tr>
								<tr>
									<td style="border-bottom:dashed 1px #000">Wygrana XP:</td>
									<td style="border-bottom:dashed 1px #000; padding: 5px"><span style="float: right;">2 pkt.</span></td>
								</tr>
								<?=($user['guild'] != 0) ? $prep : '';?>
							</table>
						</div>
					</div>
					<div class="well well-sm" style="width: 45%; float: right;"><?=avatar($enemy['id']);?></div>
					<br clear="both"/>
							
							<?php
						}
					}
			}
		}
		?><a href="index.php?a=arena" class="btn btn-primary">Powrót</a>
	<?php } ?>
</div>