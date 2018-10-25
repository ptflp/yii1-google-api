<?php

class WebUser extends CWebUser {
    private $_model = null;

	public function getField($name)
	{
		if ($this->hasState('__userInfo')) {
			$user=$this->getState('__userInfo',array());
			if (isset($user[$name])) {
				return $user[$name];
			}
		}

		return parent::__get($name);
	}

	public function login($identity, $duration = 0) {
		$this->setState('__userInfo', $identity->getUser());
		parent::login($identity, $duration);
	}

    function getRole() {
        if($user = $this->getModel()){
            // в таблице User есть поле role
            return $user->role;
        }
    }

    public function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id, array('select' => 'role'));
        }
        return $this->_model;
    }
}