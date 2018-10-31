<?php

class DI extends CApplicationComponent
{
    public $_container;

    public function getContainer()
    {
        if (!$this->_container) {
            $this->_container = require(Yii::app()->params['dicConfig']);
        }

        return $this->_container;
    }
}
