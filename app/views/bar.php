<?php
include APPROOT . "/views/inc/head.php";

$randNumber = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_REQUEST['getBarcode'])) {
        // Set default barcode data if no form input is provided
        $randNumber = random_int(111111111111, 999999999999);
        $barcodeData = $_REQUEST['barcode'] ?? $randNumber;

        if (isset($_REQUEST['barcodeImage'])) {
            $barcodeImage = $_REQUEST['barcodeImage'];

            // Decode the base64 image data and save it to a folder
            // $filePath = 'images/barimage/' . $barcodeData . '.png';
            // $barcodeImage = str_replace('data:image/png;base64,', '', $barcodeImage);
            // $barcodeImage = str_replace(' ', '+', $barcodeImage);
            // $imageData = base64_decode($barcodeImage);
            // file_put_contents($filePath, $imageData);
        } else {
            echo "Error: Barcode image data is missing.";
        }
    }
}
?>

<body>
    <!-- NAVBAR -->
    <?php include APPROOT . "/views/inc/navbar.php"; ?>

    <div class="container mt-5 text-">
        <h3 class="text-center mb-4">Dynamic Barcode Generator</h3>
        <p class="text-center">To generate a barcode, enter the desired data in the input field.</p>

        <div class="row shadow rounded-0 dark-box mt-5 p-lg-3 p-md-1">
            <div class="col-lg-8 col-md-12 dark-box">
                <div class="card rounded-0 my- border-0 bg-transparent">
                    <div class="card-body pt-">
                        <form method="POST" autocomplete="off" id="barcodeForm">
                            <div class="mb-3">
                                <label for="" class="form-label mb-1">Barcode Content</label>
                                <div class="input-group">
                                    <input type="text" class="form-control shadow-none border rounded-end-0" name="barcodeValue" id="barcodeValue" placeholder="Enter barcode content Or Get random number" required>
                                    <button class="btn btn-green rounded-start-0" type="button" id="generateRandom"><i class="las la-random" style="font-size: 1.3rem;"></i></button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="numBarcodes" class="form-label mb-1">Number of Barcodes</label>
                                <input type="number" class="form-control shadow-none border" name="numBarcodes" id="numBarcodes" min="1" max="100" value="1" required>
                            </div>

                            <div class="mb-3">
                                <input type="text" name="qrSubject" id="qrSubject" class="form-control shadow-none border" placeholder="E&dash;mail Subject" />
                            </div>
                            <div class="mb-3">
                                <textarea name="qrMessage" id="qrMessage" class="form-control shadow-none border" placeholder="Your Message"></textarea>
                                <small class="text-black-50">Scanning the QR code will send this e&dash;mail</small>
                            </div>
                            <div class="mb-">
                                <button type="submit" class="btn btn-green form-control rounded-0 d-flex align-items-center justify-content-center shadow-none"><i class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 sticky-top p-0 bg-light">
                <div class="card rounded-0 rounded-end border-0 bg-light">
                    <div class="card-body text-center">
                        <h5 class="text-center">Generated Barcodes</h5>
                        <div id="barcodeContainer">
                            <!-- Barcodes will be dynamically added here -->
                        </div>
                        <div class="d-flex justify-content-center mt-2">
                            <select id="imageFormat" class="form-select shadow-none border rounded-0">
                                <option value="png">PNG</option>
                                <option value="jpeg">JPEG</option>
                                <option value="svg">SVG</option>
                                <option value="pdf">PDF</option>
                            </select>
                            <button id="download" class="btn btn-orange rounded-0">
                                <i class="las la-download las-sm"></i> Download Barcodes
                            </button>
                        </div>

                        <div class="d-flex justify-content-center mt-2">
                            <a data-bs-toggle="modal" data-bs-target="#customize" class="btn btn-green rounded-0">
                                <i class="las la-pen las-sm"></i> Customize Barcodes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once APPROOT . '/views/inc/script-links.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        // Function to generate a random 12-digit number for the barcode
        function generateRandomNumber() {
            let randomNumber = '';
            for (let i = 0; i < 12; i++) {
                randomNumber += Math.floor(Math.random() * 10); // Generate a random digit (0-9)
            }
            return randomNumber;
        }

        // Function to generate barcode with a specific value
        function generateBarcode(value, container) {
            const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svg.classList.add("barcode");
            container.appendChild(svg);

            JsBarcode(svg, value, {
                format: "EAN13", // Barcode format (e.g., EAN13, CODE128)
                displayValue: true, // Display the barcode value below the barcode
            });
        }

        // Function to generate multiple barcodes
        function generateMultipleBarcodes() {
            const barcodeValue = document.getElementById("barcodeValue").value;
            const numBarcodes = parseInt(document.getElementById("numBarcodes").value);
            const barcodeContainer = document.getElementById("barcodeContainer");

            // Clear previous barcodes
            barcodeContainer.innerHTML = "";

            // Generate the specified number of barcodes
            for (let i = 0; i < numBarcodes; i++) {
                const barcodeDiv = document.createElement("div");
                barcodeDiv.classList.add("barcode-wrapper");
                barcodeContainer.appendChild(barcodeDiv);

                const value = barcodeValue || generateRandomNumber(); // Use input or generate random
                generateBarcode(value, barcodeDiv);
            }
        }

        // Handle clicking the button to generate a random number and populate the input
        document.getElementById("generateRandom").addEventListener("click", function() {
            const randomBarcodeValue = generateRandomNumber(); // Generate random number
            document.getElementById("barcodeValue").value = randomBarcodeValue; // Populate the input
            generateMultipleBarcodes(); // Generate barcodes based on random number
        });

        // Handle input event to generate barcodes as the user types
        document.getElementById("barcodeValue").addEventListener("input", function() {
            generateMultipleBarcodes(); // Generate barcodes dynamically
        });

        // Handle form submission to generate multiple barcodes
        document.getElementById("barcodeForm").addEventListener("submit", function(e) {
            e.preventDefault(); // Prevent form submission
            generateMultipleBarcodes(); // Generate barcodes
        });

        // Handle downloading the barcodes in the selected format
        document.getElementById("download").addEventListener("click", function() {
            const format = document.getElementById("imageFormat").value; // Get selected format
            const barcodeWrappers = document.querySelectorAll(".barcode-wrapper");

            if (format === "pdf") {
                // Save all barcodes as a single PDF
                const {
                    jsPDF
                } = window.jspdf;
                const pdf = new jsPDF();

                let promises = []; // Array to hold all promises

                barcodeWrappers.forEach((wrapper, index) => {
                    const svg = wrapper.querySelector("svg");
                    const svgData = new XMLSerializer().serializeToString(svg);

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
                        img.onload = function() {
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
                    pdf.save("barcodes.pdf"); // Save the PDF
                });
            } else {
                // Save each barcode individually
                barcodeWrappers.forEach((wrapper, index) => {
                    const svg = wrapper.querySelector("svg");
                    const svgData = new XMLSerializer().serializeToString(svg);

                    if (format === "svg") {
                        // Save as SVG
                        const blob = new Blob([svgData], {
                            type: "image/svg+xml"
                        });
                        const url = URL.createObjectURL(blob);

                        // Create a download link
                        const a = document.createElement("a");
                        a.href = url;
                        a.download = `barcode-${index + 1}.svg`;
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

                        img.onload = function() {
                            context.drawImage(img, 0, 0, canvas.width, canvas.height);

                            // Convert canvas to the selected image format
                            const imageData = canvas.toDataURL("image/" + format);

                            // Create a download link
                            const a = document.createElement("a");
                            a.href = imageData;
                            a.download = `barcode-${index + 1}.${format}`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        };
                    }
                });
            }
        });
    </script>
</body>

</html>