﻿
<div class="panel-body">
	<?php
		if (!empty($_SESSION['id']))
			header('Location: index.php?a=info');
		
		if (!empty($_POST)) {
			if (!isset($_POST['login']) || !isset($_POST['pass']) || !isset($_POST['pass2']) || !isset($_POST['email']) || !isset($_POST['class'])) {
				throwInfo('danger', 'Wypełnij pola poprawnie!', true);
		} else {
			$login = vtxt($_POST['login']);
			$pass = vtxt($_POST['pass']);
			$pass2 = vtxt($_POST['pass2']);
			$email = vtxt($_POST['email']);
			$class = vtxt($_POST['class']);
			if (strlen($login) < 3 || strlen($login) > 25)
				throwInfo('danger', 'Login nie mieści się w danym zakresie!', true);
			elseif (strlen($pass) < 6 || strlen($pass) > 20)
				throwInfo('danger', 'Hasło nie mieści się w danym zakresie!', true);
			elseif (strlen($email) < 8 || strlen($email) > 50)
				throwInfo('danger', 'Adres email nie mieści się w danym zakresie!', true);
			elseif ($login == $pass)
				throwInfo('danger', 'Login nie może być taki sam jak hasło!', true);
			elseif ($pass != $_POST['pass2'])
				throwInfo('danger', 'Podane hasła nie zgadzają się!', true);
			elseif (!ctype_alnum($login))
				throwInfo('danger', 'Login zawiera niedozwolone znaki!', true);
			elseif (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
				throwInfo('danger', 'To nie jest poprawny adres email!', true);
			else {
				$sql = 'SELECT Count(id) AS ilosc FROM users WHERE login=:login OR email=:email';
				$stmt = $db->prepare($sql);
				$stmt->bindValue(':login', $login, PDO::PARAM_STR);
				$stmt->bindValue(':email', $email, PDO::PARAM_STR);
				$stmt->execute();	
				$data = $stmt->fetchColumn();
				$stmt->closeCursor();
				if ($data>0){
					throwInfo('danger', 'Istnieje już gracz o takim samym loginie lub adresie email!', true);
				} else {
					$pass = md5(sha1(vtxt($pass)));
					$sql = 'INSERT INTO users (login, password, email, class) VALUES(:login, :pass, :email, :class)';
					$stmt = $db->prepare($sql);
					$stmt->bindValue(':login',$login,PDO::PARAM_STR);
					$stmt->bindValue(':pass',$pass,PDO::PARAM_STR);
					$stmt->bindValue(':email',$email,PDO::PARAM_STR);
					$stmt->bindValue(':class',$class,PDO::PARAM_STR);
					$stmt->execute();
					header('Location: index.php?a=login');
					}
				}
			}
		}
	?>
	
	<div class="well">
		<form class="form-horizontal" action="index.php?a=register" method="POST">
			<fieldset>
				<legend>Rejestracja</legend>
				<div class="form-group" align="right">
					<div class="col-lg-2"></div>
					<div class="col-lg-8">
						<input type="text" class="form-control" name="login" placeholder="Login">
						<span class="help-block">Od 3 do 25 znaków. Raz wybranego loginu <b><u>nie można zmienić</u></b>.</span>
					</div>
				</div>
				<div class="form-group" align="right">
					<div class="col-lg-2"></div>
					<div class="col-lg-8">
						<input type="password" class="form-control" name="pass" placeholder="Hasło">
					</div>
				</div>
				<div class="form-group" align="right">
					<div class="col-lg-2"></div>
					<div class="col-lg-8">
						<input type="password" class="form-control" name="pass2" placeholder="Powtórz hasło">
						<span class="help-block">Od 6 do 20 znaków.</span>
					</div>
				</div>
				<div class="form-group" align="right">
					<div class="col-lg-2"></div>
					<div class="col-lg-8">
						<input type="text" class="form-control" name="email" placeholder="E-mail">
						<span class="help-block">Od 8 do 50 znaków.</span>
					</div>
				</div>
				<div class="form-group" align="right">
					<div class="col-lg-2"></div>
					<div class="col-lg-8">
						<select class="form-control" name="class" id="class" placeholder="Klasa">
							<option>WOJOWNIK</option>
							<option>ŁOTR</option>
							<option>MAG</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-12 col-lg-offset-5">
						<button type="submit" class="btn btn-primary">Zatwierdź</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>