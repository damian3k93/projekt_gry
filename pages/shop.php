
<div class="panel-heading">
	<h3 class="panel-title">Sklep</h3>
</div>
<div class="panel-body">
	<?php if ($user['status'] == 0): ?>
	<div class="btn-group btn-group-justified">
		<a href="index.php?a=shop&b=weapon" class="btn btn-default">Bronie</a>
		<a href="index.php?a=shop&b=helmet" class="btn btn-default">Hełmy</a>
		<a href="index.php?a=shop&b=armor" class="btn btn-default">Zbroje</a>
		<a href="index.php?a=shop&b=shoes" class="btn btn-default">Buty</a>
		<a href="index.php?a=shop&b=gloves" class="btn btn-default">Rękawice</a>
		<a href="index.php?a=shop&b=belt" class="btn btn-default">Pasy</a>
	</div>
	<br/>
	<?php endif;
	if ($user['status'] == 0):
		// Akcje
		if (isset($_POST['item_id'])) {
			$item_id = $_POST['item_id'];
			if (!is_numeric($item_id) && $item_id <= 0)
				header("Location: index.php?a=shop");
			else {
				//$item = row("SELECT * FROM items WHERE id = ".$item_id);
				$sql = 'SELECT * FROM items WHERE id=:id';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':id', $item_id, PDO::PARAM_INT);
				$stmt->execute();
				$item = $stmt->fetch();
				unset($stmt);
				if (empty($item))
					header("Location: index.php?a=shop");
				else {
					//$inv = row("SELECT count(*) AS cap FROM inventory WHERE uid = ".$user['id']);
					$sql = 'SELECT Count(id) AS cap FROM inventory WHERE uid=:id';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					$inv = $stmt->fetch();
					unset($stmt);
					if ($inv['cap'] > 13)
						throwInfo('success', 'Nie pomieścisz już więcej w plecaku', true);
					else {
						if ($item['cost'] > $user['cash'])
							throwInfo('success', 'Nie masz wystarczającej ilości gotówki', true);
						else if ($item['lvl'] > $user['lvl'])
							throwInfo('success', 'Nie masz wystarczającego poziomu', true);
						else {
							$stats = array();
							$num = rand(1,3);
							for($i=0;$i<$num; ){
								$stats[$i] = rand(1,5);
								for($j=0;$j<$i;++$j)
									if($stats[$j] == $stats[$i])
										--$i;
									++$i;
							}
							//losowanie wartosci statystyk
							$num_st = array();
							for($i=0;$i<$num;$i++){
								$num_st[$i] = rand(round(0.5*$user['lvl']),$user['lvl']+5);
							}
							
							$st1 = 0;
							$st2 = 0;
							$st3 = 0;
							$st4 = 0;
							$st5 = 0;
							
							if($num == 1){
								if($stats[0] == 1) $st1 = $num_st[0];
								elseif($stats[0] == 2) $st2 = $num_st[0];
								elseif($stats[0] == 3) $st3 = $num_st[0];
								elseif($stats[0] == 4) $st4 = $num_st[0];
								elseif($stats[0] == 5) $st5 = $num_st[0];
							}
							elseif($num == 2){
								if($stats[0] == 1) $st1 = $num_st[0];
								elseif($stats[0] == 2) $st2 = $num_st[0];
								elseif($stats[0] == 3) $st3 = $num_st[0];
								elseif($stats[0] == 4) $st4 = $num_st[0];
								elseif($stats[0] == 5) $st5 = $num_st[0];
								
								if($stats[1] == 1) $st1 = $num_st[1];
								elseif($stats[1] == 2) $st2 = $num_st[1];
								elseif($stats[1] == 3) $st3 = $num_st[1];
								elseif($stats[1] == 4) $st4 = $num_st[1];
								elseif($stats[1] == 5) $st5 = $num_st[1];
							}
							elseif($num == 3){
								if($stats[0] == 1) $st1 = $num_st[0];
								elseif($stats[0] == 2) $st2 = $num_st[0];
								elseif($stats[0] == 3) $st3 = $num_st[0];
								elseif($stats[0] == 4) $st4 = $num_st[0];
								elseif($stats[0] == 5) $st5 = $num_st[0];
								
								if($stats[1] == 1) $st1 = $num_st[1];
								elseif($stats[1] == 2) $st2 = $num_st[1];
								elseif($stats[1] == 3) $st3 = $num_st[1];
								elseif($stats[1] == 4) $st4 = $num_st[1];
								elseif($stats[1] == 5) $st5 = $num_st[1];
								
								if($stats[2] == 1) $st1 = $num_st[2];
								elseif($stats[2] == 2) $st2 = $num_st[2];
								elseif($stats[2] == 3) $st3 = $num_st[2];
								elseif($stats[2] == 4) $st4 = $num_st[2];
								elseif($stats[2] == 5) $st5 = $num_st[2];
							}
							
							
							//call("INSERT INTO inventory (obj, uid) VALUES (".$item['id'].", ".$user['id'].")");
							$sql = 'INSERT INTO inventory (obj, uid, sta, str, dex, intell, luck) VALUES (:iid, :uid, :sta, :str, :dex, :intell, :luck)';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':iid', $item['id'], PDO::PARAM_INT);
							$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
							$stmt->bindValue(':sta', $st1+$item['ista'], PDO::PARAM_INT);
							$stmt->bindValue(':str', $st2+$item['istr'], PDO::PARAM_INT);
							$stmt->bindValue(':dex', $st3+$item['idex'], PDO::PARAM_INT);
							$stmt->bindValue(':intell', $st4+$item['iintell'], PDO::PARAM_INT);
							$stmt->bindValue(':luck', $st5+$item['iluck'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							//call("UPDATE users SET cash = cash - ".$item['cost']." WHERE id = ".$user['id']);
							$cash = $user['cash']-$item['cost'];
							$sql = 'UPDATE users SET cash=:cash WHERE id=:uid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':cash', $cash, PDO::PARAM_INT);
							$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							throwInfo('success', 'Przedmiot zakupiony za '.$item['cost'].'$', true);
						}
					}
				}
			}
		} elseif (isset($_GET['sell'])) {
				$item = vtxt($_GET['sell']);
				if (!is_numeric($item) && empty($item))
					header("Location: index.php?a=shop");
				//$data = row("SELECT * FROM inventory WHERE uid = ".$user['id']." AND id = ".$item);
				$sql = 'SELECT * FROM inventory WHERE uid=:uid AND id=:id';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
				$stmt->bindValue(':id', $item, PDO::PARAM_INT);
				$stmt->execute();
				$data = $stmt->fetch();
				unset($stmt);
			
			if (!$data || $data['used'] == 1)
				throwInfo('danger', 'Błąd (Brak przedmiotu lub przedmiot używany)', true);
			else {
				//$cost = row("SELECT cost FROM items WHERE id = ".$data['obj']);
				$sql = 'SELECT cost FROM items WHERE id=:id';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':id', $data['obj'], PDO::PARAM_INT);
				$stmt->execute();
				$cost = $stmt->fetch();
				unset($stmt);
				
				if (!$cost)
					throwInfo('danger', 'Błąd (Brak danych o cenie przedmiotu)', true);
				else {
					$cost = floor(($cost['cost'] / 2) * ($data['stamina'] / 100));
					//call("DELETE FROM inventory WHERE id = ".$data['id']);
					$sql = 'DELETE FROM inventory WHERE id=:id ';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':id', $item, PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					
					//all("UPDATE users SET cash = cash + ".$cost." WHERE id = ".$zapytanie['id']);
					$sql = 'UPDATE users SET cash=:cash WHERE id=:id';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':cash', $user['cash'] + $cost, PDO::PARAM_INT);
					$stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					
					throwInfo('success', 'Sprzedano przedmiot za '.$cost.'$', true);
				}
			}
		}
		
		// Kategorie
		if (isset($_GET['b']))
			$type = vtxt($_GET['b']);
		else
			$type = 'weapon';
		
		// Filtrowanie
		$sql = 'SELECT * FROM items WHERE lvl>:lv_min AND lvl<:lv_max AND type=:type AND class=:class ORDER BY ';
		$lv_min = 0;
		$lv_max = 101;
		$location = getLocData($user['pos_x'],$user['pos_y']);
		
		switch($location['id']){
			case 1: 
					$lv_min = 0;
					$lv_max = 21;
					break;
			case 2:
					$lv_min = 21;
					$lv_max = 46;
					break;
			case 3:
					$lv_min = 45;
					$lv_max = 71;
					break;
			case 4:
					$lv_min = 71;
					$lv_max = 110;
					break;
			default: 
					$lv_min = 0;
					$lv_max = 120;
					break;
		}
		
		
		
		if (!isset($_GET['s'])) {
			//$items = call("SELECT * FROM items WHERE lvl <= ".$user['lvl']." AND type = '".$type."' ORDER BY lvl");
			$stmt = $db->prepare($sql.' lvl');
			$stmt->bindValue(':lv_min', $lv_min, PDO::PARAM_INT);
			$stmt->bindValue(':lv_max', $lv_max, PDO::PARAM_INT);
			$stmt->bindValue(':type', $type, PDO::PARAM_STR);
			$stmt->bindValue(':class', $user['class'], PDO::PARAM_STR);
			$stmt->execute();
			$items = $stmt->fetchAll();
			$s = 2;
		} elseif (isset($_GET['o'])) {
			if ($_GET['o'] == 1) {
				//$items = call("SELECT * FROM items WHERE lvl <= ".$user['lvl']." AND type = '".$type."' ORDER BY ".vtxt($_GET['s']));
				$stmt = $db->prepare($sql.vtxt($_GET['s']));
				$stmt->bindValue(':lv_min', $lv_min, PDO::PARAM_INT);
				$stmt->bindValue(':lv_max', $lv_max, PDO::PARAM_INT);
				$stmt->bindValue(':type', $type, PDO::PARAM_STR);
				$stmt->bindValue(':class', $user['class'], PDO::PARAM_STR);
				$stmt->execute();
				$items = $stmt->fetchAll();
				$s = 2;
			} elseif ($_GET['o'] == 2) {
				//$items = call("SELECT * FROM items WHERE lvl <= ".$user['lvl']." AND type = '".$type."' ORDER BY ".vtxt($_GET['s'])." DESC");
				$stmt = $db->prepare($sql.vtxt($_GET['s']).' DESC');
				$stmt->bindValue(':lv_min', $lv_min, PDO::PARAM_INT);
				$stmt->bindValue(':lv_max', $lv_max, PDO::PARAM_INT);
				$stmt->bindValue(':type', $type, PDO::PARAM_STR);
				$stmt->bindValue(':class', $user['class'], PDO::PARAM_STR);
				$stmt->execute();
				$items = $stmt->fetchAll();
				$s = 1;
			} else
				$s = 2;
		} else {
			//$items = call("SELECT * FROM items WHERE lvl <= ".$user['lvl']." AND type = '".$type."' ORDER BY ".vtxt($_GET['s']));
			$stmt = $db->prepare($sql.vtxt($_GET['s']));
			$stmt->bindValue(':lv_min', $lv_min, PDO::PARAM_INT);
			$stmt->bindValue(':lv_max', $lv_max, PDO::PARAM_INT);
			$stmt->bindValue(':type', $type, PDO::PARAM_STR);
			$stmt->bindValue(':class', $user['class'], PDO::PARAM_STR);
			$stmt->execute();
			$items = $stmt->fetchAll();
			$s = 2;
		}
		
		// Licznik przedmiotów
		$sid = 1;
		
		// Wybieramy ekwipunek
		$sq = 'SELECT *, inventory.id AS iid FROM inventory INNER JOIN items ON inventory.obj = items.id WHERE uid =:uid AND used = 0 ORDER BY stamina';
		
		// Czy są jakieś przedmioty
		if (empty($items) || !isset($items) || !($items) || count($items) == 0):
			throwInfo('info', 'Brak ofert sprzedaży, prosimy spróbować później', false);
		else:?>
	<div class="well well-sm">
		<?php if ($type == 'weapon'): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover" style="text-align:center; ">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Nazwa</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=lvl&o=<?=$s;?>">Poziom</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=cost&o=<?=$s;?>">Cena</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=min_dmg&o=<?=$s;?>">Obrażenia</a></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($items as $s):?>		
					<tr>
						<td><?=$sid++;?></td>
						<td><img src="img/<?=$s['type'];?>/<?=$s['id'];?>.png" width="65px"></td>
						<td><?=$s['name'];?></td>
						<td><?=$s['lvl'];?></td>
						<td><?=$s['cost'];?>$</td>
						<td><?=$s['min_dmg'];?> - <?=$s['max_dmg'];?> (~<?=floor(avg(array($s['min_dmg'], $s['max_dmg'])));?>)</td>
						<td>
							<form action="index.php?a=shop" method="POST">
								<input name="item_id" type="hidden" value="<?=$s['id'];?>">
								<?php if ($user['cash'] < $s['cost'] || $user['lvl']<$s['lvl']): ?>
								<button type="submit" class="btn btn-primary disabled">Kup</button>
								<?php else: ?>
								<button type="submit" class="btn btn-primary">Kup</button>
								<?php endif; ?>
							</form>
						</td>
						</tr>
						<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php elseif ($type == 'helmet'): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Nazwa</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=lvl&o=<?=$s;?>">Poziom</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=cost&o=<?=$s;?>">Cena</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=resist&o=<?=$s;?>">Pancerz</a></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($items as $s): ?>
					<tr>
						<td><?=$sid++;?></td>
						<td><img src="img/<?=$s['type'];?>/<?=$s['id'];?>.png" width="65px"></td>
						<td><?=$s['name'];?></td>
						<td><?=$s['lvl'];?></td>
						<td><?=$s['cost'];?>$</td>
						<td><?=$s['resist'];?></td>
						<td>
							<form action="index.php?a=shop" method="POST">
								<input name="item_id" type="hidden" value="<?=$s['id'];?>">
								<?php if ($user['cash'] < $s['cost'] || $user['lvl']<$s['lvl']): ?>
								<button type="submit" class="btn btn-primary disabled">Kup</button>
								<?php else: ?>
								<button type="submit" class="btn btn-primary">Kup</button>
								<?php endif; ?>
							</form>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php elseif ($type == 'armor'): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Nazwa</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=lvl&o=<?=$s;?>">Poziom</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=cost&o=<?=$s;?>">Cena</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=stat&o=<?=$s;?>">Pancerz</a></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($items as $s): ?>
					<tr>
						<td><?=$sid++;?></td>
						<td><img src="img/<?=$s['type'];?>/<?=$s['id'];?>.png" width="65px"></td>
						<td><?=$s['name'];?></td>
						<td><?=$s['lvl'];?></td>
						<td><?=$s['cost'];?>$</td>
						<td><?=$s['resist'];?></td>
						<td>
							<form action="index.php?a=shop" method="POST">
								<input name="item_id" type="hidden" value="<?=$s['id'];?>">
								<?php if ($user['cash'] < $s['cost'] || $user['lvl']<$s['lvl']): ?>
								<button type="submit" class="btn btn-primary disabled">Kup</button>
								<?php else: ?>
								<button type="submit" class="btn btn-primary">Kup</button>
								<?php endif; ?>
							</form>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php elseif ($type == 'shoes'): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Nazwa</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=lvl&o=<?=$s;?>">Poziom</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=cost&o=<?=$s;?>">Cena</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=stat&o=<?=$s;?>">Pancerz</a></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($items as $s): ?>
					<tr>
						<td><?=$sid++;?></td>
						<td><img src="img/<?=$s['type'];?>/<?=$s['id'];?>.png" width="65px"></td>
						<td><?=$s['name'];?></td>
						<td><?=$s['lvl'];?></td>
						<td><?=$s['cost'];?>$</td>
						<td><?=$s['resist'];?></td>
						<td>
							<form action="index.php?a=shop" method="POST">
								<input name="item_id" type="hidden" value="<?=$s['id'];?>">
								<?php if ($user['cash'] < $s['cost'] || $user['lvl']<$s['lvl']): ?>
								<button type="submit" class="btn btn-primary disabled">Kup</button>
								<?php else: ?>
								<button type="submit" class="btn btn-primary">Kup</button>
								<?php endif; ?>
							</form>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php elseif ($type == 'gloves'): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Nazwa</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Poziom</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=cost&o=<?=$s;?>">Cena</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=stat&o=<?=$s;?>">Pancerz</a></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($items as $s): ?>
					<tr>
						<td><?=$sid++;?></td>
						<td><img src="img/<?=$s['type'];?>/<?=$s['id'];?>.png" width="65px"></td>
						<td><?=$s['name'];?></td>
						<td><?=$s['lvl'];?></td>
						<td><?=$s['cost'];?>$</td>
						<td><?=$s['resist'];?></td>
						<td>
							<form action="index.php?a=shop" method="POST">
								<input name="item_id" type="hidden" value="<?=$s['id'];?>">
								<?php if ($user['cash'] < $s['cost'] || $user['lvl']<$s['lvl']): ?>
								<button type="submit" class="btn btn-primary disabled">Kup</button>
								<?php else: ?>
								<button type="submit" class="btn btn-primary">Kup</button>
								<?php endif; ?>
							</form>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php elseif ($type == 'belt'): ?>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=name&o=<?=$s;?>">Nazwa</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=lvl&o=<?=$s;?>">Poziom</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=cost&o=<?=$s;?>">Cena</a></th>
						<th><a href="index.php?a=shop&b=<?=$type;?>&s=stat&o=<?=$s;?>">Pancerz</a></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($items as $s): ?>
					<tr>
						<td><?=$sid++;?></td>
						<td><img src="img/<?=$s['type'];?>/<?=$s['id'];?>.png" width="65px"></td>
						<td><?=$s['name'];?></td>
						<td><?=$s['lvl'];?></td>
						<td><?=$s['cost'];?>$</td>
						<td><?=$s['resist'];?></td>
						<td>
							<form action="index.php?a=shop" method="POST">
								<input name="item_id" type="hidden" value="<?=$s['id'];?>">
								<?php if ($user['cash'] < $s['cost'] || $user['lvl']<$s['lvl']): ?>
								<button type="submit" class="btn btn-primary disabled">Kup</button>
								<?php else: ?>
								<button type="submit" class="btn btn-primary">Kup</button>
								<?php endif; ?>
							</form>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php endif; unset($stmt);?>
	</div>
	<?php 
			$stmt = $db->prepare($sq);
			$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
			$stmt->execute();
			$object = $stmt->fetchColumn();
			unset($stmt);
	if ($object): ?>
	<div class="well well-sm">
		<center>EKWIPUNEK</center>
		<div width="100%" height="200px">
		<?php //$object = call($object);
			$stmt = $db->prepare($sq);
			$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
			$stmt->execute();
			while ($s = $stmt->fetch()): ?>
			<div style="width: 100px; float:left; margin-right:6px;">
				<a href="index.php?a=shop&sell=<?=$s['iid'];?>">
					<img style="display: block; margin: 0 auto;" src="img/<?=$s['type'];?>/<?=$s['obj'];?>.png" alt="">
					<div class="progress" style="margin-bottom:0px; margin-top:5px;">
						<?php if ($s['stamina'] <= 30): ?>
						<div class="progress-bar progress-bar-danger" style="width:<?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
						<?php else: ?>
						<div class="progress-bar" style="width:<?=$s['stamina'];?>%;"><span><?=$s['stamina'];?>%</span></div>
						<?php endif; ?>
					</div>
					<div class="form-group" style="margin-top:5px; margin-bottom:0px;">
						<input style="text-align:center;" class="form-control input-sm" id="inputSmall" type="text" value="<?=floor(($s['cost'] / 2) * ($s['stamina'] / 100));?>$">
					</div>
				</a>
			</div>
		<?php endwhile; unset($stmt)?>
			<br clear="both"/>
		</div>
	</div>
	<?php endif; endif; else: throwInfo('danger', 'Zakończ aktualną akcję, aby wejść do sklepu', false); endif; ?>
</div>