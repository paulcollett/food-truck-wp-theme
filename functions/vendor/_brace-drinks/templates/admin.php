<div ng-app="brace-drinks" class="brace-drinks-app" ng-controller="menus" style="margin: 30px 30px 30px 10px">
    <h1 style="margin-bottom:20px"><span class="dashicons dashicons-format-aside"></span> Beer Menu Manager <em style="font-weight:normal;color:#bbb">by StudioBrace</em></h1>

    <div class="brace-drinks-layout">
        <div class="brace-drinks-layout_items">

            <div class="brace-drinks-item">
                IBU
            </div>

        </div>

        <div class="brace-drinks-layout_edit">



        </div>
    </div>


</div>

<script>
    window.brace_menu_nonce = '<?php echo BraceDrinks::instance()->get_nonce(); ?>';
    window.brace_menu_menus = <?php echo json_encode(BraceDrinks::instance()->find_posts()); ?>;
</script>
<style>

.menu-locations-app{}


[ng-cloak]{display: none;}
</style>
<script type="text/html" id="brace-menu-file">
<div>
      <ul ng-if="multiple">
        <li ng-if="f.id" ng-repeat="f in file">
          {{f.filename}}
          <a href="#" ng-click="remove(f,file)">Remove</a>
        </li>
      </ul>
      <div ng-if="file.id && !multiple">

        <div ng-if="file.type == 'image'">
          <img ng-src="{{file.thumbnail_medium}}" style="max-width:300px;max-height:170px" />
        </div>
        <div ng-if="file.type != 'image'">
          <img ng-src="{{file.icon}}">
        </div>
        <div> {{file.filename}}</div>

        <div ng-if="file.type == 'image'">
            Original Size: {{file.width}} &times; {{file.height}}px
        </div>

        <div ng-if="file.type != 'image'">
            {{file.filesize}}
        </div>
        <div>
          <a href="#" ng-click="removeFile()">Remove</a>
        </div>
    </div>
    <div ng-if="!file.id">
      <button class="button" ng-click="browse()">Select Image</button>
    </div>
</div>
</script>
<script>
var app = angular.module('menuloc',[]);

app.controller('menus',['$scope','$http',function($scope,$http){

    $scope.menus = window.brace_menu_menus || [];
    $scope.menu_id = null;
    $scope.menu = null;
    $scope.menu_item_original = null;
    $scope.menu_item = null;

    //Goto edit view if only one menu exists
    /*if($scope.menus.length == 1){
        $scope.menu_id = $scope.menus[0].ID;
        $scope.menu = $scope.menus[0];
    }*/

    $scope.selectMenu = function(menu){
        $scope.menu_id = menu.ID || 'new';
        $scope.menu = menu;
        $scope.menu_item = null;
        $scope.saveState = null;
    }

    $scope.newMenuItem = function(addtotop){
        if(!$scope.menu) return;
        $scope.menu.items = $scope.menu.items||[];
        var item = {};
        if(addtotop){
            $scope.menu.items.unshift(item);
        }else{
            $scope.menu.items.push(item);
        }
        
        $scope.editMenuItem(item);
    }

    $scope.editMenuItem = function(item){

        if($scope.menu_item == item){
            return;
        }

        if($scope.menu_item && angular.equals($scope.menu_item,{})){
            $scope.removeItem($scope.menu_item);
        }

        $scope.menu_item = item;
        $scope.menu_item_original = angular.copy(item);
    }

    $scope.resetMenuItem = function(item){

        if(angular.equals($scope.menu_item_original,{})){
            $scope.removeItem(item);
            return;
        }

        item = angular.copy($scope.menu_item_original, $scope.menu_item);

        $scope.doneMenuItem();
    }

    $scope.removeItem = function(item){

        var index = $scope.menu.items.indexOf(item);
        $scope.menu.items.splice(index, 1); 

        $scope.doneMenuItem();
    }

    $scope.removeMenu = function(menu){

        var index = $scope.menus.indexOf(menu);
        $scope.menus.splice(index, 1); 
    }

    $scope.doneMenuItem = function(item){

        $scope.menu_item = null;
    }

    $scope.addMenu = function(menu){
        if(!menu.title){
            alert('You\'ll need to add a title to your menu');
            return;
        }

        menu.items = menu.items || [];

        $scope.menus.push(menu);

        $scope.saveMenu(menu);

    }

    $scope.deleteMenu = function(menu){

        if(menu.items && menu.items.length > 1){
            if(!confirm('Delete "' + menu.title + '" & ' + menu.items.length + ' items?')){
                return;
            }
        }

        $scope.removeMenu(menu);

        var url = window.ajaxurl;

        var data = {
            ID: menu.ID || false
        };

        $http.post(url + '?action=brace-menu&do=delete&_nonce=' + (window.brace_menu_nonce || ''),data).then(function(res){

          $scope.saveState = null;

          if(res.data.error) {
            alert('Error: ' + res.data.error);
            return;
          }

          if(!res.data.ok){
            alert('Unable to save');
            return;
          }

          menu.ID = res.data.ID || false;
          menu.title = res.data.title || false;

          $scope.saveState = 'saved';

        },function(){
          alert('Unable to save');
        });

    }


    $scope.saveMenu = function(menu){

        var url = window.ajaxurl;

        var data = menu;

        if(data.ID == 'new') data.ID = null;

        $http.post(url + '?action=brace-menu&do=save&_nonce=' + (window.brace_menu_nonce || ''),data).then(function(res){

          $scope.saveState = null;

          if(res.data.error) {
            alert('Error: ' + res.data.error);
            return;
          }

          if(!res.data.ok){
            alert('Unable to save');
            return;
          }

          menu.ID = res.data.ID || false;
          menu.title = res.data.title || false;

          $scope.saveState = 'saved';

        },function(){
          alert('Unable to save');
        });

    }

    // Move list items up or down or swap
    $scope.move = function(array,oldIndex,newIndex){
      if(newIndex < 0 || newIndex >= array.length) return;
      array.splice(newIndex,0,array.splice(oldIndex,1)[0]);
    }

}]);

