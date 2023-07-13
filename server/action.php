<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';


$base = $_SERVER['DOCUMENT_ROOT'];
include_once $base."/final_project/objects/dbClass.php";
include_once $base."/final_project/objects/component.php";
include_once $base."/final_project/objects/order.php";
include_once $base."/final_project/objects/product.php";
include_once $base."/final_project/objects/user.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


function sendMailTemplate($from,$fromName,$subject, $to, $htmlMsg) {

  
    //die;

    $mail = new PHPMailer(true);
    $mail->CharSet = "UTF-8"; 
    $mail->Encoding = 'base64';
    try {
        // Server settings
       //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.sendinblue.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
    
        $mail->Username = 'dbconfectionery1@gmail.com'; // YOUR gmail email
        $mail->Password = 'BnjNK13Z0sXVRFI7'; // YOUR gmail password
    
        // Sender and recipient settings
        $mail->setFrom($from, $fromName);
        $mail->addReplyTo($from,$fromName); // to set the reply to
        $mail->addAddress($to, $fromName);

    
        // Setting the email content
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlMsg;
        $mail->AltBody = 'Plain text message body for non-HTML email client. Gmail SMTP email body.';
    
        return $mail->send();

    } catch (Exception $e) {
        return false;
        //echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
    }


}

if (isset($_POST['action']) && $_POST['action'] == "send_contact") {
    
    $from = $_POST['email'];
    $fromName = $_POST['fullname'];
    $comment = $_POST['comment'];
    $subject = "הודעה מגולש באתר";
    $to = "dbconfectionery1@gmail.com";
    $htmlMsg = "<p>{$comment}</p>";

    $is_success = sendMailTemplate($from,$fromName,$subject, $to, $htmlMsg);
    die(json_encode(["type" => $is_success]));






}


if (isset($_POST['action']) && $_POST['action'] == "show-products-in-order") {
    $paypal_orderid = $_POST['payapl_order_id'];
    getAllProdutsOfOrder($paypal_orderid);
}

if (isset($_POST['action']) && $_POST['action'] == "add-to-cart" ) {

   $product_id = $_POST["product_id"];
   $user_id = $_SESSION['user_id'];
   addToCart($product_id,$user_id);
}

if (isset($_POST['action']) && $_POST['action'] == "check_stock_for_product") {

    $product_id = $_POST["product_id"];
    checkRealStock($product_id);

}

if (isset($_POST['action']) && $_POST['action'] == "add_item_in_cart") {

    $check_stock = checkRealStock($_POST['product_id'],true);

    if ($check_stock['status'] != 'success') {
        die(json_encode(["flag" => 0, "msg" => "הכמות חורגת מהמלאי"]));
    }


    $id_cart_item = $_POST['id_cart_item'];
    $user_id = $_SESSION['user_id'];
    $answer = addToItemCart($id_cart_item,$user_id);

    die(json_encode($answer));


}

if (isset($_POST['action']) && $_POST['action'] == "erase_item_in_cart") {

    $id_cart_item = $_POST['id_cart_item'];
    $user_id = $_SESSION['user_id'];
    $answer = eraseFromItemCart($id_cart_item,$user_id);

    die(json_encode($answer));


}

if (isset($_POST['action']) && $_POST['action'] == 'delete_item_in_cart'){

    $db = new dbClass();
    $id_cart_item = $_POST['id_cart_item'];
    $user_id = $_SESSION['user_id'];
    $answer = deleteFromItemCart($id_cart_item,$user_id);

    if($answer['flag'] == 1){
        die(json_encode(["type" => "success"]));
    }
    die(json_encode(["type" => "fail"]));
}

if(isset($_POST['action']) && $_POST['action'] == "add-new-user"){
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    addNewUser($_POST);
}


if(isset($_POST['action']) && $_POST['action'] == "edit-component"){
    editComponent($_POST);
}

if(isset($_POST['action']) && $_POST['action'] == "add-new-component"){
    addNewComponent($_POST);
}

if (isset($_POST['action']) && $_POST['action'] == "add-compoment-to-exists-product") {

    $product_id = $_POST['product_id'];
    $component_id = $_POST['component_id'];
    $amount = $_POST['component_amount'];

    update_component_to_exists_product( $product_id,$component_id,$amount);

}


if (isset($_POST['action']) && $_POST['action'] == "remove-compoment-from-exists-product") {
    $id_row = $_POST['id_row'];
    remove_component_to_product( $id_row);
}


