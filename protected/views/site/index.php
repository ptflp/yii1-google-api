<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<div class="md-card">
		<div class="md-card-content">
			<h3 class="heading_a">Input fields</h3>
			<?php
				if(Yii::app()->user->checkAccess('999')){
					echo "hello, I'm administrator";
				}
			?>
			<div class="uk-grid" data-uk-grid-margin>
				<div class="uk-width-medium-1-1">
						<div class="uk-form-row">
							<div class="uk-grid" data-uk-grid-margin>
								<div class="uk-width-medium-1-3">
										<select id="select_demo_5" data-md-selectize data-md-selectize-bottom data-uk-tooltip="{pos:'top'}" title="Select with tooltip">
											<option value="">Выберите город</option>
											<option value="a">Item A</option>
											<option value="b">Item B</option>
											<option value="c">Item C</option>
										</select>
										<span class="uk-form-help-block">Список городов</span>
								</div>
								<div class="uk-width-medium-2-3">
										<label>Введите адрес или место</label>
										<input type="text" class="md-input" />
								</div>
								<img class="uk-margin-top" src="/main/img/spinners/spinner.gif" alt="">
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