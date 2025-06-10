<?php
require_once 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

class Bar extends Controller
{

    public function index() {

        $this->view('bar');
    }

}
