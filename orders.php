<?php
    include_once "./header_html.php"; 
    include_once "./objects/dbClass.php"; 

    if (!isset($is_manager) || !$is_manager) {
      die("<h2 style='text-align:center;'>מצטערים, אך אין לך גישה לדף זה.</h2>");
    }


    $orders = new CustomOrders();
    $all_orders = $orders->getAllOrdersForAdmin();

    $statuses_array = [

      1 => ["text" => "בטיפול" , "style" => "info"],
      2 => ["text" => "הושלם" , "style" => "success"],
      0 => ["text" => "בוטל" , "style" => "danger"],

    ];



?>

<div class="container page-container">
  <div class="content-wrap">
    <div class="row">
        <div class="col-12 text-center">
            <h1>דף הזמנות</h1>
        </div>
    </div>

 


    <table class="table table-striped text-center table-bordered" id="orders-table">
      <thead>
        <tr>
        <th scope="col"></th>
          <th scope="col">#</th>
          <th scope="col">שם המזמין</th>
          <th scope="col">כתובת</th>
          <th scope="col">מחיר</th>
          <th scope="col">תאריך משלוח</th>
          <th scope="col">סטטוס הזמנה</th>
          <th scope="col">פריטים שהוזמנו</th>
          <th scope="col">פעולות</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($all_orders as $value) { ?>
        <tr>
          <td><button class="deleteOrder btn btn-danger" data-id="<?php echo $value["id_order"]?>" type="button">X</button></td>
          <th scope="row"><?=$value['id_order']?></th>
          <td><?=$value['fullname']?></td>
          <td><?=$value['address']?></td>
          <td data-sort="<?=$value['total_price']?>"><?=$value['total_price']?> &#8362; </td>
          <td><?=date("d/m/Y H:i", strtotime($value['order_date']))?></td>
          <td><button type="button" data-order-id="<?=$value['id_order']?>" class="btn btn-<?=$statuses_array[$value['status']]["style"]?> status-label" style="width:130px"><?=$statuses_array[$value['status']]["text"]?></button></td>
          <td>
            <button type="button" class="btn btn-link show-products" data-paypal-order-id="<?=$value['order_id_paypal']?>">
              הצג פרטי הזמנה
            </button>
          </td>
          <td>
           
          <button class="btn btn-sm btn-success btn-done" data-id="<?=$value['id_order']?>">סמן כהושלם</button>
          <button class="btn btn-sm btn-info btn-handle" data-id="<?=$value['id_order']?>">סמן בטיפול</button>
          <button class="btn btn-sm btn-danger btn-delete" data-id="<?=$value['id_order']?>">סמן כבוטל</button>
          </td>

        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div id="modal_show_products_in_order" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title title-modal-details-items"></h4>
      </div>
      <div class="modal-body">
              <table class="table">
            <thead>
              <tr>
                <th>שם פריט</th>
                <th>מחיר</th>
                <th>כמות</th>
                <th>סהכ</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>עוגת פרג</td>
                <td>15 </td>
                <td>2</td>
                <td>30 </td>
              </tr>
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>



<script>
$(".btn-done").on("click",function(){

let order_id = $(this).data("id");

$(".status-label[data-order-id='"+order_id+"']").removeClass("btn-danger");
$(".status-label[data-order-id='"+order_id+"']").removeClass("btn-info");
$(".status-label[data-order-id='"+order_id+"']").addClass("btn-success");
$(".status-label[data-order-id='"+order_id+"']").text("הושלם");
$.post("server/action.php", {action: "set-done-order", order_id: order_id},null,"json").done(function(res){
  
});

});




$(".btn-delete").on("click",function(){

let order_id = $(this).data("id");

$(".status-label[data-order-id='"+order_id+"']").removeClass("btn-info");
$(".status-label[data-order-id='"+order_id+"']").removeClass("btn-success");
$(".status-label[data-order-id='"+order_id+"']").addClass("btn-danger");
$(".status-label[data-order-id='"+order_id+"']").text("בוטלה");
$.post("server/action.php", {action: "set-delete-order", order_id: order_id},null,"json").done(function(res){
  
});

});


$(".btn-handle").on("click",function(){

let order_id = $(this).data("id");

$(".status-label[data-order-id='"+order_id+"']").removeClass("btn-danger");
$(".status-label[data-order-id='"+order_id+"']").removeClass("btn-success");
$(".status-label[data-order-id='"+order_id+"']").addClass("btn-info");
$(".status-label[data-order-id='"+order_id+"']").text("בטיפול");
$.post("server/action.php", {action: "set-handle-order", order_id: order_id},null,"json").done(function(res){
  
});

});


$('#orders-table').DataTable(
  {  "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.1/i18n/he.json"
        }
  }


);


  $(".show-products").on("click",function(){
      var payapl_order_id = $(this).attr("data-paypal-order-id");

      $(".title-modal-details-items").text("פרטי הזמנה מספר " +payapl_order_id )
    
      var str_html = ``;
      $.post("server/action.php", {action: "show-products-in-order", payapl_order_id:payapl_order_id},null,"json").done(function(res){
          for(let i in res) {
            str_html+= `<tr>
                  <td>${res[i].product_name}</td>
                  <td>${res[i].price}</td>
                  <td>${res[i].qnt}</td>
                  <td>${res[i].total}</td>
            </tr>`;
            console.log(res[i]);
          }
          $("#modal_show_products_in_order tbody").html(str_html);
          console.log(str_html);
          $("#modal_show_products_in_order").modal('show');


      });

  });

</script>

<?php
    include_once "./newfooter.php"; 
   ?>