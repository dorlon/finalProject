<?php
    include_once "./header_html.php"; 
    include_once "./objects/dbClass.php";

    $products = [];

    $db = new dbClass();

    $products=$db->selectProductDetails();

     //echo "<pre>";
     //print_r ($products);
     //echo "</pre>";
     //die("dfsd");
?>

<div class="container page-container">
    <div class="content-wrap">
        <div class="row">
            <div class="col text-center pt-5 pb-5">
                <h3>קונדיטוריה</h3>
            </div>
        </div>

        <a class="add-btn" href="/final_project/add_product.php" role="button">
            <button type="button" id="add_product" class="btn btn-primary">הוסף מוצר</button>
        </a>

        <div class="row text-center mb-4 gallery">

            <?php
            foreach($products as $value){
            ?>
    
                <div class="col-xl-3 col-lg-3 mb-4 row-md-2 card-container">
                    <div class="card" >
                        <img class="card-img-top" src="<?php echo $value["product_picture"] ?>" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $value["product_name"] ?></h5>
                            <p class="card-text piska"><?php echo $value["product_description"] ?></p>
                            <div>
                            <p class="product-price">מחיר: &#8362; <?php echo $value["product_price"] ?></p>
                            </div>
                        </div>

                        <?php
                        
                        if (isset($is_manager) && $is_manager) { ?>
                        <div class="card-button">
                            <a href="/final_project/edit_product.php?product_id=<?=$value["product_id"]?>" class="btn btn-warning">ערוך מוצר</a>
                            <td><button class="deleteProduct btn btn-danger" data-id="<?php echo $value["product_id"]?>" type="button">X</button></td>
                        </div>
                         <?php  }

                        ?>

                        <?php if (!empty($_SESSION['user_id'])) { ?>
                        <div class="card-button">
                            <button class="btn btn-primary add-to-cart" data-item-id=<?= $value['product_id']?>>הוסף לעגלה</a>
                        </div>
                        <?php } else { ?>
                            <div class="card-button">
                            <button class="btn btn-primary disabled" disabled>התחבר כדי להוסיף לעגלה</a>
                        </div>
                         <?php } ?>   
                    </div>
                </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>

<script>


$( document ).ready(function() {
  
    $(".add-to-cart").on("click",function(){
        let product_id = $(this).data("item-id");
    // create ajax requset
    $.post("server/action.php",{product_id: product_id, action: "check_stock_for_product" }, null, 'json')
      .done(function (res) {
 

                
                if (res.status == "success") {
                    $("#red-ball").show();
                    let curr_amount = $("#red-ball").text();
                    $("#red-ball").text(parseInt(curr_amount) + 1);
                    $.post("server/action.php",{product_id: product_id, action: "add-to-cart" },null,"json", function(data, status){
                        alert("Data: " + data + "\nStatus: " + status);
                });
                }
                else if (res.status == "out_of_stock") {
                        alert("הכמות חורגת מהמלאי, לא ניתן להוסיף פריט זה לעגלת הקניות.");
                        return;
                }



            //}
          //  else {
             //   alert("אין מספיק רכיבים עבור פריט זה. אנא נסה שוב מאוחר יותר");
          //  }



      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      });
  });

















    });





</script>


<?php
    include_once "./newfooter.php"; 
?>