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
          body>.content-preloader {
            position: fixed;
            z-index: 1999;
            top: -48px;
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
  <!-- component template -->
  <script type="text/x-template" id="city-list">
    <table class="uk-table uk-table-striped uk-table-hover">
      <thead>
        <tr>
          <th v-for="key in columns"
            @click="sortBy(key)"
            :class="{ active: sortKey == key }">
            {{ key | capitalize }}
            <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
            </span>
          </th>
          <th class="uk-text-center">
            Действие
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="entry in filteredData">
          <td v-for="key in columns">
            {{entry[key]}}
          </td>
          <td class="uk-text-center">
            <a href="#"><i class="md-icon material-icons" v-on:click="removeById(entry,$event)">delete</i></a>
          </td>
        </tr>
      </tbody>
    </table>
  </script>

    <script>
  // register the grid component
  Vue.component('city-list', {
    template: '#city-list',
    props: {
      data: Array,
      columns: Array,
      filterKey: String,
      removeId: Number
    },
    data: function () {
      var sortOrders = {}
      this.columns.forEach(function (key) {
        sortOrders[key] = 1
      })
      return {
        sortKey: '',
        sortOrders: sortOrders
      }
    },
    computed: {
      filteredData: function () {
        var sortKey = this.sortKey
        var filterKey = this.filterKey && this.filterKey.toLowerCase()
        var order = this.sortOrders[sortKey] || 1
        var data = this.data
        if (filterKey) {
          data = data.filter(function (row) {
            return Object.keys(row).some(function (key) {
              return String(row[key]).toLowerCase().indexOf(filterKey) > -1
            })
          })
        }
        if (sortKey) {
          data = data.slice().sort(function (a, b) {
            a = a[sortKey]
            b = b[sortKey]
            return (a === b ? 0 : a > b ? 1 : -1) * order
          })
        }
        return data
      }
    },
    filters: {
      capitalize: function (str) {
        return str.charAt(0).toUpperCase() + str.slice(1)
      }
    },
    methods: {
      sortBy: function (key) {
        this.sortKey = key
        this.sortOrders[key] = this.sortOrders[key] * -1
      },
      removeById: function (entry,e) {
        e.preventDefault();
        this.$emit('removeid', entry)
      }
    }
  })
  // end register the grid component


  var searchCity = document.getElementById('searchCity');
  if (searchCity !== null) {
    const CancelToken = axios.CancelToken;
    new Vue({
      el:"#searchCity",
      data:{
        cities: Array,
        gapiSearch: '',
        cancel: '',
        removeId: Number,
        cityListSearch: '',
        cityColumns: ['id', 'name', 'description', 'place_id', 'latitude', 'longitude'],
        cityList: [
        ]
      },
      created: function () {
        // `this` указывает на экземпляр vm
        this.getCityList();
      },
      watch: {
        gapiSearch: function() {
          if (this.gapiSearch.length > 2) {
            this.lookupSearch()
          }
        }
      },
      methods: {
        getCityList: function() {
          var app = this
          var instance = axios.create();
          instance.get('/admin/city/list')
          .then(function (response) {
            console.log(response.data);
            altair_helpers.content_preloader_hide();
            app.cityList = response.data;
          })
          .catch(function (error) {
              altair_helpers.content_preloader_hide();
              console.log(error);
          })
        },
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
                city_name: app.gapiSearch,
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
        addCity: function(city,e) {
          e.preventDefault();
          console.log(city);
          var app = this

          UIkit.modal.confirm('Добавить город ' + city.description, function(){
            const form = new FormData();
            form.append('City[name]', city.name);
            form.append('City[description]', city.description);
            form.append('City[place_id]', city.place_id);
            form.append('City[longitude]', city.longitude);
            form.append('City[latitude]', city.latitude);
            axios({
              method: 'post',
              url: '/admin/city/create',
              data: form,
              config: { headers: {'Content-Type': 'multipart/form-data' }}
            })
            .then(function (response) {
                app.getCityList();
                UIkit.modal.alert('Город успешно добавлен!');
                //handle success
                console.log(response);
            })
            .catch(function (response) {
                //handle error
                console.log(response);
            });
          });
        },
        removeCity: function (city) {
          var app = this

          UIkit.modal.confirm('Удалить город ' + city.description, function(){
            axios({
              method: 'post',
              url: '/admin/city/delete/id/'+city.id,
              config: { headers: {'Content-Type': 'multipart/form-data' }}
            })
            .then(function (response) {
                app.getCityList();
                UIkit.modal.alert('Город успешно удален!');
            })
            .catch(function (response) {
                //handle error
                console.log(response);
            });
          });
        },
        clearCityList: function () {
          var app = this

          UIkit.modal.confirm('Очистить таблицу tbl_city?', function(){
            axios({
              method: 'post',
              url: '/admin/city/clear',
              config: { headers: {'Content-Type': 'multipart/form-data' }}
            })
            .then(function (response) {
                app.getCityList();
                UIkit.modal.alert('Таблица очищена');
            })
            .catch(function (response) {
                console.log(response);
            });
          });
        }
      }
    })
  }

  </script>
</body>
</html>