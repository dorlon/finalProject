<?php 
$base = $_SERVER['DOCUMENT_ROOT'];
include_once $base."/final_project/objects/dbClass.php";
include_once $base."/final_project/objects/component.php";
include_once $base."/final_project/objects/order.php";
include_once $base."/final_project/objects/product.php";
include_once $base."/final_project/objects/user.php";
include_once $base."/final_project/objects/CustomOrders.php"; 
include_once $base."/final_project/server/action.php"; 

$is_manager = false;
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
  $is_manager = true;
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!empty($_SESSION['user_id'])) {
  $qnt_in_cart = getQntProductsInCart($_SESSION['user_id']);
}
else {
  $qnt_in_cart = 0;
}



?>

<!DOCTYPE html>
<html lang="he">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="./style/style.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"
    ></script>

    <script src="https://www.paypal.com/sdk/js?currency=ILS&client-id=AbORGJzVGoydtGeMJeMbMvL5_jV2LMTjvVbuusHg2rr5JRISBBAfgfsChrzF23UgKnh_JZQk6L73LFyw&disable-funding=credit,card"></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"
    ></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>


    <script src="./JS/script.js"></script>
    <title>Document</title>
  </head>
  <body>
    <header class="p-3">
      <nav class="navbar navbar-dark navbar-expand-lg bg-dark text-white">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav col-12 col-lg-auto">
              <li><a href="/final_project/index_html.php" class="nav-link px-2 text-white">דף הבית</a></li>
              <li><a href="/final_project/shop.php" class="nav-link px-2 text-white">גלריה</a></li>
              <?php if ($is_manager) { ?>
              <li><a href="/final_project/orders.php" class="nav-link px-2 text-white">הזמנות מנהל</a></li>
              <li><a href="/final_project/statistics.php" class="nav-link px-2 text-white">סטטיסטיקה</a></li>
              <li><a href="/final_project/users_table.php" class="nav-link px-2 text-white">משתמשים</a></li>
              <li><a href="/final_project/components_table.php" class="nav-link px-2 text-white">רכיבים</a></li>
              <?php } ?>

              <?php if (!empty($_SESSION['user_id'])) { ?>
              <li><a href="/final_project/client_orders.php" class="nav-link px-2 text-white">ההזמנות שלי</a></li>
              <?php } ?>
              <li><a href="/final_project/contact_us.php" class="nav-link px-2 text-white">צור קשר</a></li>
            </ul>
          </div>
                
          <?php 
            if (empty($_SESSION["user_id"])) {
          ?>
            <div class="text-end">
              <button type="button" class="btn btn-outline-light me-2" data-bs-toggle="modal"
              data-bs-target="#exampleModal-sign">הרשמה</button>
              <button type="button" class="btn btn-warning" data-bs-toggle="modal"
              data-bs-target="#exampleModal-conection">התחברות</button>
            </div>
          <?php   
            }else{
          ?>

          <div>
            <!-- <button type="button" href="/final_project/edit_user.php" class="btn btn-link" ></button> -->
            <?php echo "שלום" , " " , $_SESSION["full_name"] ?>
          </div>

          <?php   
            }
          ?>
            <div class="icons">
          
            <?php if (!empty($_SESSION['user_id'])) { ?>
              <a class="navbar-brand" id="edit_user" href="/final_project/edit_user.php">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" color="white">
                  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                </svg> -->

                <img id="box-img" src="images/dego.jpg" alt="" style="    max-width: 50px; border-radius: 50%;">
              </a>
              <?php } ?>
              <a href="https://wa.me/+972509580502" target="_blank" style="text-decoration: none;">
                <img src="icons/whatsapp.png" alt="" width="40" height="40" class="mr-2">
              </a>
              <?php if (!empty($_SESSION['user_id'])) { ?>
              <a class="navbar-brand" style="position:relative;" href="/final_project/checkout.php">
                <img src="images/shopping_cart.png" alt="" width="40" height="40" />
                <?php if ($qnt_in_cart > 0) {
                      $display = "block";
                }
                 else {
                  $display = "none";
                 } ?>

                <span id="red-ball" style="
                      padding: 2px;
                    position: absolute;
                    left: 40px;
                    top: -5px;
                    background: red;
                    border-radius: 50%;
                    width: 13px;
                    height: 18px;
                    font-size: 10px;
                    display: <?=$display?>;
                "><?=intval($qnt_in_cart)?></span>

              </a>
              <?php } ?>
            </div>

            <?php 
              if (!empty($_SESSION["user_id"])) {
            ?>
              <div class="text-end">
                <button type="button" class="btn btn-danger" id="disconnect">יציאה</button>
              </div>
            <?php   
              }
            ?>
        </div>
      </nav>
    </header>