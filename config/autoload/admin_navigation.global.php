<?php
/**
 * Global Configuration
 *
 * This configuration list all menu in backend
 */
return array(
    // All navigation-related configuration is collected in the 'navigation' key
    'navigation' => array(
        'admin' => array(
            array(
                'label' => 'Dashboard',
                'ulClass' => 'nav nav-second-level collapse',
                'extended' => array(
                    'before' => '<i class="fa fa-bar-chart-o fa-fw"></i> ',
                ),
                'route' => 'admin'
            ),
            array(
                'label' => 'User',
                'uri' => '#',
                'ulClass' => 'nav nav-second-level collapse',
                'resource' => 'admin:user',
                'extended' => array(
                    'before' => '<i class="fa fa-user fa-fw"></i> ',
                    'after' => ' <span class="fa arrow"></span>',
                ),
                'pages' => array(
                    array(
                        'route' => 'admin/default',
                        'controller' => 'user',
                        'label' => 'List',
                        'action' => 'index',
                        'resource' => 'admin:user',
                        'privillege' => 'index',
                    ),
                    array(
                        'route' => 'admin/default',
                        'controller' => 'user',
                        'action' => 'add',
                        'label' => 'Add',
                        'resource' => 'admin:user',
                        'privillege' => 'add',
                    )
                )
            ),
            array(
                'label' => 'Language',
                'uri' => '#',
                'ulClass' => 'nav nav-second-level collapse',
                'resource' => 'admin:language',
                'extended' => array(
                    'before' => '<i class="fa fa-language"></i> ',
                    'after' => ' <span class="fa arrow"></span>',
                ),
                'pages' => array(
                    array(
                        'route' => 'admin/default',
                        'controller' => 'language',
                        'label' => 'List',
                        'action' => 'index',
                        'resource' => 'admin:language',
                        'privillege' => 'index',
                    ),
                    array(
                        'route' => 'admin/default',
                        'controller' => 'language',
                        'action' => 'add',
                        'label' => 'Add',
                        'resource' => 'admin:language',
                        'privillege' => 'add',
                    )
                )
            ),
            array(
                'label' => 'Category',
                'uri' => '#',
                'ulClass' => 'nav nav-second-level collapse',
                'resource' => 'admin:category',
                'extended' => array(
                    'before' => '<i class="glyphicon glyphicon-menu-hamburger"></i> ',
                    'after' => ' <span class="fa arrow"></span>',
                ),
                'pages' => array(
                    array(
                        'route' => 'admin/default',
                        'controller' => 'category',
                        'label' => 'List',
                        'action' => 'index',
                        'resource' => 'admin:category',
                        'privillege' => 'index',
                    ),
                    array(
                        'route' => 'admin/default',
                        'controller' => 'category',
                        'action' => 'add',
                        'label' => 'Add',
                        'resource' => 'admin:category',
                        'privillege' => 'add',
                    )
                )
            ),
            array(
                'label' => 'Tag',
                'uri' => '#',
                'ulClass' => 'nav nav-second-level collapse',
                'resource' => 'admin:tag',
                'extended' => array(
                    'before' => '<i class="glyphicon glyphicon-tags"></i> ',
                    'after' => ' <span class="fa arrow"></span>',
                ),
                'pages' => array(
                    array(
                        'route' => 'admin/default',
                        'controller' => 'tag',
                        'label' => 'List',
                        'action' => 'index',
                        'resource' => 'admin:tag',
                        'privillege' => 'index',
                    ),
                    array(
                        'route' => 'admin/default',
                        'controller' => 'tag',
                        'action' => 'add',
                        'label' => 'Add',
                        'resource' => 'admin:tag',
                        'privillege' => 'add',
                    )
                )
            ),
            array(
                'label' => 'Page',
                'uri' => '#',
                'ulClass' => 'nav nav-second-level collapse',
                'resource' => 'admin:page',
                'extended' => array(
                    'before' => '<i class="fa fa-file-o"></i> ',
                    'after' => ' <span class="fa arrow"></span>',
                ),
                'pages' => array(
                    array(
                        'route' => 'admin/default',
                        'controller' => 'page',
                        'label' => 'List',
                        'action' => 'index',
                        'resource' => 'admin:page',
                        'privillege' => 'index',
                    ),
                    array(
                        'route' => 'admin/default',
                        'controller' => 'page',
                        'action' => 'add',
                        'label' => 'Add',
                        'resource' => 'admin:page',
                        'privillege' => 'add',
                    )
                )
            ),
            array(
                'label' => 'Media',
                'route' => 'admin/default',
                'controller' => 'media',
                'ulClass' => 'nav nav-second-level collapse',
                'extended' => array(
                    'before' => '<i class="glyphicon glyphicon-picture"></i> ',
                ),
            ),
        )
    )
);