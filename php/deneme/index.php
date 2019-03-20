<html>
 <head>
  <title>Forev</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="https://www.jqueryscript.net/demo/Dialog-Modal-Dialogify/dist/dialogify.min.js"></script>
 </head>
 <body>
  <div class="container">
   <br />
   <h3 align="center">Forev Firma Guncelleme</h3>
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">
     <div class="row">
      <div class="col-md-6">
       <h3 class="panel-title">Firmalar</h3>
      </div>
      <div class="col-md-6" align="right">
       <button type="button" name="add_data" id="add_data" class="btn btn-success btn-s">Yeni Firma</button>
      </div>
     </div>
    </div>
    <div class="panel-body">
     <div class="table-responsive">
      <span id="form_response"></span>
      <table id="user_data" class="table table-bordered table-striped">
       <thead>
        <tr>
         <td>Firma Kodu</td>
         <td>Firma Adi</td>
         <td>Alis Iskonto</td>
         <td>Max Iskonto</td>
         <td>Guncelle</td>
         <td>Sil</td>
        </tr>
       </thead>
      </table>      
     </div>
    </div>
   </div>
  </div>
 </body>
</html>

<script type="text/javascript" language="javascript" >
$(document).ready(function(){
 
 var dataTable = $('#user_data').DataTable({
  "processing":true,
  "serverSide":true,
  "order":[],
  "ajax":{
   url:"fetch.php",
   type:"POST"
  },
  "columnDefs":[
   {
    "targets":[4,5],
    "orderable":false,
   },
  ],

 });


 
 $('#add_data').click(function(){
  var options = {
   ajaxPrefix:''
  };
  new Dialogify('add_data_form.php', options)
   .title('Yeni Firma')
   .buttons([
    {
     text:'Iptal',
     click:function(e){
      this.close();
     }
    },
    {
     text:'Kaydet',
     type:Dialogify.BUTTON_PRIMARY,
     click:function(e)
     {
      var image_data = $('#images').prop("files")[0];
      var form_data = new FormData();
      form_data.append('images', image_data);
      form_data.append('firma_kodu', $('#firma_kodu').val());
      form_data.append('address', $('#address').val());
      form_data.append('firma_adi', $('#firma_adi').val());
      form_data.append('alis_iskonto', $('#alis_iskonto').val());
      form_data.append('max_iskonto', $('#max_iskonto').val());
      $.ajax({
       method:"POST",
       url:'insert_data.php',
       data:form_data,
       dataType:'json',
       contentType:false,
       cache:false,
       processData:false,
       success:function(data)
       {
        if(data.error != '')
        {
         $('#form_response').html('<div class="alert alert-danger">'+data.error+'</div>');
        }
        else
        {
         $('#form_response').html('<div class="alert alert-success">'+data.success+'</div>');
         dataTable.ajax.reload();
        }
       }
      });
     }
    }
   ]).showModal();
 });

 $(document).on('click', '.update', function(){
  var id = $(this).attr('id');
  $.ajax({
   url:"fetch_single_data.php",
   method:"POST",
   data:{id:id},
   dataType:'json',
   success:function(data)
   {
    localStorage.setItem('firma_kodu', data[0].firma_kodu);
    localStorage.setItem('address', data[0].address);
    localStorage.setItem('firma_adi', data[0].firma_adi);
    localStorage.setItem('alis_iskonto', data[0].alis_iskonto);
    localStorage.setItem('max_iskonto', data[0].max_iskonto);
    localStorage.setItem('images', data[0].images);

    var options = {
     ajaxPrefix:''
    };
    new Dialogify('edit_data_form.php', options)
     .title('Firma Guncelleme Ekrani')
     .buttons([
      {
       text:'Iptal',
       click:function(e){
        this.close();
       }
      },
      {
       text:'Guncelle',
       type:Dialogify.BUTTON_PRIMARY,
       click:function(e)
       {
        var image_data = $('#images').prop("files")[0];
        var form_data = new FormData();
        form_data.append('images', image_data);
        form_data.append('firma_kodu', $('#firma_kodu').val());
        form_data.append('address', $('#address').val());
        form_data.append('firma_adi', $('#firma_adi').val());
        form_data.append('alis_iskonto', $('#alis_iskonto').val());
        form_data.append('max_iskonto', $('#max_iskonto').val());
        form_data.append('hidden_images', $('#hidden_images').val());
        form_data.append('id', data[0].id);
        $.ajax({
         method:"POST",
         url:'update_data.php',
         data:form_data,
         dataType:'json',
         contentType:false,
         cache:false,
         processData:false,
         success:function(data)
         {
          if(data.error != '')
          {
           $('#form_response').html('<div class="alert alert-danger">'+data.error+'</div>');
          }
          else
          {
           $('#form_response').html('<div class="alert alert-success">'+data.success+'</div>');
           dataTable.ajax.reload();
          }
         }
        });
       }
      }
     ]).showModal();
   }
  })
 });

 $(document).on('click', '.delete', function(){
  var id = $(this).attr('id');
  Dialogify.confirm("<h3 class='text-danger'><b>Firmayi silmek istediginden emin misin?</b></h3>", {
   ok:function(){
    $.ajax({
     url:"delete_data.php",
     method:"POST",
     data:{id:id},
     success:function(data)
     {
      Dialogify.alert('<h3 class="text-success text-center"><b>Firma Silindi</b></h3>');
      dataTable.ajax.reload();
     }
    })
   },
   cancel:function(){
    this.close();
   }
  });
 });
 
 
});
</script>