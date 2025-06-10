<?php
class Barcode extends Controller
{
    private $data = '';
    private $formData = '';

    public function __construct() {}
    public function index()
    {

        $this->formData = filteration($_REQUEST);

        // Initialize barcode value and customization options
        $this->data = [
            'numBarcodes' => $this->formData['numBarcodes'] ?? 1, // Default to 1 barcode;
            'format' => $this->formData['format'] ?? 'EAN13',
            'displayValue' => $this->formData['displayValue'] ?? 'true',
            'lineColor' => $this->formData['lineColor'] ?? '#000000',
            'backgroundColor' => $this->formData['backgroundColor'] ?? '#FFFFFF',
            'fontSize' => $this->formData['fontSize'] ?? '16',
            'width' => $this->formData['width'] ?? '2',
            'height' => $this->formData['height'] ?? '100',
            'imageFormat' => $this->formData['imageFormat'] ?? 'png',  // Default format is PNG
            'barcodeValue' => $this->formData['barcodeValue'] ?? '', // Default value
        ];
        $_SESSION['qrImageTimestamp'] = time(); // Store the current timestamp

        // Generate random barcode values (12 digits for EAN-13)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($this->formData['generateRandom'])) {
                $barcodeValue = [];
                for ($i = 0; $i < $this->data['numBarcodes']; $i++) {
                    $barcodeValue[] = rand(100000000000, 999999999999); // Random 12-digit number
                    $_SESSION['barcodeValue'] = $barcodeValue;
                }

                // Use the user-input barcode value for all barcodes
            } elseif (isset($this->formData['barcodeValue'])) {
                $_SESSION['barcodeValue'] = array_fill(0, $this->data['numBarcodes'], $this->formData['barcodeValue']);

                // PHP logic to save the barcodes (for non-PDF formats)
            } elseif (isset($this->formData['imageData'])) {
                $imageData = $this->formData['imageData'];
                $imageFormat = $this->formData['imageFormat'];

                // Remove the "data:image/..." part
                $imageData = preg_replace('/^data:image\/(\w+);base64,/', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);

                // Decode the base64 data
                $imageData = base64_decode($imageData);

                // Ensure the "barcodes" folder exists
                if (!is_dir('barcodes')) {
                    mkdir('barcodes', 0755, true);
                }

                // Save the image to a file
                $filePath = 'barcodes/barcode-' . $_SESSION['barcodeValue'] . '.' . $imageFormat; // Unique filename
                if (file_put_contents($filePath, $imageData)) {
                    echo "<p>Barcode saved as $filePath</p>";
                } else {
                    echo "<p>Failed to save the barcode.</p>";
                }
            }
        }

        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Get the barcodes from the request
        $input = json_decode(file_get_contents('php://input'), true);
        $barcodes = $input['barcodes'] ?? [];

        if (empty($barcodes)) {
            alert('Error', 'No barcodes provided');
            // exit;
        }

        // Add the barcodes to the cart
        foreach ($barcodes as $barcode) {
            $_SESSION['cart'][] = $barcode;
        }

        alert('Success', 'Barcodes added to cart');


        $this->view('barcode', $this->data);

        // Check if the session variable should be unset
        if (isset($_SESSION['qrImageTimestamp'])) {
            $currentTime = time();
            $sessionTime = $_SESSION['qrImageTimestamp'];
            $timeoutDuration = 100; // 1 hour in seconds

            if ($currentTime - $sessionTime > $timeoutDuration) {
                unset($_SESSION['barcodeValue']);
                unset($_SESSION['qrImageTimestamp']);
            }
        }
    }
}
