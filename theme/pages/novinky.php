<?php $A = yc_articles(); ?>
<p style="font-size:20px;color:#4A5468">Krátke praktické články pre firmy, ktoré pracujú s cudzím jazykom: zmluvy, zamestnanci zo zahraničia, AI a preklad.</p>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin:24px 0">
<?php foreach ($A as $s => $a): ?>
<a href="<?php echo esc_url(home_url('/' . $s . '/')); ?>" class="yc-card" style="text-decoration:none;color:inherit;display:flex;flex-direction:column;gap:8px;margin:0"><span style="font-size:13px;font-weight:700;color:#145C53"><?php echo esc_html($a['cat']); ?></span><b style="font-size:20px;line-height:1.3"><?php echo esc_html($a['t']); ?></b><span style="color:#4A5468;line-height:1.5"><?php echo esc_html($a['d']); ?></span><span class="yc-meta"><?php echo esc_html($a['min']); ?> min čítania</span></a>
<?php endforeach; ?>
</div>
