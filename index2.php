<?php
require_once "directories.php";
require_once "barcode.php";
?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title></title>
   <meta name="description" content="">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <?php require_once APPROOT . '/inc/links.php' ?>
</head>

<body>

   <div class="container py-5">
      <div class="row justify-content-center mx-auto">
         <div class="col-md-6 card shadow-sm border-0">
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
               <div class="form-group mb-3">
                  <label for="" class="form-label mb-0">Text &dash; Keywords</label>
                  <textarea name="bartext" id="bartext" class="form-control shadow-none" placeholder="Enter Text/Number"></textarea>
               </div>
               <div class="form-group mb-3">
                  <button type="submit" name="btn_barcode" class="btn btn-primary">Generate</button>
               </div>
            </form>
         </div>
      </div>

      <div class="row mt-5">
         <?php $query = $con->query("SELECT * FROM `qr-codes` ORDER BY `id` DESC"); ?>
         <?php if (mysqli_num_rows($query) >= 1) : ?>
            <?php while ($row = $query->fetch_assoc()) : ?>
               <div class="col-lg-3 col-md-6 card border-0 shadow-sm mb-3">

                  <!-- <img src="<?= URLROOT ?>/images/qrimage/<?= $row['qrimage'] ?>" alt="<?= $row['qrtext'] ?>"> -->
               </div>
            <?php endwhile; ?>
         <?php else : ?>
            <h4>NOTHING TO DISPLAY</h4>
         <?php endif; ?>
      </div>

   </div>

   <?php require_once APPROOT . '/inc/script-links.php' ?>
</body>

</html>