<?php
$typ = sanitize_key($_GET['typ'] ?? 'kontakt');
$texty = [
	'zavolajte' => ['Ďakujeme, zavoláme vám.', 'Ozveme sa vám v pracovný deň čo najskôr, zvyčajne do niekoľkých hodín.'],
	'cennik'    => ['Ďakujeme, cenník je na ceste.', 'Cenník prekladov pre firmy vám pošleme e-mailom do 24 hodín.'],
	'rychly'    => ['Ďakujeme, dopyt sme prijali.', 'Cenovú ponuku a termín vám pošleme do 24 hodín v pracovné dni. Potvrdenie sme vám poslali aj e-mailom.'],
	'kontakt'   => ['Ďakujeme, dopyt sme prijali.', 'Cenovú ponuku a termín vám pošleme do 24 hodín v pracovné dni. Potvrdenie sme vám poslali aj e-mailom.'],
];
$t = $texty[$typ] ?? $texty['kontakt'];
status_header(200);
get_header(); ?>
<article class="yc-page" style="text-align:center">
<svg aria-hidden="true" viewBox="0 0 64 64" width="72" height="72"><circle cx="32" cy="32" r="30" fill="#E3F2EF"/><path d="M20 33 L28 41 L45 23" fill="none" stroke="#2E9E6A" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/></svg>
<h1><?php echo esc_html($t[0]); ?></h1>
<p><?php echo esc_html($t[1]); ?></p>
<p>Ak to súri, zavolajte nám na <a href="tel:+421915343261"><b>+421 915 343 261</b></a>.</p>
<p><a href="<?php echo esc_url(home_url('/')); ?>">← Späť na domovskú stránku</a></p>
</article>
<script>window.YC_LEAD=<?php echo wp_json_encode($typ); ?>;</script>
<?php get_footer(); ?>
