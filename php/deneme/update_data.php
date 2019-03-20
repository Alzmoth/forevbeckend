<?php

//update_data.php

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
    $address = $_POST[""];
 }
 else
 {
  $address = $_POST["address"];
 }

 if(empty($_POST["alis_iskonto"]))
 {
    $alis_iskonto =0;
 }
 else
 {
  $alis_iskonto = $_POST["alis_iskonto"];
 }

 if(empty($_POST["max_iskonto"]))
 {
    $max_iskonto =0;
 }
 else
 {
  $max_iskonto = $_POST["max_iskonto"];
 }

 $images = $_POST['hidden_images'];

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
   ':images'  => $images,
   ':id'   => $_POST["id"]
  );

  $query = "
  UPDATE firmalar 
  SET firma_kodu = :firma_kodu,
  address = :address,
  firma_adi = :firma_adi, 
  alis_iskonto = :alis_iskonto, 
  max_iskonto = :max_iskonto, 
  images = :images 
  WHERE id = :id
  ";
  $statement = $connect->prepare($query);
  $statement->execute($data);
  $success = 'Firma Guncellendi';
 }
 $output = array(
  'success'  => $success,
  'error'   => $error
 );
 echo json_encode($output);
}

?>
