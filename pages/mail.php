
<div class="panel-heading">
	<h3 class="panel-title">Poczta</h3>
</div>
<div class="panel-body">
	<div class="btn-group btn-group-justified">
		<a href="index.php?a=mail&s=new" class="btn btn-default">Napisz</a>
		<a href="index.php?a=mail" class="btn btn-default">Odebrane</a>
		<a href="index.php?a=mail&s=sent" class="btn btn-default">Wysłane</a>
	</div>
	<br/>
	<div class="panel panel-default">
		<div class="panel-body">
			<?php
			if (isset($_GET['s']) && $_GET['s'] == 'new'):
				if (isset($_POST['to_id']) && isset($_POST['title']) && isset($_POST['content'])) {
					$to = vtxt($_POST['to_id']);
					$title = vtxt($_POST['title']);
					$content = vtxt($_POST['content']);
					
					if ($to == '0')
						throwInfo('danger', 'Nie można odpowiadać na maile systemowe', false);
					else {
						//$id = row("SELECT id FROM users WHERE login = '".$to."'");
						$sql = 'SELECT id FROM users WHERE login=:login';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':login', $to, PDO::PARAM_STR);
						$stmt->execute();
						$id = $stmt->fetch();
						
						
						if (empty($id['id']))
							throwInfo('danger', 'Nie ma takiego gracza', false);
						else {
							//call("INSERT INTO mail (from_id, to_id, type, title, content, date) VALUES 
							//(".$user['id'].", ".$id['id'].", 1, '".$title."', '".$content."', now()),
							//(".$user['id'].", ".$id['id'].", 2, '".$title."', '".$content."', now())");
							$sql = 'INSERT INTO mail (from_id, to_id, type, title, content, date) VALUES
									(:uid, :iid, :type1, :title, :content, now()),
									(:uid, :iid, :type2, :title, :content, now())';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
							$stmt->bindValue(':iid', $id['id'], PDO::PARAM_INT);
							$stmt->bindValue(':type1', 1, PDO::PARAM_INT);
							$stmt->bindValue(':type2', 2, PDO::PARAM_INT);
							$stmt->bindValue(':title', $title, PDO::PARAM_STR);
							$stmt->bindValue(':content', $content, PDO::PARAM_STR);
							$stmt->execute();
							unset($stmt);
							throwInfo('success', 'Wysłano wiadomość', false);
						}
					}
				} ?>
			<form class="form-horizontal" action="index.php?a=mail&s=new" method="POST">
				<fieldset>
					<legend>Nowa wiadomość</legend>
					<div class="form-group" align="right">
						<div class="col-lg-2"></div>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="to_id" placeholder="Login" value="<?=(isset($_GET['to_id'])) ? vtxt($_GET['to_id']) : '';?>">
						</div>
					</div>
					<div class="form-group" align="right">
						<div class="col-lg-2"></div>
						<div class="col-lg-8">
							<input type="text" class="form-control" name="title" placeholder="Tytuł">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-2"></div>
						<div class="col-lg-10">
							<textarea class="form-control" rows="3" placeholder="Treść" id="textArea" name="content" style="margin: 0px -5.84375px 0px 0px; width: 480px; max-width: 480px; height: 74px;"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-12 col-lg-offset-5">
							<button type="submit" class="btn btn-primary">Wyślij</button>
						</div>
					</div>
				</fieldset>
			</form>
			<?php elseif (isset($_GET['s']) && $_GET['s'] == 'sent'):
				if (!empty($_GET['del'])) {
					$_GET['del'] = (int)$_GET['del'];
					$mid = vtxt($_GET['del']);
					
					$sql = 'DELETE FROM mail WHERE id=:mid AND from_id:uid AND type:type';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->bindValue(':type', 2, PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					
					if ($del == true)
						throwInfo('success', 'Wiadomość usunięta', true);
					else
						throwInfo('danger', 'Nie ma takiej wiadomości', true);
				}
				
				$mail = 'SELECT * FROM mail WHERE from_id=:uid AND type=:type';
				$stmt = $db->prepare($mail);
				$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
				$stmt->bindValue(':type', 2, PDO::PARAM_INT);
				$stmt->execute();
				
				if ($present = $stmt->rowCount()) {
					unset($stmt);
					throwInfo('info', 'Brak wysłanych wiadomości', false);
				} else {
					$stmt = $db->prepare($mail);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->bindValue(':type', 2, PDO::PARAM_INT);
					$stmt->execute();
					while ($msg = $stmt->fetch()):
						//$name = row("SELECT login FROM users WHERE id = ".$msg['from_id']); 
						$sql = 'SELECT login FROM users WHERE id=:id';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':id', $msg['from_id'], PDO::PARAM_INT);
						$stmt->execute();
						$name = $stmt->fetch();
						?>
			<div class="panel panel-default">
				<div class="panel-heading"><b><i><?=$name['login'];?></i>: <?=$msg['title'];?></b><span style="float: right;"><?=$msg['date'];?></span></div>
				<div class="panel-body">
					<?=$msg['content'];?>
				</div>
				<div class="panel-footer">
					<a href="index.php?a=mail&s=sent&del=<?=$msg['id'];?>" class="btn btn-primary btn-sm">Usuń</a>
					<br/>
				</div>
			</div>
					<?php endwhile; unset($stmt);
				}
			else:
				if (isset($_GET['del']) && !empty($_GET['del'])) {
					$_GET['del'] = (int)$_GET['del'];
					$mid = vtxt($_GET['del']);
					//$del = call("DELETE FROM mail WHERE id = ".$mid." AND to_id = ".$user['id']." AND type = 1");
					$sql = 'DELETE FROM mail WHERE id=:mid AND to_id=:uid AND type=:type';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->bindValue(':type', 1, PDO::PARAM_INT);
					$stmt->execute();
					$del = $stmt->fetch();
					unset($stmt);
					if ($del == true)
						throwInfo('success', 'Wiadomość usunięta', true);
					else
						throwInfo('danger', 'Nie ma takiej wiadomości', true);
				}

				//$mail = "SELECT * FROM mail WHERE to_id = ".$user['id']." AND type = 1";
				$mail = 'SELECT count(*) AS ilosc FROM mail WHERE to_id=:uid AND type=:type';
				$stmt = $db->prepare($mail);
				$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
				$stmt->bindValue(':type', 1, PDO::PARAM_INT);
				$stmt->execute();
				$present = $stmt->fetch();
				if ($present['ilosc'] == 0) {
					throwInfo('info', 'Brak odebranych wiadomości', false);
				} else {
					$mail = 'SELECT * FROM mail WHERE to_id=:uid AND type=:type';
					$stmt = $db->prepare($mail);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->bindValue(':type', 1, PDO::PARAM_INT);
					$stmt->execute();
					
					while ($msg = $stmt->fetch()):
						if ($msg['from_id'] == 0)
							$name['login'] = "[SYSTEM]";
						else{
							//$name = row("SELECT login FROM users WHERE id = ".$msg['from_id']); 
							$sql = 'SELECT login FROM users WHERE id=:mid';
							$stmt2 = $db->prepare($sql);
							$stmt2->bindValue(':mid', $msg['from_id'], PDO::PARAM_INT);
							$stmt2->execute();
							$name = $stmt2->fetch();
						}
						?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<b><i><?=$name['login'];?></i>: <?=$msg['title'];?><?=($msg['status'] == 0) ? '&nbsp;<span class="badge">Nowa!</span>' : '';?></b><span style="float: right;"><?=$msg['date'];?></span>
				</div>
				<div class="panel-body">
					<?=$msg['content'];?>
				</div>
				<div class="panel-footer">
					<?php if ($msg['from_id'] != 0): ?>
					<a href="index.php?a=mail&s=new&to_id=<?=$name['login'];?>" class="btn btn-primary btn-sm">Odpisz</a>
					<?php endif; ?>
					<a href="index.php?a=mail&del=<?=$msg['id'];?>" class="btn btn-primary btn-sm">Usuń</a>
					<br/>
				</div>
			</div>
					<?php endwhile; unset($stmt);
					//call("UPDATE mail SET status = 1 WHERE to_id = ".$user['id']." AND type = 1 AND status = 0");
					$sql = 'UPDATE mail SET status=:status WHERE to_id=:uid AND type=:type AND status=:status2';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':status', 1, PDO::PARAM_INT);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->bindValue(':type', 1, PDO::PARAM_INT);
					$stmt->bindValue(':status2', 0, PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
				}
			endif;  unset($stmt2);
			?>
		</div>
	</div>
</div>