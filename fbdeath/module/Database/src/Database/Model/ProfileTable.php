<?php
namespace Database\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Array
 */
class ProfileTable
{

    protected $tableGateway = null;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getprofile($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
        	throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProfile(Profile $profile)
    {
        $data = array(
        	'id'      	=> $profile->id,
        	'user_id'      	=> $profile->user_id,
        	'attr_name'      	=> $profile->attr_name,
        	'attr_value'      	=> $profile->attr_value,
        	'added'      	=> $profile->added,
        );
        $id = (int) $profile->id;
        if ($id == 0) {
        	$this->tableGateway->insert($data);
        } else {
        	if ($this->getprofile($id)) {
        		$this->tableGateway->update($data, array('id' => $id));
        	} else {
        		throw new \Exception('id does not exist');
        	}
        }
    }

    public function deleteProfile($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }


}
