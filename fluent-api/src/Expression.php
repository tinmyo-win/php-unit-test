<?php

class Expression
{
    protected $expression = '';

    public static function make()
    {
        return new static;
    }

    public function find($value)
    {
        return $this->add($this->sanitize($value));
    }

    public function then($value)
    {
        return $this->find($value);
    }

    public function anything()
    {
        return $this->add('.*');
    }

    public function anythingBut($value)
    {
        $value = $this->sanitize($value);

        $this->expression .= '^((?!' . $value . ').)*$';

        var_dump($this->getRegx());

        return $this;
    }

    public function maybe($value)
    {
        $value = $this->sanitize($value);

        return $this->add('(?:' . $value . ')?');
    }

    protected function add($value)
    {
        $this->expression = $value;

        return $this;
    }

    protected function sanitize($value)
    {
        return preg_quote($value, '/');
    }

    public function test($value)
    {
        return (bool) preg_match($this->getRegx(), $value);
    }

    public function  getRegx()
    {
        return '/' . $this->expression . '/';
    }

    public function __toString()
    {
        return $this->getRegx();
    }
}
