
<?php  
 $connect = mysqli_connect("localhost", "forevco_forev", "8TqIH#_6c+=$", "forevco_katalog");  
  $connect->set_charset("utf8");
 $query ="SELECT * FROM urunler ORDER BY stok_kodu DESC";  
 $result = mysqli_query($connect, $query);  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
      <meta charset="utf-8">
           <title>Firma Güncelleme</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
      </head>  
      <body>  
           <br /><br />  
           <div class="container">  
                <h3 align="center">Firma ve Ürün Güncelleme</h3>  
                <br />  
                <div class="table-responsive">  
                     <table id="firmalar" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                    <td>Firma Kodu</td>  
                                    <td>Firma Adı</td>  
                                    <td>Alış İskonto</td>  
                                    <td>Max İskonto</td>  
                                    <td>Aktiflik</td>  
                               </tr>  
                          </thead>  
                          <?php  
                          while($row = mysqli_fetch_array($result))  
                          {  
                               echo '  
                               <tr>  
                                    <td>'.$row["stok_kodu"].'</td>  
                                    <td>'.$row["stok_adi"].'</td>  
                                    <td>'.$row["barkod"].'</td>  
                                    <td>'.$row["kdv_oran"].'</td>  
                                    <td>'.$row["kdv_dahil_satis_fiyat"].'</td>  
                               </tr>  
                               ';  
                          }  
                          ?>  
                     </table>  
                </div>  
           </div>  
      </body>  
 </html>  
 <script>  
 $(document).ready(function(){  
      $('#firmalar').DataTable();  
 });  
 </script>  