
<script src="js/time.js"></script>
<div class="panel-heading">
	<h3 class="panel-title">Karczma</h3>
</div>
<div class="panel-body">
	<?php
	if ($user['status'] != 0 && $user['status'] != 3):
		throwInfo('danger', 'Aktualnie nie możesz wybrać się do karczmy', false);
	else:
		$i = 0;
		$p = 0;
		//$tavern = row("SELECT * FROM tavern WHERE uid = ".$user['id']);
		$sql = 'SELECT * FROM tavern WHERE uid=:uid';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
		$stmt->execute();
		$tavern = $stmt->fetch();
		unset($stmt);
		if ($tavern['end'] == 0):
			if (!empty($_POST) && isset($_POST['hours'])) {
				$h = intval($_POST['hours']);
				$time = time();
				$end = $time + 3600 * $h;
				
				if ($user['status'] != 0)
					return;
				
				if ($h == 0) {
					$hours = (100 - $user['energy']) / 2;
					if (fmod($hours, 2) == 0) {
						$p = $hours * 2;
						if ($user['energy'] + $p > 100)
							return;
						else {
							$time = time();
							$end = $time + 3600 * $hours;
							//call("UPDATE users SET status = 3 WHERE id = ".$user['id']);
							$sql = 'UPDATE users SET status=:status WHERE id=:uid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':status', 3, PDO::PARAM_INT);
							$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							//call("INSERT INTO tavern (uid, start, end, hours, is_int) VALUES (".$user['id'].", ".$time.", ".$end.", ".$hours.", 1)");
							$sql = 'INSERT INTO tavern (uid, start, end, hours, is_int) VALUES
														(:uid, :time, :end, :hours, :is_int)';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
							$stmt->bindValue(':time', $time, PDO::PARAM_INT);
							$stmt->bindValue(':end', $end, PDO::PARAM_INT);
							$stmt->bindValue(':hours', $hours, PDO::PARAM_INT);
							$stmt->bindValue(':is_int', 1, PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);					
							header("Location: index.php?a=tavern");
						}
					} else {
						$hours = floor($hours);
						$p = $hours * 2;
						if ($user['energy'] + $p > 100)
							return;
						else {
							$time = time();
							$end = $time + 3600 * $hours + 1800;
							//call("UPDATE users SET status = 3 WHERE id = ".$user['id']);
							$sql = 'UPDATE users SET status=:status WHERE id=:uid';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':status', 3, PDO::PARAM_INT);
							$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);
							//call("INSERT INTO tavern (uid, start, end, hours, is_int) VALUES (".$user['id'].", ".$time.", ".$end.", ".$hours.", 0)");
							$sql = 'INSERT INTO tavern (uid, start, end, hours, is_int) VALUES
														(:uid, :time, :end, :hours, :is_int)';
							$stmt = $db->prepare($sql);
							$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
							$stmt->bindValue(':time', $time, PDO::PARAM_INT);
							$stmt->bindValue(':end', $end, PDO::PARAM_INT);
							$stmt->bindValue(':hours', $hours, PDO::PARAM_INT);
							$stmt->bindValue(':is_int', 0, PDO::PARAM_INT);
							$stmt->execute();
							unset($stmt);					
							header("Location: index.php?a=tavern");
						}
					}
				} elseif ($h > 0) {
					$p = $h * 2;
					if ($user['energy'] + $p > 100)
						return;
					else {
						$time = time();
						$end = $time + 3600 * $h;
						//call("UPDATE users SET status = 3 WHERE id = ".$user['id']);
						$sql = 'UPDATE users SET status=:status WHERE id=:uid';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':status', 3, PDO::PARAM_INT);
						$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
						$stmt->execute();
						unset($stmt);
						//call("INSERT INTO tavern (uid, start, end, hours, is_int) VALUES (".$user['id'].", ".$time.", ".$end.", ".$h.", 1)");
						$sql = 'INSERT INTO tavern (uid, start, end, hours, is_int) VALUES
														(:uid, :time, :end, :hours, :is_int)';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
						$stmt->bindValue(':time', $time, PDO::PARAM_INT);
						$stmt->bindValue(':end', $end, PDO::PARAM_INT);
						$stmt->bindValue(':hours', $hours, PDO::PARAM_INT);
						$stmt->bindValue(':is_int', 1, PDO::PARAM_INT);
						$stmt->execute();
						unset($stmt);
						header("Location: index.php?a=tavern");
					}
				}
			}
			?>
			<div class="well">
				<form action="index.php?a=tavern" method="POST" class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-lg-2 control-label">Koszt:</label>
							<div class="col-lg-10">
								<input style="text-align: center;" class="form-control" type="text" placeholder="10$/godzinę - 2 energii" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Godzin:</label>
							<div class="col-lg-10">
								<select name="hours" class="form-control">
									<?php $time = (100 - $user['energy']) / 2;?>
									<option value="0">Pełny odpoczynek - <?=$time;?> godz.</option>
									<?php while (100 > ($user['energy'] + $p)): $i++; $p += 2;?>
									<?php if (($p + $user['energy'] + 1) <= 100): ?>
									<option value="<?=$i;?>"><?=$i;?> godz.</option>
									<?php endif; ?>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-6">
								<button type="submit" class="btn btn-primary">Odpocznij</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<?php
		else:
			$time = time();
			if ($time >= $tavern['end']):
				if ($tavern['is_int'] == 0) {
					//call("UPDATE users SET  cash = cash - (10 * ".$tavern['hours'].") + 5, status = 0 WHERE id = ".$user['id']);
					$sql = 'UPDATE users SET cash=:cash, status=:status WHERE id=:uid ';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':cash', $user['cash'] - (10*$tavern['hours'])+5, PDO::PARAM_INT);
					$stmt->bindValue(':status', 0, PDO::PARAM_INT);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					//call("DELETE FROM tavern WHERE uid = ".$user['id']);
					$sql = 'DELETE FROM tavern WHERE uid=:uid';
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
				} else {
					//call("UPDATE users SET  cash = cash - (10 * ".$tavern['hours']."), status = 0 WHERE id = ".$user['id']);
					$sql = 'UPDATE users SET cash=:cash, status=:status WHERE id=:uid ';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':cash', $user['cash'] - (10*$tavern['hours']), PDO::PARAM_INT);
					$stmt->bindValue(':status', 0, PDO::PARAM_INT);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					//call("DELETE FROM tavern WHERE uid = ".$user['id']);
					$sql = 'DELETE FROM tavern WHERE uid=:uid';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
				}
				header("Location: index.php?a=tavern");
			else:
				if (isset($_GET['stop'])) {
					//call("UPDATE users SET status = 0 WHERE id = ".$user['id']);
					$sql = 'UPDATE users SET status=:status WHERE id=:uid ';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':status', 0, PDO::PARAM_INT);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					//call("DELETE FROM tavern WHERE uid = ".$user['id']);
					$sql = 'DELETE FROM tavern WHERE uid=:uid';
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					header("Location: index.php?a=tavern");
				}
				$remains = $tavern['end'] - $time; ?>
			<div class="well">
				<form action="index.php?a=tavern&stop" method="POST" class="form-horizontal">
					<fieldset>
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
								<button type="submit" class="btn btn-primary">Wyjdź z karczmy</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<script type="text/javascript">postep(<?=$time;?>, <?=$tavern['start'];?>, <?=$tavern['end'];?>)</script>
			<script type="text/javascript">liczCzas(<?=$remains;?>)</script>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>