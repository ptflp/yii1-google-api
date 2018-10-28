<?php
/* @var $this CityController */
/* @var $model City */
/* @var $form CActiveForm */
?>

<div class="form">
<form id="city-form" action="/admin/city/create" method="post">
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    
    <div class="row">
        <label for="City_name" class="required">Название <span class="required">*</span></label>        <input size="60" maxlength="255" name="City[name]" id="City_name" type="text">            </div>

    <div class="row">
        <label for="City_description" class="required">Описание <span class="required">*</span></label>        <input size="60" maxlength="255" name="City[description]" id="City_description" type="text">            </div>

    <div class="row">
        <label for="City_place_id" class="required">PlaceID <span class="required">*</span></label>        <input size="60" maxlength="255" name="City[place_id]" id="City_place_id" type="text">            </div>

    <div class="row">
        <label for="City_longitude" class="required">Долгота <span class="required">*</span></label>        <input size="60" maxlength="255" name="City[longitude]" id="City_longitude" type="text">            </div>

    <div class="row">
        <label for="City_latitude" class="required">Широта <span class="required">*</span></label>        <input size="60" maxlength="255" name="City[latitude]" id="City_latitude" type="text">            </div>

    <div class="row buttons">
        <input type="submit" name="yt0" value="Create">    </div>

</form>
<?php /*
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'city-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'place_id'); ?>
        <?php echo $form->textField($model,'place_id',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'place_id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'longitude'); ?>
        <?php echo $form->textField($model,'longitude',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'longitude'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'latitude'); ?>
        <?php echo $form->textField($model,'latitude',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'latitude'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form --> */