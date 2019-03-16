<?php
require 'config.php';
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->post('/login','login'); /* User login */
$app->post('/signup','signup'); /* User Signup  */
$app->post('/feed','feed'); /* User Feeds  */
$app->post('/feedUpdate','feedUpdate'); /* User Feeds  */
$app->post('/feedDelete','feedDelete'); /* User Feeds  */
$app->post('/kategori','kategori'); /* User Feeds  */
$app->post('/stok_sayim','stok_sayim'); /* User Feeds  */
$app->post('/stok_kayit_id','stok_kayit_id'); /* User Feeds  */
$app->post('/stok_kayit','stok_kayit'); /* User Feeds  */
$app->post('/stok_listesi_getir','stok_listesi_getir'); /* User Feeds  */
$app->post('/stok_liste_detay_getir','stok_liste_detay_getir');
$app->post('/stok_list_sil','stok_list_sil');
$app->post('/stok_guncelle','stok_guncelle');
$app->post('/siparis_satis','siparis_satis'); /* User Feeds  */
$app->post('/siparis_kategori','siparis_kategori'); /* User Feeds  */
$app->post('/siparis_fatura_kayit','siparis_fatura_kayit');  // faturasi kaydi
$app->post('/siparis_kayit','siparis_kayit');
$app->post('/siparis_listesi_getir','siparis_listesi_getir');
$app->post('/siparis_liste_detay_getir','siparis_liste_detay_getir');
$app->post('/siparis_list_sil','siparis_list_sil');
$app->post('/siparis_guncelle','siparis_guncelle');
$app->post('/toplam_fiyat','toplam_fiyat');
$app->post('/detay_katagori','detay_katagori');
$app->post('/siparis_sepet_kaydet','siparis_sepet_kaydet');
$app->post('/siparis_sepet_sil','siparis_sepet_sil');
$app->post('/siparis_sepet_getir','siparis_sepet_getir');
$app->post('/stok_sepet_kaydet','stok_sepet_kaydet');
$app->post('/stok_sepet_sil','stok_sepet_sil');
$app->post('/stok_sepet_getir','stok_sepet_getir'); 
$app->post('/siparis_urun_sil','siparis_urun_sil');
$app->post('/cari_olustur','cari_olustur');
$app->post('/cari_cek','cari_cek');
$app->post('/cari_guncelle','cari_guncelle');


$app->run();

/************************* USER LOGIN *************************************/

