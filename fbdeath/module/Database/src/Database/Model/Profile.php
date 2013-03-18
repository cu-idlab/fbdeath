<?php
namespace Database\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Array
 */
class Profile implements InputFilterAwareInterface
{

    protected $id = 0;

    protected $userId = '';

    protected $attrName = '';

    protected $attrValue = '';

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

    public function setUserId($value)
    {
        $this->userId = $value;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setAttrName($value)
    {
        $this->attrName = $value;
        return $this;
    }

    public function getAttrName()
    {
        return $this->attrName;
    }

    public function setAttrValue($value)
    {
        $this->attrValue = $value;
        return $this;
    }

    public function getAttrValue()
    {
        return $this->attrValue;
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
        $this->userId = (isset($data['userId'])) ? $data['userId'] : null;
        $this->attrName = (isset($data['attrName'])) ? $data['attrName'] : null;
        $this->attrValue = (isset($data['attrValue'])) ? $data['attrValue'] : null;
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
        $data['userId'] = $this->userId;
        $data['attrName'] = $this->attrName;
        $data['attrValue'] = $this->attrValue;
        $data['added'] = $this->added;
        return $data;
    }


}
