<html>
<head>
<title>Dosya yükleme</title><meta charset="utf-8">
</head>
<body> <center>
<?php
if(isset($_FILES['dosya'])){
   $hata = $_FILES['dosya']['error'];
   if($hata != 0) {
      echo 'Yüklenirken bir hata gerçekleşmiş.';
   } else {
      $boyut = $_FILES['dosya']['size'];
      if($boyut > (1024*1024*6)){
         echo 'Dosya 6MB den büyük olamaz.';
      } else {
         $tip = $_FILES['dosya']['type'];
         $isim = $_FILES['dosya']['name'];
         $uzanti = explode('.', $isim);
         $uzanti = $uzanti[count($uzanti)-1];
         if($uzanti == 'xls' || $uzanti == 'xlsx') {
         	$dosya = $_FILES['dosya']['tmp_name'];
            copy($dosya, 'yuklenendosyalar/' . 'liste.xlsx');
            echo 'Dosyanız upload edildi!';

//baglanti
            $db=new mysqli("localhost", "forevco_forev", "8TqIH#_6c+=$", "forevco_katalog");

if($db->connect_error)
    die($db->connect_error);
else
    echo "baglanti akıyor";

$db->set_charset("utf8");
//tablo ismi aşağıda yazılmak zorunda
$stmt=$db->prepare("insert into stoksayim_verisi set stok_id=? , fatura_no=?, firma_kodu=?, stok_kodu=?, stok_adi=? ,barkod=? , stok_adet=?");

if(!$stmt){
    throw new Exception($stmt->error);
}


require_once "PHPExcel-1.8/Classes/PHPExcel.php";
$tmpfname = "yuklenendosyalar/liste.xlsx";
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();

echo "<table>";
for ($row = 2; $row <= $lastRow; $row++) {
    echo "<tr><td>";
    echo $stok_id=$worksheet->getCell('A'.$row)->getValue();
    echo "</td><td>";
    echo $fatura_no=$worksheet->getCell('B'.$row)->getValue();
    echo "</td><td>";
    echo $firma_kodu=$worksheet->getCell('C'.$row)->getValue();
    echo "</td><td>";
    echo $stok_kodu=$worksheet->getCell('D'.$row)->getValue();
    echo "</td><td>";
    echo $stok_adi=$worksheet->getCell('E'.$row)->getValue(bigint);
    echo "</td><td>";
    echo $barkod=$worksheet->getCell('F'.$row)->getValue();
    echo "</td><td>";
    echo $stok_adet=$worksheet->getCell('G'.$row)->getValue();
    echo "</td><tr>";
    $stmt->bind_param("iisssii",$stok_id, $fatura_no, $firma_kodu , $stok_kodu ,$stok_adi, $barkod, $stok_adet);
    //$stmt->bindParam("barkod", $barkod, PDO::PARAM_INT);
    $kayit=$stmt->execute();
}
echo "</table>";

$db->close();


         } else {
            
            echo 'Dosya Excel olmalidir';
         }
      }
   }
}
?>
</center>
</body>
</html>