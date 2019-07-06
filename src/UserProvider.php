<?php

namespace NextgenSolution\MyCardIDSDK;

use Illuminate\Contracts\Auth\UserProvider as BaseUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Exception;

class UserProvider implements BaseUserProvider
{
    /**
     * {@inheritdoc}
     */
    public function retrieveById($identifier)
    {
        $service = app(Service::class);
        $user = $service->viewProfile($identifier)['data'];
        $user['token'] = $identifier;

        return (new User())->replace($user);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByToken($identifier, $token)
    {
        // throw new Exception('Unimplemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // throw new Exception('Unimplemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByCredentials(array $credentials)
    {
        // throw new Exception('Unimplemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // throw new Exception('Unimplemented.');
    }
}
