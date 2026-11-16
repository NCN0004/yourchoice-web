# yourchoice.sk – téma WordPress

Téma webu **Your Choice – jazykové služby** (preklady pre firmy).

## Ako sa zmeny dostanú na web
1. Zmena sa zapíše sem do priečinka `theme/` (vetva `main`).
2. Web na Webglobe si každých pár minút skontroluje GitHub (plánovaná úloha volá `/?yc_deploy=1`) a stiahne nové súbory do `wp-content/themes/yourchoice/`.
3. Pred prepísaním sa pôvodný súbor uloží do `wp-content/yc-zalohy/`.

Každá zmena je tu zapísaná v histórii a dá sa vrátiť späť (revert).
