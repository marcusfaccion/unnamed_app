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
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_PASSWORD_RESET = 'password_reset';
    const SCENARIO_NULL = 'null';
    const SCENARIO_CHALLANGE1 = 'challange1';
    const SCENARIO_CHALLANGE2 = 'challange2';
    const SCENARIO_CHALLANGE3 = 'challange3';
    
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
            'home_dir_name' =>  Yii::t('app', 'diretório do home'),
            'home_dir' =>  Yii::t('app', 'diretório do home'),
            'avatar_file' => Yii::t('app', 'Imagem do perfil'),
            'pharse' => Yii::t('app', 'Frase do perfil'),
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
           
         /* Cria o registro na tabela de controle de estado online e offline dos usuários */  
         $online = new OnlineUsers();
         $online->user_id = $this->id;
         $online->updated_date = date('Y-m-d H:i:s');
         $online->save();
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
     * Retorna todos os models Alerts que estão com a propriedade enable=>1
     * @return \yii\db\ActiveQuery ou null
     */
    public function getActiveAlerts()
    {
        return $this->hasMany(Alerts::className(), ['user_id' => 'id'])->active()->orderBy('id desc');
    }
    
    /**
     * Retorna todos os models Alerts que possuem reportes de não existência
     * @return \yii\db\ActiveQuery ou null
     */
    public function getNonexistentAlerts()
    {
        return $this->hasMany(Alerts::className(), ['user_id' => 'id'])->nonexistent()->orderBy('id desc');
    }

    /**
     * Retorna todos os models Alerts que possuem reportes de não existência que estão com
     * a propriedade enable=>1
     * @return \yii\db\ActiveQuery ou null
     */
    public function getActiveNonexistentAlerts()
    {
        return $this->hasMany(Alerts::className(), ['user_id' => 'id'])->nonexistent()->active()->orderBy('id desc');
    }
    
    /**
     * Retorna todos os models BikeKeepers que possuem reportes de não existência que estão com
     * a propriedade enable=>1
     * @return \yii\db\ActiveQuery ou null
     */
    public function getActiveNonexistentBikeKeepers()
    {
        return $this->hasMany(BikeKeepers::className(), ['user_id' => 'id'])->nonexistent()->active()->orderBy('id desc');
    }
    
    /**
     * Retorna todos os models BikeKeepers que estão com
     * a propriedade enable=>1
     * @return \yii\db\ActiveQuery ou null
     */
    public function getActiveBikeKeepers()
    {
        return $this->hasMany(BikeKeepers::className(), ['user_id' => 'id'])->active()->orderBy('id desc');
    }
   
    /**
     * Retorna todos os models OnlineUsers 
     * @return \yii\db\ActiveQuery ou null
     */
    public function getOnliner()
    {
        return $this->hasOne(OnlineUsers::className(), ['user_id' => 'id']);
    }
    
    /**
     * Retorna todos os models UserConversationAlerts onde eu sou o destinatário
     * @return \yii\db\ActiveQuery ou null
     */
    public function getConversationAlerts()
    {
        return $this->hasMany(UserConversationAlerts::className(), ['user_id' => 'id']);
    }
    
    /**
     * Retorna todos os models UserConversationAlerts onde eu sou o remetente
     * @return \yii\db\ActiveQuery ou null
     */
    public function getConversationAlertsSended()
    {
        return $this->hasMany(UserConversationAlerts::className(), ['user_id2' => 'id']);
    }
    
    /**
     * Checa se o usuário está logado de acordo com 
     * a tabela de controle de usuário online e offline
     * se a diferença da ultima updated_date para a datetime em minutos for maior que 1 minuto o usuário está offline
     * @return boolean
     */
    public function getOnline()
    {
        $timezone = Yii::$app->formatter->timeZone;
        Yii::$app->formatter->timeZone = 'UTC';
        
        if(Yii::$app->formatter->asDate($this->onliner->updated_date, 'php:Y-m-d')==date('Y-m-d')){ //icu yyyy-MM-dd            
            
            if((int)date('H')-(int)Yii::$app->formatter->asDatetime($this->onliner->updated_date, 'HH') > 1){
              Yii::$app->formatter->timeZone = $timezone;
              return  0;
            }
            
            if(
                    ((int)date('H')-(int)Yii::$app->formatter->asDatetime($this->onliner->updated_date, 'HH')) <= 1
                    &&
                    (
                            ((int)date('i')-(int)Yii::$app->formatter->asDatetime($this->onliner->updated_date, 'mm'))>1
                            ||
                            ((int)date('i')-(int)Yii::$app->formatter->asDatetime($this->onliner->updated_date, 'mm'))<0
                    )
              ){
                Yii::$app->formatter->timeZone = $timezone;
                return 0;
              }
                 
        }else{
          Yii::$app->formatter->timeZone = $timezone; 
          return 0;
        }
        Yii::$app->formatter->timeZone = $timezone;
        return 1;
    }
    /**
     * Retorna todos os avisos de mensagens enviados por mim
     *  para o usuário identificado por $user_id
     * @param integer $user_id
     * @return UserConversationAlerts[]
     */
    public function conversationsWhith($user_id){
        return UserConversationAlerts::find()->where(['user_id'=>$user_id,'user_id2'=>$this->id])->all();
    }
    
    
    /**
     * Verifica se $answer correspnde a resposta secreta
     * @param string $answer
     * @return boolean
     */
    public function isAnswerCorrect($answer){
        return (trim(strtolower($this->answer))===trim(strtolower($answer)));
    }
    
    /**
     * Verifica se a senha confere com a confirmação de senha
     * @return boolean
     */
    public function checkPasswords(){
        return ((!empty($this->password) && !empty($this->password_repeat)) && trim(strtolower($this->password))===trim(strtolower($this->password_repeat)));
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [$this->scenarios()[self::SCENARIO_CREATE], 'required', 'on'=>self::SCENARIO_CREATE],
            [['first_name', 'last_name', 'how_to_be_called', 'email', 'question', 'answer'], 'required', 'on'=>self::SCENARIO_UPDATE],
            
            [['avatar_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 1, 'on'=>self::SCENARIO_CREATE],
            ['username', 'unique', 'on'=>self::SCENARIO_CREATE],
            ['email', 'unique', 'on'=>self::SCENARIO_CREATE],
            ['email', 'unique', 'on'=>self::SCENARIO_UPDATE],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'on'=>self::SCENARIO_CREATE],
            
            ['email', 'required', 'on'=>self::SCENARIO_CHALLANGE1],
            
            ['answer', 'required', 'on'=>self::SCENARIO_CHALLANGE2],
            
            ['password, password_repeat', 'required', 'on'=>self::SCENARIO_CHALLANGE3],
            [['email'], 'email'],
            //['password_repeat', 'compare', 'compareAttribute' => 'password', 'on'=>self::SCENARIO_CHALLANGE3],
            
            [['signup_date', 'last_access_date'], 'safe'],
            [['password','password_repeat'], 'string', 'min' => 6,],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['full_name'], 'string', 'max' => 100],
            [['how_to_be_called'], 'string', 'max' => 30],
            [['question','answer','pharse'], 'string'],
            [['home_dir'], 'string'],         
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
            self::SCENARIO_UPDATE => ['first_name', 'last_name', 'how_to_be_called', 'password', 'password_repeat', 'email', 'question', 'answer', 'pharse'],
            self::SCENARIO_PASSWORD_RESET => ['password', 'password_repeat'],
            self::SCENARIO_CHALLANGE1 => ['email'],
            self::SCENARIO_CHALLANGE2 => ['answer'],
            self::SCENARIO_CHALLANGE3 => ['password', 'password_repeat'],
            self::SCENARIO_NULL => [],
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
    
    /**
     * Checa se o usuário identificado por $user_id é um amigo
     * @param int $user_id id do usuário
     * @return boolean true se são amigos e false caso não
     */
    public function isMyFriend($user_id){
        return (UserFriendships::find()->where(['user_id'=>$this->id, 'friend_user_id'=>$user_id])->orWhere(['user_id'=>$user_id, 'friend_user_id'=>$this->id])->count()==1)?true:false ;
    }
    
    /**
     * Verifica se os dois usuários são amigos
     * @param int $user_id id do usuário 1 
     * @param int $user_id2 id do usuário 2
     * @return boolean true se são amigos e false caso não
     */
    static function checkFriendShipping($user_id, $user_id2){
        return (UserFriendships::find()->where(['user_id'=>$user_id, 'friend_user_id'=>$user_id2])->orWhere(['user_id'=>$user_id2, 'friend_user_id'=>$user_id])->count()==1)?true:false ;
    }
    
    /**
     * Checa se existe uma solicitação de amizade para o usuário identificado por $requested_user_id
     * @param int $requested_user_id id do usuário solicitado
     * @return boolean true se existe solicitação de amizade e false caso não
     */
    public function wasIRequestFriendship($requested_user_id){
        return (UserFriendshipRequests::find()->where(['user_id'=>$this->id, 'requested_user_id'=>$requested_user_id])->count()==1)?true:false ;
    }
    
    /**
     * Checa se existe uma solicitação de amizade para o usuário identificado por $requested_user_id
     * feita pelo usuário identificado por $user_id
     * @param int $requested_user_id id do usuário solicitado
     * @param int $user_id id do usuário que solicita
     * @return boolean true se existe uma solicitação de amizade e false caso não
     */
    static function checkRequestFriendship($user_id, $requested_user_id){
        return (UserFriendshipRequests::find()->where(['user_id'=>$user_id, 'requested_user_id'=>$requested_user_id])->count()==1)?true:false ;
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
