<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Auth' => 'Application\Controller\AuthController',
            'Application\Controller\Image' => 'Application\Controller\ImageController',
            'Application\Controller\Page' => 'Application\Controller\PageController',
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
            'Zend\Navigation\Service\NavigationAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator'
        ),
    ),

    'controller_plugins' => array (
        'invokables' => array (
            'UtilityPlugin' => 'Application\Controller\Plugin\UtilityPlugin',
            'MailPlugin' => 'Application\Controller\Plugin\MailPlugin',
        )
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/auth' => __DIR__ . '/../view/layout/auth.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    
    'view_helpers' => array(
        'invokables' => array(
            'utility' => 'Application\View\Helper\Utility',
            'stnavigation' => 'Application\View\Helper\Navigation',
            'image' => 'Application\View\Helper\Image',
            'lang' => 'Application\View\Helper\Lang',
        )
    ),
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array()
        )
    ),
);
