/**
 * Block editor modifications
 *
 * This file is loaded only by the block editor. Use it to modify the block
 * editor via its APIs.
 *
 * The JavaScript code you place here will be processed by esbuild, and the
 * output file will be created at `../theme/js/block-editor.min.js` and
 * enqueued in `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */

/**
 * This import adds your front-end post title and Tailwind Typography classes
 * to the block editor. It also adds some helper classes so you can access the
 * post type when modifying the block editor’s appearance.
 */
import '@_tw/typography/block-editor-classes';

wp.domReady(() => {
	/**
	 * Add support for Tailwind Typography’s `lead` class via a block style.
	 */
	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'lead',
		label: 'Lead',
	});

	/**
	 * Overline label placed above a heading — see the `.is-style-text-label`
	 * rule in `tailwind/custom/utilities.css`. Keeping the label a paragraph
	 * (rather than a heading) means the page's heading outline only lists
	 * the real titles.
	 */
	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'text-label',
		label: 'Label',
	});

	/**
	 * Replace the bullet with a check icon — see the `.is-style-checklist`
	 * rule in `tailwind/custom/components/components.css`. Only takes effect
	 * on an unordered list (the CSS selector is scoped to `ul`).
	 */
	wp.blocks.registerBlockStyle('core/list', {
		name: 'checklist',
		label: 'Coche',
	});

	// Add additional block editor modifications here. For example, you could
	// register another block style:
	//
	// wp.blocks.registerBlockStyle( 'core/quote', {
	// 	name: 'fancy-quote',
	// 	label: 'Fancy Quote',
	// } );
});

/**
 * Document sidebar panel for the two header CTA buttons rendered in
 * `template-parts/content/content-page.php`. Values are stored as post meta
 * registered in `inc/cta-buttons.php`.
 */
function CtaButtonsPanel() {
	const { createElement: h } = wp.element;
	const { PluginDocumentSettingPanel } = wp.editPost;
	const { PanelRow, TextControl } = wp.components;
	const { useSelect, useDispatch } = wp.data;
	const { __ } = wp.i18n;

	const postType = useSelect(
		(select) => select('core/editor').getCurrentPostType(),
		[]
	);

	const meta = useSelect(
		(select) => select('core/editor').getEditedPostAttribute('meta'),
		[]
	);

	const { editPost } = useDispatch('core/editor');

	if ('page' !== postType) {
		return null;
	}

	const updateMeta = (metaKey, value) =>
		editPost({ meta: { ...meta, [metaKey]: value } });

	const fields = [
		['_ufo_cta_primary_label', __('Bouton principal — texte', 'ufo-boilerplate')],
		['_ufo_cta_primary_url', __('Bouton principal — URL', 'ufo-boilerplate')],
		['_ufo_cta_secondary_label', __('Bouton secondaire — texte', 'ufo-boilerplate')],
		['_ufo_cta_secondary_url', __('Bouton secondaire — URL', 'ufo-boilerplate')],
	];

	return h(
		PluginDocumentSettingPanel,
		{ name: 'ufo-cta-buttons', title: __('Boutons d’en-tête', 'ufo-boilerplate') },
		...fields.map(([metaKey, label]) =>
			h(
				PanelRow,
				{ key: metaKey },
				h(TextControl, {
					label,
					value: (meta && meta[metaKey]) || '',
					onChange: (value) => updateMeta(metaKey, value),
					__nextHasNoMarginBottom: true,
				})
			)
		)
	);
}

wp.domReady(() => {
	wp.plugins.registerPlugin('ufo-cta-buttons', { render: CtaButtonsPanel });
});
