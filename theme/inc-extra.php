<?php
/* Your Choice – doplnky z auditu 16. 11. 2026: zdieľanie, údaje pre Google, súkromie súborov, upratovanie. */
if (!defined('ABSPATH')) exit;

/* Písma priamo z webu (bez Google Fonts). */
add_action('wp_enqueue_scripts', function () {
	wp_dequeue_style('yc-fonts');
	wp_deregister_style('yc-fonts');
	wp_enqueue_style('yc-fonts-local', get_template_directory_uri() . '/fonts.css', [], '1');
}, 20);

/* Aktuálna adresa stránky (bez parametrov). */
function yc_current_url() {
	$pg = get_query_var('yc_page');
	if ($pg) return home_url('/' . $pg . '/');
	if (get_query_var('yc_thanks')) return home_url('/dakujeme/');
	return home_url('/');
}

/* Hlavná adresa, obrázky a popisy pre zdieľanie, údaje o firme pre Google. */
add_action('wp_head', function () {
	if (is_404() || is_search()) return;
	$pages = yc_pages(); $pg = get_query_var('yc_page');
	$title = wp_get_document_title();
	$desc = ($pg && isset($pages[$pg])) ? $pages[$pg][1] : 'Odborné a úradné preklady pre firmy v 25 jazykoch. Zmluvy, technická dokumentácia, weby a dokumenty pre zamestnancov. Cenová ponuka do 24 hodín.';
	$url = yc_current_url();
	$img = get_template_directory_uri() . '/og.jpg';
	echo '<link rel="canonical" href="' . esc_url($url) . '">' . "\n";
	foreach (['og:type' => 'website', 'og:site_name' => 'Your Choice – preklady pre firmy', 'og:locale' => yc_is_en() ? 'en_GB' : 'sk_SK', 'og:title' => $title, 'og:description' => $desc, 'og:url' => $url, 'og:image' => $img, 'og:image:width' => '1200', 'og:image:height' => '627', 'og:image:alt' => 'Your Choice – preklady pre firmy'] as $k => $v) {
		echo '<meta property="' . esc_attr($k) . '" content="' . esc_attr($v) . '">' . "\n";
	}
	$LL = yc_landing();
	if ($pg && isset($LL[$pg]['sk'])) { echo '<link rel="alternate" hreflang="en" href="' . esc_url($url) . '">' . "\n" . '<link rel="alternate" hreflang="sk" href="' . esc_url(home_url('/' . $LL[$pg]['sk'] . '/')) . '">' . "\n"; }
	foreach ($LL as $es => $ex) if (isset($ex['sk']) && $ex['sk'] === $pg) { echo '<link rel="alternate" hreflang="sk" href="' . esc_url($url) . '">' . "\n" . '<link rel="alternate" hreflang="en" href="' . esc_url(home_url('/' . $es . '/')) . '">' . "\n"; }
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

	$org = [
		'@context' => 'https://schema.org',
		'@type' => 'ProfessionalService',
		'@id' => home_url('/#firma'),
		'name' => 'Your Choice – jazykové služby, s.r.o.',
		'alternateName' => 'Your Choice',
		'description' => 'Odborné a úradné preklady pre firmy v 25 jazykoch.',
		'url' => home_url('/'),
		'logo' => get_template_directory_uri() . '/favicon-32.png',
		'image' => $img,
		'telephone' => '+421915343261',
		'email' => yc_email(),
		'priceRange' => 'od 18,50 € / normostrana bez DPH',
		'address' => ['@type' => 'PostalAddress', 'streetAddress' => 'Jazdecká 8686/65', 'addressLocality' => 'Trnava', 'postalCode' => '917 08', 'addressCountry' => 'SK'],
		'areaServed' => ['@type' => 'Country', 'name' => 'Slovensko'],
		'openingHoursSpecification' => [['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], 'opens' => '08:00', 'closes' => '18:00']],
		'taxID' => '2120212391', 'vatID' => 'SK2120212391', 'identifier' => 'IČO 50208055',
		'knowsLanguage' => ['sk', 'en', 'de', 'hu', 'cs', 'pl', 'uk', 'ro', 'fr', 'it', 'es', 'ru'],
	];
	if ($pg === 'sluzby' || yc_is_home()) {
		$org['hasOfferCatalog'] = ['@type' => 'OfferCatalog', 'name' => 'Prekladové služby', 'itemListElement' => [
			['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Odborný preklad'], 'priceSpecification' => ['@type' => 'UnitPriceSpecification', 'price' => '18.50', 'priceCurrency' => 'EUR', 'unitText' => 'normostrana', 'valueAddedTaxIncluded' => false]],
			['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Úradný preklad'], 'priceSpecification' => ['@type' => 'UnitPriceSpecification', 'price' => '25.00', 'priceCurrency' => 'EUR', 'unitText' => 'normostrana', 'valueAddedTaxIncluded' => false]],
		]];
	}
	$org = apply_filters('yc_org_schema', $org);
	echo '<script type="application/ld+json">' . wp_json_encode($org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}, 5);

/* Titulok domovskej stránky s mestom. */
add_filter('pre_get_document_title', function ($t) {
	if (yc_is_home()) return 'Preklady pre firmy Trnava – odborné a úradné | Your Choice';
	return $t;
}, 20);

/* Ukážkový obsah WordPressu preč (raz). */
add_action('init', function () {
	if (get_option('yc_demo_cleaned')) return;
	foreach (['hello-world' => 'post', 'sample-page' => 'page', 'vzorova-stranka' => 'page', 'ahoj-svet' => 'post'] as $slug => $type) {
		$p = get_page_by_path($slug, OBJECT, $type);
		if ($p) wp_delete_post($p->ID, true);
	}
	$c = get_comments(['number' => 50]);
	foreach ($c as $cm) if ($cm->comment_author === 'A WordPress Commenter' || $cm->comment_author === 'Komentátor WordPressu') wp_delete_comment($cm->comment_ID, true);
	update_option('yc_demo_cleaned', 1);
});
/* Web nemá blog: RSS a archívy vypnuté. */
add_action('template_redirect', function () {
	if (is_feed() || is_author() || is_date() || is_category() || is_tag() || is_attachment()) { wp_safe_redirect(home_url('/'), 301); exit; }
});
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

/* Priložené súbory z dopytov: zákaz priameho prístupu a mazanie po 90 dňoch. */
function yc_dopyty_dir() { $u = wp_upload_dir(); return $u['basedir'] . '/yc-dopyty'; }
add_action('init', function () {
	$d = yc_dopyty_dir();
	if (is_dir($d)) {
		$g = $d . '/.htaccess';
		$want = "Options -Indexes\n<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n";
		if (!file_exists($g) || file_get_contents($g) !== $want) @file_put_contents($g, $want);
	}
	if (!wp_next_scheduled('yc_dopyty_cleanup')) wp_schedule_event(time() + 600, 'daily', 'yc_dopyty_cleanup');
});
add_action('yc_dopyty_cleanup', function () {
	$d = yc_dopyty_dir();
	if (!is_dir($d)) return;
	$limit = time() - 90 * DAY_IN_SECONDS;
	foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($d, FilesystemIterator::SKIP_DOTS)) as $f) {
		if ($f->isFile() && $f->getFilename() !== '.htaccess' && $f->getMTime() < $limit) @unlink($f->getPathname());
	}
});

/* Odosielateľ e-mailov z webu. */
add_filter('wp_mail_from_name', function () { return 'Your Choice – web'; });

/* Anglické stránky: jazyk dokumentu. */
add_filter('language_attributes', function ($o) { return yc_is_en() ? 'lang="en-GB"' : $o; });
