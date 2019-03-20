<?php

//insert_data.php

include('database_connection.php');

if(isset($_POST["firma_kodu"]))
{
 $error = '';
 $success = '';
 $firma_kodu = '';
 $address = '';
 $alis_iskonto = '';
 $max_iskonto = '';
 $images = '';
 $firma_adi = $_POST["firma_adi"];
 if(empty($_POST["firma_kodu"]))
 {
  $error .= '<p>firma_kodu is Required</p>';
 }
 else
 {
  $firma_kodu = $_POST["firma_kodu"];
 }
 if(empty($_POST["address"]))
 {
  $error .= '<p>Address is Required</p>';
 }
 else
 {
  $address = $_POST["address"];
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

 if(isset($_FILES["images"]["firma_kodu"]) && $_FILES["images"]["firma_kodu"] != '')
 {
  $image_firma_kodu = $_FILES["images"]["firma_kodu"];
  $array = explode(".", $image_firma_kodu);
  $extension = end($array);
  $temporary_firma_kodu = $_FILES["images"]["tmp_firma_kodu"];
  $allowed_extension = array("jpg","png");
  if(!in_array($extension, $allowed_extension))
  {
   $error .= '<p>Invalid Image</p>';
  }
  else
  {
   $images = rand() . '.' . $extension;
   move_uploaded_file($temporary_firma_kodu, 'images/' . $images);
  }
 }
 if($error == '')
 {
  $data = array(
   ':firma_kodu'   => $firma_kodu,
   ':address'  => $address,
   ':firma_adi'  => $firma_adi,
   ':alis_iskonto' => $alis_iskonto,
   ':max_iskonto'   => $max_iskonto,
   ':images'  => $images
  );

  $query = "
  INSERT INTO firmalar 
  (firma_kodu, address, firma_adi, alis_iskonto, max_iskonto, images) 
  VALUES (:firma_kodu, :address, :firma_adi, :alis_iskonto, :max_iskonto, :images)
  ";
  $statement = $connect->prepare($query);
  $statement->execute($data);
  $success = 'Employee Data Inserted';
 }
 $output = array(
  'success'  => $success,
  'error'   => $error
 );
 echo json_encode($output);
 echo json_encode($data);
}

?>