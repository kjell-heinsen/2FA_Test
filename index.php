<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('log_errors', 'On');
ini_set('error_log', 'php-errors.log');
require 'autoload.php';


use Vectorface\GoogleAuthenticator;



function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen( $output_file, 'wb' );
    $data = explode( ',', $base64_string );
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    fclose( $ifp );
    return $output_file;
}

$ga = new GoogleAuthenticator();
$secret = $ga->createSecret();
echo "Secret is: {$secret}\n\n";
echo "</br></br>";

$qrCodeUrl = $ga->getQRCodeUrl('Blog', $secret);
echo "PNG Data URI for the QR-Code: {$qrCodeUrl}\n\n";
echo "</br></br>";
?>
<img src="<?php echo $qrCodeUrl; ?> " />

<?php
$path = __DIR__.'/qrcode.png';
base64_to_jpeg($qrCodeUrl,$path);

echo "</br></br>";


$oneCode = $ga->getCode($secret);
echo "Checking Code '$oneCode' and Secret '$secret':\n";
echo "</br></br>";

// 2 = 2*30sec clock tolerance
$checkResult = $ga->verifyCode($secret, $oneCode, 2);
if ($checkResult) {
    echo 'OK';
} else {
    echo 'FAILED';
}
echo "</br></br>";
