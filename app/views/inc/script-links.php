<script src="<?= URLROOT ?>/assets/js/jquery3.7.1.js"></script>
<script src="<?= URLROOT ?>/assets/js/sweet-alert.js"></script>
<script src="<?= URLROOT ?>/assets/js/bootstrap5.2.3.min.js"></script>
<script src="<?= URLROOT ?>/assets/js/swipper-10.2.0.js"></script>
<script src="<?= URLROOT ?>/assets/js/main.js"></script>
<script src="<?= URLROOT ?>/assets/js/htmx.min.js"></script>
<!-- <script src="<?= URLROOT ?>/assets/js/jsBarcode.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- Initializer Swiper -->
<script src="<?= URLROOT ?>/assets/js/swipper-script.js"></script>

<!-- Paystack -->
<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
   const paystackPublicKey = "your_public_key_here"; // Replace with your Paystack Public Key

   document.getElementById('paystackButton').addEventListener('click', function() {
      const handler = PaystackPop.setup({
         key: paystackPublicKey,
         email: "customer@example.com", // Replace with the customer's email
         amount: 5000 * 100, // Amount in kobo (e.g., 5000 = ₦50.00)
         currency: "NGN", // Currency (e.g., NGN for Nigerian Naira)
         ref: "BARCODE_" + Math.floor((Math.random() * 1000000000) + 1), // Unique transaction reference
         callback: function(response) {
            // Payment was successful
            alert('Payment successful! Transaction reference: ' + response.reference);

            // Optionally, send the transaction reference to your server for verification
            verifyPayment(response.reference);
         },
         onClose: function() {
            // Payment was canceled
            alert('Payment canceled.');
         }
      });

      handler.openIframe();
   });

   // Function to verify payment on the server
   function verifyPayment(reference) {
      fetch('verify_payment.php', {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json',
            },
            body: JSON.stringify({
               reference: reference
            }),
         })
         .then(response => response.json())
         .then(data => {
            if (data.status === 'success') {
               alert('Payment verified successfully!');
            } else {
               alert('Payment verification failed.');
            }
         })
         .catch(error => console.error('Error:', error));
   }
</script>

<script>
   function showPassword() {
      var x = document.getElementById("show_pass");
      if (x.type === "password") {
         x.type = "text";
      } else {
         x.type = "password";
      }
   }

   $(document).ready(function() {

      $('.toast').toast('show');
   });
</script>

