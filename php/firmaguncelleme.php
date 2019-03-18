<html>
<head> <title>Dosya yükleme</title> <meta charset="utf-8">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Webslesson Tutorial | Datatables Jquery Plugin with Php MySql and Bootstrap</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
           <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
           <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  

</head>
<body>


<br /><br />  
           <div class="container">  
                <h3 align="center">Firma güncelleme</h3>  
                <br />  
                <div class="table-responsive">  
                     <table id="employee_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                    <td>Firma kodu</td>  
                                    <td>firma adi</td>  
                                    <td>Alis iskonto</td>  
                                    <td>Max iskonto</td>  
                                  
                               </tr>  
                          </thead>  
                          <?php  
                          
            $connect = mysqli_connect("localhost", "forevco_forev", "8TqIH#_6c+=$", "forevco_katalog");
            $connect->set_charset("utf8");
            $query ="Select * from firmalar";  
            $result = mysqli_query($connect, $query);  
 
 
                          while($row = mysqli_fetch_array($result))  
                          {  
                               echo '  
                               <tr>  
                                     <td>'.$row["firma_kodu"].'</td>  
                                     <td>'.$row["firma_adi"].'</td>  
                                     <td>'.$row["alis_iskonto"].'</td>  
                                     <td>'.$row["max_iskonto"].'</td>  
                                
    
                               </tr>  
                               ';  
                          }  
                          ?>  
                     </table>  
                </div>  
           </div>  







</body>
</html>