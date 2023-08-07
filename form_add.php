<?php
	//include_once("function.php");
    include_once("connect_db.php");
	session_start();
	if(empty($_SESSION['id'])){
		header('Location: index.php');	
	}
    $sql="SELECT * FROM `department`";
	$res=mysqli_query($connect,$sql);
	$array_department=[];
	while($result=mysqli_fetch_array($res)){
		$array_department[] = $result;
	}
	$i=$_GET['i'];
	$j=$_GET['j'];
	$g=$_GET['g'];
	$v=$_GET['v'];
	$sql="SELECT * FROM `groups` WHERE `id`='".$g."'";
	$res=mysqli_query($connect,$sql);
	$array_groups=mysqli_fetch_array($res);
    $sql="SELECT * FROM `teachers`";
	$res=mysqli_query($connect,$sql);
	$array_teachers_f=[];
	while($result=mysqli_fetch_array($res)){
		$array_teachers_f[] = $result;
	}
	$sql01="SELECT `schedule`.`id`, `schedule`.`id_classroom` FROM `schedule` WHERE `schedule`.`day`='".$i."' AND `schedule`.`id_classroom`!='0' AND `schedule`.`n_lesson`='".$j."' AND `schedule`.`zvedena`='0' UNION SELECT `schedule`.`id`, `schedule`.`id_classroom1` FROM `schedule` WHERE `schedule`.`day`='".$i."' AND `schedule`.`id_classroom1`!='0' AND `schedule`.`n_lesson`='".$j."' AND `schedule`.`zvedena`='0';";
	$res01=mysqli_query($connect,$sql01);
	$array_class=[];
	while ($result01 = mysqli_fetch_array($res01)) {
	    $array_class[]=$result01['id_classroom'];
	}
    $sql = "SELECT `classroom`.`id`, `classroom`.`name`, `building`.`name` AS `nameK` FROM `classroom` INNER JOIN `building` ON `classroom`.`id_building`=`building`.`id`"; 
	if(!empty($array_class)){
		$sql=$sql." WHERE `classroom`.`id`!='".$array_class[0]."' ";
		for($q=1;$q<count($array_class);$q++){
			$sql=$sql."AND `classroom`.`id`!='".$array_class[$q]."' ";
		}
	}
	$sql=$sql." ORDER BY `building`.`name`, `classroom`.`name` ASC";
    $res=mysqli_query($connect,$sql);
	$array_classroom=[];
	while($result=mysqli_fetch_array($res)){
		$array_classroom[] = $result;
	}
    $array_day = [['Пн','Понеділок'], ['Вт','Вівторок'], ['Ср','Середа'], ['Чт','Четвер'], ['Пт','П`ятниця']];
	
	//відображення прізвищ в розкладі
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
	// відображення кнопок на формі редагування
	function buttons(){
		echo '<button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalToggle1" aria-expanded="false"><img src="img/1.png"></button><button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalToggle2" aria-expanded="false"><img src="img/2.png"></button><button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalToggle21" aria-expanded="false"><img src="img/2_1.png"></button><button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalToggle3" aria-expanded="false"><img src="img/3.png"></button><button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalToggle31" aria-expanded="false"><img src="img/3_1.png"></button><button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalToggle4" aria-expanded="false"><img src="img/4.png"></button><button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalToggle5" aria-expanded="false"><img src="img/5.png"></button><button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalToggle51" aria-expanded="false"><img src="img/5_1.png"></button>';
	}
	
	//випадний список + скрипт + поля для виводу
	function formElement($connect, $name, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j){
		echo '<div class="row"><div class="col-sm-11"><select name="subj'.$name.'" id="subj'.$name.'" onchange="updateInputValue'.$name.'()" class="form-select">';
		$sql123="SELECT * FROM `subjects` WHERE `name`=' ' AND `id_group`='".$array_groups['id']."';";
		$res123=mysqli_query($connect,$sql123);
		$result123 = mysqli_fetch_array($res123);
		echo "<option value='".$result123['id']."'>-</option>";
		$sqlq="SELECT `schedule`.`id`, `subjects`.`id_teacher` FROM `schedule` INNER JOIN `subjects` ON `schedule`.`id_subject`=`subjects`.`id` WHERE `schedule`.`day`='".$i."' AND `schedule`.`n_lesson`='".$j."' AND `subjects`.`id_teacher`!='1' AND `subjects`.`name`!=' ' AND `schedule`.`zvedena`='0' UNION SELECT `schedule`.`id`, `subjects`.`teachers` FROM `schedule` INNER JOIN `subjects` ON `schedule`.`id_subject`=`subjects`.`id` WHERE `schedule`.`day`='".$i."' AND `schedule`.`n_lesson`='".$j."' AND `subjects`.`teachers`!='1' AND `subjects`.`name`!=' ' ";
		$resq=mysqli_query($connect,$sqlq);
		$teach="";
	    while ($resultq = mysqli_fetch_array($resq)) {
			$teach=$teach." AND `id_teacher`!='".$resultq['id_teacher']."' AND `teachers`!='".$resultq['id_teacher']."' ";
	    }
		$sql="SELECT * FROM `subjects` WHERE `name`!=' ' AND `id_group`='".$array_groups['id']."'".$teach." ORDER BY `name` ASC";
		$res=mysqli_query($connect,$sql);
		while ($result = mysqli_fetch_array($res)) {
			echo "<option value='".$result['id']."'>".$result['name']." (".pib($result['id_teacher'], $result['teachers'], $array_teachers_f).")"."</option>";
		}
		$sql2="SELECT `id_department` FROM `groups` WHERE `id`='".$g."'";
		$res2=mysqli_query($connect,$sql2);
		$v = mysqli_fetch_array($res2);
		echo '</select></div><div class="col-sm-1"><a href="http://r.gi.edu.ua/settings_subj.php?v='.$v[0].'#g'.$g.'" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"  fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></a></div></div><br>';
		echo '<script>function updateInputValue'.$name.'() { var select = document.getElementById("subj'.$name.'"); var input = document.getElementById("name_subj'.$name.'"); var input1 = document.getElementById("name_teach'.$name.'"); switch(select.value){'; 
		$sql="SELECT * FROM `subjects` WHERE `name`!=' ' AND `id_group`='".$array_groups['id']."'";//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		$res=mysqli_query($connect,$sql);
		while ($result = mysqli_fetch_array($res)) {
			echo " case '".$result['id']."': input.value='".$result['name']."'; input1.value='".pib($result['id_teacher'], $result['teachers'], $array_teachers_f)."'; break;";
		}
		echo 'default: input.value=" "; input1.value=" "; break; } }</script>';
		echo "<label class='form-label'>Відображається в розкладі:</label><input name='name_subj".$name."' id='name_subj".$name."' value='' type='text' class='form-control'><br><input name='name_teach".$name."' id='name_teach".$name."' value='' type='text'  class='form-control'><br>";
		echo '<select name="room'.$name.'[]" multiple class="form-select" style="height: 20%;">';
		for ($z = 0; $z < count($array_classroom); $z++) {
			if($array_classroom[$z]['id']=='0'){
				echo "<option selected value='" . $array_classroom[$z]['id'] . "'>" . $array_classroom[$z]['name'] . "</option>";
			} else {
				echo "<option value='" . $array_classroom[$z]['id'] . "'>" . $array_classroom[$z]['name'] . " (".$array_classroom[$z]['nameK'].")"."</option>";
			}
		}
		echo '</select><p>*Для вибору декількох кабінетів зажміть Ctrl</p><input  name="zvedena'.$name.'" type="checkbox" value="1">Зведена пара<br><br>';
	}
	 
	//save ($connect, $_POST['id_gr'], $_POST['subj0'], $array_teachers_f, $_POST['room1'], $_POST['lesson'], $type_lesson, $_POST['day'], $_POST['zvedena'])
	function newLesson($connect, $id_gr, $subj, $array_teachers_f, $room, $lesson, $type_lesson, $day, $zvedena, $name_subj, $name_teacher){
		if($zvedena=='1'){
	    	$zv='1';
	    } else {
	    	$zv='0';
	    }
		if (count($room)==2){
			$sql = "INSERT INTO `schedule` (`id_subject`, `id_classroom`, `id_classroom1`, `n_lesson`, `id_type_lesson`, `day`, `zvedena`, `name_subj`, `name_teacher`) VALUES ('".$subj."','".$room[0]."','".$room[1]."','".$lesson."','".$type_lesson."','".$day."','".$zv."','".$name_subj."','".$name_teacher."')";
		} else {
			$sql = "INSERT INTO `schedule` (`id_subject`, `id_classroom`, `id_classroom1`, `n_lesson`, `id_type_lesson`, `day`, `zvedena`, `name_subj`, `name_teacher`) VALUES ('".$subj."','".$room[0]."','0','".$lesson."','".$type_lesson."','".$day."','".$zv."','".$name_subj."','".$name_teacher."')";
		}
		$res=mysqli_query($connect,$sql);
	}