app.controller('CMFileSelector',function($scope){

  $scope.file = $scope.file || ($scope.multiple?[]:{});

  //$scope.remove = CovermenuUtils.remove;

  var settings = {
    title: 'Select Menu Item Image',
    button: {
      text: 'Use this Image'
    },
    //allowLocalEdits: false,
    //displaySettings: false,
    //displayUserSettings: false,
    multiple: $scope.multiple || false,
    library : { type : 'image'},//audio, video, application/pdf, ... etc
  };

  if($scope.type){
    settings.library.type = $scope.type == 'all' ? null : $scope.type;
  }

  var media = window.wp && window.wp.media && window.wp.media(settings);

  
  if(!media){
    console.warn('no media library');
    return;
  }
  //http://stackoverflow.com/questions/21540951/custom-wp-media-with-arguments-support

  var filterMediaResponseData = function(attachment){

    if(!attachment) return false;

    var filtered = {
      id: attachment.id,
      filename: attachment.filename,
      mime: attachment.mime,
      type: attachment.type,
      icon: attachment.icon,
      size: attachment.filesize,
      width: attachment.width,
      height: attachment.height,
      orientation: attachment.orientation,
      thumbnail: (attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : false),
      thumbnail_medium: (attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : false)
    };

    console.log(filtered);

    return filtered;

  }

  var mediaSelected = function(){
    $scope.$apply(function(){
      if(settings.multiple){
        var attachment = media.state().get('selection').toJSON();
        for (var i = 0; i < attachment.length; i++) {
          attachment[i] = filterMediaResponseData(attachment[i]);
        };
        $scope.file = attachment || [];
      }else{
        var attachment = media.state().get('selection').first().toJSON();
        $scope.file = filterMediaResponseData(attachment);
      }
    });
  }

  media.on( 'select', mediaSelected );

  media.on('open',function() {
      var selection = media.state().get('selection');

      var files = [].concat($scope.file);

      for (var i = 0; i < files.length; i++){
        if(!files[i].id) continue;
        files[i] = wp.media.attachment(files[i].id);
        files[i].fetch();
      };

      selection.add(files);

  });

  $scope.remove = function(item){
    var index = $scope.file.indexOf(item);
    $scope.file.splice(index, 1); 
  }

/*
  //on close, if there is no select files, remove all the files already selected in your main frame
  frame.on('close',function() {
      var selection = frame.state('insert-image').get('selection');
      if(!selection.length){
          #remove file nodes
          #such as: jq("#my_file_group_field").children('div.image_group_row').remove();
          #...
      }
  });
*/
  $scope.removeFile = function(){
    $scope.file = {};
  }

  $scope.browse = function(){

    if(!media){
      alert('Wordpress file selector is unavailable');
      return;
    }

    media.open();

  }

});

app.directive('cmFileSelector',function(){

  return {
    scope:{
      file:'=',
      multiple:'@',
      type:'@'
    },
    //restrict: 'E',
    replace:true,
    template: jQuery('#brace-menu-file').html(),
    controller:'CMFileSelector'//,
    //controllerAs:'cmfm'
  };

});


</script>
