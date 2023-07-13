<?php
    include_once "./header_html.php"; 

    $component = new Component();
    $all_components = $component->getAllComponents();


?>

<div class="container">
  <div class="row">
      <div class="col-12 text-center">
        <h1>הוספת מוצר</h1>
  </div>


  
<form id="add_product_form" class="form-horizontal text-right" enctype="multipart/form-data">
  <div class="upload-img-container">
    <div class="form-group">
        <label>להעלאת תמונה</label>
        <input style='max-width: 245px;' type="file" class="form-control" name="product_picture" id="product_picture" accept=".png,.gif,.jpg,.webp" multiple />
        <div class="form-group">
          <label class="control-label label-form" for="product_name">שם מוצר</label>
          <input type="text" class="form-control" id="product_name" name="product_name">
        </div>
        <div class="form-group">
          <label class="control-label label-form" for="product_description">תיאור מוצר</label>
          <input type="text" class="form-control" id="product_description" name="product_description">
        </div>
        <div class="form-group">
          <label class="control-label label-form" for="price">מחיר</label>
          <input type="number" class="form-control" id="product_price" name="product_price" placeholder="מחיר מוצר">
        </div>
    </div>
    <input type="hidden" name="action" value="add-new-product" /> 
    <div class="cake-photo-upload">
    <img id="box-img" src="" style="max-width:245px;" alt=""/>

    </div>
     
      <br>
     
    </div>
    <button type="submit" id="product" class="btn btn-primary">Submit</button>
  </div>
<div class='row'>

  <div class='col-6' style='text-align:right;'><form class="form-horizontal text-right">
  <div class="form-group second-form">
    <div class="label-form">
      <h3>הוספת רכיב קיים</h3>
    </div>
    <label class="control-label label-form" for="email">סוג:</label>
      <select class="form-control" id="select_components">
        <option selected disabled>בחר רכיב:</option>
        <?php foreach($all_components as $value) { ?>
          <option value="<?=$value['component_id']?>"><?=$value['component_name']?></option>
        <?php } ?>  
      </select>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <a class="dropdown-item" href="#">Action</a>
      <a class="dropdown-item" href="#">Another action</a>
      <a class="dropdown-item" href="#">Something else here</a>
    </div>
    <label class="control-label label-form" for="email">כמות רכיב:</label>
    <input type="number" class="form-control" id="count">
    <button type="button" class="btn btn-primary add-component-btn">הוסף רכיב</button>
  </div>       
</form></div>
  <div class='col-6'>


  <table class="table" style="display:none;" id="table-components">
    <thead>
      <tr>
        <th>שם פריט</th>
        <th>כמות</th>
        <th>מחק</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>


  </div>

</div>  
</form>

<script>

$('#product_picture').change( function(event) {
    var tmppath = URL.createObjectURL(event.target.files[0]);
    $("#box-img").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
    
    //$("#disp_tmp_path").html("Temporary Path(Copy it and try pasting it in browser address bar) --> <strong>["+tmppath+"]</strong>");
});


</script>

</div>
<?php
    include_once "./newfooter.php"; 
   ?>