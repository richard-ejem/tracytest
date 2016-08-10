<?php
require __DIR__ . '/vendor/autoload.php';
session_start();

if (strpos($_SERVER['QUERY_STRING'], '_tracy_bar') !== FALSE
    && strpos($_SERVER['QUERY_STRING'], '_redirected') === FALSE
) {
    header('location: ' . $_SERVER['REQUEST_URI'] . '&_redirected=1');
    die;
}

require __DIR__ . '/PathInfo.php';
if (!isset($_SESSION['inCart'])) {
    $_SESSION['inCart'] = 0;
}
if (strpos(\RichardEjem\PathInfo::getPathInfo(), 'add-stuff-to-cart') !== FALSE) {
    $_SESSION['inCart']++;
}

Tracy\Debugger::enable(Tracy\Debugger::DEVELOPMENT);
echo 'breakpoints now work.<br>';


?>
TRACY UP AND RUNNING.<br>
<p></p><a href="<?= htmlspecialchars(\RichardEjem\PathInfo::getBasePath()) ?>/add-stuff-to-cart">Click me to add one
    item to cart</a></p>
<p>Stuff in cart: <?= $_SESSION['inCart'] ?></p>

