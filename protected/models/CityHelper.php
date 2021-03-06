<?php

class CityHelper extends City
{
    /**
     * City list for dropdown
     *
     * @return void
     */
    public static function dropDownList()
    {
        $list = self::model()->findAll(array('order'=>'id'));
        $dropDownList = [];
        foreach ($list as $item) {
            $dropDownList[$item->id] = $item->description;
        }

        return $dropDownList;
    }
}
