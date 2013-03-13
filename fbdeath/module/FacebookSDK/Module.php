<?php

namespace Zf2mFacebook;

use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\StaticEventManager;
use Zend\EventManager\EventInterface as Event;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ServiceProviderInterface
{
	public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    
    public function getServiceConfig()
    {
    	return array(
    		'aliases' => array(),
    		'factories' => array(
    			'FacebookService'  => 'Zf2mFacebook\Service\FacebookFactory',
    		),
    	);
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/configs/module.config.php';
    }
}
