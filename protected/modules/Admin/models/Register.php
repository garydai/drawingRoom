<?php

class Register extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tbl_user':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $profile
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'g_admin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.

		return array(
			array('email', 'required','message'=>'邮箱不能为空'),
			array('password','required','message'=>'密码不能为空'),
			array('password2','compare','compareAttribute'=>'password','message'=>'两次密码不一致'),  
		        array('email','email','allowEmpty'=>false,'message'=>'邮箱格式不正确'),
			array('email', 'unique','message'=>'该邮箱已注册'), // 唯一，不能有重复记录
		);

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'password' => 'Password',
			'password2'=>'Password2',
			'email' => 'Email',
			'profile' => 'Profile',
		);
	}



	protected function beforeSave() {
		if($this->isNewRecord) {
			$this->password  = md5($this->password);
			$this->password2 = md5($this->password2);

		}
		return parent::beforeSave();
		}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return CPasswordHelper::verifyPassword($password,$this->password);
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @return string hash
	 */
	public function hashPassword($password)
	{
		return CPasswordHelper::hashPassword($password);
	}
}
