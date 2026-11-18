<?php
/* Your Choice – firemné HTML e-maily z webu (potvrdenie zákazníkovi a oznámenie o dopyte). */
if (!defined('ABSPATH')) exit;

function yc_mail_wrap($preheader, $inner, $sign = true) {
	$logo = get_template_directory_uri() . '/email-logo.png';
	$web = 'https://www.yourchoice.sk/';
	return '<!DOCTYPE html><html lang="sk"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Your Choice</title></head>'
	. '<body style="margin:0;padding:0;background:#F3EFE6;">'
	. '<div style="display:none;max-height:0;overflow:hidden;opacity:0;">' . esc_html($preheader) . '</div>'
	. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F3EFE6;"><tr><td align="center" style="padding:24px 12px;">'
	. '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;font-family:Arial,Helvetica,sans-serif;color:#14213D;">'
	. '<tr><td style="padding:28px 32px 8px;"><a href="' . $web . '"><img src="' . esc_url($logo) . '" width="220" alt="Your Choice – preklady pre firmy" style="display:block;border:0;width:220px;height:auto;"></a></td></tr>'
	. $inner
	. (!$sign ? '<tr><td style="height:12px;"></td></tr>' : '<tr><td style="padding:8px 32px 28px;font-size:15px;line-height:1.6;">'
	. '<p style="margin:0 0 6px;">S pozdravom</p>'
	. '<p style="margin:0;"><strong>Janka Kubičková</strong><br>projektová manažérka, Your Choice<br>'
	. 'tel. <a href="tel:+421915343261" style="color:#14213D;">+421 915 343 261</a> · <a href="mailto:hello@yourchoice.sk" style="color:#14213D;">hello@yourchoice.sk</a></p></td></tr>')
	. '<tr><td style="background:#14213D;border-radius:0 0 12px 12px;padding:16px 32px;font-size:12px;line-height:1.6;color:#D9D3C5;">'
	. 'Your Choice – jazykové služby, s.r.o. · Jazdecká 8686/65, 917 08 Trnava · IČO 50208055 · IČ DPH SK2120212391<br>'
	. 'Odborné a úradné preklady pre firmy · 25 jazykov · 22 rokov praxe · <a href="' . $web . '" style="color:#F59E1B;">www.yourchoice.sk</a></td></tr>'
	. '</table></td></tr></table></body></html>';
}

function yc_mail_rows($rows) {
	$h = '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#FAF8F4;border:1px solid #ECE7DC;border-radius:10px;">';
	foreach ($rows as $l => $v) {
		$h .= '<tr><td style="padding:9px 16px;font-size:13px;color:#6B7280;width:34%;vertical-align:top;border-bottom:1px solid #ECE7DC;">' . esc_html($l) . '</td>'
			. '<td style="padding:9px 16px;font-size:14px;color:#14213D;vertical-align:top;border-bottom:1px solid #ECE7DC;">' . nl2br(esc_html($v)) . '</td></tr>';
	}
	return $h . '</table>';
}

/* Potvrdenie zákazníkovi. */
function yc_mail_customer($kind, $f, $nr, $nfiles) {
	$name = trim((string) ($f['meno'] ?? ''));
	$hi = 'Dobrý deň' . ($name !== '' ? ', ' . esc_html($name) : '') . ',';
	if ($kind === 'cennik') {
		$title = 'Ďakujeme za záujem o náš cenník';
		$lead = 'Cenník prekladov pre firmy vám pošleme e-mailom do 24 hodín v pracovné dni. Nájdete v ňom ceny podľa jazykov a odborov, zľavy pri väčšom objeme a vzor rámcovej zmluvy.';
		$steps = [['Pošleme cenník', 'do 24 hodín na tento e-mail'], ['Radi poradíme', 'ak si nebudete istí, či potrebujete odborný alebo úradný preklad'], ['Bez záväzkov', 'cenník vás k ničomu nezaväzuje']];
		$subject = 'Cenník prekladov pre firmy – Your Choice';
	} else {
		$title = 'Váš dopyt sme prijali';
		$lead = 'Ďakujeme, že ste sa obrátili na Your Choice. Dokumenty si pozrieme a do 24 hodín v pracovné dni vám pošleme presnú cenu a termín. Ponuka je nezáväzná.';
		$steps = [['Posúdime dokument', 'jazyk, odbor, rozsah a formát'], ['Pošleme cenu a termín', 'do 24 hodín v pracovné dni'], ['Po vašom potvrdení prekladáme', 'odborník z odboru, kontrola druhým lingvistom']];
		$subject = 'Váš dopyt č. ' . $nr . ' sme prijali – Your Choice';
	}
	$rows = ['Číslo dopytu' => $nr];
	$map = ['firma' => 'Firma', 'jazyk' => 'Jazyk', 'jazyky' => 'Jazyky', 'sluzba' => 'Služba', 'termin' => 'Želaný termín', 'telefon' => 'Telefón', 'sprava' => 'Vaša správa'];
	foreach ($map as $k => $l) if (!empty($f[$k])) $rows[$l] = $k === 'sprava' ? wp_trim_words($f[$k], 60, ' …') : $f[$k];
	if ($nfiles) $rows['Priložené súbory'] = $nfiles . ($nfiles === 1 ? ' súbor' : ($nfiles < 5 ? ' súbory' : ' súborov'));
	$st = '';
	foreach ($steps as $i => $s) {
		$st .= '<tr><td style="width:34px;vertical-align:top;padding:0 0 12px;"><div style="width:26px;height:26px;border-radius:13px;background:#F59E1B;color:#14213D;font-weight:bold;font-size:14px;line-height:26px;text-align:center;">' . ($i + 1) . '</div></td>'
			. '<td style="vertical-align:top;padding:2px 0 12px;font-size:15px;line-height:1.5;"><strong>' . esc_html($s[0]) . '</strong><br><span style="color:#4A5468;">' . esc_html($s[1]) . '</span></td></tr>';
	}
	$inner = '<tr><td style="padding:16px 32px 0;font-size:16px;line-height:1.6;">'
		. '<h1 style="margin:0 0 14px;font-size:24px;line-height:1.25;">' . esc_html($title) . '</h1>'
		. '<p style="margin:0 0 14px;">' . $hi . '</p><p style="margin:0 0 18px;">' . esc_html($lead) . '</p></td></tr>'
		. '<tr><td style="padding:0 32px 18px;">' . yc_mail_rows($rows) . '</td></tr>'
		. '<tr><td style="padding:0 32px 8px;"><p style="margin:0 0 12px;font-size:16px;font-weight:bold;">Čo bude nasledovať</p>'
		. '<table role="presentation" width="100%" cellpadding="0" cellspacing="0">' . $st . '</table></td></tr>'
		. '<tr><td style="padding:4px 32px 18px;font-size:15px;line-height:1.6;color:#4A5468;">Chcete niečo doplniť alebo poslať ďalší dokument? Stačí odpovedať na tento e-mail. Ak je to súrne, zavolajte nám na <a href="tel:+421915343261" style="color:#14213D;">+421 915 343 261</a>.</td></tr>'
		. '<tr><td style="padding:0 32px 18px;"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #ECE7DC;"><tr><td style="padding-top:14px;font-size:13px;line-height:1.6;color:#6B7280;">'
		. '✓ dôvernosť a NDA na požiadanie &nbsp; ✓ súbory mažeme najneskôr do 90 dní &nbsp; ✓ odborný preklad od 18,50 € / NS, úradný od 25 € / NS (bez DPH)</td></tr></table></td></tr>';
	return [$subject, yc_mail_wrap($title . ' – odpovieme do 24 hodín.', $inner)];
}