?>
<!DOCTYPE html>
<html>
<script language="JavaScript">
function closeIt() { document.location.href="change.php<?php if($v){echo "?v=".$v;} ?>"; }
</script>
    <head>
        <meta charset='utf-8'>
        <title>Створити пару</title>
        <link rel='stylesheet' type='text/css' href='main.css'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body style="background-color: gray;">
        <div class="modal1 fade1" id="exampleModalToggleNew" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title fs-5" id="exampleModalToggleLabel"><?php echo $array_day[$i][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
					</div>
					<div class="modal-body">
						<?php buttons(); ?>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="exampleModalToggle1" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$i][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
					</div>
					<div class="modal-body">
						<?php buttons(); ?>
						<br><br>
						<form action="form_add.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $i; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<input name="id_gr" type="hidden" value="<?php echo $array_groups['id']; ?>">
							<?php formElement($connect, 10, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<input type="submit" class="btn btn-outline-secondary" name="new1" value="Зберегти">
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$i][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
					</div>
					<div class="modal-body">
						<?php buttons(); ?>
						<br><br>
						<form action="form_add.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $i; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<input name="id_gr" type="hidden" value="<?php echo $array_groups['id']; ?>">
							<h5>Верхи</h5>
							<?php formElement($connect, 20, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<h5>Низи</h5>
							<?php formElement($connect, 21, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<input type="submit" class="btn btn-outline-secondary" name="new2" value="Зберегти" onClick="closeIt()">
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="exampleModalToggle21" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$i][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
					</div>
					<div class="modal-body">
						<?php buttons(); ?>
						<br><br>
						<form action="form_add.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $i; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<input name="id_gr" type="hidden" value="<?php echo $array_groups['id']; ?>">
							<h5>Спеціальність 1</h5>
							<?php formElement($connect, 210, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<h5>Спеціальність 2</h5>
							<?php formElement($connect, 211, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
                            <input type="submit" class="btn btn-outline-secondary" name="new21" value="Зберегти">
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModalToggle3" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$i][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
                    </div>
                    <div class="modal-body">
                        <?php buttons(); ?>
                        <br><br>
                        <form action="form_add.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
                            <input name="day" type="hidden" value="<?php echo $i; ?>">
                            <input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<input name="id_gr" type="hidden" value="<?php echo $array_groups['id']; ?>">
                            <h5>Спеціальність 1</h5>
                            <h5>Верхи</h5>
                            <?php formElement($connect, 30, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
			    			<h5>Низи</h5>
                            <?php formElement($connect, 31, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
			    			<h5>Спеціальність 2</h5>
                            <?php formElement($connect, 32, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
                            <input type="submit" class="btn btn-outline-secondary" name="new3" value="Зберегти">
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModalToggle31" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$i][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
                    </div>
                    <div class="modal-body">
                        <?php buttons(); ?>
                        <br><br>
                        <form action="form_add.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
                            <input name="day" type="hidden" value="<?php echo $i; ?>">
                            <input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<input name="id_gr" type="hidden" value="<?php echo $array_groups['id']; ?>">
                            <h5>Спеціальність 1</h5>
                            <?php formElement($connect, 310, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
			    			<h5>Спеціальність 2</h5>
			    			<h5>Верхи</h5>
                            <?php formElement($connect, 311, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
			    			<h5>Низи</h5>
                            <?php formElement($connect, 312, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
                            <input type="submit" class="btn btn-outline-secondary" name="new31" value="Зберегти">
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModalToggle4" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$i][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
                    </div>
                    <div class="modal-body">
                        <?php buttons(); ?>
						<br><br>
						<form action="form_add.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $i; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<input name="id_gr" type="hidden" value="<?php echo $array_groups['id']; ?>">
							<h5>Спеціальність 1</h5>
							<h5>Верхи</h5>
							<?php formElement($connect, 40, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<h5>Низи</h5>
							<?php formElement($connect, 41, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<h5>Спеціальність 2</h5>
							<h5>Верхи</h5>
							<?php formElement($connect, 42, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<h5>Низи</h5>
							<?php formElement($connect, 43, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<input type="submit" class="btn btn-outline-secondary" name="new4" value="Зберегти">
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="exampleModalToggle5" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$i][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
					</div>
					<div class="modal-body">
						<?php buttons(); ?>
						<br><br>
						<form action="form_add.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $i; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<input name="id_gr" type="hidden" value="<?php echo $array_groups['id']; ?>">
							<h5>Верхи</h5>
							<?php formElement($connect, 50, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<h5>Спеціальність 1. Низи</h5>
							<?php formElement($connect, 51, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<h5>Спеціальність 2. Низи</h5>
							<?php formElement($connect, 52, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
							<input type="submit" class="btn btn-outline-secondary" name="new5" value="Зберегти">
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
        <div class="modal fade" id="exampleModalToggle51" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$i][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
                    </div>
                    <div class="modal-body">
                        <?php buttons(); ?>
                        <br><br>
                        <form action="form_add.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
                            <input name="day" type="hidden" value="<?php echo $i; ?>">
                            <input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<input name="id_gr" type="hidden" value="<?php echo $array_groups['id']; ?>">
                            <h5>Спеціальність 1. Верхи</h5>
                            <?php formElement($connect, 510, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
                            <h5>Спеціальність 2. Верхи</h5>
                            <?php formElement($connect, 511, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
                            <h5>Низи</h5>
                            <?php formElement($connect, 512, $array_groups, $array_teachers_f, $array_classroom, $g, $v, $i, $j); ?><br> 
                            <input type="submit" class="btn btn-outline-secondary" name="new51" value="Зберегти">
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
    </body> 
</html>
<?php
    if(isset($_POST['new1'])){
        //subj, room, day, lesson, zvedena
		newLesson($connect, $_POST['id_gr'], $_POST['subj10'], $array_teachers_f, $_POST['room10'], $_POST['lesson'], '1', $_POST['day'], $_POST['zvedena10'], $_POST['name_subj10'], $_POST['name_teach10']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['new2'])){
        //subj, room, subj1, room1, day, lesson, zvedena, zvedena1
		newLesson($connect, $_POST['id_gr'], $_POST['subj20'], $array_teachers_f, $_POST['room20'], $_POST['lesson'], '2', $_POST['day'], $_POST['zvedena20'], $_POST['name_subj20'], $_POST['name_teach20']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj21'], $array_teachers_f, $_POST['room21'], $_POST['lesson'], '3', $_POST['day'], $_POST['zvedena21'], $_POST['name_subj21'], $_POST['name_teach21']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['new21'])){
        //subj, room, subj1, room1, day, lesson, zvedena, zvedena1
        newLesson($connect, $_POST['id_gr'], $_POST['subj210'], $array_teachers_f, $_POST['room210'], $_POST['lesson'], '4', $_POST['day'], $_POST['zvedena210'], $_POST['name_subj210'], $_POST['name_teach210']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj211'], $array_teachers_f, $_POST['room211'], $_POST['lesson'], '5', $_POST['day'], $_POST['zvedena211'], $_POST['name_subj211'], $_POST['name_teach211']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['new3'])){
        //subj, room, subj1, room1, subj2, room2, day, lesson, zvedena, zvedena1, zvedena2  
        newLesson($connect, $_POST['id_gr'], $_POST['subj30'], $array_teachers_f, $_POST['room30'], $_POST['lesson'], '6', $_POST['day'], $_POST['zvedena30'], $_POST['name_subj30'], $_POST['name_teach30']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj31'], $array_teachers_f, $_POST['room31'], $_POST['lesson'], '7', $_POST['day'], $_POST['zvedena31'], $_POST['name_subj31'], $_POST['name_teach31']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj32'], $array_teachers_f, $_POST['room32'], $_POST['lesson'], '5', $_POST['day'], $_POST['zvedena32'], $_POST['name_subj32'], $_POST['name_teach32']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['new31'])){
        //subj, room, subj1, room1, subj2, room2, day, lesson, zvedena, zvedena1, zvedena2  
        $type_lesson = ['4', '8', '9'];
        newLesson($connect, $_POST['id_gr'], $_POST['subj310'], $array_teachers_f, $_POST['room310'], $_POST['lesson'], '4', $_POST['day'], $_POST['zvedena310'], $_POST['name_subj310'], $_POST['name_teach310']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj311'], $array_teachers_f, $_POST['room311'], $_POST['lesson'], '8', $_POST['day'], $_POST['zvedena311'], $_POST['name_subj311'], $_POST['name_teach311']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj312'], $array_teachers_f, $_POST['room312'], $_POST['lesson'], '9', $_POST['day'], $_POST['zvedena312'], $_POST['name_subj312'], $_POST['name_teach312']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['new4'])){
        //subj, room, subj1, room1, subj2, room2, subj3, room3, day, lesson, zvedena, zvedena1, zvedena2, zvedena3  
        newLesson($connect, $_POST['id_gr'], $_POST['subj40'], $array_teachers_f, $_POST['room40'], $_POST['lesson'], '6', $_POST['day'], $_POST['zvedena40'], $_POST['name_subj40'], $_POST['name_teach40']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj41'], $array_teachers_f, $_POST['room41'], $_POST['lesson'], '7', $_POST['day'], $_POST['zvedena41'], $_POST['name_subj41'], $_POST['name_teach41']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj42'], $array_teachers_f, $_POST['room42'], $_POST['lesson'], '8', $_POST['day'], $_POST['zvedena42'], $_POST['name_subj42'], $_POST['name_teach42']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj43'], $array_teachers_f, $_POST['room43'], $_POST['lesson'], '9', $_POST['day'], $_POST['zvedena43'], $_POST['name_subj43'], $_POST['name_teach43']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['new5'])){
        //subj, room, subj1, room1, subj2, room2, day, lesson, zvedena, zvedena1, zvedena2  
        $type_lesson = ['2', '7', '9'];
        newLesson($connect, $_POST['id_gr'], $_POST['subj50'], $array_teachers_f, $_POST['room50'], $_POST['lesson'], '2', $_POST['day'], $_POST['zvedena50'], $_POST['name_subj50'], $_POST['name_teach50']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj51'], $array_teachers_f, $_POST['room51'], $_POST['lesson'], '7', $_POST['day'], $_POST['zvedena51'], $_POST['name_subj51'], $_POST['name_teach51']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj52'], $array_teachers_f, $_POST['room52'], $_POST['lesson'], '9', $_POST['day'], $_POST['zvedena52'], $_POST['name_subj52'], $_POST['name_teach52']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['new51'])){
        //subj, room, subj1, room1, subj2, room2, lesson, zvedena, zvedena1, zvedena2  
        $type_lesson = ['6', '8', '3'];
        newLesson($connect, $_POST['id_gr'], $_POST['subj510'], $array_teachers_f, $_POST['room510'], $_POST['lesson'], '6', $_POST['day'], $_POST['zvedena510'], $_POST['name_subj510'], $_POST['name_teach510']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj511'], $array_teachers_f, $_POST['room511'], $_POST['lesson'], '8', $_POST['day'], $_POST['zvedena511'], $_POST['name_subj511'], $_POST['name_teach511']);
		newLesson($connect, $_POST['id_gr'], $_POST['subj512'], $array_teachers_f, $_POST['room512'], $_POST['lesson'], '3', $_POST['day'], $_POST['zvedena512'], $_POST['name_subj512'], $_POST['name_teach512']);
		?><script>closeIt();</script><?php
    }
?>