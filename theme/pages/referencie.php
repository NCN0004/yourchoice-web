<?php $k = yc_anchor('kontakt');
$refs = [
	['Arcadis CZ', 'stavebníctvo a inžiniering', 'Matej Mažgut', 'Pri preklade mesačných správ stavebného dozoru na rýchlostnej ceste R2 sme nezaznamenali žiadne výhrady zo strany klienta.', ['mesačné správy stavebného dozoru', 'projekt rýchlostnej cesty R2', 'pravidelná spolupráca s pevnými termínmi'], 'preklady-pre-stavebnictvo', 'Preklady pre stavebníctvo'],
	['Slovenská pošta', 'verejná spoločnosť', 'Ján Kollár', 'Nanajvýš kvalitná a promptná dodávka – od medzinárodných zmlúv a výročných správ až po detské listy pre medzinárodné súťaže.', ['medzinárodné zmluvy', 'výročné správy', 'detské listy pre medzinárodné súťaže'], 'pravne-preklady', 'Právne preklady'],
	['ELPAM real', 'reality', 'Martina Nováková', 'Preklady na vysokej úrovni, ústretovosť a vždy dodržaný termín.', ['realitné dokumenty', 'dodržané termíny', 'ústretová komunikácia'], 'preklady-pre-stavebnictvo', 'Preklady pre stavebníctvo a reality'],
]; ?>
<p class="yc-answer">Prekladáme pre firmy, verejné inštitúcie aj medzinárodné projekty. Tu je, čo o spolupráci s nami hovoria naši klienti – s ich súhlasom.</p>

<div class="yc-clients" aria-label="Klienti">
<?php foreach ($refs as $r): ?><span><?php echo esc_html($r[0]); ?></span><?php endforeach; ?>
</div>

<?php foreach ($refs as $r): ?>
<section class="yc-ref">
 <div class="yc-ref-head"><span class="yc-ref-mono"><?php echo esc_html(mb_substr($r[0], 0, 1)); ?></span><div><h2><?php echo esc_html($r[0]); ?></h2><p class="yc-meta"><?php echo esc_html($r[1]); ?></p></div></div>
 <blockquote>„<?php echo esc_html($r[3]); ?>“</blockquote>
 <p class="yc-ref-who">— <?php echo esc_html($r[2]); ?>, <?php echo esc_html($r[0]); ?></p>
 <ul class="yc-check"><?php foreach ($r[4] as $i) echo '<li>' . esc_html($i) . '</li>'; ?></ul>
 <p class="yc-meta">Podobnú zákazku máte? Pozrite si <a href="<?php echo esc_url(home_url('/' . $r[5] . '/')); ?>"><?php echo esc_html($r[6]); ?></a>.</p>
</section>
<?php endforeach; ?>

<h2>Čím sa líšime</h2>
<div class="yc-stats">
 <div><b>22</b><span>rokov praxe</span></div>
 <div><b>25</b><span>jazykov</span></div>
 <div><b>24 h</b><span>cena a termín</span></div>
 <div><b>1</b><span>kontakt pre celú zákazku</span></div>
</div>
<p>Každý text prekladá človek, ktorý odboru rozumie. Ak v origináli nájdeme nejasnosť alebo preklep, upozorníme vás ešte pred odovzdaním. Anglické preklady navyše kontroluje druhý lingvista.</p>
<p>Chcete referenciu z vášho odboru alebo kontakt na klienta s podobnou zákazkou? Napíšte nám – po dohode s klientom vás radi prepojíme.</p>
<script type="application/ld+json"><?php echo wp_json_encode(['@context' => 'https://schema.org', '@type' => 'ItemList', 'name' => 'Referencie klientov Your Choice', 'itemListElement' => array_map(function ($r, $i) { return ['@type' => 'ListItem', 'position' => $i + 1, 'item' => ['@type' => 'Organization', 'name' => $r[0]]]; }, $refs, array_keys($refs))], JSON_UNESCAPED_UNICODE); ?></script>
