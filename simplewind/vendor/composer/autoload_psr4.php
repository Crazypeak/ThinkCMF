<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname(dirname($vendorDir));

return array(
    'think\\helper\\' => array($vendorDir . '/topthink/think-helper/src'),
    'think\\composer\\' => array($vendorDir . '/topthink/think-installer/src'),
    'think\\captcha\\' => array($vendorDir . '/topthink/think-captcha/src'),
    'think\\' => array($baseDir . '/simplewind/thinkphp/library/think', $vendorDir . '/topthink/think-image/src', $vendorDir . '/topthink/think-queue/src'),
    'mindplay\\annotations\\' => array($vendorDir . '/mindplay/annotations/src/annotations'),
    'Symfony\\Component\\Finder\\' => array($vendorDir . '/symfony/finder'),
    'Swagger\\' => array($vendorDir . '/zircote/swagger-php/src'),
    'Qiniu\\' => array($vendorDir . '/qiniu/php-sdk/src/Qiniu'),
    'FontLib\\' => array($vendorDir . '/phenx/php-font-lib/src/FontLib'),
    'Dompdf\\' => array($vendorDir . '/dompdf/dompdf/src'),
    'Doctrine\\Common\\Annotations\\' => array($vendorDir . '/doctrine/annotations/lib/Doctrine/Common/Annotations'),
);