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



 if(empty($_POST["firma_kodu"]))

 {

  $error .= '<p>Firma Kodu Zorunlu</p>';

 }

 else

 {

  $name = $_POST["firma_kodu"];

 }


 if($error == '')

 {

  $data = array(

   ':firma_kodu'   => $firma_kodu,

   ':firma_adi'  => $firma_adi,

   ':alis_iskonto'  => $alis_iskonto,

   ':max_iskonto' => $max_iskonto,



   ':id'   => $_POST["id"]

  );



  $query = "

  UPDATE firmalar 

  SET firma_kodu = :firma_kodu,

  firma_adi = :firma_adi,

  alis_iskonto = :alis_iskonto, 

  max_iskonto = :max_iskonto, 



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