if (isset($_POST['action']) && $_POST['action'] == "add-compoment-to-product") {
   
    $components = $_POST['compoment'];
    $last_id = $_POST['product_id'];

    update_component_to_product( $components,$last_id);

}

if (isset($_POST['action']) && $_POST['action'] == 'remove_my_account' ) {

    $user_id = $_SESSION['user_id'];

    $db = new dbClass();
    $details = $db->remove_user($user_id);
    session_destroy();
    die(json_encode(["type" => "success"]));

}

if(isset($_POST['action']) && $_POST['action'] == "add-new-product"){
    $last_id = addNewProduct($_POST, $_FILES);

}

if (isset($_POST['action']) && $_POST['action'] == 'deleteOrder'){

    $db = new dbClass();
    $ans = $db->deleteOrder($_POST['id']);

    if($ans['flag'] == 1){
        die(json_encode(["type" => "success"]));
    }
    die(json_encode(["type" => "fail"]));
}

if (isset($_POST['action']) && $_POST['action'] == 'deleteProduct'){

    $db = new dbClass();
    $ans = $db->deleteProduct($_POST['id']);

    if($ans['flag'] == 1){
        die(json_encode(["type" => "success"]));
    }
    die(json_encode(["type" => "fail"]));
}

if(isset($_POST['action']) && $_POST['action'] == "add-new-order"){
    addNewOrder($_POST);
}

if (isset($_POST['action']) && $_POST['action'] == "set-done-order") {

    setDoneOrder($_POST["order_id"]);


}

if (isset($_POST['action']) && $_POST['action'] == "set-delete-order") {

    setCancelOrder($_POST["order_id"]);


}

if (isset($_POST['action']) && $_POST['action'] == "set-handle-order") {

    setHandleOrder($_POST["order_id"]);


}

if(isset($_POST['action']) && $_POST['action'] == "check_login_details"){
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    // die("abc");
    loginUser($_POST);
}

if (isset($_POST['action']) && $_POST['action'] == 'deleteComponent'){

    $db = new dbClass();
    $ans = $db->deleteComponent($_POST['id']);

    if($ans['flag'] == 1){
        die(json_encode(["type" => "success"]));
    }
    die(json_encode(["type" => "fail"]));
}

if (isset($_POST['action']) && $_POST['action'] == 'deleteUser'){

    $db = new dbClass();
    $ans = $db->deleteUser($_POST['id']);

    if($ans['flag'] == 1){
        die(json_encode(["type" => "success"]));
    }
    die(json_encode(["type" => "fail"]));
}

if (isset($_POST['action']) && $_POST['action'] == "get_five_cakes_sold" ) {

    $start = $_POST['start'];
    $end = $_POST['end'];
    $num_to_show = $_POST['num_to_show'];
    $db = new dbClass();
    $five_cake_sold = $db->get_five_cakes_sold($start,$end,$num_to_show);
 
    

    die(json_encode($five_cake_sold));
}


if(isset($_POST['action']) && $_POST['action'] == 'logout'){

    session_destroy();
    die(json_encode(["type" => "success"]));
}


function deleteFromItemCart($cart_item_id, $user_id) {

    $db = new dbClass();
    $answer = $db->deleteFromCartDb($cart_item_id,$user_id);
    return $answer;
}

function addToItemCart($cart_item_id, $user_id) {

    $db = new dbClass();
    $answer = $db->addToCartDb($cart_item_id,$user_id);
    return $answer;
}

function eraseFromItemCart($cart_item_id, $user_id) {

    $db = new dbClass();
    $answer = $db->eraseFromCartDb($cart_item_id,$user_id);
    return $answer;
}

function checkRealStock($product_id, $from_cart = false) {
    $db = new dbClass();
    $commited = $db->getCommitedComponentsForProduct($product_id);
    $arr_needed_and_stock = $db->getRequiredComponentsForProduct($product_id);

    $nedded = $arr_needed_and_stock['needed'];
    $in_stock =  $arr_needed_and_stock['arr'];



    foreach($in_stock as $key_compoments => $amount) {
        if (!isset($commited[$key_compoments])) {
            $commited[$key_compoments] = 0;
        }


        if (($commited[$key_compoments ] > $amount || $in_stock[$key_compoments] -  $nedded[$key_compoments] - $commited[$key_compoments ] <= 0    ) ) {
            if ($from_cart) return ["status" => "out_of_stock" , "commited" => $commited, "stock_arr" => $in_stock, "needed" => $nedded ];
            
            die(json_encode(["status" => "out_of_stock" , "commited" => $commited, "stock_arr" => $in_stock, "needed" => $nedded ]));
        }
    }

    if ($from_cart) return ["status" => "success" , "commited" => $commited, "stock_arr" => $in_stock, "needed" => $nedded ];
    die(json_encode(["status" => "success" , "commited" => $commited, "stock_arr" => $in_stock, "needed" => $nedded ]));



}

