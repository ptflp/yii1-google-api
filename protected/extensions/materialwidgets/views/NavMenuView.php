<?php
/**
 * Виджет кривой, чисто для примера
 */
$icons = [
      'default' => '',
      'fullscreen' => '<i class="material-icons md-24 md-light">fullscreen</i>'
];
?>
<nav class="uk-navbar">
      <div class="uk-navbar-flip">
         <ul class="uk-navbar-nav user_actions">
            <?php
            foreach ($items as $item):
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
                        switch ($item['type']) {
                              case 'modal':?>
                                    <li><a href="#" class="user_action_icon uk-visible-large" data-uk-modal="{target:'#modal_overflow'}"><i class="material-icons md-24 md-light">exit_to_app</i><?=$item['label']?></a></li>

                                    <div id="modal_overflow" class="uk-modal" aria-hidden="false">
                                          <div class="uk-modal-dialog md-card" style="top: 31.5px;">
                                                <div class="md-card-content large-padding" id="login_form">
                                                      <div class="login_heading">
                                                            <div class="user_avatar"></div>
                                                      </div>
                                                      <form>
                                                            <div class="uk-margin-medium-top">
                                                            <a href="<?=$item['url'][0]?>" onclick="(function(modal){ modal = UIkit.modal.blockUI('<div class=\'uk-text-center\'>Авторизация...<br/><div class=\'content-preloader-undefined\' style=\'height:48px;width:48px;\'><div class=\'md-preloader\'><svg xmlns=\'http://www.w3.org/2000/svg\' version=\'1.1\' height=\'48\' width=\'48\' viewBox=\'0 0 75 75\'><circle cx=\'37.5\' cy=\'37.5\' r=\'33.5\' stroke-width=\'8\'></circle></svg></div></div>'); })();" class="md-btn md-btn-gplus md-btn-large md-btn-block md-btn-icon"><i class="uk-icon-google-plus"></i>Sign in with Google+</a>
                                                            </div>
                                                      </form>
                                                </div>
                                          </div>
                                    </div>
                              <?php break;
                              case 'profile':
                                    $img = '/main/img/avatar_11_tn.png';
                                    if(isset($item['img'])&&!empty($item['img'])){
                                          if (strlen($item['img'])>10) {
                                                $img = $item['img'];
                                          }
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
                                    <?php
                                    break;
                        } ?>
            <?php endif;
            endforeach; ?>
         </ul>
      </div>
</nav>