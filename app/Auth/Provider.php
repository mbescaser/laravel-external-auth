<?php

namespace App\Auth;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

use App\Auth\Model;

class Provider implements UserProvider {

    private $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function retrieveByCredentials(array $credentials) {
        // Get and return a user by looking up the given credentials
        if($credentials) {
            $model = $this->model->fetchUserByCredentials($credentials);
            if($model) {
                return $model;
            }
        }
        return;
    }

    public function validateCredentials(Authenticatable $model, array $credentials) {
        // Check that given credentials belong to the given user
        return $credentials['email'] == $model->getAuthEmail() && md5($credentials['password']) == $model->getAuthPassword();
    }

    public function retrieveById($identifier) {
        // Get and return a user by their unique identifier
        if($identifier) {
            $model = $this->model->fetchUserById($identifier);
            if($model) {
                return $model;
            }
        }
        return;
    }

    public function retrieveByToken($identifier, $token) {
        // Get and return a user by their unique identifier and "remember me" token
    }

    public function updateRememberToken(Authenticatable $user, $token) {
        // Save the given "remember me" token for the given user
    }

}
