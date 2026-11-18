<?php
$pg = get_query_var('yc_page'); $p = yc_pages()[$pg]; $LL = yc_landing(); $L = $LL[$pg] ?? null;
$en = function_exists('yc_is_en') && yc_is_en();
$A = yc_articles();
$legal = in_array($pg, ['ochrana-osobnych-udajov', 'cookies', 'obchodne-podmienky'], true);
/* Malý nadpis nad H1 a drobčeky podľa typu stránky. */
if ($L) {
	$kick = $en ? 'Translation services' : (['o' => 'Preklady podľa odvetvia', 'd' => 'Preklady podľa dokumentu', 'j' => 'Preklady podľa jazyka', 'l' => 'Prekladateľská agentúra Trnava', 'eo' => 'Translations by industry'][$L['g']] ?? 'Služby');
	$crumb = $en ? ['Translation services', home_url('/en/')] : ['Služby', home_url('/sluzby/')];
} elseif (isset($A[$pg])) {
	$kick = 'Odborné novinky · ' . $A[$pg]['min'] . ' min čítania'; $crumb = ['Odborné novinky', home_url('/novinky/')];
} else {
	$kick = ['sluzby' => 'Služby a ceny', 'cennik' => 'Cenník 2026', 'kontakt' => 'Kontakt', 'referencie' => 'Klienti o nás', 'novinky' => 'Blog o prekladoch pre firmy', 'en' => 'Your Choice · Trnava, Slovakia'][$pg] ?? ($legal ? 'Právne informácie' : 'Your Choice');
	$crumb = null;
}
$lead = $L ? $L['lead'] : ($A[$pg]['d'] ?? null);
$GLOBALS['yc_hero_lead'] = (bool) $lead;
status_header(200);
get_header(); ?>
<section class="yc-hero<?php echo $legal ? ' slim' : ''; ?>">
 <div class="yc-hero-in">
  <div class="yc-hero-txt">
   <nav class="yc-crumbs" aria-label="<?php echo $en ? 'Breadcrumb' : 'Navigácia'; ?>"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo $en ? 'Home' : 'Domov'; ?></a><?php if ($crumb): ?> <span>›</span> <a href="<?php echo esc_url($crumb[1]); ?>"><?php echo esc_html($crumb[0]); ?></a><?php endif; ?></nav>
   <p class="yc-kicker"><?php echo esc_html($kick); ?></p>
   <h1><?php echo esc_html($p[0]); ?></h1>
   <?php if ($lead): ?><p class="yc-hero-lead"><?php echo esc_html($lead); ?></p><?php endif; ?>
   <?php if (!$legal && $pg !== 'kontakt'): ?>
   <p class="yc-hero-cta"><a class="yc-btn" href="<?php echo $en ? esc_url('mailto:' . yc_email() . '?subject=' . rawurlencode('Translation enquiry')) : yc_anchor('kontakt'); ?>"><?php echo $en ? 'Get a quote within 24 h' : 'Cenová ponuka do 24 h'; ?></a> <a class="yc-hero-tel" href="tel:+421915343261">+421 915 343 261</a></p>
   <?php endif; ?>
  </div>
  <?php if (!$legal): ?>
  <figure class="yc-hero-art" aria-hidden="true">
   <svg viewBox="0 0 320 240" width="320" height="240"><rect x="18" y="26" width="190" height="122" rx="22" fill="#22335A"/><path d="M58 148v34l34-34z" fill="#22335A"/><rect x="44" y="56" width="128" height="12" rx="6" fill="#5A6A8C"/><rect x="44" y="82" width="100" height="12" rx="6" fill="#5A6A8C"/><rect x="44" y="108" width="116" height="12" rx="6" fill="#5A6A8C"/><rect x="118" y="92" width="184" height="118" rx="22" fill="#F59E1B"/><path d="M262 210v30l-30-30z" fill="#F59E1B"/><path d="M178 150l22 22 44-48" fill="none" stroke="#fff" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"/></svg>
   <figcaption>Ilustrácia: Claude</figcaption>
  </figure>
  <?php endif; ?>
 </div>
</section>
<article class="yc-page">
<?php include get_template_directory() . ($L ? '/landing.php' : '/pages/' . $pg . '.php'); ?>
</article>
<?php if (!$legal && !$en && $pg !== 'kontakt'): ?>
<section class="yc-endcta">
 <div class="yc-endcta-in">
  <div class="yc-endcta-person"><span class="yc-avatar">JK</span><div><b>Janka Kubičková</b><br><span>projektová manažérka</span></div></div>
  <div class="yc-endcta-txt"><h2>Pošlite dokument, cenu máte do 24 hodín</h2><p>Nezáväzne, v pracovné dni. Na dopyt odpovedá vždy ten istý človek.</p></div>
  <div class="yc-endcta-act"><a class="yc-btn" href="<?php echo yc_anchor('kontakt'); ?>">Poslať dopyt</a><a class="yc-endcta-tel" href="tel:+421915343261">+421 915 343 261</a></div>
 </div>
</section>
<?php endif; ?>
<?php get_footer(); ?>
