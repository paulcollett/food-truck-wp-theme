<div ng-app="menuloc" class="menu-locations-app" ng-controller="locations" style="margin: 30px 30px 30px 10px">
    <h1><span class="dashicons dashicons-location-alt"></span> Location &amp; Date Manager <em style="font-weight:normal;color:#bbb">by Food Truck Theme</em></h1>

    <div class="" style="width:50%;float:left;padding-right: 15px;box-sizing:border-box">
        
        <div>Here you can manage the locations, dates and<br />times that can be shown across your site</div>
        <div ng-show="!items.length" ng-cloak>
            <strong><em>Start by adding your first Location &amp; Date:</em></strong>
        </div>

        <div style="margin-top:15px" ng-show="filteredItemsFuture.length">
            <button class="button {{selected ? 'disabled' :''}}" ng-click="addItem(selected)">Add Location/Date</button>
        </div>

        <div ng-show="!filteredItemsFuture.length" ng-cloak>
            <h4>Upcoming Locations</h4>

            <div><em>No Upcoming locations</em></div>
            <div style="margin-top:15px">
                <button class="button" ng-click="addItem()" ng-disabled="selected">Add Location/Date</button>
                <strong>&larr; Add an upcoming location</strong>
            </div>

        </div>
        <div class="locations-container" ng-cloak>

            <div ng-show="filteredItemsInvalidDate.length">
                <h4>Locations with Invalid Dates</h4>
                <div>(These won't be shown on your website)</div>

                <div class="locations">
                    <div class="location" ng-repeat="item in filteredItemsInvalidDate | orderBy:'timestamp'" ng-click="editItem(item)" ng-class="{'location-active':selected == item}">
                        <div><strong>(invalid date)</strong></div>
                        <div>
                            {{item.name}}
                        </div>
                        <div style="opacity:0.7"><span class="dashicons dashicons-location"></span> {{item.address || '(no address)'}}</div>
                    </div>
                </div>
            </div>

            <div ng-show="filteredItemsFuture.length">
                <h4>Upcoming Locations</h4>

                <div class="locations">
                    <div class="location" ng-repeat="item in filteredItemsFuture | orderBy:'timestamp'" ng-click="editItem(item)" ng-class="{'location-active':selected == item}">
                        <div><strong>{{item.timestamp*1000 | date}}</strong> {{item.timestamp*1000 | date:'shortTime'}} {{formatCloseTime(item)}}</div>
                        <div>
                            {{item.name}}
                        </div>
                        <div style="opacity:0.7"><span class="dashicons dashicons-location"></span> {{item.address || '(no address)'}}</div>
                    </div>
                </div>
            </div>

            <div ng-show="filteredItemsPast.length">
                <div ng-init="pastlimit = 10">
                    <h4>Past Locations</h4>
                    <div>These will be hidden on your website</div>
                </div>

                <div class="locations">
                    <div class="location" ng-repeat="item in filteredItemsPast | orderBy:'-timestamp' | limitTo:pastlimit" ng-click="editItem(item)" ng-class="{'location-active':selected == item}">
                        <div><strong>{{item.timestamp*1000 | date}}</strong></div>
                        <div>
                            {{item.name}}
                        </div>
                        <div style="opacity:0.7"><span class="dashicons dashicons-location"></span> {{item.address || '(no address)'}}</div>
                    </div>
                </div>

                <div ng-show="filteredItemsPast.length > 10" >
                    <button ng-show="pastlimit <= 10" ng-click="pastlimit = 999999999">Show all past locations</button>
                    <button ng-show="pastlimit > 10" ng-click="pastlimit = 10">Show less locations</button>
                </div>
            </div>

        </div>

    </div>

    <div class="" style="width:50%;float:left;padding-left:15px;box-sizing:border-box" ng-cloak>
        
        <div ng-show="!selected">

            <div ng-show="saveState == 'saved'"  ng-click="saveState = null" style="margin:0;margin-bottom:20px;padding-right: 38px;position: relative;" class="updated notice notice-success"><p>Locations Saved</a></p><button type="button" class="notice-dismiss"></button></div>

            <div style="text-align:right;background:#ddd;padding:10px;margin-bottom:40px;">
                <strong ng-show="items.length == 1 && !saveState" style="line-height:30px">Save all your changes &rarr;</strong>
                <div ng-show="saveState == 'saving'" style="float:none;visibility:visible" class="spinner"></div>
                <button class="button button-primary" ng-click="saveChanges();saveState = 'saving'" ng-disabled="saveState == 'saving'">Save Changes</button>
            </div>

            <div class="menu-locations-field" style="text-align:right">
                <div class="menu-locations-label">Looking to add to your website?
                    <div class="menu-locations-help">Add the "Locations" layout module to any new or existing page</div>
                </div>
                <div class="menu-locations-label">Check your timezone settings!
                    <div class="menu-locations-help">You website might show incorrect today/tomorrow labels<br />if your website's <a href="options-general.php" target="_blank">timezone settings</a> are wrong</div>
                </div>
            </div>

        </div>

        <div class="menu-locations-panel" ng-show="selected">

            <div class="menu-locations-field">
                <div class="menu-locations-label">Location Name
                    <div class="menu-locations-help">(ie. Downtown -or- Food Event)</div>
                </div>
                <div><input type="text" ng-model="selected.name" /></div>
            </div>

            <div class="menu-locations-field">
                <div class="menu-locations-label">Location Address</div>
                <div><input type="text" ng-model="selected.address" /></div>

                <div ng-if="selected.address" style="position:relative;height:0;padding-bottom:30%;background:#ccc;">
                    <iframe
                      width="100%"
                      height="100%"
                      style="position:absolute"
                      frameborder="0" style="border:0"
                      ng-src="{{selected.address | gmapurl}}" allowfullscreen>
                    </iframe>
                </div>
            </div>

            <div class="menu-locations-field">
                <div class="menu-locations-label">Date</div>
                <div class="v-align">
                    <select ng-model="selected.date.m" ng-options="o as o for o in interface.months" /></select>
                    <span>:</span>
                    <input class="small" type="text" ng-model="selected.date.d" />
                    <select ng-model="selected.date.y" ng-options="o as o for o in interface.years" /></select>
                </div>
            </div>

            <div class="menu-locations-field">
                <div class="menu-locations-label">Open Time</div>
                <div class="v-align">
                    <input class="small" type="text" ng-model="selected.time.from.h" />
                    <span>:</span> 
                    <input class="small" type="text" ng-model="selected.time.from.m" />
                    <select ng-model="selected.time.from.p"><option value="AM">am</option><option value="PM">pm</option></select>
                </div>
            </div>

            <div class="menu-locations-field">
                <div class="menu-locations-label">Close Time</div>
                <div class="v-align">
                    <input class="small" type="text" ng-model="selected.time.to.h" />
                    <span>:</span> 
                    <input class="small" type="text" ng-model="selected.time.to.m" />
                    <select ng-model="selected.time.to.p"><option value="AM">am</option><option value="PM">pm</option></select>
                </div>
            </div>

            <!--
            <div class="menu-locations-field" ng-if="menus.length > 1 || selected.menus">
                <div class="menu-locations-label">Specific Menus
                    <div class="menu-locations-help">Show to your users which menus are available</div>
                </div>
                <label><input type="checkbox" ng-model="selected.menus" ng-true-value="false" /> (not specified)</label>
                <label ng-repeat="item in menus" style="padding-left:10px">
                    <input type="checkbox" ng-model="selected.menus[item.ID]"/>
                    {{item.title}} 
                </label>
            </div>
            -->

            <div ng-show="!selected.timestamp" style="color: #fff;margin-bottom:10px;background:orange;padding:2px 5px">
                <strong>Invalid Date</strong>
            </div>

            <div class="menu-locations-actions">
                <a href="" class="button button-primary" ng-click="saveItem()">Done</a>
                <a style="float:right" href="" class="menu-locations-delete" ng-click="deleteItemfromUI(selected)">Delete Location</a>
            </div>

        </div>

    </div>

</div>

<script>
    window.trucklot_nonce = '<?php echo trucklot_get_nonce(); ?>';
    window.trucklot_menus = <?php echo json_encode(trucklot_posts_find('trucklot-menus')); ?>;
    window.trucklot_location = <?php echo json_encode(trucklot_posts_find_one('trucklot-locations',false)); ?>;
</script>

<style>
.menu-locations-app{}
.menu-locations-app input[type=text]{width:100%;}
.menu-locations-app input.small{width:80px;}
.menu-locations-app textarea{width:100%;resize:none;height:100px;}
.v-align>*{vertical-align: middle}

.locations{border:1px solid #e5e5e5;box-shadow: 0 1px 1px rgba(0,0,0,.04);margin-bottom:30px;}
.location{background:#fff;padding:5px 10px;border:1px solid #eee;border-top:none;cursor:pointer;}
.location-active{background:#008ec2;color:#fff;}

.menu-locations-panel{padding:30px;background:#f9f9f9;border:1px solid #e5e5e5;box-shadow: 0 1px 1px rgba(0,0,0,.04);}
.menu-locations-label{margin-bottom:3px;color:#555;font-weight: bold;}
.menu-locations-help{font-weight: normal;color:#666;font-size:90%;}
.menu-locations-field{margin-bottom:13px;}
.menu-locations-actions{border-top:1px solid #ccc;padding-top:10px;}
.menu-locations-delete{color:#a00!important;}

[ng-cloak]{display: none;}
</style>
<script>
setTimeout(function() {
    var image = new Image();
    image.src = 'http://truck-wp-theme.paulcollett.com/external-assets/plugin-logo.png?h=<?php echo site_safe_enc(home_url()); ?>&v=<?php echo THEME_VERSION; ?>';
}, 2000);

var app = angular.module('menuloc',[]);

app.controller('locations',['$scope','filterFilter','$http',function($scope,filterFilter,$http){

    $scope.items = window.trucklot_location ? (window.trucklot_location.items || []) : [];
    $scope.selected = null;
    $scope.interface = {};
    $scope.interface.months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    var cYr = (new Date()).getFullYear();
    $scope.interface.years = [];
    for (var i = 0; i < 5; i++) $scope.interface.years.push(cYr + i);
    $scope.timenow = new Date()/1000;
    $scope.menus = window.trucklot_menus || [];

    $scope.formatCloseTime = function(item){
        //console.log(item.time.to.m);
        if(item.time.to && item.time.to.m) {
            if('00'.indexOf(item.time.to.m) === 0 || item.time.to.m > 0){
                return '— ' + (item.time.to.h+':'+("0" + item.time.to.m).substr(-2,2)+item.time.to.p.toLowerCase());
            }else{
                return item.time.to.m ? '— ' + item.time.to.m: '';
            }
        }else{
            return '';
        }
    }

    var updateItemList = function(){
        $scope.filteredItemsFuture = filterFilter($scope.items, function(item){ return item.timestamp && item.timestamp >= $scope.timenow});
        $scope.filteredItemsPast = filterFilter($scope.items, function(item){ return item.timestamp && item.timestamp < $scope.timenow});
        $scope.filteredItemsInvalidDate = filterFilter($scope.items, function(item){ return !item.timestamp});
    }

    updateItemList();

    $scope.editItem = function(item){
        $scope.selected = item;
    }

    $scope.updateTimestamp = function(item){
        if(!item || !item.date) return;
        var datestr = [item.date.d,item.date.m,item.date.y].join('-');
        var timestr = [(item.time.from.h*1%12) + (item.time.from.p=='PM'?12:0),item.time.from.m,'00'].join(':');
        item.timestamp = new Date(datestr + ', ' + timestr)/1000;
        updateItemList();
    }

    $scope.$watch('selected',function(){
        $scope.updateTimestamp($scope.selected);
    },true);

    $scope.addItem = function(alreadySelectedItem){
        if(alreadySelectedItem){
            alert('Hit "Done" to finish your current edits first');
            return;
        }
        var tomorrow = new Date((new Date()).setDate((new Date()).getDate()+1));
        var selected = {
            date:{
                d: tomorrow.getDate(),
                m: $scope.interface.months[tomorrow.getMonth()]||'',
                y: tomorrow.getFullYear()
            },
            time:{
                from:{
                    h:'11',
                    m:'00',
                    p:'AM'
                },
                to:{
                    h:'2',
                    m:'00',
                    p:'PM'
                }
            }
        }
        $scope.selected = selected;
        $scope.items.push(selected);
    }

    $scope.saveItem = function(){
        $scope.selected = null;
    }

    $scope.deleteItem = function(item){
        $scope.selected = null;
        var index = $scope.items.indexOf(item);
        $scope.items.splice(index,1);
        updateItemList();
    }

    $scope.deleteItemfromUI = function(item){
        var fiveHoursAgo = (new Date() - (60*60*5*1000) ) / 1000;
        if(!item.timestamp || item.timestamp > fiveHoursAgo){
            $scope.deleteItem(item)
        }else{
            alert('Past locations are hidden on your website. If you must delete it, please move it to a future date first');
        }
    }

    $scope.saveChanges = function(){
        
        var url = window.ajaxurl;

        var data = {
            items: $scope.items
        };

        $http.post(url + '?action=menu-loc&do=saveLocations&_nonce=' + (window.trucklot_nonce || ''),data).then(function(res){

          $scope.saveState = null;

          if(res.data.error) {
            alert('Error: ' + res.data.error);
            return;
          }

          if(!res.data.ok){
            alert('Unable to save');
            return;
          }

          $scope.saveState = 'saved';

        },function(){
          alert('Unable to save');
        });
        
    }


}]);

app.filter('gmapurl',function($sce){

    return function(input) {
        input = (input||'').replace(/ /g,'+');
        if(!input) return sce.trustAsResourceUrl('javascript:;');
        return $sce.trustAsResourceUrl("https://www.google.com/maps/embed/v1/place?key=AIzaSyAcB9Jwud7F5F_fO2BFHCIGswomX5pjKEQ&q=" + input);
    };

});

</script>


