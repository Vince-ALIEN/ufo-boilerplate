export default () => {
	const plugins = {
		'postcss-import-ext-glob': {},
		'@tailwindcss/postcss': {
			optimize: false,
			transformAssetUrls: false,
		},
		'postcss-advanced-variables': {
			variables: {
				target: process.env._TW_TARGET || 'frontend',
				'where-not':
					'editor' === process.env._TW_TARGET
						? "[class~='not-prose'], [class~='not-prose'] *, [class~='acf-block-body']:not([class~='acf-block-preview']) *"
						: "[class~='not-prose'], [class~='not-prose'] *",
			},
		},
'postcss-nesting': {},
	// Inject the WordPress theme header at the very top of the compiled
	// stylesheet, so it is the first comment WordPress reads (required by the
	// theme headers parser).
	'postcss-header': {
		header: `/*!
Theme Name: Ufo Boilerplate
Theme URI: https://www.ufo-agency.com
Description: A custom theme based on _tw
Version: 0.1.0
Author: Vincent LASSERRE
Author URI: https://www.ufo-agency.com
Text Domain: ufo-boilerplate
Requires at least: 6.2
Requires PHP: 7.4
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This theme, like WordPress, is licensed under the GPL.
Use it to make something cool, have fun, and share what you've learned.

Ufo Boilerplate is based on _tw https://underscoretw.com/, (C) 2021-2026 Greg Sullivan
_tw is distributed under the terms of the GNU GPL v2 or later.

_tw is based on Underscores https://underscores.me/ and Varia https://github.com/Automattic/themes/tree/master/varia, (C) 2012-2026 Automattic, Inc.
Underscores and Varia are distributed under the terms of the GNU GPL v2 or later.
*/`,
	},
};

	if ('production' === process.env._TW_ENV) {
		plugins.cssnano = {};
	}

	if ('intellisense' === process.env._TW_TARGET) {
		delete plugins['@tailwindcss/postcss'];

		plugins.cssnano = {};
		plugins['postcss-advanced-variables'] = {
			importFilter: () => {
				return false;
			},
			unresolved: 'ignore',
			variables: {
				target: 'editor',
			},
		};
		plugins['postcss-header'] = {
			header: '/*!\n * IMPORTANT: This is a generated file. EDITS WILL BE OVERWRITTEN.\n * This file enables full Tailwind CSS Language Server support for all imports.\n */',
		};
	}

	return { plugins };
};
