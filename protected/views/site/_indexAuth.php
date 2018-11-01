
<div class="md-card" id="app">
    <div class="md-card-content">
        <h3 class="heading_a">Test Google places API</h3>
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-1-1">
                <div class="uk-form-row">
                    <div class="uk-grid" data-uk-grid-margin >
                        <div class="uk-width-medium-1-4">
                                <?php if (!empty(Yii::app()->user->getCity()['description'])) : ?>
                                <input id="firstSelect" type="hidden" value="<?=Yii::app()->user->getCity()['id']?>" />
                                <?php endif;?>
                                <select id="cityId"  data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Выберите город" >
                                    <?php if (!empty(Yii::app()->user->getCity()['description'])) : ?>
                                        <option selected value="<?=Yii::app()->user->getCity()['id']?>"><?=Yii::app()->user->getCity()['description']?></option>
                                    <?php else : ?>
                                        <option value="">Установите город по умолчанию в настройках</option>
                                    <?php endif;?>

                                    <?php foreach ($cityList as $city) :?>
                                        <?php if ($city->id !== Yii::app()->user->getCity()['id']) : ?>
                                            <option value="<?=$city->id?>"><?=$city->description?></option>
                                        <?php endif; ?>
                                    <?php endforeach;?>
                                </select>
                                <span class="uk-form-help-block">Выберите город</span>
                        </div>
                        <div class="uk-width-medium-3-4">
                            <div class="md-input-wrapper">
                                <label>Введите адрес или место</label>
                                <input type="text" class="md-input" v-model="placesInput">
                            </div>
                        </div>
                        <div class="uk-width-medium-1-4">
                            <input type="text" id="matchPercent" class="ion-slider" data-min="0" data-max="100" data-from="61.8" />
                            <span class="uk-form-help-block">Точность совпадения запросов в %</span>
                        </div>
                        <div class="uk-width-medium-3-4">
                            <input type="text" class="md-input" disabled="" v-model="queryUrl">
                        </div>
                        <div class="uk-width-medium-1-6">
                            <div class="md-input-wrapper">
                                <input type="text" class="uk-form-width-medium k-input" id="addressesLimit" value="8" min="0" max="100">
                                <span class="uk-form-help-block">Лимит выдачи адресов</span>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-6">
                            <div class="md-input-wrapper">
                                <input type="text" class="uk-form-width-medium k-input" id="placesLimit" value="13" min="0" max="100">
                                <span class="uk-form-help-block">Лимит выдачи заведений</span>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-6">
                            <div class="md-btn-group">
                                <a class="md-btn md-btn-primary md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="javascript:void(0)" v-on:click="redis('flushall')">
                                    <i class="material-icons">cached</i>
                                    Flushall
                                </a>
                                <a class="md-btn md-btn-success md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="javascript:void(0)" v-on:click="redis('start')">
                                <i class="material-icons">settings_power</i>
                                    Start
                                </a>
                                <a class="md-btn md-btn-danger md-btn-wave-light md-btn-icon waves-effect waves-button waves-light" href="javascript:void(0)" v-on:click="redis('stop')">
                                <i class="material-icons">power_settings_new</i>
                                    Stop
                                </a>
                            </div>
                            <p class="uk-text-large">Redis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="md-card">
    <div class="md-card-content">
        <h3 class="heading_a">Output data</h3>
        <div class="uk-grid" data-uk-grid-margin>
            <div class="uk-width-medium-1-2">
                    <div id="jsoneditorCode"></div>
            </div>
            <div class="uk-width-medium-1-2">
                    <div id="jsoneditorTree"></div>
            </div>
        </div>
    </div>
</div>