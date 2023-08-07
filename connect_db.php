<?php

$host="localhost";
$user="sql_r_gi_edu_ua";
$user_password="cLK7EasM8xXAexxS";
$database="sql_r_gi_edu_ua";

//підключення до бд
$connect = mysqli_connect($host,$user, $user_password, $database);
//$sql="SELECT * FROM category";
//$res=mysqli_query($connect,$sql);
//$result=mysqli_fetch_array($res);
/*
для форми редагування розкладу
<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Модалка 1</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body">
        Покажите второе модальное окно и скройте его с помощью кнопки ниже.
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Открыть второе модальное окно</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Модалка 2</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body">
        Скройте это модальное окно и покажите первое с помощью кнопки ниже.
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Вернуться к первому</button>
      </div>
    </div>
  </div>
</div>
<a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Открыть первое модальное окно</a>*/
?>

