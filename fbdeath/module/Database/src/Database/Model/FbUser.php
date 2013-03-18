<?php
namespace Database\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Array
 */
class FbUser implements InputFilterAwareInterface
{

    protected $id = 0;

    protected $fbId = '';

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFbId($value)
    {
        $this->fbId = $value;
        return $this;
    }

    public function getFbId()
    {
        return $this->fbId;
    }

    public function exhcangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->fbId = (isset($data['fbId'])) ? $data['fbId'] : null;
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
        $data['fbId'] = $this->fbId;
        return $data;
    }


}
