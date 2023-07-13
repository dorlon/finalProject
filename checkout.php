<?php include_once "./objects/dbClass.php"; ?>
<?php include_once "./header_html.php"; ?>
<?php require_once ("./cart.php"); ?>

<?php
$user = [];

if (!isset($_SESSION) || empty($_SESSION['user_id'])) {
    die("<h2 style='text-align:center;'>מצטערים, אך אין לך גישה לדף זה.</h2>");
  }

$db = new dbClass();?>



<?php

$user_id = $_SESSION['user_id'];

$cart_items_response = get_cart_items_by_user_id($user_id);
$cart_items = $cart_items_response['cart_items'];
/*
echo "<pre>";
print_r($cart_items);
die;*/

$total = 0;

?>



<div class="container page-container home-page">
    <div class="content-wrap">
        <div class="row">
            <div class="col-12 text-center pt-4 pb-4">
                <h1>רכישה</h1>
            </div>
        </div>
        <div class="col-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>תמונה</th>
                                <th>שם מוצר</th>
                                <th>מחיר ליחידה</th>
                                <th>כמות</th>
                                <th>סה"כ</th>
                                <th></th>  
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php foreach($cart_items as $key => $value) {
                            $total+= intval($value['product_price']) * intval($value['quantity'])
                            ?>
                            <tr class='tr-items-cart' data-table-id='<?=$value['id']?>'>
                                <td><img src="<?=$value['product_picture']?>" alt="Item" width="100" height="100"></td>
                                <td><?=$value['product_name']?></td>
                                <td class="price_item" data-id="<?=$value['id']?>" ><?=$value['product_price']?></td>
                                <td class="quantity_item" data-id="<?=$value['id']?>"><?=$value['quantity']?></td>
                                <td class="total_item" data-id="<?=$value['id']?>"><?= intval($value['product_price']) * intval($value['quantity']) ?></td>
                                <td><a class='btn btn-success plus_item' data-product-id="<?=$value['product_id']?>" data-id=<?=$value['id']?> > <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                        </svg>
                                    </a>
                                </td>
                                <td><a class='btn btn-warning minus_item' data-id=<?=$value['id']?> ><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                    <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                                        </svg>
                                    </a>
                                </td>
                                <td><a class='btn btn-danger delete_item' data-id=<?=$value['id']?>><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>                               


                           <?php } ?>
                            
                        </tbody>
                    </table> 
                
                
            </div>
            
                <form target = "_blank"  width = 710px height = 555px action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="sb-ghfc514631765@business.example.com">
                    <input type="hidden" name="currency_code" value="ILS"> 
                    <input type="hidden" name="item_name" value="hat"> 
                    <input type="hidden" name="item_number" value="123"> 
                    <input type="hidden" name="amount" id="total_amount" value="<?=$total?>">
                    <input type="hidden" name="upload" value="1">
                    <input type="image" style="display:none;"  name="upload" alt="PayPal - The safer, easier way to pay online" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" > 
                    </form> 

                   
           
        </div>



            <div id="paypal-payment-button">

            </div>
            <script class="paypal-payment-button" src="index.js"></script>
        </div>
    </div>
</div>

 <!-- ***********CART TOTALS*************-->




 </div><!--Main Content-->


    </div>
    <!-- /.container -->
<?php
    include_once "./newfooter.php"; 
   ?>