<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
if (Yii::app()->user->isGuest) :
    $this->renderPartial('_indexGuest');
endif;

if (!Yii::app()->user->isGuest) :
    $this->renderPartial('_indexAuth', array('cityList'=>$cityList));
endif;
