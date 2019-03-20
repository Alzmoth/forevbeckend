
<div class="form-group">
 <label>Enter Employee Name</label>
 <input type="text" name="name" id="name" class="form-control" />
</div>
<div class="form-group">
 <label>Enter Employee Address</label>
 <textarea name="address" id="address" class="form-control"></textarea>
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
 <input type="text" name="designation" id="designation" class="form-control" />
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

  var name = localStorage.getItem('name');
  var address = localStorage.getItem('address');
  var gender = localStorage.getItem('gender');
  var designation = localStorage.getItem('designation');
  var max_iskonto = localStorage.getItem('max_iskonto');
  var images = localStorage.getItem('images');

  $('#name').val(name);
  $('#address').val(address);
  $('#gender').val(gender);
  $('#designation').val(designation);
  $('#max_iskonto').val(max_iskonto);

  if(images != '')
  {
   $('#uploaded_image').html('<img src="images/'+images+'" class="img-thumbnail" width="100" />');
   $('#hidden_images').val(images);
  }

 });
</script>
