<?php

/**
 * authenticate user
 */
class AuthRepository
{
    public function loginAttempt($username, $password)
    {
        $hashPassword = $this->hashPassword($password);

        $user = User::where(function($query) use ($username, $hashPassword) {
                            $query->where('username', $username)
                                ->orWhere('email', $username);
                      })
                      ->where('password', $this->hashPassword($password))
                      ->take(1)
                      ->first();

        if ($user != null) {
            $this->login($user);

            return true;
        }

        return false;
    }

    public function logout()
    {
        $app = \Slim\Slim::getInstance();

        $app->deleteCookie('user');
        $app->deleteCookie('hash');
    }

    public function hashPassword($password)
    {
        return sha1($password . AUTH_SALT);
    }

    private function login(User $user)
    {
        $app = \Slim\Slim::getInstance();

        $userData = [
            'id'       => $user->id,
            'username' => $user->username,
            'staffNo'  => $user->staff_no,
            'role'     => $user->role,
        ];
        $hash = $this->hashSession($userData);

        $app->setEncryptedCookie('user', json_encode($userData), AUTH_EXPIRY);
        $app->setEncryptedCookie('hash', $hash, AUTH_EXPIRY);
    }

    public function hashSession($userData)
    {
        return md5(AUTH_SESSION_SALT . implode('|', $userData));
    }

    public function isLoggedIn()
    {
        $app = \Slim\Slim::getInstance();

        $userData = json_decode($app->getEncryptedCookie('user'), true);
        $hash     = $app->getEncryptedCookie('hash');

        if (!empty($userData) && !empty($hash)) {
            return $hash === $this->hashSession($userData);
        }

        return false;
    }

    public function getCurrentUser()
    {
        $app = \Slim\Slim::getInstance();

        return json_decode($app->getEncryptedCookie('user'), true);
    }
}
