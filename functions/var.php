<?php
	
	if($user['class']=='MAG'){
		$hp = $user['sta']*2*($user['lvl']+1);
	}
	else if ($user['class']=='ŁOTR'){
		$hp = $user['sta']*4*($user['lvl']+1);
	}
	else if ($user['class']=='WOJOWNIK'){
		$hp = $user['sta']*5*($user['lvl']+1);
	}

	$stats = stats($user['id']);
	
	if($user['class']=='MAG'){
		$hp = $stats['sta']*2*($user['lvl']+1);
	}
	else if ($user['class']=='ŁOTR'){
		$hp = $stats['sta']*4*($user['lvl']+1);
	}
	else if ($user['class']=='WOJOWNIK'){
		$hp = $stats['sta']*5*($user['lvl']+1);
	}
	
	
	$fen = 100;
	$en = $user['energy'];
	$wEn = ($en / $fen) * 100;
	if (is_float($wEn) == false) $pEn = $wEn; else $pEn = round($wEn, 0);
	
	$fpd = $user['max_xp'];
	$pd = $user['xp'];
	$wpd = ($pd / $fpd) * 100;
	if (is_float($wpd) == false) $pbpd = $wpd; else $pbpd = round($wpd, 0);
	
	$fap = 10;
	$ap = $user['ap'];
	$wAp = ($ap / $fap) * 100;
	if (is_float($wAp) == false) $pAp = $wAp; else $pAp = round($wAp, 0);

	if (!empty($_GET['a']))
		$hl = 'index.php?a='.$_GET['a'];
	else
		$hl = 'index.php?';

	if ($user['sp'] > 0) {
		$wybutton = '
			<span class="input-group-btn">
				<a class="btn btn-default" href="'.$hl.'&skill=sta"><span class="glyphicon glyphicon-upload"/></a>
			</span>
		';
		$sibutton = '
			<span class="input-group-btn">
				<a class="btn btn-default" href="'.$hl.'&skill=str"><span class="glyphicon glyphicon-upload"/></a>
			</span>
		';
		$zrbutton = '
			<span class="input-group-btn">
				<a class="btn btn-default" href="'.$hl.'&skill=dex"><span class="glyphicon glyphicon-upload"/></a>
			</span>
		';
		$inbutton = '
			<span class="input-group-btn">
				<a class="btn btn-default" href="'.$hl.'&skill=intell"><span class="glyphicon glyphicon-upload"/></a>
			</span>
		';
		$szbutton = '
			<span class="input-group-btn">
				<a class="btn btn-default" href="'.$hl.'&skill=luck"><span class="glyphicon glyphicon-upload"/></a>
			</span>
		';
	} else {
		$wybutton = '<span class="input-group-addon"></span>';
		$sibutton = '<span class="input-group-addon"></span>';
		$zrbutton = '<span class="input-group-addon"></span>';
		$inbutton = '<span class="input-group-addon"></span>';
		$szbutton = '<span class="input-group-addon"></span>';
	}
?>