<?php $m = esc_url('mailto:' . yc_email() . '?subject=' . rawurlencode('Translation enquiry')); ?>
<p style="font-size:20px;color:#4A5468">Specialised and certified translations to and from Slovak for companies. 22 years of experience, 25 languages, one project manager for your whole job.</p>
<p><span class="yc-price">specialised translation from €18.50 · certified translation from €25 per standard page · excl. VAT</span></p>
<p><a class="yc-btn" href="<?php echo $m; ?>">Get a quote within 24 h</a> <a href="tel:+421915343261" style="margin-left:12px;font-weight:600">+421 915 343 261</a></p>

<h2>What we do</h2>
<ul class="yc-check">
<li>specialised translations: contracts, technical documentation, annual reports</li>
<li>certified translations with the stamp of a sworn translator, for Slovak courts, authorities and registers</li>
<li>website and e-shop localisation</li>
<li>documents for foreign employees</li>
<li>AI translation with expert review for large volumes</li>
</ul>

<h2>Translations by industry</h2>
<div class="yc-links"><?php foreach (yc_landing() as $s => $x) if ($x['g'] === 'eo') echo '<a href="' . esc_url(home_url('/' . $s . '/')) . '">' . esc_html($x['n']) . '</a>'; ?></div>

<h2>How it works</h2>
<section class="yc-card"><h3 style="margin-top:0">1. Send us the document</h3><p style="margin-bottom:0">By e-mail, in any common format. Tell us the target language and when you need it.</p></section>
<section class="yc-card"><h3 style="margin-top:0">2. You get a price and a deadline</h3><p style="margin-bottom:0">Within 24 hours on working days, with no obligation.</p></section>
<section class="yc-card"><h3 style="margin-top:0">3. Translation</h3><p style="margin-bottom:0">A translator experienced in your field translates the text. English translations are additionally checked by a second linguist.</p></section>

<section class="yc-card" style="background:#14213D;color:#fff;border:0">
<h2 style="color:#fff">Write to us in English</h2>
<p>We reply in English. Your Choice – jazykové služby, s.r.o., Jazdecká 8686/65, Trnava, Slovakia.</p>
<a class="yc-btn" href="<?php echo $m; ?>"><?php echo esc_html(yc_email()); ?></a>
</section>
<p class="yc-meta"><a lang="sk" href="<?php echo esc_url(home_url('/')); ?>">Slovenská verzia webu</a></p>
