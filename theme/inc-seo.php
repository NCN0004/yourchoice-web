<?php
/* Your Choice – SEO a GEO: indexovanie, mapa stránok, llms.txt, štruktúrované údaje, meranie. */
if (!defined('ABSPATH')) exit;

/* Dočasná adresa (novy.yourchoice.sk) sa neindexuje. Ostrý web (www.yourchoice.sk) áno – automaticky po prepnutí domény. */
function yc_is_staging() {
	$h = (string) parse_url(home_url(), PHP_URL_HOST);
	return strpos($h, 'novy.') === 0 || strpos($h, 'staging.') === 0;
}

/* Otázky na domovskej stránke (zobrazenie aj údaje pre Google a AI). */
function yc_home_faq() {
	return [
		['Ako sa počíta cena prekladu?', 'Podľa normostrany (1 800 znakov vrátane medzier), jazyka a odboru. Odborné preklady od 18,50 €/NS, úradné od 25 €/NS (ceny bez DPH). Za odborný text si nič nepripočítavame. Od 10 normostrán máte zľavu 10 %, od 30 normostrán 15 %, pri rámcovej zmluve ešte nižšiu cenu.'],
		['Aký je rozdiel medzi odborným a úradným prekladom?', 'Úradný preklad je zviazaný s dokumentom a má pečiatku úradného prekladateľa – vyžadujú ho súdy, úrady a obchodný register. Odborný preklad je na bežné firemné použitie a dá sa dodať elektronicky.'],
		['Ako rýchlo to stihnete?', 'Presný termín dostanete spolu s cenou do 24 hodín. Väčšie zákazky rozdelíme medzi viacerých prekladateľov so spoločným glosárom, aby text pôsobil jednotne. Štandardný termín je bez príplatku, expres do 24 hodín (do 5 normostrán) je za príplatok 50 %.'],
		['Sú naše dokumenty v bezpečí?', 'Áno. Podpíšeme NDA, súbory prenášame šifrovane a zo servera ich automaticky vymažeme najneskôr do 90 dní. Dôverné texty nikdy nevkladáme do verejných prekladačov.'],
	];
}

/* Profily firmy na iných miestach (pre Google a AI: „toto je tá istá firma“). */
function yc_same_as() {
	return [
		'https://www.facebook.com/yourchoice.sk',
		'https://www.instagram.com/yourchoice.sk',
		'https://www.linkedin.com/company/145453598/',
	];
}

/* Typ stránky pre prehľady v Google Analytics. */
function yc_page_group() {
	if (yc_is_home()) return 'domov';
	if (get_query_var('yc_thanks')) return 'dakujeme';
	$pg = (string) get_query_var('yc_page');
	if ($pg === '') return is_404() ? '404' : 'ine';
	$L = yc_landing();
	if (isset($L[$pg])) return ['o' => 'odvetvie', 'd' => 'dokument', 'j' => 'jazyk', 'l' => 'lokalita', 'eo' => 'en-odvetvie'][$L[$pg]['g']] ?? 'vstupna';
	if (isset(yc_articles()[$pg])) return 'clanok';
	if ($pg === 'en') return 'en-prehlad';
	return $pg;
}

/* Indexovanie: dočasná adresa nie, ostrý web áno; vyhľadávanie, 404 a ďakovacia stránka nie. */
add_filter('wp_robots', function ($r) {
	if (yc_is_staging()) return ['noindex' => true, 'nofollow' => true];
	unset($r['noindex'], $r['nofollow']);
	if (is_search() || is_404() || get_query_var('yc_thanks')) return ['noindex' => true, 'follow' => true];
	return ['index' => true, 'follow' => true, 'max-image-preview' => 'large', 'max-snippet' => -1];
}, 99);

/* robots.txt: mapa stránok, AI vyhľadávače vítané. */
add_filter('robots_txt', function ($out) {
	$out = "User-agent: *\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\nDisallow: /?s=\nDisallow: /dakujeme/\n";
	$out .= "\n# AI asistenti a vyhľadávače môžu čítať verejný obsah\nUser-agent: GPTBot\nAllow: /\nUser-agent: OAI-SearchBot\nAllow: /\nUser-agent: ClaudeBot\nAllow: /\nUser-agent: PerplexityBot\nAllow: /\nUser-agent: Google-Extended\nAllow: /\n";
	$out .= "\nSitemap: " . home_url('/sitemap.xml') . "\n";
	return $out;
}, 99);

/* Adresy /sitemap.xml a /llms.txt */
add_action('init', function () {
	add_rewrite_rule('^sitemap\.xml$', 'index.php?yc_file=sitemap', 'top');
	add_rewrite_rule('^llms\.txt$', 'index.php?yc_file=llms', 'top');
});
add_filter('query_vars', function ($v) { $v[] = 'yc_file'; return $v; });
add_filter('wp_sitemaps_enabled', '__return_false');
add_filter('redirect_canonical', function ($u) { return get_query_var('yc_file') ? false : $u; });

