<?php
/**
 * Title: Grille tarifaire (3 offres)
 * Slug: ufo-boilerplate/pricing-tiers
 * Categories: ufo-boilerplate
 * Description: Section tarifs à 3 colonnes, construite avec ufo-grid/ufo-row/ufo-title/ufo-button.
 * Viewport Width: 1400
 *
 * @package Ufo_Boilerplate
 */

?>
<!-- wp:group {"className":"ufo-pricing-tiers py-16 sm:py-24 group/tiers"} -->
<div class="wp-block-group ufo-pricing-tiers py-16 sm:py-24 group/tiers">

<!-- wp:group {"className":"max-w-2xl mx-auto text-center"} -->
<div class="wp-block-group max-w-2xl mx-auto text-center">

<!-- wp:paragraph {"className":"text-base font-semibold text-primary"} -->
<p class="text-base font-semibold text-primary">Tarifs</p>
<!-- /wp:paragraph -->

<!-- wp:ufo-blocks/ufo-title {"content":"Des tarifs qui grandissent avec vous","level":2,"titleAlignment":"center","fontSize":"text-4xl"} /-->

<!-- wp:paragraph {"className":"mt-6 text-lg text-slate-600"} -->
<p class="mt-6 text-lg text-slate-600">Choisissez la formule adaptée à votre activité, sans engagement.</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->

<!-- wp:html -->
<div class="mt-16 flex justify-center">
	<fieldset aria-label="Fréquence de facturation">
		<div class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs/5 font-semibold inset-ring inset-ring-slate-200">
			<label class="group relative rounded-full px-2.5 py-1 has-checked:bg-primary">
				<input type="radio" name="frequency" value="monthly" checked class="absolute inset-0 appearance-none rounded-full" />
				<span class="text-slate-500 group-has-checked:text-background">Mensuel</span>
			</label>
			<label class="group relative rounded-full px-2.5 py-1 has-checked:bg-primary">
				<input type="radio" name="frequency" value="annually" class="absolute inset-0 appearance-none rounded-full" />
				<span class="text-slate-500 group-has-checked:text-background">Annuel</span>
			</label>
		</div>
	</fieldset>
</div>
<!-- /wp:html -->

<!-- wp:ufo-blocks/ufo-grid {"columnsCount":3,"gap":"8","paddingX":"0","paddingY":"0","className":"mt-16"} -->
<div class="wp-block-ufo-blocks-ufo-grid relative ufo-grid-wrapper px-0 py-0 mt-16">
<div class="grid grid-cols-1 md:auto-rows-fr auto-rows-auto gap-8 md:grid-cols-3">

<!-- wp:ufo-blocks/ufo-row {"paddingX":"8","paddingY":"8","borderRadius":"3xl","className":"ring-1 ring-slate-200"} -->
<div class="wp-block-ufo-blocks-ufo-row ufo-row rounded-3xl px-8 py-8 ring-1 ring-slate-200">

<!-- wp:group {"className":"flex items-center justify-between gap-x-4"} -->
<div class="wp-block-group flex items-center justify-between gap-x-4">
<!-- wp:ufo-blocks/ufo-title {"content":"Indépendant","level":3,"fontSize":"text-lg"} /-->
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"mt-4 text-sm text-slate-600"} -->
<p class="mt-4 text-sm text-slate-600">L'essentiel pour démarrer et facturer vos premiers clients.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=monthly]:checked]/tiers:hidden"} -->
<p class="mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=monthly]:checked]/tiers:hidden"><span class="text-4xl font-semibold tracking-tight text-foreground">19&nbsp;€</span><span class="text-sm font-semibold text-slate-600">/mois</span></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=annually]:checked]/tiers:hidden"} -->
<p class="mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=annually]:checked]/tiers:hidden"><span class="text-4xl font-semibold tracking-tight text-foreground">190&nbsp;€</span><span class="text-sm font-semibold text-slate-600">/an</span></p>
<!-- /wp:paragraph -->

<!-- wp:ufo-blocks/ufo-buttons-group {"buttonAlignment":"left","className":"mt-6"} -->
<div class="wp-block-ufo-blocks-ufo-buttons-group mt-6">
<div class="lg:flex lg:flex-nowrap py-2 gap-2 lg:space-y-0 space-y-2 justify-start">
<!-- wp:ufo-blocks/ufo-button {"buttonText":"Choisir cette offre","buttonUrl":"#","className":"is-style-secondary w-full justify-center"} /-->
</div>
</div>
<!-- /wp:ufo-blocks/ufo-buttons-group -->

<!-- wp:list {"className":"is-style-checklist mt-8 space-y-3 text-sm text-slate-600"} -->
<ul class="wp-block-list is-style-checklist mt-8 space-y-3 text-sm text-slate-600">
<!-- wp:list-item -->
<li>5 projets actifs</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Jusqu'à 1 000 contacts</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Statistiques de base</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Support sous 48h</li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->

</div>
<!-- /wp:ufo-blocks/ufo-row -->

