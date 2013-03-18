<?php
namespace Database\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Array
 */
class FbUserTable
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

    public function getfb_user($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
        	throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveFb_user(Fb_user $fb_user)
    {
        $data = array(
        	'id'      	=> $fb_user->id,
        	'fb_id'      	=> $fb_user->fb_id,
        );
        $id = (int) $fb_user->id;
        if ($id == 0) {
        	$this->tableGateway->insert($data);
        } else {
        	if ($this->getfb_user($id)) {
        		$this->tableGateway->update($data, array('id' => $id));
        	} else {
        		throw new \Exception('id does not exist');
        	}
        }
    }

    public function deleteFb_user($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }


}
