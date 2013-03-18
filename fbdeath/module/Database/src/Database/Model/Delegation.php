<?php
namespace Database\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Array
 */
class Delegation implements InputFilterAwareInterface
{

    protected $id = 0;

    protected $providerUserId = 0;

    protected $recipientUserId = 0;

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setProviderUserId($value)
    {
        $this->providerUserId = $value;
        return $this;
    }

    public function getProviderUserId()
    {
        return $this->providerUserId;
    }

    public function setRecipientUserId($value)
    {
        $this->recipientUserId = $value;
        return $this;
    }

    public function getRecipientUserId()
    {
        return $this->recipientUserId;
    }

    public function exhcangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->providerUserId = (isset($data['providerUserId'])) ? $data['providerUserId'] : null;
        $this->recipientUserId = (isset($data['recipientUserId'])) ? $data['recipientUserId'] : null;
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
        $data['providerUserId'] = $this->providerUserId;
        $data['recipientUserId'] = $this->recipientUserId;
        return $data;
    }


}
