
<div class="panel-heading">
	<h3 class="panel-title">Ranking</h3>
</div>
<div class="panel-body">
	<div class="btn-group btn-group-justified">
		<a href="index.php?a=rank&b=p" class="btn btn-default">Gracze</a>
		<a href="index.php?a=rank&b=g" class="btn btn-default">Gildie</a>
	</div>
	<table class="table table-striped table-hover ">
		<?php
		if (isset($_GET['b']) && $_GET['b'] == 'p') {
			$b = 'p';
			$sql = "SELECT * FROM users";
			if (!isset($_GET['s']) || empty($_GET['s'])){
				$stmt = $db->prepare($sql.' ORDER BY lvl');
				$targets = $stmt->execute();
			}
			elseif (!empty($_GET['o'])) {
				if ($_GET['o'] == 1){
					$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']));
					$targets = $stmt->execute();
				}
				if ($_GET['o'] == 2){
					$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']).' DESC');
					$targets = $stmt->execute();
				}
			} else{
				$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']));
				$targets = $stmt->execute();
			}
			if (!isset($_GET['o']) || empty($_GET['o']))
				$_GET['o'] = 0;
			if ($_GET['o'] == 2)
				$s = 1;
			else
				$s = 2;
		} elseif (isset($_GET['b']) && $_GET['b'] == 'g') {
			$b = 'g';
			$sql = "SELECT * FROM guilds";
			if (!isset($_GET['s']) || empty($_GET['s'])){
				$stmt = $db->prepare($sql.' ORDER BY lvl');
				$targets = $stmt->execute();
			}
			elseif (!empty($_GET['o'])) {
				if ($_GET['o'] == 1){
					$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']));
					$targets = $stmt->execute();
				}
				if ($_GET['o'] == 2){
					$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']).' DESC');
					$targets = $stmt->execute();
				}
			} else{
				$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']));
				$targets = $stmt->execute();
			}
			
			if (!isset($_GET['o']) || empty($_GET['o']))
				$_GET['o'] = 0;
			if ($_GET['o'] == 2)
				$s = 1;
			else
				$s = 2;
		} else {
			$b = 'p';
			$sql = "SELECT * FROM users";
			if (!isset($_GET['s']) || empty($_GET['s'])){
				$stmt = $db->prepare($sql.' ORDER BY lvl DESC');
				$targets = $stmt->execute();
			}
			elseif (!empty($_GET['o'])) {
				if ($_GET['o'] == 1){
					$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']));
					$targets = $stmt->execute();
				}
				if ($_GET['o'] == 2){
					$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']).' DESC');
					$targets = $stmt->execute();
				}
			} else {
				$stmt = $db->prepare($sql.' ORDER BY '.vtxt($_GET['s']));
				$targets = $stmt->execute();
			}
			
			if (!isset($_GET['o']) || empty($_GET['o']))
				$_GET['o'] = 0;
			if ($_GET['o'] == 2)
				$s = 1;
			else
				$s = 2;
		}
		?>
		<thead>
			<tr>
			<?php if ($b == 'g'): ?>
				<th>#</th>
				<th><a href="index.php?a=rank&b=g&s=tag&o=<?=$s;?>">Tag</a></th>
				<th><a href="index.php?a=rank&b=g&s=name&o=<?=$s;?>">Nazwa</a></th>
				<th><a href="index.php?a=rank&b=g&s=lvl&o=<?=$s;?>">Poziom</a></th>
				<th><a href="index.php?a=rank&b=g&s=rep&o=<?=$s;?>">Reputacja</a></th>
			<?php else: ?>
				<th>#</th>
				<th><a href="index.php?a=rank&b=p&s=login&o=<?=$s;?>">Nick</a></th>
				<th><a href="index.php?a=rank&b=p&s=lvl&o=<?=$s;?>">Poziom</a></th>
				<th><a href="index.php?a=rank&b=p&s=class&o=<?=$s;?>">Klasa</a></th>
				<th><a href="index.php?a=rank&b=p&s=prest&o=<?=$s;?>">Prestiż</a></th>
				<th><a href="index.php?a=rank&b=p&s=wld&o=<?=$s;?>">W/P/R</a></th>
			<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php
		$i = 1;
		if ($b == 'g'):
			while ($row = $stmt->fetch()): ?>
			<tr>
				<td><?=$i++;?></td>
				<td><a href="index.php?a=stats&g=<?=$row['id'];?>"><?=$row['tag'];?></a></td>
				<td><a href="index.php?a=stats&g=<?=$row['id'];?>"><?=$row['name'];?></a></td>
				<td><?=$row['lvl'];?></td>
				<td><?=$row['rep'];?></td>
			</tr>
			<?php endwhile; unset($stmt);
		else:
			while ($row = $stmt->fetch()): ?>
			<tr>
				<td><?=$i++;?></td>
				<?php if ($row['guild'] > 0):
				$sql = 'SELECT tag FROM guilds WHERE id=:guild';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':id', $row['guild'], PDO::PARAM_INT);
				$guild = $stmt->fetch();
				?>
				<td><a href="index.php?a=stats&g=<?=$row['guild'];?>"><?=$guild['tag'];?></a> <a href="index.php?a=stats&p=<?=$row['id'];?>"><?=$row['login'];?></a></td>
				<?php else: ?>
				<td><a href="index.php?a=stats&p=<?=$row['id'];?>"><?=$row['login'];?></a></td>
				<?php endif; ?>
				<td><?=$row['lvl'];?></td>
				<td><?=$row['class'];?></td>
				<td><?=$row['prest'];?></td>
				<td><?=$row['win'];?>/<?=$row['lost'];?>/<?=$row['draw'];?></td>
			</tr>
			<?php endwhile; unset($stmt);
		endif;
		?>
		</tbody>
	</table>
</div>