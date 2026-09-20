<?php
/**
 * Generates theme/languages/ufo-boilerplate.pot by scanning all PHP files
 * in theme/ for gettext calls, using the PHP tokenizer.
 *
 * Fallback used because the make-pot composer script (wp-cli/i18n-command)
 * requires wp-cli, which may not be installed.
 *
 * Run: php node_scripts/make-pot.php
 */

$theme_dir = realpath( __DIR__ . '/../theme' );
$pot_file  = $theme_dir . '/languages/ufo-boilerplate.pot';

$dir = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator( $theme_dir, RecursiveDirectoryIterator::SKIP_DOTS )
);

$entries = array();

$functions = array( '__', '_e', '_x', '_n', '_nx', '_ex', 'esc_html__', 'esc_attr__', 'esc_html_e', 'esc_attr_e', 'esc_html_x', 'esc_attr_x' );

foreach ( $dir as $file ) {
	if ( 'php' !== $file->getExtension() ) {
		continue;
	}
	$path = $file->getPathname();
	$tokens = token_get_all( file_get_contents( $path ) );
	$n = count( $tokens );

	for ( $i = 0; $i < $n; $i++ ) {
		$tok = $tokens[ $i ];
		if ( ! is_array( $tok ) || T_STRING !== $tok[0] || ! in_array( $tok[1], $functions, true ) ) {
			continue;
		}
		$fn = $tok[1];

		// Skip next '('.
		$j = $i + 1;
		while ( $j < $n && ( is_array( $tokens[ $j ] ) && in_array( $tokens[ $j ][0], array( T_WHITESPACE, T_COMMENT ), true ) ) ) {
			$j++;
		}
		if ( $j >= $n || ! is_string( $tokens[ $j ] ) || '(' !== $tokens[ $j ] ) {
			continue;
		}
		$j++;

		// Collect string-literal arguments separated by commas at depth 0.
		$args    = array();
		$depth   = 0;
		$cur     = array();
		$has_str = false;
		for ( ; $j < $n; $j++ ) {
			$t = $tokens[ $j ];
			if ( is_string( $t ) ) {
				if ( '(' === $t ) { $depth++; continue; }
				if ( ')' === $t ) {
					if ( 0 === $depth ) {
						if ( $has_str ) { $args[] = $cur; }
						break;
					}
					$depth--;
					continue;
				}
				if ( ',' === $t && 0 === $depth ) {
					if ( $has_str ) { $args[] = $cur; }
					$cur = array(); $has_str = false;
					continue;
				}
				continue;
			}
			if ( T_CONSTANT_ENCAPSED_STRING === $t[0] ) {
				$cur[] = $t[1];
				$has_str = true;
			}
		}

		if ( empty( $args ) ) {
			continue;
		}

		// Normalize an argument (array of string-literal chunks) to a plain PHP string.
		$norm = function ( $chunks ) {
			$joined = '';
			foreach ( $chunks as $lit ) {
				$joined .= stripcslashes( substr( $lit, 1, -1 ) );
			}
			return $joined;
		};

		$arg_strs = array_map( $norm, $args );

		// Find the text domain argument (last argument) — must be 'ufo-boilerplate'.
		$domain = end( $arg_strs );
		if ( 'ufo-boilerplate' !== $domain ) {
			continue;
		}

		switch ( $fn ) {
			case '__':
			case 'esc_html__':
			case 'esc_attr__':
				$msgid = $arg_strs[0];
				break;
			case '_e':
			case 'esc_html_e':
			case 'esc_attr_e':
				$msgid = $arg_strs[0];
				break;
			case '_x':
			case '_ex':
			case 'esc_html_x':
			case 'esc_attr_x':
				$msgid   = $arg_strs[0];
				$context = $arg_strs[1];
				break;
			case '_n':
				$msgid       = $arg_strs[0];
				$msgid_plural = $arg_strs[1];
				break;
			case '_nx':
				$msgid   = $arg_strs[0];
				$msgid_plural = $arg_strs[1];
				$context = $arg_strs[3];
				break;
			default:
				break;
		}

		if ( '' === $msgid ) {
			continue 2;
		}

		$key = ( isset( $context ) ? $context . "\x04" : '' ) . $msgid;
		if ( ! isset( $entries[ $key ] ) ) {
			$entries[ $key ] = array(
				'msgid'        => $msgid,
				'context'      => isset( $context ) ? $context : null,
				'msgid_plural' => isset( $msgid_plural ) ? $msgid_plural : null,
				'references'   => array(),
			);
		}
		$entries[ $key ]['references'][] = str_replace( dirname( $theme_dir ) . '/', '', $path );
		unset( $context, $msgid_plural );
	}
}

ksort( $entries );

$header = <<<'POT'
# Copyright (C) 2026 Ufo Agency
# This file is distributed under the GNU General Public License v2 or later.
msgid ""
msgstr ""
"Project-Id-Version: Ufo Boilerplate 0.1.0\n"
"Report-Msgid-Bugs-To: https://www.ufo-agency.com\n"
"POT-Creation-Date: 2026-09-02\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"PO-Revision-Date: YEAR-MO-DA HO:MM+ZONE\n"
"Last-Translator: \n"
"Language-Team: \n"
"Language: \n"
"X-Generator: opencode manual extractor\n"
"X-Domain: ufo-boilerplate\n"

POT;

$out = $header;
foreach ( $entries as $entry ) {
	if ( empty( $entry['msgid'] ) ) {
		continue;
	}
	foreach ( array_unique( $entry['references'] ) as $ref ) {
		$out .= '#: ' . $ref . "\n";
	}
	if ( $entry['context'] ) {
		$out .= 'msgctxt "' . addcslashes( $entry['context'], '\\"' ) . "\"\n";
	}
	$out .= 'msgid "' . addcslashes( $entry['msgid'], '\\"' ) . "\"\n";
	if ( $entry['msgid_plural'] ) {
		$out .= 'msgid_plural "' . addcslashes( $entry['msgid_plural'], '\\"' ) . "\"\n";
		$out .= "msgstr[0] \"\"\n";
		$out .= "msgstr[1] \"\"\n";
	} else {
		$out .= "msgstr \"\"\n";
	}
	$out .= "\n";
}

file_put_contents( $pot_file, $out );
echo 'Generated ' . count( $entries ) . " entries -> $pot_file\n";