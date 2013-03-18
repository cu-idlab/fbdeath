<?php
namespace Database\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Array
 */
class RelationshipTable
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

    public function getrelationship($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
        	throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveRelationship(Relationship $relationship)
    {
        $data = array(
        	'id'      	=> $relationship->id,
        	'fb_user_id1'      	=> $relationship->fb_user_id1,
        	'fb_user_id2'      	=> $relationship->fb_user_id2,
        );
        $id = (int) $relationship->id;
        if ($id == 0) {
        	$this->tableGateway->insert($data);
        } else {
        	if ($this->getrelationship($id)) {
        		$this->tableGateway->update($data, array('id' => $id));
        	} else {
        		throw new \Exception('id does not exist');
        	}
        }
    }

    public function deleteRelationship($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }


}
