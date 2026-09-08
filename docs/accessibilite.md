# Rapport d'accessibilité — thème `ufo-boilerplate`

_Audit réalisé le 2026-09-08 · référentiel WCAG 2.1 niveau AA · cible `https://wordpress.ddev.site/`_

## 1. Résumé

Le thème part d'un **bon socle** : landmarks corrects, `lang` renseigné, skip link,
textes `sr-only` nombreux, `aria-expanded` / `aria-controls` sur les menus,
région `aria-live` pour le filtre AJAX, aucun `outline: none` global, zoom non
bloqué.

L'audit automatisé (Lighthouse / axe-core) affichait **100/100** sur mobile mais
ne couvre qu'environ un tiers des critères. La revue manuelle (clavier, lecteur
d'écran, `prefers-reduced-motion`, audit **desktop**) a mis en évidence
**8 points**, dont 6 corrigés dans ce lot.

| Score Lighthouse Accessibilité | Avant | Après |
| --- | --- | --- |
| Mobile | 100 | 100 |
| Desktop | **97** (`<li>` hors `<ul>`) | **100** |

## 2. Méthode

- Lighthouse (Chrome DevTools), modes mobile + desktop, navigation complète.
- Parcours clavier seul : skip link, menu principal desktop (dropdown/méga-menu),
  menu mobile (burger), ancres internes.
- Inspection DOM : landmarks, `id` dupliqués, ordre de tabulation, gestion du
  focus à l'ouverture/fermeture des panneaux.
- Vérification du respect de `prefers-reduced-motion` (CSS + JS).
- Revue de code : `theme/header.php`, `template-parts/layout/header-content.php`,
  `inc/navigation/class-navwalker.php`, `javascript/ui.js`,
  `tailwind/custom/*.css`.

Hors périmètre (extensions distinctes, non livrées avec le thème) :
`ufo-post-carousel`, `contact-form-7`, `etchenet-reviews-for-google-business`.

## 3. Points corrigés

### 3.1 — Skip link invisible au focus _(WCAG 2.4.7 — Corrigé)_

**Constat.** `theme/header.php` : `<a href="#content" class="sr-only">` sans
variante d'affichage au focus. Un utilisateur clavier voyant rencontrait un arrêt
de tabulation totalement invisible. De plus, `initSmoothAnchors()`
(`javascript/ui.js`) interceptait `a[href^="#"]` avec `preventDefault()` puis un
simple `scrollIntoView` : le focus clavier n'était **jamais** déplacé vers la
cible, y compris pour les lecteurs d'écran.

**Correctif.**

- `theme/header.php` : ajout de la classe `skip-link`.
- `tailwind/custom/base.css` : règle `.skip-link:focus` qui ré-affiche le lien
  (position fixe en haut à gauche, fond, contour visible). Placée hors `@layer`
  pour l'emporter sur l'utilitaire `.sr-only`.
- `javascript/ui.js` : `initSmoothAnchors()` appelle désormais `moveFocusTo(target)`
  après le défilement — pose un `tabindex="-1"` temporaire si nécessaire et
  déplace réellement le focus. Bénéficie à **toutes** les ancres internes, pas
  seulement au skip link.

**Vérifié.** Focus sur le lien → visible en haut à gauche ; activation → focus
positionné sur `#content` (`tabindex="-1"`).

### 3.2 — `prefers-reduced-motion` ignoré _(WCAG 2.3.3 / 2.2.2 — Corrigé)_

**Constat.** Aucune media query globale. Les transitions/animations CSS
(`#masthead`, menus, chevrons) et le défilement fluide JS (`window.lenis`,
`scrollIntoView({ behavior: 'smooth' })`) s'exécutaient quelle que soit la
préférence système.

**Correctif.**

- `tailwind/custom/base.css` : bloc
  `@media (prefers-reduced-motion: reduce)` neutralisant
  `animation-duration`, `transition-duration`, `transition-delay` et
  `scroll-behavior` sur `*` (déclarations `!important`, hors `@layer` pour
  primer sur les utilitaires `transition-*` / `animate-*` de Tailwind).
- `javascript/ui.js` : helper `prefersReducedMotion()` ; `initSmoothAnchors()`
  saute Lenis et utilise `behavior: 'auto'` quand la préférence est active.