function yc_public_pages() {
	$skip = ['dakujeme'];
	$out = ['' => 1.0];
	foreach (yc_pages() as $s => $p) {
		if (in_array($s, $skip, true)) continue;
		$pr = 0.6;
		if (in_array($s, ['sluzby', 'cennik', 'kontakt'], true)) $pr = 0.9;
		elseif (isset(yc_landing()[$s])) $pr = 0.8;
		elseif (isset(yc_articles()[$s])) $pr = 0.7;
		elseif (in_array($s, ['ochrana-osobnych-udajov', 'cookies', 'obchodne-podmienky'], true)) $pr = 0.2;
		$out[$s] = $pr;
	}
	return $out;
}

add_action('template_redirect', function () {
	$f = get_query_var('yc_file');
	if (!$f) return;
	$mod = gmdate('Y-m-d', max(filemtime(__FILE__), filemtime(get_template_directory() . '/landing-data.php'), filemtime(get_template_directory() . '/parts/front.php')));
	if ($f === 'sitemap') {
		status_header(200);
		header('Content-Type: application/xml; charset=UTF-8');
		header('X-Robots-Tag: noindex');
		$L = yc_landing();
		$pairs = [];
		foreach ($L as $s => $x) if (!empty($x['sk'])) { $pairs[$s] = $x['sk']; $pairs[$x['sk']] = $s; }
		echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
		foreach (yc_public_pages() as $s => $pr) {
			$loc = home_url($s === '' ? '/' : '/' . $s . '/');
			echo "<url><loc>" . esc_url($loc) . "</loc><lastmod>$mod</lastmod><priority>" . number_format($pr, 1) . "</priority>";
			if (isset($pairs[$s])) {
				$en = strpos($s, 'en/') === 0;
				echo '<xhtml:link rel="alternate" hreflang="' . ($en ? 'en' : 'sk') . '" href="' . esc_url($loc) . '"/>';
				echo '<xhtml:link rel="alternate" hreflang="' . ($en ? 'sk' : 'en') . '" href="' . esc_url(home_url('/' . $pairs[$s] . '/')) . '"/>';
			}
			echo "</url>\n";
		}
		echo '</urlset>';
		exit;
	}
	if ($f === 'llms') {
		status_header(200);
		header('Content-Type: text/plain; charset=UTF-8');
		$u = function ($s) { return home_url('/' . $s . '/'); };
		$t = "# Your Choice – preklady pre firmy\n\n";
		$t .= "> Your Choice – jazykové služby, s.r.o. je prekladateľská agentúra z Trnavy (Slovensko). Robí odborné a úradné preklady pre firmy, úrady a organizácie v 25 jazykoch, s 22-ročnou praxou. Cenovú ponuku posiela do 24 hodín v pracovné dni.\n\n";
		$t .= "## Kľúčové fakty\n\n";
		$t .= "- Odborný preklad: od 18,50 € za normostranu bez DPH (normostrana = 1 800 znakov vrátane medzier). Bez príplatku za odborný text.\n";
		$t .= "- Úradný preklad s pečiatkou úradného prekladateľa: od 25 € za normostranu bez DPH.\n";
		$t .= "- Zľavy: od 10 normostrán 10 %, od 30 normostrán 15 %. Expres do 24 hodín (do 5 normostrán): príplatok 50 %.\n";
		$t .= "- Každý preklad robí prekladateľ so skúsenosťou v odbore a kontroluje ho druhý lingvista.\n";
		$t .= "- Najčastejšie jazyky: angličtina, nemčina, maďarčina, poľština, čeština, ukrajinčina, rumunčina; spolu 25 jazykov.\n";
		$t .= "- Dôvernosť: NDA na požiadanie, súbory sa mažú najneskôr do 90 dní.\n";
		$t .= "- Pre súkromné osoby (rodné listy, vysvedčenia, doklady) odporúčame partnerský web uradnaprekladatelka.sk.\n";
		$t .= "- Kontakt: hello@yourchoice.sk, +421 915 343 261, Jazdecká 8686/65, Trnava. Pondelok – piatok 8:00 – 18:00.\n";
		$t .= "- Firma: Your Choice – jazykové služby, s.r.o., IČO 50208055.\n\n";
		$t .= "## Hlavné stránky\n\n";
		$t .= "- [Služby](" . $u('sluzby') . "): prehľad služieb a kalkulačka ceny\n";
		if (isset(yc_pages()['cennik'])) $t .= "- [Cenník prekladov](" . $u('cennik') . "): ceny, zľavy, príklady výpočtu\n";
		$t .= "- [Kontakt](" . $u('kontakt') . "): kontakt a fakturačné údaje\n\n";
		$groups = ['o' => 'Preklady podľa odvetvia', 'd' => 'Preklady podľa typu dokumentu', 'j' => 'Preklady podľa jazyka', 'l' => 'Lokálne', 'eo' => 'English pages'];
		foreach ($groups as $g => $name) {
			$rows = [];
			foreach (yc_landing() as $s => $x) if ($x['g'] === $g) $rows[] = '- [' . $x['t'] . '](' . $u($s) . '): ' . $x['d'];
			if ($rows) $t .= "## $name\n\n" . implode("\n", $rows) . "\n\n";
		}
		$t .= "## Odborné články\n\n";
		foreach (yc_articles() as $s => $a) $t .= '- [' . $a['t'] . '](' . $u($s) . '): ' . $a['d'] . "\n";
		echo $t;
		exit;
	}
}, 1);

