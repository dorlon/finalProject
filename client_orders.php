<?php
    include_once "./header_html.php"; 
    include_once "./objects/dbClass.php"; 
    if (!isset($client_order) || !$client_order) {
      die("<h2 style='text-align:center;'>מצטערים, אך אין לך גישה לדף זה.</h2>");
    }
    $user_id = $_SESSION['user_id'];
    $client_order = new CustomOrders($user_id);
   
    $my_orders = $client_order->getAllOrdersPerUserId();

    $statuses_array = [

      1 => ["text" => "בטיפול" , "style" => "info"],
      2 => ["text" => "הושלם" , "style" => "success"],
      0 => ["text" => "בוטל" , "style" => "danger"],

    ];
  //  echo "<pre>";
  //   ($my_orders);

  //   die;



?>

<div class="container page-container">
  <div class="content-wrap">
    <div class="row">
        <div class="col-12 text-center">
            <h1>ההזמנות שלי</h1>
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
        </tr>
      </thead>
      <tbody>
        <?php foreach($my_orders as $value) { ?>
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
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>



<?php
    include_once "./newfooter.php"; 
   ?>