<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=100%, initial-scale=1.0">
    <title>Barcode</title>
</head>

<body>
    <?php
    include 'generatebarcode.php';
    $number = 590123412345;
    generateBarcode($number);
    ?>

</body>

</html>