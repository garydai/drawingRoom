<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <script type="text/javascript" src="/js/jquery-2.1.1.min.js"></script>

                <link href="/3rd/bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet">


<link href="/css/gaga.css" rel="stylesheet">

                <script src="/3rd/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>



 <div class="col-xs-5 col-sm-5 ">
</div>
<div class="col-xs-6 col-sm-6">
<h1>Register</h1>

<div class="form">



<?php $form=$this->beginWidget('CActiveForm', array(
)); ?>

        <div class="row">
                <?php echo $form->labelEx($model,'email'); ?>
                <?php echo $form->textField($model,'email'); ?>
                <?php echo $form->error($model,'email'); ?>
        </div>

        <div class="row">
                <?php echo $form->labelEx($model,'password'); ?>
                <?php echo $form->passwordField($model,'password'); ?>
                <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="row">
                <?php echo $form->labelEx($model,'password2'); ?>
                <?php echo $form->passwordField($model,'password2'); ?>
                <?php echo $form->error($model,'password2'); ?>
        </div>



        <div class="row submit">
                <?php echo CHtml::submitButton('Register'); ?>
        </div>



<?php $this->endWidget(); ?>
</div><!-- form -->


</div>
