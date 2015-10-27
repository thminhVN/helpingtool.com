<?php
/*
 * Config for cookie session
 */
return array(
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'user',
                'cookie_lifetime' => 604800, // one week
                'remember_me_seconds' => 604800, // one week
                'gc_maxlifetime' => 604800,
                'use_cookies' => TRUE
            )
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            array(
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent'
            )
        )
    )
);