**Vérifié.** Règle présente dans `theme/style.css` compilé ; défilement JS
instantané sous `prefers-reduced-motion: reduce`.

### 3.3 — Menu mobile : aucune gestion du focus _(WCAG 2.4.3 — Corrigé)_

**Constat.** À l'ouverture du panneau (`#mobile-menu`, superposé au contenu avec
ombre + anneau), le focus restait sur le bouton burger ; à la fermeture il n'était
pas restitué ; le focus pouvait passer derrière le panneau opaque.

**Correctif** (`javascript/ui.js`, `initBurgerMenu()`) :

- ouverture → focus déplacé sur le premier élément focusable du panneau ;
- fermeture (Échap, clic extérieur, bouton) → focus rendu au bouton burger s'il
  était resté dans le panneau ;
- cycle de tabulation maintenu entre le bouton burger (qui reste visible en
  « X ») et les éléments du panneau. Échap et le bouton ferment toujours le
  menu : ce n'est donc pas un piège au sens WCAG 2.1.2.

**Vérifié.** Ouverture → focus sur « Accueil » ; `Tab` depuis le dernier lien →
bouton burger ; Échap → menu fermé, focus rendu au bouton.

### 3.4 — Menu mobile hors de tout landmark de navigation _(WCAG 1.3.1 — Corrigé)_

**Constat.** `#mobile-menu` était un `<div>` situé **après** `</nav>`. En
navigation par landmarks, la navigation mobile était introuvable pour un lecteur
d'écran.

**Correctif** (`header-content.php`) : le contenu du menu mobile est désormais
enveloppé dans `<nav aria-label="Mobile">` (libellé distinct de la navigation
« Global »).

### 3.5 — `aria-haspopup` mensonger sur les boutons de dropdown _(WCAG 4.1.2 — Corrigé)_

**Constat.** `class-navwalker.php` posait `aria-haspopup="true"` (équivalent
`menu`) sur chaque bouton, alors que le panneau n'expose pas de `role="menu"` et
ne gère pas la navigation par flèches : les lecteurs d'écran annonçaient « menu »
à tort. Par ailleurs Échap ne fermait le panneau que si le focus était **sur le
bouton**, pas à l'intérieur du panneau.

**Correctif.**

- `class-navwalker.php` : suppression de `aria-haspopup`. C'est un widget
  _disclosure_ ; `aria-expanded` + `aria-controls` suffisent et sont désormais
  tous deux présents.
- `aria-controls` ajouté, pointant vers l'`id` du panneau. Le menu étant rendu
  deux fois (barre desktop + panneau mobile), le walker prend un préfixe
  d'instance au constructeur (`primary` / `mobile`) → `id`s
  `primary-submenu-{ID}` et `mobile-submenu-{ID}`, sans collision.
- `javascript/ui.js` : l'écouteur Échap passe du bouton au `<li>` conteneur
  (couvre bouton + panneau) et restitue le focus au bouton.

**Vérifié.** `aria-haspopup` absent ; `aria-controls` résolu vers un panneau
existant ; aucun `id` dupliqué (desktop + mobile) ; Échap depuis un lien du
panneau → fermeture + focus rendu au bouton.

### 3.6 — `<li>` hors `<ul>` dans la navigation _(WCAG 1.3.1 — Corrigé)_

**Constat.** Découvert pendant la vérification (audit desktop, score 97). Le
walker était appelé avec `'items_wrap' => '%3$s'`, ce qui supprime le conteneur
`<ul>` : les `<li>` de premier niveau étaient orphelins (perte de la sémantique
de liste et du décompte d'éléments pour les lecteurs d'écran).

**Correctif** (`header-content.php`) : `items_wrap` rétablit un `<ul>` porteur des
classes de mise en page —
`'<ul class="flex gap-x-12">%3$s</ul>'` (desktop),
`'<ul class="space-y-2">%3$s</ul>'` (mobile) — le `<div>` parent perd `flex` au
profit de `lg:block`. Mise en page inchangée visuellement.

**Vérifié.** 0 `<li>` orphelin ; navigation desktop toujours en ligne, gap 48 px ;
Lighthouse desktop repasse à **100**.

## 4. Points restants (recommandations, non traités)

