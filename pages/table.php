
<?php
	$location = getLocData($user['pos_x'], $user['pos_y']);
	$sql = 'SELECT * FROM users WHERE pos_x=:pos_x AND pos_y=:pos_y';				
				
	if (empty($_GET['s'])){
		$stmt = $db->prepare($sql.' ORDER BY lvl');
		$stmt->bindValue(':pos_x', $user['pos_x'], PDO::PARAM_INT);
		$stmt->bindValue(':pos_y', $user['pos_y'], PDO::PARAM_INT);
		$stmt->execute();	
		$players = $stmt->fetch();
		$stmt->closeCursor();
	}
	elseif (!empty($_GET['o'])) {
		if ($_GET['o'] == 1){
			$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']));
			$stmt->bindValue(':pos_x', $user['pos_x'], PDO::PARAM_INT);
			$stmt->bindValue(':pos_y', $user['pos_y'], PDO::PARAM_INT);
			$stmt->execute();	
			$players = $stmt->fetch();
			$stmt->closeCursor();
		}
		if ($_GET['o'] == 2){
			$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']).' DESC');
			$stmt->bindValue(':pos_x', $user['pos_x'], PDO::PARAM_INT);
			$stmt->bindValue(':pos_y', $user['pos_y'], PDO::PARAM_INT);
			$stmt->execute();	
			$players = $stmt->fetch();
			$stmt->closeCursor();
		}
	} else{
		$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']));
		$stmt->bindValue(':pos_x', $user['pos_x'], PDO::PARAM_INT);
		$stmt->bindValue(':pos_y', $user['pos_y'], PDO::PARAM_INT);
		$stmt->execute();	
		$players = $stmt->fetch();
		$stmt->closeCursor();
	
	}
	if (empty($_GET['o']))
		$_GET['o'] = 0;
	if ($_GET['o'] == 2)
		$s = 1;
	else
		$s = 2;
?>

<div class="panel-heading">
	<h3 class="panel-title">
		<b><?=locName($location['type']);?>&nbsp;<?=$location['name'];?></b>
	</h3>
</div>
<div class="panel-body">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Gracze w mieście</b></h3>
		</div>
		<div class="panel-body">
			<table class="table table-striped table-hover ">
				<thead>
					<tr>
						<th>#</th>
						<th><a href="index.php?a=table&s=login&o=<?php echo $s; ?>">Nazwa</a></th>
						<th><a href="index.php?a=table&s=lvl&o=<?php echo $s; ?>">Poz</a></th>
						<th><a href="index.php?a=table&s=sta&o=<?php echo $s; ?>">WYT</a></th>
						<th><a href="index.php?a=table&s=str&o=<?php echo $s; ?>">S</a></th>
						<th><a href="index.php?a=table&s=dex&o=<?php echo $s; ?>">ZR</a></th>
						<th><a href="index.php?a=table&s=intell&o=<?php echo $s; ?>">INT</a></th>
						<th><a href="index.php?a=table&s=luck&o=<?php echo $s; ?>">SZ</a></th>
					</tr>
				</thead>
				<tbody>
					<?php
						$i = 1;
						$stmt = $db->prepare($sql.' ORDER BY lvl DESC');
						$stmt->bindValue(':pos_x', $user['pos_x'], PDO::PARAM_INT);
						$stmt->bindValue(':pos_y', $user['pos_y'], PDO::PARAM_INT);
						$stmt->execute();
						while ($row = $stmt->fetch()): ?>
							<tr>
							<td><?=$i++;?></td>
								<?php if ($row['guild'] > 0):
									$sql = 'SELECT tag FROM guilds WHERE id=:id';
									$stmt = $db->prepare($sql);
									$stmt->bindValue(':id', $row['guild'], PDO::PARAM_STR);
									$stmt->execute();	
									$guild = $stmt->fetch();
									$stmt->closeCursor();
								?>
								<td><a href="index.php?a=stats&g=<?=$row['guild'];?>"><?=$guild['tag'];?></a> <a href="index.php?a=stats&p=<?=$row['id'];?>"><?=$row['login'];?></a></td>
								<?php else: ?>
								<td><a href="index.php?a=stats&p=<?=$row['id'];?>"><?=$row['login'];?></a></td>
								<?php endif; ?>
								<td><?=$row['lvl'];?></td>
								<td><?=$row['sta'];?></td>
								<td><?=$row['str'];?></td>
								<td><?=$row['dex'];?></td>
								<td><?=$row['intell'];?></td>
								<td><?=$row['luck'];?></td>
							</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>