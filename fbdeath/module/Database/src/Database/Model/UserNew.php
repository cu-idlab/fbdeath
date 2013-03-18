<?php
namespace Database\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Array
 */
class UserNew implements InputFilterAwareInterface
{

    protected $id = 0;

    protected $fbUserId = 0;

    protected $fbToken = '';

    protected $fbTokenExp = '';

    protected $fbLastUpdate = '';

    protected $added = '';

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFbUserId($value)
    {
        $this->fbUserId = $value;
        return $this;
    }

    public function getFbUserId()
    {
        return $this->fbUserId;
    }

    public function setFbToken($value)
    {
        $this->fbToken = $value;
        return $this;
    }

    public function getFbToken()
    {
        return $this->fbToken;
    }

    public function setFbTokenExp($value)
    {
        $this->fbTokenExp = $value;
        return $this;
    }

    public function getFbTokenExp()
    {
        return $this->fbTokenExp;
    }

    public function setFbLastUpdate($value)
    {
        $this->fbLastUpdate = $value;
        return $this;
    }

    public function getFbLastUpdate()
    {
        return $this->fbLastUpdate;
    }

    public function setAdded($value)
    {
        $this->added = $value;
        return $this;
    }

    public function getAdded()
    {
        return $this->added;
    }

    public function exhcangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->fbUserId = (isset($data['fbUserId'])) ? $data['fbUserId'] : null;
        $this->fbToken = (isset($data['fbToken'])) ? $data['fbToken'] : null;
        $this->fbTokenExp = (isset($data['fbTokenExp'])) ? $data['fbTokenExp'] : null;
        $this->fbLastUpdate = (isset($data['fbLastUpdate'])) ? $data['fbLastUpdate'] : null;
        $this->added = (isset($data['added'])) ? $data['added'] : null;
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
        $data['id'] = $this->id;
        $data['fbUserId'] = $this->fbUserId;
        $data['fbToken'] = $this->fbToken;
        $data['fbTokenExp'] = $this->fbTokenExp;
        $data['fbLastUpdate'] = $this->fbLastUpdate;
        $data['added'] = $this->added;
        return $data;
    }


}
