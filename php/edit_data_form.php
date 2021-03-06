
<div class="form-group">
 <label>Enter Employee firma_kodu</label>
 <input type="text" name="firma_kodu" id="firma_kodu" class="form-control" />
</div>
<div class="form-group">
 <label>Enter Employee firma_adi</label>
 <textarea name="firma_adi" id="firma_adi" class="form-control"></textarea>
</div>
<div class="form-group">
 <label>Enter Employee Gender</label>
 <select name="gender" id="gender" class="form-control">
  <option value="Male">Male</option>
  <option value="Female">Female</option>
 </select>
</div>
<div class="form-group">
 <label>Enter Employee Desingation</label>
 <input type="text" name="alis_iskonto" id="alis_iskonto" class="form-control" />
</div>
<div class="form-group">
 <label>Enter Employee max_iskonto</label>
 <input type="text" name="max_iskonto" id="max_iskonto" class="form-control" />
</div>
<div class="form-group">
 <label>Select Employee Image</label>
 <input type="file" name="images" id="images" />
 <span id="uploaded_image"></span>
 <input type="hidden" name="hidden_images" id="hidden_images" />
</div>

<script>
 $(document).ready(function () {

  var firma_kodu = localStorage.getItem('firma_kodu');
  var firma_adi = localStorage.getItem('firma_adi');
  var gender = localStorage.getItem('gender');
  var alis_iskonto = localStorage.getItem('alis_iskonto');
  var max_iskonto = localStorage.getItem('max_iskonto');
  var images = localStorage.getItem('images');

  $('#firma_kodu').val(firma_kodu);
  $('#firma_adi').val(firma_adi);
  $('#gender').val(gender);
  $('#alis_iskonto').val(alis_iskonto);
  $('#max_iskonto').val(max_iskonto);

  if(images != '')
  {
   $('#uploaded_image').html('<img src="images/'+images+'" class="img-thumbnail" width="100" />');
   $('#hidden_images').val(images);
  }

 });
</script>
