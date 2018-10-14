<?php

class UserRepository extends BaseRepository
{
    /**
     * @param $userId
     * @param $username
     * @param $email
     * @return bool|string Return false if not duplicate; return field name if duplicate
     */
    public function isUserInfoDuplicate($userId, $username, $email)
    {
        $username = strtolower(trim($username));
        $email = strtolower(trim($email));

        $query = User::where(function($query) use ($username, $email)
        {
            $query->where('username', $username)->orWhere('email', $email);
        });

        if ($userId) {
            $query = $query->where('id', '!=', $userId);
        }

        $user = $query->first();

        if ($user != null) {
            if ($user->username == $username) {
                return $username;
            } else if ($user->email == $email) {
                return $email;
            }
        }

        return false;
    }

    public function addUser(array $userInfo)
    {
        $app = \Slim\Slim::getInstance();

        $authRepo = $app->authRepository; /** @var AuthRepository $authRepo */
        $dataValidatorRepo = $app->dataValidatorRepository; /** @var DataValidatorRepository $dataValidatorRepo */

        // trim & lowercase all
        foreach ($userInfo as $key => $value) {
            $userInfo[$key] = trim($value);

            if ($key !== 'password') {
                $userInfo[$key] = strtolower($userInfo[$key]);
            }
        }

        $dataValidatorRepo->validateNewUserInformation($userInfo);

        if ($duplicate = $this->isUserInfoDuplicate(null, $userInfo['username'], $userInfo['email'])) {
            throw new \DataValidatorException\DuplicateDataException($duplicate);
        }
        $encryptedPwd = $authRepo->hashPassword($userInfo['password']);

        $user = new User;

        $user->username = $userInfo['username'];
        $user->email = $userInfo['email'];
        $user->password = $encryptedPwd;
        $user->role = $userInfo['role'];

        $user->save();

        return true;
    }

    public function changePassword($userId, $newPassword)
    {
        $authRepo = \Slim\Slim::getInstance()->authRepository; /** @var AuthRepository $authRepo */

        $currentUser = $authRepo->getCurrentUser();

        // if not specific user_id or if not admin; change their own password
        if (null == $userId || $currentUser['role'] != User::ROLE_ADMIN) {
            $userId = $currentUser['id'];
        }

        $user = User::findOrFail($userId);

        $user->password = $authRepo->hashPassword($newPassword);
        $user->save();

        return true;
    }

    public function getUsers($page = 1, $limit = 50)
    {
        return User::skip(($page - 1) * $limit)->take($limit)->get(['id', 'username', 'email', 'role']);
    }
}