function addToCart($product_id,$user_id) {


    $db = new dbClass();
    $answer = $db->addToCartItemFromGallery($product_id,$user_id);
    if($answer["flag"] == 1){
        die(json_encode(["type"=>"success", "msg" =>["פרטיך נשמרו במערכת"]]));
    }
    if($answer["flag"]== 0){
        die(json_encode(["type"=>"error", "msg"=>["not saved"]]));
    }
}

function getQntProductsInCart($user_id) {
    $db = new DbClass();
    return $db->getQntOfCart($user_id);
}

function getAllProdutsOfOrder($paypal_orderid) {

    $db = new DbClass();
    $allProducts = $db->getAllProductsPerOrderId($paypal_orderid);
    die(json_encode($allProducts));
}

function addNewUser($user){
    
    // echo "<pre>";
    // print_r($user);
    // echo "</pre>";

    $flag = true;

    $arr_problem = [];

    if (empty($user['user_name']['val'])){
        $flag = false;
        $arr_problem = "שם מלא ריק (הודעת שרת)";
    }

    if (empty($user['email']['val'])|| !(filter_var($user['email']['val'], FILTER_VALIDATE_EMAIL))){
        $flag = false;
        $arr_problem = "מייל ריק (הודעת שרת)";
    }

    if (empty($user['password']['val'])){
        $flag = false;
        $arr_problem = "סיסמה ריק (הודעת שרת)";
    }

    if (empty($user['verify_password']['val'])){
        $flag = false;
        $arr_problem = "אימות סיסמה ריק (הודעת שרת)";
    }

    if (empty($user['mobile']['val'])){
        $flag = false;
        $arr_problem = "מספר טלפון ריק (הודעת שרת)";
    }

    if (empty($user['city']['val'])){
        $flag = false;
        $arr_problem = "עיר ריק (הודעת שרת)";
    }

    if (empty($user['street']['val'])){
        $flag = false;
        $arr_problem = "רחוב ריק (הודעת שרת)";
    }
    if (empty($user['number_house']['val'])){
        $flag = false;
        $arr_problem = "מספר בית ריק (הודעת שרת)";
    }

    if($flag == false){
        die(json_encode(["type"=>"error","msg"=>$arr_problem]));
    }
    else{
        $user_register = new User();

        $user_register->setFull_name($user['user_name']['val']);
        $user_register->setEmail($user['email']['val']);
        $user_register->setPassword($user['password']['val']);
        $user_register->setMobile($user['mobile']['val']);
        $user_register->setCity($user['city']['val']);
        $user_register->setStreet($user['street']['val']);
        $user_register->setHouse_number($user['number_house']['val']);

    //         echo "<pre>";
    // print_r($user_register);
    // echo "</pre>";
        // die("cgxjklbhfd");
        $db = new dbClass();
        $answer = $db->insertNewUser($user_register);
        if($answer["flag"] == -1){
            die(json_encode(["type"=>"error", "msg" =>[$user['email']['val']." כבר קיים במערכת "]]));
        }
        if($answer["flag"] == 1){

            $_SESSION["user_id"] = $answer["lastInsertId"];
            $_SESSION["full_name"] = $user['user_name']['val'];
            $_SESSION["user_picture"] = '';
            $_SESSION["user_type"] = 1;

            die(json_encode(["type"=>"success", "msg" =>["פרטיך נשמרו במערכת"]]));
        }
        if($answer["flag"]== 0){
            die(json_encode(["type"=>"error", "msg"=>["not saved"]]));
        }
    }    
}

