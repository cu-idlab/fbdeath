<?php
namespace Database\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Array
 */
class User implements InputFilterAwareInterface
{

    protected $userId = 0;

    protected $username = '';

    protected $email = '';

    protected $displayName = '';

    protected $password = '';

    protected $state = 0;

    public function setUserId($value)
    {
        $this->userId = $value;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUsername($value)
    {
        $this->username = $value;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setEmail($value)
    {
        $this->email = $value;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setDisplayName($value)
    {
        $this->displayName = $value;
        return $this;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setPassword($value)
    {
        $this->password = $value;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setState($value)
    {
        $this->state = $value;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function exhcangeArray($data)
    {
        $this->userId = (isset($data['userId'])) ? $data['userId'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->displayName = (isset($data['displayName'])) ? $data['displayName'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
        $this->state = (isset($data['state'])) ? $data['state'] : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterace $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function toArray()
    {
        $data = array();
        $data['userId'] = $this->userId;
        $data['username'] = $this->username;
        $data['email'] = $this->email;
        $data['displayName'] = $this->displayName;
        $data['password'] = $this->password;
        $data['state'] = $this->state;
        return $data;
    }


}
