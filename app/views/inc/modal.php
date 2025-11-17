<!-- Modal for customizing QR code -->
<div class="modal fade" id="customize" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="customizeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered modal-fullscreen">
        <div class="container modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customizeLabel">Customize QR Code</h5>
                <button type="button" class="close border-0 bg-transparent" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row d-non">
                    <div class="col-md-8">
                        <form action="" method="POST" id="customizeForm" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="foregroundColor">Foreground Color:</label>
                                    <input type="color" id="foregroundColor" name="foregroundColor"
                                        class="form-control color-input shadow-none border bg-transparent"
                                        value="<?= isset($_SESSION['customize']['foregroundColor']) ? sprintf("#%02x%02x%02x", $_SESSION['customize']['foregroundColor'][0], $_SESSION['customize']['foregroundColor'][1], $_SESSION['customize']['foregroundColor'][2]) : '#000000' ?>"
                                        required />
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="backgroundColor">Background Color:</label>
                                    <input type="color" id="backgroundColor" name="backgroundColor"
                                        class="form-control color-input shadow-none border bg-transparent"
                                        value="<?= isset($_SESSION['customize']['backgroundColor']) ? sprintf("#%02x%02x%02x", $_SESSION['customize']['backgroundColor'][0], $_SESSION['customize']['backgroundColor'][1], $_SESSION['customize']['backgroundColor'][2]) : '#ffffff' ?>"
                                        required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="size">Size:</label>
                                    <input type="number" id="size" name="size"
                                        class="form-control shadow-none border bg-transparent"
                                        value="<?= $_SESSION['customize']['size'] ?? 300 ?>">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="margin">Margin:</label>
                                    <input type="number" id="margin" name="margin"
                                        class="form-control shadow-none border bg-transparent"
                                        value="<?= $_SESSION['customize']['margin'] ?? 10 ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="qrCodeFormat">QR Code Format:</label>
                                    <select id="qrCodeFormat" name="qrCodeFormat"
                                        class="form-control shadow-none border bg-transparent">
                                        <option value="png" selected>PNG</option>
                                        <option value="svg">SVG</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="labelText">Display Text:</label>
                                    <input type="text" id="labelText" name="labelText"
                                        class="form-control shadow-none border bg-transparent"
                                        value="<?= $_SESSION['customize']['labelText'] ?? '' ?>" placeholder="Optional">
                                </div>
                            </div>
                            <div class="row d-non">
                                <div class="col-md-6 mb-2">
                                    <label for="logo">Logo:</label>
                                    <input type="file" id="logo" name="logo"
                                        class="form-control shadow-none border bg-transparent"
                                        value="<?= $_SESSION['logoPath'] ?? '' ?>" accept="image/*">
                                    <?php if (isset($_SESSION['logoPath']) && $_SESSION['logoPath']): ?>
                                        <label>Current Logo: <img
                                                src="<?= URLROOT ?>/images/uploads/<?= $_SESSION['logoPath'] ?>" alt="Logo"
                                                style="width: 50px;"></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <small class="text-uppercase text-danger">Note-> More features coming soon< </small>
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <button type="submit" name="btnCustomize"
                                                class="form-control btn btn-orange rounded-0 d-flex align-items-center justify-content-center"><i
                                                    class="las la-plus-square" style="font-size: 1.1rem;"></i>Apply
                                                Changes</button>
                                        </div>
                                    </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="card-body text-center border py-4">
                            <img id="qrCodePreview" src="<?= $_SESSION['qrImageData'] ?? '' ?>" alt="QR Code Preview"
                                class="img-fluid" style="width: 250px;">

                            <div class="d-flex justify-content-center mt-2">
                                <a href="<?= $_SESSION['qrImageData'] ?? '' ?>" class="btn btn-orange rounded-0"
                                    download="qrcode.png">
                                    <i class="las la-download las-sm"></i> Download QR Code
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal for cart modal -->
<div class="modal fade" id="cartModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Your Cart</h5>
                <button type="button" class="close border-0 bg-transparent shadow-none" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true" class="text-white fs-3">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="cartModalBody">
                <!-- Check if the cart is empty -->
                <?php if (empty($_SESSION['cart'])): ?>
                    <div class='text-center py-5'>
                        <div class='mb-3'>
                            <i class='las la-shopping-cart' style='font-size: 4rem; color: #ccc;'></i>
                        </div>
                        <h5 class='text-white mb-2'>Your cart is empty!</h5>
                        <p class='text-white mb-0'>Add some QR codes or barcodes to your cart to get started.</p>
                    </div>
                <?php else: ?>
                    <!--  Display cart items -->
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <div class='cart-item mb-3 pb-3 border-bottom' data-item-index='" . $index . "'>
                            <div class='d-flex justify-content-between align-items-start mb-2'>
                                <div class='flex-grow-1'>
                                    <strong>Item #<?= ($index + 1) ?></strong>
                                    <?php $itemType = isset($item['type']) && $item['type'] === 'qrcode' ? 'QR Code' : 'Barcode'; ?>
                                    <span class='badge bg-info ms-2'><?= htmlspecialchars($itemType) ?></span>
                                    <span
                                        class='badge bg-secondary ms-2'><?= htmlspecialchars($item['format'] ?? 'N/A') ?></span>
                                </div>
                                <button type='button' class='btn btn-sm btn-danger remove-item-btn' data-index='<?= $index ?>'
                                    onclick='removeCartItem(<?= $index ?>)' title='Remove item'>
                                    <i class='las la-trash'></i>
                                </button>
                            </div>

                            <!-- // Handle QR codes -->
                            <?php if (isset($item['type']) && $item['type'] === 'qrcode'): ?>
                                <div class='qr-code-item mt-2'>
                                    <?php if (isset($item['qrImageData'])): ?>
                                        <div class='text-center mb-2'>
                                            <img src='<?= htmlspecialchars($item['qrImageData']) ?>' alt='QR Code' class='img-fluid'
                                                style='max-width: 150px;'>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($item['qrCodeValue'])): ?>
                                        <div class='qr-code-value'>
                                            <strong>QR Data:</strong>
                                            <?php $qrValue = htmlspecialchars($item['qrCodeValue']); ?>
                                            <!-- // Truncate long values -->
                                            <?php if (strlen($qrValue) > 50): ?>
                                                <?php $qrValue = substr($qrValue, 0, 50) . '...'; ?>
                                            <?php endif; ?>
                                            <span class='text-primary'>
                                                <a href="<?= $qrValue ?>" target="_blank"><?= $qrValue ?></a>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Handle Barcodes -->
                            <?php elseif (isset($item['barcodeValue'])): ?>
                                <?php
                                $barcodeFormat = $item['format'] ?? 'EAN13';
                                $displayValue = isset($item['displayValue']) ? ($item['displayValue'] === 'true' || $item['displayValue'] === true) : true;
                                $lineColor = $item['lineColor'] ?? '#000000';
                                $backgroundColor = $item['backgroundColor'] ?? '#FFFFFF';
                                $width = $item['width'] ?? '2';
                                $height = $item['height'] ?? '100';
                                ?>
                                <?php if (is_array($item['barcodeValue'])): ?>
                                    <!-- Multiple barcode values -->
                                    <div class='barcode-values'>
                                        <strong>Barcodes (<?= count($item['barcodeValue']) ?>):</strong><br>
                                        <?php foreach ($item['barcodeValue'] as $barcodeIndex => $barcodeVal): ?>
                                            <div class='mb-2'>
                                                <div class='d-flex align-items-center mb-1'>
                                                    <span class='badge bg-primary me-2'><?= ($barcodeIndex + 1) ?></span>
                                                    <small class='text-muted'><?= htmlspecialchars($barcodeVal) ?></small>
                                                </div>
                                                <?php $barcodeId = "barcode-cart-" . $index . "-" . $barcodeIndex; ?>
                                                <svg id='<?= $barcodeId ?>' class='barcode-svg'
                                                    data-value='<?= htmlspecialchars($barcodeVal) ?>'
                                                    data-format='<?= htmlspecialchars($barcodeFormat) ?>'
                                                    data-display='<?= ($displayValue ? 'true' : 'false') ?>'
                                                    data-line-color='<?= htmlspecialchars($lineColor) ?>'
                                                    data-bg-color='<?= htmlspecialchars($backgroundColor) ?>'
                                                    data-width='<?= htmlspecialchars($width) ?>'
                                                    data-height='<?= htmlspecialchars($height) ?>'
                                                    style='max-width: 200px; height: auto;'></svg>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <!-- Single barcode value -->
                                    <div class='barcode-item mt-2'>
                                        <div class='text-center mb-2'>
                                            <?php $barcodeId = "barcode-cart-" . $index; ?>
                                            <svg id='<?= $barcodeId ?>' class='barcode-svg'
                                                data-value='<?= htmlspecialchars($item['barcodeValue']) ?>'
                                                data-format='<?= htmlspecialchars($barcodeFormat) ?>'
                                                data-display='<?= ($displayValue ? 'true' : 'false') ?>'
                                                data-line-color='<?= htmlspecialchars($lineColor) ?>'
                                                data-bg-color='<?= htmlspecialchars($backgroundColor) ?>'
                                                data-width='<?= htmlspecialchars($width) ?>'
                                                data-height='<?= htmlspecialchars($height) ?>'
                                                style='max-width: 200px; height: auto;'></svg>
                                        </div>
                                        <div class='barcode-value text-center'>
                                            <small class='text-muted'><?= htmlspecialchars($item['barcodeValue']) ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class='text-muted'>No value</div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <button type="button" class="btn btn-orange" id="checkoutBtn" onclick="proceedToCheckout()">
                        <i class="las la-shopping-cart las-sm"></i> Proceed to Checkout
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function proceedToCheckout() {
        // Close the cart modal
        const cartModal = bootstrap.Modal.getInstance(document.getElementById('cartModal'));
        if (cartModal) {
            cartModal.hide();
        }

        // Redirect to checkout page
        // Replace with your actual checkout route/URL
        // window.location.href = '<?= URLROOT ?>/barcode/checkout';

        // Alternative: If you want to open a payment modal instead, uncomment below:
        initPayment();
    }

    function removeCartItem(index) {
        // Use SweetAlert for confirmation
        swal({
            title: "Are you sure?",
            text: "Do you want to remove this item from the cart?",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: false,
                    visible: true,
                    className: "",
                    closeModal: true,
                },
                confirm: {
                    text: "Yes, remove it!",
                    value: true,
                    visible: true,
                    className: "btn-danger",
                    closeModal: false
                }
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (!willDelete) {
                return;
            }

            // Get the modal body and show loading state
            const modalBody = document.getElementById('cartModalBody');
            const originalContent = modalBody.innerHTML;
            modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Removing item...</p></div>';

            // Disable all buttons during removal
            const removeButtons = document.querySelectorAll('.remove-item-btn');
            const checkoutBtn = document.getElementById('checkoutBtn');
            removeButtons.forEach(btn => btn.disabled = true);
            if (checkoutBtn) checkoutBtn.disabled = true;

            // Send AJAX request to remove the item
            fetch('<?= URLROOT ?>/barcode/removeItem', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    index: index
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update modal body with new cart content
                        modalBody.innerHTML = data.cartHtml || originalContent;

                        // Update checkout button visibility
                        const checkoutBtn = document.getElementById('checkoutBtn');
                        const footer = document.querySelector('#cartModal .modal-footer');

                        if (data.hasItems) {
                            // Show checkout button if items exist
                            if (!checkoutBtn && footer) {
                                // Create checkout button if it doesn't exist
                                const closeBtn = footer.querySelector('button[data-bs-dismiss="modal"]');
                                if (closeBtn) {
                                    const newCheckoutBtn = document.createElement('button');
                                    newCheckoutBtn.type = 'button';
                                    newCheckoutBtn.className = 'btn btn-orange';
                                    newCheckoutBtn.id = 'checkoutBtn';
                                    newCheckoutBtn.onclick = proceedToCheckout;
                                    newCheckoutBtn.innerHTML = '<i class="las la-shopping-cart las-sm"></i> Proceed to Checkout';
                                    closeBtn.insertAdjacentElement('afterend', newCheckoutBtn);
                                }
                            } else if (checkoutBtn) {
                                checkoutBtn.style.display = 'inline-block';
                            }
                        } else {
                            // Hide or remove checkout button if cart is empty
                            if (checkoutBtn) {
                                checkoutBtn.remove(); // Remove instead of hide for cleaner UI
                            }
                        }

                        // Render barcodes after content update
                        setTimeout(function () {
                            if (typeof window.renderCartBarcodes === 'function') {
                                window.renderCartBarcodes();
                            } else {
                                // Try again after a bit more delay
                                setTimeout(function () {
                                    if (typeof window.renderCartBarcodes === 'function') {
                                        window.renderCartBarcodes();
                                    }
                                }, 200);
                            }
                        }, 150);

                        // Show success message using SweetAlert
                        swal("Removed!", "Item has been removed from the cart.", "success");

                    } else {
                        // Restore original content on error
                        modalBody.innerHTML = originalContent;
                        swal("Error!", data.message || 'Failed to remove item from cart', "error");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Restore original content on error
                    modalBody.innerHTML = originalContent;
                    swal("Error!", "An error occurred while removing the item. Please try again.", "error");
                })
                .finally(() => {
                    // Re-enable buttons
                    const removeButtons = document.querySelectorAll('.remove-item-btn');
                    removeButtons.forEach(btn => btn.disabled = false);
                    const checkoutBtn = document.getElementById('checkoutBtn');
                    if (checkoutBtn) checkoutBtn.disabled = false;
                });
        });
    }

    function updateItemNumbers() {
        const items = document.querySelectorAll('.cart-item');
        items.forEach((item, index) => {
            const itemNumber = item.querySelector('strong');
            if (itemNumber) {
                itemNumber.textContent = 'Item #' + (index + 1);
            }
        });
    }

    // Function to render barcodes in the cart (make it global)
    window.renderCartBarcodes = function () {
        // Wait for JsBarcode to be available
        if (typeof JsBarcode === 'undefined') {
            console.warn('JsBarcode not loaded yet, retrying...');
            setTimeout(window.renderCartBarcodes, 100);
            return;
        }

        const barcodeSvgs = document.querySelectorAll('.barcode-svg');
        if (barcodeSvgs.length === 0) {
            console.log('No barcode SVG elements found in cart');
            return;
        }

        console.log('Found ' + barcodeSvgs.length + ' barcode SVG elements to render');

        // Check if we have valid barcode values
        let validBarcodes = false;
        barcodeSvgs.forEach(function (svg, idx) {
            // Skip if already rendered (has children)
            if (svg.children.length > 0) {
                return;
            }

            const value = svg.getAttribute('data-value');
            if (!value) {
                console.log('No value found for barcode:', svg.id);
                return;
            }

            validBarcodes = true;
            const format = svg.getAttribute('data-format') || 'EAN13';
            const displayValue = svg.getAttribute('data-display') === 'true';
            const lineColor = svg.getAttribute('data-line-color') || '#000000';
            const backgroundColor = svg.getAttribute('data-bg-color') || '#FFFFFF';
            const width = svg.getAttribute('data-width') || '2';
            const height = svg.getAttribute('data-height') || '100';

            try {
                // Clean the value - remove any suffix like "-1", "-2" for display
                let cleanValue = value;
                if (cleanValue.includes('-')) {
                    const parts = cleanValue.split('-');
                    cleanValue = parts[0];
                }

                console.log('Rendering barcode:', cleanValue, 'Format:', format, 'SVG:', svg.id || idx);
                JsBarcode(svg, cleanValue, {
                    format: format,
                    displayValue: displayValue,
                    lineColor: lineColor,
                    background: backgroundColor,
                    width: parseFloat(width),
                    height: parseFloat(height)
                });
                console.log('Barcode rendered successfully for:', cleanValue);
            } catch (e) {
                console.error('Error rendering barcode:', e, 'Value:', value, 'Format:', format);
            }
        });
    };

    // Render barcodes when modal is shown - using Bootstrap 5 event
    document.addEventListener('DOMContentLoaded', function () {
        const cartModal = document.getElementById('cartModal');
        if (cartModal) {
            // Use Bootstrap 5 modal events
            cartModal.addEventListener('shown.bs.modal', function () {
                console.log('Cart modal shown, attempting to render barcodes...');
                // Try multiple times with increasing delays
                setTimeout(window.renderCartBarcodes, 100);
                setTimeout(window.renderCartBarcodes, 500);
                setTimeout(window.renderCartBarcodes, 1000);
            });
        }
    });

    // Attempt to render on page load as well
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(window.renderCartBarcodes, 500);
        });
    } else {
        setTimeout(window.renderCartBarcodes, 500);
    }
</script>