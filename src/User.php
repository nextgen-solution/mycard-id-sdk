<?php

namespace MyCard\ID;

use Illuminate\Contracts\Auth\Authenticatable;
use Exception;

class User implements Authenticatable
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function replace(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthIdentifierName()
    {
        return 'token';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthPassword()
    {
        throw new Exception('Unimplemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getRememberToken()
    {
        throw new Exception('Unimplemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function setRememberToken($value)
    {
        throw new Exception('Unimplemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getRememberTokenName()
    {
        throw new Exception('Unimplemented.');
    }
}