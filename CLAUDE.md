# Ufo Boilerplate — consignes pour les agents IA

Starter de thème WordPress classique (pas un block theme FSE) de Ufo Agency,
dérivé de [\_tw](https://underscoretw.com/). Chaque projet client le duplique
puis le personnalise. Le README décrit l'architecture en détail : le lire
avant d'ajouter une fonctionnalité.

Le contenu de `index.php`, `content-single.php`, `content-page.php` et
`content-frontpage.php` (héros, sommaire, CTA…) est du **contenu de départ
voulu**, pas du code mort : ne pas le supprimer.

## Commandes

| Tâche | Commande |
| --- | --- |
| Build de dev (CSS + JS) | `npm run dev` |
| Watch | `npm run watch` |
| Build de prod + ZIP | `npm run bundle` |
| Lint JS / Prettier / PHP | `npm run lint` (`npm run lint-fix` pour corriger) |
| Lint PHP seul | `composer install` une fois, puis `composer php:lint` |
| Régénérer le `.pot` | `npm run make-pot` (sans wp-cli) ou `composer make-pot` |

Après une modification de CSS, de classes Tailwind dans le PHP ou de JS,
relancer `npm run dev` : `theme/style.css`, `theme/style-editor.css` et
`theme/js/*.min.js` sont générés et ignorés par Git.

## Arborescence

```
tailwind.css              Point d'entrée CSS (ne pas modifier sauf besoin)
tailwind/tailwind-theme.css  Tokens @theme + @source (plugins, safelist)
tailwind/custom/          base.css, utilities.css, components/*.css, fonts.css
javascript/               Sources JS (esbuild → theme/js/*.min.js)
theme/                    Le thème livré (seul ce dossier part dans le ZIP)
  theme.json              Palette, polices, tailles, espacements, styles
  *.php                   Gabarits racine : orchestrent, get_template_part()
  template-parts/         Rendu HTML (content/, layout/, ajax-filter/)
  inc/                    Tout ce qui n'est pas un gabarit
  patterns/               Patterns Gutenberg (enregistrés automatiquement)
  languages/              .pot + fr_FR.po/.mo
docs/                     Documentation (audit d'accessibilité)
```

## PHP

- Compatible **PHP 7.4** et WordPress 6.2 : pas de `str_starts_with()`,
  `match`, types union, propriétés `readonly`, etc.
- Standards **WordPress-Extra + WordPress-Docs** (`phpcs.xml.dist`) :
  indentation par tabulations, docblock sur chaque fonction, classe et
  fichier, Yoda conditions, `array()` et non `[]`.
- Préfixe `_ufo_` pour les fonctions, `_Ufo_` pour les classes, `_UFO_` pour
  les constantes ; garde `if ( ! function_exists() )` /
  `if ( ! class_exists() )` autour des déclarations, comme le code existant.
- Échapper à la sortie (`esc_html`, `esc_attr`, `esc_url`, `wp_kses`) ;
  assainir les entrées ; vérifier nonce et capacité avant toute écriture.
- Chaînes traduisibles avec le text domain `ufo-boilerplate`, puis régénérer
  le `.pot` et compléter `theme/languages/fr_FR.po`.

### Ajouter une fonctionnalité

Le démarrage passe par `theme/inc/class-loader.php` (lire son docblock) :

1. **Une classe** : un fichier nommé selon la règle de `inc/autoload.php`,
   dans `inc/`, `inc/features/`, `inc/navigation/` ou `inc/ajax-filter/`.
   `_Ufo_Header_Cta` → `class-header-cta.php`. Aucun `require` à ajouter :
   l'autoloader la charge à la première utilisation.
2. **Un service** (classe qui accroche ses hooks) : implémenter
   `_Ufo_Service` (une méthode statique `register()`) **et** ajouter le nom
   de la classe à `$_ufo_services` dans `class-loader.php`.
3. **Un fichier de fonctions ou de hooks** : il ne peut pas être autochargé ;
   l'ajouter à `$_ufo_function_files`. Ne pas le préfixer `class-`.
4. Réglage éditable par le client : section du Customizer dans le panneau
   `_Ufo_Options::PANEL_ID` (voir `class-header-cta.php`).
5. Documenter la fonctionnalité dans le README (section « Project-specific
   customizations »).

## CSS et design tokens

Source de vérité : `theme/theme.json` → variables `--wp--preset--*` →
réexposées dans `@theme` (`tailwind/tailwind-theme.css`). Un rebranding ne
doit toucher que ces deux fichiers.

- Couleurs nommées par **rôle** (`primary`, `secondary`, `tertiary`,
  `background`, `foreground`, nuanciers `primary-*`, `slate-*`), jamais par
  teinte ni en dur dans un gabarit.
- **Pas de valeur arbitraire pour une valeur de design** : couleur
  (`bg-[#…]`, `from-[#…]`), taille de texte (`text-[7rem]`), interligne
  (`leading-[1.15]`), rayon, ombre, opacité (`/[.09]`). Ajouter un token dans
  `@theme` (ou `theme.json` s'il doit apparaître dans l'éditeur) et utiliser
  la classe générée (`text-display`, `leading-heading`, `from-hero-glow-start`).
- **Dimensions** : utiliser l'échelle d'espacement (multiples de 0,25rem),
  pas des pixels : `h-125` et non `h-[500px]`, `size-12.5` et non
  `size-[50px]`.
- Couleur d'un `<stop>` SVG : `class="text-<token>" stop-color="currentColor"`.
- **Exceptions admises** (géométrie ou syntaxe, pas des tokens) : positions
  en `calc()`, `mask-[…]`, `transition-[…]`, variantes arbitraires
  (`group-not-has-[…]`), références de variables
  (`text-(length:--wp--preset--font-size--…)`), styles d'impression.
- Ne pas utiliser `size-full` (désactivé : conflit avec la classe que
  Gutenberg pose sur les images) : `w-full h-full`.
- Classes saisies dans l'éditeur ou construites dynamiquement : les déclarer
  avec `@source inline(…)` dans `tailwind-theme.css`, sinon Tailwind ne les
  génère pas. Code d'un plugin Ufo stylé par le thème : ajouter une ligne
  `@source` vers son `src/`.
- CSS de plugin ou de librairie : un fichier par plugin dans
  `tailwind/custom/components/`, avec des sélecteurs propres au plugin (ordre
  d'import alphabétique).

## Éditeur de blocs

- Patterns : un fichier PHP dans `theme/patterns/` avec l'en-tête
  `Title` / `Slug` (`ufo-boilerplate/…`) / `Categories: ufo-boilerplate`.
  Composer avec les blocs natifs et `ufo-blocks/*`.
- Les titres de l'éditeur sont limités à h2–h4 (`functions.php`).
- Les classes Tailwind Typography du contenu viennent de la constante
  `_UFO_TYPOGRAPHY_CLASSES` (front, éditeur de blocs et TinyMCE).

## JavaScript

- Modules ES bundlés par esbuild. Un nouveau point d'entrée doit être ajouté
  au script `development:esbuild` de `package.json`, puis mis en file
  d'attente en `defer` (voir `theme/inc/enqueue-scripts.php`).
- UI en VanJS (`javascript/ui.js`), icônes Lucide (`javascript/icons.js`).

## Accessibilité

Cible WCAG 2.1 AA (voir `docs/accessibilite.md`) : navigation clavier,
`aria-expanded` / `aria-controls` sur les panneaux, gestion du focus,
respect de `prefers-reduced-motion` en CSS et en JS, textes `sr-only` pour
les liens et icônes sans libellé.

## Git

Messages de commit en anglais, à l'impératif, sans préfixe ni emoji
(« Cap the ajax filter's requested page size »). Ne pas commiter les
fichiers générés ni `ufo-boilerplate.zip`.
