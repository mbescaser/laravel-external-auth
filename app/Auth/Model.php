<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Auth\Service;

use App\Models\User;

class Model implements Authenticatable {

    private $service;
    private $userId;
    private $email;
    private $password;

    protected $rememberTokenName = 'remember_token';

    public function __construct(Service $service) {
        $this->service = $service;
    }

    public function fetchUserByCredentials(array $credentials) {
        if($credentials) {
            $user = $this->service->loginUser($credentials);
            if($user) {
                $user->timeLoggedIn = time();
                $this->userId = $user->userId;
                $this->email = $credentials['email'];
                $this->password = md5($credentials['password']);
                session()->put($this->getAuthIdentifier(), (object) ['auth' => $user]);
                return $this;
            }
        }
        return null;
    }

    public function fetchUserById($identifier) {
        if($identifier) {
            $fromSession = session()->get($identifier);
            if($fromSession && $fromSession->auth) {
                $currentTimestamp = time();
                if(!(($currentTimestamp - $fromSession->auth->timeLoggedIn) > 7200)) {
                    $fromSession->auth->timeLoggedIn = $currentTimestamp;
                    $user = $this->service->getUser($fromSession->auth);
                    if($user) {
                        $class = new User();
                        foreach($user as $key => $value) {
                            $class->{$key} = $value;
                        }
                        session()->put($identifier, $fromSession);
                        return $class;
                    }
                }
                session()->forget($identifier);
            }
        }
        return null;
    }

    public function getAuthIdentifierName() {
        // Return the name of unique identifier for the user (e.g. "id")
        return 'userId';
    }

    public function getAuthIdentifier() {
        // Return the unique identifier for the user (e.g. their ID, 123)
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthEmail() {
        return $this->email;
    }

    public function getAuthPassword() {
        // Returns the (hashed) password for the user
        return $this->password;
    }

    public function getRememberToken() {
        // Return the token used for the "remember me" functionality
        if (!empty($this->getRememberTokenName())) {
          return $this->{$this->getRememberTokenName()};
        }
    }

    public function setRememberToken($value) {
        // Store a new token user for the "remember me" functionality
        if (!empty($this->getRememberTokenName())) {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    public function getRememberTokenName() {
        // Return the name of the column / attribute used to store the "remember me" token
        return $this->rememberTokenName;
    }
}
