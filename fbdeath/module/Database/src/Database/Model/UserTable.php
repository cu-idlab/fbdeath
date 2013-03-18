<?php
namespace Database\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Array
 */
class UserTable
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

    public function getuser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
        	throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveUser(User $user)
    {
        $data = array(
        	'user_id'      	=> $user->user_id,
        	'username'      	=> $user->username,
        	'email'      	=> $user->email,
        	'display_name'      	=> $user->display_name,
        	'password'      	=> $user->password,
        	'state'      	=> $user->state,
        );
        $id = (int) $user->id;
        if ($id == 0) {
        	$this->tableGateway->insert($data);
        } else {
        	if ($this->getuser($id)) {
        		$this->tableGateway->update($data, array('id' => $id));
        	} else {
        		throw new \Exception('id does not exist');
        	}
        }
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }


}
