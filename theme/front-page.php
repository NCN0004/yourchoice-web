<?php get_header(); ?>
<?php if (!empty($_GET['yc_chyba'])): ?>
<div class="yc-note" style="max-width:1152px;margin:16px auto 0;background:#FDEBD3">Skontrolujte prosím <?php echo $_GET['yc_chyba'] === 'telefon' ? 'telefónne číslo' : 'e-mailovú adresu'; ?> a odošlite formulár znova.</div>
<?php endif; ?>
<?php include get_template_directory() . '/parts/front.php'; ?>
<?php get_footer(); ?>
