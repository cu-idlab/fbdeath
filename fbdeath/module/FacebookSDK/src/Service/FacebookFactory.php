<?php

namespace FacebookSDK\Service;

use InvalidArgumentException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Facebook;

class FacebookFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $config = $config['facebook'];

        if (!($config['config']['appId'] && $config['config']['secret'])) {
        	throw new InvalidArgumentException('Facebook configuration data \'appId\' and \'secret\' must bedefined in config');
        }
        //var_dump($serviceLocator->getRegisteredServices());exit;
        $facebook = new Facebook($config['config']);
        
        if($config['config']['setAppIdInHeadScript'])
        {
        	$script = sprintf('var FB_APP_ID = "%s";', $config['config']['appId']);
        	/** @var $view Zend\View\View */
        	$view = $serviceLocator->get('ViewRenderer');
        	$headScript = $view->plugin('HeadScript')->prependScript($script);
        }

        return $facebook;
    }
}