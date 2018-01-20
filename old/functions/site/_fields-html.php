<style>
.acf-field-57d1ee803a69d{padding:20px!important;margin:10px 10px 10px 5px!important;margin-bottom:10px!important;background:#ffd900;outline:1px solid #d8b800;box-shadow:0 2px 2px #ddd;}
#acf-group_57f1bfc948a67{padding-bottom:20px!important;}
.acf-field_57d1ee803a69d{color:#945b00!important;}
.acf-field_57d1ee803a69d .acf-required{color:#945b00!important;}
.foodlot-label{text-transform: uppercase;font-size:80%;color:#ffd900;background:#7d770f;margin-right:3px;display:inline-block;vertical-align:middle;padding: 0 2px;border-radius:3px;}

.acf-postbox.seamless > .inside{margin-left:0!important;}
.acf-settings-wrap > h1{margin-bottom: 20px;font-size:18px;}

.acf-tab-group li .acf-tab-button{background: #fff!important;}
.acf-tab-group li .acf-tab-button:hover{background: #f5f5f5!important;}
.acf-tab-group li.active .acf-tab-button{font-weight: bold!important;background: transparent!important;}
#acf-group_57d21a5732df8 .acf-input h1{padding-top:0!important;}

.acf-icon.-minus:before {font-family: dashicons;content: "\f182";line-height: inherit;}
.acf-fc-layout-order:before {content:'Layout #';}
.acf-field-57f5b708199e5 .acf-fc-layout-order:before {content:'Slide #';}
.acf-field-57f5b708199e5 .acf-fc-layout-order{background:#4e4e4e!important;color:#fff!important;}
.acf-field-57f5b708199e5 .acf-fc-layout-controlls .acf-icon.-plus.small:after{content: 'Add Slide Above';}
.acf-field-57f5b708199e5 .acf-flexible-content .layout{background:#f5f5f5;}
.acf-field-57ff0e05e7ad8 .acf-fc-layout-order:before {content:'Grid Item #';}
.acf-field-57ff0e05e7ad8 .acf-fc-layout-controlls .acf-icon.-plus.small:after{content: 'Add Grid Item Above';}
.acf-field-57ff0e05e7ad8 .acf-fc-layout-order{background:#4e4e4e!important;color:#fff!important;}
.acf-field-57ff0e05e7ad8 .acf-flexible-content .layout{background:#f5f5f5;}
.acf-flexible-content .layout .acf-fc-layout-order{width: auto;padding:0 5px;}
.acf-field-57ddb92b95ac6 .acf-row-handle{vertical-align: top!important;color: #333!important;}/*testimonials*/
.acf-field-57ddb92b95ac6 .acf-row-handle > span:before{content:'#';}

.acf-fc-layout-controlls .acf-icon.-plus.small{width: auto!important;border-radius: 3px!important;padding-right: 3px;}
.acf-fc-layout-controlls .acf-icon.-plus.small:after{content: 'Add Module Above';font-size: 10px;text-transform: uppercase;font-weight:bold;}
.acf-flexible-content .layout .acf-fc-layout-controlls > li:last-child{margin-left: 15px;}
</style>
<script>

window.jQuery(function($){
    setTimeout(function(){ $('#revisionsdiv:not(.closed) > button').trigger('click'); }, 10);
});

window.jQuery(function($){

    var $inputs = $('.acf-field-57d1ee803a69d input');//.acf-field-57d20f9a1e5ea input
    var $themelotInput = $inputs.filter('[value=themelot]');

    if(!$inputs.length) return;

    var pageToggleEditorState = function(){
        var val = $inputs.filter(':checked').val();
        $('#postdivrich').toggle(val!='themelot');
        $(window).trigger('resize');
    }

    $inputs.on('change', pageToggleEditorState);

    var defaultToThemeLot = <?php $post = get_post(); echo isset($post->post_content) && strlen(strip_tags($post->post_content)) > 2 ? 'false':'true'; ?>;

    if(defaultToThemeLot && !$themelotInput.prop('checked')){
        $themelotInput.prop('checked', true);
        $themelotInput.filter('[value=themelot]').trigger('change');
    }

    pageToggleEditorState();

});

</script>