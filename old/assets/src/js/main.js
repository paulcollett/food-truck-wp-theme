//=include vendor/*
//=include includes/*
(function($){

    if(!$) {
        console.error('jQuery needs to be loaded before the theme\'s Javscript');
        return false;
    }

    var exports = {};
    var $body;
    var $header;
    var $window = $(window);

    // This code sets the height of a fake header that pushes the page down
    // the same amount as the height of the real header
    // it also does some smart stuff to not slow down your page
    var headerSize = function() {

        $body = $body || $('body');
        var $fakeHeader = $('.header-faux');
        $header = $header || $('.header');
        var fixedHeader = $body.hasClass('site--fixed-header');
        var needsFauxHeight;
        var hasFauxHeight;
        var lastHeight;

        if(!fixedHeader || !$fakeHeader.length) {
            return false;
        }

        var reCalculateHeaderHeight = function(){

            var headerHeight = $header.height();
            
            if(lastHeight !== headerHeight) {
                $fakeHeader.get(0).style = 'height: ' + headerHeight + 'px';
            }
            lastHeight = headerHeight;
        }

        var onResize = function(){
            var windowWidth = window.innerWidth || document.documentElement.clientWidth;// note: doc.. doesn't factor in scrollbar

            needsFauxHeight = windowWidth > 1300;

            if(needsFauxHeight) {
                reCalculateHeaderHeight();
            } else if(hasFauxHeight && !needsFauxHeight) {
                $fakeHeader.get(0).style = '';
                lastHeight = null;
            }

            hasFauxHeight = needsFauxHeight;

        }

        $window.on('resize', onResize);
        $window.on('load', onResize);
        onResize();

    }

    var headerMobileToggle = function() {

        var toggleMenu = function() {
            $body.toggleClass('site--menu-open');
        }

        $('.navigation-toggle').on('click', toggleMenu)

    }

    var renderVimeoImage = function() {
        $('.js-vimeo-image').each(function(){

            var container = $(this);
            if(container.data('_rendered')) return;
            container.data('_rendered', true);

            var id = container.data('vimeo-id');

            $.getJSON('//vimeo.com/api/v2/video/' + id + '.json?callback=?',function(r){
                var src = r[0] && r[0]['thumbnail_large'];
                src && container.replaceWith('<img alt="" src="' + src + '" />')
            });

        });
    }

    var renderVideoModule = function() {

        $('.js-video').each(function(){
            var container = $(this);
            if(container.data('_rendered')) return;
            container.data('_rendered', true);
            var render = function() {
                container.html(container.find('script:last').html());
            }
            if('ontouchstart' in window) render();
            container.on('click',render);
        });

    }

    var renderContactFormModule = function() {

        $('.js-contact-form').each(function(){
            var container = $(this);
            if(container.data('_rendered')) return;
            container.data('_rendered', true);
            var successHTML = container.find('script:last').html();
            var submitElements = container.find('.js-submit, [href="#submit"]');
            container.on('submit',function(e){
                e.preventDefault();
            });
            var showSuccess = function() {
                container.html(successHTML);
                window.scrollTo(0, container.position().top - 150);
                container.css('opacity', 1);
            }
            var onSubmit = function(e) {
                e.preventDefault();
                container.css('opacity', 0.8);
                submitElements.css('opacity', 0.8);
                $.post(container.attr('action'), container.serialize() + '&ajax=1',function(r) {
                    try{r = $.parseJSON(r)}catch(e){r=false}
                    if(r.ok){
                        showSuccess();
                    }else{
                        alert(r.error || 'Response Error. Try again later');
                        container.css('opacity', 1);
                        submitElements.css('opacity', 1);
                    }
                });
            }
            submitElements.on('click',onSubmit);
        });

    }

    var renderSlider = function() {
        $('.js-slider').each(function(){
            var container = $(this);
            var slides = $(this).find('.slider_slide');
            if(container.data('_rendered')) return;
            container.data('_rendered', true);
            var paginationItems = container.find('.pagination_item');
            var pagActiveClass = 'pagination_item--active';
            var slideActiveClass = 'slider_slide--active';
            if(!slides.length || slides.length < 2) return;
            var slider = new Swipe(container.get(0), {
              startSlide: 0,
              speed: 400,
              auto: 3500,
              draggable: true,
              continuous: true,
              disableScroll: false,
              stopPropagation: false,
              callback: function(index, elem) {
                slides.removeClass(slideActiveClass).eq(index).addClass(slideActiveClass)
                paginationItems.removeClass(pagActiveClass).eq(index).addClass(pagActiveClass);
              },
              transitionEnd: function(index, elem) {}
            });
            paginationItems.on('click', function(e){
                e.stopPropagation();
                var index = paginationItems.index(this);
                slider.to(index);
            });
            container.on('click',function(){
                slider.stop();
            });
        });

    }

    var renderSubscribe = function() {
        
    
        $('.js-subscribe-container').each(function(){

            var container = $(this);
            if(container.data('_rendered')) return;
            container.data('_rendered', true);
            var form = container.find('.js-subscribe-form');
            var successHTML = container.find('script').html();
            var submitElements = container.find('.button');
            var onSubmit = function(e) {
                e.preventDefault();
                container.css('opacity', 0.5);
                $.post(form.attr('action'), form.serialize() + '&ajax=1',function(r) {
                    try{r = $.parseJSON(r)}catch(e){r=false}
                    if(r.ok){
                        container.html(successHTML);
                    }else{
                        alert(r.error || 'Response Error. Try again later');
                    }
                    container.css('opacity', 1);
                });
            }
            submitElements.on('click',onSubmit);
            form.on('submit', onSubmit);

        });

    }

    var renderMap = function() {

        $('.js-map').each(function(){

            var container = $(this);
            if(container.data('_rendered')) return;
            container.data('_rendered', true);

            GMap.ready(function(){
                var map = GMap.create(container, {scrollwheel: false});
                var marker = container.attr('data-marker');
                var locations = container.data('map').split('|');
                for (var i = 0; i < locations.length; i++) {
                    if(locations[i].indexOf(';') > 0) {
                        map.addMarker(locations[i].split(';'),marker);
                    }else{
                        GMap.parseLocation(locations[i],function(googLatLng){
                            map.addMarker(googLatLng,marker);
                        });
                    }
                };
            });

        });

    }

    var renderLocationsModule = function() {

        var render = function() {

            var container = $(this);
            var detailContainer = container.find('.js-location-container');
            var detailBack = detailContainer.find('.js-location-back, .button[href=#]');
            var detailDetails = detailContainer.find('.js-location-details');
            var detailMap = detailContainer.find('.js-location-map');
            var listContainer = container.find('.js-location-list-container');
            var itemsDOM = container.find('.js-location-expand-container');
            var itemsMap = container.find('.js-locations-all-map');
            var items = [];
            var marker = container.attr('data-marker') || null;
            var detailMapGObj = false;
            var mainMapGObj = false;

            detailBack.on('click', function(e){
                e.preventDefault();
                detailContainer.hide();
                listContainer.show();
                if(detailMapGObj) detailMapGObj.reset();
                positionPage();
                if(mainMapGObj) google.maps.event.trigger(mainMapGObj.map, "resize");
            });

            var positionPage = function(){
                window.scrollTo(0, container.offset().top - $header.height() - 150);
            }

            var loadItem = function(obj) {
                detailContainer.show();
                listContainer.hide();
                detailDetails.html(obj.html);
                if(obj._foundAddr) detailDetails.append('<hr />' + obj._foundAddr + '<br /><a href="https://maps.google.com?saddr=Current+Location&daddr=' + obj._foundAddr + '" target="_blank">View Directions</a>');
                positionPage();

                if(!detailMapGObj) return;

                google.maps.event.trigger(detailMapGObj.map, "resize");

                if(obj._latlng) {
                    detailMapGObj.addMarker(obj._latlng,marker);
                } else {
                    GMap.parseLocation(obj.address,function(googLatLng){
                        detailMapGObj.addMarker(googLatLng,marker);
                    });
                }

            }

            var loadAll = function() {
                itemsDOM.each(function(){
                    var html = $(this).html();
                    var address = $(this).find('script').html();
                    var obj = {
                        address: address,
                        html: html,
                        _latlng: false,
                        _foundAddr: false
                    };
                    items.push(obj);
                    $(this).on('click',loadItem.bind(null, obj))
                });
                if(!items.length) return;
                GMap.ready(function(){
                    var windowWidth = window.innerWidth || document.documentElement.clientWidth;
                    detailMapGObj = GMap.create(detailMap, {zoom: 15, scrollwheel: (windowWidth > 980)});
                    mainMapGObj = GMap.create(itemsMap, {scrollwheel: false});
                    for (var i = 0; i < items.length; i++) {
                        items[i].address && (function(item){
                            GMap.parseLocation(items[i].address,function(googLatLng, addr){
                                item._latlng = googLatLng;
                                item._foundAddr = addr;
                                mainMapGObj.addMarker(googLatLng,marker,loadItem.bind(null, item));
                            });
                        })(items[i]);
                    };
                });
            }

            loadAll();

            itemsDOM.on('click', loadItem);

        };

        $('.js-location-module').each(function(){
            var container = $(this);
            if(container.data('_rendered')) return;
            container.data('_rendered', true);
            render.call(this);
        });

    }

    var onDOMReady = function() {
        // misc: post comments button
        $('.button[href=#toggle-comments]').on('click',function(e){
            //e.preventDefault();
            $('.js-post-comment').show();
        });

        if(window.FastClick) {
            window.FastClick.attach(document.body);
        }


        $('.cover-image').each(function() {
            $(this).append('<div class="cover-image_img" style="background-image: url(' + $(this).find('img:first').prop('src') + ')" />');
        });

        // set broken image src to 1x1 px image
        $('img').on('error', function(){
            this.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
            $(this).attr('data-broken-image',true);
        });

        if(window.theme_no_ga) return;

        (function(i,r){i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();
            i[r]('create', 'UA-85978714-1', 'auto', 'trucklotwp1');
            i[r]('trucklotwp1.send', 'pageview');
            i[r]('trucklotwp1.set', 'dimension1', '1.0');
        })(window,window.GoogleAnalyticsObject||'ga');

    }

    $(onDOMReady);

    exports.headerReady = function() {
        headerSize();
        headerMobileToggle();
    }

    exports.videoReady = function() {
        renderVimeoImage();
        renderVideoModule();
    }

    exports.contactFormReady = function() {
        renderContactFormModule();
    }

    exports.sliderReady = function() {
        renderSlider();
    }

    exports.mapReady = function() {
        renderMap();
    }

    exports.locationsMapReady = function() {
        renderLocationsModule();
    }

    exports.subscribeReady = function() {
        renderSubscribe();
    }

    window.BraceFramework = exports;

})(window.jQuery);
