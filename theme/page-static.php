<?php
$pg = get_query_var('yc_page'); $p = yc_pages()[$pg]; $LL = yc_landing(); $L = $LL[$pg] ?? null;
status_header(200);
get_header(); ?>
<article class="yc-page">
<h1><?php echo esc_html($p[0]); ?></h1>
<?php include get_template_directory() . ($L ? '/landing.php' : '/pages/' . $pg . '.php'); ?>
</article>
<?php get_footer(); ?>
