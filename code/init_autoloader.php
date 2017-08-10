<?php
if (file_exists('vender/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}

if (class_exists('Zend\Loader\AutoloaderFactory')) {
    return;
}

$zf2Path = false;

if (getenv('ZF2_PATH')) {
    $zf2Path = getenv('ZF2_PATH');
} else if (get_cfg_var('zf2_path')) {
    $zf2Path = get_cfg_var('zf2_path');
}

if ($zf2Path) {
    if (isset($loader)) {
        $laoder->add('Zend', $zf2Path);
        $loader->add('ZendXml', $zf2Path);
    } else {
        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory([
            'Zend\Loader\StandardAutoLoader' => [
                'autoregister_zf' => true
            ]
        ]);
    }
}

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2');
}
