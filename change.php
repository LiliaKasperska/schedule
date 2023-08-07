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
	$sql="SELECT * FROM `classroom`";
	$res=mysqli_query($connect,$sql);
	$array_cl=[];
	while($result=mysqli_fetch_array($res)){
		$array_cl[] = $result;
	}
	if($_GET['v']){
		$sql="SELECT * FROM `groups` WHERE `id_department`='".$_GET['v']."'  ";
	} else {
		$sql="SELECT * FROM `groups` ORDER BY `id_department` ASC ";
	}
	$res=mysqli_query($connect,$sql);
	$array_groups=[];
	while ($result = mysqli_fetch_array($res)) {
		$array_groups[]=$result;
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
            <div class="col-sm-1"></div>
            <div class="dropdown col-sm-1">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php 
                    if(!empty($_GET['v'])){
                        for ($q = 0; $q < count($array_department); $q++) {
                            if ($array_department[$q]['id']==$_GET['v']){
                                echo $array_department[$q]['name'];
                            }
                        }                           
                    } else {
                        echo "Всі відділення";
                    } ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <?php if($_SESSION['id_department']=="1"){ ?><li><a class="dropdown-item" href="change.php">Всі відділення</a></li><?php } ?>
                    <?php 
                    for ($q = 0; $q < count($array_department); $q++) {
                        echo "<li><a class='dropdown-item' href='change.php?v=".$array_department[$q]['id']."'>".$array_department[$q]['name']."</a></li>";
                    } ?>
                </ul>
            </div>
            <div class="col-sm-8"></div>
			<div class="dropdown col-sm-1">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                    Профіль
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
					<?php if($_SESSION['id_department']=="1"){ ?>
					<li><a class="dropdown-item" href="index.php">Переглянути розклад</a></li>
                    <li><a class="dropdown-item" href="change.php">Редагувати розклад</a></li>
					<li><a class="dropdown-item" href="users.php">Користувачі</a></li>
					<li><a class="dropdown-item" href="settings_subj.php?v=2">Налаштування</a></li> 
					<li><a class="dropdown-item" href="exit.php">Вихід</a></li>
					<?php } ?>
					<?php if(!empty($_SESSION['id_department']) and $_SESSION['id_department']!="1"){ ?>
					<li><a class="dropdown-item" href="index.php">Переглянути розклад</a></li>
                    <li><a class="dropdown-item" href="change.php?v=<?php echo $_SESSION['id_department']; ?>">Редагувати розклад</a></li>
					<li><a class="dropdown-item" href="settings_subj.php?v=<?php echo $_SESSION['id_department']; ?>">Налаштування</a></li> 
					<li><a class="dropdown-item" href="users_settings.php">Налаштуваня профілю</a></li>
					<li><a class="dropdown-item" href="exit.php">Вихід</a></li>
					<?php } ?>
                </ul>
            </div>	
        </div>
        <div style="height: 8px;"></div>
		<div style="height: 2px; background-color: #ECECEC;"></div>
		<div style="height: 10px;"></div>
        <!---  розклад  --->
        <table class="table-bordered" style="font-family: Cambria; font-size: 14pt; width: <?php echo 69+count($array_groups)*180; ?>px;">
            <!---  Заголовок (група, куратор, поділ спеціальностей)  --->
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
            <!---  день тиждня  --->
            <?php 
            for ($i = 0; $i < count($array_day); $i++) {
                for ($j = 1; $j < 8; $j++) { 
                    $sql="SELECT `groups`.`id` AS `group`,  `groups`.`id_department`,  `schedule`.*, `subjects`.`id_teacher`, `subjects`.`teachers` FROM  `schedule` INNER JOIN `subjects` ON `schedule`.`id_subject`=`subjects`.`id` INNER JOIN `groups` ON `subjects`.`id_group`=`groups`.`id` WHERE `schedule`.`day`='".$i."' AND `schedule`.`n_lesson`='".$j."' ORDER BY `groups`.`id_department` ASC;";
	                $res=mysqli_query($connect,$sql);
	                $array_1=[];
	                while ($result = mysqli_fetch_array($res)) {
	                	$array_1[]=$result;
	                }
					?>
                <tr>
                    <?php if($j==1){ ?><td rowspan="28" style="font-size: 22pt; font-weight: bold; width: 44px; text-align: center;"><?php echo $array_day[$i][0]; ?></td><?php } ?>
                    <td rowspan="4" style="font-weight: bold; width: 25px; text-align: center;"><?php echo $j; ?></td>
                    <?php 
                        for ($g = 0; $g < count($array_groups); $g++) {
                            $n=0;
                            for ($ar = 0; $ar < count($array_1); $ar++) {
                                if($array_groups[$g]['id']==$array_1[$ar]['group']){
                                    if($array_1[$ar]['id_type_lesson']=='1'){
                                        echo "<td colspan='4' style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'><a href='form_change.php?i=".$i."&j=".$j."&g=".$array_1[$ar]['group']."&v=".$_GET['v']."'><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'><path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/></svg></a>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td>";
                                        $n=1;
                                    } 
                                    if($array_1[$ar]['id_type_lesson']=='2'){
                                        echo "<td colspan='2' style='height: 23px; border-right: hidden; border-bottom: hidden; text-align: left; font-style: italic;'><a href='form_change.php?i=".$i."&j=".$j."&g=".$array_1[$ar]['group']."&v=".$_GET['v']."'><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'><path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/></svg></a>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td><td colspan='2' style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'>".$array_1[$ar]['name_teacher']."</td>";
                                        $n=1;
                                    }
                                    if($array_1[$ar]['id_type_lesson']=='3'){
                                        echo ""; // -
                                        $n=1;
                                    }
                                    if($array_1[$ar]['id_type_lesson']=='4'){
                                        echo "<td colspan='2' style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'><a href='form_change.php?i=".$i."&j=".$j."&g=".$array_1[$ar]['group']."&v=".$_GET['v']."'><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'><path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/></svg></a>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td>";
                                        $n=1;
                                    }
                                    if($array_1[$ar]['id_type_lesson']=='5'){
                                        echo "<td colspan='2' style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td>";
                                        $n=1;
                                    }
                                    if($array_1[$ar]['id_type_lesson']=='6'){
                                        echo "<td style='height: 23px; border-right: hidden; border-bottom: hidden; text-align: left; font-style: italic; '><a href='form_change.php?i=".$i."&j=".$j."&g=".$array_1[$ar]['group']."&v=".$_GET['v']."'><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'><path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/></svg></a>".clas($array_1[$ar]['id_classroom'],$array_1[$ar]['id_classroom1'], $array_cl)."</td><td style='height: 23px; border-bottom: hidden; text-align: right; font-style: italic;'>".$array_1[$ar]['name_teacher']."</td>";
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
                    <td colspan="4" rowspan="4" style="height: 94px; text-align: center; font-weight: bold; vertical-align: middle;"> 
                        <a href="form_add.php?i=<?php echo $i; ?>&j=<?php echo $j; ?>&g=<?php echo $array_groups[$g]['id']; ?>&v=<?php echo $_GET['v']; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"  fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                        </a>                        
                    </td>
                    <?php }} ?>
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
                <?php } ?>
            <?php } ?>
        </table>
        <br>
    </body> 
</html>
