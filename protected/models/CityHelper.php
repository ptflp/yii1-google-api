<?php

class CityHelper extends City
{
   public static function dropDownList()
   {
      $list = self::model()->findAll(array('order'=>'id'));
      $dropDownList = [];
      foreach ($list as $item) {
         $dropDownList[$item->id] = $item->name;
      }

      return $dropDownList;
   }
}
