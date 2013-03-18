<?php
namespace Database\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Array
 */
class Album implements InputFilterAwareInterface
{

    protected $id = 0;

    protected $artist = '';

    protected $title = '';

    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setArtist($value)
    {
        $this->artist = $value;
        return $this;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function exhcangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->artist = (isset($data['artist'])) ? $data['artist'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
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
        $data['artist'] = $this->artist;
        $data['title'] = $this->title;
        return $data;
    }


}
