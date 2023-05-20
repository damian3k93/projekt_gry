
<div class="panel-heading">
	<h3 class="panel-title">Ekwipunek</h3>
</div>
<div class="panel-body">
	<?php
	if (!empty($_GET['item'])) {
		$item = $_GET['item'];
		if (!empty($item) && is_numeric($item)) {
			//$data = row("SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND inventory.id = ".$item);
			$sql = 'SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid=:uid AND inventory.id=:inid';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
			$stmt->bindValue(':inid', $item, PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch();
			
			if ($data) {
				if ($data['used'] == 1) {
					//call("UPDATE inventory SET used = 0 WHERE id = ".$data['iid']);
					$sql = 'UPDATE inventory SET used=:used WHERE id=:id';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':used', 0, PDO::PARAM_INT);
					$stmt->bindValue(':id', $data['iid'], PDO::PARAM_INT);
					$stmt->execute();
					throwInfo('success', 'Zdjęto przedmiot', true);
				} else {
					if ($data['stamina'] < 1)
						throwInfo('danger', 'Ten przedmiot jest zniszczony', true);
					else {
						//$is_wearing = row("SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 1 AND type = '".$data['type']."'");
						$sql = 'SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid =:uid AND used =:used AND type =:type';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
						$stmt->bindValue(':used', 1, PDO::PARAM_INT);
						$stmt->bindValue(':type', $data['type'], PDO::PARAM_STR);
						$stmt->execute();
						$is_wearing = $stmt->fetch();
						if ($is_wearing)
							throwInfo('danger', 'Postać już ma założony jakiś przedmiot', true);
						else {
							//call("UPDATE inventory SET used = 1 WHERE id = ".$data['iid']);
							$sql ='UPDATE inventory SET used=:used WHERE id=:id';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':used', 1, PDO::PARAM_INT);
							$stmt->bindValue(':id', $data['iid'], PDO::PARAM_INT);
							$stmt->execute();
							throwInfo('success', 'Założono przedmiot', true);
						}
					}
				}
			}
		}
	}
	
	//$weapon = arr("SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 1 AND type = 'weapon' LIMIT 1");
	$sql = 'SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid=:uid AND used=:used AND type=:type LIMIT 1';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 1, PDO::PARAM_INT);
	$stmt->bindValue(':type', 'weapon', PDO::PARAM_STR);
	$stmt->execute();
	$weapon = $stmt->fetch();
	
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 1, PDO::PARAM_INT);
	$stmt->bindValue(':type', 'helmet', PDO::PARAM_STR);
	$stmt->execute();
	$helmet = $stmt->fetch();
	
	//$armor = arr("SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 1 AND type = 'armor' LIMIT 1");
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 1, PDO::PARAM_INT);
	$stmt->bindValue(':type', 'armor', PDO::PARAM_STR);
	$stmt->execute();
	$armor = $stmt->fetch();
	
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 1, PDO::PARAM_INT);
	$stmt->bindValue(':type', 'shoes', PDO::PARAM_STR);
	$stmt->execute();
	$shoes = $stmt->fetch();
	
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 1, PDO::PARAM_INT);
	$stmt->bindValue(':type', 'gloves', PDO::PARAM_STR);
	$stmt->execute();
	$gloves = $stmt->fetch();
	
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 1, PDO::PARAM_INT);
	$stmt->bindValue(':type', 'belt', PDO::PARAM_STR);
	$stmt->execute();
	$belt = $stmt->fetch();
	
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 1, PDO::PARAM_INT);
	$stmt->bindValue(':type', 'necklace', PDO::PARAM_STR);
	$stmt->execute();
	$necklace = $stmt->fetch();
	
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 1, PDO::PARAM_INT);
	$stmt->bindValue(':type', 'ring', PDO::PARAM_STR);
	$stmt->execute();
	$ring = $stmt->fetch();
	
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 1, PDO::PARAM_INT);
	$stmt->bindValue(':type', 'talisman', PDO::PARAM_STR);
	$stmt->execute();
	$talisman = $stmt->fetch();
	
	//$neck = arr("SELECT * FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 1 AND type = 'neck' LIMIT 1");
	?>
	<div class="well well-sm" style="padding:10px;">
		<div class="panel panel-default" style="width:230px; margin:10px; float:left;">
			<div class="panel-body" style="text-align:center; ">
				Broń podstawowa<br/>
				<?php if ($weapon): ?>
				<a href="index.php?a=inv&item=<?=$weapon[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$weapon['type'];?>/<?=$weapon['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
					<?php if ($weapon['stamina'] <= 30): ?>
						<div class="progress-bar progress-bar-danger" style="width:<?=$weapon['stamina'];?>%;"><span><?=$weapon['stamina'];?>%</span></div>
					<?php else: ?>
						<div class="progress-bar" style="width:<?=$weapon['stamina'];?>%;"><span><?=$weapon['stamina'];?>%</span></div>
					<?php endif; ?>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
			<div class="panel-body" style="text-align:left;">
				<?php if ($weapon): ?>
				<div class="form-group">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Obrażenia:  <?=$weapon['min_dmg'];?>-<?=$weapon['max_dmg'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Wytrzymałość:  <?=$weapon['sta'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Siła:  <?=$weapon['str'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Zręczność:  <?=$weapon['dex'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Inteligencja:  <?=$weapon['intell'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Szczęście:  <?=$weapon['luck'];?>">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<div class="panel panel-default" style="width:230px; float:left; margin:5px;">
			<div class="panel-body" style="text-align:center;">
				Hełm<br/>
				<?php if ($helmet): ?>
				<a href="index.php?a=inv&item=<?=$helmet[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$helmet['type'];?>/<?=$helmet['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$helmet['stamina'];?>%;"><span><?=$helmet['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
			<div class="panel-body" style="text-align:left;">
				<?php if ($helmet): ?>
				<div class="form-group">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Pancerz:  <?=$helmet['resist'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Wytrzymałość:  <?=$helmet['sta'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Siła:  <?=$helmet['str'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Zręczność:  <?=$helmet['dex'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Inteligencja:  <?=$helmet['intell'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Szczęście:  <?=$helmet['luck'];?>">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<div class="panel panel-default" style="width:230px; float:left; margin:10px;">
			<div class="panel-body" style="text-align:center;">
				Zbroja<br/>
				<?php if ($armor): ?>
				<a href="index.php?a=inv&item=<?=$armor[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$armor['type'];?>/<?=$armor['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$armor['stamina'];?>%;"><span><?=$armor['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
			<div class="panel-body" style="text-align:left;">
				<?php if ($armor): ?>
				<div class="form-group">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Pancerz:  <?=$armor['resist'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Wytrzymałość:  <?=$armor['sta'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Siła:  <?=$armor['str'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Zręczność:  <?=$armor['dex'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Inteligencja:  <?=$armor['intell'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Szczęście:  <?=$armor['luck'];?>">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>	
		<br clear="both"/>	
		
		<div class="panel panel-default" style="width:230px; float:left; margin:10px;">
			<div class="panel-body" style="text-align:center;">
				Rękawice<br/>
				<?php if ($gloves): ?>
				<a href="index.php?a=inv&item=<?=$gloves[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$gloves['type'];?>/<?=$gloves['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$gloves['stamina'];?>%;"><span><?=$gloves['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
			<div class="panel-body" style="text-align:left;">
				<?php if ($gloves): ?>
				<div class="form-group">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Pancerz:  <?=$gloves['resist'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Wytrzymałość:  <?=$gloves['sta'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Siła:  <?=$gloves['str'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Zręczność:  <?=$gloves['dex'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Inteligencja:  <?=$gloves['intell'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Szczęście:  <?=$gloves['luck'];?>">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<div class="panel panel-default" style="width:230px; float:left; margin:5px;">
			<div class="panel-body" style="text-align:center;">
				Pas<br/>
				<?php if ($belt): ?>
				<a href="index.php?a=inv&item=<?=$belt[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$belt['type'];?>/<?=$belt['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$belt['stamina'];?>%;"><span><?=$belt['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
			<div class="panel-body" style="text-align:left;">
				<?php if ($belt): ?>
				<div class="form-group">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Pancerz:  <?=$belt['resist'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Wytrzymałość:  <?=$belt['sta'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Siła:  <?=$belt['str'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Zręczność:  <?=$belt['dex'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Inteligencja:  <?=$belt['intell'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Szczęście:  <?=$belt['luck'];?>">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>	
		<div class="panel panel-default" style="width:230px; float:left; margin:10px;">
			<div class="panel-body" style="text-align:center;">
				Buty<br/>
				<?php if ($shoes): ?>
				<a href="index.php?a=inv&item=<?=$shoes[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$shoes['type'];?>/<?=$shoes['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$shoes['stamina'];?>%;"><span><?=$shoes['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
			<div class="panel-body" style="text-align:left;">
				<?php if ($shoes): ?>
				<div class="form-group">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Pancerz:  <?=$shoes['resist'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Wytrzymałość:  <?=$shoes['sta'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Siła:  <?=$shoes['str'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Zręczność:  <?=$shoes['dex'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Inteligencja:  <?=$shoes['intell'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Szczęście:  <?=$shoes['luck'];?>">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<br clear="both"/>
		
		<div class="panel panel-default" style="width:230px; float:left; margin:10px;">
			<div class="panel-body" style="text-align:center;">
				Naszyjnik<br/>
				<?php if ($necklace): ?>
				<a href="index.php?a=inv&item=<?=$necklace[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$necklace['type'];?>/<?=$necklace['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$necklace['stamina'];?>%;"><span><?=$necklace['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
			<div class="panel-body" style="text-align:left;">
				<?php if ($necklace): ?>
				<div class="form-group">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Pancerz:  <?=$necklace['resist'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Wytrzymałość:  <?=$necklace['sta'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Siła:  <?=$necklace['str'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Zręczność:  <?=$necklace['dex'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Inteligencja:  <?=$necklace['intell'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Szczęście:  <?=$necklace['luck'];?>">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<div class="panel panel-default" style="width:230px; float:left; margin:5px;">
			<div class="panel-body" style="text-align:center;">
				Pierścień<br/>
				<?php if ($ring): ?>
				<a href="index.php?a=inv&item=<?=$ring[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$ring['type'];?>/<?=$ring['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$ring['stamina'];?>%;"><span><?=$ring['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
			<div class="panel-body" style="text-align:left;">
				<?php if ($ring): ?>
				<div class="form-group">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Pancerz:  <?=$ring['resist'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Wytrzymałość:  <?=$ring['sta'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Siła:  <?=$ring['str'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Zręczność:  <?=$ring['dex'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Inteligencja:  <?=$ring['intell'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Szczęście:  <?=$ring['luck'];?>">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<div class="panel panel-default" style="width:230px; float:left; margin:10px"">
			<div class="panel-body" style="text-align:center;">
				Talizman<br/>
				<?php if ($talisman): ?>
				<a href="index.php?a=inv&item=<?=$talisman[0];?>">
					<img style="display:block; margin:0 auto;" src="img/<?=$talisman['type'];?>/<?=$talisman['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<div class="progress-bar" style="width:<?=$talisman['stamina'];?>%;"><span><?=$talisman['stamina'];?>%</span></div>
					</div>
				</a>
				<?php else: ?>
				<img src="img/none.png">
				<?php endif; ?>
			</div>
			<div class="panel-body" style="text-align:left;">
				<?php if ($talisman): ?>
				<div class="form-group">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Pancerz:  <?=$talisman['resist'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Wytrzymałość:  <?=$talisman['sta'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Siła:  <?=$talisman['str'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Zręczność:  <?=$talisman['dex'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Inteligencja:  <?=$talisman['intell'];?>">
					<input class="form-control input-sm" id="inputSmall" type="text" style="text-align:center; font-weight: bold; margin-top:5px; margin-bottom:5px;" disabled value="Szczęście:  <?=$talisman['luck'];?>">
				</div>
				<?php else: ?>
				<img style="display:block; margin:0 auto;" src="img/none.png">
				<?php endif; ?>
			</div>
		</div>
		<br clear="both"/>
		
	</div>
	<?php
	//$object = "SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 0 ORDER BY stamina";
	$sql = 'SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid=:uid AND used =:used ORDER BY stamina';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
	$stmt->bindValue(':used', 0, PDO::PARAM_INT);
	$stmt->execute();
	$object = $stmt->fetch();
	if ($object):
		//$object = call($object); 
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
		$stmt->bindValue(':used', 0, PDO::PARAM_INT);
		$stmt->execute();
	?>
	<div class="well well-sm">
		<center>EKWIPUNEK</center>
		<div width="100%" height="200px">
		<?php while ($s = $stmt->fetch()): ?>
			<div style="width: 100px; float:left; margin-right:6px;">
				<a href="index.php?a=inv&item=<?=$s['iid'];?>">
					<img style="display: block; margin: 0 auto;" src="img/<?=$s['type'];?>/<?=$s['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
					<?php if ($s['stamina'] <= 30): ?>
						<div class="progress-bar progress-bar-danger" style="width:<?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
					<?php else: ?>
						<div class="progress-bar" style="width:<?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
					<?php endif; ?>
					</div>
				</a>
			</div>
		<?php endwhile; unset($stmt);?>
			<br clear="both"/>
		</div>
	</div>
	<?php endif; ?>
</div>