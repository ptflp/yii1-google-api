<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>


			<h3 class="heading_a">Выберите ваш город:</h3>
			<div class="uk-grid" data-uk-grid-margin>
				<div class="uk-width-medium-1-1">
						<div class="uk-form-row">
							<div class="uk-grid" data-uk-grid-margin>
								<div class="uk-width-medium-1-2">
										<select id="settingsCity" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Выберите город">
											<?php if(!empty(Yii::app()->user->getCity()['name'])): ?>
												<option value="<?=Yii::app()->user->getCity()['id']?>"><?=Yii::app()->user->getCity()['description']?></option>
											<?php else: ?>
                                 	<option value=""></option>
											<?php endif;?>

											<?php foreach ($cityList as $key => $city):?>
												<?php if ($key !== Yii::app()->user->getCity()['id']): ?>
													<option value="<?=$key?>"><?=$city?></option>
												<?php endif; ?>
											<?php endforeach;?>
										</select>
										<span class="uk-form-help-block">Выберите город</span>
								</div>
							</div>
						</div>
				</div>
			</div>