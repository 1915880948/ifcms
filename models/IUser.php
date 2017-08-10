<?php

namespace app\models;

use Yii;

class IUser extends \yii\base\Object implements \yii\web\IdentityInterface {
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    public $status;
    public $create_time;

    private static $users = [

    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($userid) {
        $sql = 'select * from admin where status=1 and id=:id';
        $admin = Yii::$app->db->createCommand($sql)->bindValue(':id', $userid)->queryOne();
        return new static($admin);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        $sql = 'select id,username,password from admin where status=1 and username=:name';
        $admin = Yii::$app->db->createCommand($sql)->bindValue(':name', $username)->queryOne();
        return new static($admin);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->password === $password;
    }
}
