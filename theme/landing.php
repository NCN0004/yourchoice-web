<?php $k = yc_anchor('kontakt'); ?>
<p style="font-size:20px;color:#4A5468"><?php echo esc_html($L['lead']); ?></p>
<p><span class="yc-price"><?php echo esc_html($L['price']); ?> · bez DPH</span> <span class="yc-meta">bez príplatku za odborný text · od 10 NS zľava 10 %</span></p>
<p><a class="yc-btn" href="<?php echo $k; ?>">Získať cenovú ponuku do 24 h</a> <a href="tel:+421915343261" style="margin-left:12px;font-weight:600">+421 915 343 261</a></p>

<h2>Čo najčastejšie prekladáme</h2>
<ul class="yc-check"><?php foreach ($L['items'] as $i) echo '<li>' . esc_html($i) . '</li>'; ?></ul>

<h2>Ako pracujeme</h2>
<?php foreach ($L['why'] as $w): ?>
<section class="yc-card"><h3 style="margin-top:0"><?php echo esc_html($w[0]); ?></h3><p style="margin-bottom:0"><?php echo esc_html($w[1]); ?></p></section>
<?php endforeach; ?>

<h2>Časté otázky</h2>
<?php foreach ($L['faq'] as $f): ?>
<details class="yc-card"><summary style="font-weight:700;cursor:pointer"><?php echo esc_html($f[0]); ?></summary><p style="margin-bottom:0"><?php echo esc_html($f[1]); ?></p></details>
<?php endforeach; ?>
<script type="application/ld+json"><?php echo wp_json_encode(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(function ($f) { return ['@type' => 'Question', 'name' => $f[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]]]; }, $L['faq'])]); ?></script>

<section class="yc-card" style="background:#14213D;color:#fff;border:0">
<h2 style="color:#fff">Pošlite nám dokument, ozveme sa s cenou</h2>
<p>Cenu a termín máte do 24 hodín v pracovné dni, nezáväzne.</p>
<a class="yc-btn" href="<?php echo $k; ?>">Poslať dopyt</a>
</section>
<p class="yc-meta">Pozrite aj: <?php $o = []; foreach (yc_landing() as $s => $x) if ($s !== $pg && $x['g'] === $L['g']) $o[] = '<a href="' . esc_url(home_url('/' . $s . '/')) . '">' . esc_html($x['n']) . '</a>'; echo implode(' · ', $o); ?> · <a href="<?php echo esc_url(home_url('/sluzby/')); ?>">Všetky služby</a></p>
