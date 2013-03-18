<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Database\Controller\Database' => 'Database\Controller\DatabaseController',
        ),
    ),
        
        'router' => array(
        		'routes' => array(
        				'album' => array(
        						'type'    => 'segment',
        						'options' => array(
        								'route'    => '/database[/:action][/:id]',
        								'constraints' => array(
        										'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        										'id'     => '[0-9]+',
        								),
        								'defaults' => array(
        										'controller' => 'Database\Controller\Database',
        										'action'     => 'index',
        								),
        						),
        				),
        		),
        ),        
        
    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
);