<?php
    require_once("connect_db.php");
	session_start();
	if($_SESSION['id_department']==1 or empty($_SESSION['id'])){
		header('Location: index.php');	
	}
	$sql="SELECT `users`.*, `department`.`name` AS `d_name` FROM `users` INNER JOIN `department` ON `users`.`id_department`=`department`.`id` WHERE `users`.`id`='".$_SESSION['id']."'";
	$res=mysqli_query($connect,$sql);
	$array_user=mysqli_fetch_array($res);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Налаштування профілю</title>
        <link rel='stylesheet' type='text/css' href='main.css'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body>
        <div style="height: 15px;"></div>
        <div class="row"> <!---  шапка  --->
			<div class="col-sm-10"></div>
			<div class="dropdown col-sm-1">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                    Профіль
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
					<li><a class="dropdown-item" href="index.php">Переглянути розклад</a></li>
                    <li><a class="dropdown-item" href="change.php?v=<?php echo $_SESSION['id_department']; ?>">Редагувати розклад</a></li>
					<li><a class="dropdown-item" href="settings_subj.php?v=<?php echo $_SESSION['id_department']; ?>">Налаштування</a></li>
					<li><a class="dropdown-item" href="users_settings.php">Налаштуваня профілю</a></li>
					<li><a class="dropdown-item" href="exit.php">Вихід</a></li>
                </ul>
            </div>	
        </div>
        <div style="height: 8px;"></div>
		<div style="height: 2px; background-color: #ECECEC;"></div>
		<div style="height: 10px;"></div>
		<?php
			if(!empty($_GET['error'])){
				echo '<div class="row"><div class="col-sm-4"></div><div class="col-sm-4 alert alert-danger" role="alert"><strong>Помилка! </strong> Даний логін вже використовується</div>';
			}
		?>
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-6">
				<br>
				<h4>Редагувати особисті дані</h4>
				<hr>
				<br>
				<div class="row">
					<div class="col-sm-2"></div>
					<div class="col-sm-8">
						<form action="users_settings.php" method="POST">
							<div class="form-floating mb-3">
								<input type="text" name="pib" value="<?php echo $array_user['name']; ?>" class="form-control" id="floatingInput" placeholder="ПІБ">
								<label for="floatingInput">ПІБ</label>
							</div>
							<div class="form-floating mb-3">
								<input type="text" value="<?php echo $array_user['d_name']; ?>" disabled class="form-control" id="floatingPassword" placeholder="Роль">
								<label for="floatingPassword">Роль</label>
							</div>
							<div class="col form-floating mb-3">
									<input type="login" name="login" value="<?php echo $array_user['login']; ?>" class="form-control" id="floatingInput" placeholder="Логін">
									<label for="floatingInput">Логін</label>
								</div>
							<div class="row form-floating mb-3">
								<div class="col form-floating mb-3">
									<input type="password" name="pass" class="form-control" id="floatingInput" placeholder="Пароль">
									<label for="floatingInput">Пароль</label>
								</div>
								<div class="col form-floating mb-3">
									<input type="password" name="pass1" class="form-control" id="floatingInput" placeholder="Повторіть пароль">
									<label for="floatingInput">Повторіть пароль</label>
								</div>
							</div>
							<div class="form-floating text-center">
								<button type="submit" name="save" class="btn btn-outline-secondary">Зберегти</button>
							</div>
						</form>
					</div>
					<div class="col-sm-2"></div>
				</div>
			</div>
			<div class="col-sm-3"></div>
		</div>
    </body>
</html>
<?php 
	if(isset($_POST['save'])){
		//pib, rol, login, pass, pass1
		if(!empty($_POST['pass']) and !empty($_POST['pass1']) and $_POST['pass']==$_POST['pass1']){
			$sql="UPDATE `users` SET `password`='".password_hash($_POST['pass'], PASSWORD_DEFAULT)."' WHERE `id`='".$_SESSION['id']."';";
			$res=mysqli_query($connect,$sql);
		}
		$sqll="SELECT `login` FROM `users` WHERE `login`='".$_POST['login']."';";
		$res=mysqli_query($connect,$sqll);
		$result1=mysqli_fetch_array($res);
		if(!empty($result1)){
			?>
            <script>document.location.href="users_settings.php?error=q"</script>
        <?php
		} else {		
			$sql = "UPDATE `users` SET `name`='".$_POST['pib']."', `login`='".$_POST['login']."' WHERE `id`='".$_SESSION['id']."'";
			$res=mysqli_query($connect,$sql);
			?>
				<script>document.location.href="users_settings.php"</script>
			<?php
		}
	}
 ?>