function login() {
    
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    try {
        
        $db = getDB();
        $userData ='';
        $sql = "SELECT user_id, name, email, username FROM users WHERE (username=:username or email=:username) and password=:password ";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("username", $data->username, PDO::PARAM_STR);
        $password=hash('sha256',$data->password);
        $stmt->bindParam("password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $mainCount=$stmt->rowCount();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);
        
        if(!empty($userData))
        {
            $user_id=$userData->user_id;
            $userData->token = apiToken($user_id);
        }
        
        $db = null;
         if($userData){
               $userData = json_encode($userData);
                echo '{"userData": ' .$userData . '}';
            } else {
               echo '{"error":{"text":"Bad request wrong username and password"}}';
            }

           
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


/* ### User registration ### */
function signup() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $email=$data->email;
    $name=$data->name;
    $username=$data->username;
    $password=$data->password;
    $sube = $sube->sube;
    try {
        
     
        
        
        if (strlen(trim($username))>0 && strlen(trim($password))>0 )
        {
            $db = getDB();
            $userData = '';
            $sql = "SELECT user_id FROM users WHERE username=:username or email=:email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("username", $username,PDO::PARAM_STR);
            $stmt->bindParam("email", $email,PDO::PARAM_STR);
            $stmt->execute();
            $mainCount=$stmt->rowCount();
            $created=date("l", mktime(0, 0, 0, 7, 1, 2000));
            if($mainCount==0)
            {
                
                /*Inserting user values*/
                $sql1="INSERT INTO users(username,password,email,name,sube)VALUES(:username,:password,:email,:name,:sube)";
                $stmt1 = $db->prepare($sql1);
                $stmt1->bindParam("username", $username,PDO::PARAM_STR);
                $password=hash('sha256',$data->password);
                $stmt1->bindParam("password", $password,PDO::PARAM_STR);
                $stmt1->bindParam("email", $email,PDO::PARAM_STR);
                $stmt1->bindParam("name", $name,PDO::PARAM_STR);
                $stmt1->bindParam("sube", $sube,PDO::PARAM_STR);
                $stmt1->execute();
                
                $userData=internalUserDetails($email);
                
            }
            
            $db = null;
         

            if($userData){
               $userData = json_encode($userData);
                echo '{"userData": ' .$userData . '}';
            } else {
               echo '{"error":{"text":"Enter valid data"}}';
            }

           
        }
        else{
            echo '{"error":{"text":"Enter valid data"}}';
        }
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function email() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $email=$data->email;

    try {
       
        $email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
       
        if (strlen(trim($email))>0 && $email_check>0)
        {
            $db = getDB();
            $userData = '';
            $sql = "SELECT user_id FROM emailUsers WHERE email=:email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("email", $email,PDO::PARAM_STR);
            $stmt->execute();
            $mainCount=$stmt->rowCount();
            $created=time();
            if($mainCount==0)
            {
                
                /*Inserting user values*/
                $sql1="INSERT INTO emailUsers(email)VALUES(:email)";
                $stmt1 = $db->prepare($sql1);
                $stmt1->bindParam("email", $email,PDO::PARAM_STR);
                $stmt1->execute();
                
                
            }
            $userData=internalEmailDetails($email);
            $db = null;
            if($userData){
               $userData = json_encode($userData);
                echo '{"userData": ' .$userData . '}';
            } else {
               echo '{"error":{"text":"Enter valid dataaaa"}}';
            }
        }
        else{
            echo '{"error":{"text":"Enter valid data"}}';
        }
    }
    
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


/* ### internal Username Details ### */
function internalUserDetails($input) {
    
    try {
        $db = getDB();
        $sql = "SELECT user_id, name, email, username FROM users WHERE username=:input or email=:input";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("input", $input,PDO::PARAM_STR);
        $stmt->execute();
        $usernameDetails = $stmt->fetch(PDO::FETCH_OBJ);
        $usernameDetails->token = apiToken($usernameDetails->user_id);
        $db = null;
        return $usernameDetails;
        
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}

function feed(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
            $feedData = '';
            $db = getDB();
            $sql = "SELECT * FROM feed WHERE user_id_fk=:user_id ORDER BY feed_id DESC";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
    
    
}

function feedUpdate(){

    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    $feed=$data->feed;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
         
            
            $feedData = '';
            $db = getDB();
            $sql = "INSERT INTO feed ( feed, created, user_id_fk) VALUES (:feed,:created,:user_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("feed", $feed, PDO::PARAM_STR);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $created = time();
            $stmt->bindParam("created", $created, PDO::PARAM_INT);
            $stmt->execute();
            


            $sql1 = "SELECT * FROM feed WHERE user_id_fk=:user_id ORDER BY feed_id DESC LIMIT 1";
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt1->execute();
            $feedData = $stmt1->fetch(PDO::FETCH_OBJ);


            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }

}

function feedDelete(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    $feed_id=$data->feed_id;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
            $feedData = '';
            $db = getDB();
            $sql = "Delete FROM feed WHERE user_id_fk=:user_id AND feed_id=:feed_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam("feed_id", $feed_id, PDO::PARAM_INT);
            $stmt->execute();
            
           
            $db = null;
            echo '{"success":{"text":"Feed deleted"}}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}



function kategori(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $search=$data->feed_id;

    
  
   
    try {
         
       
            $feedData = '';
            $db = getDB();
           // $sql = "SELECT * FROM firmalar WHERE p_category=:cat_id";
        
            $sql="Select firma_kodu, firma_adi from firmalar ORDER BY firma_adi";

            $stmt = $db->prepare($sql);
            //$stmt->bindParam("feed", $search, PDO::PARAM_STR);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            
            echo '{"feedData": ' . json_encode($feedData) . '}';
        
            //echo '{"error":{"text":"No access"}}';
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
   
}
function stok_sayim(){

$request = \Slim\Slim::getInstance()->request();
$data = json_decode($request->getBody());
$search=$data->search;
$kategori = $data->kategori;
$sayac=$data->sayac;
$sayacson=$sayac+50;

    try {
            $feedData = '';
            $db = getDB();
           
           if($search && $kategori){
                $sql="Select * from stoksayim where firma_kodu IN ( select firma_kodu from firmalar where firma_kodu like '%$kategori%')and (stok_adi like '%$search%' OR stok_kodu like '%$search%' OR barkod like '%$search%' ) limit $sayac,$sayacson ";
           
            }else if($search){
                $sql="Select * from stoksayim where stok_adi like '%$search%' OR stok_kodu like '%$search%' OR barkod like '%$search%' limit $sayac,$sayacson ";
            }else if($kategori){
                 $sql="Select * from stoksayim where firma_kodu like '$kategori' limit $sayac, $sayacson ";
            }
            
            else {
               $sql="Select * from stoksayim limit $sayac,$sayacson ";
                
           }
            
           $stmt = $db->prepare($sql);
           
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            if(feedData){
                 echo '{"feedData": ' . json_encode($feedData) . '}';
                
            }
            else{
                echo '{"feedData": ""}';
            }
           
        
            //echo '{"error":{"text":"No access"}}';
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
   
}

 function stok_kayit_id(){ //fatura no kay覺t edilen yer
      $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    $aciklama=$data->aciklama;

    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
         
            
            $feedData = '';
            $db = getDB();
            $sql = "INSERT INTO stok_faturasi ( fatura_tarih, user_id, aciklama) VALUES (:fatura_tarih,:user_id,:aciklama)";
            $stmt = $db->prepare($sql);
            
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam("aciklama",$aciklama,PDO::PARAM_STR);
            $fatura_tarih = time();
            $stmt->bindParam("fatura_tarih", $fatura_tarih, PDO::PARAM_INT);
            $stmt->execute();
         


            $sql1 = "SELECT fatura_no FROM stok_faturasi ORDER BY fatura_no DESC limit 0,1";
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt1->execute();
            $feedData = $stmt1->fetch(PDO::FETCH_OBJ);


            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
     
     
 }
 
 function stok_kayit(){
      $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $barkod = $data->barkod;
    $firma_kodu =$data->firma_kodu;
    $stok_adet =$data->stok_adet;
    $stok_adi = $data->stok_adi;
    $stok_kodu = $data->stok_kodu;
    $fatura_no = $data->fatura_no;
    
    
   
    try {
         
            $feedData = '';
            $db = getDB();
            
             
            
            $sql = "INSERT INTO stoksayim_verisi ( fatura_no, firma_kodu, stok_kodu, stok_adi, stok_adet, barkod) VALUES (:fatura_no, :firma_kodu,:stok_kodu, :stok_adi, :stok_adet, :barkod)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("fatura_no", $fatura_no, PDO::PARAM_INT);
            $stmt->bindParam("firma_kodu", $firma_kodu, PDO::PARAM_STR);
            $stmt->bindParam("stok_kodu", $stok_kodu, PDO::PARAM_STR);
            $stmt->bindParam("stok_adi", $stok_adi, PDO::PARAM_STR);
            $stmt->bindParam("stok_adet", $stok_adet, PDO::PARAM_INT);
            $stmt->bindParam("barkod", $barkod, PDO::PARAM_INT);
            
            $stmt->execute();
            
            $sql1 = "SELECT * FROM stoksayim_verisi ORDER BY fatura_no DESC ";
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt1->execute();
            $feedData = $stmt1->fetch(PDO::FETCH_OBJ);


            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
     
     
 }
 
 
 
 function stok_listesi_getir(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $sayac=$data->sayac;
    $sayacson=$sayac+50;

   
    try {   $feedData = '';
            $db = getDB();
            
            


           
            $sql= "select stok_faturasi.fatura_no , users.username, stok_faturasi.fatura_tarih, stok_faturasi.aciklama from stok_faturasi INNER JOIN users ON stok_faturasi.user_id=users.user_id ORDER BY stok_faturasi.fatura_no DESC limit $sayac,$sayacson" ;
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam("isis", $fatura_no,$user_name ,$fatura_tarih,$aciklama);
          

            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            if(feedData){
                 echo '{"feedData": ' . json_encode($feedData) . '}';
                
            }
            else{
                echo '{"feedData": ""}';
            }
        
            //echo '{"error":{"text":"No access"}}';
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
   
}

function stok_liste_detay_getir(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $sorulan_fatura=$data->fatura_no;
    
   
    try {
         
            $feedData = '';
            
            $db = getDB();
           
     
         $sql="Select * FROM stoksayim_verisi WHERE fatura_no=:sorulan_fatura";
           
            $stmt = $db->prepare($sql);
            $stmt->bindParam("sorulan_fatura", $sorulan_fatura, PDO::PARAM_INT);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            
            echo '{"feedData": ' . json_encode($feedData) . '}';
        
            //echo '{"error":{"text":"No access"}}';
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
   
}

function stok_list_sil(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $fatura_no=$data->fatura_no;
   
    

   
    try {
         
       
            $feedData = '';
            $db = getDB();
            $sql = "Delete FROM stok_faturasi WHERE fatura_no=:fatura_no";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("fatura_no",$fatura_no , PDO::PARAM_INT);
            $stmt->execute();
            $sql1 = "Delete FROM stoksayim_verisi WHERE fatura_no=:fatura_no";
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindParam("fatura_no",$fatura_no , PDO::PARAM_INT);
            $stmt1->execute();
            
           
            $db = null;
            echo '{"success":{"text":"Feed deleted"}}';
   
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}
function stok_guncelle(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $stok_id=$data->stok_id;
    $stok_adet=$data->stok_adet;
    
   
    try {
         
       
            $db = getDB();
            $sql = "UPDATE stoksayim_verisi set stok_adet=:stok_adet WHERE stok_id=:stok_id ";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("stok_adet",$stok_adet , PDO::PARAM_INT);
            $stmt->bindParam("stok_id",$stok_id , PDO::PARAM_INT);
            $stmt->execute();
            
            
           
            $db = null;
            echo '{"success":{"text":"Stok updated "}}';
   
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


function siparis_satis(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $search=$data->search;
    $kategori = $data->kategori;
    $sayac=$data->sayac;
    $sayacson=$sayac+50;
    $sube=$data->sube;

    try {
              $feedData = '';
            
            $db = getDB();
           
        if($sube==null){
       
           if($search && $kategori ){
                $sql="Select * from urunler where firma_kodu IN ( select firma_kodu from firmalar where firma_kodu like '%$kategori%')and (stok_adi like '%$search%' OR stok_kodu like '%$search%' OR barkod like '%$search%' ) limit $sayac,$sayacson ";
           
           
                
            }else if($search){
                $sql="Select * from urunler where stok_adi like '%$search%' OR stok_kodu like '%$search%' OR barkod like '%$search%' limit $sayac,$sayacson ";
                
            }else if($kategori){
                 $sql="Select * from urunler where firma_kodu like '$kategori'  limit $sayac, $sayacson";
            }
            
            else {
               $sql="Select * from urunler limit $sayac,$sayacson ";
           }
           
           
            }else{   
         if($search && $kategori ){
                $sql="Select * from urunler where firma_kodu IN ( select firma_kodu from firmalar where $sube AND firma_kodu like '%$kategori%')and (stok_adi like '%$search%' OR stok_kodu like '%$search%' OR barkod like '%$search%' ) limit $sayac,$sayacson ";
           
           
                
            }else if($search){
                $sql="Select * from urunler where $sube AND stok_adi like '%$search%' OR stok_kodu like '%$search%' OR barkod like '%$search%' limit $sayac,$sayacson ";
                
            }else if($kategori){
                 $sql="Select * from urunler where $sube AND firma_kodu like '$kategori'  limit $sayac, $sayacson";
            }
            
            else {
               $sql="Select * from urunler where $sube limit $sayac,$sayacson ";
           }
    
    
    
    
}







            $stmt = $db->prepare($sql);
           
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            if(feedData){
                 echo '{"feedData": ' . json_encode($feedData) . '}';
                
            }
            else{
                echo '{"feedData": ""}';
            }
           
        
            //echo '{"error":{"text":"No access"}}';
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
   
}

function siparis_kategori(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $search=$data->feed_id;

    
    try {
       
            $feedData = '';
            $db = getDB();
           // $sql = "SELECT * FROM firmalar WHERE p_category=:cat_id";
        
            $sql="Select firma_kodu, firma_adi from firmalar ORDER BY firma_adi";

            $stmt = $db->prepare($sql);
            //$stmt->bindParam("feed", $search, PDO::PARAM_STR);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            
            echo '{"feedData": ' . json_encode($feedData) . '}';
        
            //echo '{"error":{"text":"No access"}}';
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function siparis_fatura_kayit(){ //fatura no kay覺t edilen yer
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $cari_id=$data->cari_id;
    $token=$data->token;
    $aciklama=$data->aciklama;
    $toplam_fiyat=$data->toplam_fiyat;
    $urun_adedi=$data->urun_adedi;
    $genel_iskonto=$data->genel_iskonto;

    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
         
            
            $feedData = '';
            $db = getDB();
            $sql = "INSERT INTO satis_faturası ( fatura_tarih, user_id, toplam_fiyat,urun_adedi,genel_iskonto, aciklama,cari_id) VALUES (:fatura_tarih,:user_id,:toplam_fiyat,:urun_adedi,:genel_iskonto,:aciklama,:cari_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id",$user_id , PDO::PARAM_INT);
            $stmt->bindParam("toplam_fiyat", $toplam_fiyat, PDO::PARAM_INT);
            $stmt->bindParam("urun_adedi", $urun_adedi, PDO::PARAM_INT);
            $stmt->bindParam("genel_iskonto", $genel_iskonto, PDO::PARAM_INT);
            $stmt->bindParam("aciklama",$aciklama,PDO::PARAM_STR);
            $stmt->bindParam("cari_id",$cari_id,PDO::PARAM_STR);
            $fatura_tarih = time();
            $stmt->bindParam("fatura_tarih", $fatura_tarih, PDO::PARAM_INT);
            $stmt->execute();
         


            $sql1 = "SELECT fatura_no FROM satis_faturası ORDER BY fatura_no DESC limit 0,1";
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt1->execute();
            $feedData = $stmt1->fetch(PDO::FETCH_OBJ);


            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
 }
 
 function siparis_kayit(){
      $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $barkod = $data->barkod;
    $firma_kodu =$data->firma_kodu;
    $stok_adet =$data->urun_adet;
    $stok_adi = $data->stok_adi;
    $stok_kodu = $data->stok_kodu;
    $fatura_no = $data->fatura_no;
    $kdvsiz_satis_fiyat = $data->kdvsiz_satis_fiyat;
    $sabit_iskonto = $data->sabit_iskonto;
    $iskontolu_satis_fiyati= $satis_fiyati - (($satis_fiyati/100)* $sabit_iskonto);
    
    
    
   
    try {
         
            $feedData = '';
            $db = getDB();
            
             
            
            $sql = "INSERT INTO satis_verisi ( fatura_no, firma_kodu, stok_kodu, stok_adi,satis_fiyati,sabit_iskonto, stok_adet, barkod, kdvsiz_satis_fiyat) VALUES (:fatura_no, :firma_kodu,:stok_kodu, :stok_adi,:satis_fiyati,:sabit_iskonto,:stok_adet, :barkod, kdvsiz_satis_fiyat=:kdvsiz_satis_fiyat)";
            $stmt = $db->prepare($sql);
           
            $stmt->bindParam("fatura_no", $fatura_no, PDO::PARAM_INT);
            $stmt->bindParam("kdvsiz_satis_fiyat", $kdvsiz_satis_fiyat, PDO::PARAM_INT);
            $stmt->bindParam("firma_kodu", $firma_kodu, PDO::PARAM_STR);
            $stmt->bindParam("stok_kodu", $stok_kodu, PDO::PARAM_STR);
            $stmt->bindParam("stok_adi", $stok_adi, PDO::PARAM_STR);
            $stmt->bindParam("satis_fiyati", $satis_fiyati, PDO::PARAM_INT);
            $stmt->bindParam("sabit_iskonto", $sabit_iskonto, PDO::PARAM_INT);
            $stmt->bindParam("stok_adet", $stok_adet, PDO::PARAM_INT);
            $stmt->bindParam("barkod", $barkod, PDO::PARAM_INT);
            
            $stmt->execute();
            
            $sql1 = "SELECT * FROM stoksayim_verisi ORDER BY fatura_no DESC ";
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt1->execute();
            $feedData = $stmt1->fetch(PDO::FETCH_OBJ);


            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
     
     
 }
 
 
 
 function siparis_listesi_getir(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $sayac=$data->sayac;
    $sayacson=$sayac+50;

   
    try {   $feedData = '';
            $db = getDB();
            
            


           
            $sql= "select satis_faturası.fatura_no, satis_faturası.toplam_fiyat,satis_faturası.urun_adedi, users.username, satis_faturası.fatura_tarih, satis_faturası.aciklama from satis_faturası INNER JOIN users ON satis_faturası.user_id=users.user_id ORDER BY satis_faturası.fatura_no DESC limit $sayac,$sayacson" ;
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam("iiisis", $fatura_no,$toplam_fiyat,$urun_adedi,$user_name ,$fatura_tarih,$aciklama);
          

            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
               if(feedData){
                 echo '{"feedData": ' . json_encode($feedData) . '}';
                
            }
            else{
                echo '{"feedData": ""}';
            }
        
            //echo '{"error":{"text":"No access"}}';
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
   
}

function siparis_liste_detay_getir(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $sorulan_fatura=$data->fatura_no;
    

    
  
   
    try {
         
            $feedData = '';
            
            $db = getDB();
           
     
         $sql="Select  * FROM satis_verisi WHERE fatura_no=:sorulan_fatura";
           
            $stmt = $db->prepare($sql);
            $stmt->bindParam("sorulan_fatura", $sorulan_fatura, PDO::PARAM_INT);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            
            echo '{"feedData": ' . json_encode($feedData) . '}';
        
            //echo '{"error":{"text":"No access"}}';
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
   
}

function siparis_list_sil(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $fatura_no=$data->fatura_no;
   
    

   
    try {
         
       
            $feedData = '';
            $db = getDB();
            $sql = "Delete FROM satis_faturası WHERE fatura_no=:fatura_no";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("fatura_no",$fatura_no , PDO::PARAM_INT);
            $stmt->execute();
            
            
           
            $db = null;
            echo '{"success":{"text":"Feed deleted"}}';
   
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}

function siparis_guncelle(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $stok_id=$data->stok_id;
    $stok_adet=$data->stok_adet;
    $sabit_iskonto=$data->sabit_iskonto;
    $satis_fiyati=$data->satis_fiyati;
    $iskontolu_satis_fiyati= $satis_fiyati - (($satis_fiyati/100)* $sabit_iskonto);
    
    	
   
    try {
       
         
            
            $db = getDB();
            $sql = "UPDATE satis_verisi SET stok_adet=:stok_adet, sabit_iskonto=:sabit_iskonto, iskontolu_satis_fiyati=:iskontolu_satis_fiyati WHERE stok_id=:stok_id ";
            $stmt = $db->prepare($sql);
            
            $stmt->bindParam("sabit_iskonto",$sabit_iskonto , PDO::PARAM_INT);
            $stmt->bindParam("iskontolu_satis_fiyati",$iskontolu_satis_fiyati , PDO::PARAM_INT);
            $stmt->bindParam("stok_adet",$stok_adet , PDO::PARAM_INT);
           
            $stmt->bindParam("stok_id",$stok_id , PDO::PARAM_INT);
            
            $stmt->execute();
            
           
            $db = null;
            echo '{"success":{"text":"Stok updated "}}';
   
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function toplam_fiyat(){ //fatura no kayit edilen yer
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $fatura_no=$data->fatura_no;
    $toplam_fiyat=$data->toplam_fiyat;
    $urun_adedi=$data->toplam_urun;


    
 
   
    try {
         
       
         
            
            $feedData = '';
            $db = getDB();
            $sql = "UPDATE satis_faturası SET toplam_fiyat=:toplam_fiyat, urun_adedi=:urun_adedi WHERE fatura_no=:fatura_no ";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("fatura_no", $fatura_no, PDO::PARAM_INT);
            $stmt->bindParam("urun_adedi", $urun_adedi, PDO::PARAM_INT);
            $stmt->bindParam("toplam_fiyat", $toplam_fiyat, PDO::PARAM_INT);
            $stmt->execute();


            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
       
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
 }
 
 function detay_katagori(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $firma_kodu=$data->firma_kodu;
    

    
  
   
    try {
         
            $feedData = '';
            
            $db = getDB();
           
     
         $sql="Select * FROM firmalar WHERE firma_kodu=:firma_kodu";
           
            $stmt = $db->prepare($sql);
            $stmt->bindParam("firma_kodu", $firma_kodu, PDO::PARAM_INT);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            
            echo '{"feedData": ' . json_encode($feedData) . '}';
        
            //echo '{"error":{"text":"No access"}}';
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
function siparis_sepet_kaydet(){
      $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $user_id=$data->user_id;
    $token=$data->token;
    $systemToken=apiToken($user_id);
    $barkod = $data->barkod;
    $firma_kodu =$data->firma_kodu;
    $firma_adi=$data->firma_adi;
    $urun_adet =$data->urun_adet;
    $stok_adi = $data->stok_adi;
    $stok_kodu = $data->stok_kodu;
    $foto_adi = $data->foto_adi;
    $olcu_birim= $data->stok_olcu_birim;
    $kdv_oran = $data->kdv_oran;
    $kdvsiz_satis_fiyat=$data->kdvsiz_satis_fiyat;
    $sabit_iskonto = $data->sabit_iskonto;
    $max_iskonto=$data->max_iskonto;
    $aciklama=$aciklama->aciklama;
    
    
   
    try {
          if($systemToken == $token){
            $feedData = '';
            $db = getDB();
            
             
            
            $sql = "INSERT INTO siparis_sepet ( user_id, firma_kodu,firma_adi, stok_kodu, stok_adi,foto_adi, olcu_birim , kdv_oran,kdvsiz_satis_fiyat ,sabit_iskonto, max_iskonto , urun_adet, barkod,aciklama   
            ) VALUES (:user_id,  :firma_kodu,:firma_adi,:stok_kodu, :stok_adi,:foto_adi,:olcu_birim,:kdv_oran, :kdvsiz_satis_fiyat, :sabit_iskonto,:max_iskonto,:urun_adet,:barkod,:aciklama)";
            $stmt = $db->prepare($sql);
           
            
            $stmt->bindParam("kdvsiz_satis_fiyat", $kdvsiz_satis_fiyat, PDO::PARAM_INT);
            $stmt->bindParam("firma_kodu", $firma_kodu, PDO::PARAM_INT);
            $stmt->bindParam("stok_kodu", $stok_kodu, PDO::PARAM_STR);
            $stmt->bindParam("firma_adi", $firma_adi, PDO::PARAM_STR);
            $stmt->bindParam("aciklama", $aciklama, PDO::PARAM_STR);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam("foto_adi", $foto_adi, PDO::PARAM_STR);
            $stmt->bindParam("olcu_birim", $olcu_birim, PDO::PARAM_STR);
            $stmt->bindParam("stok_adi", $stok_adi, PDO::PARAM_STR);
           
            $stmt->bindParam("sabit_iskonto", $sabit_iskonto, PDO::PARAM_INT);
            $stmt->bindParam("urun_adet", $urun_adet, PDO::PARAM_INT);
            $stmt->bindParam("barkod", $barkod, PDO::PARAM_INT);
            $stmt->bindParam("kdv_oran", $kdv_oran, PDO::PARAM_INT);
            $stmt->bindParam("max_iskonto", $max_iskonto, PDO::PARAM_INT);
            
            $stmt->execute();
            
        


            $db = null;
            
            echo '{"feedData": ' . json_encode($feedData) . '}';
          } else{
            echo '{"error":{"text":"No access"}}';
        }  
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
     
     
 }

function siparis_sepet_sil(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
  
    $token=$data->token;
    $systemToken=apiToken($user_id);
   

       
   
    try {
        
          if($systemToken == $token){
         
       
            $feedData = '';
            $db = getDB();
            $sql = "Delete FROM siparis_sepet WHERE user_id=:user_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id",$user_id , PDO::PARAM_INT);
            $stmt->execute();
           
           
            $db = null;
            echo '{"success":{"text":"Feed deleted"}}';
   
    
    } else{
            echo '{"error":{"text":"No access"}}';
        }  
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}
function siparis_urun_sil(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->stok_adi;
    $stok_kodu = $data->stok_kodu;

   

       
   
    try {
        
        
         
       
            $feedData = '';
            $db = getDB();
            $sql = "Delete FROM siparis_sepet WHERE user_id=:user_id and stok_kodu=:stok_kodu";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id",$user_id , PDO::PARAM_INT);
            $stmt->bindParam("stok_kodu",$stok_kodu , PDO::PARAM_INT);
            $stmt->execute();
           
           
            $db = null;
            echo '{"success":{"text":"Feed deleted"}}';
   
    

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}

function siparis_sepet_getir(){ 
   $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
            $feedData = '';
            $db = getDB();
            $sql = "SELECT * FROM siparis_sepet WHERE user_id=:user_id ORDER BY stok_id DESC ";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}
function stok_sepet_kaydet(){
      $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $user_id=$data->user_id;
    $token=$data->token;
    $systemToken=apiToken($user_id);
    $barkod = $data->barkod;
    $firma_kodu =$data->firma_kodu;
    $olcu_birim= $data->stok_olcu_birim;
    $sabit_iskonto = $data->sabit_iskonto;
    $firma_adi=$data->firma_adi;
    $stok_adet =$data->stok_adet;
    $stok_adi = $data->stok_adi;
    $stok_kodu = $data->stok_kodu;
    $satis_fiyat= $data->satis_fiyat;

    
   
    

    
    
    
   
    try {
          if($systemToken == $token){
            $feedData = '';
            $db = getDB();
            
            
             
            
            $sql = "INSERT INTO stok_sepet ( user_id, firma_kodu,firma_adi, stok_kodu, stok_adi, olcu_birim ,satis_fiyat ,sabit_iskonto , stok_adet, barkod   
            ) VALUES (:user_id,  :firma_kodu,:firma_adi,:stok_kodu, :stok_adi,:olcu_birim, :satis_fiyat, :sabit_iskonto,:stok_adet,:barkod)";
            $stmt = $db->prepare($sql);
           
            
            $stmt->bindParam("satis_fiyat", $satis_fiyat, PDO::PARAM_INT);
            $stmt->bindParam("firma_kodu", $firma_kodu, PDO::PARAM_INT);
            $stmt->bindParam("stok_kodu", $stok_kodu, PDO::PARAM_STR);
            $stmt->bindParam("firma_adi", $firma_adi, PDO::PARAM_STR);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam("olcu_birim", $olcu_birim, PDO::PARAM_STR);
            $stmt->bindParam("stok_adi", $stok_adi, PDO::PARAM_STR);
           
            $stmt->bindParam("sabit_iskonto", $sabit_iskonto, PDO::PARAM_INT);
            $stmt->bindParam("stok_adet", $stok_adet, PDO::PARAM_INT);
            $stmt->bindParam("barkod", $barkod, PDO::PARAM_INT);

            
            $stmt->execute();
            
        


            $db = null;
            
            echo '{"feedData": ' . json_encode($feedData) . '}';
          } else{
            echo '{"error":{"text":"No access"}}';
        }  
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
     
     
 }

function stok_sepet_sil(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
  
    $token=$data->token;
    $systemToken=apiToken($user_id);
   

       
   
    try {
        
          if($systemToken == $token){
         
       
            $feedData = '';
            $db = getDB();
            $sql = "Delete FROM stok_sepet WHERE user_id=:user_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id",$user_id , PDO::PARAM_INT);
            $stmt->execute();
           
           
            $db = null;
            echo '{"success":{"text":"Feed deleted"}}';
   
    
    } else{
            echo '{"error":{"text":"No access"}}';
        }  
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}
function stok_sepet_getir(){ 
   $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
            $feedData = '';
            $db = getDB();
            $sql = "SELECT * FROM stok_sepet WHERE user_id=:user_id ORDER BY stok_id DESC";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}

function cari_cek(){ 
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $search=$data->search;
    $sayac=$data->sayac;
    $sayacson=$sayac+50;

   
    try {
         
       
            $feedData = '';
            $db = getDB();
           
                
            if($search){
                $sql="Select * from cari where cari_adi like '%$search%' OR cari_tel like '%$search%' OR ilgili_adi like '%$search%' OR yetkili_adi like '%$search%' limit $sayac,$sayacson ";
            }
            
            else {
               $sql="Select * from cari limit $sayac,$sayacson ";
                
           }
            
         
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
     
        
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
}

function cari_olustur(){ 
    
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    $cari_id=$data->cari_id;
    $cari_adi=$data->cari_adi;
    $cari_adres=$data->cari_adres;
    $cari_mail=$data->cari_mail;
    $cari_tel=$data->cari_tel;
    $ilgili_adi=$data->ilgili_adi;
    $ilgili_mail=$data->ilgili_mail;
    $ilgili_tel=$data->ilgili_tel;
    $teslim_adres=$data->teslim_adres;
    $vergi_dairesi=$data->vergi_dairesi;
    $vergi_no=$data->vergi_no;
    $yetkili_adi=$data->yetkili_adi;
    $yetkili_mail=$data->yetkili_mail;
    $yetkili_tel=$data->yetkili_tel;
    

    
   
    try {
         
            $feedData = '';
            $db = getDB();
            

                
                  $sql = "INSERT INTO cari (  cari_adi, cari_adres,cari_mail,cari_tel,ilgili_adi,ilgili_mail,ilgili_tel,teslim_adres,vergi_dairesi,vergi_no,yetkili_adi,yetkili_mail,yetkili_tel , fatura_tarih
                 ) VALUES ( :cari_adi, :cari_adres, :cari_mail, :cari_tel, :ilgili_adi, :ilgili_mail, :ilgili_tel, :teslim_adres, :vergi_dairesi,:vergi_no, :yetkili_adi, :yetkili_mail, :yetkili_tel, :fatura_tarih)";
                 
                 
            
            
            $stmt = $db->prepare($sql);
           
            
           
            $stmt->bindParam("cari_adi", $cari_adi, PDO::PARAM_STR);
            $stmt->bindParam("cari_adres", $cari_adres, PDO::PARAM_STR);
            $stmt->bindParam("cari_mail", $cari_mail, PDO::PARAM_STR);
            $stmt->bindParam("cari_tel", $cari_tel, PDO::PARAM_STR);
            $stmt->bindParam("ilgili_adi", $ilgili_adi, PDO::PARAM_STR);
            $stmt->bindParam("ilgili_mail", $ilgili_mail, PDO::PARAM_STR);
            $stmt->bindParam("ilgili_tel", $ilgili_tel, PDO::PARAM_STR);
            $stmt->bindParam("teslim_adres", $teslim_adres, PDO::PARAM_STR);
            $stmt->bindParam("vergi_dairesi", $vergi_dairesi, PDO::PARAM_STR);
            $stmt->bindParam("vergi_no", $vergi_no, PDO::PARAM_STR);
            $stmt->bindParam("yetkili_adi", $yetkili_adi, PDO::PARAM_STR);
            $stmt->bindParam("yetkili_mail", $yetkili_mail, PDO::PARAM_STR);
            $stmt->bindParam("yetkili_tel", $yetkili_tel, PDO::PARAM_STR);

            $fatura_tarih = time();
            $stmt->bindParam("fatura_tarih", $fatura_tarih, PDO::PARAM_INT);
            
            $stmt->execute();
                


            $db = null;
            
            echo '{"feedData": ' . json_encode($feedData) . '}';
           
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
     
    
}

function cari_guncelle(){ 
    
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
   
    $cari_adi=$data->cari_adi;
    $cari_adres=$data->cari_adres;
    $cari_mail=$data->cari_mail;
    $cari_tel=$data->cari_tel;
    $ilgili_adi=$data->ilgili_adi;
    $ilgili_mail=$data->ilgili_mail;
    $ilgili_tel=$data->ilgili_tel;
    $teslim_adres=$data->teslim_adres;
    $vergi_dairesi=$data->vergi_dairesi;
    $vergi_no=$data->vergi_no;
    $yetkili_adi=$data->yetkili_adi;
    $yetkili_mail=$data->yetkili_mail;
    $yetkili_tel=$data->yetkili_tel;
     $cari_id=$data->cari_id;

    
   
    try {
         
            $feedData = '';
            $db = getDB();
            
         
                $sql = "UPDATE cari SET cari_adi=:cari_adi  , cari_adres=:cari_adres , cari_mail=:cari_mail , cari_tel=:cari_tel , ilgili_adi=:ilgili_adi , ilgili_mail=:ilgili_mail , ilgili_tel=:ilgili_tel , teslim_adres=:teslim_adres , vergi_dairesi=:vergi_dairesi , vergi_no=:vergi_no , yetkili_adi=:yetkili_adi , yetkili_mail=:yetkili_mail ,yetkili_tel=:yetkili_tel  WHERE cari_id=:cari_id ";
                
              
            
            $stmt = $db->prepare($sql);
           
            $stmt->bindParam("cari_id", $cari_id, PDO::PARAM_INT);
            $stmt->bindParam("cari_adi", $cari_adi, PDO::PARAM_STR);
            $stmt->bindParam("cari_adres", $cari_adres, PDO::PARAM_STR);
            $stmt->bindParam("cari_mail", $cari_mail, PDO::PARAM_STR);
            $stmt->bindParam("cari_tel", $cari_tel, PDO::PARAM_STR);
            $stmt->bindParam("ilgili_adi", $ilgili_adi, PDO::PARAM_STR);
            $stmt->bindParam("ilgili_mail", $ilgili_mail, PDO::PARAM_STR);
            $stmt->bindParam("ilgili_tel", $ilgili_tel, PDO::PARAM_STR);
            $stmt->bindParam("teslim_adres", $teslim_adres, PDO::PARAM_STR);
            $stmt->bindParam("vergi_dairesi", $vergi_dairesi, PDO::PARAM_STR);
            $stmt->bindParam("vergi_no", $vergi_no, PDO::PARAM_STR);
            $stmt->bindParam("yetkili_adi", $yetkili_adi, PDO::PARAM_STR);
            $stmt->bindParam("yetkili_mail", $yetkili_mail, PDO::PARAM_STR);
            $stmt->bindParam("yetkili_tel", $yetkili_tel, PDO::PARAM_STR);

           
            
            $stmt->execute();
            
                
          


            $db = null;
            
            echo '{"feedData": ' . json_encode($feedData) . '}';
           
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
     
    
}


?>

