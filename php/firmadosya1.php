
<?php

//baglanti
  $db=new mysqli("localhost", "forevco_forev", "8TqIH#_6c+=$", "forevco_katalog");

if($db->connect_error)
    die($db->connect_error);
else
    echo "baglanti akıyor";


$db->set_charset("utf8");
//tablo ismi aşağıda yazılmak zorunda
$stmt=$db->prepare("insert into firmalar set  firma_kodu=?, firma_adi=?, alis_iskonto=?, max_iskonto=? ,aktiflik=? ");

if(!$stmt){
    throw new Exception($stmt->error);
}


require_once "PHPExcel-1.8/Classes/PHPExcel.php";
$tmpfname = "firma.xlsx";
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();

echo "<table>";
for ($row = 2; $row <= $lastRow; $row++) {
    echo "<tr><td>";
    echo $firma_kodu=$worksheet->getCell('A'.$row)->getValue();
    echo "</td><td>";
    echo $firma_adi=$worksheet->getCell('B'.$row)->getValue();
    echo "</td><td>";
    echo $alis_iskonto=$worksheet->getCell('C'.$row)->getValue();
    echo "</td><td>";
    echo $max_iskonto=$worksheet->getCell('D'.$row)->getValue();
    echo "</td><td>";
    echo $aktiflik=$worksheet->getCell('E'.$row)->getValue();
    /*echo "</td><td>";
    echo $kdv_oran=$worksheet->getCell('G'.$row)->getValue();
    echo "</td><td>";
    echo $kdv_dahil_satis_fiyat=$worksheet->getCell('H'.$row)->getValue(bigint);
    echo "</td><td>";
    echo $doviz_cins=$worksheet->getCell('I'.$row)->getValue();
    echo "</td><td>";
    echo $sabit_iskonto=$worksheet->getCell('J'.$row)->getValue();
    echo "</td><td>";
    echo $max_iskonto=$worksheet->getCell('K'.$row)->getValue();
    echo "</td><td>";
    echo $stok_adet=$worksheet->getCell('L'.$row)->getValue();
    echo "</td><td>";
    echo $guncelleme_tarih=$worksheet->getCell('M'.$row)->getValue();*/
    echo "</td><tr>";
    $stmt->bind_param("ssiii", $firma_kodu, $firma_adi , $alis_iskonto ,$max_iskonto, $aktiflik);
    //$stmt->bindParam("barkod", $barkod, PDO::PARAM_INT);
    $kayit=$stmt->execute();
}
echo "</table>";

$db->close();


      
   

?>
