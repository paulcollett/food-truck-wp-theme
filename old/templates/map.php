<?php if(get_sub_field('address')): $location = get_sub_field('address'); ?>
    <div class="frame frame--skinny">
        <div class="map map--fill js-map" data-map="<?php echo $location['lat']. ';' . $location['lng']; ?>"><!-- | pipe for multiple pins -->
        </div>
    </div>
    <script>window.BraceFramework && window.BraceFramework.mapReady()</script>
<?php endif; ?>
