

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

  $error .= '<p>Firma kodu olmalı</p>';

 }

 else

 {

  $firma_adi = $_POST["firma_adi"];

 }

 if(empty($_POST["firma_adi"]))

 {

  $error .= '<p>Firma adı olmalı</p>';

 }

 else

 {

  $address = $_POST["Firma_adi"];

 }

 

 if($error == '')

 {

  $data = array(

   ':firma_kodu'   => $firma_kodu,

   ':firma_adi'  => $firma_adi,

   ':alis_iskonto'  => $alis_iskonto,

   ':max_iskonto' => $max_iskonto,

  );



  $query = "

  INSERT INTO firmalar 

  (firma_kodu, firma_adi, alis_iskonto, max_iskonto) 

  VALUES (:firma_kodu, :firma_adi, :alis_iskonto, :max_iskonto)

  ";

  $statement = $connect->prepare($query);

  $statement->execute($data);

  $success = 'Firma Kayit Edildi';

 }

 $output = array(

  'success'  => $success,

  'error'   => $error

 );

 echo json_encode($output);

}



?>

