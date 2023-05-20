﻿
<div class="panel-heading">
	<h3 class="panel-title">Kowal</h3>
</div>
<div class="panel-body">
	<?php
	if ($user['status'] != 0):
		throwInfo('danger', 'Zakończ aktualną akcję, aby wejść do kowala', false);
	else:
		if (isset($_GET['bid'])) {
			$id = vtxt($_GET['bid']);
			if (empty($id))
				throwInfo('danger', 'Wypełnij pola porawnie!', true);
			else {
				//$item = row("SELECT * FROM inventory WHERE uid = ".$user['id']." AND id = ".$id." AND used = 0");
				$sql = 'SELECT * FROM inventory WHERE uid=:uid AND id=:id AND used=:used';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
				$stmt->bindValue(':id', $id, PDO::PARAM_INT);
				$stmt->bindValue(':used', 0, PDO::PARAM_INT);
				$stmt->execute();
				$item = $stmt->fetch();
				unset($stmt);
				if (!$item)
					throwInfo('danger', 'Wystąpił błąd', true);
				else {
					if ($item['stamina'] == 100)
						throwInfo('danger', 'Przedmiot nie jest uszkodzony', true);
					else {
						$rep = 100 - $item['stamina'];
						if ($rep > $user['cash'])
							throwInfo('danger', 'Nie masz tyle pieniędzy', true);
						else {
							//$pay = $user['cash'] - $rep;
							//call("UPDATE users SET cash = cash - ".$rep." WHERE id = ".$user['id']);
							$sql = 'UPDATE users SET cash=:cash WHERE id=:uid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':cash', $user['cash'] - $rep, PDO::PARAM_INT);
							$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							$repair = $item['stamina'] + $rep;
							//call("UPDATE inventory SET stamina = stamina + ".$rep." WHERE id = ".$item['id']);
							$sql = 'UPDATE inventory SET stamina=:stamina WHERE id=:iid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':stamina', $repair, PDO::PARAM_INT);
							$stmt->bindValue(':iid', $item['id'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							throwInfo('success', 'Zregenerowano <b>'.$rep.'%</b>', true);
						}
					}
				}
			}
		}
		
		//$object = "SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid = ".$user['id']." AND used = 0 ORDER BY stamina";
		$sql = 'SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid=:uid AND used=:used ORDER BY stamina';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
		$stmt->bindValue(':used', 0, PDO::PARAM_INT);
		$stmt->execute();
		$object = $stmt->fetchColumn();;
		
		if ($object==0):
			throwInfo('info', 'Nie posiadasz przedmiotów w ekwipunku', false);
		else:
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
			$stmt->bindValue(':used', 0, PDO::PARAM_INT);
			$stmt->execute();
			//$object = $stmt->fetch(); 
			 
			?>
	<div class="well">
		<form class="form-horizontal">
			<fieldset>
				<div class="form-group">
					<label class="col-lg-2 control-label" for="kowal">Koszt:</label>
					<div class="col-lg-10">
						<input style="text-align: center;" class="form-control" id="kowal" type="text" placeholder="1$ - 1%" disabled>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div style="text-align: center;" class="well well-sm">
		<center>EKWIPUNEK</center>
		<div width="100%" height="200px">
		<?php while($s = $stmt->fetch()): ?>
			<div style="width: 100px; float:left; margin-right:6px;">
				<a href="index.php?a=blacksmith&bid=<?=$s['iid'];?>">
					<img style="display: block; margin: 0 auto;" src="img/<?=$s['type'];?>/<?=$s['obj'];?>.png" alt="" />
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
					<?php if ($s['stamina'] <= 30): ?>
						<div class="progress-bar progress-bar-danger" style="width: <?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
					<?php else: ?>
						<div class="progress-bar" style="width: <?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
					<?php endif; ?>
					</div>
					<div class="form-group" style="margin-top:5px; margin-bottom:0px;">
					<?php if ($s['stamina'] == 100): ?>
						<input style="text-align:center;" class="form-control input-sm" id="inputSmall" type="text" disabled value="SPRAWNY">
					<?php else: ?>
						<input style="text-align:center;" class="form-control input-sm" id="inputSmall" type="text" disabled value="<?=(100 - $s['stamina']);?>$">
					<?php endif; ?>
					</div>
				</a>
			</div>
		<?php endwhile; unset($stmt); ?>
			<br clear="both"/>
		</div>
	</div>
		<?php endif; ?>
	<?php endif; ?>
</div>