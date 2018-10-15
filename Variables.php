<?php
if ( version_compare( $wgVersion, '1.29', '>=' ) ) {
	wfLoadExtension( 'Variables' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['Variables'] = __DIR__ . '/i18n';
	$wgExtensionMessagesFiles['VariablesMagic'] = __DIR__ . '/Variables.i18n.magic.php';
	wfWarn(
		'Deprecated PHP entry point used for Variables extension. Please use wfLoadExtension ' .
		'instead, see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the Variables extension requires MediaWiki 1.29+' );
}
