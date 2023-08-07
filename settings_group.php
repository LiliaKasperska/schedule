<?php
    require_once("connect_db.php");
session_start();
	if($_SESSION['id_department']!=1 or empty($_SESSION['id'])){
		header('Location: index.php');	
	}
	$sql="SELECT * FROM `department` WHERE `id`!='1'";
	$res=mysqli_query($connect,$sql);
	$array_department=[];
	while($result1=mysqli_fetch_array($res)){
		$array_department[] = $result1;
	}
?> 
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Групи</title>
        <link rel='stylesheet' type='text/css' href='main.css'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body>
        <div style="height: 15px;"></div>
        <div class="row"> <!---  шапка  --->
			<div class="col-sm-1"></div>
			<div class="col-sm-2">
				<button class="btn btn-outline-secondary col-sm-12" type="button"  id="dropdownMenuButton0" data-bs-toggle="dropdown" aria-expanded="false">Предмети</button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton0">
					<?php for ($i = 0; $i < count($array_department);$i++){
						echo "<li><a class='dropdown-item' href='settings_subj.php?v=".$array_department[$i][0]."'>".$array_department[$i][1]."</a></li>";
					} ?>
                </ul>
			</div>
			<div class="col-sm-2">
				<a href="settings_teacher.php"><button class="btn btn-outline-secondary col-sm-12" type="button" aria-expanded="false">
                    Викладачі
                </button></a>
			</div>
			<div class="col-sm-2">
				<a href="settings_group.php"><button class="btn btn-outline-secondary col-sm-12 active" type="button" aria-expanded="false">
                    Групи
                </button></a>
			</div>
			<div class="col-sm-2">
				<a href="settings_vid.php"><button class="btn btn-outline-secondary col-sm-12" type="button" aria-expanded="false">
                    Відділення
                </button></a>
			</div>
			<div class="col-sm-1"></div>
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
		<div class="row">
			<div class="col-sm-1"></div>
			<div class="col-sm-2">
				<ul class="nav flex-column" style="margin-top: 15%;">
					<?php for ($i = 0; $i < count($array_department);$i++){
						echo "<li class='nav-item'><a class='nav-link' href='#v".$array_department[$i][0]."' style='color: #7e868d; margin-bottom: 5%;'>".$array_department[$i][1]."</a></li>";
					} ?>
				</ul>
			</div>
			<div class="col-sm-6">
				<?php for ($i = 0; $i < count($array_department);$i++){ ?>
					<table class="table">
						<thead>
							<tr><th colspan="5" id="v<?php echo $array_department[$i][0]; ?>"><?php echo $array_department[$i][1]; ?></th></tr>
							<tr>
								<th scope="col">№</th>
								<th scope="col">Назва групи</th>
								<th scope="col">Куратор</th>
								<th scope="col">Спеціальності</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$sql="SELECT `groups`.*, `department`.`name` AS `d_name` FROM `groups` INNER JOIN `department` ON `groups`.`id_department`=`department`.`id` WHERE `groups`.`id_department`='".$array_department[$i][0]."'";
								$res=mysqli_query($connect,$sql);
								$n=1;
								while($result=mysqli_fetch_array($res)){ ?>								
									<tr>
										<th scope="row"><?php echo $n; ?></th>
										<td><?php echo $result['name'] ?></td>
										<td><?php echo $result['kurator'] ?></td>
										<td><?php if (!empty($result['spec1'])) {
											echo $result['spec1'] . " | " . $result['spec2'];
										} ?></td>
										<td data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo $result['id'] ?>">
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil " viewBox="0 0 16 16">
												<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
											</svg>
										</td>
									</tr>
									<div class="modal fade" id="staticBackdrop<?php echo $result['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="staticBackdropLabel">Редагувати</h5>
													<button type="button" class="btn-close text-center" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<form method="POST" action="settings_group.php">
														<div class="form-floating mb-3">
															<input type="hidden" name="id" value="<?php echo $result['id'] ?>">
															<input type="text" name="name" value="<?php echo $result['name'] ?>" class="form-control" id="floatingInput" placeholder="Назва групи">
															<label for="floatingInput">Назва групи</label>
														</div>
														<div class="mb-3">
															<label class="text-muted text-start" style="margin-bottom: 3px;">Відділення</label>
															<select class="form-select" style="height: 55px;" name="vid">
														  		<?php 
																	for ($j= 0; $j < count($array_department);$j++){
																		echo "<option value='".$array_department[$j][0]."' ";
																		if($array_department[$j][0]==$result['id_department']){
																			echo "selected";
																		}
																	  	echo ">".$array_department[$j][1]."</option>";
																	} 
																?>
															</select>
														</div>
														<div class="form-floating mb-3">
															<input type="text" name="kurator" value="<?php echo $result['kurator'] ?>" class="form-control" id="floatingInput" placeholder="Куратор">
															<label for="floatingInput">Куратор</label>
														</div>
														<hr>
														<div class="form-floating mb-3">
															<input type="text" name="spec1" value="<?php echo $result['spec1'] ?>"  class="form-control" id="floatingInput" placeholder="Спеціальність 1">
															<label for="floatingInput">Спеціальність 1</label>
														</div>
														<div class="form-floating mb-3">
															<input type="text" name="spec2" value="<?php echo $result['spec2'] ?>"  class="form-control" id="floatingInput" placeholder="Спеціальність 2">
															<label for="floatingInput">Спеціальність 2</label>
														</div>
														<br>
														<div class="form-floating mb-3 text-center">
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
								<?php $n++;} ?>
							<tr>
								<td colspan="5" style="text-align: center;">
									<button type="button"  data-bs-toggle="modal" data-bs-target="#staticBackdropNew<?php echo $array_department[$i][0];  ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"  fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
										</svg>
									</button>
									<div class="modal fade" id="staticBackdropNew<?php echo $array_department[$i][0];  ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="staticBackdropLabel">Додати групу</h5>
													<button type="button" class="btn-close text-center" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<form method="POST" action="settings_group.php">
														<div class="form-floating mb-3">
															<input type="text" name="name" class="form-control" id="floatingInput" placeholder="Назва групи">
															<label for="floatingInput">Назва групи</label>
														</div>
														<div class="mb-3">
															<label class="text-muted text-start" style="margin-bottom: 3px;">Відділення</label>
															<select class="form-select" style="height: 55px;" name="vid">
																<?php 
																	for ($q = 0; $q < count($array_department);$q++){
																		echo "<option value='".$array_department[$q][0]."' ";
																		if($array_department[$q][0]==$array_department[$i][0]){
																			echo "selected";
																		}
																  		echo ">".$array_department[$q][1]."</option>";
																	} 
																?>
															</select>
														</div>
														<div class="form-floating mb-3">
															<input type="text" name="kurator" class="form-control" id="floatingInput" placeholder="Куратор">
															<label for="floatingInput">Куратор</label>
														</div>
														<hr>
														<div class="form-floating mb-3">
															<input type="text" name="spec1" class="form-control" id="floatingInput" placeholder="Спеціальність 1">
															<label for="floatingInput">Спеціальність 1</label>
														</div>
														<div class="form-floating mb-3">
															<input type="text" name="spec2" class="form-control" id="floatingInput" placeholder="Спеціальність 2">
															<label for="floatingInput">Спеціальність 2</label>
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
					<br>
				<?php } ?>
			</div>
			<div class="col-sm-1"></div>
		</div>
    </body>
