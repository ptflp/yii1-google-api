<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
	// Будем хранить id.
	protected $_id;

	// Данный метод вызывается один раз при аутентификации пользователя.
	public function authenticate(){
		 // Производим стандартную аутентификацию, описанную в руководстве.
		 $user = User::model()->find('LOWER(email)=?', array(strtolower($this->username)));
		 if(($user===null) || ($this->username!==$user->email)) {
			 $user = new User;
			 $user->email = $this->username;
			 $user->role = 999;
			 $user->ban = 0;
			 $user->save(false);
		 }
		// В качестве идентификатора будем использовать id, а не username,
		// как это определено по умолчанию. Обязательно нужно переопределить
		// метод getId(см. ниже).
		$this->_id = $user->id;

		// Далее логин нам не понадобится, зато имя может пригодится
		// в самом приложении. Используется как Yii::app()->user->name.
		// realName есть в нашей модели. У вас это может быть name, firstName
		// или что-либо ещё.
		$this->username = $user->email;

		$this->errorCode = self::ERROR_NONE;
		return true;
	}

	public function getId(){
		 return $this->_id;
	}
}