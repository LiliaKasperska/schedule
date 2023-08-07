<?php
    require_once("connect_db.php");
	session_start();
	if($_SESSION['id_department']!=1 or empty($_SESSION['id'])){
		header('Location: index.php');	
	}
	$sql="SELECT * FROM department WHERE `id`!='1'";
	$res=mysqli_query($connect,$sql);
	$array_department=[];
	while($result=mysqli_fetch_array($res)){
		$array_department[] = $result;
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Відділеня</title>
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
				<a href="settings_group.php"><button class="btn btn-outline-secondary col-sm-12" type="button" aria-expanded="false">
                    Групи
                </button></a>
			</div>
			<div class="col-sm-2">
				<a href="settings_vid.php"><button class="btn btn-outline-secondary col-sm-12 active" type="button" aria-expanded="false">
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
			<div class="col-sm-3"></div>
			<div class="col-sm-6">
				<table class="table">
					<thead>
						<tr>
							<th scope="col">№</th>
							<th scope="col">Назва відділення</th>
							<th scope="col"></th>
						</tr>
					</thead>
					<tbody>
						<?php for ($i = 0; $i < count($array_department);$i++){ ?>
							<tr>
								<th scope="row"><?php echo $i+1; ?></th>
								<td><?php echo $array_department[$i][1];?></td>
								<td data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo $array_department[$i][0];?>">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil " viewBox="0 0 16 16">
										<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
									</svg>
								</td>
							</tr>
							<div class="modal fade" id="staticBackdrop<?php echo $array_department[$i][0];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="staticBackdropLabel">Редагувати</h5>
											<button type="button" class="btn-close text-center" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body">
											<form method="POST" action="settings_vid.php">
												<div class="form-floating mb-3">
													<input type="hidden" value="<?php echo $array_department[$i][0];?>" name="id">
													<input type="text" name="name" class="form-control" id="floatingInput" placeholder="Назва відділення" value="<?php echo $array_department[$i][1];?>">
													<label for="floatingInput">Назва відділення</label>
												</div>
												<br>
												<div class="form-floating text-center">
													<button type="submit" class="btn btn-outline-secondary" name="save" style="margin-right: 40%;">Зберегти</button>
													<button type="submit" class="btn btn-outline-secondary" name="delete">Видалити</button>
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
							<td colspan="3" style="text-align: center;">
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
												<h5 class="modal-title" id="staticBackdropLabel">Додати відділення</h5>
												<button type="button" class="btn-close text-center" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<form method="POST" action="settings_vid.php">
													<div class="form-floating mb-3">
														<input type="text" class="form-control" name="name_new" id="floatingInput" placeholder="Назва відділення">
														<label for="floatingInput">Назва відділення</label>
													</div>
													<br>
													<div class="form-floating text-center">
														<button type="submit" class="btn btn-outline-secondary" name="add">Зберегти</button>
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
	if(isset($_POST['add'])){
		//name_new
		$sql = "INSERT INTO department (name) VALUES ('".$_POST['name_new']."')";
		$res=mysqli_query($connect,$sql);
		?>
            <script>document.location.href="settings_vid.php"</script>
        <?php
	}
	if(isset($_POST['save'])){
		//id, name
		$sql = "UPDATE department SET name='".$_POST['name']."' WHERE id='".$_POST['id']."'";
		$res=mysqli_query($connect,$sql);
		?>
            <script>document.location.href="settings_vid.php"</script>
        <?php
	}
	if(isset($_POST['delete'])){
		//id, name
		$sql = "DELETE FROM department WHERE id='".$_POST['id']."'";
		$res=mysqli_query($connect,$sql);
		?>
            <script>document.location.href="settings_vid.php"</script>
        <?php
	}
?>
