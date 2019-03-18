<?php

//fetch_single.php

include('database_connection.php');

if(isset($_GET["id"]))
{
 $query = "SELECT * FROM firmalar WHERE id = '".$_GET["firma_id"]."'";

 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 $output = '<div class="row">';
 foreach($result as $row)
 {
  
  $output .= '
  <div >
   <br />
   <p><label>Firma Kodu :&nbsp;</label>'.$row["firma_kodu"].'</p>
   <p><label>firma Adi :&nbsp;</label>'.$row["firma_adi"].'</p>
   <p><label>Alis İskontosu :&nbsp;</label>'.$row["alis_iskonto"].'</p>
   <p><label>Satıcının vereceği Max iskonto :&nbsp;</label>'.$row["max_iskonto"].'</p>
   
  </div>
  </div><br />
  ';
 }
 echo $output;
}

?>
