<?php
    require_once("connect_db.php");
	session_start();
	if($_SESSION['id_department']!=1 or empty($_SESSION['id'])){
		header('Location: index.php');	
	} 
	$sql="SELECT `users`.*, `department`.`name` AS `d_name` FROM `users` INNER JOIN `department` ON `users`.`id_department`=`department`.`id`";
	$res=mysqli_query($connect,$sql);
	$array_user=[];
	while($result1=mysqli_fetch_array($res)){
		$array_user[] = $result1;
	}
	
	$sql="SELECT `id`,`name` FROM `department`";
	$res=mysqli_query($connect,$sql);
	while($result1=mysqli_fetch_array($res)){
		$array_department[] = $result1;
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Користувачі</title>
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
                    <li><a class="dropdown-item" href="change.php">Редагувати розклад</a></li>
					<li><a class="dropdown-item" href="users.php">Користувачі</a></li>
					<li><a class="dropdown-item" href="settings_subj.php?v=2">Налаштування</a></li> 
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
		<!---   табчилка викладач - роль - редагувати  --->
		<div class="row">
			<div class="col-sm-1"></div>
			<div class="col-sm-10">
				<table class="table">
					<thead>
						<tr>
							<th scope="col">№</th>
							<th scope="col">Користувач</th>
							<th scope="col">Роль</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0; $i<count($array_user); $i++){?>
						<tr>
							<th scope="row"><?php echo $i+1; ?></th>
							<td><?php echo $array_user[$i]['name']; ?></td>
							<td><?php echo $array_user[$i]['d_name'];?></td>
							<td data-bs-toggle="modal" data-bs-target="#staticBackdrop0<?php echo $i; ?>">
								<?php if($array_user[$i]['id']!=1){?>
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil " viewBox="0 0 16 16">
									<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
								</svg>
								<?php } ?>
							</td>
						</tr>
						<div class="modal fade" id="staticBackdrop0<?php echo $i; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="staticBackdropLabel">Редагувати</h5>
										<button type="button" class="btn-close text-center" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<form method="POST" action="users.php">
										<div class="form-floating mb-3">
											<input type="hidden" name="id" value="<?php echo $array_user[$i]['id']; ?>">
											<input type="text" class="form-control" value="<?php echo $array_user[$i]['name']; ?>" name="pib" id="floatingInput" placeholder="ПІБ">
											<label for="floatingInput">ПІБ</label>
										</div>
										<div class="form-floating">
											<select class="form-control" id="floatingPassword" name="rol" placeholder="Роль">
											<?php 
												for($k=0; $k<count($array_department);$k++){
													echo "<option value='".$array_department[$k][0]."'";
													if($array_department[$k][1]==$array_user[$i]['d_name']){
														echo "selected";
													}
													echo ">".$array_department[$k][1]."</option>";
												}
											?>
											</select>
											<label for="floatingPassword">Роль</label>
										</div>
										<br>
										<div class="row form-floating">
											<div class="col form-floating">
												<input type="text" name="login" value="<?php echo $array_user[$i]['login']; ?>" class="form-control" id="floatingInput" placeholder="Логін">
												<label for="floatingInput">Логін</label>
											</div>
										</div>
										<br>
										<div class="row form-floating">
											<div class="col form-floating">
												<input type="password" name="pass" class="form-control" id="floatingInput" placeholder="Пароль">
												<label for="floatingInput">Пароль</label>
											</div>
											<div class="col form-floating">
												<input type="password" name="pass1" class="form-control" id="floatingInput" placeholder="Підтвердити пароль">
												<label for="floatingInput">Підтвердити пароль</label>
											</div>
										</div>
										<br>
										<div class="form-floating text-center">
											<button type="submit" name="save" class="btn btn-outline-secondary" style="margin-right: 40%;">Зберегти</button>
											<button type="submit" name="delete" class="btn btn-outline-secondary">Видалити</button>
										</div>
										</form>
									</div>
									<div class="modal-footer">
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
						<tr>
							<td colspan="4" style="text-align: center;">
								<button type="button"  data-bs-toggle="modal" data-bs-target="#staticBackdropNew">
									<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"  fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
										<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
										<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
									</svg>
								</button>
								<div class="modal fade" id="staticBackdropNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="staticBackdropLabel">Додати користувача</h5>
												<button type="button" class="btn-close text-center" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<form method="POST" action="users.php">
												<div class="form-floating mb-3">
													<input type="text" name="pib" class="form-control" id="floatingInput" placeholder="ПІБ">
													<label for="floatingInput">ПІБ</label>
												</div>
												<div class="form-floating">
													<select class="form-control" id="floatingPassword" name="rol" placeholder="Роль">
													<option></option>
														<?php 
															for($k=0; $k<count($array_department);$k++){
																echo "<option value='".$array_department[$k][0]."'>".$array_department[$k][1]."</option>";
															}
														?>
														</select>
													<label for="floatingPassword">Роль</label>
												</div>
												<br>
												<div class="row form-floating">
													<div class="col form-floating">
														<input type="text" name="login" class="form-control" id="floatingInput" placeholder="Логін">
														<label for="floatingInput">Логін</label>
													</div>
												</div>
												<br>
												<div class="row form-floating">
													<div class="col form-floating">
														<input type="password" name="pass" class="form-control" id="floatingInput" placeholder="Пароль">
														<label for="floatingInput">Пароль</label>
													</div>
													<div class="col form-floating">
														<input type="password" name="pass1" class="form-control" id="floatingInput" placeholder="Підтвердити пароль">
														<label for="floatingInput">Підтвердити пароль</label>
													</div>
												</div>
												<br>
												<div class="form-floating text-center">
													<button type="submit" name="new" class="btn btn-outline-secondary">Зберегти</button>
												</div>
												</form>
											</div>
											<div class="modal-footer">
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-sm-1"></div>
		</div>
    </body>
</html>
<?php  
	if(isset($_POST['save'])){
		//pib, rol, login, pass, pass1
		if(!empty($_POST['pass']) and !empty($_POST['pass1']) and $_POST['pass']==$_POST['pass1']){
			$sql="UPDATE `users` SET `password`='".password_hash($_POST['pass'], PASSWORD_DEFAULT)."' WHERE `id`='".$_POST['id']."';";
			$res=mysqli_query($connect,$sql);
		}
		$sqll="SELECT `login` FROM `users` WHERE `login`='".$_POST['login']."';";
		$res=mysqli_query($connect,$sqll);
		$result1=mysqli_fetch_array($res);
		if(!empty($result1)){
			?>
            <script>document.location.href="users.php?error=q"</script>
        <?php
		} else {		
			$sql = "UPDATE `users` SET `name`='".$_POST['pib']."', `id_department`='".$_POST['rol']."', `login`='".$_POST['login']."' WHERE `id`='".$_POST['id']."'";
			$res=mysqli_query($connect,$sql);
			?>
				<script>document.location.href="users.php"</script>
			<?php
		}
	}
	if(isset($_POST['delete'])){
		//pib, rol, login, pass, pass1
		$sql = "DELETE FROM `users` WHERE `id`='".$_POST['id']."'";
		$res=mysqli_query($connect,$sql);
		?>
            <script>document.location.href="users.php"</script>
        <?php
	}
	if(isset($_POST['new'])){
		//pib, rol, login, pass, pass1
		$sqll="SELECT `login` FROM `users` WHERE `login`='".$_POST['login']."';";
		$res=mysqli_query($connect,$sqll);
		$result1=mysqli_fetch_array($res);
		if(!empty($result1)){
			?>
            <script>document.location.href="users.php?error=q"</script>
        <?php
		} else {	
			if($_POST['pass']==$_POST['pass1']){
				$sql = "INSERT INTO `users` (`login`, `password`, `name`, `id_department`) VALUES ('".$_POST['login']."','".password_hash($_POST['pass'], PASSWORD_DEFAULT)."','".$_POST['pib']."','".$_POST['rol']."')";
				$res=mysqli_query($connect,$sql);
			}
			?>
				<script>document.location.href="users.php"</script>
			<?php
		}
	}
?>