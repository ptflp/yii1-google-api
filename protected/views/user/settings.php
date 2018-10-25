<?php
/* @var $this UserController */
/* @var $model User */
?>

<h1>User settings</h1>

    <div id="page_content">
        <div id="page_content_inner">
				<div class="uk-grid" data-uk-grid-margin>
					<div class="uk-width-large-7-10">
						<div class="md-card">
								<div class="user_heading" data-uk-sticky="{ top: 48, media: 960 }">
									<div class="user_heading_avatar fileinput fileinput-new" data-provides="fileinput">
										<div class="fileinput-new thumbnail">
												<img src="<?=Yii::app()->user->getAvatar()?>" alt="user avatar"/>
										</div>
									</div>
									<div class="user_heading_content">
										<h2 class="heading_b"><span class="uk-text-truncate" id="user_edit_uname"><?=Yii::app()->user->name?></span><span class="sub-heading" id="user_edit_position">Land acquisition specialist</span></h2>
									</div>
								</div>
								<div class="user_content">
								<?php $this->renderPartial('_form', array('model'=>$model,'cityList'=>$cityList)); ?>
								</div>
						</div>
					</div>
				</div>
        </div>
    </div>