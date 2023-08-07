<?php
    require_once("connect_db.php");
	session_start();
	if(empty($_SESSION['id'])){
		header('Location: index.php');	
	}
	if($_SESSION['id_department']=='1'){
		$sql="SELECT * FROM `department` WHERE `id`!='1'";
	} else {
		$sql="SELECT * FROM `department` WHERE `id`='".$_SESSION['id_department']."' AND `id`!='1'";
	}
	$res=mysqli_query($connect,$sql);
	$array_department=[];
	while($result=mysqli_fetch_array($res)){
		$array_department[] = $result;
	}
	$sql="SELECT * FROM `teachers` ORDER BY `name` ASC";
	$res=mysqli_query($connect,$sql);
	$array_teachers=[];
	while($result=mysqli_fetch_array($res)){
		$array_teachers[] = $result;
	}
	$sql="SELECT * FROM `teachers`";
	$res=mysqli_query($connect,$sql);
	$array_teachers_f=[];
	while($result=mysqli_fetch_array($res)){
		$array_teachers_f[] = $result;
	}
	$sql="SELECT * FROM `groups` WHERE `id_department`='".$_GET['v']."'";
	$res=mysqli_query($connect,$sql);
	$array_groups=[];
	while ($result = mysqli_fetch_array($res)) {
		$array_groups[]=$result;
	}	

	function pib($name1, $name2, $ar) {
		$pib = "";
		if ($name1 != '1' and $name1 != '2') {
			for($ii = 0; $ii < count($ar);$ii++){
				if($ar[$ii]['id']==$name1){
					$str = $ar[$ii]['name'];
				}
			}
			$ar_str=explode(" ", $str);
			$pib = $ar_str[0]." ".substr($ar_str[1],0,2).". ".substr($ar_str[2],0,2).".";
		}
		if ($name1 == '1') {
			$pib = " ";
		}
		if ($name1 == '2') {
			$pib = $ar[1]['name'];
		}
		if ($name2 != '1' and $name2 != '2') {
			for($iii = 0; $iii < count($ar);$iii++){
				if($ar[$iii]['id']==$name2){
					$str = $ar[$iii]['name'];
				}
			}
			$ar_str=explode(" ", $str);
			$pib = $pib.", ".$ar_str[0]." ".substr($ar_str[1],0,2).". ".substr($ar_str[2],0,2).".";
		}
		if ($name2 == '1') {
			$pib = $pib."";
		}
		if ($name2 == '2') {
			$pib = $pib.$ar[1]['name'];
		}
		return $pib;
	} 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Предмети</title>
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
				<?php if($_SESSION['id_department']=='1'){ ?>
				<a href="settings_teacher.php"><button class="btn btn-outline-secondary col-sm-12" type="button" aria-expanded="false">
                    Викладачі
                </button></a><?php } ?>
			</div>
			<div class="col-sm-2">
				<?php if($_SESSION['id_department']=='1'){ ?>
				<a href="settings_group.php"><button class="btn btn-outline-secondary col-sm-12" type="button" aria-expanded="false">
                    Групи
                </button></a> <?php } ?>
			</div>
			<div class="col-sm-2">
				<?php if($_SESSION['id_department']=='1'){?>
				<a href="settings_vid.php"><button class="btn btn-outline-secondary col-sm-12" type="button" aria-expanded="false">
                    Відділення
                </button></a><?php } ?>
			</div>
			<div class="col-sm-1"></div>
			<div class="dropdown col-sm-1">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                    Профіль
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
					<li><a class="dropdown-item" href="index.php">Переглянути розклад</a></li>
                    <li><a class="dropdown-item" href="change.php<?php if($_SESSION['id_department']!='1'){echo "?v=".$_SESSION['id_department'];} ?>">Редагувати розклад</a></li>
					<li><a class="dropdown-item" href="settings_subj.php?v=<?php if($_SESSION['id_department']=='1'){echo '2';} else {echo $_SESSION['id_department'];}?>">Налаштування</a></li> 
					<?php if($_SESSION['id_department']=='1'){?><li><a class="dropdown-item" href="users.php">Користувачі</a></li><?php }
					else {?> <li><a class="dropdown-item" href="users_settings.php">Налаштуваня профілю</a></li> <?php }?>
					<li><a class="dropdown-item" href="exit.php">Вихід</a></li>
                </ul>
            </div>	
        </div>
        <div style="height: 8px;"></div>
		<div style="height: 2px; background-color: #ECECEC;"></div>
		<div style="height: 10px;"></div>
		<div class="row">
			<div class="col-sm-1"></div>
			<div class="col-sm-1">
				<ul class="nav flex-column" style="margin-top: 15%; margin-right: 7%; margin-left: 20%;">
					<?php  
						for ($i = 0; $i < count($array_groups);$i++) {
							echo "<li class='nav-item'><a class='nav-link' href='#g".$array_groups[$i]['id']."' style='color: #7e868d; margin-bottom: 5%;'>".$array_groups[$i]['name']."</a></li>";
						}
					?>
				</ul>
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-7">
				<?php for ($i = 0; $i < count($array_groups);$i++) {?>
					<table class="table">
						<thead>
							<tr><th colspan="5" id="g<?php echo $array_groups[$i]['id']; ?>"><?php echo $array_groups[$i]['name']; ?></th></tr>
							<tr>
								<th scope="col">№</th>
								<th scope="col">Назва</th>
								<th scope="col">Викладач</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							<?php 
								$sql="SELECT * FROM `subjects` WHERE `id_group`='".$array_groups[$i]['id']."' AND `name`!=' ' ORDER BY `name` ASC;";
								$res=mysqli_query($connect,$sql);
								$j = 1;
								while ($result = mysqli_fetch_array($res)) {?>
									<tr>
										<th scope="row"><?php echo $j; ?></th>
										<td><?php echo $result['name']; ?> </td>
										<td><?php echo pib($result['id_teacher'], $result['teachers'], $array_teachers_f); ?></td>
										<td data-bs-toggle="modal" data-bs-target="#staticBackdrop<?php echo $result['id']; ?>">
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil " viewBox="0 0 16 16">
												<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
											</svg>
										</td>
									</tr>
									<div class="modal fade" id="staticBackdrop<?php echo $result['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="staticBackdropLabel">Редагувати</h5>
													<button type="button" class="btn-close text-center" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<form method="POST" action="settings_subj.php?v=<?php echo $_GET['v']; ?>">
														<div class="form-floating mb-3">
															<input type="hidden" name="id" value="<?php echo $result['id'] ?>">
															<input type="hidden" name="old_name" value="<?php echo $result['name'] ?>">
															<input type="hidden" name="old_t1" value="<?php echo $result['id_teacher'] ?>">
															<input type="hidden" name="old_t2" value="<?php echo $result['teachers'] ?>">
															<input type="text" name="name" value="<?php echo $result['name'] ?>" class="form-control" id="floatingInput" placeholder="Назва">
															<label for="floatingInput">Назва предмету</label>
														</div>
														<div class="mb-3" >
															<label class="text-muted text-start" style="margin-bottom: 3px;">Викладач</label>
															<select name="teach[]" multiple class="form-select" style="height: 20%;">
																<?php
																for ($z = 0; $z < count($array_teachers); $z++) {
																	echo "<option value='" . $array_teachers[$z]['id'] . "' ";
																	if ($array_teachers[$z]['id'] == $result['id_teacher'] and $result['id_teacher']!='1') {
																		echo "selected";
																	}
																	if ($array_teachers[$z]['id'] == $result['teachers'] and $result['teachers']!='1') {
																		echo "selected";
																	}
																	echo ">" . $array_teachers[$z]['name'] . "</option>";
																}
																?>
															</select>
															<p>*Для вибору декількох викладачів зажміть Ctrl</p>
														</div>
														<div class="mb-3">
															<label class="text-muted text-start" style="margin-bottom: 3px;">Група</label>
															<select name="group" style="height: 55px;" class="form-select">
																<?php
																for ($y = 0; $y < count($array_groups); $y++) {
																	echo "<option value='" . $array_groups[$y]['id'] . "' ";
																	if ($array_groups[$y]['id'] == $result['id_group']) {
																		echo "selected";
																	}
																	echo ">" . $array_groups[$y]['name'] . "</option>";
																}
																?>
															</select>
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
								<?php $j++; } ?>
							<tr>
								<td colspan="4" style="text-align: center;">
									<button type="button"  data-bs-toggle="modal" data-bs-target="#staticBackdropNew<?php echo $array_groups[$i]['id']; ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"  fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
											<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
											<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
										</svg>
									</button>
									<div class="modal fade" id="staticBackdropNew<?php echo $array_groups[$i]['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="staticBackdropLabel">Додати предмет</h5>
													<button type="button" class="btn-close text-center" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body">
													<form method="POST" action="settings_subj.php?v=<?php echo $_GET['v']; ?>">
														<div class="form-floating mb-3">
															<input type="text" name="name" class="form-control" id="floatingInput" placeholder="Назва">
															<label for="floatingInput">Назва предмету</label>
														</div>
														<div class="mb-3" >
															<label class="text-muted text-start" style="margin-bottom: 3px;">Викладач</label>
															<select name="teach[]" multiple class="form-select" style="height: 20%;">
																<?php
																for ($z = 0; $z < count($array_teachers); $z++) {
																	echo "<option value='" . $array_teachers[$z]['id'] . "'>" . $array_teachers[$z]['name'] . "</option>";
																}
																?>
															</select>
															<p>*Для вибору декількох викладачів зажміть Ctrl</p>
														</div>
														<div class="mb-3">
															<label class="text-muted text-start" style="margin-bottom: 3px;">Група</label>
															<select name="group" style="height: 55px;" class="form-select">
																<?php
																for ($y = 0; $y < count($array_groups); $y++) {
																	echo "<option value='" . $array_groups[$y]['id'] . "' ";
																	if ($array_groups[$y]['id'] == $array_groups[$i][0]) {
																		echo "selected";
																	}
																	echo ">" . $array_groups[$y]['name'] . "</option>";
																}
																?>
															</select>
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
				<?php } ?>
			</div>
			<div class="col-sm-1"></div>
		</div>
    </body>
