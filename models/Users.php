<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $how_to_be_called
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $signup_date
 * @property string $last_access_date
 */
class Users extends ActiveRecord implements IdentityInterface
{
    const AVATAR_SIZE = 90;
    const AVATAR_EXT = 'png';
    const DEFAULT_USER_ID = 0;
    const DEFAULT_USERNAME = 'bikesocial';
    
    /**
     * Endereço url relativo/fixo da imagem avtar do usuário 
     * @var string $avatar
     */
    protected $avatar = '';

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'Primeiro Nome'),
            'last_name' => Yii::t('app', 'Ultimo Nome'),
            'how_to_be_called' => Yii::t('app', 'Como Ser Chamado'),
            'username' => Yii::t('app', 'Usuário'),
            'email' => Yii::t('app', 'conta de email do usuário'),
            'password' => Yii::t('app', 'password do usuário, usada na autenticação'),
            'signup_date' => Yii::t('app', 'Data Cadastro'),
            'last_access_date' => Yii::t('app', 'Data Ultimo Acesso'),
            'auth_key' => Yii::t('app', 'Chave de autenticação'),
            'access_token' => Yii::t('app', 'Token de acesso'),
            'online' =>  Yii::t('app', 'Online'),
        ];
    }
    /**
     * Executa antes de salvar (aplicar o commit) o usuário no banco de dados.
     * Se for um novo usuário cria a chave de acesso (auth_key) 
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
        
    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id){
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Returns an ID that can uniquely identify a users identity.
     * @return string|integer an ID that uniquely identifies a users identity.
     */
    public function getId(){
        return $this->id;
    }
    
    public function getfriends(){
        $friends = new \stdClass();
        $friends->total = rand(0, 99);
        return $friends;
    }
    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual users, and should be persistent
     * so that it can be used to check the validity of the users identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[Users::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey(){
         return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['signup_date', 'last_access_date'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['how_to_be_called'], 'string', 'max' => 30],
            [['online'], 'integer'],            
            [['username'], 'string', 'max' => 30],
            [['auth_key','access_token'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }
    
    /**
     * Validates the given auth key.
     *
     * This is required if [[Users::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
     public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current users
     */
    public function validatePassword($password)
    {   
        return $this->password === $password;
    }
    
    public function getavatar(){
        if(trim($this->user_avatar_src)==='')
            $this->setavatar();
        else
            $this->setavatar($this->user_avatar_src);
        return $this->avatar;
    }
    public function setavatar($src=''){
        if(trim($src)===''){
            $this->avatar = 'users/'.self::DEFAULT_USERNAME.'/images/avatar_'.self::AVATAR_SIZE.'.'.self::AVATAR_EXT;
        }else{
            $this->avatar = $src;
        }
    }
}
