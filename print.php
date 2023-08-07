<?php
    require_once("connect_db.php");
    $sql="SELECT * FROM `groups` ORDER BY `id_department` ASC ";
	$res=mysqli_query($connect,$sql);
	$array_groups=[];
	while ($result = mysqli_fetch_array($res)) {
		$array_groups[]=$result;
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
					$sql="SELECT `groups`.`id` AS `group`,  `groups`.`id_department`,  `schedule`.* FROM  `schedule` INNER JOIN `subjects` ON `schedule`.`id_subject`=`subjects`.`id` INNER JOIN `groups` ON `subjects`.`id_group`=`groups`.`id` WHERE `schedule`.`day`='".$i."' AND `schedule`.`n_lesson`='".$j."' ORDER BY `groups`.`id_department` ASC;";
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
            <?php }  }?>
        </table>
    </body>
</html>