function editComponent($component) {

    $flag = true;

    $arr_problem = [];

    if (empty($component['component_name']['val'])){
        $flag = false;
        $arr_problem = "שם רכיב ריק (הודעת שרת)";
    }

    if (empty($component['amount']['val'])){
        $flag = false;
        $arr_problem = "כמות רכיב ריק (הודעת שרת)";
    }

    if($flag == false){
        die(json_encode(["type"=>"error","msg"=>$arr_problem]));
    }
    else{
        $add_component = new Component();

        $add_component->setComponent_name($component['component_name']['val']);
        $add_component->setAmount($component['amount']['val']);
        $add_component->setComponent_id($component['id']['val']);

   
        $db = new dbClass();
        $answer = $db->updateComponent($add_component);

        if($answer["flag"] == -1){
            die(json_encode(["type"=>"error", "msg" =>[$component['component_name']['val']." כבר קיים במערכת "]]));
        }

        if($answer["flag"] == 1){
            die(json_encode(["type"=>"success", "msg" =>["פרטי רכיב במערכת"]]));
        }
        if($answer["flag"]== 0){
            die(json_encode(["type"=>"error", "msg"=>["not saved"]]));
        }
    }   


}

function addNewComponent($component){
   // echo "<pre>";
   // print_r($component);
   // echo "</pre>";

    $flag = true;

    $arr_problem = [];

    if (empty($component['component_name']['val'])){
        $flag = false;
        $arr_problem = "שם רכיב ריק (הודעת שרת)";
    }

    if (empty($component['amount']['val'])){
        $flag = false;
        $arr_problem = "כמות רכיב ריק (הודעת שרת)";
    }

    if($flag == false){
        die(json_encode(["type"=>"error","msg"=>$arr_problem]));
    }
    else{
        $add_component = new Component();

        $add_component->setComponent_name($component['component_name']['val']);
        $add_component->setAmount($component['amount']['val']);

        $db = new dbClass();
        $answer = $db->insertNewComponent($add_component);

        if($answer["flag"] == -1){
            die(json_encode(["type"=>"error", "msg" =>[$component['component_name']['val']." כבר קיים במערכת "]]));
        }

        if($answer["flag"] == 1){
            die(json_encode(["type"=>"success", "msg" =>["פרטי רכיב במערכת"]]));
        }
        if($answer["flag"]== 0){
            die(json_encode(["type"=>"error", "msg"=>["not saved"]]));
        }
    }   
}


function update_component_to_exists_product( $product_id,$component_id,$amount) {

    $db = new dbClass();
    $answer = $db->updateComponentsToExistsProduct($product_id,$component_id,$amount);




}

function remove_component_to_product( $id_row) {
    $db = new dbClass();
    $answer = $db->removeComponentsExistsProduct($id_row);
}

function update_component_to_product( $components,$last_id) {

    $db = new dbClass();
    $answer = $db->updateComponents($components,$last_id);

}

function addNewProduct($new_product, $new_files){
    // echo "<pre>";
    // print_r($new_files);
    // echo "</pre>";
    // die("130");

   

    $flag = true;
    $arr_problem = [];

    if (empty($new_product['product_name'])){
        $flag = false;
        $arr_problem = "שם מוצר ריק (הודעת שרת)";
    }

    if (empty($new_product['product_price'])){
        $flag = false;
        $arr_problem = "מחיר מוצר ריק (הודעת שרת)";
    }

    if (empty($new_product['product_description'])){
        $flag = false;
        $arr_problem = "תיאור מוצר ריק (הודעת שרת)";
    }

    if (empty($new_files['product_picture']['name'])){
        $flag = false;
        $arr_problem = "תמונת מוצר ריק (הודעת שרת)";
    }

    if($flag == false){
        die(json_encode(["type"=>"error","msg"=>$arr_problem]));
    }
    else{
        $add_product = new Product();

        $add_product->setProduct_name($new_product['product_name']);
        $add_product->setProduct_price($new_product['product_price']);
        $add_product->setProduct_description($new_product['product_description']);
        

        $img_name = $new_files['product_picture']['name'];
        $tmp_path = $new_files['product_picture']['tmp_name'];
        // $file_content = file_get_contents($tmp_path);
        $new_path = "images/".$img_name;
        move_uploaded_file($tmp_path, "../".$new_path);

    

        $add_product->setProduct_picture($new_path);

        $db = new dbClass();
        $answer = $db->insertNewProduct($add_product);
        
        if($answer["flag"] == -1){
            die(json_encode(["type"=>"error", "msg" =>[$new_product['product_name']." כבר קיים במערכת "]]));
        }

        if($answer["flag"] == 1){
            die(json_encode(["type"=>"success","last_id" => $answer["last_id"] , "msg" =>["פרטי מוצר במערכת"]]));
        }
        if($answer["flag"]== 0){
            die(json_encode(["type"=>"error", "msg"=>["not saved"]]));
        }
    }   
}

