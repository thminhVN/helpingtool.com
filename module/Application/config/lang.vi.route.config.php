<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
$lang = 'vi';
return array(
    'router' => array(
        'routes' => array(
            $lang.'_home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/trang-chu.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                        'lang' => $lang,
                    ),
                ),
                'priority' => 1,
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            $lang.'_application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => "/$lang/",
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'lang' => $lang,
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[:controller[/:action][.html]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array()
                        )
                    ),
                    'page-list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => 'trang/:page[.html]',
                            'constraints' => array(
                                'page' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'page',
                                'action' => 'index',
                                'page' => 1
                            )
                        )
                    ),
                    'page-detail' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => 'trang/[:title]-:id[.html]',
                            'constraints' => array(
                                'title' => '[a-zA-Z0-9-]+',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'page',
                                'action' => 'detail',
                            )
                        )
                    ),
                )
            )
        )
    )
);
