<?php
$icons = [
      'default' => '',
      'fullscreen' => '<i class="material-icons md-24 md-light">fullscreen</i>'
];
?>
<nav class="uk-navbar">
      <div class="uk-navbar-flip">
         <ul class="uk-navbar-nav user_actions">
            <?php
            foreach ($items as $item) {
                  $visible = true;
                  $iconName = 'default';

                  if(isset($item['visible'])) {
                        $visible = $item['visible'];
                  }
                  if(isset($item['icon'])){
                        $iconName = $item['iconName'];
                  }

                  if(!isset($item['type'])&&$visible):?>
                        <li><a href="<?=$item['url'][0]?>" class="user_action_icon uk-visible-large"><?=$icons[$iconName].$item['label']?></a></li>
                  <?php
                  endif;

                  if(isset($item['type'])&&$visible):
                  $img = '/main/img/avatar_11_tn.png';
                  if(isset($item['img'])){
                        $img = $item['img'];
                  }
                  ?>
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                              <a href="#" class="user_action_image"><?=$item['label']?> <img class="md-user-image" src="<?=$img?>" alt=""/></a>
                              <div class="uk-dropdown uk-dropdown-small">
                              <?php
                                    if(isset(($item['submenu']))):?>
                                          <ul class="uk-nav js-uk-prevent">
                                                <?php foreach ($item['submenu'] as $menuItem):?>
                                                            <li><a href="<?=$menuItem['url'][0]?>"><?=$menuItem['label']?></a></li>
                                                <?php endforeach;?>
                                          </ul>
                              <?php endif;?>
                              </div>
                        </li>
            <?php endif;
            } ?>
         </ul>
      </div>
</nav>