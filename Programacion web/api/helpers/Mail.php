
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
class Mail{

    static function sendInvoice($to, $products, $infoExtra){

        $bodyMsg = "
        <html>
  <head></head>
  <body
    style='
      color: rgba(0, 0, 0, 0.712);
      font-family: Arial, Helvetica, sans-serif;
      width: 600px;
      margin: 4em auto;
    '
  >
    <div style='background-color: #f5990f; padding: 10px'>
      <h1 style='color: white; text-align: center'>Remito de compra:</h1>
    </div>
    <div style='margin: auto; padding: 20px; border: 3px #f5990f solid; border-radius: 0 0 3px 3px'>
      <h2 style='color: #000000; text-decoration: underline rgba(245, 175, 61, 0.896)'> GRACIAS POR TU COMPRA!</h2>
      <h3 style='padding-right: 10px'>
        Tu pedido ha sido recibido y se encuentra en estado   " . $infoExtra['status'] . ".
      </h3>
      
      <hr />
      <p style='font-size: 125%'>Fecha:" ."$<strong>" . $infoExtra['date'] . "</strong></p>
      <p style='font-size: 125%'>Metodo de pago:" ."$<strong>" . $infoExtra['payment'] . "</strong></p>
      <p style='font-size: 125%'>Horario de entrega:"."$<strong>" . $infoExtra['time'] . "</strong></p>
      <p style='font-size: 125%'>Direccion de entrega:"."$<strong>" . $infoExtra['address'] . "</strong></p>
      <p style='font-size: 125%'>Total de la compra:"."$<strong>" . $infoExtra['totalSale'] . "</strong></p>
      <hr />
      <h2>Productos comprados:</h2>
        ";

        $index = 0;
        foreach ($products as $product) {
            $bodyMsg .= "
            <h3 style='background-color: #ffc526;'>Producto " . ($index + 1) . "</h3>
            <ul>
              <li>
                <strong>Nombre: </strong>
                <span>" . $product['productName'] . "</span>
              </li>
              <li>
                <strong>Cantidad: </strong>
                <span>" . $product['quantity'] . "</span>
              </li>
              <li>
                <strong>Precio por unidad: </strong>
                <span>" . $product['price'] . "</span>
              </li>
              <li>
                <strong>Precio total</strong>
                <span>" . $product['total'] . "</span>
              </li>
            </ul>
            ";
            $index++;
        }

        $bodyMsg .= "<hr />
        <i>Natalia Viera: seguridad corporal</i>
      </div>
    </body>
  </html>
  ";

        $mail = new PHPMailer(true);
        try {

            //Server settings
            $mail->SMTPDebug = false;                      
            $mail->isSMTP();                                           
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'seguridadcorporalnv@gmail.com';                     
            $mail->Password   = 'keyoxlrlntpcxnuv';                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           
            $mail->Port       = 465;                                   

            //Recipients
            $mail->setFrom('seguridadcorporalnv@gmail.com', 'Seguridad Corporal - Natalia Viera');
            $mail->addAddress($to, 'Compra web');

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'COMPRA WEB';
            $mail->Body    = $bodyMsg;
            $mail->AltBody = '.';
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    
    static function getConsult($client, $subject, $text){

        $bodyMsg = "
        <h1>NUEVA CONSULTA! </h1>
        <p><strong>CLIENTE: </strong>" . $client . "</p>
        <p><strong>Asunto:  </strong>" . $subject . "</p>
        <p><strong>Consulta:</strong>" . $text . "</p>";
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = false;                      
            $mail->isSMTP();                                           
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'seguridadcorporalnv@gmail.com';                     
            $mail->Password   = 'keyoxlrlntpcxnuv';                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           
            $mail->Port       = 465;                                   

            //Recipients
            $mail->setFrom('seguridadcorporalnv@gmail.com', 'Seguridad Corporal - Natalia Viera');
            $mail->addAddress('seguridadcorporalnv@gmail.com', 'NUEVA CONSULTA!');

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'CONSULTA DE CLIENTE';
            $mail->Body    = $bodyMsg;
            $mail->AltBody = '.';
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
  }




?>
