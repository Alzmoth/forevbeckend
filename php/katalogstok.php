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
      if($boyut > (1024*1024*10)){
         echo 'Dosya 10MB den büyük olamaz.';
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
$stmt=$db->prepare("insert into urunler set firma_kodu=? , stok_kodu=?, stok_adi=?, foto_adi=?, barkod=? ,stok_olcu_birim=? , kdv_oran=?, kdv_dahil_satis_fiyat=?, doviz_cins=?, stok_adet=?, guncelleme_tarih=?");

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
    echo $firma_kodu=$worksheet->getCell('A'.$row)->getValue();
    echo "</td><td>";
    echo $stok_kodu=$worksheet->getCell('B'.$row)->getValue();
    echo "</td><td>";
    echo $stok_adi=$worksheet->getCell('C'.$row)->getValue();
    echo "</td><td>";
    echo $foto_adi=$worksheet->getCell('D'.$row)->getValue();
    echo "</td><td>";
    echo $barkod=$worksheet->getCell('E'.$row)->getValue(bigint);
    echo "</td><td>";
    echo $stok_olcu_birim=$worksheet->getCell('F'.$row)->getValue();
    echo "</td><td>";
    echo $kdv_oran=$worksheet->getCell('G'.$row)->getValue();
    echo "</td><td>";
    echo $kdv_dahil_satis_fiyat=$worksheet->getCell('H'.$row)->getValue(bigint);
    echo "</td><td>";
    echo $doviz_cins=$worksheet->getCell('I'.$row)->getValue();
    echo "</td><td>";
    echo $stok_adet=$worksheet->getCell('L'.$row)->getValue();
    echo "</td><td>";
    echo $guncelleme_tarih=$worksheet->getCell('M'.$row)->getValue();
    echo "</td><tr>";
    $stmt->bind_param("ssssisissii",$firma_kodu, $stok_kodu, $stok_adi , $foto_adi ,$barkod, $stok_olcu_birim, $kdv_oran, $kdv_dahil_satis_fiyat, $doviz_cins, $stok_adet, $guncelleme_tarih);
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