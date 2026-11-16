<?php get_header(); ?>
<article class="yc-page">
<?php if (have_posts()): while (have_posts()): the_post(); ?>
<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
<?php endwhile; else: ?>
<h1>Stránka sa nenašla</h1>
<p>Skúste <a href="<?php echo esc_url(home_url('/')); ?>">domovskú stránku</a> alebo nám zavolajte na <a href="tel:+421915343261">+421 915 343 261</a>.</p>
<?php endif; ?>
</article>
<?php get_footer(); ?>
