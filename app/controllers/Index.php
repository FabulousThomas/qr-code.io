<?php
if (file_exists(APPROOT . '/../vendor/autoload.php')) {
    require_once APPROOT . '/../vendor/autoload.php';
} else {
    require_once 'vendor/autoload.php';
}
// If Endroid classes are still unavailable (e.g. split vendor trees), try public/vendor
if (!class_exists('Endroid\\QrCode\\QrCode') && file_exists(APPROOT . '/../public/vendor/autoload.php')) {
    require_once APPROOT . '/../public/vendor/autoload.php';
}

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;

class Index extends Controller
{
    private $formData = [];

    public function __construct()
    {
        error_log("Index controller constructor called");
    }
    public function index()
    {
        error_log("Index controller index method called");
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . print_r($_POST, true));
        // When user returns to generator after a completed payment, start a fresh session for locking
        if (isset($_SESSION['payment_completed']) && $_SESSION['payment_completed']) {
            unset(
                $_SESSION['payment_completed'],
                $_SESSION['payment_reference'],
                $_SESSION['customer_email'],
                $_SESSION['payment_amount'],
                $_SESSION['payment_date']
            );
        }

        // Check if the session variable should be unset
        if (isset($_SESSION['qrImageTimestamp'])) {
            $currentTime = time();
            $sessionTime = $_SESSION['qrImageTimestamp'];
            $timeoutDuration = 3600; // 1 hour in seconds

            if ($currentTime - $sessionTime > $timeoutDuration) {
                unset($_SESSION['qrImageData']);
                unset($_SESSION['qrImageTimestamp']);
                unset($_SESSION['customize']);
                unset($_SESSION['lastQrCodeData']);
                unset($_SESSION['logoPath']);
            }
        }

        // Call the customization function
        $this->customizeQRCode();
        // Call the generateQRCode function
        $this->generateQRCode();