/* Štruktúrované údaje: drobčeky, služba, otázky, profily firmy. */
add_action('wp_head', function () {
	if (is_404() || is_search()) return;
	$pg = (string) get_query_var('yc_page');
	$pages = yc_pages();
	$graph = [];
	if (yc_is_home()) {
		$graph[] = ['@type' => 'WebSite', '@id' => home_url('/#web'), 'url' => home_url('/'), 'name' => 'Your Choice – preklady pre firmy', 'inLanguage' => 'sk', 'publisher' => ['@id' => home_url('/#firma')]];
		$graph[] = ['@type' => 'FAQPage', 'mainEntity' => array_map(function ($f) { return ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]]; }, yc_home_faq())];
	}
	if ($pg && isset($pages[$pg])) {
		$en = function_exists('yc_is_en') && yc_is_en();
		$crumbs = [['@type' => 'ListItem', 'position' => 1, 'name' => $en ? 'Home' : 'Domov', 'item' => home_url('/')]];
		$L = yc_landing();
		if (isset($L[$pg]) && !$en) $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => 'Služby', 'item' => home_url('/sluzby/')];
		if (isset(yc_articles()[$pg])) $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => 'Odborné novinky', 'item' => home_url('/novinky/')];
		if ($en && $pg !== 'en') $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => 'Translation services', 'item' => home_url('/en/')];
		$crumbs[] = ['@type' => 'ListItem', 'position' => count($crumbs) + 1, 'name' => $pages[$pg][0], 'item' => home_url('/' . $pg . '/')];
		$graph[] = ['@type' => 'BreadcrumbList', 'itemListElement' => $crumbs];
		if (isset($L[$pg])) {
			$x = $L[$pg];
			$graph[] = ['@type' => 'Service', 'name' => $x['t'], 'description' => $x['d'], 'serviceType' => $en ? 'Translation' : 'Prekladateľské služby', 'provider' => ['@id' => home_url('/#firma')], 'areaServed' => ['@type' => 'Country', 'name' => 'Slovakia'], 'url' => home_url('/' . $pg . '/'), 'inLanguage' => $en ? 'en' : 'sk',
				'offers' => ['@type' => 'Offer', 'priceCurrency' => 'EUR', 'price' => '18.50', 'priceSpecification' => ['@type' => 'UnitPriceSpecification', 'price' => '18.50', 'priceCurrency' => 'EUR', 'unitText' => $en ? 'standard page' : 'normostrana', 'valueAddedTaxIncluded' => false]]];
		}
	}
	if ($graph) echo '<script type="application/ld+json">' . wp_json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}, 6);

/* Doplnenie profilov firmy (sameAs) a mapy do hlavného záznamu o firme. */
add_filter('yc_org_schema', function ($org) {
	$org['sameAs'] = yc_same_as();
	$org['contactPoint'] = [['@type' => 'ContactPoint', 'telephone' => '+421915343261', 'email' => 'hello@yourchoice.sk', 'contactType' => 'customer service', 'availableLanguage' => ['sk', 'en']]];
	$org['slogan'] = 'Odborné a úradné preklady pre firmy';
	return $org;
});

/* Typ stránky pre Google Analytics. */
add_action('wp_enqueue_scripts', function () {
	wp_add_inline_script('yc-site', 'window.YC_CFG=window.YC_CFG||{};YC_CFG.group=' . wp_json_encode(yc_page_group()) . ';YC_CFG.lang=' . wp_json_encode(function_exists('yc_is_en') && yc_is_en() ? 'en' : 'sk') . ';', 'before');
}, 30);

/* Bezpečnosť: zoznam používateľov cez API len pre prihlásených. */
add_filter('rest_endpoints', function ($e) {
	if (!is_user_logged_in()) { unset($e['/wp/v2/users'], $e['/wp/v2/users/(?P<id>[\d]+)']); }
	return $e;
});

/* Referencie klientov (zobrazené aj na domovskej stránke). */
function yc_references() {
	return [
		['Pri preklade mesačných správ stavebného dozoru na rýchlostnej ceste R2 sme nezaznamenali žiadne výhrady zo strany klienta.', 'Matej Mažgut · Arcadis CZ'],
		['Nanajvýš kvalitná a promptná dodávka – od medzinárodných zmlúv a výročných správ až po detské listy pre medzinárodné súťaže.', 'Ján Kollár · Slovenská pošta'],
		['Preklady na vysokej úrovni, ústretovosť a vždy dodržaný termín.', 'Martina Nováková · ELPAM real'],
	];
}
