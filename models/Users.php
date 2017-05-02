<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Url;
/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property string $how_to_be_called
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $signup_date
 * @property string $last_access_date
 * @property string $question
 * @property string $answer
 * @property string $home_dir_name
 */
class Users extends ActiveRecord implements IdentityInterface
{
    const AVATAR_FILE = 'avatar.png';
    const DEFAULT_USER_ID = 0;
    const DEFAULT_USERNAME = 'bikesocial';
    
    const SCENARIO_CREATE = 'create';
    
    /**
     * Endereço url relativo/fixo da imagem avtar do usuário 
     * @var string $avatar
     */
    protected $avatar = '';
    
    public $password_repeat = '';
    
    public $avatar_file;

    /**
     *
     * @var string user directory path
     */
    protected $home_dir;
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'Primeiro Nome'),
            'answer' => Yii::t('app', 'Resposta'),
            'question' => Yii::t('app', 'Pergunta secreta'),
            'last_name' => Yii::t('app', 'Ultimo Nome'),
            'last_name' => Yii::t('app', 'Nome Completo'),
            'how_to_be_called' => Yii::t('app', 'Como Ser Chamado'),
            'username' => Yii::t('app', 'Usuário'),
            'email' => Yii::t('app', 'conta de email do usuário'),
            'password' => Yii::t('app', 'senha do usuário, usada na autenticação'),
            'password_repeat' => Yii::t('app', 'Confirmação'),
            'signup_date' => Yii::t('app', 'Data Cadastro'),
            'last_access_date' => Yii::t('app', 'Data Ultimo Acesso'),
            'auth_key' => Yii::t('app', 'Chave de autenticação'),
            'access_token' => Yii::t('app', 'Token de acesso'),
            'online' =>  Yii::t('app', 'Online'),
            'home_dir_name' =>  Yii::t('app', 'diretório do home'),
            'home_dir' =>  Yii::t('app', 'diretório do home'),
            'avatar_file' => Yii::t('app', 'Imagem do perfil'),
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
     * Executa em batch configurações iniciais no banco de dados e aplicação para o usuário.
     */
    public function initialBootstrap(){
        
        /* Cria a view user_{user_id}_friends_id noo banco de dados para guardar os user_id de amigos */
        $row_count = Yii::$app->db->createCommand("
           CREATE VIEW user_{$this->id}_friends_id AS select  b.friend_user_id id from ".Users::tableName()." a
           inner join ".UserFriendships::tableName(). " b on (a.id=b.user_id) where a.id = 2
           union
           select  b.user_id id from ".Users::tableName()." a inner join ".UserFriendships::tableName()." b on (a.id=b.friend_user_id) where a.id = 2  
        ")->execute();
           //...
           //...
           //...
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
    
    public function getfakefriends(){
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
     * @return \yii\db\ActiveQuery
     */
    public function getFriends()
    {
        // Usando operador union, para capturar quando usuário é o mandatário da amizade (user_id = id) e quando ele não é (friend_user_id = id)
        return $this->hasMany(Users::className(), ['id' => 'friend_user_id'])
            ->viaTable(UserFriendships::tableName(), ['user_id' => 'id'])->union(
               $this->hasMany(Users::className(), ['id' => 'user_id'])
            ->viaTable(UserFriendships::tableName(), ['friend_user_id' => 'id'])
                    );
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendshipRequests()
    {
        return $this->hasMany(UserFriendshipRequests::className(), ['requested_user_id' => 'id']);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlerts()
    {
        return $this->hasMany(Alerts::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveAlerts()
    {
        return $this->hasMany(Alerts::className(), ['user_id' => 'id'])->active();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNonexistentAlerts()
    {
        return $this->hasMany(Alerts::className(), ['user_id' => 'id'])->nonexistent();
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [$this->scenarios()[self::SCENARIO_CREATE], 'required', 'on'=>self::SCENARIO_CREATE],
            [['avatar_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 1, 'on'=>self::SCENARIO_CREATE],
            ['username', 'unique', 'on'=>self::SCENARIO_CREATE],
            ['email', 'unique', 'on'=>self::SCENARIO_CREATE],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'on'=>self::SCENARIO_CREATE],
            [['signup_date', 'last_access_date'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['full_name'], 'string', 'max' => 100],
            [['how_to_be_called'], 'string', 'max' => 30],
            [['question','answer'], 'string'],
            [['home_dir'], 'string'],
            [['online'], 'integer'],            
            [['username'], 'string', 'max' => 30],
            [['auth_key','access_token'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['password'], 'string', 'max' => 16],
            // regras para cadastro
            // compara "password" com "password_repeat"
            // Verifica o arquivo do perfil no cadastro
            
        ];
    }
    
    // define os atributos seguros "safe" para massive atribuition via $model->attributes 
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['first_name', 'last_name', 'how_to_be_called','username', 'password', 'password_repeat', 'email', 'avatar_file', 'question', 'answer'],
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
        $this->setavatar(Url::to('@users_dir/'.$this->getHomeDirName().'/images/'.self::AVATAR_FILE));
        return $this->avatar;
    }
    
    public function setavatar($src=''){
        $this->avatar = $src;
    }
    
    public function getHomeDirName(){
        if($this->isNewRecord){
            $this->home_dir_name = md5(Yii::$app->security->generateRandomString());
            return $this->home_dir_name;
        }
        return $this->home_dir_name;
    }
    public function getHomeDir(){
        return Url::to('@users_dir').'/'.$this->getHomeDirName();
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $this->home_dir = Yii::getAlias('@users_dir_path').'/'.$this->getHomeDirName();
            if(!is_dir($this->home_dir)){
                mkdir($this->home_dir.'/images', 775, true);
            }
            $this->avatar_file->saveAs($this->home_dir.'/images/'.self::AVATAR_FILE, false);
            return true;
        }else {
            return false;
        }
    }
}
