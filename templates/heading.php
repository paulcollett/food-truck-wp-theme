<?php if(get_sub_field('heading')): ?>
    <?php
        $size = get_sub_field('size');
        $tag = array(
            'large' => 'h2',
            'medium' => 'h2',
            'small' => 'h3',
            'tiny' => 'h4'
        );
        $tag['large'] = isset($h1_used) && !$h1_used ? 'h1' : 'h2';
        $tag['medium'] = isset($h1_used) && !$h1_used ? 'h1' : 'h2';
        $tag = isset($tag[$size]) ? $tag[$size] : $tag['small'];
    ?>
    <div class="contain contain--<?php echo in_array($size, array('small', 'tiny')) ? 'body' : 'max1200'; ?> contain--margin">
        <?php echo "<$tag class='heading heading--$size'>";?>
        <?php the_sub_field('heading'); ?>
        <?php echo "</$tag>";?>
    </div>

<?php endif; ?>