<!-- HEAD -->
<?php include APPROOT . '/views/inc/head.php'; ?>

<body>
    <!-- NAVBAR -->
    <?php include APPROOT . '/views/inc/navbar.php'; ?>

    <div class="container mt-5 text-">
        <h3 class="text-center mb-4">Dynamic Barcode Generator</h3>
        <p class="text-center">To generate a barcode, enter the desired data in the input field.</p>
        <form method="POST" autocomplete="off" id="barcodeForm">
            <div class="row shadow rounded-0 dark-box mt-5 p-lg-3 p-md-1">
                <div class="col-lg-8 col-md-12 dark-box">
                    <div class="card rounded-0 my- border-0 bg-transparent">
                        <div class="card-body pt-">

                            <div class="mb-2">
                                <label for="" class="form-label mb-1">Barcode Content</label>
                                <div class="input-group">
                                    <input type="text" class="form-control shadow-none border rounded-end-0"
                                        name="barcodeValue" id="barcodeValue"
                                        placeholder="Enter barcode content Or Get random number"
                                        value="<?= $data['barcodeValue'] ?>" required>
                                    <button type="button" onclick="generateRandomNumber()"
                                        class="btn btn-green rounded-start-0"><i class="las la-random"
                                            style="font-size: 1.3rem;"></i></button>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label for="format" class="form-label">Barcode Type</label>
                                <select id="format" name="format" class="form-select shadow-none border">
                                    <option class="text-dark" value="CODE128" <?= $data['format'] === 'CODE128' ? 'selected' : ''; ?>>CODE-128</option>
                                    <option class="text-dark" value="EAN13" <?= $data['format'] === 'EAN13' ? 'selected' : ''; ?>>EAN-13</option>
                                    <option class="text-dark" value="UPC" <?= $data['format'] === 'UPC' ? 'selected' : ''; ?>>UPC</option>
                                    <option class="text-dark" value="CODE39" <?= $data['format'] === 'CODE39' ? 'selected' : ''; ?>>CODE-39</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label for="numBarcodes" class="form-label mb-1">Number of Barcodes</label>
                                <input type="number" class="form-control shadow-none border" name="numBarcodes"
                                    id="numBarcodes" value="<?= $data['numBarcodes']; ?>" min="1" max="100" required>
                            </div>
                            <div class="card-body text-center px-0">
                                <div class="row d-flex flex-colum align-items-center justify-content-center">
                                    <div class="col-md-6 col-sm-12 mb-1">
                                        <button type="button"
                                            class="btn btn-green form-control rounded-0 d-flex align-items-center justify-content-center shadow-none"
                                            id="addToCart"><i class="las la-plus-square" style="font-size: 1.1rem;"></i>
                                            Generate</button>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-1">
                                        <button type="submit" name="generateRandom"
                                            class="btn btn-green form-control rounded-0 d-flex align-items-center justify-content-center shadow-none"><i
                                                class="las la-plus-square" style="font-size: 1.1rem;"></i>Random
                                            Barcode(s)</button>
                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-1">
                                        <select id="imageFormat" name="imageFormat"
                                            class="form-select shadow-none border rounded-0 text-light">
                                            <option class="text-dark" value="png" <?= $data['imageFormat'] === 'png' ? 'selected' : ''; ?>>PNG</option>
                                            <option class="text-dark" value="jpg" <?= $data['imageFormat'] === 'jpg' ? 'selected' : ''; ?>>JPG</option>
                                            <option class="text-dark" value="svg" <?= $data['imageFormat'] === 'svg' ? 'selected' : ''; ?>>SVG</option>
                                            <option class="text-dark" value="pdf" <?= $data['imageFormat'] === 'pdf' ? 'selected' : ''; ?>>PDF</option>
                                        </select>

                                    </div>
                                    <div class="col-md-6 col-sm-12 mb-1">
                                        <button type="button" id="download" class="btn btn-green rounded-0 form-control"
                                            onclick="saveBarcodes()">
                                            <i class="las la-download las-sm"></i> Download Barcodes
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 sticky-top p-0 bg-light">
                    <div class="card rounded-0 rounded-end border-0 bg-light">
                        <div class="card-body text-center">
                            <h6 class="text-center">Barcode preview</h6>
                            <!-- Display the barcodes -->
                            <?php if (!empty($_SESSION['barcodeValue'])): ?>
                                <div id="barcodes">
                                    <?php foreach ($_SESSION['barcodeValue'] as $index => $value): ?>
                                        <div class="barcode-container mb-1">
                                            <svg class="barcode" id="barcode-<?= $index; ?>"></svg>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <!-- ==========CODE HERE DON'T FORGET=============== -->
                            <?php else: ?>
                                <img class="barcode" src="<?= URLROOT ?>/images/barimage/barcode-1-0.png">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row shadow rounded-0 dark-box mt-4 p-lg-3 p-md-1">
                <h6 class="text-start pt-2">Customize Barcode</h6>
                <div class="col-lg-6 col-md-12 dark-box">
                    <div class="card rounded-0 my- border-0 bg-transparent">
                        <div class="card-body pt-">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="lineColor" class="form-label">Line Color:</label>
                                    <input type="color" id="lineColor" name="lineColor"
                                        class="form-control color-input shadow-none border"
                                        value="<?= $data['lineColor'] ?>" required />
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="backgroundColor" class="form-label">Background Color:</label>
                                    <input type="color" id="backgroundColor" name="backgroundColor"
                                        class="form-control color-input shadow-none border"
                                        value="<?= $data['backgroundColor'] ?>" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="fontSize" class="form-label">Font Sise:</label>
                                    <input type="number" id="fontSize" name="fontSize"
                                        class="form-control color-input shadow-none border"
                                        value="<?= $data['fontSize'] ?>" min="10" max="20" required />
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="height" class="form-label">Barcode Height:</label>
                                    <input type="number" id="height" name="height"
                                        class="form-control color-input shadow-none border"
                                        value="<?= $data['height'] ?>" min="20" max="100" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="width" class="form-label">Line Width:</label>
                                    <input type="number" id="width" name="width" class="form-control shadow-none border"
                                        value="<?= $data['width'] ?>" min="1" max="5" required />
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="displayValue" class="form-label">Barcode displayValue:</label>
                                    <select id="displayValue" name="displayValue"
                                        class="form-select shadow-none border">
                                        <option class="text-dark" value="true" <?= $data['displayValue'] === 'true' ? 'selected' : ''; ?>>Show</option>
                                        <option class="text-dark" value="false" <?= $data['displayValue'] === 'false' ? 'selected' : ''; ?>>Hide</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="displayValue" class="form-label"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 dark-nav d-none">
                    <div class="card rounded-0 rounded-end border-0 px-0 dark-nav">
                        <div class="card-body text-center px-0">
                            <div class="row d-flex flex-colum align-items-center justify-content-center">
                                <div class="col-md-6 col-sm-12 mb-1">
                                    <button type="submit"
                                        class="btn btn-green form-control rounded-0 d-flex align-items-center justify-content-center shadow-none"><i
                                            class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate</button>
                                </div>
                                <div class="col-md-6 col-sm-12 mb-1">
                                    <button type="submit" name="generateRandom"
                                        class="btn btn-green form-control rounded-0 d-flex align-items-center justify-content-center shadow-none"><i
                                            class="las la-plus-square" style="font-size: 1.1rem;"></i>Random
                                        Barcode(s)</button>
                                </div>
                                <div class="col-md-6 col-sm-12 mb-1">
                                    <select id="imageFormat" name="imageFormat"
                                        class="form-select shadow-none border rounded-0 text-light">
                                        <option class="text-dark" value="png" <?= $data['imageFormat'] === 'png' ? 'selected' : ''; ?>>PNG</option>
                                        <option class="text-dark" value="jpg" <?= $data['imageFormat'] === 'jpg' ? 'selected' : ''; ?>>JPG</option>
                                        <option class="text-dark" value="svg" <?= $data['imageFormat'] === 'svg' ? 'selected' : ''; ?>>SVG</option>
                                        <option class="text-dark" value="pdf" <?= $data['imageFormat'] === 'pdf' ? 'selected' : ''; ?>>PDF</option>
                                    </select>

                                </div>
                                <div class="col-md-6 col-sm-12 mb-1">
                                    <button type="button" id="download" class="btn btn-green rounded-0 form-control"
                                        onclick="saveBarcodes()">
                                        <i class="las la-download las-sm"></i> Download Barcodes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paystack Payment Button -->
                <div class="container mt-5">
                    <h3 class="text-center">Complete Your Payment</h3>
                    <button id="paystackButton" class="btn btn-green form-control mt-3">Pay Now</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ================================================================== -->

    <?php include APPROOT . '/views/inc/script-links.php'; ?>

    <script>
        function generateRandomNumber() {
            // Generate 12-digit random number for EAN-13 (JsBarcode will add the check digit)
            const randomNum = Math.floor(100000000000 + Math.random() * 900000000000);
            document.getElementById('barcodeValue').value = randomNum;
        }
        // Generate the barcodes using the input or random values and customization options
        <?php if (!empty($_SESSION['barcodeValue'])): ?>
            <?php foreach ($_SESSION['barcodeValue'] as $index => $value): ?>
                JsBarcode("#barcode-<?= $index; ?>", "<?= $value; ?>", {
                    format: "<?= $data['format']; ?>", // Barcode format
                    displayValue: <?= $data['displayValue']; ?>, // Show/hide the value
                    lineColor: "<?= $data['lineColor']; ?>", // Line color
                    background: "<?= $data['backgroundColor']; ?>", // Background color
                    fontSize: <?= $data['fontSize']; ?>, // Font size
                    width: <?= $data['width']; ?>, // Line width
                    height: <?= $data['height']; ?> // Barcode height
                });
            <?php endforeach; ?>
        <?php endif; ?>

        // Function to save all barcodes
        function saveBarcodes() {
            const imageFormat = document.getElementById("imageFormat").value;

            if (imageFormat === "pdf") {
                // Save all barcodes as a single PDF
                const {
                    jsPDF
                } = window.jspdf;
                const pdf = new jsPDF();
                const barcodeContainers = document.querySelectorAll(".barcode-container");

                let promises = []; // Array to hold all promises

                barcodeContainers.forEach((container, index) => {
                    const svgElement = container.querySelector("svg");
                    const svgData = new XMLSerializer().serializeToString(svgElement);

                    // Convert SVG to canvas
                    const canvas = document.createElement("canvas");
                    const context = canvas.getContext("2d");

                    // Set canvas dimensions explicitly
                    canvas.width = 300; // Adjust as needed
                    canvas.height = 150; // Adjust as needed

                    // Draw the SVG onto the canvas
                    const img = new Image();
                    img.src = "data:image/svg+xml;base64," + btoa(svgData);

                    // Wrap the onload logic in a promise
                    const promise = new Promise((resolve) => {
                        img.onload = function () {
                            context.drawImage(img, 0, 0, canvas.width, canvas.height);

                            // Convert canvas to image data
                            const imageData = canvas.toDataURL("image/png");

                            // Add the barcode to the PDF
                            if (index > 0) pdf.addPage(); // Add a new page for each barcode after the first
                            pdf.addImage(imageData, "PNG", 10, 10, 100, 50); // Adjust dimensions as needed
                            resolve();
                        };
                    });

                    promises.push(promise); // Add the promise to the array
                });

                // Wait for all promises to resolve before saving the PDF
                Promise.all(promises).then(() => {
                    pdf.save("barcodes-" + Date.now() + ".pdf");
                });
            } else {
                // Save each barcode individually
                const barcodeContainers = document.querySelectorAll(".barcode-container");

                barcodeContainers.forEach((container, index) => {
                    const svgElement = container.querySelector("svg");
                    const svgData = new XMLSerializer().serializeToString(svgElement);

                    if (imageFormat === "svg") {
                        // Save as SVG
                        const blob = new Blob([svgData], {
                            type: "image/svg+xml"
                        });
                        const url = URL.createObjectURL(blob);

                        // Create a download link
                        const a = document.createElement("a");
                        a.href = url;
                        a.download = "barcode-" + (index + 1) + "-" + <?= $data['barcodeValue'] ?> + ".svg";
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    } else {
                        // Save as PNG or JPEG
                        const canvas = document.createElement("canvas");
                        const context = canvas.getContext("2d");

                        // Set canvas dimensions explicitly
                        canvas.width = 300; // Adjust as needed
                        canvas.height = 150; // Adjust as needed

                        // Draw the SVG onto the canvas
                        const img = new Image();
                        img.src = "data:image/svg+xml;base64," + btoa(svgData);

                        img.onload = function () {
                            context.drawImage(img, 0, 0, canvas.width, canvas.height);

                            // Convert canvas to the selected image format
                            const imageData = canvas.toDataURL("image/" + imageFormat);

                            // Create a download link
                            const a = document.createElement("a");
                            a.href = imageData;
                            a.download = "barcode-" + (index + 1) + "-" + <?= $data['barcodeValue'] ?> + "." + imageFormat;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        };
                    }
                });
            }
        }
    </script>

    <script>
        // Prevent the form from submitting normally for the Generate button
        document.addEventListener('DOMContentLoaded', function () {
            const barcodeForm = document.getElementById('barcodeForm');
            const addToCartBtn = document.getElementById('addToCart');

            console.log('Form found:', barcodeForm);
            console.log('Add to Cart button found:', addToCartBtn);

            if (!addToCartBtn) {
                console.error('Add to Cart button not found!');
                return;
            }

            // Prevent form submission when Generate button is clicked
            barcodeForm.addEventListener('submit', function (e) {
                // Only prevent if it's NOT the generateRandom button
                if (!e.submitter || !e.submitter.name || e.submitter.name !== 'generateRandom') {
                    e.preventDefault();
                    console.log('Form submission prevented');
                }
            });

            addToCartBtn.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent form submission
                e.stopPropagation(); // Stop event bubbling
                console.log('Generate button clicked!');

                const barcodeValue = document.getElementById('barcodeValue').value;
                const numBarcodes = parseInt(document.getElementById('numBarcodes').value);
                const format = document.getElementById('format').value;

                if (!barcodeValue || numBarcodes < 1) {
                    alert('Please enter valid barcode content and quantity.');
                    return;
                }

                // Get customization options
                const displayValue = document.getElementById('displayValue') ? document.getElementById('displayValue').value : 'true';
                const lineColor = document.getElementById('lineColor') ? document.getElementById('lineColor').value : '#000000';
                const backgroundColor = document.getElementById('backgroundColor') ? document.getElementById('backgroundColor').value : '#FFFFFF';
                const width = document.getElementById('width') ? document.getElementById('width').value : '2';
                const height = document.getElementById('height') ? document.getElementById('height').value : '100';

                // Prepare the barcodes to add to the cart
                const barcodes = [];

                if (numBarcodes === 1) {
                    // Single barcode
                    barcodes.push({
                        barcodeValue: barcodeValue,
                        format: format,
                        displayValue: displayValue,
                        lineColor: lineColor,
                        backgroundColor: backgroundColor,
                        width: width,
                        height: height
                    });
                } else {
                    // Multiple barcodes - group them together
                    const barcodeValues = [];
                    for (let i = 0; i < numBarcodes; i++) {
                        barcodeValues.push(barcodeValue + '-' + (i + 1)); // Append a unique identifier
                    }
                    barcodes.push({
                        barcodeValue: barcodeValues,
                        format: format,
                        displayValue: displayValue,
                        lineColor: lineColor,
                        backgroundColor: backgroundColor,
                        width: width,
                        height: height
                    });
                }

                // Validate barcodes array before sending
                if (!barcodes || barcodes.length === 0) {
                    console.error('Barcodes array is empty!');
                    alert('Error: No barcodes to send');
                    return;
                }

                console.log('Number of barcodes:', barcodes.length);
                console.log('Barcodes array:', JSON.stringify(barcodes, null, 2));

                const payload = { barcodes: barcodes };
                console.log('Full payload:', JSON.stringify(payload, null, 2));

                // Send the barcodes to the server
                fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        barcodes: barcodes
                    }),
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Server response:', data);
                        if (data.status === 'success') {
                            swal("Added!", data.message || 'Barcodes added to cart successfully!', "success").then(() => {
                                // Reload page to show updated cart and preview
                                location.reload();
                            });
                        } else {
                            swal("Error!", data.message || 'Failed to add barcodes to cart.', "error");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        swal("Error!", "An error occurred while adding barcodes to cart.", "error");
                    });
            });
        });
    </script>

    <?php include APPROOT . "/views/inc/modal.php" ?>
</body>

</html>