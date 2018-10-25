<?php

class NavMenu extends CWidget
{
   public $items;

   public function init() {

   }

   public function run() {
      $this->render('NavMenuView', [
         'items' => $this->items
      ]);
   }
}

