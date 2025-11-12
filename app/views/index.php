<!-- HEAD START -->
<?php include APPROOT . "/views/inc/head.php" ?>

<body>
    <!-- NAVBAR START -->
    <?php include APPROOT . "/views/inc/navbar.php" ?>

    <div class="container mt-5">
        <div class="text-center mb-5 w-75 mx-auto">
            <h1 class="mb-4">QR-Hub.io Universal Generator</h1>
            <p class="" style="font-size: 1.2rem;">Generate <strong>beautiful</strong> QR codes, stand out from the crowd. Create <i>dynamic</i> QR codes to get scan statistics and insights.</p>
        </div>

        <div class="row shadow rounded-0 dark-box">
            <div class="col-lg-8 col-md-12 dark-box">
                <div class="card rounded-0 my-4 border-0">
                    <div class="card-body p-0 border-0 dark-nav">
                        <ul class="nav nav-tabs border-0">
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#link-tab" data-bs-toggle="tab">
                                    <i class="me-1 las la-link icon-sm"></i>
                                    Link
                                </a>
                            </li>
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#text-tab" data-bs-toggle="tab">
                                    <i class="me-1 las la-comment icon-sm"></i>
                                    Text
                                </a>
                            </li>
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#email-tab" data-bs-toggle="tab">
                                    <i class="me-1 las la-envelope icon-sm"></i>
                                    E&dash;mail
                                </a>
                            </li>
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#call-tab" data-bs-toggle="tab">
                                    <i class="me-1 las la-phone-square icon-sm"></i>
                                    Call
                                </a>
                            </li>
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#sms-tab" data-bs-toggle="tab">
                                    <i class="me-1 las la-sms icon-sm"></i>
                                    SMS
                                </a>
                            </li>
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#vcard-tab" data-bs-toggle="tab">
                                    <i class="me-1 las la-id-card icon-sm"></i>
                                    V&dash;card
                                </a>
                            </li>
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#whatsapp-tab" data-bs-toggle="tab">
                                    <i class="me-1 lab la-whatsapp icon-sm"></i>
                                    Whatsapp
                                </a>
                            </li>
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#wifi-tab" data-bs-toggle="tab">
                                    <i class="me-1 las la-wifi icon-sm"></i>
                                    WI&dash;FI
                                </a>
                            </li>
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#paypal-tab" data-bs-toggle="tab">
                                    <i class="me-1 lab la-paypal icon-sm"></i>
                                    Paypal
                                </a>
                            </li>
                            <li class="qr-item">
                                <a class="qr-link p-3" href="#app-tab" data-bs-toggle="tab">
                                    <i class="me-1 las la-mobile icon-sm"></i>
                                    App
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>

                <div class="card rounded-0 my- border-0 bg-transparent">
                    <div class="card-body pt-0">
                        <div id="tabs" class="tab-content">
                            <!-- LINK TAB -->
                            <div class="tab-pane" id="link-tab">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="qrUrl" class="form-label mb-0">Link Url
                                            <small class="text-black-50"> (The QR Code will open this URL.) </small>
                                        </label>
                                        <input type="url" name="qrUrl" id="qrUrl" class="form-control shadow-none border" placeholder="https://example.com"
                                            pattern="[Hh][Tt][Tt][Pp][Ss]?:\/\/(?:(?:[a-zA-Z\u00a1-\uffff0-9]+-?)*[a-zA-Z\u00a1-\uffff0-9]+)(?:\.(?:[a-zA-Z\u00a1-\uffff0-9]+-?)*[a-zA-Z\u00a1-\uffff0-9]+)*(?:\.(?:[a-zA-Z\u00a1-\uffff]{2,}))(?::\d{2,5})?(?:\/[^\s]*)?"
                                            required />
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="btnUrl" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square las-sm"></i> Generate QR Code</button>
                                    </div>
                                </form>
                            </div>
                            <!-- TEXT TAB -->
                            <div id="text-tab" class="tab-pane">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-0">Text</label>
                                        <div class="mb-3">
                                            <textarea name="qrText" id="qrText" class="form-control shadow-none border" placeholder="Write your text here" required></textarea>
                                            <small class="text-black-50">Scanning the QR code will show this text</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="btnText" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square las-sm"></i> Generate QR Code</button>
                                    </div>
                                </form>
                            </div>
                            <!-- EMAIL TAB -->
                            <div id="email-tab" class="tab-pane">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-0">E&dash;mail Content</label>
                                        <input type="email" name="qrEmail" id="qrEmail" class="form-control shadow-none border" placeholder="Your E&dash;mail Address" required />
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="qrSubject" id="qrSubject" class="form-control shadow-none border" placeholder="E&dash;mail Subject" required />
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="qrMessage" id="qrMessage" class="form-control shadow-none border" placeholder="Your Message" required></textarea>
                                        <small class="text-black-50">Scanning the QR code will send this e&dash;mail</small>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="btnEmail" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate QR Code</button>
                                    </div>
                                </form>
                            </div>
                            <!-- CALL TAB -->
                            <div id="call-tab" class="tab-pane">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-0">Phone Call</label>
                                        <div class="mb-3">
                                            <input type="tel" name="qrPhoneNumber" id="qrPhoneNumber" pattern="[+][1-9]{2}\d{3}?\d{6,14}" class="form-control shadow-none border" placeholder="Your Number (+123456789)" required />
                                            <small class="text-black-50">Scanning the QR code will send message to the number</small>
                                            <div class="mb-3">
                                                <button type="submit" name="btnCall" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate QR Code</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- SMS TAB -->
                            <div id="sms-tab" class="tab-pane">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-0">SMS</label>
                                        <div class="mb-3">
                                            <input type="tel" name="qrSMSnum" id="qrSMSnum" pattern="[+][1-9]{2}\d{3}?\d{6,14}" class="form-control shadow-none border" placeholder="Your Number (+123456789)" required />
                                        </div>
                                        <div class="mb-3">
                                            <textarea name="qrSMSmsg" id="qrSMSmsg" class="form-control shadow-none border" placeholder="Message" required></textarea>
                                            <small class="text-black-50">Scanning the QR code will call this number</small>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" name="btnSMS" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate QR Code</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- V-CARD TAB -->
                            <div id="vcard-tab" class="tab-pane">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-0">V-Card</label>
                                        <div class="mb-3">
                                            <input type="text" name="qrVcardName" id="qrVcardName" class="form-control shadow-none border" placeholder="Your Name" required />
                                        </div>
                                        <div class="mb-3">
                                            <input type="tel" name="qrVcardNum" id="qrVcardNum" pattern="[+][1-9]{2}\d{3}?\d{6,14}" class="form-control shadow-none border" placeholder="Your Number (+123456789)" required />
                                        </div>
                                        <div class="mb-3">
                                            <input type="email" name="qrVcardEmail" id="qrVcardEmail" class="form-control shadow-none border" placeholder="Your Email (example@email.com)" />
                                        </div>
                                        <div class="mb-3">
                                            <textarea name="qrVcardAdd" id="qrVcardAdd" class="form-control shadow-none border" placeholder="Address" rows="1"></textarea>
                                            <small class="text-black-50">Scanning the QR code will call this number</small>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" name="btnVCard" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate QR Code</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- WI-FI TAB -->
                            <div id="wifi-tab" class="tab-pane">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-0">Wi-Fi Content</label>
                                        <input type="text" name="qrWifiName" id="qrWifiName" class="form-control shadow-none border" placeholder="Your Network Name (SSID)" required />
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" name="qrWifiPass" id="qrWifiPass" class="form-control shadow-none border" placeholder="Network Password" />
                                    </div>
                                    <div class="mb-2 d-flex justify-content-evenly">
                                        <label for="qrWifiSec4" class="form-label mb-0">
                                            <input type="radio" name="qrWifiSec" id="qrWifiSec4" class="shadow-none" value="None" checked> None
                                        </label>
                                        <label for="qrWifiSec1" class="form-label mb-0">
                                            <input type="radio" name="qrWifiSec" id="qrWifiSec1" class="shadow-none" value="WPA"> WPA
                                        </label>
                                        <label for="qrWifiSec2" class="form-label mb-0">
                                            <input type="radio" name="qrWifiSec" id="qrWifiSec2" class="shadow-none" value="WEP"> WEP
                                        </label>
                                        <label for="qrWifiSec3" class="form-label mb-0">
                                            <input type="radio" name="qrWifiSec" id="qrWifiSec3" class="shadow-none" value="WPA2"> WPA2
                                        </label>
                                    </div>
                                    <small class="text-black-50">Scanning the QR code will connect you to the network</small>
                                    <div class="mb-3 mt-2">
                                        <button type="submit" name="btnWifi" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate QR Code</button>
                                    </div>
                                </form>
                            </div>
                            <!-- WHATSAPP TAB -->
                            <div id="whatsapp-tab" class="tab-pane">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-0">WhatsApp Content</label>
                                        <input type="tel" name="qrWhatsappNum" id="qrWhatsappNum" class="form-control shadow-none border" placeholder="Your WhatsApp Number" pattern="[+][1-9]{1}[0-9]{3,14}" required />
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="qrWhatsappMsg" id="qrWhatsappMsg" class="form-control shadow-none border" placeholder="Your WhatsApp Message" rows="1"></textarea>
                                    </div>
                                    <small class="text-black-50">Scanning the QR code will send a WhatsApp Message</small>
                                    <div class="mb-3 mt-2">
                                        <button type="submit" name="btnWhatsApp" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate QR Code</button>
                                    </div>
                                </form>
                            </div>
                            <!-- PAYPAL TAB -->
                            <div id="paypal-tab" class="tab-pane">
                                <h3 class="text-center">Not Available</h3>
                                <form action="" method="POST" class="d-none">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-0">WhatsApp Content</label>
                                        <input type="tel" name="qrWhatsappNum" id="qrWhatsappNum" class="form-control shadow-none border" placeholder="Your WhatsApp Number" pattern="[+][1-9]{1}[0-9]{3,14}" required />
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="qrWhatsappMsg" id="qrWhatsappMsg" class="form-control shadow-none border" placeholder="Your WhatsApp Message" rows="1"></textarea>
                                    </div>
                                    <small class="text-black-50">Scanning the QR code will send a WhatsApp Message</small>
                                    <div class="mb-3 mt-2">
                                        <button type="submit" name="btnWhatsApp" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate QR Code</button>
                                    </div>
                                </form>
                            </div>
                            <!-- APP TAB -->
                            <div id="app-tab" class="tab-pane">
                                <h3 class="text-center">Not Available</h3>
                                <form action="" method="POST" class="d-none">
                                    <div class="mb-3">
                                        <label for="" class="form-label mb-0">WhatsApp Content</label>
                                        <input type="tel" name="qrWhatsappNum" id="qrWhatsappNum" class="form-control shadow-none border" placeholder="Your WhatsApp Number" pattern="[+][1-9]{1}[0-9]{3,14}" required />
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="qrWhatsappMsg" id="qrWhatsappMsg" class="form-control shadow-none border" placeholder="Your WhatsApp Message" rows="1"></textarea>
                                    </div>
                                    <small class="text-black-50">Scanning the QR code will send a WhatsApp Message</small>
                                    <div class="mb-3 mt-2">
                                        <button type="submit" name="btnWhatsApp" class="btn btn-orange form-control rounded-0 d-flex align-items-center justify-content-center"><i class="las la-plus-square" style="font-size: 1.1rem;"></i> Generate QR Code</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-12 sticky-top p-0 bg-light">
                <div class="card rounded-0 rounded-end border-0 bg-light">
                    <div class="card-body text-center">
                        <?php if (!empty($_SESSION['qrImageData'])) : ?>
                            <h5 class="text-center">Generated QR Code</h5>
                            <img src="<?= $_SESSION['qrImageData'] ?? '' ?>" alt="QR Code" class="img-fluid" style="width: 250px;">
                            <div class="d-flex justify-content-center mt-2">
                                <a href="<?= $_SESSION['qrImageData'] ?? '' ?>" class="btn btn-orange rounded-0" download="qrcode.png">
                                    <i class="las la-download las-sm"></i> Download QR Code
                                </a>
                            </div>

                            <div class="d-flex justify-content-center mt-2">
                                <a data-bs-toggle="modal" data-bs-target="#customize" class="btn btn-green rounded-0">
                                    <i class="las la-pen las-sm"></i> Customize QR Code
                                </a>
                            </div>

                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="btn btn-orange rounded-0" id="addQrToCart">
                                    <i class="las la-shopping-cart las-sm"></i> Add to Cart
                                </button>
                            </div>

                        <?php else : ?>
                            <h5 class="text-center">Sample QR Code</h5>
                            <img src="<?= URLROOT ?>/images/qrimage/1734600549.png" alt="" class="img-fluid" style="width: 250px;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once APPROOT . '/views/inc/script-links.php' ?>

    <script>
        $(document).ready(function() {
            // Function to activate a tab
            function activateTab(tab) {
                $('.nav-tabs a[href="#' + tab + '"]').tab('show');
                localStorage.setItem('activeTab', tab);
            }

            // Check if there's a saved active tab in local storage
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                activateTab(activeTab);
            } else {
                activateTab('link-tab'); // Default tab
            }

            // Handle tab clicks
            $('.nav-tabs a').on('click', function(e) {
                e.preventDefault();
                var tabId = $(this).attr('href').substring(1);
                activateTab(tabId);
            });

            // Handle Add QR Code to Cart
            $('#addQrToCart').on('click', function() {
                fetch('<?= URLROOT ?>/index/addQrToCart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        swal("Added!", "QR Code has been added to your cart.", "success");
                    } else {
                        swal("Error!", data.message || 'Failed to add QR code to cart', "error");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    swal("Error!", "An error occurred. Please try again.", "error");
                });
            });
        });
    </script>


    <?php include APPROOT . "/views/inc/modal.php" ?>


    <!-- <img id="barcode"></img> -->

    <?php $randNumber = random_int(-234, 999999999999); ?>

    <script>
        JsBarcode("#barcode", "<?= $randNumber ?>", {
            format: "ean13",
            lineColor: "#000",
            width: 2,
            height: 40,
            displayValue: true
        });
    </script>

</body>

</html>