<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="ru"> <!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <link rel="icon" type="image/png" href="/main/img/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/main/img/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" sizes="96x96" href="/main/img/favicon-96x96.png">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>


    <!-- additional styles for plugins -->
    <!-- kendo UI -->
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/kendo-ui/styles/kendo.material.min.css" id="kendoCSS"/>

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
            <?php $this->widget('ext.materialwidgets.NavMenu', array(
                'items'=>array(
                    array('label'=>'Главная', 'url'=>array('/', 'city'=>'якутск', 'place'=>'лена')),
                    array('label'=>'Настройки', 'url'=>array('/user/settings'), 'visible'=>!Yii::app()->user->isGuest),
                    array('label'=>'Войти', 'type'=>'modal', 'url'=>array('/googleapi/oauth/authenticate'), 'visible'=>Yii::app()->user->isGuest),
                    array('label'=>'('.Yii::app()->user->name.')', 'type'=>'profile', 'img'=>Yii::app()->user->getAvatar(), 'visible'=>!Yii::app()->user->isGuest,
                        'submenu' => [
                            [
                                'label'=>'Админка',
                                'url'=>array('/admin/'),
                                'visible'=>Yii::app()->user->checkAccess(User::ROLE_ADMIN)
                            ],
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

    <!-- common functions -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/main/js/common.js"></script>
    <!-- uikit functions -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/main/js/uikit_custom.js"></script>
    <!-- altair common functions/helpers -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/main/js/altair_admin_common.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/jsoneditor/dist/jsoneditor.min.js"></script>
    <!-- ionrangeslider -->
    <script src="/bower_components/ion.rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="/main/js/pages/forms_advanced.js"></script>

    <!-- page specific plugins -->
    <!-- kendo UI -->
    <script src="/main/js/kendoui_custom.js"></script>

    <!--  kendoui functions -->
    <script src="/main/js/pages/kendoui.min.js"></script>

    <script src="https://unpkg.com/vue@2.5.17/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/lodash@4.13.1/lodash.min.js"></script>
    <script src="/main/js/main.js"></script>

    <script>
    </script>
</body>
</html>