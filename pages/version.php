
<?php
if (!isset($_GET['b'])):
	header("Location: index.php?a=home");
else:
	if (!empty($_POST) && isset($_POST['cid']) && isset($_POST['content'])) {
		if (!is_array($user))
			throwInfo('danger', 'Tylko zalogowani mogą dodawać komentarze', true);
		else {
			$content = vtxt($_POST['content']);
			$cid = vtxt($_POST['cid']);
			$time = time();
			if ($time < $user['last_comment'] + 600)
				throwInfo('danger', 'Zwolnij! Możesz dodać komentarz raz na 10 minut', true);
			else {
				//$prot = call("UPDATE users SET last_comment = ".$time." WHERE id = ".$user['id']);
				$sql = 'UPDATE users SET last_comment=:time WHERE id=:uid';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':time', $time, PDO::PARAM_INT);
				$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
				$stmt->execute();
				$prot = $stmt->rowCount();
				unset($stmt);
				
				if ($prot == 0)
					throwInfo('danger', 'Błąd podczas dodawania komentarza', true);
				else {
					//$push = call("INSERT INTO comments (uid, cid, content) VALUES (".$user['id'].", ".$cid.", '".$content."')");
					$data = [
							'uid' => $user['id'],
							'cid' => $cid,
							'content' => $content,
							];
					$sql = 'INSERT INTO comments (uid, cid, content) VALUES (:uid, :cid, :content)';
					$stmt = $db->prepare($sql);
					$stmt->execute($data);
					$push = $stmt->rowCount();
					unset($stmt);
					
					if ($push != 1)
						throwInfo('danger', 'Błąd podczas dodawania komentarza /2', true);
					else
						throwInfo('success', 'Dodano komentarz', true);
				}
			}
		} 
	}
	
	$id = vtxt($_GET['b']);
	//$ver = row("SELECT * FROM changelog WHERE id = ".$id);
	$sql = 'SELECT * FROM changelog WHERE id=:id';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$ver = $stmt->rowCount();
	unset($stmt);
	
	if ($ver != 0): ?>
<div class="panel-heading">
	<h3 class="panel-title">Szczegóły</h3>
</div>
<div class="panel-body">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Wersja <?=$ver['ver'];?></h3>
		</div>
		<div class="panel-body">
			<?=$ver['content'];?>
		</div>
		<div class="panel-footer">
			<a href="index.php?a=changelog" class="btn btn-info">Powrót do listy</a>
		</div>
	</div>
</div>
<div class="panel-footer">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Komentarze</h3>
		</div>
		<div class="panel-body">
		<?php
		//$comments = call("SELECT * FROM comments WHERE cid = ".$id);
		$sql = 'SELECT * FROM comments WHERE cid=:id';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$comments = $stmt->rowCount();
		
		if ($comments == 0):
			throwInfo('info', 'Brak komentarzy', false);
		else:
			while($row = $stmt->fetch()):
			//$name = row("SELECT login FROM users WHERE id = ".$row['uid']);
			$sql = 'SELECT login FROM users WHERE id=:uid';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':uid', $row['uid'], PDO::PARAM_INT);
			$stmt->execute();
			$name = $stmt->fetch();
			?>
			
			
			<div class="well well-sm">
				<div class="panel-heading"><b><?=$name['login'];?></b><span style="float: right;"><?=$row['date'];?></span></div>
				<div class="panel-body">
					<?=$row['content'];?>
				</div>
			</div>
			<?php endwhile; unset($stmt); ?>
		<?php endif; ?>
		</div>
		<div class="panel-footer">
			<form class="form-horizontal" action="index.php?a=version&b=<?=$id;?>" method="POST">
				<fieldset>
					<legend>Dodaj komentarz</legend>
					<input type="hidden" name="cid" value="<?=$id;?>"/>
					<div class="form-group">
						<label for="textArea" class="col-lg-2 control-label">Treść</label>
						<div class="col-lg-10">
							<textarea name="content" class="form-control" rows="3" id="textArea" style="max-width: 606px;"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-10 col-lg-offset-2">
							<button type="submit" class="btn btn-primary">Wyślij</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
	<?php endif; ?>
<?php endif; ?>