| # | Point | Sévérité | Piste |
| --- | --- | --- | --- |
| 4.1 | **Contraste des textes en opacité réduite** (`text-foreground/70`, `text-slate-200` sur fond sombre) : méta d'articles, extraits, cartes de méga-menu. Lighthouse ne teste que les nœuds visibles de la page d'accueil ; les panneaux déroulants sont masqués pendant l'audit. | Moyenne (1.4.3) | Passe manuelle avec un méga-menu ouvert et sur les cartes sombres, viser 4,5:1 (3:1 pour le texte large). |
| 4.2 | **Sous-menus dupliqués dans le menu mobile** : `initMegaMenus()` câble aussi les `[data-dropdown-item]` du menu mobile, qui conservent des classes de positionnement desktop (`md:w-screen`, `lg:absolute`). Fonctionnel (accordéon vérifié, `id`s désormais préfixés), mais markup non idéal. | Faible | À terme, un gabarit d'accordéon mobile dédié plutôt que la réutilisation du panneau desktop. |
| 4.3 | **`alt` du logo** = « WordPress » (repli sur le titre du site) au lieu du nom réel de la marque. | Faible (1.1.1) | Renseigner un texte alternatif sur le logo personnalisé, ou le rendre décoratif si le nom est déjà en texte à côté. |
| 4.4 | **`image-size-responsive`** (Lighthouse Best Practices 96, mobile) : une image servie en résolution insuffisante. Probablement le logo (50×50) ou une image d'extension carousel — pas du code de thème. | Faible | Vérifier la source de l'image signalée dans le rapport Lighthouse. |
| 4.5 | **Dette Prettier sur `javascript/ui.js` et `javascript/script.js`** : ces fichiers ne passent pas `npm run lint:prettier` sur `master` (antérieurs à la config `@wordpress/prettier-config`). Les ajouts de ce lot suivent le style **existant** du fichier pour limiter le bruit du diff ; un reformatage complet est à faire dans un commit séparé. | Faible (hors WCAG) | `npx prettier --write javascript/*.js` en commit dédié. |
| 4.6 | **Extensions hors thème** : `ufo-post-carousel` (vérifier bouton pause si défilement auto — WCAG 2.2.2 —, `aria-roledescription`), `contact-form-7` (association label/erreur, `aria-describedby`). | À évaluer | Audit séparé côté extensions. |

## 5. Bonnes pratiques déjà en place

- Skip link + `<main id="main">` dans tous les gabarits ; `<header>`,
  `<nav aria-label>` avec libellés distincts.
- `language_attributes()` → `lang` correct ; `viewport` sans `maximum-scale` ni
  `user-scalable=no`.
- Textes `sr-only` : boutons à icône seule, liens « Modifier », auteur,
  commentaires, réseaux sociaux, états de chargement.
- Burger : `aria-expanded` + `aria-controls`, libellé `sr-only`, cible tactile
  44 px.
- Filtre AJAX : région `aria-live="polite" aria-atomic`.
- Fil d'Ariane accessible et annoté schema.org ; SVG décoratifs `aria-hidden`.
- Fermeture des menus sur Échap et clic extérieur.
- Aucun `outline: none` global — les indicateurs de focus natifs sont préservés,
  les CTA ont `focus-visible:outline-2`.

## 6. Fichiers modifiés

| Fichier | Nature |
| --- | --- |
| `theme/header.php` | classe `skip-link` sur le lien d'évitement |
| `tailwind/custom/base.css` | `@media (prefers-reduced-motion)` global + `.skip-link:focus` |
| `javascript/ui.js` | helpers `prefersReducedMotion` / `moveFocusTo` / `getFocusable` ; focus du menu mobile (entrée/sortie + cycle Tab) ; Échap dropdown au niveau `<li>` ; focus + `prefers-reduced-motion` sur les ancres internes |
| `theme/inc/navigation/class-navwalker.php` | préfixe d'`id` d'instance ; suppression `aria-haspopup` ; ajout `aria-controls` + `id` de panneau |
| `theme/template-parts/layout/header-content.php` | `<nav aria-label="Mobile">` ; `items_wrap` avec `<ul>` ; padding `p-2.5` (débordement burger, corrigé précédemment) |

> Sorties de build (`theme/style.css`, `theme/js/*.min.js`) : régénérées par
> `npm run dev` / `npm run prod`, non versionnées (`.gitignore`).
