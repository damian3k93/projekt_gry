<?php

	$time = time();
	global $db;

	$sql = 'SELECT last_action FROM cron';
	$stmt = $db->query($sql);
	$lastAction = $stmt->fetch();
	$stmt->closeCursor();
	
	$nextAction = $lastAction['last_action'] + 86400;
	if ($time >= $nextAction){
		restoreEnergy();
		$sql = 'UPDATE cron SET last_action=:time';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':time',$time);
		$stmt->execute();
		$stmt->closeCursor();
	}

?>