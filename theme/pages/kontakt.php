<?php $e = yc_email();
$ico = [
 'tel' => '<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.6a1 1 0 0 1-.25 1z"/>',
 'mail' => '<path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1zm9 7.2L4.4 7H19.6zM4 9.1V17h16V9.1l-8 5.5z"/>',
 'wa' => '<path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5.2 13.9c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7a11 11 0 0 1-4.4-3.9 5 5 0 0 1-1-2.7 2.9 2.9 0 0 1 .9-2.1 1 1 0 0 1 .7-.3h.5c.2 0 .4 0 .6.4l.8 1.9c.1.2.1.3 0 .5l-.4.6-.4.4c-.1.1-.3.3-.1.6a8 8 0 0 0 1.5 1.8 7 7 0 0 0 2.1 1.3c.3.1.4.1.6-.1l.8-1c.2-.2.4-.2.6-.1l1.8.9c.3.1.4.2.5.3a2.2 2.2 0 0 1-.4 1z"/>',
 'pin' => '<path d="M12 2a7 7 0 0 1 7 7c0 5.2-7 13-7 13S5 14.2 5 9a7 7 0 0 1 7-7zm0 4.5A2.5 2.5 0 1 0 12 11.5 2.5 2.5 0 0 0 12 6.5z"/>',
];
$svg = function ($k) use ($ico) { return '<svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" fill="currentColor">' . $ico[$k] . '</svg>'; };
$map = 'https://www.google.com/maps/search/?api=1&query=Your+Choice+jazykov%C3%A9+slu%C5%BEby+Jazdeck%C3%A1+8686%2F65+Trnava';
?>
<div class="yc-kq">
 <a class="yc-kq-i" href="tel:+421915343261"><span class="yc-kq-ic"><?php echo $svg('tel'); ?></span><span><small>Zavolajte</small><b>+421 915 343 261</b></span></a>
 <a class="yc-kq-i" href="mailto:<?php echo esc_attr($e); ?>"><span class="yc-kq-ic"><?php echo $svg('mail'); ?></span><span><small>Napíšte</small><b><?php echo esc_html($e); ?></b></span></a>
 <a class="yc-kq-i" href="https://wa.me/421915343261" target="_blank" rel="noopener"><span class="yc-kq-ic wa"><?php echo $svg('wa'); ?></span><span><small>WhatsApp</small><b>Napísať správu</b></span></a>
</div>

<div class="yc-k2">
 <section class="yc-kc">
  <p class="yc-kc-label">Váš kontakt</p>
  <div class="yc-kperson"><span class="yc-avatar">JK</span><div><b>Janka Kubičková</b><span>projektová manažérka</span></div></div>
  <p>Odpovedá na všetky dopyty a postará sa o celú zákazku od ponuky po odovzdanie prekladu. Netreba nič vysvetľovať dvakrát.</p>
  <p class="yc-kc-fast">Cenu a termín máte <b>do 24 hodín</b> v pracovné dni</p>
  <a class="yc-btn" href="<?php echo yc_anchor('kontakt'); ?>">Poslať dopyt cez formulár</a>
 </section>
 <section class="yc-kc">
  <p class="yc-kc-label">Kancelária v Trnave</p>
  <p class="yc-kaddr"><span class="yc-kq-ic"><?php echo $svg('pin'); ?></span><span><b>Jazdecká 8686/65</b><br>917 08 Trnava</span></p>
  <table class="yc-khours"><tr><th>Pondelok – piatok</th><td>8:00 – 18:00</td></tr><tr><th>Sobota – nedeľa</th><td>zatvorené</td></tr></table>
  <p class="yc-meta">Osobne len po dohode – pred návštevou nám prosím zavolajte.</p>
  <a class="yc-klink" href="<?php echo esc_url($map); ?>" target="_blank" rel="noopener">Ukázať na mape →</a>
 </section>
</div>

<h2>Úradný preklad: ako odovzdať originál</h2>
<p>Úradný preklad sa pevne spája s originálom alebo s úradne overenou kópiou, preto k nemu potrebujeme listinu.</p>
<ol class="yc-ksteps">
 <li><b>Pošlite sken</b><span>E-mailom alebo cez formulár. Do 24 hodín máte cenu a termín.</span></li>
 <li><b>Doručte originál</b><span>Poštou, kuriérom alebo osobne na Jazdeckú (vopred zavolajte).</span></li>
 <li><b>Prevezmite preklad</b><span>Pošleme ho poštou či kuriérom, alebo si ho vyzdvihnete osobne.</span></li>
</ol>

<h2>Pre firmy z regiónu</h2>
<p>Odborné preklady odovzdávame elektronicky, takže vzdialenosť nehrá rolu. Najčastejšie prekladáme pre firmy z týchto miest:</p>
<p class="yc-kchips"><?php foreach (yc_cities() as $cs => $cx) echo '<a href="' . esc_url(home_url('/' . $cs . '/')) . '">' . esc_html($cx['city']) . '</a>'; ?></p>

<h2>Fakturačné údaje</h2>
<section class="yc-kc yc-kbill">
 <dl>
  <dt>Obchodné meno</dt><dd><b>Your Choice – jazykové služby, s.r.o.</b></dd>
  <dt>Sídlo</dt><dd>Jazdecká 8686/65, 917 08 Trnava, Slovenská republika</dd>
  <dt>IČO</dt><dd>50208055</dd>
  <dt>DIČ</dt><dd>2120212391</dd>
  <dt>IČ DPH</dt><dd>SK2120212391 · platiteľ DPH</dd>
  <dt>Zápis</dt><dd>Obchodný register Okresného súdu Trnava, oddiel Sro, vložka č. 37254/T</dd>
 </dl>
</section>

