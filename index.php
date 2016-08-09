<?php
require __DIR__ . '/vendor/autoload.php';

if (strpos($_SERVER['QUERY_STRING'], '_tracy_bar') !== FALSE
    && strpos($_SERVER['QUERY_STRING'], '_redirected') === FALSE
) {
    header('location: ' . $_SERVER['REQUEST_URI'] . '&_redirected=1');
    die;
}
Tracy\Debugger::enable(Tracy\Debugger::DEVELOPMENT);
echo 'try to set breakpoint here.<br>';
?>
TRACY UP AND RUNNING.<br>
<a href="./">Click me to break xdebug</a>
