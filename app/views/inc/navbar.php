<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
   <div class="container">
      <a class="navbar-brand" href="<?= URLROOT ?>">
         <h4 class="mb-0">QR&dash;Hub.io</h4>
      </a>
      <button class="navbar-toggler d-lg-none shadow-none border-0 bg-transparent p-0 m-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#menu"><i class="las la-bars" style="font-size: 2rem;"></i></button>
      <div class="collapse navbar-collapse">
         <ul class="navbar-nav m-auto mt-2 mt-lg-0">
            <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown">
                  QR Codes
               </a>
               <div class="dropdown-menu border-0 rounded-0 shadow-sm">
                  <a class="dropdown-item text-black-50" href="<?= URLROOT ?>">Static QR Codes</a>
                  <a class="dropdown-item text-black-50" href="">Dynamic QR Codes</a>
               </div>
            </li>

            <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown">
                  Bar Codes
               </a>
               <div class="dropdown-menu border-0 rounded-0 shadow-sm">
                  <a class="dropdown-item text-black-50" href="<?= URLROOT ?>/barcode">Static Bar Codes</a>
                  <a class="dropdown-item text-black-50" href="">Dynamic Bar Codes</a>
               </div>
            </li>

            <li class="nav-item dropdown">
               <a class="nav-link dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown">
                  Products
               </a>
               <div class="dropdown-menu border-0 rounded-0 shadow-sm">
                  <a class="dropdown-item text-black-50" href="">QR Codes</a>
                  <a class="dropdown-item text-black-50" href="">Bar Codes</a>
                  <a class="dropdown-item text-black-50" href="">Features</a>
               </div>
            </li>

            <li class="nav-item">
               <a class="nav-link" href="">Why Us?</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="">Contact Us</a>
            </li>

         </ul>
         <ul class="navbar-na d-flex my-2 my-lg-0">
            <a class="btn btn-orange btn-sm rounded-0 me-2" href=""><i class="las la-sign-in-alt las-sm"></i> Log In</a>
            <a class="btn btn-green btn-sm rounded-0 me-2" href=""><i class="las la-user-plus las-sm"></i> Sign Up</a>
            <button class="btn btn-green btn-sm rounded-0" type="button" data-bs-toggle="modal" data-bs-target="#cartModal"><i class="las la-user-plus las-sm"></i> Cart</button>
         </ul>
      </div>
   </div>
</nav>
<?php include APPROOT . "/views/inc/menu.php" ?>