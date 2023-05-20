
<div class="panel-heading">
	<h3 class="panel-title">Strona główna</h3>
</div>
<div class="panel-body">
	<?php
		throwInfo('info', 'Rozpoczęcie prac nad projektem');

		$sql = 'SELECT * FROM changelog ORDER BY id DESC LIMIT 3';
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$changes = $stmt->fetch();
		$stmt->closeCursor();

		$i = 0;
		while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)):
			$i++;
	
			$sql = 'SELECT count(*) AS ilosc FROM comments WHERE cid=:cid';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':cid', $changes['id'],PDO::PARAM_INT);
			$stmt->execute();

			$comments = $stmt->fetch(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
	
			if ($i == 1):
				$style = ' style="color: white;"'; ?>
				<div class="panel panel-primary">
			<?php else:
				$style = ''; ?>
					<div class="panel panel-default">
			<?php endif; ?>
						<div class="panel-heading"><a<?=$style;?> href="index.php?a=version&b=<?=$row['id'];?>"><b>Wersja: <i><?=$row['ver'];?></i></b></a><span style="float: right;"><?=$row['date'];?></span></div>
						<div class="panel-body"><?=$row['content'];?></div>
						<div class="panel-footer">Komentarzy: <?=$comments['ilosc'];?></div>
					</div>
		<?php endwhile; ?>
				</div>