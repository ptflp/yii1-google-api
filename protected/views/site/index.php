<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
if(Yii::app()->user->isGuest):
?>

<?php endif; ?>
<?php if(!Yii::app()->user->isGuest):
/* AUTHORIZED USER CONTENT */
	?>
<div class="md-card" id="app">
		<div class="md-card-content">
			<h3 class="heading_a">Test Google places API</h3>
			<?php
				if(Yii::app()->user->checkAccess('999')){
					echo "hello, I'm administrator";
				}
			?>
			<div class="uk-grid" data-uk-grid-margin>
				<div class="uk-width-medium-1-1">
						<div class="uk-form-row">
							<div class="uk-grid" data-uk-grid-margin v-on:load="test()">
								<div class="uk-width-medium-1-3">
										<?php if(!empty(Yii::app()->user->getCity()['name'])): ?>
										<input id="firstSelect" type="hidden" value="<?=Yii::app()->user->getCity()['id']?>" />
										<?php endif;?>
										<select id="cityId"  data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Выберите город" >
											<?php if(!empty(Yii::app()->user->getCity()['name'])): ?>
												<option selected value="<?=Yii::app()->user->getCity()['id']?>"><?=Yii::app()->user->getCity()['name']?></option>
											<?php else: ?>
                                 	<option value="">Установите город по умолчанию в настройках</option>
											<?php endif;?>

											<?php foreach ($cityList as $city):?>
												<?php if ($city->id !== Yii::app()->user->getCity()['id']): ?>
													<option value="<?=$city->id?>"><?=$city->name?></option>
												<?php endif; ?>
											<?php endforeach;?>
										</select>
										<span class="uk-form-help-block">Выберите город</span>
								</div>
								<div class="uk-width-medium-2-3">
									<div class="md-input-wrapper">
										<label>Введите адрес или место</label>
										<input type="text" class="md-input" v-model="placesInput">
									</div>
								</div>
                        <div class="uk-width-medium-1-3">
                            <input type="text" id="matchPercent" class="ion-slider" data-min1="0" data-max="100" data-from="70" />
                            <span class="uk-form-help-block">Точность совпадения слов</span>
                        </div>
							</div>
						</div>
				</div>
			</div>
		</div>
</div>
<div class="md-card">
		<div class="md-card-content">
			<h3 class="heading_a">Input fields</h3>
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
<?php
/* END AUTHORIZED USER CONTENT */
endif;?>