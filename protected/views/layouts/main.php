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
    <!-- ionrangeslider -->
    <script src="/bower_components/ion.rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="/main/js/pages/forms_advanced.js"></script>

    <!-- page specific plugins -->
    <!-- kendo UI -->
    <script src="/main/js/kendoui_custom.js"></script>

    <!--  kendoui functions -->
    <script src="/main/js/pages/kendoui.min.js"></script>


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
            $('.k-input').kendoNumericTextBox({
                format: "n0",
                min: 0,
                max: 50,
                step: 1
            });
        });
        $( "#settingsCity" ).change(function() {
            var cityId = $(this).val();
            cityId = parseInt(cityId);
            $.post( "/user/save/", { city_id: cityId })
            .done(function( data ) {
                UIkit.notify("Операция успешна", {status:'success'})
            });
        });
    </script>


   <script>
     var json = [];
     var optionsCode = {
       mode: 'code',
     };
     var optionsTree = {
       mode: 'tree',
     };
     // create the editor
     var container = document.getElementById('jsoneditorCode');
     if (container !== null) {
        var editorJSON = new JSONEditor(container, optionsCode, json);
     }
     var container = document.getElementById('jsoneditorTree');
     if (container !== null) {
        var editorObj = new JSONEditor(container, optionsTree, json);
     }
   </script>


  <script src="https://unpkg.com/vue@2.5.17/dist/vue.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="https://unpkg.com/lodash@4.13.1/lodash.min.js"></script>

    <script>

    const CancelToken = axios.CancelToken;
    var checkApp = document.getElementById('app');
    if (checkApp !== null) {
        var app = new Vue({
            el: '#app',
            data: {
                placesInput: '',
                cityId: '',
                cancel: '',
                matchPercent: 61.8,
                queryUrl: 'GET ' + document.URL + 'googleapi/place/search',
                addressesLimit: 8,
                placesLimit: 13
            },
            created: function () {
                var f = document.getElementById('firstSelect') ;
                if(f !== null) {
                 this.cityId = f.value;
                }
            },
            watch: {
                placesInput: function() {
                    if (this.placesInput.length > 2) {
                        this.lookupPlacesInput()
                    }
                }
            },
            methods: {
                lookupPlacesInput: _.debounce(function() {
                    this.queryUrl = 'GET ' +
                        document.URL +
                        'googleapi/place/search?city_id='+this.cityId+
                        '&match_percent='+this.matchPercent+
                        '&places_limit='+this.placesLimit+
                        '&addresses_limit='+this.addressesLimit+
                        '&keyword='+this.placesInput;

                    altair_helpers.content_preloader_show();
                    var app = this
                    if (typeof app.cancel !== "string") {
                        app.cancel('Stop previous request');
                    }
                    var instance = axios.create();
                    instance.get('/googleapi/place/search', {
                            cancelToken: new CancelToken(function executor(c) {
                                // An executor function receives a cancel function as a parameter
                                app.cancel = c;
                            }),
                            params: {
                                city_id: app.cityId,
                                keyword: app.placesInput,
                                match_percent: app.matchPercent,
                                places_limit: app.placesLimit,
                                addresses_limit: app.addressesLimit
                            }
                        })
                        .then(function (response) {
                            altair_helpers.content_preloader_hide();
                            editorJSON.set(response.data);
                            editorObj.set(response.data);
                        })
                        .catch(function (error) {
                            console.log(error);
                        })
                }, 800)
            }
        });

        $( "#cityId" ).change(function() {
            app.cityId = $(this).val();
            if (app.placesInput.length > 2) {
                app.lookupPlacesInput();
            }
        });

        $( "#matchPercent" ).change(function() {
                app.matchPercent =$(this).val();
            if (app.placesInput.length > 2) {
                app.lookupPlacesInput();
            }
        });

        $( "#addressesLimit" ).change(function() {
            console.log('addressesLimit');
                app.addressesLimit =$(this).val();
            if (app.placesInput.length > 2) {
            console.log('addressesLimit');
                app.lookupPlacesInput();
            }
        });

        $( "#placesLimit" ).change(function() {
            console.log('placesLimit');
                app.placesLimit =$(this).val();
            if (app.placesInput.length > 2) {
                console.log('placesLimit');
                app.lookupPlacesInput();
            }
        });
    }
    </script>
</body>
</html>