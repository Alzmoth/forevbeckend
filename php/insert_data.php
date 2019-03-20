<?php

//insert_data.php

include('database_connection.php');

if(isset($_POST["firma_kodu"]))
{
 $error = '';
 $success = '';
 $firma_kodu = '';
 $firma_adi = '';
 $alis_iskonto = '';
 $max_iskonto = '';

 
 if(empty($_POST["firma_kodu"]))
 {
  $error .= '<p>firma kodu lazim</p>';
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


 if($error == '')
 {
  $data = array(
   ':name'   => $name,
   ':firma_adi'  => $firma_adi,
   ':gender'  => $gender,
   ':alis_iskonto' => $alis_iskonto,
   ':max_iskonto'   => $max_iskonto,
  
  );

  $query = "
  INSERT INTO tbl_employee 
  (name, firma_adi, gender, alis_iskonto, max_iskonto) 
  VALUES (:name, :firma_adi, :gender, :alis_iskonto, :max_iskonto)
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