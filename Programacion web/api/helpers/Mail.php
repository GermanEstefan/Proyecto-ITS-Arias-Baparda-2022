
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Mail{

    static function sendInvoice($to, $products, $infoExtra){

        $bodyMsg = "
        <h1>Remito de compra: </h1>
        <p><strong>Fecha: </strong>" . $infoExtra['date'] . "</p>
        <p><strong>Metodo de pago: </strong>" . $infoExtra['payment'] . "</p>
        <p><strong>Horario de entrega: </strong>" . $infoExtra['time'] . "</p>
        <p><strong>Direccion de entrega: </strong>" . $infoExtra['address'] . "</p>
        <p><strong>Total de la compra: </strong>" . $infoExtra['totalSale'] . "$</p>
        <h2>Productos comprados: </h2>
        ";

        $index = 0;
        foreach ($products as $product) {
            $bodyMsg .= "
                <h3>Producto " . ($index + 1) . "</h3>
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
}


?>
