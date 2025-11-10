<?php
require_once "vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class Index extends Controller
{
    private $userModel;
    private $formData = [];

    public function __construct()
    {
        // $this->userModel = $this->model('User');
    }
    public function index()
    {
        function createQRCode($qrCodeData, $fgColor = [0, 0, 0], $bgColor = [255, 255, 255], $size = 300, $margin = 10, $labelText = '', $logoPath = NULL)
        {
            // Create a QR code 
            $qrCode = new QrCode(
                data: $qrCodeData,
                encoding: new Encoding('UTF-8'),
                size: $size,
                margin: $margin,
                foregroundColor: new Color($fgColor[0], $fgColor[1], $fgColor[2]),
                backgroundColor: new Color($bgColor[0], $bgColor[1], $bgColor[2]),
                // roundBlockSizeMode: RoundBlockSizeMode::Margin,
                // errorCorrectionLevel: ErrorCorrectionLevel::Low,
            );

            // Add a label if provided
            $label = null;
            if ($labelText) {
                $label = new Label(
                    text: $labelText,
                    textColor: new Color(0, 0, 0),
                );
            }

            // Add the logo if provided
            $logo = null;
            $logoPath = addLogo('logo', 'uploads');
            $_SESSION['logoPath'] = $logoPath;
            if ($logoPath) {
                $logo = new Logo(
                    path: 'images/uploads/' . $logoPath,
                    resizeToWidth: 100, // Resize the logo to fit within the QR code
                );
            }

            $writer = new PngWriter;
            $result = $writer->write($qrCode, $logo, $label);

            // // Save the QR code to a file
            // $filename = "images/qrimage/" . time() . ".png";
            // $result->saveToFile($filename);

            // Output the QR code directly
            $_SESSION['qrImageData'] = $result->getDataUri();
            $_SESSION['qrImageTimestamp'] = time(); // Store the current timestamp
            $_SESSION['qrCodeData'] = $qrCodeData;

            return $_SESSION['qrImageData'];
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

            createQRCode($qrCodeData, $_forColor, $_bgColor, $_size, $_margin, $_labelText, $_logoPath);

            alert('message', 'Changes applied successfully');
        }
    }
    // Function to handle the generation of the QR code
    private function generateQRCode()
    {
        // URL QR CODE
        if (isset($_REQUEST['btnUrl'])) {
            $qrCodeData = $_REQUEST['qrUrl'];

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            createQRCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // TEXT QR CODE
        if (isset($_REQUEST['btnText'])) {
            $qrCodeData = $_REQUEST['qrText'];

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            createQRCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // SMS QR Code MESSAGES
        if (isset($_REQUEST['btnSMS'])) {
            // Format the data for an SMS QR code
            $qrCodeData = 'smsto:' . $_REQUEST['qrSMSnum'] . ':' . $_REQUEST['qrSMSmsg'];

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            createQRCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // WIFI QR Code
        if (isset($_REQUEST['btnWifi'])) {
            $qrWifiName = $_REQUEST['qrWifiName'];
            $qrWifiPass = $_REQUEST['qrWifiPass'];
            $qrWifiSec = $_REQUEST['qrWifiSec'];

            // Create the Wi-Fi URI for the QR code (format: WIFI:T:<encryption>;S:<SSID>;P:<password>;;)
            $qrCodeData = "WIFI:T:$qrWifiSec;S:$qrWifiName;P:$qrWifiPass;";

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            createQRCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // CALL QR CODE
        if (isset($_REQUEST['btnCall'])) {
            // Format the data for a phone call QR code
            $qrCodeData = 'tel:' . $_REQUEST['qrPhoneNumber'];

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            createQRCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // VCARD QR codes
        if (isset($_REQUEST['btnVCard'])) {
            // Format the data for a vCard QR code
            $qrCodeData = "BEGIN:VCARD\n";
            $qrCodeData .= "VERSION:3.0\n";
            $qrCodeData .= "FN:$_REQUEST[qrVcardName]\n";
            $qrCodeData .= "TEL:$_REQUEST[qrVcardNum]\n";
            $qrCodeData .= "EMAIL:$_REQUEST[qrVcardEmail]\n";
            $qrCodeData .= "ADR:$_REQUEST[qrVcardAdd]\n";
            $qrCodeData .= "END:VCARD";

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            createQRCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // EMAIL QR CODE
        if (isset($_REQUEST['btnEmail'])) {

            $qrCodeData = "mailto:$_REQUEST[qrEmail]?subject=$_REQUEST[qrSubject]&body=$_REQUEST[qrMessage]";

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            createQRCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }

        // WHATSAPP QR CODE 
        if (isset($_REQUEST['btnWhatsApp'])) {
            // Format the data for a WhatsApp message QR code
            $qrCodeData = 'https://wa.me/' . urlencode($_REQUEST['qrWhatsappNum']) . '?text=' . urlencode($_REQUEST['qrWhatsappMsg']);

            $_SESSION['lastQrCodeData'] = $qrCodeData;
            createQRCode($qrCodeData);

            alert('message', 'QR Code Generated Successfully');
        }
    }

    //    public function register()
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $this->formData = filteration($_POST);
            $data = [
                'name' => $this->formData['name'],
                'email' => $this->formData['email'],
                'password' => $this->formData['password'],
                'confirm_password' => $this->formData['confirm_password'],
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];
            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                // Check Email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }
            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }
            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters long';
            }
            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } elseif ($data['password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Passwords do not match';
            }
            // Register User
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                $this->userModel->register($data);
                header('location: ' . URLROOT . '/users/login');
            } else {
                $this->view('index/register', $data);
            }
        } else {
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('index/register', $data);
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
        } else {
            $qrType = 'Text';
        }

        // Get format from customize or default
        $format = $_SESSION['customize']['qrCodeFormat'] ?? 'PNG';

        // Create cart item
        $cartItem = [
            'type' => 'qrcode',
            'qrCodeValue' => $qrCodeData,
            'qrImageData' => $_SESSION['qrImageData'],
            'format' => $qrType,
            'imageFormat' => $format,
            'customize' => $_SESSION['customize'] ?? []
        ];

        // Add to cart
        $_SESSION['cart'][] = $cartItem;

        // Save to database
        $barcodeQrModel = $this->model('BarcodeQr');
        $saveData = [
            'type' => 'qrcode',
            'value' => $qrCodeData,
            'format' => $qrType,
            'imageData' => $_SESSION['qrImageData'],
            'customization' => $_SESSION['customize'] ?? []
        ];

        $id = $barcodeQrModel->save($saveData);
        $dbMessage = $id ? ' and saved to database' : '';

        echo json_encode([
            'success' => true,
            'message' => 'QR Code added to cart successfully' . $dbMessage,
            'db_id' => $id
        ]);
        exit;
    }
}
