<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Media' => 'Admin\Controller\MediaController',
            'Admin\Controller\User' => 'Admin\Controller\UserController',
            'Admin\Controller\Language' => 'Admin\Controller\LanguageController',
            'Admin\Controller\Category' => 'Admin\Controller\CategoryController',
            'Admin\Controller\Tag' => 'Admin\Controller\TagController',
            'Admin\Controller\Page' => 'Admin\Controller\PageController',
        )
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route' => '/admin',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action][/:id]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'admin/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'admin/empty' => __DIR__ . '/../view/layout/empty.phtml'
        ),
        'template_path_stack' => array(
            'Admin' => __DIR__ . '/../view'
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'AdminPlugin' => 'Admin\Controller\Plugin\AdminPlugin',
        )
    ),
);