</html>
<?php
	if (isset($_POST['save'])) {
		//group, teach, name, id, old_t1, old_t2, old_name
		if (count($_POST['teach'])==2){
			$sql = "UPDATE `subjects` SET `name`='" . $_POST['name'] . "', `id_teacher`='" . $_POST['teach'][0] . "', `id_group`='" . $_POST['group'] . "', `teachers`='" . $_POST['teach'][1] . "' WHERE `id`='" . $_POST['id'] . "'";
		} else {
			$sql = "UPDATE `subjects` SET `name`='" . $_POST['name'] . "', `id_teacher`='" . $_POST['teach'][0] . "', `id_group`='" . $_POST['group'] . "', `teachers`='1' WHERE `id`='" . $_POST['id'] . "'";
			$sql11 = "UPDATE `schedule` SET `name_teacher`='" . pib($_POST['teach'][0], '1', $array_teachers_f) . "' WHERE `id_subject`='" . $_POST['id'] . "'";
			$res11=mysqli_query($connect,$sql11);	
		}
		$res=mysqli_query($connect,$sql);
		if($_POST['old_name']!=$_POST['name']){
			$sql = "UPDATE `schedule` SET `name_subj`='" . $_POST['name'] . "' WHERE `id_subject`='" . $_POST['id'] . "'";
			$res=mysqli_query($connect,$sql);
		}
		if (count($_POST['teach'])==1){
			$_POST['teach'][]='1';
		}
		if($_POST['old_t1']!=$_POST['teach'][0] and $_POST['old_t2']!=$_POST['teach'][1]){
			$sql = "UPDATE `schedule` SET `name_teacher`='" . pib($_POST['teach'][0], $_POST['teach'][1], $array_teachers_f) . "' WHERE `id_subject`='" . $_POST['id'] . "'";
			$res=mysqli_query($connect,$sql);
			echo "1 and 2";
			echo $_POST['teach'][0]."----".$_POST['teach'][1];
		} else if($_POST['old_t1']!=$_POST['teach'][0]){
			$sql = "UPDATE `schedule` SET `name_teacher`='" . pib($_POST['teach'][0], $_POST['old_t2'], $array_teachers_f) . "' WHERE `id_subject`='" . $_POST['id'] . "'";
			$res=mysqli_query($connect,$sql);
			echo "1";
		} else if($_POST['old_t2']!=$_POST['teach'][1]){
			$sql = "UPDATE `schedule` SET `name_teacher`='" . pib($_POST['old_t1'], $_POST['teach'][1], $array_teachers_f) . "' WHERE `id_subject`='" . $_POST['id'] . "'";
			$res=mysqli_query($connect,$sql);
			echo "2";
		}		
		?>
            <script>document.location.href="settings_subj.php?v=<?php echo $_GET['v']; ?>"</script>
        <?php
	}
	if (isset($_POST['delete'])) {
		//group, teach, name, id
		$sql = "DELETE FROM `subjects` WHERE `id`='".$_POST['id']."'";
		$res=mysqli_query($connect,$sql);
		$sql="SELECT `id` FROM `subjects` WHERE `id_group`='".$_POST['group']."' AND `name`=' ';";
		$res=mysqli_query($connect,$sql);
		$result = mysqli_fetch_array($res);
		$sql = "UPDATE `schedule` SET `name_teacher`=' ', `name_subj`=' ', `id_classroom`='0', `id_classroom1`='0', `id_subject`='".$result['id']."' WHERE `id_subject`='" . $_POST['id'] . "'";
			$res=mysqli_query($connect,$sql);
		?>
            <script>document.location.href="settings_subj.php?v=<?php echo $_GET['v']; ?>"</script>
        <?php
	}
	if (isset($_POST['new'])) {
		//group, teach, name
		if($_POST['teach']){
			if (count($_POST['teach'])==2){
				$sql = "INSERT INTO `subjects`(`name`, `id_teacher`, `id_group`, `teachers`) VALUES ('".$_POST['name']."','".$_POST['teach'][0]."','".$_POST['group']."','".$_POST['teach'][1]."');";
			} else {
				$sql = "INSERT INTO `subjects`(`name`, `id_teacher`, `id_group`, `teachers`) VALUES ('".$_POST['name']."','".$_POST['teach'][0]."','".$_POST['group']."','1');";
			}
		} else {
			$sql = "INSERT INTO `subjects`(`name`, `id_teacher`, `id_group`, `teachers`) VALUES ('".$_POST['name']."','1','".$_POST['group']."','1');";
		}
		$res=mysqli_query($connect,$sql);
		?>
            <script>document.location.href="settings_subj.php?v=<?php echo $_GET['v']; ?>"</script>
        <?php
	}
?>
