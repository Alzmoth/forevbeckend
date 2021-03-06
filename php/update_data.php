<?php

//update_data.php

include('database_connection.php');

if(isset($_POST["firma_kodu"]))
{
 $error = '';
 $success = '';
 $firma_kodu = '';
 $firma_adi = '';
 $alis_iskonto = '';
 $max_iskonto = '';
 $images = '';
 $gender = $_POST["gender"];
 $max_iskonto = $_POST["max_iskonto"];
 if(empty($_POST["firma_kodu"]))
 {
  $error .= '<p>firma_kodu is Required</p>';
 }
 else
 {
  $firma_kodu = $_POST["firma_kodu"];
 }
 if(empty($_POST["firma_adi"]))
 {
  $error .= '<p>firma_adi is Required</p>';
 }
 else
 {
  $firma_adi = $_POST["firma_adi"];
 }
 if(empty($_POST["alis_iskonto"]))
 {
  $error .= '<p>alis_iskonto is Required</p>';
 }
 else
 {
  $alis_iskonto = $_POST["alis_iskonto"];
 }
 if(empty($_POST["max_iskonto"]))
 {
  $error .= '<p>max_iskonto is Required</p>';
 }
 else
 {
  $max_iskonto = $_POST["max_iskonto"];
 }

 $images = $_POST['hidden_images'];

 if(isset($_FILES["images"]["name"]) && $_FILES["images"]["name"] != '')
 {
  $image_name = $_FILES["images"]["name"];
  $array = explode(".", $image_name);
  $extension = end($array);
  $temporary_name = $_FILES["images"]["tmp_name"];
  $allowed_extension = array("jpg","png");
  if(!in_array($extension, $allowed_extension))
  {
   $error .= '<p>Invalid Image</p>';
  }
  else
  {
   $images = rand() . '.' . $extension;
   move_uploaded_file($temporary_name, 'images/' . $images);
  }
 }
 if($error == '')
 {
  $data = array(
   ':firma_kodu'   => $firma_kodu,
   ':firma_adi'  => $firma_adi,
   ':max_iskonto'  => $max_iskonto,
   ':alis_iskonto' => $alis_iskonto,
   ':gender'  => $gender,
   ':images'  => $images,
   ':id'   => $_POST["id"]
  );

  $query = "
  UPDATE firmalar 
  SET firma_kodu = :firma_kodu,
  firma_adi = :firma_adi,
  max_iskonto = :max_iskonto, 
  alis_iskonto = :alis_iskonto, 
  gender = :gender, 
  images = :images 
  WHERE id = :id
  ";
  $statement = $connect->prepare($query);
  $statement->execute($data);
  $success = 'Employee Data Updated';
 }
 $output = array(
  'success'  => $success,
  'error'   => $error
 );
 echo json_encode($output);
}

?>