<!-- wp:ufo-blocks/ufo-row {"paddingX":"8","paddingY":"8","borderRadius":"3xl","className":"ring-2 ring-primary"} -->
<div class="wp-block-ufo-blocks-ufo-row ufo-row rounded-3xl px-8 py-8 ring-2 ring-primary">

<!-- wp:group {"className":"flex items-center justify-between gap-x-4"} -->
<div class="wp-block-group flex items-center justify-between gap-x-4">
<!-- wp:ufo-blocks/ufo-title {"content":"Croissance","level":3,"fontSize":"text-lg","textColor":"primary"} /-->

<!-- wp:paragraph {"className":"rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary"} -->
<p class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">Le plus populaire</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"mt-4 text-sm text-slate-600"} -->
<p class="mt-4 text-sm text-slate-600">Une formule qui suit le rythme d'une activité en pleine croissance.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=monthly]:checked]/tiers:hidden"} -->
<p class="mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=monthly]:checked]/tiers:hidden"><span class="text-4xl font-semibold tracking-tight text-foreground">39&nbsp;€</span><span class="text-sm font-semibold text-slate-600">/mois</span></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=annually]:checked]/tiers:hidden"} -->
<p class="mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=annually]:checked]/tiers:hidden"><span class="text-4xl font-semibold tracking-tight text-foreground">390&nbsp;€</span><span class="text-sm font-semibold text-slate-600">/an</span></p>
<!-- /wp:paragraph -->

<!-- wp:ufo-blocks/ufo-buttons-group {"buttonAlignment":"left","className":"mt-6"} -->
<div class="wp-block-ufo-blocks-ufo-buttons-group mt-6">
<div class="lg:flex lg:flex-nowrap py-2 gap-2 lg:space-y-0 space-y-2 justify-start">
<!-- wp:ufo-blocks/ufo-button {"buttonText":"Choisir cette offre","buttonUrl":"#","className":"is-style-primary w-full justify-center"} /-->
</div>
</div>
<!-- /wp:ufo-blocks/ufo-buttons-group -->

<!-- wp:list {"className":"is-style-checklist mt-8 space-y-3 text-sm text-slate-600"} -->
<ul class="wp-block-list is-style-checklist mt-8 space-y-3 text-sm text-slate-600">
<!-- wp:list-item -->
<li>25 projets actifs</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Jusqu'à 10 000 contacts</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Statistiques avancées</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Support sous 24h</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Automatisations marketing</li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->

</div>
<!-- /wp:ufo-blocks/ufo-row -->

<!-- wp:ufo-blocks/ufo-row {"paddingX":"8","paddingY":"8","borderRadius":"3xl","className":"ring-1 ring-slate-200"} -->
<div class="wp-block-ufo-blocks-ufo-row ufo-row rounded-3xl px-8 py-8 ring-1 ring-slate-200">

<!-- wp:group {"className":"flex items-center justify-between gap-x-4"} -->
<div class="wp-block-group flex items-center justify-between gap-x-4">
<!-- wp:ufo-blocks/ufo-title {"content":"Entreprise","level":3,"fontSize":"text-lg"} /-->
</div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"mt-4 text-sm text-slate-600"} -->
<p class="mt-4 text-sm text-slate-600">Support dédié et infrastructure adaptée aux grands comptes.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=monthly]:checked]/tiers:hidden"} -->
<p class="mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=monthly]:checked]/tiers:hidden"><span class="text-4xl font-semibold tracking-tight text-foreground">79&nbsp;€</span><span class="text-sm font-semibold text-slate-600">/mois</span></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=annually]:checked]/tiers:hidden"} -->
<p class="mt-6 flex items-baseline gap-x-1 group-not-has-[[name=frequency][value=annually]:checked]/tiers:hidden"><span class="text-4xl font-semibold tracking-tight text-foreground">790&nbsp;€</span><span class="text-sm font-semibold text-slate-600">/an</span></p>
<!-- /wp:paragraph -->

<!-- wp:ufo-blocks/ufo-buttons-group {"buttonAlignment":"left","className":"mt-6"} -->
<div class="wp-block-ufo-blocks-ufo-buttons-group mt-6">
<div class="lg:flex lg:flex-nowrap py-2 gap-2 lg:space-y-0 space-y-2 justify-start">
<!-- wp:ufo-blocks/ufo-button {"buttonText":"Choisir cette offre","buttonUrl":"#","className":"is-style-secondary w-full justify-center"} /-->
</div>
</div>
<!-- /wp:ufo-blocks/ufo-buttons-group -->

<!-- wp:list {"className":"is-style-checklist mt-8 space-y-3 text-sm text-slate-600"} -->
<ul class="wp-block-list is-style-checklist mt-8 space-y-3 text-sm text-slate-600">
<!-- wp:list-item -->
<li>Projets illimités</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Contacts illimités</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Statistiques avancées</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Support dédié sous 1h</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Rapports sur mesure</li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->

</div>
<!-- /wp:ufo-blocks/ufo-row -->

</div>
</div>
<!-- /wp:ufo-blocks/ufo-grid -->

</div>
<!-- /wp:group -->
