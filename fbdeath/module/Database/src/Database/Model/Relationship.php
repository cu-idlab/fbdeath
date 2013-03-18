<?php
namespace Database\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Array
 */
class Relationship implements InputFilterAwareInterface
{

    protected $id = 0;

    protected $fbUserId1 = 0;

    protected $fbUserId2 = 0;

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFbUserId1($value)
    {
        $this->fbUserId1 = $value;
        return $this;
    }

    public function getFbUserId1()
    {
        return $this->fbUserId1;
    }

    public function setFbUserId2($value)
    {
        $this->fbUserId2 = $value;
        return $this;
    }

    public function getFbUserId2()
    {
        return $this->fbUserId2;
    }

    public function exhcangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->fbUserId1 = (isset($data['fbUserId1'])) ? $data['fbUserId1'] : null;
        $this->fbUserId2 = (isset($data['fbUserId2'])) ? $data['fbUserId2'] : null;
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
        $data['fbUserId1'] = $this->fbUserId1;
        $data['fbUserId2'] = $this->fbUserId2;
        return $data;
    }


}