</html>
<?php
	if (isset($_POST['save'])) {
		//id, name, vid
		$sql = "UPDATE `groups` SET `name`='".$_POST['name']."', `id_department`='".$_POST['vid']."', `kurator`='".$_POST['kurator']."', `spec1`='".$_POST['spec1']."', `spec2`='".$_POST['spec2']."' WHERE `id`='".$_POST['id']."'";
		$res=mysqli_query($connect,$sql);
		?>
            <script>document.location.href="settings_group.php"</script>
        <?php
	}
	if (isset($_POST['delete'])) {
		//id, name, vid
		$sql = "DELETE FROM `groups` WHERE `id`='".$_POST['id']."'";
		$res=mysqli_query($connect,$sql);
		$sql = "DELETE FROM `subjects` WHERE `id_group`='".$_POST['id']."'";
		$res=mysqli_query($connect,$sql);
		?>
            <script>document.location.href="settings_group.php"</script>
        <?php
	}
	if (isset($_POST['new'])) {
		//name, vid
		$sql = "INSERT INTO `groups` (`name`, `id_department`, `kurator`, `spec1`, `spec2`) VALUES ('".$_POST['name']."','".$_POST['vid']."','".$_POST['kurator']."','".$_POST['spec1']."','".$_POST['spec2']."')";
		$res=mysqli_query($connect,$sql);
		$sql="SELECT `id` FROM `groups` ORDER BY `id` DESC";
		$res=mysqli_query($connect,$sql);
		$result=mysqli_fetch_array($res);
		$sql="INSERT INTO `subjects`(`name`, `id_teacher`, `id_group`, `teachers`) VALUES (' ','1','".$result['id']."','1')";
		$res=mysqli_query($connect,$sql);
		?>
            <script>document.location.href="settings_group.php"</script>
        <?php
	}
?>
