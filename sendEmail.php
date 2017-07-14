<?php
    //ini_set('display_errors', 1);
    //ini_set('display_startup_errors', 1);
    //error_reporting(E_ALL);
    
    require './vendor/autoload.php';
    
    $id_redemption = $_POST['rid'];
    //$id_redemption = $_GET['rid'];
     
    $servername = "localhost";
    $username = "melawatimall";
    $password = "mm2017";
    $dbname = "melawatimall";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT c.thankyoumessage AS TQMessage, r.idUser AS UserID, r.id AS RedemptionID, r.pdf AS PDFFile, u.email AS UserEmail, u.name AS Name, r.id AS RedID FROM categories c LEFT JOIN redemptions r ON r.idCategory = c.id LEFT JOIN users u ON r.idUser = u.id WHERE r.id = " . $id_redemption;
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'twsolutions.com.my';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'web@melawatimallopening.com';                 // SMTP username
            $mail->Password = 'web123';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            $mail->setFrom('web@melawatimallopening.com', 'Melawati Mall');
            $mail->addAddress($row['UserEmail'], $row['Name']);     // Add a recipient

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('http://www.melawatimallopening.com/mmadmin/thankyou/' . $row['RedemptionID'] . '?download=pdf', 'voucher.pdf');    // Optional name
            $mail->addAttachment('/home/ubuntu/public_html/tmp/' . $row['PDFFile'], $row['PDFFile']);         // Add attachments
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'Voucher Redemption - MM' . $row['UserID'] . $row['RedemptionID'];
            /*$body_content = 'Thank you for joining us!<br />'
                            . 'Below is the QR code that you will be needing to redeem the voucher<br />'
                            . '<img src="https://api.qrserver.com/v1/create-qr-code/?data=http://192.168.1.117:8008/mmadmin/user/' . $row['UserID'] . '&amp;size=150x150&amp;color=F26B3C&amp;qzone=1&amp;margin=0" alt="" />';
            */
            $body_content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                            <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                              <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                              <title>MELAWATI MALL VOUCHER</title>
                              <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                              <style type="text/css">
                                html{width: 100%; background:#fff; font-family: "Open Sans", sans-serif;}
                                h1 {font-family: "Open Sans", sans-serif; color: #6D6E71; font-size: 1.1em; margin: 0; font-weight: 400;}
                                h2 {font-family: "Open Sans", sans-serif; color: #F26B3C; font-size: .9em; font-weight: 500;}
                                span { color: #FBB03B; font-weight: 700; font-family: "Open Sans", sans-serif;}
                              </style> 
                            </head>
                            <body style="margin: 0; padding: 0;">
                              <table align="center" cellpadding="0" cellspacing="0" width="800" style="border-collapse: collapse;">
                                <tr>
                                    <td align="left" bgcolor="#000" style="padding: 0;">
                                      <img src="http://www.melawatimallopening.com/images/mm/slip-header.jpg"  style="display: block; width: 100%;" alt="Melawati Mall Voucher">
                                  </td>
                                </tr>


                                <tr>
                                    <td align="center" bgcolor="#fff" style="padding: 40px 40px 10px ;">
                                      <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                          <td width="260" valign="top" align="center">
                                            <h1>We have postponed the redemption period dates.</h1>
                                          </td>  
                                        </tr>
                                        <tr>
                                          <td width="260" valign="top" align="center">
                                            <h1>Here is your new QR Code!</h1>
                                          </td>  
                                        </tr>
                                        <tr>
                                          <td width="260" valign="top" align="center">
                                            <h1>Download & print this slip to redeem your rewards!</h1>
                                          </td>  
                                        </tr>
                                      </table> 
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center" bgcolor="#fff" style="padding: 0 30px 40px ;">
                                      <table cellpadding="0" cellspacing="0" width="100%">
                                      <tr>
                                        <td width="260" valign="top" align="center">
                                          &nbsp;
                                        </td>

                                        <td width="260" valign="top" align="center">
                                          MM' . $row['UserID'] . $row['RedID'] . '
                                          <img src="https://api.qrserver.com/v1/create-qr-code/?data=http://www.melawatimallopening.com/mmadmin/user/' . $row['UserID'] . '&amp;size=192x112&amp;color=F26B3C&amp;qzone=1&amp;margin=0" style="display: block;  padding: 15px;" width="100" alt="Melawati Mall Voucher" />
                                        </td>

                                        <td width="260" valign="top" align="center">
                                          &nbsp;
                                        </td> 
                                      </tr>
                                    </table>
                                  </td>
                                </tr>

                                <tr>
                                    <td align="center" bgcolor="#fff" style="padding: 0px 40px 10px ;">
                                      <h2>HOW IT WORKS?</h2>
                                    </td>
                                </tr>


                                <tr>
                                    <td align="center" bgcolor="#fff" style="padding: 0 30px 50px ;">
                                      <table cellpadding="0" cellspacing="0" width="100%">
                                      <tr>
                                        <td width="260" valign="top" align="center">
                                          <img src="http://www.melawatimallopening.com/images/mm/step2.png" align="center" alt="Step 1"  width="100%"> 
                                        </td>

                                        <td width="260" valign="top" align="center">
                                          <img src="http://www.melawatimallopening.com/images/mm/step3.png" align="center" alt="Step 2"  width="100%">  
                                        </td>

                                        <td width="260" valign="top" align="center">
                                          <img src="http://www.melawatimallopening.com/images/mm/step4.png" align="center" alt="Step 3"  width="100%"> 
                                        </td> 
                                      </tr>
                                    </table>
                                  </td>
                                </tr>

                                <tr>
                                    <td align="center" bgcolor="#F26B3C" style="padding: 10px ;"">
                                      <a  href="http://www.melawatimallopening.com/doc/Terms&ConditionMM.pdf" target="_blank"><h2 style="color: #fff; padding: 10px; border: 1px dashed #fff; margin: 0;">*TERMS & CONDITIONS</h2></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="left" bgcolor="#fff" style="padding:20px ;background-color: #F2F2F2;">
                                      <table cellpadding="0" cellspacing="0" width="100%">
                                      <tr>
                                        <td width="250" valign="top" align="left">
                                           <table cellpadding="0" cellspacing="0" width="90%">
                                            <tr>
                                              <td width="260" valign="top" align="left">
                                                <span style="color: #676868; font-weight: 400; font-size: .8em;">SelfPrint ticket must be printed out and presented at the information counter to gain rewards from Melawati Mall.</span> <br><br>
                                              </td>  
                                            </tr>

                                            <tr>
                                              <td width="260" valign="top" align="left">
                                                <span style="color: #676868; font-weight: 400; font-size: .8em;">First come first serve gift vouchers* will be given out daily.</span> <br><br>
                                              </td>  
                                            </tr>

                                            <tr>
                                              <td width="260" valign="top" align="left">
                                                <span style="color: #676868; font-weight: 400; font-size: .8em;">Bring the slip to the mall between 1 August 2017 - 15 September 2017 to claim your gift vouchers* and stand a chance to win up to <span style="color: #EC2227;">RM25,000 cash vouchers*</span> !</span> <br><br>
                                              </td>  
                                            </tr>

                                            <tr>
                                              <td width="260" valign="top" align="left">
                                                <a href="http://www.melawatimallopening.com/doc/Terms&ConditionMM.pdf" target="_blank" style="color: #676868; font-weight: 400; font-size: .8em; font-family: "Open Sans", sans-serif !important;">*Terms and Conditions apply</a> <br><br>
                                              </td>  
                                            </tr>
                                          </table> 
                                        </td> 

                                        <td width="50" valign="center" align="center">
                                          <img src="http://www.melawatimallopening.com/images/mm/logo-black.png" align="center" alt="Step 4"  width="100%">
                                        </td> 
                                      </tr>
                                    </table>
                                  </td>
                                </tr>

                                <tr>
                                   <td align="right" style="padding:20px ;background-color: #fff;">
                                     <table border="0" cellpadding="0" cellspacing="0">
                                      <tr>
                                        <td>
                                        <a href="http://www.melawatimallopening.com/">
                                         <span style="text-decoration: none; color: #676868; margin-right: 1em;">www.melawatimallopening.com</span>
                                        </a>
                                       </td>
                                       <td>
                                        <a href="https://www.facebook.com/MelawatiMallOfficial/">
                                         <img src="http://www.melawatimallopening.com/images/mm/fb.png" alt="Facebook" width="38" height="38" style="display: block;" border="0" />
                                        </a>
                                       </td>
                                       <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                                       <td>
                                        <a href="https://www.instagram.com/melawatimall/">
                                         <img src="http://www.melawatimallopening.com/images/mm/insta.png" alt="Instagram" width="38" height="38" style="display: block;" border="0" />
                                        </a>
                                       </td>
                                      </tr>
                                     </table>
                                    </td>
                                  </td>
                                </tr>
                                <tr>
                                    <td align="left" bgcolor="#000" style="padding: 0;">
                                      <p style=" color: #fff; text-align: center; font-size: .9em; padding: .2em 0;">Got a question? <br> Email: <span style="color: #F26B3C">marketing@melawatimall.com</span> | Tel: <span style="color: #F26B3C">03 - 4161 6313</span> <br> Melawati Mall. &copy; 2017 All Rights Reserved</p>
                                  </td>
                                </tr>
                              </table>
                            </body>
                            </html>';           
            
            $mail->Body = $body_content;

            if(!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent';
                return $email->Send();
            }
        }
    } else {
        //echo "Redemption is not available";
        die();
    }
    $conn->close();