<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <link rel="icon" type="image/png" href="/main/img/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/main/img/favicon-32x32.png" sizes="32x32">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>


    <!-- uikit -->
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/uikit/css/uikit.almost-flat.min.css" media="all">

    <!-- altair admin -->
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/main/css/main.min.css" media="all">

    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/jsoneditor/dist/jsoneditor.min.css" rel="stylesheet" type="text/css">
    <style>
         div#jsoneditorTree, div#jsoneditorCode {
            height: 377px;
         }
    </style>

    <!-- matchMedia polyfill for testing media queries in JS -->
    <!--[if lte IE 9]>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/matchMedia/matchMedia.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/matchMedia/matchMedia.addListener.js"></script>
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/main/css/ie.css" media="all">
    <![endif]-->

</head>
<body class="disable_transitions sidebar_main_swipe">
    <!-- main header -->
    <header id="header_main">
        <div class="header_main_content">
				<?php $this->widget('ext.materialwidgets.NavMenu',array(
					'items'=>array(
						array('label'=>'Главная', 'url'=>array('/', 'city'=>'якутск', 'place'=>'лена')),
						array('label'=>'Админка', 'url'=>array('/admin/user'), 'visible'=>Yii::app()->user->checkAccess(User::ROLE_ADMIN)),
						array('label'=>'Настройки', 'url'=>array('/user/settings'), 'visible'=>!Yii::app()->user->isGuest),
						array('label'=>'Войти', 'type'=>'modal', 'url'=>array('/googleapi/oauth/authenticate'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'('.Yii::app()->user->name.')', 'type'=>'profile', 'img'=>Yii::app()->user->getField('avatar'), 'visible'=>!Yii::app()->user->isGuest,
							'submenu' => [
								[
									'url'=>array('/site/logout'),
									'label'=>'logout'
								]
							]
						)
					),
				)); ?>
        </div>
    </header><!-- main header end -->
    <!-- main sidebar -->
    <div id="page_content">
        <div id="page_content_inner">

				<?php echo $content; ?>

        </div>
    </div>
    <!-- google web fonts -->
    <script>
        WebFontConfig = {
            google: {
                families: [
                    'Source+Code+Pro:400,700:latin',
                    'Roboto:400,300,500,700,400italic:latin'
                ]
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>

    <!-- common functions -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/main/js/common.js"></script>
    <!-- uikit functions -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/main/js/uikit_custom.js"></script>
    <!-- altair common functions/helpers -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/main/js/altair_admin_common.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/jsoneditor/dist/jsoneditor.min.js"></script>


    <script>
        $(function() {
            if(isHighDensity()) {
                $.getScript( "/main/js/custom/dense.min.js", function(data) {
                    // enable hires images
                    altair_helpers.retina_images();
                });
            }
            if(Modernizr.touch) {
                // fastClick (touch devices)
                FastClick.attach(document.body);
            }
        });
        $window.load(function() {
            // ie fixes
            altair_helpers.ie_fix();
        });
    </script>


   <script>
     var job = {
       "title": "Job description",
       "type": "object",
       "required": ["address"],
       "properties": {
         "company": {
           "type": "string"
         },
         "role": {
           "type": "string"
         },
         "address": {
           "type": "string"
         },
         "salary": {
           "type": "number",
           "minimum": 120
         }
       }
     };
     var json = {
       firstName: 'John',
       lastName: 'Doe',
       gender: null,
       age: "28",
       availableToHire: 1,
       job: {
         company: 'freelance',
         role: 'developer',
         salary: 100
       }
     };
     var optionsCode = {
       mode: 'code',
     };
     var optionsTree = {
       mode: 'tree',
     };
     // create the editor
     var container = document.getElementById('jsoneditorCode');
     var editor = new JSONEditor(container, optionsCode, json);
     var container = document.getElementById('jsoneditorTree');
     var editor = new JSONEditor(container, optionsTree, json);
   </script>
</body>
</html>