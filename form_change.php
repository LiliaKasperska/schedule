<?php
	$ii=$_GET['i'];
	$j=$_GET['j'];
	$g=$_GET['g'];
	$v=$_GET['v'];
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
	$sql="SELECT * FROM `groups` WHERE `id`='".$_GET['g']."'";
	$res=mysqli_query($connect,$sql);
	$array_groups=mysqli_fetch_array($res);
	
    $sql="SELECT * FROM `teachers`";
	$res=mysqli_query($connect,$sql);
	$array_teachers_f=[];
	while($result=mysqli_fetch_array($res)){
		$array_teachers_f[] = $result;
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
	// form_change вибір кабінетів
	function select_room($array_classroom, $on_form, $o, $array_1, $i){
		for ($z = 0; $z < count($array_classroom); $z++) {
			if($array_classroom[$z]['id']=="0"){
				echo "<option ";
				if($on_form==$o and $array_classroom[$z]['id']==$array_1[$i]['id_classroom']){
					echo "selected ";
				}
				echo "value='" . $array_classroom[$z]['id'] . "'>" . $array_classroom[$z]['name'] . "</option>";
			} else {
				echo "<option ";
				if($on_form==$o and ($array_classroom[$z]['id']==$array_1[$i]['id_classroom'] or $array_classroom[$z]['id']==$array_1[$i]['id_classroom1'])){
					echo "selected ";
				}
				echo "value='" . $array_classroom[$z]['id'] . "'>" . $array_classroom[$z]['name'] . " (".$array_classroom[$z]['nameK'].")"."</option>";
			}
		}
	}
	// form_change зберігання змін в парі
	function save_lesson($connect, $id, $id_s, $zvedena, $subj, $array_teachers_f, $room, $ns, $nt){
		if($zvedena=='1'){
			$zv='1';
		} else {
			$zv='0';
		} 
		$sql = "UPDATE `schedule` SET `zvedena`='".$zv."', `id_subject`='".$subj."', `name_subj`='".$ns."', `name_teacher`='".$nt."' WHERE `id`='".$id."';";
		$res=mysqli_query($connect,$sql);
		//кабінети
		if(count($room)==2){
			$sql = "UPDATE `schedule` SET `id_classroom`='".$room[0]."', `id_classroom1`='".$room[1]."' WHERE `id`='".$id."';";
		} else {
			$sql = "UPDATE `schedule` SET `id_classroom`='".$room[0]."', `id_classroom1`='0' WHERE `id`='".$id."';";
		}
		$res=mysqli_query($connect,$sql);
	}
	
	//випадний список + скрипт + поля для виводу
	//formElement($connect, номер біля предмету і ід)
	function formElement($connect, $name, $i, $on, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v){
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
		$sql="SELECT * FROM `subjects` WHERE `name`!=' ' AND `id_group`='".$array_groups['id']."' ORDER BY `name` ASC";
		$res=mysqli_query($connect,$sql);
		while ($result = mysqli_fetch_array($res)) {
			echo "<option ";
			if($on_form==$on and $result['id']==$array_1[$i]['id_subject']){
				echo "selected ";
			}
			echo "value='".$result['id']."'>".$result['name']." (".pib($result['id_teacher'], $result['teachers'], $array_teachers_f).")"."</option>";
		}
		$sql2="SELECT `id_department` FROM `groups` WHERE `id`='".$g."'";
		$res2=mysqli_query($connect,$sql2);
		$v = mysqli_fetch_array($res2);
		echo '</select></div><div class="col-sm-1"><a href="http://r.gi.edu.ua/settings_subj.php?v='.$v[0].'#g'.$g.'" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"  fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg></a></div></div><br>';
		echo '<script>function updateInputValue'.$name.'() { var select = document.getElementById("subj'.$name.'"); var input = document.getElementById("name_subj'.$name.'"); var input1 = document.getElementById("name_teach'.$name.'"); switch(select.value){ case "'.$array_1[$i]['id_subject'].'": input.value = "'.$array_1[$i]['name_subj'].'"; input1.value = "'.$array_1[$i]['name_teacher'].'"; break;'; 
		$sql="SELECT * FROM `subjects` WHERE `name`!=' ' AND `id_group`='".$array_groups['id']."' AND `id`!='".$array_1[$i]['id_subject']."'";
		$res=mysqli_query($connect,$sql);
		while ($result = mysqli_fetch_array($res)) {
			echo " case '".$result['id']."': input.value='".$result['name']."'; input1.value='".pib($result['id_teacher'], $result['teachers'], $array_teachers_f)."'; break;";
		}
		echo 'default: input.value=" "; input1.value=" "; break; } }</script>';
		echo "<label class='form-label'>Відображається в розкладі:</label><input name='name_subj".$name."' id='name_subj".$name."' value='";
		if($on_form==$on){
			echo $array_1[$i]['name_subj'];
		}
		echo "' type='text' class='form-control'><br><input name='name_teach".$name."' id='name_teach".$name."' value='";
		if($on_form==$on){
			echo $array_1[$i]['name_teacher'];
		}
		echo "' type='text'  class='form-control'><br>";
	}
?>
<!DOCTYPE html>
<html>
<script language="JavaScript">
function closeIt() { document.location.href="change.php<?php if($_GET['v']){echo "?v=".$_GET['v'];} ?>";}
</script>
    <head>
        <meta charset='utf-8'>
        <title>Редагувати пару</title>
        <link rel='stylesheet' type='text/css' href='main.css'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body style="background-color: gray;">
		<?php 
		$sql="SELECT `schedule`.* FROM  `schedule` INNER JOIN `subjects` ON `schedule`.`id_subject`=`subjects`.`id` INNER JOIN `groups` ON `subjects`.`id_group`=`groups`.`id` WHERE `schedule`.`day`='".$ii."' AND `schedule`.`n_lesson`='".$j."' AND `groups`.`id`='".$g."';";
		$res=mysqli_query($connect,$sql);
		$array_1=[];
		$array_type=[];
		$array_id=[];
		while ($result = mysqli_fetch_array($res)) {
			$array_1[]=$result;
			$array_type[]=$result['id_type_lesson'];
			$array_id[]=$result['id'];
		}
		$class="`schedule`.`id`!='".$array_id[0]."'";
		for($q=1;$q<count($array_id);$q++){
			$class=$class." AND `schedule`.`id`!='".$array_id[0]."'";
		}
		$sql01="SELECT `schedule`.`id`, `schedule`.`id_classroom` FROM `schedule` WHERE `schedule`.`day`='".$i."' AND `schedule`.`id_classroom`!='0' AND `schedule`.`n_lesson`='".$j."' AND ".$class." UNION SELECT `schedule`.`id`, `schedule`.`id_classroom1` FROM `schedule` WHERE `schedule`.`day`='".$i."' AND `schedule`.`id_classroom1`!='0' AND `schedule`.`n_lesson`='".$j."' AND ".$class.";";
		$res01=mysqli_query($connect,$sql01);
		$array_class=[];
		while ($result01 = mysqli_fetch_array($res01)) {
			$array_class[]=$result01['id_classroom'];
		}
		$sql = "SELECT `classroom`.`id`, `classroom`.`name`, `building`.`name` AS `nameK` FROM `classroom` INNER JOIN `building` ON `classroom`.`id_building`=`building`.`id` WHERE `classroom`.`id`!='".$array_class[0]."' ";
		for($q=1;$q<count($array_class);$q++){
			$sql=$sql."AND `classroom`.`id`!='".$array_class[$q]."' ";
		}
		$res=mysqli_query($connect,$sql);
		$array_classroom=[];
		while($result=mysqli_fetch_array($res)){
			$array_classroom[] = $result;
		}
		$on_form=0;
		if(in_array('1',$array_type)){
			$on_form=1;
		}
		if(in_array('2',$array_type) and in_array('3',$array_type)){
			$on_form=2;
		}
		if(in_array('4',$array_type) and in_array('5',$array_type)){
			$on_form=21;
		}
		if(in_array('6',$array_type) and in_array('7',$array_type) and in_array('5',$array_type)){
			$on_form=3;
		}
		if(in_array('4',$array_type) and in_array('9',$array_type) and in_array('8',$array_type)){
			$on_form=31;
		}
		if(in_array('6',$array_type) and in_array('7',$array_type) and in_array('9',$array_type) and in_array('8',$array_type)){
			$on_form=4;
		}
		if(in_array('2',$array_type) and in_array('7',$array_type) and in_array('9',$array_type)){
			$on_form=5;
		}
		if(in_array('3',$array_type) and in_array('6',$array_type) and in_array('8',$array_type)){
			$on_form=51;
		}
		?>
		<!--- якшо $on_form==.. то назва кнопки Зберегти save, якщо ні то назва new--->
		<div class="<?php if($on_form==1){echo "modal1 fade1";} else {echo "modal fade";} ?>" id="exampleModalToggle1" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$ii][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
					</div>
					<div class="modal-body">
						<?php buttons(); ?>
						<br><br>
						<form action="form_change.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $ii; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<?php 
							for($q=0;$q<count($array_1);$q++){
								echo "<input name='id[]' type='hidden' value=".$array_1[$q]['id']."><input name='id_s[]' type='hidden' value=".$array_1[$q]['id_subject'].">";
							} 	
							?>
							<?php formElement($connect, 0, 0, 1, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 1, $array_1, 0);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena" <?php if($array_1[0]['zvedena']=='1'){echo "checked";} ?> type="checkbox" value="1">Зведена пара
							<br><br>
							<input type="submit" class="btn btn-outline-secondary" name="<?php if($on_form==1){ echo "save1";} else {echo "new1";} ?>" value="Зберегти" style="<?php if($on_form==1){ echo "margin-right: 25%; margin-left: 15%;";} else {echo "margin-left: 38%;";} ?>">
							<?php if($on_form==1){ echo "<input type='submit' class='btn btn-outline-secondary' name='delete' value='Видалити'>";} ?>
						</form>	
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		<div class="<?php if($on_form==2){echo "modal1 fade1";} else {echo "modal fade";} ?>" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$ii][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
					</div>
					<div class="modal-body">
						<?php buttons(); ?>
						<br><br>
						<form action="form_change.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $ii; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<?php 
							for($q=0;$q<count($array_id);$q++){
								echo "<input name='id[]' type='hidden' value=".$array_id[$q]."><input name='id_s[]' type='hidden' value=".$array_1[$q]['id_subject'].">";
							}
							?>
							<h5>Верхи</h5>
							<?php formElement($connect, 20, 0, 2, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 2, $array_1, 0);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena" type="checkbox" value="1">Зведена пара
							<br><br>
							<h5>Низи</h5>
							<?php formElement($connect, 21, 1, 2, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room1[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 2, $array_1, 1);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena1" type="checkbox" value="1">Зведена пара
							<br><br>
							<input type="submit" class="btn btn-outline-secondary" name="<?php if($on_form==2){ echo "save2";} else {echo "new2";} ?>" value="Зберегти" style="<?php if($on_form==2){ echo "margin-right: 25%; margin-left: 15%;";} else {echo "margin-left: 38%;";} ?>">
							<?php if($on_form==2){ echo "<input type='submit' class='btn btn-outline-secondary' name='delete' value='Видалити'>";} ?>
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		<div class="<?php if($on_form==21){echo "modal1 fade1";} else {echo "modal fade";} ?>" id="exampleModalToggle21" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$ii][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
					</div>
					<div class="modal-body">
						<?php buttons(); ?>
						<br><br>
						<form action="form_change.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $ii; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<?php 
							for($q=0;$q<count($array_id);$q++){
								echo "<input name='id[]' type='hidden' value=".$array_id[$q]."><input name='id_s[]' type='hidden' value=".$array_1[$q]['id_subject'].">";
							}
							?>
							<h5>Спеціальність 1</h5>
							<?php formElement($connect, 210, 0, 21, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 21, $array_1, 0);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena" type="checkbox" value="1">Зведена пара
							<br><br>
							<h5>Спеціальність 2</h5>
							<?php formElement($connect, 211, 1, 21, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room1[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 21, $array_1, 1);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena1" type="checkbox" value="1">Зведена пара
                            <br><br>
							<input type="submit" class="btn btn-outline-secondary" name="<?php if($on_form==21){ echo "save21";} else {echo "new21";} ?>" value="Зберегти" style="<?php if($on_form==21){ echo "margin-right: 25%; margin-left: 15%;";} else {echo "margin-left: 38%;";} ?>">
							<?php if($on_form==21){ echo "<input type='submit' class='btn btn-outline-secondary' name='delete' value='Видалити'>";} ?>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <div class="<?php if($on_form==3){echo "modal1 fade1";} else {echo "modal fade";} ?>" id="exampleModalToggle3" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$ii][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
                    </div>
                    <div class="modal-body">
                        <?php buttons(); ?>
                        <br><br>
                        <form action="form_change.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
                            <input name="day" type="hidden" value="<?php echo $ii; ?>">
                            <input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<?php 
							for($q=0;$q<count($array_id);$q++){
								echo "<input name='id[]' type='hidden' value=".$array_id[$q]."><input name='id_s[]' type='hidden' value=".$array_1[$q]['id_subject'].">";
							}
							?>
                            <h5>Спеціальність 1</h5>
                            <h5>Верхи</h5>
							<?php formElement($connect, 30, 0, 3, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 3, $array_1, 0);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena" type="checkbox" value="1">Зведена пара
                            <br><br>
			    			<h5>Низи</h5>
							<?php formElement($connect, 31, 1, 3, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room1[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 3, $array_1, 1);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena1" type="checkbox" value="1">Зведена пара
                            <br><br>
			    			<h5>Спеціальність 2</h5>
							<?php formElement($connect, 32, 2, 3, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room2[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 3, $array_1, 2);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena2" type="checkbox" value="1">Зведена пара
                            <br><br>
							<input type="submit" class="btn btn-outline-secondary" name="<?php if($on_form==3){ echo "save3";} else {echo "new3";} ?>" value="Зберегти" style="<?php if($on_form==3){ echo "margin-right: 25%; margin-left: 15%;";} else {echo "margin-left: 38%;";} ?>">
							<?php if($on_form==3){ echo "<input type='submit' class='btn btn-outline-secondary' name='delete' value='Видалити'>";} ?>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <div class="<?php if($on_form==31){echo "modal1 fade1";} else {echo "modal fade";} ?>" id="exampleModalToggle31" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$ii][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
                    </div>
                    <div class="modal-body">
                        <?php buttons(); ?>
                        <br><br>
                        <form action="form_change.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
                            <input name="day" type="hidden" value="<?php echo $ii; ?>">
                            <input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<?php 
							for($q=0;$q<count($array_id);$q++){
								echo "<input name='id[]' type='hidden' value=".$array_id[$q]."><input name='id_s[]' type='hidden' value=".$array_1[$q]['id_subject'].">";
							}
							?>
                            <h5>Спеціальність 1</h5>
							<?php formElement($connect, 310, 0, 31, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 31, $array_1, 0);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena" type="checkbox" value="1">Зведена пара
                            <br><br>
			    			<h5>Спеціальність 2</h5>
			    			<h5>Верхи</h5>
							<?php formElement($connect, 311, 1, 31, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room1[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 31, $array_1, 1);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena1" type="checkbox" value="1">Зведена пара
                            <br><br>
			    			<h5>Низи</h5>
							<?php formElement($connect, 312, 2, 31, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room2[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 31, $array_1, 2);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena2" type="checkbox" value="1">Зведена пара
                            <br><br>
							<input type="submit" class="btn btn-outline-secondary" name="<?php if($on_form==31){ echo "save31";} else {echo "new31";} ?>" value="Зберегти" style="<?php if($on_form==31){ echo "margin-right: 25%; margin-left: 15%;";} else {echo "margin-left: 38%;";} ?>">
							<?php if($on_form==31){ echo "<input type='submit' class='btn btn-outline-secondary' name='delete' value='Видалити'>";} ?>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
        <div class="<?php if($on_form==4){echo "modal1 fade1";} else {echo "modal fade";} ?>" id="exampleModalToggle4" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$ii][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
                    </div>
                    <div class="modal-body">
                        <?php buttons(); ?>
						<br><br>
						<form action="form_change.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $ii; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<?php 
							for($q=0;$q<count($array_id);$q++){
								echo "<input name='id[]' type='hidden' value=".$array_id[$q]."><input name='id_s[]' type='hidden' value=".$array_1[$q]['id_subject'].">";
							}
							?>
							<h5>Спеціальність 1</h5>
							<h5>Верхи</h5>
							<?php formElement($connect, 40, 0, 4, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 4, $array_1, 0);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena" type="checkbox" value="1">Зведена пара
							<br><br>
							<h5>Низи</h5>
							<?php formElement($connect, 41, 1, 4, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room1[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 4, $array_1, 1);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena1" type="checkbox" value="1">Зведена пара
							<br><br>
							<h5>Спеціальність 2</h5>
							<h5>Верхи</h5>
							<?php formElement($connect, 42, 2, 4, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room2[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 4, $array_1, 2);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena2" type="checkbox" value="1">Зведена пара
							<br><br>
							<h5>Низи</h5>
							<?php formElement($connect,43, 3, 4, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room3[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 4, $array_1, 3);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena3" type="checkbox" value="1">Зведена пара
							<br><br>
							<input type="submit" class="btn btn-outline-secondary" name="<?php if($on_form==4){ echo "save4";} else {echo "new4";} ?>" value="Зберегти" style="<?php if($on_form==4){ echo "margin-right: 25%; margin-left: 15%;";} else {echo "margin-left: 38%;";} ?>">
							<?php if($on_form==4){ echo "<input type='submit' class='btn btn-outline-secondary' name='delete' value='Видалити'>";} ?>
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		<div class="<?php if($on_form==5){echo "modal1 fade1";} else {echo "modal fade";} ?>" id="exampleModalToggle5" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$ii][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
					</div>
					<div class="modal-body">
						<?php buttons(); ?>
						<br><br>
						<form action="form_change.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
							<input name="day" type="hidden" value="<?php echo $ii; ?>">
							<input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<?php 
							for($q=0;$q<count($array_id);$q++){
								echo "<input name='id[]' type='hidden' value=".$array_id[$q]."><input name='id_s[]' type='hidden' value=".$array_1[$q]['id_subject'].">";
							}
							?>
							<h5>Верхи</h5>
							<?php formElement($connect, 50, 0, 5, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 5, $array_1, 0);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena" type="checkbox" value="1">Зведена пара
							<br><br>
							<h5>Спеціальність 1. Низи</h5>
							<?php formElement($connect, 51, 1, 5, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room1[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 5, $array_1, 1);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena1" type="checkbox" value="1">Зведена пара
							<br><br>
							<h5>Спеціальність 2. Низи</h5>
							<?php formElement($connect, 52, 2, 5, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
							<select name="room2[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 5, $array_1, 2);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
							<input  name="zvedena2" type="checkbox" value="1">Зведена пара
							<br><br>
							<input type="submit" class="btn btn-outline-secondary" name="<?php if($on_form==5){ echo "save5";} else {echo "new5";} ?>" value="Зберегти" style="<?php if($on_form==5){ echo "margin-right: 25%; margin-left: 15%;";} else {echo "margin-left: 38%;";} ?>">
							<?php if($on_form==5){ echo "<input type='submit' class='btn btn-outline-secondary' name='delete' value='Видалити'>";} ?>
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
        <div class="<?php if($on_form==51){echo "modal1 fade1";} else {echo "modal fade";} ?>" id="exampleModalToggle51" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalToggleLabel2"><?php echo $array_day[$ii][1].", ".$j." пара, ".$array_groups['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onClick="closeIt()"></button>
                    </div>
                    <div class="modal-body">
                        <?php buttons(); ?>
                        <br><br>
                        <form action="form_change.php?i=<?php echo $i;?>&j=<?php echo $j;?>&g=<?php echo $g;?>&v=<?php echo $v;?>" method="POST">
                            <input name="day" type="hidden" value="<?php echo $ii; ?>">
                            <input name="lesson" type="hidden" value="<?php echo $j; ?>">
							<?php 
							for($q=0;$q<count($array_id);$q++){
								echo "<input name='id[]' type='hidden' value=".$array_id[$q]."><input name='id_s[]' type='hidden' value=".$array_1[$q]['id_subject'].">";
							}
							?>
                            <h5>Спеціальність 1. Верхи</h5>
							<?php formElement($connect, 510, 0, 51, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 51, $array_1, 0);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena" type="checkbox" value="1">Зведена пара
                            <br><br>
                            <h5>Спеціальність 2. Верхи</h5>
							<?php formElement($connect, 511, 1, 51, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room1[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 51, $array_1, 1);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena1" type="checkbox" value="1">Зведена пара
                            <br><br>
                            <h5>Низи</h5>
							<?php formElement($connect, 512, 2, 51, $on_form, $array_groups, $array_1, $array_teachers_f, $g, $v); ?>
                            <select name="room2[]" multiple class="form-select" style="height: 20%;">
							<?php select_room($array_classroom, $on_form, 51, $array_1, 2);?> 
							</select>
							<p>*Для вибору декількох кабінетів зажміть Ctrl</p>
                            <input  name="zvedena2" type="checkbox" value="1">Зведена пара
                            <br><br>
							<input type="submit" class="btn btn-outline-secondary" name="<?php if($on_form==51){ echo "save51";} else {echo "new51";} ?>" value="Зберегти" style="<?php if($on_form==51){ echo "margin-right: 25%; margin-left: 15%;";} else {echo "margin-left: 38%;";} ?>"> 
							<?php if($on_form==51){ echo "<input type='submit' class='btn btn-outline-secondary' name='delete' value='Видалити'>";} ?>
                        </form> 
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
    </body> 
</html>
<?php
    if(isset($_POST['save1'])){
        // id[], id_s[], subj, name_subj0, name_teach0, room[], zvedena
		save_lesson($connect, $_POST['id'][0], $_POST['id_s'][0], $_POST['zvedena'], $_POST['subj0'], $array_teachers_f, $_POST['room'], $_POST['name_subj0'], $_POST['name_teach0']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['save2'])){
        //day, lesson, id[], subj, name_subj0, name_teach0, room[], zvedena, subj1, name_subj1, name_teach1, room1[], zvedena1
		save_lesson($connect, $_POST['id'][0], $_POST['id_s'][0], $_POST['zvedena'], $_POST['subj20'], $array_teachers_f, $_POST['room'], $_POST['name_subj20'], $_POST['name_teach20']);
		save_lesson($connect, $_POST['id'][1], $_POST['id_s'][1], $_POST['zvedena1'], $_POST['subj21'], $array_teachers_f, $_POST['room1'], $_POST['name_subj21'], $_POST['name_teach21']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['save21'])){
        //day, lesson, id[], subj, name_subj0, name_teach0, room[], zvedena, subj1, name_subj1, name_teach1, room1[], zvedena1
		save_lesson($connect, $_POST['id'][0], $_POST['id_s'][0], $_POST['zvedena'], $_POST['subj210'], $array_teachers_f, $_POST['room'], $_POST['name_subj210'], $_POST['name_teach210']);
		save_lesson($connect, $_POST['id'][1], $_POST['id_s'][1], $_POST['zvedena1'], $_POST['subj211'], $array_teachers_f, $_POST['room1'], $_POST['name_subj211'], $_POST['name_teach211']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['save3'])){
        //day, lesson, id[], subj, name_subj0, name_teach0, room[], zvedena, subj1, name_subj1, name_teach1, room1[], zvedena1, subj2, name_subj2, name_teach2, room2[], zvedena2
		save_lesson($connect, $_POST['id'][0], $_POST['id_s'][0], $_POST['zvedena'], $_POST['subj30'], $array_teachers_f, $_POST['room'], $_POST['name_subj30'], $_POST['name_teach30']);
		save_lesson($connect, $_POST['id'][1], $_POST['id_s'][1], $_POST['zvedena1'], $_POST['subj31'], $array_teachers_f, $_POST['room1'], $_POST['name_subj31'], $_POST['name_teach1']);
		save_lesson($connect, $_POST['id'][2], $_POST['id_s'][2], $_POST['zvedena2'], $_POST['subj32'], $array_teachers_f, $_POST['room2'], $_POST['name_subj32'], $_POST['name_teach2']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['save31'])){
        //day, lesson, id[], subj, name_subj0, name_teach0, room[], zvedena, subj1, name_subj1, name_teach1, room1[], zvedena1, subj2, name_subj2, name_teach2, room2[], zvedena2
		save_lesson($connect, $_POST['id'][0], $_POST['id_s'][0], $_POST['zvedena'], $_POST['subj310'], $array_teachers_f, $_POST['room'], $_POST['name_subj310'], $_POST['name_teach310']);
		save_lesson($connect, $_POST['id'][1], $_POST['id_s'][1], $_POST['zvedena1'], $_POST['subj311'], $array_teachers_f, $_POST['room1'], $_POST['name_subj311'], $_POST['name_teach311']);
		save_lesson($connect, $_POST['id'][2], $_POST['id_s'][2], $_POST['zvedena2'], $_POST['subj312'], $array_teachers_f, $_POST['room2'], $_POST['name_subj312'], $_POST['name_teach312']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['save4'])){
        //day, lesson, id[], subj, name_subj0, name_teach0, room[], zvedena, subj1, name_subj1, name_teach1, room1[], zvedena1, subj2, name_subj2, name_teach2, room2[], zvedena2, subj3, name_subj3, name_teach3, room3[], zvedena3
		save_lesson($connect, $_POST['id'][0], $_POST['id_s'][0], $_POST['zvedena'], $_POST['subj40'], $array_teachers_f, $_POST['room'], $_POST['name_subj0'], $_POST['name_teach0']);
		save_lesson($connect, $_POST['id'][1], $_POST['id_s'][1], $_POST['zvedena1'], $_POST['subj41'], $array_teachers_f, $_POST['room1'], $_POST['name_subj41'], $_POST['name_teach41']);
		save_lesson($connect, $_POST['id'][2], $_POST['id_s'][2], $_POST['zvedena2'], $_POST['subj42'], $array_teachers_f, $_POST['room2'], $_POST['name_subj42'], $_POST['name_teach42']);
		save_lesson($connect, $_POST['id'][3], $_POST['id_s'][3], $_POST['zvedena3'], $_POST['subj43'], $array_teachers_f, $_POST['room3'], $_POST['name_subj43'], $_POST['name_teach43']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['save5'])){
        //day, lesson, id[], subj, name_subj0, name_teach0, room[], zvedena, subj1, name_subj1, name_teach1, room1[], zvedena1, subj2, name_subj2, name_teach2, room2[], zvedena2
		save_lesson($connect, $_POST['id'][0], $_POST['id_s'][0], $_POST['zvedena'], $_POST['subj50'], $array_teachers_f, $_POST['room'], $_POST['name_subj50'], $_POST['name_teach0']);
		save_lesson($connect, $_POST['id'][1], $_POST['id_s'][1], $_POST['zvedena1'], $_POST['subj51'], $array_teachers_f, $_POST['room1'], $_POST['name_subj51'], $_POST['name_teach51']);
		save_lesson($connect, $_POST['id'][2], $_POST['id_s'][2], $_POST['zvedena2'], $_POST['subj52'], $array_teachers_f, $_POST['room2'], $_POST['name_subj52'], $_POST['name_teach52']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['save51'])){
        //day, lesson, id[], subj, name_subj0, name_teach0, room[], zvedena, subj1, name_subj1, name_teach1, room1[], zvedena1, subj2, name_subj2, name_teach2, room2[], zvedena2
		save_lesson($connect, $_POST['id'][0], $_POST['id_s'][0], $_POST['zvedena'], $_POST['subj510'], $array_teachers_f, $_POST['room'], $_POST['name_subj510'], $_POST['name_teach0']);
		save_lesson($connect, $_POST['id'][1], $_POST['id_s'][1], $_POST['zvedena1'], $_POST['subj511'], $array_teachers_f, $_POST['room1'], $_POST['name_subj511'], $_POST['name_teach1']);
		save_lesson($connect, $_POST['id'][2], $_POST['id_s'][2], $_POST['zvedena2'], $_POST['subj512'], $array_teachers_f, $_POST['room2'], $_POST['name_subj512'], $_POST['name_teach2']);
		?><script>closeIt();</script><?php
    }
    if(isset($_POST['delete'])){
        //id
		for($i=0;$i<count($_POST['id']);$i++){
			$sql = "DELETE FROM `schedule` WHERE `id`='".$_POST['id'][$i]."'";
			$res=mysqli_query($connect,$sql);
		}
		?><script>closeIt();</script><?php
    }
?>