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
						array('label'=>'Главная', 'url'=>array('/')),
						array('label'=>'Пользователи', 'url'=>array('/admin/user'), 'visible'=>!Yii::app()->user->isGuest),
						array('label'=>'Города', 'url'=>array('/admin/city'), 'visible'=>!Yii::app()->user->isGuest),
						array('label'=>'('.Yii::app()->user->name.')', 'type'=>'profile', 'img'=>Yii::app()->user->getAvatar(), 'visible'=>!Yii::app()->user->isGuest,
							'submenu' => [
                                [
                                    'label'=>'Админка',
                                    'url'=>array('/admin/user'),
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


    <script>
        $(function() {
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



  <script src="https://unpkg.com/vue@2.5.17/dist/vue.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="https://unpkg.com/lodash@4.13.1/lodash.min.js"></script>

    <script>
  var searchCity = document.getElementById('searchCity');
  if (searchCity !== null) {
    const CancelToken = axios.CancelToken;
    new Vue({
      el:"#searchCity",
      data:{
        cities: [],
        search: '',
        cancel: ''
      },
      watch: {
        search: function() {
              if (this.search.length > 2) {
                console.log('triggered');
                  this.lookupSearch()
              }
          }
      },
      methods: {
        lookupSearch: _.debounce(function() {
          altair_helpers.content_preloader_show();
          var app = this
          if (typeof app.cancel !== "string") {
              app.cancel('Stop previous request');
          }
          var instance = axios.create();
          instance.get('/googleapi/place/findcity', {
            cancelToken: new CancelToken(function executor(c) {
                // An executor function receives a cancel function as a parameter
                app.cancel = c;
            }),
            params: {
                city_name: app.search,
            }
          })
          .then(function (response) {
            altair_helpers.content_preloader_hide();
            app.cities = response.data;
          })
          .catch(function (error) {
              altair_helpers.content_preloader_hide();
              console.log(error);
          })
        }, 800),
        addCity: function(city) {
            UIkit.modal.confirm('Добавить город ' + city.description, function(){ UIkit.modal.alert('Confirmed!'); });
            console.log(city);
        }
      }
    })
  }
    </script>
</body>
</html>