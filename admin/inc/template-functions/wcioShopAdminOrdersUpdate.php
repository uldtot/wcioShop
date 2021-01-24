<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$cart_id = $_GET["id"] ?? "";
if($cart_id == "") {
      header("Location: /admin/orders/");
}

// Get cart data
$stmt = $dbh->prepare("SELECT * FROM wcio_se_porders WHERE cart_id = :cart_id LIMIT 1");
$result = $stmt->execute(array(
      "cart_id" => $cart_id,
));

$data = $stmt->fetch(PDO::FETCH_ASSOC);

      $wcioShopAdminOrders = array();

      foreach( $data AS $key => $value ) {

            $wcioShopAdminOrders[$key] = $value;

      }

// Get other data

$orderAction = $_POST["orderAction"] ?? "";
$orderActionUpdate = $_POST["orderActionUpdate"] ?? "";

$orderAdminNotes = $_POST["orderAdminNotes"] ?? "";
$orderAdminNotesUpdate = $_POST["orderAdminNotesUpdate"] ?? "";

$orderStatus = $_POST["orderStatus"] ?? "";
$orderStatusUpdate = $_POST["orderStatusUpdate"] ?? "";


// Get mail settings
$wcioShopAdminSettings = array();

$stmt = $dbh->prepare("SELECT settingSecondaryGroup,columnName,columnValue FROM wcio_se_settings WHERE settingSecondaryGroup = 'Mail service' AND columnName != 'wcioShopAdminSettingsMenu' ORDER BY settingSecondaryGroup,settingOrder,columnNiceName");
$result = $stmt->execute();

  while($data = $stmt->fetch(PDO::FETCH_ASSOC))
  {

        $settingSecondaryGroup = $data['settingSecondaryGroup'];

        $wcioShopAdminSettings[$data['columnName']] = array
        (
              "columnName" => $data['columnName'],
              "columnValue" => $data['columnValue'],
        );
  }




// Order action update
if(isset($orderAction) && $orderActionUpdate == "1") {

      // Order action send to customer
      if($orderAction == "send_order_details") {

            $orderActionTo = $wcioShopAdminOrders["email"];
            $orderActionFrom = $_SETTING["storeSaleEmail"];
            $orderActionSubject = "Order notification - Order: $cart_id";
            $orderActionMessage = "Thank you for your order: $cart_id\r\n
            Note: This is not an order confirmation, but just a copy of what we have registered in connection with your order. You will get another e-mail when we have accepted your order.";

      }

      // Order action send to admin
      if($orderAction == "send_order_details_admin") {

            $orderActionTo = $_SETTING["storeSaleNotificationEmail"];
            $orderActionFrom = $_SETTING["storeSaleEmail"];
            $orderActionSubject = "New order notification: $cart_id";
            $orderActionMessage = "There is a new order in your store.";

      }

      if($wcioShopAdminSettings["storeMailSerivce"]["columnValue"] == "phpmail") {

            $headers = 'From: '.$orderActionFrom.'' . "\r\n" .
                        'Reply-To: '.$orderActionFrom.'' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

            mail($orderActionTo, $orderActionSubject, $orderActionMessage, $headers);

      } else if($wcioShopAdminSettings["storeMailSerivce"]["columnValue"] == "smtp") {
            // SMTP
      /*       use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;

            require dirname(__FILE__)."../vendor/PHPMailer/PHPMailer/src/Exception.php";
            require dirname(__FILE__)."../vendor/PHPMailer/PHPMailer/src/PHPMailer.php";
            require dirname(__FILE__)."../vendor/PHPMailer/PHPMailer/src/SMTP.php";

            // Instantiation and passing `true` enables exceptions
           $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.example.com';                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = 'user@example.com';                     // SMTP username
                $mail->Password   = 'secret';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom('from@example.com', 'Mailer');
                $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
                $mail->addAddress('ellen@example.com');               // Name is optional
                $mail->addReplyTo('info@example.com', 'Information');
                $mail->addCC('cc@example.com');
                $mail->addBCC('bcc@example.com');

                // Attachments
                $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Here is the subject';
                $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            */

      }

      header("Location: /admin/orders/view/?id=$cart_id");
      exit;

}


// Order status update
if(isset($orderStatus) && $orderStatusUpdate == "1") {

      $allowedStatus = array(
            "pending",
            "processing",
            "on-hold",
            "completed",
            "cancelled",
            "refunded",
            "failed",
      );

      if( !in_array($orderStatus,$allowedStatus) ) {
            header("Location: /admin/orders/view/?id=$cart_id");
            exit;
      }

      $stmt = $dbh->prepare("UPDATE wcio_se_porders SET cart_status=:cart_status WHERE cart_id = :cart_id");
      $result = $stmt->execute(array(
      	"cart_id" => $cart_id,
            "cart_status" => $orderStatus
      ));

      header("Location: /admin/orders/view/?id=$cart_id");
      exit;

}

// Order admin Notes
if(isset($orderAdminNotes) && $orderAdminNotesUpdate == "1") {
      $stmt = $dbh->prepare("UPDATE wcio_se_porders SET AdminNotes = :adminnotes WHERE cart_id = :cart_id");
      $result = $stmt->execute(array(
      	"adminnotes" => $orderAdminNotes,
      	"cart_id" => $cart_id,
      ));

      header("Location: /admin/orders/view/?id=$cart_id");
      exit;
}

// Order status update

//Order action update
