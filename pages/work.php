
<script src="js/time.js"></script>
<div class="panel-heading">
	<h3 class="panel-title">Praca</h3>
</div>
<div class="panel-body">
	<?php
	if ($user['status'] != 0 && $user['status'] != 1):
		throwInfo('danger', 'Aktualnie nie możesz pracować', false);
	else:
		$location = getLocData($user['pos_x'], $user['pos_y']);
		if (!$location):
			throwInfo('danger', 'Błąd pobierania danych lokacji', true);
		else:
			$sql = 'SELECT * FROM work WHERE uid=:uid';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
			$stmt->execute();
			$work = $stmt->fetch();
			unset($stmt);

			if ($work['end'] == 0):
				if (!empty($_POST) && isset($_POST['time'])) {
					$h = intval($_POST['time']);
					$time = time();
					$end = $time + 3600 * $h;
					if ($user['energy'] < $h)
						throwInfo('danger', 'Nie masz siły na taką pracę!', true);
					else {
					//call("UPDATE users SET status = 1 WHERE id = ".$user['id']);
					$sql = 'UPDATE users SET status=:status WHERE id=:uid ';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':status', 1, PDO::PARAM_INT);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					
					//call("INSERT INTO work (uid, start, end, hours) VALUES (".$user['id'].", ".$time.", ".$end.", ".$h.")");
					$sql = 'INSERT INTO work (uid, start, end, hours) VALUES
											 (:uid, :time, :end, :hours)';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->bindValue(':time', $time, PDO::PARAM_INT);
					$stmt->bindValue(':end', $end, PDO::PARAM_INT);
					$stmt->bindValue(':hours', $h, PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);				
					header("Location: index.php?a=work");
					}
				} ?>
	<div class="well">
		<form action="index.php?a=work" method="POST" class="form-horizontal">
			<fieldset>
				<?php
					switch ($location['id']){
						case 1:
							echo '<div class="form-group">
									<label class="control-label">Sprzątanie okolicy:</label>
								  </div>';
								  break;
						case 2:
							echo '<div class="form-group">
									<label class="control-label">Pilnowanie porządku:</label>
								  </div>';
								  break;
						case 3:
							echo '<div class="form-group">
									<label class="control-label">Ochrona miasteczka:</label>
								  </div>';
								  break;
						case 4:
							echo '<div class="form-group">
									<label class="control-label">Patrolowanie okolicy:</label>
								  </div>';
								  break;
						case 5:
							echo '<div class="form-group">
									<label class="control-label">Warta:</label>
								  </div>';
								  break;
					}
				?>
				<div class="form-group">
					<div class="col-lg-10">
						<input style="text-align: center;" class="form-control" id="praca" type="text" placeholder="<?=$location['re_cash'];?>$ na godzine" disabled>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-10">
						<select name="time" class="form-control">
							<option value="1">1 godzina</option>
							<option value="2">2 godziny</option>
							<option value="3">3 godziny</option>
							<option value="5">5 godzin</option>
							<option value="6">6 godzin</option>
							<option value="7">7 godzin</option>
							<option value="8">8 godzin</option>
							<option value="9">9 godzin</option>
							<option value="10">10 godzin</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-5">
						<button type="submit" class="btn btn-primary">Pracuj</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
			<?php else:
				$time = time();
				if ($time >= $work['end']):
					$sql = 'SELECT cash FROM users WHERE id=:uid';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					$cash = $stmt->fetch();
					unset($stmt);
					$location = getLocData($user['pos_x'], $user['pos_y']);
					//call("UPDATE users SET  cash = cash + hours * ".$location['re_cash'].", status = 0 WHERE id = ".$user['id']);
					$sql = 'UPDATE users SET cash=:cash, status=:status WHERE id=:uid';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':cash', $cash['cash'] + ($h+$user['lvl']) * $location['re_cash'], PDO::PARAM_INT);
					$stmt->bindValue(':status', 0, PDO::PARAM_INT);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					//call("DELETE FROM work WHERE uid = ".$user['id']);
					$sql = 'DELETE FROM work WHERE id=:uid';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
					$stmt->execute();
					unset($stmt);
					header("Location: index.php?a=work");
				else:
					if (isset($_GET['stop'])) {
						//call("UPDATE users SET status = 0 WHERE id = ".$user['id']);
						$sql = 'UPDATE users SET status=:status WHERE id=:uid';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':status', 0, PDO::PARAM_INT);
						$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
						$stmt->execute();
						unset($stmt);
						//call("DELETE FROM work WHERE uid = ".$user['id']);
						$sql = 'DELETE FROM work WHERE uid=:uid';
						$stmt = $db->prepare($sql);
						$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
						$stmt->execute();
						unset($stmt);
						header("Location: index.php?a=work");
					}
					$remains = $work['end'] - $time; ?>
	<div class="well">
		<form action="index.php?a=work&stop" method="POST" class="form-horizontal">
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
						<button type="submit" class="btn btn-primary">Zakończ pracę</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<script type="text/javascript">postep(<?=$time;?>, <?=$work['start'];?>, <?=$work['end'];?>)</script>
	<script type="text/javascript">liczCzas(<?=$remains;?>)</script>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>