<?php

class WebUser extends CWebUser {
    private $_model = null;

	public function login($identity, $duration = 0) {
		parent::login($identity, $duration);
	}

    function getRole() {
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->role;
        }
	}

    function getAvatar() {
        if($user = $this->getModel()){
            return $user->avatar;
        }
	}


    function getCity() {
        if($user = $this->getModel()){
			$city = $user->city;
            return $city;
        }
	}

    public function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id, array('select' => 'role,avatar,city_id'));
        }
        return $this->_model;
    }
}