function setCancelOrder($order_id) {
    $db = new dbClass();
    $details = $db->getDetailsOfUserByOrder_id($order_id);

    $from = "dbconfectionery1@gmail.com";
    $fromName = "Confectionery";
    $subject = "הודעה על ביטול הזמנתך באתרנו";
    $to = $details["email"];
    $htmlMsg = "<p>לקוח יקר. הרינו להודיעך כי הזמנתך מספר $order_id מבוטלת ולא תסופק. אנו מצטערים אך נשמח לשרת אותך בעתיד.</p>";
    $ans = sendMailTemplate($from,$fromName,$subject, $to, $htmlMsg);

    var_dump($ans);
    die;
    $db->setCancelOrder($order_id);


} 



function setHandleOrder($order_id) {
    $db = new dbClass();
    $db->setHandleOrder($order_id);
} 

function setDoneOrder($order_id) {
    $db = new dbClass();
    $db->setDoneOrder($order_id);

}

function addNewOrder($order){
    echo "<pre>";
    print_r($order);
    echo "</pre>";

    $flag = true;

    $arr_problem = [];

    if (empty($order['user_order']['val'])){
        $flag = false;
        $arr_problem = "שם המזמין ריק (הודעת שרת)";
    }

    if (empty($order['address']['val'])){
        $flag = false;
        $arr_problem = "כתובת ריק (הודעת שרת)";
    }

    if (empty($order['tottal_price']['val'])){
        $flag = false;
        $arr_problem = "סהכ מחיר ריק (הודעת שרת)";
    }

    if (empty($order['delivery_date']['val'])){
        $flag = false;
        $arr_problem = "תאריך משלוח ריק (הודעת שרת)";
    }

    if (empty($order['order_status']['val'])){
        $flag = false;
        $arr_problem = "סטטוס הזמנה ריק (הודעת שרת)";
    }

    if (empty($order['download_order']['val'])){
        $flag = false;
        $arr_problem = "הורד הזמנה ריק (הודעת שרת)";
    }
}

function loginUser($login){
    // echo "<pre>";
    // print_r($login);
    // echo "</pre>";
    // die('257');

    $flag = true;

    $arr_problem = [];

    if (empty($login['email']['val'])){
        $flag = false;
        $arr_problem = "Sharat email";
    }

    if (empty($login['password']['val'])){
        $flag = false;
        $arr_problem += "Sharat Password";
    }

    if($flag == false){
        die(json_encode(["type"=>"error","msg"=>$arr_problem]));
    }

    $db = new dbClass();
    $answer = $db->checkLogin($login['email']['val'], $login['password']['val']);

    if($answer["flag"]== -2){
        die(json_encode(["type" => "error","msg" => ["שגיאת שרת חמורה. נא ליצור קשר עם מתכנתי האתר"]]));

    }

    if($answer["flag"]== -1){
        die(json_encode(["type" => "error","msg" => ["שגיאה, משתמש לא קיים במערכת"]]));
    }

    if($answer["flag"]== 1){


        $detailsUser = $db->getUserDetails($login['email']['val']);
        $user_id = $detailsUser["user_id"];
        $full_name = $detailsUser["full_name"];
        $user_picture = $detailsUser["user_picture"];
        $user_type = $detailsUser["user_type"];

        $_SESSION["user_id"] = $user_id;
        $_SESSION["full_name"] = $full_name;
        $_SESSION["user_picture"] = $user_picture;
        $_SESSION["user_type"] = $user_type;

        die(json_encode(["type" => "success","msg" => ["המנהל מגיע"]]));
    }

    if($answer["flag"]== 0){
        $detailsUser = $db->getUserDetails($login['email']['val']);
        $user_id = $detailsUser["user_id"];
        $full_name = $detailsUser["full_name"];
        $user_picture = $detailsUser["user_picture"];
        $user_type = $detailsUser["user_type"];

        $_SESSION["user_id"] = $user_id;
        $_SESSION["full_name"] = $full_name;
        $_SESSION["user_picture"] = $user_picture;
        $_SESSION["user_type"] = $user_type;
        
        // echo "<pre>";
        // print_r($_SESSION);
        // echo "</pre>";
        die(json_encode(["type" => "success","msg" => ["משתמש צעיר נכנס"]]));
    }

    if($answer["flag"]== 2){
        die(json_encode(["type" => "error","msg" => ["סיסמה לא נכונה"]]));
    }
}
?>