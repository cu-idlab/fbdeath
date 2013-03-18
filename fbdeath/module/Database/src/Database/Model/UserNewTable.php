<?php
namespace Database\Model;

use Zend\Db\TableGateway\TableGateway;

/**
 * Array
 */
class UserNewTable
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

    public function getuser_new($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
        	throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveUser_new(User_new $user_new)
    {
        $data = array(
        	'id'      	=> $user_new->id,
        	'fb_user_id'      	=> $user_new->fb_user_id,
        	'fb_token'      	=> $user_new->fb_token,
        	'fb_token_exp'      	=> $user_new->fb_token_exp,
        	'fb_last_update'      	=> $user_new->fb_last_update,
        	'added'      	=> $user_new->added,
        );
        $id = (int) $user_new->id;
        if ($id == 0) {
        	$this->tableGateway->insert($data);
        } else {
        	if ($this->getuser_new($id)) {
        		$this->tableGateway->update($data, array('id' => $id));
        	} else {
        		throw new \Exception('id does not exist');
        	}
        }
    }

    public function deleteUser_new($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }


}
