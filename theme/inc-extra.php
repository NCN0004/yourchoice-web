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

/* Jedna oficiálna adresa webu: https://www.yourchoice.sk (presmeruje yourchoice.sk aj novy.yourchoice.sk). */
define('YC_CANONICAL_HOST', 'www.yourchoice.sk');
add_action('init', function () {
	$h = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
	if ($h === '' || $h === YC_CANONICAL_HOST || substr($h, -13) !== 'yourchoice.sk') return;
	if (isset($_GET['yc_deploy']) || wp_doing_cron() || is_admin() || (defined('WP_CLI') && WP_CLI)) return;
	if (strpos((string) ($_SERVER['REQUEST_URI'] ?? ''), 'wp-login.php') !== false) return;
	wp_redirect('https://' . YC_CANONICAL_HOST . ($_SERVER['REQUEST_URI'] ?? '/'), 301, 'Your Choice');
	exit;
}, 0);
foreach (['option_home', 'option_siteurl'] as $yc_opt) add_filter($yc_opt, function ($v) {
	$h = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
	return ($h === YC_CANONICAL_HOST) ? 'https://' . YC_CANONICAL_HOST : $v;
}, 99);

/* Odosielanie e-mailov z webu cez schránku web@yourchoice.sk u M7 (Platon).
   Heslo zadá správca v administrácii: Nastavenia → E-mail webu. Do kódu sa heslo nikdy neukladá. */
function yc_smtp_conf() {
	return [
		'host' => defined('YC_SMTP_HOST') ? YC_SMTP_HOST : 'smtps.platon.sk',
		'port' => defined('YC_SMTP_PORT') ? (int) YC_SMTP_PORT : 465,
		'secure' => defined('YC_SMTP_SECURE') ? YC_SMTP_SECURE : 'ssl',
		'user' => defined('YC_SMTP_USER') ? YC_SMTP_USER : 'web@yourchoice.sk',
		'pass' => defined('YC_SMTP_PASS') ? YC_SMTP_PASS : (string) get_option('yc_smtp_pass', ''),
	];
}
add_action('phpmailer_init', function ($m) {
	$c = yc_smtp_conf();
	if ($c['pass'] === '') return;
	$m->isSMTP();
	$m->Host = $c['host'];
	$m->SMTPAuth = true;
	$m->Username = $c['user'];
	$m->Password = $c['pass'];
	$m->Port = $c['port'];
	$m->SMTPSecure = $c['secure'];
	$m->setFrom($c['user'], 'Your Choice – web', false);
	$m->Sender = $c['user'];
});
add_action('wp_mail_failed', function ($e) { update_option('yc_smtp_last_error', current_time('mysql') . ' – ' . $e->get_error_message(), false); });
add_action('admin_menu', function () {
	add_options_page('E-mail webu', 'E-mail webu', 'manage_options', 'yc-email', function () {
		$c = yc_smtp_conf();
		$msg = '';
		if (isset($_POST['yc_smtp_nonce']) && wp_verify_nonce($_POST['yc_smtp_nonce'], 'yc_smtp')) {
			if (!empty($_POST['yc_smtp_pass'])) { update_option('yc_smtp_pass', wp_unslash($_POST['yc_smtp_pass']), false); $msg = 'Heslo je uložené.'; }
			if (!empty($_POST['yc_smtp_test'])) {
				delete_option('yc_smtp_last_error');
				$ok = wp_mail(yc_notify_email(), '[yourchoice.sk] Test odosielania z webu', "Toto je testovací e-mail z webu www.yourchoice.sk.\nAk ho čítate, odosielanie cez schránku web@yourchoice.sk funguje.\n\nOdoslané: " . current_time('d. m. Y H:i'));
				$msg = $ok ? 'Testovací e-mail bol odoslaný na ' . esc_html(yc_notify_email()) . '. Skontrolujte schránku (aj spam).' : 'Odoslanie zlyhalo: ' . esc_html((string) get_option('yc_smtp_last_error'));
			}
			$c = yc_smtp_conf();
		}
		echo '<div class="wrap"><h1>E-mail webu</h1>';
		if ($msg) echo '<div class="notice notice-info"><p>' . $msg . '</p></div>';
		echo '<p>Web posiela dopyty a potvrdenia cez schránku <b>' . esc_html($c['user']) . '</b> na serveri ' . esc_html($c['host']) . ' (port ' . (int) $c['port'] . ', ' . esc_html(strtoupper($c['secure'])) . ').</p>';
		echo '<p>Stav: ' . ($c['pass'] !== '' ? '<b style="color:#2E9E6A">heslo je nastavené</b>' : '<b style="color:#B45309">heslo zatiaľ chýba – web posiela náhradným spôsobom</b>') . '</p>';
		$err = get_option('yc_smtp_last_error'); if ($err) echo '<p>Posledná chyba: ' . esc_html($err) . '</p>';
		echo '<form method="post">'; wp_nonce_field('yc_smtp', 'yc_smtp_nonce');
		echo '<p><label>Heslo k schránke ' . esc_html($c['user']) . '<br><input type="password" name="yc_smtp_pass" autocomplete="new-password" style="width:320px"></label></p>';
		echo '<p><button class="button button-primary" name="yc_smtp_save" value="1">Uložiť heslo</button> <button class="button" name="yc_smtp_test" value="1">Poslať testovací e-mail</button></p></form></div>';
	});
});
