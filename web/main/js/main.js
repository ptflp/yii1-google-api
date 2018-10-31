
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