<?php

class UserAuthorize
{
   protected $email;

	protected $googleInfo;

   private $_identity;

   public function setEmail(string $email)
   {
      $this->email = $email;

      return $this;
   }

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->email,NULL);
			$this->_identity->setGoogleInfo($this->googleInfo);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			Yii::app()->user->login($this->_identity,false);
			return true;
		}
		else
			return false;
	}

	public function setGoogleInfo($googleInfo)
	{
		$this->googleInfo = $googleInfo;

		return $this;
	}
}
