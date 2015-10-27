<?php
/**
 * Global Configuration
 *
 * This configuration list all menu in front end
 */
return array(
    // All navigation-related configuration is collected in the 'navigation' key
    'navigation' => array(
        'default' => array(
            'homepage' => array(
                'label' => 'Home',
                'route' => '%lang%_home',
                'ulClass' => 'dropdown-menu',
                'liClass' => 'dropdown',
                'class' => 'dropdown-toggle',
                'attribute' => array(
                    'data-toggle' => 'dropdown',
                    'aria-expanded' => "false",
                ),
                'extended' => array(
                    'after' => ' <b class="caret"></b>'
                ),
                'pages' => array(
                    array(
                        'label' => 'Auth',
                        'route' => '%lang%_application/default',
                        'controller' => 'auth',
                        'action' => 'login',
                    )
                )
            )
        )
    )
);