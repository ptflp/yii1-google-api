<?php
/* @var $this CityController */
/* @var $dataProvider CActiveDataProvider */
?>

<h1>Управление городами</h1>
<div class="md-card">
	<div class="md-card-content" id="searchCity">
		<div class="uk-grid">
      <div class="uk-width-medium-1-1">
          <div class="md-btn-group">
              <a href="javascript:void(0)" class="md-btn md-btn-primary md-btn-wave waves-effect waves-button" data-uk-modal="{target:'#addCity'}"><i class="material-icons">add_circle</i> Добавить город</a>
              <a href="javascript:void(0)" class="md-btn md-btn-danger md-btn-wave waves-effect waves-button" v-on:click="clearCityList"><i class="material-icons">delete_forever</i> Очистить список городов</a>
          </div>
          <div class="uk-modal" id="addCity">
              <div class="uk-modal-dialog uk-modal-dialog-large">
                <div class="uk-width-medium-1-1 uk-row-first">
                    <h2>Поиск</h2>
                    <div class="md-input-wrapper">
                      <label>Поиск города</label>
                      <input type="text" class="md-input uk-form-width-large" v-model="gapiSearch">
                      <span class="md-input-bar uk-form-width-large"></span>
                    </div>
                </div>
                <div class="uk-width-medium-1-1">
                  <div class="md-card uk-margin-medium-bottom">
                    <div class="md-card-content">
                        <div class="uk-overflow-container">
                          <h2>Результаты поиска Google Places API</h2>
                          <table class="uk-table uk-table-striped uk-table-hover">
                            <thead>
                              <tr>
                                  <th class="uk-width-2-10">Название города</th>
                                  <th class="uk-width-3-10">Описание</th>
                                  <th class="uk-width-2-10 uk-text-center">longitude</th>
                                  <th class="uk-width-2-10 uk-text-center">latitude</th>
                                  <th class="uk-width-1-10 uk-text-center">Действие</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="city in cities">
                                  <td>{{city.name}}</td>
                                  <td>{{city.description}}</td>
                                  <td class="uk-text-center">{{city.longitude}}</td>
                                  <td class="uk-text-center">{{city.latitude}}</td>
                                  <td class="uk-text-center">
                                    <a href="#"><i class="md-icon material-icons" v-on:click="addCity(city,$event)">add_circle</i></a>
                                  </td>
                              </tr>
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      <hr>
      </div>
			<div class="uk-width-medium-1-1 uk-row-first">
				<h2>Список городов в системе</h2>
        <form>
          <label>Поиск города</label>
          <input name="query" class="md-input uk-form-width-large" v-model="cityListSearch">
        </form>
        <city-list @removeid="removeCity"
          :data="cityList"
          :columns="cityColumns"
          :filter-key="cityListSearch">
        </city-list>
      </div>
		</div>
	</div>
</div>
<?php /* $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?> */
