<?php
require_once "vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

$red = rand(0, 225);
$green = rand(0, 225);
$blue = rand(0, 225);
$red1 = rand(0, 225);
$green1 = rand(0, 225);
$blue1 = rand(0, 225);

// CREATE QRCODE FUNCTION
function createQRCode($qrCodeData, $forColor = [0, 0, 0], $bgColor = [255, 255, 255], $size = 300, $margin = 10)
{
    // Create a QR code with custom colors, a logo, and high error correction level
    $qrCode = new QrCode(
        data: $qrCodeData
            ->setSize($size)
            ->setMargin($margin)
            ->setForegroundColor(new Color($forColor[0], $forColor[1], $forColor[2]))
            ->setBackgroundColor(new Color($bgColor[0], $bgColor[1], $bgColor[2]))
    );

    $label = new Label(
        text: '',
        textColor: new Color(255, 0, 0),
    );

    $writer = new PngWriter;
    $result = $writer->write($qrCode, null, null);

    // // Save the QR code to a file
    // $filename = "images/qrimage/" . time() . ".png";
    // $result->saveToFile($filename);

    // Output the QR code directly
    $_SESSION['qrImageData'] = $result->getDataUri();
    $_SESSION['qrImageTimestamp'] = time(); // Store the current timestamp
    return $_SESSION['qrImageData'];
}

// Check if the session variable should be unset
if (isset($_SESSION['qrImageTimestamp'])) {
    $currentTime = time();
    $sessionTime = $_SESSION['qrImageTimestamp'];
    $timeoutDuration = 300; // 5 minutes in seconds

    if ($currentTime - $sessionTime > $timeoutDuration) {
        unset($_SESSION['qrImageData']);
        unset($_SESSION['qrImageTimestamp']);
    }
}

// Handle customization form submission
if (isset($_POST['btnCustomize'])) {
    $forColor = sscanf($_POST['foregroundColor'], "#%02x%02x%02x");
    $bgColor = sscanf($_POST['backgroundColor'], "#%02x%02x%02x");
    $size = intval($_POST['size']);
    $margin = intval($_POST['margin']);

    // Use the last generated QR code data for customization
    $qrCodeData = $_SESSION['lastQrCodeData'] ?? 'Sample QR Code';

    createQRCode($qrCodeData, $forColor, $bgColor, $size, $margin);

    // Return the updated QR code data as a JSON response
    // echo json_encode([
    //     'qrImageData' => $_SESSION['qrImageData'],
    //     'lastQrCodeData' => $_SESSION['lastQrCodeData']
    // ]);
    // exit;
}

// URL QR CODE
if (isset($_REQUEST['btnUrl'])) {
    $qrUrlData = $_REQUEST['qrUrl'];
    $_SESSION['lastQrCodeData'] = $qrUrlData;
    createQRCode($qrUrlData);
}

// TEXT QR CODE
if (isset($_REQUEST['btnText'])) {
    $qrTextData = $_REQUEST['qrText'];
    $_SESSION['lastQrCodeData'] = $qrTextData;
    createQRCode($qrTextData);
}

// SMS QR Code MESSAGES
if (isset($_REQUEST['btnSMS'])) {
    // Format the data for an SMS QR code
    $qrSMSData = 'smsto:' . $_REQUEST['qrSMSnum'] . ':' . $_REQUEST['qrSMSmsg'];

    createQRCode($qrSMSData, [$red, $green, $blue], [$red1, $green1, $blue1]);
}

// WIFI QR Code
if (isset($_REQUEST['btnWifi'])) {
    $qrWifiName = $_REQUEST['qrWifiName'];
    $qrWifiPass = $_REQUEST['qrWifiPass'];
    $qrWifiSec = $_REQUEST['qrWifiSec'];

    // Create the Wi-Fi URI for the QR code (format: WIFI:T:<encryption>;S:<SSID>;P:<password>;;)
    $wifiUri = "WIFI:T:$qrWifiSec;S:$qrWifiName;P:$qrWifiPass;";

    createQRCode($wifiUri, [$red, $green, $blue], [$red1, $green1, $blue1]);
}

// CALL QR CODE
if (isset($_REQUEST['btnCall'])) {
    // Format the data for a phone call QR code
    $qrCallData = 'tel:' . $_REQUEST['qrPhoneNumber'];

    createQRCode($qrCallData, [$red, $green, $blue], [$red1, $green1, $blue1]);
}

// VCARD QR codes
if (isset($_REQUEST['btnVCard'])) {
    // Format the data for a vCard QR code
    $vCardData = "BEGIN:VCARD\n";
    $vCardData .= "VERSION:3.0\n";
    $vCardData .= "FN:$_REQUEST[qrVcardName]\n";
    $vCardData .= "TEL:$_REQUEST[qrVcardNum]\n";
    $vCardData .= "EMAIL:$_REQUEST[qrVcardEmail]\n";
    $vCardData .= "ADR:$_REQUEST[qrVcardAdd]\n";
    $vCardData .= "END:VCARD";

    createQRCode($vCardData, [$red, $green, $blue], [$red1, $green1, $blue1]);
}

// EMAIL QR CODE
if (isset($_REQUEST['btnEmail'])) {

    $qrEmailData = "mailto:$_REQUEST[qrEmail]?subject=$_REQUEST[qrSubject]&body=$_REQUEST[qrMessage]";

    createQRCode($qrEmailData, [$red, $green, $blue], [$red1, $green1, $blue1]);
}

// WHATSAPP QR CODE 
if (isset($_REQUEST['btnWhatsApp'])) {
    // Format the data for a WhatsApp message QR code
    $qrCodeData = 'https://wa.me/' . urlencode($_REQUEST['qrWhatsappNum']) . '?text=' . urlencode($_REQUEST['qrWhatsappMsg']);

    createQRCode($qrCodeData, [$red, $green, $blue], [$red1, $green1, $blue1]);
}


// $logo = Logo::create('https://th.bing.com/th/id/R.24b5c5331d3737ab69b2d4e631b35596?rik=9rxLG2wT6VCazA&riu=http%3a%2f%2fpluspng.com%2fimg-png%2fwhatsapp-png-wazapp-logo-whats-whatsapp-logo-whatsapp-icon-2050.png&ehk=Spk3ZDnOuA%2bBHep8M1XmGD7BVSPDFPev4tIe8OW694Y%3d&risl=&pid=ImgRaw&r=0')
//     ->setResizeToWidth(70);
