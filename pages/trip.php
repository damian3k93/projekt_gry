
<script src="js/time.js"></script>
<div class="panel-heading">
	<h3 class="panel-title">Wyprawa</h3>
</div>
<div class="panel-body">
	<?php
	if ($user['status'] == 1):
		throwInfo('danger', 'Nie możesz podróżować będąc w pracy', false);
	else:
		$sql = 'SELECT * FROM trips WHERE uid=:uid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
		$stmt->execute();
		$trip = $stmt->fetch();
		unset($stmt);
		
		if (!$trip):
			if (!isset($_GET['id']))
				header("Location: index.php?a=map");
			else {
				$id = intval($_GET['id']);
				$sql = 'SELECT * FROM locations WHERE id=:id';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				$loc = $stmt->fetch();
				unset($stmt);
				
				if ($loc) {
					$time = time();
					$dis = distance($user['pos_x'], $user['pos_y'], $loc['x'], $loc['y']);
					$end = $time + (($dis/100) * 300);
					$sql ='UPDATE users SET status=2 WHERE id=:uid';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					
					$sql = 'INSERT INTO trips (uid, start, end, x, y, dis) VALUES (:uid, :time, :end, :loc_x, :loc_y, :dis)';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->bindValue(':time', $time);
					$stmt->bindValue(':end', $end, PDO::PARAM_INT);
					$stmt->bindValue(':loc_x', $loc['x'], PDO::PARAM_INT);
					$stmt->bindValue(':loc_y', $loc['y'], PDO::PARAM_INT);
					$stmt->bindValue(':dis', $dis, PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					
					header("Location: index.php?a=trip");
				}
			}
		else:
			$time = time();
			if ($time >= $trip['end']):
				header("Location: index.php?a=table");
			else:
				if (isset($_GET['stop'])) {
					//call("UPDATE users SET status = 0 WHERE id = ".$user['id']);
					$sql = 'UPDATE users SET status=0 WHERE id=:uid ';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					
					//call("DELETE FROM trips WHERE uid = ".$user['id']);
					$sql = 'DELETE FROM trips WHERE uid=:uid ';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					
					header("Location: index.php?a=table");
				}
				
				$remains = $trip['end'] - $time;
				$from = getLocData($user['pos_x'], $user['pos_y']);
				$to = getLocData($trip['x'], $trip['y']);?>
	<div class="well">
		<form action="index.php?a=trip&stop" method="POST" class="form-horizontal">
			<fieldset>
				<div class="input-group">
					<input class="form-control" style="text-align: center;" id="disabledInput" type="text" placeholder="<?=$from['name'];?>" disabled>
					<span class="input-group-addon">-></span>
					<input class="form-control" style="text-align: center;" id="disabledInput" type="text" placeholder="<?=$to['name'];?>" disabled>
				</div>
				<br/>
				<div class="form-group">
					<div class="col-lg-12">
						<div class="progress">
							<div id="pasek" class="progress-bar progress-bar-info" style="width: 100%;">
								<span id="zegar">abc</span>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-5">
						<button type="submit" class="btn btn-primary">Anuluj wyprawę</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
			<?php endif; ?>
	<script type="text/javascript">postep(<?=$time;?>, <?=$trip['start'];?>, <?=$trip['end'];?>)</script>
	<script type="text/javascript">liczCzas(<?=$remains;?>)</script>
		<?php endif; ?>
	<?php endif; ?>
</div>