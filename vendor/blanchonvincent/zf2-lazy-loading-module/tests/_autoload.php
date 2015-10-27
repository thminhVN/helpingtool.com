<?php

require_once __DIR__ . '/../../zf2-fork/library/Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true,
        'namespaces' => array(
            'ZFMLL' => __DIR__ . '/../ZFMLL',
            'ZFMLLTest' => __DIR__ . '/ZFMLLTest',
        ),
    ),
));