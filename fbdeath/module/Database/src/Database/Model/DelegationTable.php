<?php
namespace Database\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Array
 */
class DelegationTable
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

    public function getdelegation($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
        	throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveDelegation(Delegation $delegation)
    {
        $data = array(
        	'id'      	=> $delegation->id,
        	'provider_user_id'      	=> $delegation->provider_user_id,
        	'recipient_user_id'      	=> $delegation->recipient_user_id,
        );
        $id = (int) $delegation->id;
        if ($id == 0) {
        	$this->tableGateway->insert($data);
        } else {
        	if ($this->getdelegation($id)) {
        		$this->tableGateway->update($data, array('id' => $id));
        	} else {
        		throw new \Exception('id does not exist');
        	}
        }
    }

    public function deleteDelegation($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }


}
