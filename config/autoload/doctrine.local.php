<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'user' => 'root',
                    'password' => '',
                    'dbname' => 'blank',
                    'driverOptions' => array(
                        1002 => 'SET NAMES UTF8'
                    )
                )
            )
        ),

        'driver' => array(
            'entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../../doctrine/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Entity' => 'entities'
                )
            )
        ),
        'orm' => array(
            'auto_mapping' => true
        ),
        'configuration' => array(
            'orm_default' => array(
                /*
                 * Config function in doctrine extension
                 */
                'string_functions'   => array(
                    'MATCH' => 'DoctrineExtensions\Query\Mysql\MatchAgainst',
                ),
                'numeric_functions' => array(
                    'RAND' => 'DoctrineExtensions\Query\Mysql\Rand',
                ),
                'datetime_functions' => array(
                    'DAY' => 'DoctrineExtensions\Query\Mysql\Day',
                    'MONTH' => 'DoctrineExtensions\Query\Mysql\Month',
                    'YEAR' => 'DoctrineExtensions\Query\Mysql\Year',
                    'DATE_FORMAT' => 'DoctrineExtensions\Query\Mysql\DateFormat',
                ),
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Entity\StUser',
                'identity_property' => 'username',
                'credential_property' => 'password',
                'credential_callable' => function(\Entity\StUser $user, $passwordGiven) {
                    return 
                        $user->getStatus() == \Application\Config\Config::STATUS_ACTIVE
                        && $user->getPassword() == \ST\Text::generatePassword($passwordGiven, $user->getDatetimeCreated()->getTimestamp());
                },
            ),
        ),
    ),
);
