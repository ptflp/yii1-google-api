<?php

class NavMenu extends CWidget
{
   public $items;

   public function init() {

   }

   public function run() {
      $this->render('NavMenu', [
         'items' => $this->items
      ]);
   }
}

