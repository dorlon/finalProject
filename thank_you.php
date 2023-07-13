<?php include_once "./header_html.php";
require_once ("./cart.php"); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/server/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/server/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/server/vendor/phpmailer/src/SMTP.php';


if (empty($_GET['order_id'])) {
  // move to cancel...
}

function send_mail_after_finish($from,$fromName,$subject, $to, $htmlMsg) {


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

function build_html_mail($user_id,$paypalnum,$fullname ) {
   
    $db = new dbClass();
    $cart_items_response = get_cart_items_by_user_id($user_id);
    $cart_items_array = $cart_items_response['cart_items'];
    $html = "<h2>תודה $fullname על הזמנתך , להלן פרוט הזמנה מספר $paypalnum </h2>";
    $html .= <<<HEREDOC
     <table style="width:100%;border: 1px solid black;">
    <tr>
      <th style='border: 1px solid black;'>מקט</th>
      <th style='border: 1px solid black;'>שם מוצר</th> 
      <th style='border: 1px solid black;'>כמות</th>
      <th style='border: 1px solid black;'>מחיר יחידה</th>
      <th style='border: 1px solid black;'>סהכ</th>
    </tr>
    HEREDOC;
    $total_order = 0;
    
    foreach($cart_items_array as $key => $value) { $total_price_prod = (int)$value['quantity'] * (int)$value['product_price']; $total_order += $total_price_prod;
        $html.= <<<HEREDOC
                     <tr>
                    <td style='border: 1px solid black;'>{$value['product_id']}</td>
                    <td style='border: 1px solid black;'>{$value['product_name']}</td>
                    <td style='border: 1px solid black;'>{$value['quantity']}</td>
                    <td style='border: 1px solid black;'>{$value['product_price']}</td>
                    <td style='border: 1px solid black;'>{$total_price_prod}</td>
                </tr>
                HEREDOC;
    }

    $html.= "</table>";
    $html.= "<p>סכום עסקה סופי: $total_order שח</p>";

    return $html;


}



$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];
$total = $_GET['total'];
$date = date("Y-m-d H:i:s");

$order_obj = new Order($order_id,$user_id,$total,$date);

$user_details = $order_obj->getUserDetails($user_id);
$html_items = build_html_mail($user_id,$order_id,$user_details['full_name']);
$ans = send_mail_after_finish('dbconfectionery1@gmail.com',"Confectionery","אישור הזמנה באתר confectionery",$user_details['email'] , $html_items );


$order_obj->cleanStock();
$answer = $order_obj->createNewOrder();
if ($answer['flag'] == 1) {
  $answer2 = $order_obj->createOrdersParts($answer['last_id']);
  if ($answer2['flag'] == 1) {
    $order_obj->removeCartAfterFinish();
  }
}
print_r($answer);
// die;

//



// echo "<pre>";
// var_dump($order_obj );
// die;




?>


<div class="site-title text-center">
  <div class="container">
    <h1 class="font-title">התשלום בוצע בהצלחה</h1>
    <div><img src="images/V.png" alt="" width="250" height="250"></div>
  </div>
</div>

<script>
$("#red-ball").text("0");
      $("#red-ball").hide();

</script>



<?php
    include_once "./newfooter.php"; 
?>