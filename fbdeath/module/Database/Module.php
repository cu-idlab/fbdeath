<?php
namespace Database;

use Database\Model\Database;
use Database\Model\DatabaseTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{

    public function getAutoloaderConfig ()
    {
        return array(
                'Zend\Loader\ClassMapAutoloader' => array(
                        __DIR__ . '/autoload_classmap.php'), 
                'Zend\Loader\StandardAutoloader' => array(
                        'namespaces' => array(
                                __NAMESPACE__ => __DIR__ . '/src/' .
                                         __NAMESPACE__)));
    }

    public function getConfig ()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig ()
    {
        return array(
                'factories' => array(
                        'Database\Model\DatabaseTable' => function  ($sm)
                        {
                            $tableGateway = $sm->get('DatabaseTableGateway');
                            $table = new DatabaseTable($tableGateway);
                            return $table;
                        }, 
                        'DatabaseTableGateway' => function  ($sm)
                        {
                            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                            $resultSetPrototype = new ResultSet();
                            $resultSetPrototype->setArrayObjectPrototype(
                                    new Database());
                            return new TableGateway('database', $dbAdapter, null, 
                                    $resultSetPrototype);
                        }));
    }
}