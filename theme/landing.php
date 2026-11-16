<?php $en = !empty($L['lang']) && $L['lang'] === 'en';
$k = $en ? esc_url('mailto:' . yc_email() . '?subject=' . rawurlencode('Translation enquiry')) : yc_anchor('kontakt');
$S = $en
 ? ['vat' => 'excl. VAT', 'meta' => 'no surcharge for specialised text · 10% discount from 10 standard pages', 'cta' => 'Get a quote within 24 h', 'h1' => 'What we translate most often', 'h2' => 'How we work', 'h3' => 'Frequently asked questions', 'b1' => 'Send us your document and we will reply with a price', 'b2' => 'You get the price and deadline within 24 hours on working days, with no obligation. Write to us in English, we reply in English.', 'b3' => 'Send an enquiry', 'also' => 'See also', 'all' => 'All translation services', 'allurl' => home_url('/en/')]
 : ['vat' => 'bez DPH', 'meta' => 'bez príplatku za odborný text · od 10 NS zľava 10 %', 'cta' => 'Získať cenovú ponuku do 24 h', 'h1' => 'Čo najčastejšie prekladáme', 'h2' => 'Ako pracujeme', 'h3' => 'Časté otázky', 'b1' => 'Pošlite nám dokument, ozveme sa s cenou', 'b2' => 'Cenu a termín máte do 24 hodín v pracovné dni, nezáväzne.', 'b3' => 'Poslať dopyt', 'also' => 'Pozrite aj', 'all' => 'Všetky služby', 'allurl' => home_url('/sluzby/')]; ?>
<p style="font-size:20px;color:#4A5468"><?php echo esc_html($L['lead']); ?></p>
<p><span class="yc-price"><?php echo esc_html($L['price']); ?> · <?php echo $S['vat']; ?></span> <span class="yc-meta"><?php echo esc_html($S['meta']); ?></span></p>
<p><a class="yc-btn" href="<?php echo $k; ?>"><?php echo esc_html($S['cta']); ?></a> <a href="tel:+421915343261" style="margin-left:12px;font-weight:600">+421 915 343 261</a></p>

<h2><?php echo esc_html($S['h1']); ?></h2>
<ul class="yc-check"><?php foreach ($L['items'] as $i) echo '<li>' . esc_html($i) . '</li>'; ?></ul>

<h2><?php echo esc_html($S['h2']); ?></h2>
<?php foreach ($L['why'] as $w): ?>
<section class="yc-card"><h3 style="margin-top:0"><?php echo esc_html($w[0]); ?></h3><p style="margin-bottom:0"><?php echo esc_html($w[1]); ?></p></section>
<?php endforeach; ?>

<h2><?php echo esc_html($S['h3']); ?></h2>
<?php foreach ($L['faq'] as $f): ?>
<details class="yc-card"><summary style="font-weight:700;cursor:pointer"><?php echo esc_html($f[0]); ?></summary><p style="margin-bottom:0"><?php echo esc_html($f[1]); ?></p></details>
<?php endforeach; ?>
<script type="application/ld+json"><?php echo wp_json_encode(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(function ($f) { return ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]]; }, $L['faq'])]); ?></script>

<section class="yc-card" style="background:#14213D;color:#fff;border:0">
<h2 style="color:#fff"><?php echo esc_html($S['b1']); ?></h2>
<p><?php echo esc_html($S['b2']); ?></p>
<a class="yc-btn" href="<?php echo $k; ?>"><?php echo esc_html($S['b3']); ?></a>
</section>
<p class="yc-meta"><?php echo esc_html($S['also']); ?>: <?php $o = []; foreach (yc_landing() as $s => $x) if ($s !== $pg && $x['g'] === $L['g']) $o[] = '<a href="' . esc_url(home_url('/' . $s . '/')) . '">' . esc_html($x['n']) . '</a>'; echo implode(' · ', $o); ?> · <a href="<?php echo esc_url($S['allurl']); ?>"><?php echo esc_html($S['all']); ?></a><?php if ($en && !empty($L['sk'])) echo ' · <a lang="sk" href="' . esc_url(home_url('/' . $L['sk'] . '/')) . '">Slovenská verzia</a>'; ?></p>
