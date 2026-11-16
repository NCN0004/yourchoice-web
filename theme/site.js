(function () {
  var cfg = window.YC_CFG || {};
  function getC() { try { return JSON.parse(localStorage.getItem('yc_consent') || 'null'); } catch (e) { return null; } }
  function setC(v) { try { localStorage.setItem('yc_consent', JSON.stringify({ analytics: v, t: Date.now() })); } catch (e) {} }
  function loadGA() {
    if (window.gtag || !cfg.ga) return;
    window.dataLayer = window.dataLayer || []; window.gtag = function () { dataLayer.push(arguments); };
    gtag('js', new Date()); gtag('config', cfg.ga);
    var s = document.createElement('script'); s.async = true; s.src = 'https://www.googletagmanager.com/gtag/js?id=' + cfg.ga; document.head.appendChild(s);
    if (window.YC_LEAD) gtag('event', 'generate_lead', { formular: window.YC_LEAD });
  }
  function banner() {
    if (document.getElementById('yc-cookie')) return;
    var d = document.createElement('div'); d.id = 'yc-cookie'; d.setAttribute('role', 'dialog'); d.setAttribute('aria-label', 'Súhlas s cookies');
    d.innerHTML = '<b>Môžeme merať návštevnosť?</b><br>Na fungovanie webu cookies nepotrebujeme. S vaším súhlasom použijeme Google Analytics, aby sme vedeli, čo na webe zlepšiť. Súhlas môžete kedykoľvek zmeniť. <a href="' + (cfg.cookies || '#') + '">Viac o cookies</a><div class="row"><button type="button" class="yc-btn ghost" data-c="0">Odmietnuť</button><button type="button" class="yc-btn dark" data-c="1">Súhlasím</button></div>';
    document.body.appendChild(d);
    d.querySelectorAll('button').forEach(function (b) { b.addEventListener('click', function () { var ok = b.dataset.c === '1'; setC(ok); d.remove(); if (ok) loadGA(); else if (window.gtag) location.reload(); }); });
  }
  var c = getC();
  /* súhlas platí 12 mesiacov */
  if (!c || Date.now() - c.t > 365 * 864e5) banner(); else if (c.analytics) loadGA();
  document.querySelectorAll('[data-yc-cookie-open]').forEach(function (a) { a.addEventListener('click', function (e) { e.preventDefault(); banner(); }); });
  var docs = window.YC_DOCS || [];
  var base = 'min-height: 44px; padding: 12px 18px; border-radius: 999px; font-size: 15px; font-weight: 600; cursor: pointer; font-family: Inter, sans-serif; ';
  var on = base + 'background: #14213D; color: #FFFFFF; border: 2px solid #14213D;';
  var off = base + 'background: #FFFFFF; color: #14213D; border: 2px solid #D9D3C5;';
  document.querySelectorAll('.yc-doc').forEach(function (b) {
    b.addEventListener('click', function () {
      var d = docs.filter(function (x) { return x.id === b.dataset.doc; })[0]; if (!d) return;
      document.querySelectorAll('.yc-doc').forEach(function (o) { var a = o === b; o.setAttribute('aria-pressed', a); o.setAttribute('style', a ? on : off); });
      ['title', 'text', 'time', 'extra'].forEach(function (k) { var el = document.querySelector('[data-rec="' + k + '"]'); if (el) el.textContent = d[k]; });
      if (window.gtag) gtag('event', 'konfigurator_vyber', { dokument: d.id });
    });
  });
  document.querySelectorAll('form[data-yc-form]').forEach(function (f) {
    var t = f.querySelector('input[name="yc_js"]'); if (t) t.value = 'ok';
    f.querySelectorAll('input[type="file"]').forEach(function (inp) {
      inp.addEventListener('change', function () {
        var lab = inp.closest('label'); var span = lab && lab.querySelector('span[style*="dashed"]');
        if (span && inp.files.length) { var n = Array.prototype.map.call(inp.files, function (x) { return x.name; }).join(', '); var txt = span.querySelector('.yc-fn') || document.createElement('b'); txt.className = 'yc-fn'; txt.style.cssText = 'display:block;color:#14213D;word-break:break-all'; txt.textContent = n; span.appendChild(txt); }
      });
    });
    f.addEventListener('submit', function () {
      var b = f.querySelector('button[type="submit"]'); if (b) { b.disabled = true; b.textContent = 'Odosielam…'; }
      if (window.gtag) gtag('event', 'form_odoslanie', { formular: f.dataset.ycForm });
    });
  });
  document.querySelectorAll('a[href^="tel:"]').forEach(function (a) { a.addEventListener('click', function () { if (window.gtag) gtag('event', 'klik_telefon'); }); });
  document.querySelectorAll('a[href^="mailto:"]').forEach(function (a) { a.addEventListener('click', function () { if (window.gtag) gtag('event', 'klik_email'); }); });
})();
(function () {
  document.querySelectorAll('a[href*="wa.me/"]').forEach(function (a) { a.addEventListener('click', function () { if (window.gtag) gtag('event', 'klik_whatsapp'); }); });
  var c = document.querySelector('[data-yc-calc]'); if (!c) return;
  var q = function (k) { return c.querySelector('[data-calc="' + k + '"]'); };
  var fmt = function (n) { return n.toLocaleString('sk-SK', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €'; };
  function run() {
    var ns = Math.max(1, Math.min(2000, parseFloat(q('ns').value) || 1));
    var cena = parseFloat(q('druh').value) * ns * parseFloat(q('termin').value);
    var z = ns >= 30 ? 0.15 : (ns >= 10 ? 0.10 : 0);
    cena = cena * (1 - z);
    q('out').textContent = fmt(cena); q('out2').textContent = '(' + fmt(cena * 1.23) + ' s DPH)';
    q('note').textContent = z ? 'Započítali sme množstevnú zľavu ' + Math.round(z * 100) + ' %.' : (ns >= 7 ? 'Od 10 normostrán máte zľavu 10 %.' : '');
  }
  ['druh', 'ns', 'termin'].forEach(function (k) { q(k).addEventListener('input', run); q(k).addEventListener('change', run); });
  run();
  var once = false; c.addEventListener('change', function () { if (!once && window.gtag) { once = true; gtag('event', 'kalkulacka_pouzitie'); } });
})();
