<div class="form-group">

 <label>firma Kodu</label>

 <input type="text" name="firma_kodu" id="firma_kodu" class="form-control" />

</div>

<div class="form-group">

 <label>Firma Adı</label>

 <textarea name="firma_adi" id="firma_adi" class="form-control"></textarea>

</div>

<div class="form-group">

 <label>Firma Alış İskontosu</label>


 <input type="number" name="alis_iskonto" id="alis_iskonto" class="form-control" />
</div>

<div class="form-group">

 <label>Firma Max İskontosu</label>

 <input type="number" name="max_iskonto" id="max_iskonto" class="form-control" />

</div>




<script>

 $(document).ready(function () {



  var firma_kodu = localStorage.getItem('firma_kodu');

  var firma_adi = localStorage.getItem('firma_adi');

  var alis_iskonto = localStorage.getItem('alis_iskonto');

  var max_iskonto = localStorage.getItem('max_iskonto');





  $('#firma_kodu').val(firma_kodu);

  $('#firma_adi').val(firma_adi);

  $('#alis_iskonto').val(alis_iskonto);

  $('#max_iskonto').val(max_iskonto);







 });

</script>

