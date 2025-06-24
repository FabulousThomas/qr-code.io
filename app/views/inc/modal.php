<!-- Modal for customizing QR code -->
<div class="modal fade" id="customize" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="customizeLabel" aria-hidden="true">
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
                                    <input type="color" id="foregroundColor"
                                        name="foregroundColor" class="form-control color-input shadow-none border bg-transparent"
                                        value="<?= isset($_SESSION['customize']['foregroundColor']) ? sprintf("#%02x%02x%02x", $_SESSION['customize']['foregroundColor'][0], $_SESSION['customize']['foregroundColor'][1], $_SESSION['customize']['foregroundColor'][2]) : '#000000' ?>" required />
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="backgroundColor">Background Color:</label>
                                    <input type="color" id="backgroundColor"
                                        name="backgroundColor" class="form-control color-input shadow-none border bg-transparent"
                                        value="<?= isset($_SESSION['customize']['backgroundColor']) ? sprintf("#%02x%02x%02x", $_SESSION['customize']['backgroundColor'][0], $_SESSION['customize']['backgroundColor'][1], $_SESSION['customize']['backgroundColor'][2]) : '#ffffff' ?>" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="size">Size:</label>
                                    <input type="number" id="size" name="size" class="form-control shadow-none border bg-transparent" value="<?= $_SESSION['customize']['size'] ?? 300 ?>">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="margin">Margin:</label>
                                    <input type="number" id="margin" name="margin" class="form-control shadow-none border bg-transparent" value="<?= $_SESSION['customize']['margin'] ?? 10 ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="qrCodeFormat">QR Code Format:</label>
                                    <select id="qrCodeFormat" name="qrCodeFormat" class="form-control shadow-none border bg-transparent">
                                        <option value="png" selected>PNG</option>
                                        <option value="svg">SVG</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="labelText">Display Text:</label>
                                    <input type="text" id="labelText" name="labelText" class="form-control shadow-none border bg-transparent" value="<?= $_SESSION['customize']['labelText'] ?? '' ?>" placeholder="Optional">
                                </div>
                            </div>
                            <div class="row d-non">
                                <div class="col-md-6 mb-2">
                                    <label for="logo">Logo:</label>
                                    <input type="file" id="logo" name="logo" class="form-control shadow-none border bg-transparent" value="<?= $_SESSION['logoPath'] ?? '' ?>" accept="image/*">
                                    <?php if (isset($_SESSION['logoPath']) && $_SESSION['logoPath']): ?>
                                        <label>Current Logo: <img src="<?= URLROOT ?>/images/uploads/<?= $_SESSION['logoPath'] ?>" alt="Logo" style="width: 50px;"></label>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <small class="text-uppercase text-danger">Note-> More features coming soon< </small>
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <button type="submit" name="btnCustomize" class="form-control btn btn-orange rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square" style="font-size: 1.1rem;"></i>Apply Changes</button>
                                        </div>
                                    </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="card-body text-center border py-4">
                            <img id="qrCodePreview" src="<?= $_SESSION['qrImageData'] ?? '' ?>" alt="QR Code Preview" class="img-fluid" style="width: 250px;">

                            <div class="d-flex justify-content-center mt-2">
                                <a href="<?= $_SESSION['qrImageData'] ?? '' ?>" class="btn btn-orange rounded-0" download="qrcode.png">
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

<div class="modal fade" id="cartModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php   
                // session_start();

                // Check if the cart is empty
                if (empty($_SESSION['cart'])) {
                    echo "Your cart is empty!";
                    exit;
                }

                // Display cart items
                foreach ($_SESSION['cart'] as $index => $item) {
                    echo "Barcode: " . $item['barcodeValue'] . "<br>";
                    echo "Format: " . $item['format'] . "<br>";
                    echo "<hr>";
                }
                ?>
            </div>

        </div>
    </div>
</div>