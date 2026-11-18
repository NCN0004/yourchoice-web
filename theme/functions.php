<?php
/* Your Choice – téma. Formuláre, merania a ďakovacia stránka. */
if (!defined('ABSPATH')) exit;

define('YC_GA4', 'G-XBMSY87KV3');

add_action('after_setup_theme', function () {
	add_theme_support('title-tag');
	add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
});

function yc_is_home() {
	return is_front_page() && !get_query_var('yc_page') && !get_query_var('yc_thanks');
}
function yc_anchor($id) {
	return esc_url((yc_is_home() ? '' : home_url('/')) . '#' . $id);
}
function yc_landing() {
	static $l = null;
	if ($l === null) $l = array_merge(include get_template_directory() . '/landing-data.php', include get_template_directory() . '/landing-en.php');
	return $l;
}
function yc_pages() {
	$p = [
		'sluzby' => ['Prekladové služby pre firmy', 'Odborné a úradné preklady, lokalizácia webov, dokumenty pre zamestnancov, technická dokumentácia a AI preklad s kontrolou. Cenová ponuka do 24 hodín.'],
		'cennik' => ['Cenník prekladov pre firmy 2026', 'Cenník odborných a úradných prekladov pre firmy: od 18,50 € za normostranu, úradný preklad od 25 €. Zľavy pri objeme, expres do 24 hodín, príklady výpočtu.'],
		'referencie' => ['Referencie a skúsenosti klientov', 'Čo hovoria klienti o prekladoch Your Choice: Arcadis, Slovenská pošta, ELPAM real. Odborné a úradné preklady pre firmy, 22 rokov praxe.'],
		'kontakt' => ['Kontakt a fakturačné údaje', 'Kontakt na Your Choice – jazykové služby, s.r.o., Trnava. Telefón, e-mail a fakturačné údaje.'],
		'ochrana-osobnych-udajov' => ['Ochrana osobných údajov', 'Ako Your Choice – jazykové služby, s.r.o. spracúva osobné údaje.'],
		'cookies' => ['Cookies', 'Aké cookies používa web yourchoice.sk a ako zmeniť svoj súhlas.'],
		'obchodne-podmienky' => ['Všeobecné obchodné podmienky', 'Všeobecné obchodné podmienky Your Choice – jazykové služby, s.r.o.'],
	];
	$p['novinky'] = ['Odborné novinky pre firmy', 'Praktické články o prekladoch pre firmy: anglické zmluvy, dokumenty pre zahraničných zamestnancov, AI a strojový preklad.'];
	foreach (yc_articles() as $s => $a) $p[$s] = [$a['t'], $a['d']];
	$p['en'] = ['Translation services for companies in Slovakia', 'Specialised and certified translations to and from Slovak for companies: contracts, technical documentation, HR documents, websites. 25 languages, quote within 24 hours.'];
	foreach (yc_landing() as $s => $x) $p[$s] = [$x['t'], $x['d']];
	return $p;
}
function yc_is_en() { $pg = (string) get_query_var('yc_page'); return $pg === 'en' || strpos($pg, 'en/') === 0; }
function yc_email() { return yc_notify_email(); }
function yc_articles() {
	return [
		'anglicka-zmluva-5-viet' => ['cat' => 'ZMLUVY', 'min' => 6, 't' => '5 viet v anglickej zmluve, na ktorých sa firmy najčastejšie potknú', 'd' => 'Shall, best efforts, indemnify, consequential damages a including without limitation: čo znamenajú a ako ich správne preložiť.'],
		'dokumenty-pre-zahranicnych-zamestnancov' => ['cat' => 'HR', 'min' => 5, 't' => 'Zamestnávate cudzincov? Ktoré dokumenty im dať v ich jazyku', 'd' => 'BOZP, pracovná zmluva, pracovný poriadok a postupy: čo hovorí zákon a ktoré dokumenty preložiť zahraničným zamestnancom.'],
		'kolko-stoji-preklad' => ['cat' => 'CENY', 'min' => 5, 't' => 'Koľko stojí preklad pre firmu v roku 2026', 'd' => 'Ceny odborného a úradného prekladu za normostranu, čo cenu zvyšuje a ako ušetriť. S príkladmi výpočtu pre zmluvu, manuál aj výročnú správu.'],
		'strojovy-preklad-kedy-staci' => ['cat' => 'AI A PREKLAD', 'min' => 4, 't' => 'Kedy stačí strojový preklad a kedy firmu vyjde draho', 'd' => 'Kedy použiť AI a strojový preklad, kedy je potrebný človek a prečo zmluvy, úradné texty a marketing nepatria do bezplatného prekladača.'],
	];
}

