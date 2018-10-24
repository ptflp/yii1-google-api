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

    <title>Google API Yii 1.16</title>


    <!-- uikit -->
    <link rel="stylesheet" href="bower_components/uikit/css/uikit.almost-flat.min.css" media="all">

    <!-- altair admin -->
    <link rel="stylesheet" href="/main/css/main.min.css" media="all">

    <link href="jsoneditor/dist/jsoneditor.min.css" rel="stylesheet" type="text/css">
    <style>
         div#jsoneditorTree, div#jsoneditorCode {
            height: 377px;
         }
    </style>

    <!-- matchMedia polyfill for testing media queries in JS -->
    <!--[if lte IE 9]>
        <script type="text/javascript" src="bower_components/matchMedia/matchMedia.js"></script>
        <script type="text/javascript" src="bower_components/matchMedia/matchMedia.addListener.js"></script>
        <link rel="stylesheet" href="/main/css/ie.css" media="all">
    <![endif]-->

</head>
<body class="disable_transitions sidebar_main_swipe">
    <!-- main header -->
    <header id="header_main">
        <div class="header_main_content">
            <nav class="uk-navbar">

                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav user_actions">
                        <li><a href="#" class="user_action_icon uk-visible-large"><i class="material-icons md-24 md-light">fullscreen</i>asfasfas</a></li>
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="#" class="user_action_image"><img class="md-user-image" src="/main/img/avatars/avatar_11_tn.png" alt=""/></a>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav js-uk-prevent">
                                    <li><a href="page_user_profile.html">My profile</a></li>
                                    <li><a href="page_settings.html">Settings</a></li>
                                    <li><a href="login.html">Login Page</a></li>
                                    <li><a href="login_v2.html">Login Page v2</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header><!-- main header end -->
    <!-- main sidebar -->
    <div id="page_content">
        <div id="page_content_inner">

            <div class="md-card">
                <div class="md-card-content">
                    <h3 class="heading_a">Input fields</h3>
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
    <script src="/main/js/common.js"></script>
    <!-- uikit functions -->
    <script src="/main/js/uikit_custom.js"></script>
    <!-- altair common functions/helpers -->
    <script src="/main/js/altair_admin_common.js"></script>
    <script src="jsoneditor/dist/jsoneditor.min.js"></script>


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