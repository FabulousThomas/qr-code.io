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

        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Handle AJAX POST request for adding barcodes to cart (CHECK THIS FIRST!)
        $rawInput = file_get_contents('php://input');
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($rawInput) && strpos($contentType, 'application/json') !== false) {
            // Log the raw input for debugging
            error_log("Raw input: " . $rawInput);
            
            $input = json_decode($rawInput, true);
            error_log("Decoded input: " . print_r($input, true));
            
            $barcodes = $input['barcodes'] ?? [];
            error_log("Barcodes array: " . print_r($barcodes, true));

            if (empty($barcodes)) {
                header('Content-Type: application/json');
                error_log("ERROR: Barcodes array is empty!");
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No barcodes provided',
                    'debug' => [
                        'raw_input_length' => strlen($rawInput),
                        'decoded_input' => $input,
                        'barcodes' => $barcodes
                    ]
                ]);
                exit;
            }

            // Load the model
            $barcodeQrModel = $this->model('BarcodeQr');
            
            // Add the barcodes to the cart and save to database
            $savedIds = [];
            foreach ($barcodes as $barcode) {
                // Add type identifier for cart items
                $barcode['type'] = 'barcode';
                
                // Add to session cart
                $_SESSION['cart'][] = $barcode;
                
                // Save to database
                // Handle single or multiple barcode values
                $barcodeValues = [];
                if (is_array($barcode['barcodeValue'])) {
                    $barcodeValues = $barcode['barcodeValue'];
                } else {
                    // Remove suffix like "-1", "-2" for database storage, but keep the original in cart
                    $barcodeValue = $barcode['barcodeValue'];
                    if (strpos($barcodeValue, '-') !== false) {
                        $parts = explode('-', $barcodeValue);
                        $barcodeValue = $parts[0];
                    }
                    $barcodeValues = [$barcodeValue];
                }
                
                // Save each barcode value separately
                foreach ($barcodeValues as $barcodeVal) {
                    // Clean the barcode value - remove suffix
                    $cleanValue = $barcodeVal;
                    if (strpos($cleanValue, '-') !== false) {
                        $parts = explode('-', $cleanValue);
                        $cleanValue = $parts[0];
                    }
                    
                    $saveData = [
                        'type' => 'barcode',
                        'value' => $cleanValue,
                        'format' => $barcode['format'] ?? 'EAN13',
                        'imageData' => '', // Barcode images are generated client-side
                        'customization' => [
                            'displayValue' => $barcode['displayValue'] ?? 'true',
                            'lineColor' => $barcode['lineColor'] ?? '#000000',
                            'backgroundColor' => $barcode['backgroundColor'] ?? '#FFFFFF',
                            'width' => $barcode['width'] ?? '2',
                            'height' => $barcode['height'] ?? '100',
                            'fontSize' => $barcode['fontSize'] ?? '16'
                        ]
                    ];
                    
                    $id = $barcodeQrModel->save($saveData);
                    if ($id) {
                        $savedIds[] = $id;
                    }
                }
            }

            // Return JSON response for AJAX
            header('Content-Type: application/json');
            if (!empty($savedIds)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Barcodes added to cart and saved to database',
                    'saved_ids' => $savedIds
                ]);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Barcodes added to cart'
                ]);
            }
            exit;
        }

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
                $filePath = APPROOT . '/images/barimage/barcode-' . $_SESSION['barcodeValue'] . '.' . $imageFormat; // Unique filename
                if (file_put_contents($filePath, $imageData)) {
                    echo "<p>Barcode saved as $filePath</p>";
                } else {
                    echo "<p>Failed to save the barcode.</p>";
                }
            }
        }

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
        
        $this->view('barcode', $this->data);
    }

    // Remove item from cart
    public function removeItem()
    {
        // Set response header to JSON
        header('Content-Type: application/json');

        // Initialize the cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Get the JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $index = isset($input['index']) ? (int)$input['index'] : null;

        // Validate index
        if ($index === null || $index < 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid item index'
            ]);
            exit;
        }

        // Check if cart is empty
        if (empty($_SESSION['cart'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Cart is empty'
            ]);
            exit;
        }

        // Check if index exists
        if (!isset($_SESSION['cart'][$index])) {
            echo json_encode([
                'success' => false,
                'message' => 'Item not found in cart'
            ]);
            exit;
        }

        // Remove the item from cart
        unset($_SESSION['cart'][$index]);
        
        // Reindex the array to maintain sequential keys
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        // Generate updated cart HTML
        $cartHtml = $this->getCartHtml();
        $hasItems = !empty($_SESSION['cart']);

        // Return success response with updated cart HTML
        echo json_encode([
            'success' => true,
            'message' => 'Item removed from cart successfully',
            'cartHtml' => $cartHtml,
            'hasItems' => $hasItems
        ]);
        exit;
    }

    // Get cart HTML content for modal
    private function getCartHtml()
    {
        ob_start();
        
        // Check if the cart is empty
        if (empty($_SESSION['cart'])) {
            echo "<div class='text-center py-5'>";
            echo "<div class='mb-3'>";
            echo "<i class='las la-shopping-cart' style='font-size: 4rem; color: #ccc;'></i>";
            echo "</div>";
            echo "<h5 class='text-muted mb-2'>Your cart is empty!</h5>";
            echo "<p class='text-muted mb-0'>Add some QR codes or barcodes to your cart to get started.</p>";
            echo "</div>";
        } else {
            // Display cart items
            foreach ($_SESSION['cart'] as $index => $item) {
                echo "<div class='cart-item mb-3 pb-3 border-bottom' data-item-index='" . $index . "'>";
                echo "<div class='d-flex justify-content-between align-items-start mb-2'>";
                echo "<div class='flex-grow-1'>";
                echo "<strong>Item #" . ($index + 1) . "</strong>";
                $itemType = isset($item['type']) && $item['type'] === 'qrcode' ? 'QR Code' : 'Barcode';
                echo "<span class='badge bg-info ms-2'>" . htmlspecialchars($itemType) . "</span>";
                echo "<span class='badge bg-secondary ms-2'>" . htmlspecialchars($item['format'] ?? 'N/A') . "</span>";
                echo "</div>";
                echo "<button type='button' class='btn btn-sm btn-danger remove-item-btn' data-index='" . $index . "' onclick='removeCartItem(" . $index . ")' title='Remove item'>";
                echo "<i class='las la-trash'></i>";
                echo "</button>";
                echo "</div>";
                
                // Handle QR codes
                if (isset($item['type']) && $item['type'] === 'qrcode') {
                    echo "<div class='qr-code-item mt-2'>";
                    if (isset($item['qrImageData'])) {
                        echo "<div class='text-center mb-2'>";
                        echo "<img src='" . htmlspecialchars($item['qrImageData']) . "' alt='QR Code' class='img-fluid' style='max-width: 150px;'>";
                        echo "</div>";
                    }
                    if (isset($item['qrCodeValue'])) {
                        echo "<div class='qr-code-value'>";
                        echo "<strong>QR Data:</strong> ";
                        $qrValue = htmlspecialchars($item['qrCodeValue']);
                        // Truncate long values
                        if (strlen($qrValue) > 50) {
                            $qrValue = substr($qrValue, 0, 50) . '...';
                        }
                        echo "<span class='text-primary'>" . $qrValue . "</span>";
                        echo "</div>";
                    }
                    if (isset($item['imageFormat'])) {
                        echo "<div class='text-muted small'>Format: " . htmlspecialchars($item['imageFormat']) . "</div>";
                    }
                    echo "</div>";
                } 
                // Handle barcodes
                elseif (isset($item['barcodeValue'])) {
                    $barcodeFormat = $item['format'] ?? 'EAN13';
                    $displayValue = isset($item['displayValue']) ? ($item['displayValue'] === 'true' || $item['displayValue'] === true) : true;
                    $lineColor = $item['lineColor'] ?? '#000000';
                    $backgroundColor = $item['backgroundColor'] ?? '#FFFFFF';
                    $width = $item['width'] ?? '2';
                    $height = $item['height'] ?? '100';
                    
                    if (is_array($item['barcodeValue'])) {
                        // Multiple barcode values
                        echo "<div class='barcode-values'>";
                        echo "<strong>Barcodes (" . count($item['barcodeValue']) . "):</strong><br>";
                        foreach ($item['barcodeValue'] as $barcodeIndex => $barcodeVal) {
                            echo "<div class='mb-2'>";
                            echo "<div class='d-flex align-items-center mb-1'>";
                            echo "<span class='badge bg-primary me-2'>" . ($barcodeIndex + 1) . "</span>";
                            echo "<small class='text-muted'>" . htmlspecialchars($barcodeVal) . "</small>";
                            echo "</div>";
                            $barcodeId = "barcode-cart-" . $index . "-" . $barcodeIndex;
                            echo "<svg id='" . $barcodeId . "' class='barcode-svg' data-value='" . htmlspecialchars($barcodeVal) . "' data-format='" . htmlspecialchars($barcodeFormat) . "' data-display='" . ($displayValue ? 'true' : 'false') . "' data-line-color='" . htmlspecialchars($lineColor) . "' data-bg-color='" . htmlspecialchars($backgroundColor) . "' data-width='" . htmlspecialchars($width) . "' data-height='" . htmlspecialchars($height) . "' style='max-width: 200px; height: auto;'></svg>";
                            echo "</div>";
                        }
                        echo "</div>";
                    } else {
                        // Single barcode value
                        echo "<div class='barcode-item mt-2'>";
                        echo "<div class='text-center mb-2'>";
                        $barcodeId = "barcode-cart-" . $index;
                        echo "<svg id='" . $barcodeId . "' class='barcode-svg' data-value='" . htmlspecialchars($item['barcodeValue']) . "' data-format='" . htmlspecialchars($barcodeFormat) . "' data-display='" . ($displayValue ? 'true' : 'false') . "' data-line-color='" . htmlspecialchars($lineColor) . "' data-bg-color='" . htmlspecialchars($backgroundColor) . "' data-width='" . htmlspecialchars($width) . "' data-height='" . htmlspecialchars($height) . "' style='max-width: 200px; height: auto;'></svg>";
                        echo "</div>";
                        echo "<div class='barcode-value text-center'>";
                        echo "<small class='text-muted'>" . htmlspecialchars($item['barcodeValue']) . "</small>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='text-muted'>No value</div>";
                }
                
                echo "</div>";
            }
        }
        
        return ob_get_clean();
    }
}
