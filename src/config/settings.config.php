<?php
function selfAutoLoad($pClassName) {
    $path = $_SERVER['DOCUMENT_ROOT'] . "/espaco-crianca/src/" . strtolower($pClassName) . '.php';
    if (is_file($path)) {
        include_once $path;
    }
}
spl_autoload_register('selfAutoLoad');
?>

