<?php
require_once "directories.php";
require_once "vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

if (isset($_REQUEST['btn_generate'])) {
   $qrtext = $_REQUEST['qrtext'];

   $qrcode = new QRcode($qrtext);
   $writer = new PngWriter;

   $result = $writer->write($qrcode);

   // header("Content-Type: " . $result->getMimeType());
   // echo $result->getString();

   $qrimage = $result->getString();

   // $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

   $result->saveToFile("images/qrimage/" . time() . ".png");

   // $query = $con->query("INSERT INTO `qr-codes` SET `qrtext`='$qrtext', `qrimage`='" . time() . ".png" . "'");
   //    if ($query) {
   // 
?>
   <script>
      alert("Data saved");
   </script>
<?php

}
// }
