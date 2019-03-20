
<div class="form-group">
 <label>Firma Kodu :</label>
 <input type="text" name="firma_kodu" id="firma_kodu" class="form-control" />
</div>
<div class="form-group">
 <label>Firma Adresi(bos olabilir)</label>
 <textarea name="address" id="address" class="form-control"></textarea>
</div>
<div class="form-group">
 <label>Firma Adi : </label>
 <textarea name="firma_adi" id="firma_adi" class="form-control"></textarea>
</div>
<div class="form-group">
 <label>Alis Iskonto :</label>
 <input type="text" name="alis_iskonto" id="alis_iskonto" class="form-control" />
</div>
<div class="form-group">
 <label>Max Iskonto :</label>
 <input type="text" name="max_iskonto" id="max_iskonto" class="form-control" />
</div>
<div class="form-group">
 <label>Resim (Simdilik Bos)</label>
 <input type="file" name="images" id="images" />
 <span id="uploaded_image"></span>
 <input type="hidden" name="hidden_images" id="hidden_images" />
</div>

<script>
 $(document).ready(function () {

  var firma_kodu = localStorage.getItem('firma_kodu');
  var address = localStorage.getItem('address');
  var firma_adi = localStorage.getItem('firma_adi');
  var alis_iskonto = localStorage.getItem('alis_iskonto');
  var max_iskonto = localStorage.getItem('max_iskonto');
  var images = localStorage.getItem('images');

  $('#firma_kodu').val(firma_kodu);
  $('#address').val(address);
  $('#firma_adi').val(firma_adi);
  $('#alis_iskonto').val(alis_iskonto);
  $('#max_iskonto').val(max_iskonto);

  if(images != '')
  {
   $('#uploaded_image').html('<img src="images/'+images+'" class="img-thumbnail" width="100" />');
   $('#hidden_images').val(images);
  }

 });
</script>
