<?php
    require_once("connect_db.php");
	session_start();
	$sql="SELECT * FROM `department`";
	$res=mysqli_query($connect,$sql);
	$array_department=[];
	while($result=mysqli_fetch_array($res)){
		$array_department[] = $result;
	}
    $sql="SELECT * FROM `groups` ORDER BY `id_department` ASC ";
	$res=mysqli_query($connect,$sql);
	$array_groups=[];
	while ($result = mysqli_fetch_array($res)) {
		$array_groups[]=$result;
	}
	$sql="SELECT * FROM `teachers` WHERE `id`!='1' AND `id`!='2' ORDER BY `name` ASC";
	$res=mysqli_query($connect,$sql);
	$array_teachers=[];
	while ($result = mysqli_fetch_array($res)) {
		$array_teachers[]=$result;
	}
	
    $sql="SELECT * FROM `classroom`";
	$res=mysqli_query($connect,$sql);
	$array_cl=[];
	while($result=mysqli_fetch_array($res)){
		$array_cl[] = $result;
	}
    $array_day = [['Пн','Понеділок'], ['Вт','Вівторок'], ['Ср','Середа'], ['Чт','Четвер'], ['Пт','П`ятниця']];
	function clas($c1, $c2, $array_cl){
		if($c1!='0' and $c2!='0'){
			for($i = 0; $i < count($array_cl);$i++){
				if($c1==$array_cl[$i]['id']){
					$cl1=$array_cl[$i]['name'];
				}
			}
			for($i = 0; $i < count($array_cl);$i++){
				if($c2==$array_cl[$i]['id']){
					$cl2=$array_cl[$i]['name'];
				}
			}
			return $cl1.",".$cl2;
		} else if($c1=='0' and $c2!='0'){
			for($i = 0; $i < count($array_cl);$i++){
				if($c2==$array_cl[$i]['id']){
					$cl2=$array_cl[$i]['name'];
				}
			}
			return $cl2;
		} else if($c1!='0' and $c2=='0'){
			for($i = 0; $i < count($array_cl);$i++){
				if($c1==$array_cl[$i]['id']){
					$cl1=$array_cl[$i]['name'];
				}
			}
			return $cl1;
		} else {
			return " ";
		}
	}


	if(!empty($_POST['teacher'])){
		$sql="SELECT `id` FROM `subjects` WHERE `id_teacher`='".$_POST['teacher']."';";
		$res=mysqli_query($connect,$sql);
		$array_s_id=[];
		while ($result = mysqli_fetch_array($res)) {
			$array_s_id[]=$result[0];
		}
		$sql="SELECT `id` FROM `subjects` WHERE `teachers`='".$_POST['teacher']."';";
		$res=mysqli_query($connect,$sql);
		while ($result = mysqli_fetch_array($res)) {
			$array_s_id[]=$result[0];
		}
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Розклад</title>
        <link rel='stylesheet' type='text/css' href='main.css'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body>
        <div style="height: 15px;"></div>
        <div class="row"> <!---  шапка  --->
            <div class="dropdown col-sm-2">
			<form action="index.php" method="POST">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php 
						
					?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="index.php">Всі відділення</a></li>
					<?php 
						for($i=0; $i<count($array_department); $i++){
							echo "<li><a class='dropdown-item' href='index.php?idv=".$array_department[$i]['id']."'>".$array_department[$i]['name']."</a></li>";
						}
					?>
                </ul>
			</form>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-3">
			<form action="index.php" method="POST">
                <div class="row">
                    <div class="col">
                        <select name="group" class="form-control" placeholder="Група">
							<option value="qwerty">Обери групу</option>
							<?php 
								for($g=0; $g<count($array_groups); $g++){
									echo "<option value='".$array_groups[$g]['id']."'>".$array_groups[$g]['name']."</option>";
								}
							?>
						</select>
                    </div>
                    <div class="col">
                        <input type="submit" class="btn btn-outline-secondary" value="Показати">
                    </div>
                </div>
			</form>
            </div> 
            <div class="col-sm-4">
			<form action="index.php" method="POST">
                <div class="row">
                    <div class="col">
                        <select name="teacher" class="form-control" placeholder="Викладач">
						<option value="qwerty">Обери викладача</option>
							<?php 
								for($t=0;$t<count($array_teachers);$t++){
									echo "<option value='".$array_teachers[$t]['id']."'>".$array_teachers[$t]['name']."</option>";
								}
							?>
							
						</select>
                    </div>
                    <div class="col">
                        <input type="submit" name="select_teacher" class="btn btn-outline-secondary" value="Показати">
                    </div>
                </div>
			</form>
            </div> 
			<div class="dropdown col-sm-1">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                    Профіль
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
			<?php if(empty($_SESSION['id'])){ ?>
                    <li data-bs-toggle="modal" data-bs-target="#staticBackdrop0"><a class="dropdown-item">Увійти</a></li><?php } ?>
					<!---<hr>admin<hr>--->
					<?php if($_SESSION['id_department']=="1"){ ?>
					<li><a class="dropdown-item" href="index.php">Переглянути розклад</a></li>
                    <li><a class="dropdown-item" href="change.php">Редагувати розклад</a></li>
					<li><a class="dropdown-item" href="users.php">Користувачі</a></li>
					<li><a class="dropdown-item" href="settings_subj.php?v=2">Налаштування</a></li> 
					<li><a class="dropdown-item" href="exit.php">Вихід</a></li>
					<?php } ?>
					<?php if(!empty($_SESSION['id_department']) and $_SESSION['id_department']!="1"){ ?>
					<!---<hr>user<hr>--->	
					<li><a class="dropdown-item" href="index.php">Переглянути розклад</a></li>
                    <li><a class="dropdown-item" href="change.php?v=<?php echo $_SESSION['id_department']; ?>">Редагувати розклад</a></li>
					<li><a class="dropdown-item" href="settings_subj.php?v=<?php echo $_SESSION['id_department']; ?>">Налаштування</a></li> 
					<li><a class="dropdown-item" href="users_settings.php">Налаштуваня профілю</a></li>
					<li><a class="dropdown-item" href="exit.php">Вихід</a></li>
					<?php } ?>
                </ul>
            </div>
			<div class="col-sm-1" style="text-align: center;">
				<a href="print.php"><button class="btn btn-outline-secondary" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
						<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
						<path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
					</svg>
				</button></a>
			</div>			
			<!---  Форма входу   --->
			<?php if(empty($_SESSION['id'])){ ?>
			<div class="modal fade" id="staticBackdrop0" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="staticBackdropLabel">Вхід</h5>
							<button type="button" class="btn-close text-center" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form action="index.php" method="POST">
							<div class="form-floating mb-3">
								<input type="text" name="login" class="form-control" id="floatingInput" placeholder="Логін">
								<label for="floatingInput">Логін</label>
							</div>
							<div class="form-floating">
								<input type="password" name="pass" class="form-control" id="floatingPassword" placeholder="Пароль">
								<label for="floatingPassword">Пароль</label>
							</div>
							<br>
							<div class="form-floating text-center">
								<button type="submit" name="enter" class="btn btn-outline-secondary">Увійти</button>
							</div>
							</form>
						</div>
						<div class="modal-footer">
						</div>
					</div>
				</div>
			</div><?php } ?>
        </div>
        <div style="height: 8px;"></div>
		<div style="height: 2px; background-color: #ECECEC;"></div>
		<div style="height: 10px;"></div>
        <!---  розклад  --->
        <table class="table-bordered" style="font-family: Cambria; font-size: 14pt; width: <?php echo 69+count($array_groups)*180; ?>px;">
            <tr> 
                <td colspan="2" rowspan="3"></td>
                <?php for ($i = 0; $i < count($array_groups);$i++) { ?>
				<td colspan="4" style="font-size: 22pt; background-color: #C0C0C0;  text-align: center; font-weight: bold; width: 180px;"><?php echo $array_groups[$i]['name'] ?></td>
                <?php } ?>
            </tr>
            <tr>
                <?php for ($i = 0; $i < count($array_groups);$i++) { ?>
                <td colspan="4" style="font-style: italic; text-align: center; width: 180px;" ><?php echo $array_groups[$i]['kurator'] ?></td>
                <?php } ?>
            </tr>
            <tr>
                <?php for ($i = 0; $i < count($array_groups); $i++) {
                    if (!empty($result['spec1'])) { ?>
                        <td colspan="4" style="font-style: italic; text-align: center; width: 180px;" ><?php echo $array_groups[$i]['spec1'] ?></td>
                    <?php } else { ?>
                        <td colspan="2" class=" td-2" style="font-style: italic; text-align: center; width: 90px; height: 22px;"><?php echo $array_groups[$i]['spec1'] ?></td>
                        <td colspan="2" class=" td-2" style="font-style: italic; text-align: center; width: 90px; height: 22px;"><?php echo $array_groups[$i]['spec2'] ?></td>
                    <?php }
                } ?>
                
            </tr>
            <!---  день тиждня  				--->
            <?php 
			for ($i = 0; $i < count($array_day); $i++) {
				for ($j = 1; $j < 8; $j++) { 
					if(!empty($_POST['teacher'])){
						$sql="SELECT `groups`.`id` AS `group`,  `groups`.`id_department`,  `schedule`.* FROM  `schedule` INNER JOIN `subjects` ON `schedule`.`id_subject`=`subjects`.`id` INNER JOIN `groups` ON `subjects`.`id_group`=`groups`.`id` WHERE `schedule`.`day`='".$i."' AND `schedule`.`n_lesson`='".$j."' AND ( ";
						$sql=$sql."`schedule`.`id_subject`='".$array_s_id[0]."' ";
						for($idd=1;$idd<count($array_s_id);$idd++){
							$sql=$sql."OR `schedule`.`id_subject`='".$array_s_id[$idd]."' ";
						}
						$sql=$sql.") ORDER BY `groups`.`id_department` ASC;";
					} else {	
						$sql="SELECT `groups`.`id` AS `group`,  `groups`.`id_department`,  `schedule`.* FROM  `schedule` INNER JOIN `subjects` ON `schedule`.`id_subject`=`subjects`.`id` INNER JOIN `groups` ON `subjects`.`id_group`=`groups`.`id` WHERE `schedule`.`day`='".$i."' AND `schedule`.`n_lesson`='".$j."' ORDER BY `groups`.`id_department` ASC;";
					}
					$res=mysqli_query($connect,$sql);
					$array_1=[];
					while ($result = mysqli_fetch_array($res)) {
						$array_1[]=$result;
					}?>
			<tr>
				<?php if($j==1){ ?><td rowspan="28" style="font-size: 22pt; font-weight: bold; width: 44px; text-align: center;"><?php echo $array_day[$i][0]; ?></td><?php } ?>
				<td rowspan="4" style="font-weight: bold; width: 25px; text-align: center;"><?php echo $j; ?></td>
				<?php 
				for ($g = 0; $g < count($array_groups); $g++) {
					$n=0;
					for ($ar = 0; $ar < count($array_1); $ar++) {
						if($array_groups[$g]['id']==$array_1[$ar]['group']){
							if($array_1[$ar]['id_type_lesson']=='1'){
                                echo "<td colspan='4' style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td>";
                                $n=1;
                            } 
                            if($array_1[$ar]['id_type_lesson']=='2'){
                                echo "<td colspan='2' style='height: 23px; border-right: hidden; border-bottom: hidden; text-align: left; font-style: italic;'>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td><td colspan='2' style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'>".$array_1[$ar]['name_teacher']."</td>";
                                $n=1;
                            }
                            if($array_1[$ar]['id_type_lesson']=='3'){
                                echo ""; // -
                                $n=1;
                            }
                            if($array_1[$ar]['id_type_lesson']=='4'){
                                echo "<td colspan='2' style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td>";
                                $n=1;
                            }
                            if($array_1[$ar]['id_type_lesson']=='5'){
                                echo "<td colspan='2' style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td>";
                                $n=1;
                            }
                            if($array_1[$ar]['id_type_lesson']=='6'){
                                echo "<td style='height: 23px; border-right: hidden; border-bottom: hidden; text-align: left; font-style: italic; '>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td><td style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'>".$array_1[$ar]['name_teacher']."</td>";
                                $n=1;
                            }
                            if($array_1[$ar]['id_type_lesson']=='7'){
                                echo ""; // -
                                $n=1;
                            }
                            if($array_1[$ar]['id_type_lesson']=='8'){
                                echo "<td style='height: 23px; border-right: hidden; border-bottom: hidden; text-align: left; font-style: italic; '>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td><td style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'>".$array_1[$ar]['name_teacher']."</td>";
                                $n=1;
                            }
                            if($array_1[$ar]['id_type_lesson']=='9'){
                                echo ""; // -
                                $n=1;
                            }
						} 
					}
					if($n==0){
						?>
						<td colspan="4" rowspan="4" style="height: 94px; text-align: center; font-weight: bold; vertical-align: middle;"></td>
						<?php 
					}
				} ?>
			</tr>
			<tr>
				<?php 
					for ($g = 0; $g < count($array_groups); $g++) { 
						for ($ar = 0; $ar < count($array_1); $ar++) {
							if($array_groups[$g]['id']==$array_1[$ar]['group']){
								if($array_1[$ar]['id_type_lesson']=='1'){
                                    echo "<td colspan='4' rowspan='2' style='height: 48px; border-bottom: hidden; text-align: center; font-weight: bold; vertical-align: middle;'>".$array_1[$ar]['name_subj']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='2'){
                                    echo "<td colspan='4' style='height: 24px; text-align: center; font-weight: bold; vertical-align: middle;'>".$array_1[$ar]['name_subj']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='3'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='4'){
                                    echo "<td colspan='2' rowspan='2' style='height: 48px; border-bottom: hidden; text-align: center; font-weight: bold; vertical-align: middle;'>".$array_1[$ar]['name_subj']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='5'){
                                    echo "<td colspan='2' rowspan='2' style='height: 48px; border-bottom: hidden; text-align: center; font-weight: bold; vertical-align: middle;'>".$array_1[$ar]['name_subj']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='6'){
                                    echo "<td colspan='2' style='height: 24px; text-align: center; font-weight: bold; vertical-align: middle;'>".$array_1[$ar]['name_subj']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='7'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='8'){
                                    echo "<td colspan='2' style='height: 24px; text-align: center; font-weight: bold; vertical-align: middle;'>".$array_1[$ar]['name_subj']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='9'){
                                    echo ""; // -
                                }
							}
						}
					}
				?>
			</tr>
			<tr>
				<?php 
					for ($g = 0; $g < count($array_groups); $g++) { 
						for ($ar = 0; $ar < count($array_1); $ar++) {
							if($array_groups[$g]['id']==$array_1[$ar]['group']){
								if($array_1[$ar]['id_type_lesson']=='1'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='2'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='3'){
                                    echo "<td colspan='4' style='height: 24px; border-bottom: hidden; text-align: center; font-weight: bold; vertical-align: middle;'>".$array_1[$ar]['name_subj']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='4'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='5'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='6'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='7'){
                                    echo "<td colspan='2' style='height: 24px; border-bottom: hidden; text-align: center; font-weight: bold; vertical-align: middle;'>".$array_1[$ar]['name_subj']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='8'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='9'){
                                    echo "<td colspan='2' style='height: 24px; border-bottom: hidden; text-align: center; font-weight: bold; vertical-align: middle;'>".$array_1[$ar]['name_subj']."</td>";
                                }
							}
						}
					}
				?>
			</tr>
			<tr>
				<?php 
					for ($g = 0; $g < count($array_groups); $g++) { 
						for ($ar = 0; $ar < count($array_1); $ar++) {
							if($array_groups[$g]['id']==$array_1[$ar]['group']){
								if($array_1[$ar]['id_type_lesson']=='1'){
                                    echo "<td colspan='4' style='height: 23px; text-align: right; font-style: italic;'>".$array_1[$ar]['name_teacher']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='2'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='3'){
                                    echo "<td colspan='2' style='height: 23px; border-right: hidden; text-align: left; font-style: italic; '>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td><td colspan='2' style='height: 23px; text-align: right; font-style: italic;'>".$array_1[$ar]['name_teacher']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='4'){
                                    echo "<td colspan='2' style='height: 23px; text-align: right; font-style: italic; '>".$array_1[$ar]['name_teacher']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='5'){
                                    echo "<td colspan='2' style='height: 23px; text-align: right; font-style: italic; '>".$array_1[$ar]['name_teacher']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='6'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='7'){
                                    echo "<td style='height: 23px; border-right: hidden; text-align: left; font-style: italic; '>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td><td style='height: 23px; text-align: right; font-style: italic;'>".$array_1[$ar]['name_teacher']."</td>";
                                }
                                if($array_1[$ar]['id_type_lesson']=='8'){
                                    echo ""; // -
                                }
                                if($array_1[$ar]['id_type_lesson']=='9'){
                                    echo "<td style='height: 23px; border-right: hidden; text-align: left; font-style: italic; '>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td><td style='height: 23px; text-align: right; font-style: italic;'>".$array_1[$ar]['name_teacher']."</td>";
                                }
							}
						}
					}
				?>
			</tr>
            <?php }  } 
		if(isset($_POST['enter'])){
			$sql="SELECT * FROM users WHERE login='".$_POST['login']."'";
                        $res=mysqli_query($connect,$sql);
                        $result=mysqli_fetch_array($res);
                        if(empty($result)){
                            echo "<div class='col-sm-3 alert alert-warning alert-dismissible fade show' role='alert'><strong>Помилка!<br></strong> Невірний логін або пароль<button type='button' class='close' data-dismiss='alert' aria-label='Закрити'><span aria-hidden='true'>&times;</span></button></div>";
                        } else {
							if(password_verify($_POST['pass'], $result['password'])){
                            $_SESSION['id']=$result['id'];
							$_SESSION['id_department']=$result['id_department'];}
				?>
            <script>document.location.href="index.php"</script>
        <?php
			}
		}
	?>
        </table>
    </body>
</html>