add_action('wp_enqueue_scripts', function () {
	$v = wp_get_theme()->get('Version');
	wp_enqueue_style('yc-fonts', 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Inter:wght@400;500;600;700&display=swap', [], null);
	wp_enqueue_style('yc-style', get_stylesheet_uri(), [], $v);
	wp_enqueue_script('yc-site', get_template_directory_uri() . '/site.js', [], $v, true);
	wp_localize_script('yc-site', 'YC_CFG', ['ga' => YC_GA4, 'cookies' => home_url('/cookies/')]);
	wp_localize_script('yc-site', 'YC_DOCS', json_decode(file_get_contents(get_template_directory() . '/docs.json'), true));
});

add_action('wp_head', function () {
	$u = get_template_directory_uri();
	echo '<link rel="icon" href="' . esc_url($u . '/favicon.svg') . '" type="image/svg+xml">' . "\n";
	echo '<link rel="icon" href="' . esc_url($u . '/favicon-32.png') . '" sizes="32x32">' . "\n";

	echo '<meta name="theme-color" content="#14213D">' . "\n";
	$pg = get_query_var('yc_page'); $pages = yc_pages();
	if ($pg && isset($pages[$pg])) {
		echo '<meta name="description" content="' . esc_attr($pages[$pg][1]) . '">' . "
";
	} elseif (yc_is_home()) {
		echo '<meta name="description" content="Odborné a úradné preklady pre firmy v 25 jazykoch. Zmluvy, technická dokumentácia, weby a dokumenty pre zamestnancov. Cenová ponuka do 24 hodín.">' . "
";
	}
}, 1);

/* Title for the homepage */
add_filter('pre_get_document_title', function ($t) {
	$pg = get_query_var('yc_page'); $pages = yc_pages();
	if ($pg && isset($pages[$pg])) return $pages[$pg][0] . ' | Your Choice';
	if (get_query_var('yc_thanks')) return 'Ďakujeme | Your Choice';
	if (is_front_page()) return 'Preklady pre firmy – odborné a úradné | Your Choice';
	return $t;
});

/* Thank-you page /dakujeme/ */
add_action('init', function () {
	add_rewrite_rule('^dakujeme/?$', 'index.php?yc_thanks=1', 'top');
	add_rewrite_rule('^(' . implode('|', array_keys(yc_pages())) . ')/?$', 'index.php?yc_page=$matches[1]', 'top');
	register_post_type('yc_dopyt', [
		'labels' => ['name' => 'Dopyty', 'singular_name' => 'Dopyt'],
		'public' => false, 'show_ui' => true, 'menu_icon' => 'dashicons-email-alt',
		'supports' => ['title', 'editor'], 'capability_type' => 'post',
	]);
});
add_filter('query_vars', function ($v) { $v[] = 'yc_thanks'; $v[] = 'yc_page'; return $v; });
add_action('init', function () {
	$v = wp_get_theme()->get('Version');
	if (get_option('yc_rules_ver') !== $v) { flush_rewrite_rules(); update_option('yc_rules_ver', $v); }
}, 20);
add_action('after_switch_theme', function () { flush_rewrite_rules(); });
add_filter('template_include', function ($t) {
	if (get_query_var('yc_thanks')) return get_template_directory() . '/dakujeme.php';
	$pg = get_query_var('yc_page');
	if ($pg && isset(yc_pages()[$pg])) return get_template_directory() . '/page-static.php';
	return $t;
});

/* Settings: where inquiries go */
function yc_notify_email() {
	$e = get_option('yc_notify_email');
	return is_email($e) ? $e : 'hello@yourchoice.sk';
}
add_action('admin_init', function () {
	register_setting('general', 'yc_notify_email', ['sanitize_callback' => 'sanitize_email']);
	add_settings_field('yc_notify_email', 'E-mail pre dopyty z webu', function () {
		echo '<input type="email" name="yc_notify_email" class="regular-text" value="' . esc_attr(yc_notify_email()) . '">';
	}, 'general');
});

/* Form handler */
add_action('admin_post_nopriv_yc_form', 'yc_handle_form');
add_action('admin_post_yc_form', 'yc_handle_form');
function yc_handle_form() {
	$kinds = ['rychly' => 'Rýchly dopyt', 'cennik' => 'Žiadosť o cenník', 'zavolajte' => 'Zavolajte mi', 'kontakt' => 'Dopyt z kontaktného formulára'];
	$kind = isset($_POST['yc_kind'], $kinds[$_POST['yc_kind']]) ? $_POST['yc_kind'] : 'kontakt';
	$back = wp_get_referer() ?: home_url('/');
	// spam protection: honeypot + JS token
	if (!empty($_POST['yc_web']) || ($_POST['yc_js'] ?? '') !== 'ok') { wp_safe_redirect(home_url('/dakujeme/?typ=' . $kind)); exit; }
	$f = [];
	foreach (['meno', 'firma', 'email', 'telefon', 'jazyk', 'jazyky', 'sluzba', 'termin'] as $k) {
		if (isset($_POST[$k])) $f[$k] = sanitize_text_field(wp_unslash($_POST[$k]));
	}
	if (isset($_POST['sprava'])) $f['sprava'] = sanitize_textarea_field(wp_unslash($_POST['sprava']));
	if (isset($f['email']) && !is_email($f['email'])) { wp_safe_redirect(add_query_arg('yc_chyba', 'email', $back) . '#kontakt'); exit; }
	if ($kind === 'zavolajte' && empty($f['telefon'])) { wp_safe_redirect(add_query_arg('yc_chyba', 'telefon', $back) . '#kontakt'); exit; }

	// uploads
	$files = []; $paths = [];
	if (!empty($_FILES['subory']['name'][0])) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$allowed = ['pdf' => 'application/pdf', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'xls' => 'application/vnd.ms-excel', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'ppt' => 'application/vnd.ms-powerpoint', 'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'txt' => 'text/plain', 'rtf' => 'application/rtf', 'odt' => 'application/vnd.oasis.opendocument.text', 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png', 'zip' => 'application/zip', 'idml' => 'application/octet-stream', 'indd' => 'application/octet-stream'];
		$dir_filter = function ($d) { $d['subdir'] = '/yc-dopyty/' . date('Y/m'); $d['path'] = $d['basedir'] . $d['subdir']; $d['url'] = $d['baseurl'] . $d['subdir']; return $d; };
		add_filter('upload_dir', $dir_filter);
		$n = min(count($_FILES['subory']['name']), 10);
		for ($i = 0; $i < $n; $i++) {
			if ($_FILES['subory']['error'][$i] !== UPLOAD_ERR_OK || $_FILES['subory']['size'][$i] > 25 * 1024 * 1024) continue;
			$one = ['name' => $_FILES['subory']['name'][$i], 'type' => $_FILES['subory']['type'][$i], 'tmp_name' => $_FILES['subory']['tmp_name'][$i], 'error' => 0, 'size' => $_FILES['subory']['size'][$i]];
			$r = wp_handle_upload($one, ['test_form' => false, 'mimes' => $allowed]);
			if (empty($r['error'])) { $files[] = $r['url']; $paths[] = $r['file']; }
		}
		remove_filter('upload_dir', $dir_filter);
		$up = wp_upload_dir();
		$guard = $up['basedir'] . '/yc-dopyty/.htaccess';
		if (!file_exists($guard)) @file_put_contents($guard, "Options -Indexes\n");
	}

	$labels = ['meno' => 'Meno', 'firma' => 'Firma', 'email' => 'E-mail', 'telefon' => 'Telefón', 'jazyk' => 'Jazyk', 'jazyky' => 'Jazyky', 'sluzba' => 'Služba', 'termin' => 'Termín', 'sprava' => 'Správa'];
	$lines = [];
	foreach ($labels as $k => $l) if (!empty($f[$k])) $lines[] = $l . ': ' . $f[$k];
	if ($files) $lines[] = 'Súbory: ' . count($files) . ' (v prílohe e-mailu aj v administrácii → Dopyty)';
	$lines[] = 'Stránka: ' . esc_url_raw($back);
	$body = implode("\n", $lines);
	$who = $f['firma'] ?? ($f['email'] ?? ($f['telefon'] ?? ''));
	$subject = $kinds[$kind] . ($who ? ' – ' . $who : '');

	$post_id = wp_insert_post(['post_type' => 'yc_dopyt', 'post_status' => 'private', 'post_title' => $subject, 'post_content' => $body . ($files ? "\n\n" . implode("\n", $files) : '')]);
	if ($post_id) update_post_meta($post_id, 'yc_kind', $kind);

	$nr = 'YC-' . date('Y') . '-' . str_pad((string) ($post_id ?: wp_rand(1000, 9999)), 4, '0', STR_PAD_LEFT);
	if ($post_id) { update_post_meta($post_id, 'yc_nr', $nr); wp_update_post(['ID' => $post_id, 'post_title' => $nr . ' · ' . $subject]); }
	$headers = ['Content-Type: text/html; charset=UTF-8'];
	if (!empty($f['email'])) $headers[] = 'Reply-To: ' . (!empty($f['meno']) ? $f['meno'] . ' ' : '') . '<' . $f['email'] . '>';
	wp_mail(yc_notify_email(), '[yourchoice.sk] ' . $nr . ' · ' . $subject, yc_mail_team($kinds[$kind], $f, $nr, count($files), esc_url_raw($back), admin_url('edit.php?post_type=yc_dopyt')), $headers, $paths);

	if (!empty($f['email']) && $kind !== 'zavolajte') {
		list($csubj, $chtml) = yc_mail_customer($kind, $f, $nr, count($files));
		wp_mail($f['email'], $csubj, $chtml, ['Content-Type: text/html; charset=UTF-8', 'Reply-To: Your Choice <' . yc_notify_email() . '>']);
	}
	wp_safe_redirect(home_url('/dakujeme/?typ=' . $kind));
	exit;
}

/* Show inquiry list nicely in admin */
add_filter('manage_yc_dopyt_posts_columns', function ($c) { return ['cb' => $c['cb'], 'title' => 'Dopyt', 'date' => 'Prijaté']; });
require_once __DIR__ . '/inc-extra.php';
require_once __DIR__ . '/inc-seo.php';
require_once __DIR__ . '/inc-mail.php';
