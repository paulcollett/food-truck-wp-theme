</main> <!-- /main which is opened in header.php -->

<!-- sidebar (Footer Widgets) -->
<?php get_sidebar(); ?>
<!-- / sidebar (Footer Widgets)-->

<footer class="footer ptlg pblg" role="contentinfo">
  <div class="content tc">
    <?php
      $footer_text_tags = array(
        '[copyright]' => '&copy;',
        '[year]' => current_time('Y'),
        '[name]' => get_bloginfo('name'),
      );

      echo str_replace(
        array_keys($footer_text_tags),
        array_values($footer_text_tags),
        esc_html(get_theme_mod("ftt_theme_mod_footer_text", "[copyright] [year] [name]"))
      );
    ?>
  </div>
</footer>

<!-- wp_footer -->
<?php wp_footer(); ?>
<!-- / wp_footer -->

</body>
</html>
