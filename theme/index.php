<?php get_header(); ?>
<article class="yc-page">
<?php if (have_posts()): while (have_posts()): the_post(); ?>
<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
<?php endwhile; else: ?>
<h1>Stránka sa nenašla</h1>
<p>Táto adresa na webe neexistuje. Možno hľadáte niečo z tohto:</p>
<ul class="yc-check"><li><a href="<?php echo esc_url(home_url('/sluzby/')); ?>">Služby a cenník</a></li><li><a href="<?php echo esc_url(home_url('/preklad-zmluvy/')); ?>">Preklad zmluvy</a></li><li><a href="<?php echo esc_url(home_url('/kontakt/')); ?>">Kontakt</a></li><li><a href="<?php echo esc_url(home_url('/')); ?>">Domovská stránka</a></li></ul>
<p>Alebo nám zavolajte na <a href="tel:+421915343261">+421 915 343 261</a>, poradíme.</p>
<?php endif; ?>
</article>
<?php get_footer(); ?>