        // View page
        $this->view('index');
    }

    // Function to handle the customization of the QR code
    private function customizeQRCode()
    {
        // Handle customization form submission
        if (isset($_REQUEST['btnCustomize'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['csrf_token']) || !function_exists('csrf_verify') || !csrf_verify($_POST['csrf_token'])) {
                    alert('message', 'Invalid or missing CSRF token');
                    return;
                }
            }
            $_forColor = sscanf($_REQUEST['foregroundColor'], "#%02x%02x%02x");
            $_bgColor = sscanf($_REQUEST['backgroundColor'], "#%02x%02x%02x");
            $_size = intval($_REQUEST['size']);
            $_margin = intval($_REQUEST['margin']);
            $_labelText = $_REQUEST['labelText'];
            // Handle the logo upload
            $_logoPath = $_SESSION['logoPath'];

            // Store the customization options in the session
            $_SESSION['customize'] = [
                'foregroundColor' => $_forColor,
                'backgroundColor' => $_bgColor,
                'size' => $_size,
                'margin' => $_margin,
                'labelText' => $_labelText,
            ];

            // Use the last generated QR code data for customization
            $qrCodeData = $_SESSION['lastQrCodeData'] ?? 'Sample QR Code';

            $this->renderQrCode($qrCodeData, $_forColor, $_bgColor, $_size, $_margin, $_labelText, $_logoPath);

            // Persist the customized QR code so changes are reflected in database and file storage
            $this->persistGeneratedQrCode($qrCodeData);

            alert('message', 'Changes applied successfully');
        }
    }
    // Function to handle the generation of the QR code
    private function generateQRCode()
    {
        // Debug: Log when method is called
        error_log("generateQRCode() method called");
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . print_r($_POST, true));
        error_log("REQUEST data: " . print_r($_REQUEST, true));
        
        // Debug: Check which button was pressed
        if (isset($_REQUEST['btnSMS'])) {
            error_log("btnSMS button detected");
        }
        if (isset($_REQUEST['btnVCard'])) {
            error_log("btnVCard button detected");
        }
        if (isset($_REQUEST['btnWhatsApp'])) {
            error_log("btnWhatsApp button detected");
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("POST request detected");
            if (!isset($_POST['csrf_token'])) {
                error_log("CSRF token missing");
                alert('message', 'Invalid or missing CSRF token');
                return;
            }
            if (!function_exists('csrf_verify')) {
                error_log("csrf_verify function not found");
                alert('message', 'CSRF verification function not available');
                return;
            }
            if (!csrf_verify($_POST['csrf_token'])) {
                error_log("CSRF validation failed");
                alert('message', 'Invalid or missing CSRF token');
                return;
            }
            error_log("CSRF validation passed");
        } else {
            error_log("Not a POST request - QR code generation skipped");
            return;
        }
        // URL QR CODE
        if (isset($_REQUEST['btnUrl'])) {
            error_log("Processing URL QR Code generation");
            $qrCodeData = $_REQUEST['qrUrl'];
            error_log("QR Code Data: " . $qrCodeData);

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            $this->renderQrCode($qrCodeData);
            $this->persistGeneratedQrCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // TEXT QR CODE
        if (isset($_REQUEST['btnText'])) {
            error_log("Processing Text QR Code generation");
            $qrCodeData = $_REQUEST['qrText'];
            error_log("QR Code Data: " . $qrCodeData);

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            $this->renderQrCode($qrCodeData);
            $this->persistGeneratedQrCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // SMS QR Code MESSAGES
        if (isset($_REQUEST['btnSMS'])) {
            error_log("Processing SMS QR Code generation");
            // Format the data for an SMS QR code
            $num = (string) ($_REQUEST['qrSMSnum'] ?? '');
            $msg = (string) ($_REQUEST['qrSMSmsg'] ?? '');
            error_log("SMS Number: " . $num);
            error_log("SMS Message: " . $msg);
            $qrCodeData = 'smsto:' . $num . ':' . rawurlencode($msg);
            error_log("SMS QR Code Data: " . $qrCodeData);

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            $this->renderQrCode($qrCodeData);
            $this->persistGeneratedQrCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // WIFI QR Code
        if (isset($_REQUEST['btnWifi'])) {
            $qrWifiName = $_REQUEST['qrWifiName'];
            $qrWifiPass = $_REQUEST['qrWifiPass'];
            $qrWifiSec = $_REQUEST['qrWifiSec'];

            // Create the Wi-Fi URI for the QR code (format: WIFI:T:<encryption>;S:<SSID>;P:<password>;;)
            $esc = function ($v) { return str_replace(['\\', ';', ',', ':'], ['\\\\', '\\;', '\\,', '\\:'], (string) $v); };
            $qrCodeData = 'WIFI:T:' . $qrWifiSec . ';S:' . $esc($qrWifiName) . ';P:' . $esc($qrWifiPass) . ';';

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            $this->renderQrCode($qrCodeData);
            $this->persistGeneratedQrCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // CALL QR CODE
        if (isset($_REQUEST['btnCall'])) {
            // Format the data for a phone call QR code
            $qrCodeData = 'tel:' . $_REQUEST['qrPhoneNumber'];

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            $this->renderQrCode($qrCodeData);
            $this->persistGeneratedQrCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // VCARD QR codes
        if (isset($_REQUEST['btnVCard'])) {
            error_log("Processing V-Card QR Code generation");
            // Format the data for a vCard QR code
            $qrCodeData = "BEGIN:VCARD\n";
            $qrCodeData .= "VERSION:3.0\n";
            $qrCodeData .= "FN:" . ($_REQUEST['qrVcardName'] ?? '') . "\n";
            $qrCodeData .= "TEL:" . ($_REQUEST['qrVcardNum'] ?? '') . "\n";
            $qrCodeData .= "EMAIL:" . ($_REQUEST['qrVcardEmail'] ?? '') . "\n";
            $qrCodeData .= "ADR:" . ($_REQUEST['qrVcardAdd'] ?? '') . "\n";
            $qrCodeData .= "END:VCARD";
            error_log("V-Card QR Code Data: " . $qrCodeData);

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            $this->renderQrCode($qrCodeData);
            $this->persistGeneratedQrCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // EMAIL QR CODE
        if (isset($_REQUEST['btnEmail'])) {

            $email = (string) ($_REQUEST['qrEmail'] ?? '');
            $subject = rawurlencode((string) ($_REQUEST['qrSubject'] ?? ''));
            $body = rawurlencode((string) ($_REQUEST['qrMessage'] ?? ''));
            $qrCodeData = 'mailto:' . $email . '?subject=' . $subject . '&body=' . $body;

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            $this->renderQrCode($qrCodeData);
            $this->persistGeneratedQrCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // WHATSAPP QR CODE 
        if (isset($_REQUEST['btnWhatsApp'])) {
            error_log("Processing WhatsApp QR Code generation");
            // Format the data for a WhatsApp message QR code
            $qrCodeData = 'https://wa.me/' . urlencode($_REQUEST['qrWhatsappNum'] ?? '') . '?text=' . urlencode($_REQUEST['qrWhatsappMsg'] ?? '');
            error_log("WhatsApp Number: " . ($_REQUEST['qrWhatsappNum'] ?? ''));
            error_log("WhatsApp Message: " . ($_REQUEST['qrWhatsappMsg'] ?? ''));
            error_log("WhatsApp QR Code Data: " . $qrCodeData);

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            $this->renderQrCode($qrCodeData);
            $this->persistGeneratedQrCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }
    }

    // Add QR code to cart
    public function addQrToCart()
    {
        // Set response header to JSON
        header('Content-Type: application/json');

        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if QR code exists
        if (empty($_SESSION['qrImageData']) || empty($_SESSION['qrCodeData'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No QR code to add to cart. Please generate a QR code first.'
            ]);
            exit;
        }

        // Determine QR code type based on the data
        $qrCodeData = $_SESSION['qrCodeData'];
        $persistResult = $this->persistGeneratedQrCode($qrCodeData);

        if (!$persistResult['success']) {
            echo json_encode([
                'success' => false,
                'message' => 'Unable to save QR Code at this time. Please try again.'
            ]);
            exit;
        }

        $dbAction = $persistResult['db_action'] ?? 'created';
        $baseMessage = $dbAction === 'updated' ? 'QR Code refreshed in cart' : 'QR Code added to cart';
        $dbMessage = ($dbAction === 'created' || $dbAction === 'updated') ? ' and synced with database' : '';

        echo json_encode([
            'success' => true,
            'message' => $baseMessage . $dbMessage,
            'db_id' => $persistResult['db_id'] ?? null
        ]);
        exit;
    }

    private function persistGeneratedQrCode($qrCodeData)
    {
        if (empty($qrCodeData) || empty($_SESSION['qrImageData'])) {
            return ['success' => false, 'reason' => 'missing_data'];
        }

        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $qrType = 'QR Code';
        if (strpos($qrCodeData, 'http://') === 0 || strpos($qrCodeData, 'https://') === 0) {
            $qrType = 'URL';
        } elseif (strpos($qrCodeData, 'mailto:') === 0) {
            $qrType = 'Email';
        } elseif (strpos($qrCodeData, 'tel:') === 0) {
            $qrType = 'Call';
        } elseif (strpos($qrCodeData, 'smsto:') === 0) {
            $qrType = 'SMS';
        } elseif (strpos($qrCodeData, 'WIFI:') === 0) {
            $qrType = 'WiFi';
        } elseif (strpos($qrCodeData, 'BEGIN:VCARD') === 0) {
            $qrType = 'vCard';
        } elseif (strpos($qrCodeData, 'wa.me') !== false || strpos($qrCodeData, 'whatsapp.com') !== false) {
            $qrType = 'WhatsApp';
        } elseif (strpos($qrCodeData, 'text:') === 0) {
            $qrType = 'Text';
        }

        $format = $_SESSION['customize']['qrCodeFormat'] ?? 'PNG';
        $cartItem = [
            'type' => 'qrcode',
            'qrCodeValue' => $qrCodeData,
            'qrImageData' => $_SESSION['qrImageData'],
            'format' => $qrType,
            'imageFormat' => $format,
            'customize' => $_SESSION['customize'] ?? []
        ];

        $existingIndex = null;
        foreach ($_SESSION['cart'] as $index => $item) {
            if (($item['type'] ?? '') === 'qrcode' && ($item['qrCodeValue'] ?? null) === $qrCodeData) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $_SESSION['cart'][$existingIndex] = array_merge($_SESSION['cart'][$existingIndex], $cartItem);
            $addedToCart = false;
        } else {
            $_SESSION['cart'][] = $cartItem;
            $addedToCart = true;
        }

        if (!isset($_SESSION['qr_saved_ids']) || !is_array($_SESSION['qr_saved_ids'])) {
            $_SESSION['qr_saved_ids'] = [];
        }

        $barcodeQrModel = $this->model('BarcodeQr');
        $dbAction = 'created';
        $dbId = null;

        if (isset($_SESSION['qr_saved_ids'][$qrCodeData])) {
            $existingId = $_SESSION['qr_saved_ids'][$qrCodeData];
            if ($barcodeQrModel->updateImage($existingId, $_SESSION['qrImageData'])) {
                $dbAction = 'updated';
                $dbId = $existingId;
            } else {
                $dbAction = 'failed';
            }
        } else {
            $saveData = [
                'type' => 'qrcode',
                'value' => $qrCodeData,
                'format' => $qrType,
                'imageData' => $_SESSION['qrImageData'],
                'customization' => $_SESSION['customize'] ?? [],
            ];

            $dbId = $barcodeQrModel->save($saveData);
            if ($dbId) {
                $_SESSION['qr_saved_ids'][$qrCodeData] = $dbId;
                $dbAction = 'created';
            } else {
                $dbAction = 'failed';
            }
        }

        return [
            'success' => $dbAction === 'created' || $dbAction === 'updated',
            'db_id' => $dbId,
            'db_action' => $dbAction,
            'added_to_cart' => $addedToCart
        ];
    }

    private function renderQrCode($qrCodeData, $fgColor = [0, 0, 0], $bgColor = [255, 255, 255], $size = 300, $margin = 10, $labelText = '', $logoPath = null)
    {
        $qrCode = new QrCode(
            data: $qrCodeData,
            encoding: new Encoding('UTF-8'),
            size: $size,
            margin: $margin,
            foregroundColor: new Color($fgColor[0], $fgColor[1], $fgColor[2]),
            backgroundColor: new Color($bgColor[0], $bgColor[1], $bgColor[2]),
        );

        $label = null;
        if ($labelText) {
            $label = new Label(
                text: $labelText,
                textColor: new Color(0, 0, 0),
            );
        }

        $logo = null;
        if ($logoPath === null && isset($_FILES['logo']) && !empty($_FILES['logo']['tmp_name'])) {
            $logoPath = addLogo('logo', 'uploads');
        }
        $_SESSION['logoPath'] = $logoPath;
        if ($logoPath) {
            $logo = new Logo(
                path: 'images/uploads/' . $logoPath,
                resizeToWidth: 100,
            );
        }

        $format = strtolower($_SESSION['customize']['qrCodeFormat'] ?? 'png');
        $writer = ($format === 'svg') ? new SvgWriter : new PngWriter;
        $result = $writer->write($qrCode, $logo, $label);

        $_SESSION['qrImageData'] = $result->getDataUri();
        $_SESSION['qrImageTimestamp'] = time();
        $_SESSION['qrCodeData'] = $qrCodeData;

        return $_SESSION['qrImageData'];
    }
}
