<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

\Slim\Slim::getInstance()->DB;

class Model extends Eloquent
{
    public $timestamps = true;

    public static $trackUser = false;

    public static function boot()
    {
        parent::boot();

        if (true == static::$trackUser) {
            static::creating(function($model)
            {
                $authRepo = \Slim\Slim::getInstance()->authRepository; /** @var AuthRepository $authRepo */
                if (!$authRepo->isLoggedIn()) {
                    throw new \Exception('Not logged in');
                }
                $user = $authRepo->getCurrentUser();

                $model->attributes['created_by'] = $user['id'];
                $model->attributes['updated_by'] = $user['id'];
            });

            static::updating(function($model)
            {
                $authRepo = \Slim\Slim::getInstance()->authRepository; /** @var AuthRepository $authRepo */
                if (!$authRepo->isLoggedIn()) {
                    throw new \Exception('Not logged in');
                }
                $user = $authRepo->getCurrentUser();

                $model->attributes['updated_by'] = $user['id'];
            });
        }
    }

    public function toArray(){
        $array = parent::toArray();
        $camelArray = [];

        foreach($array as $name => $value){
            $camelArray[camel_case($name)] = $value;
        }

        return $camelArray;
    }
}
