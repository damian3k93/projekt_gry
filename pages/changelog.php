
<div class="panel-heading">
	<h3 class="panel-title">Lista zmian</h3>
</div>
<div class="panel-body">
	<table class="table table-striped table-hover ">
		<thead>
			<tr>
				<th>#</th>
				<th>Wersja</th>
				<th>Data</th>
				<th>Komentarze</th>
			</tr>
		</thead>
		<?php
			$sql = 'SELECT * FROM changelog ORDER BY id DESC';
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$i = 0;
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
				$i++;
				$sql = 'SELECT Count(*) AS ilosc FROM comments WHERE cid=:id';
				$stmt2 = $db->prepare($sql);
				$stmt2->bindValue(':id', $row['id'], PDO::PARAM_INT);
				$stmt2->execute();
				$comments = $stmt2->fetch();
				unset($stmt2);		
		?>
			<tbody>
				<?=($i == 1) ? '<tr class="info">' : '<tr>';?>
					<td><?=$i;?></td>
					<td><a href="index.php?a=version&b=<?=$row['id'];?>"><?=$row['ver'];?></a></td>
					<td><?=$row['date'];?></td>
					<td><?=$comments['ilosc'];?></td>
				</tr>
			</tbody>
		<?php endwhile; unset($stmt); ?>
	</table>
</div>