/* Oznámenie pre tím (hello@). */
function yc_mail_team($kindlabel, $f, $nr, $nfiles, $back, $admin_url) {
	$rows = ['Číslo dopytu' => $nr, 'Typ' => $kindlabel];
	$map = ['meno' => 'Meno', 'firma' => 'Firma', 'email' => 'E-mail', 'telefon' => 'Telefón', 'jazyk' => 'Jazyk', 'jazyky' => 'Jazyky', 'sluzba' => 'Služba', 'termin' => 'Termín', 'sprava' => 'Správa'];
	foreach ($map as $k => $l) if (!empty($f[$k])) $rows[$l] = $f[$k];
	if ($nfiles) $rows['Súbory'] = $nfiles . ' (v prílohe tohto e-mailu)';
	$rows['Odoslané zo stránky'] = $back;
	$btn = '';
	if (!empty($f['email'])) $btn .= '<a href="mailto:' . esc_attr($f['email']) . '?subject=' . rawurlencode('Re: dopyt ' . $nr . ' – Your Choice') . '" style="display:inline-block;background:#F59E1B;color:#14213D;text-decoration:none;font-weight:bold;font-size:15px;padding:12px 22px;border-radius:8px;margin:0 8px 8px 0;">Odpovedať zákazníkovi</a>';
	if (!empty($f['telefon'])) $btn .= '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $f['telefon'])) . '" style="display:inline-block;background:#14213D;color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;padding:12px 22px;border-radius:8px;margin:0 8px 8px 0;">Zavolať</a>';
	$inner = '<tr><td style="padding:16px 32px 0;font-size:16px;line-height:1.6;">'
		. '<h1 style="margin:0 0 6px;font-size:22px;">Nový dopyt z webu</h1>'
		. '<p style="margin:0 0 16px;color:#4A5468;">Zákazník dostal automatické potvrdenie, že cenu a termín pošleme do 24 hodín v pracovné dni.</p></td></tr>'
		. '<tr><td style="padding:0 32px 16px;">' . yc_mail_rows($rows) . '</td></tr>'
		. '<tr><td style="padding:0 32px 10px;">' . $btn . '</td></tr>'
		. '<tr><td style="padding:0 32px 16px;font-size:13px;color:#6B7280;">Dopyt je uložený aj v administrácii webu: <a href="' . esc_url($admin_url) . '" style="color:#14213D;">Dopyty</a>.</td></tr>';
	return yc_mail_wrap('Nový dopyt ' . $nr . ' – ' . ($f['firma'] ?? $f['email'] ?? ''), $inner, false);
}

/* Textová verzia pre e-mailové programy bez HTML. */
add_action('phpmailer_init', function ($m) {
	if ($m->ContentType === 'text/html' && empty($m->AltBody)) {
		$t = preg_replace('/<(br|\/p|\/tr|\/h1)[^>]*>/i', "\n", $m->Body);
		$m->AltBody = trim(preg_replace("/\n{3,}/", "\n\n", html_entity_decode(wp_strip_all_tags($t), ENT_QUOTES, 'UTF-8')));
	}
}, 20);
