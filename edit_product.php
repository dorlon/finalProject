<?php
    include_once "./header_html.php"; 
    
    $product_id = $_GET['product_id'];

    $product = new Product();
    $component = new Component();

    $details = $product->getDetailsOfProduct($product_id);

    $all_components = $component->getAllComponents();



    echo "<pre>";
    print_r($details);

    echo "</pre>";
    


?>

<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1>עריכת מוצר</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
          <div class="row">
            <div class="col-12">
              <div class="cake-photo-upload">
                <img id="box-img" style='max-width: 245px;' src="<?=$details[$product_id]['product_picture']?>" alt="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <label>להעלאת תמונה</label>
              <input type="file" class="form-control" value="<?=$details[$product_id]["product_picture"]?>" id="product_picture" accept=".png,.gif,.jpg,.webp" name="product_picture" multiple />
            </div>
          </div>

          <div class="row">
            <div class="col-12">
              <p>רכיבים</p>
                

              <table class="table">
    <thead>
      <tr>
        <th>שם פריט</th>
        <th>כמות</th>
        <th>פעולה</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach( $details[$product_id]['components'] as $component) { ?>
            <tr>
                <td ><?=$component['component_name']?></td>
                <td contenteditable="true"><?=$component['amount']?></td>
                <td> <button data-product-id="<?=$product_id?>" class="btn btn-danger btn-remove-compoment-from-product" data-id-row="<?=$component['id_pc']?>">מחק</button> </td>
 
          <?php } ?>
    </tbody>
  </table>


            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="container">
            <form class="form-horizontal text-right">
              <div class="form-group">
                <label class="control-label label-form" for="product_name">שם מוצר</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="product_name" value="<?=$details[$product_id]["product_name"]?> ">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label label-form" for="product_description">תיאור מוצר</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="product_description" value="<?=$details[$product_id]["product_description"]?>">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label label-form" for="price">מחיר</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="product_price" placeholder="מחיר מוצר" value="<?=$details[$product_id]["price"]?>">
                </div>
              </div>

              <div class="form-group">
              <div class="label-form">הוסף רכיב</div>
                <label class="control-label label-form" for="email">סוג:</label>
                  <div class="col-sm-10">
                    <select class="form-control" id="select_component_in_product">
                      <option selected disabled>בחר רכיב:</option>
                      <?php foreach ($all_components as $value) { ?>
                          <option value="<?=$value['component_id']?>"> <?=$value['component_name']?> </option>

                        <?php } ?>
                    </select>
                  </div>
              </div>
                
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
                <label class="control-label label-form" for="email">כמות רכיב:</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control" id="count_component_in_product">
                </div>
                <button type="button" data-product-id="<?=$product_id?>" class="btn btn-primary add-component-in-product-btn">הוסף רכיב</button>
              </div>

              <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-10 confirm-product-btn">
                  <button type="button" id="product" class="btn btn-success">אישור מוצר</button>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class=" btn btn-danger" >מחק מוצר</button>
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
        </div>
    </div>
</div>

<?php
    include_once "./newfooter.php"; 
   ?>