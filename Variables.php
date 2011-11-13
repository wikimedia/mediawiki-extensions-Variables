<?php

/**
 * 'Variables' introduces parser functions for defining page-scoped variables within
 * wiki pages.
 * 
 * Documentation: http://www.mediawiki.org/wiki/Extension:Variables
 * Support:       http://www.mediawiki.org/wiki/Extension_talk:Variables
 * Source code:   http://svn.wikimedia.org/viewvc/mediawiki/trunk/extensions/Variables
 * 
 * @version: 1.4
 * @license: Public domain
 * @author: Rob Adams
 * @author: Tom Hempel
 * @author: Xiloynaha
 * @author: Daniel Werner < danweetz@web.de >
 *
 * @file Variables.php
 * @ingroup Variables
 * 
 * @ToDo:
 * FIXME: Fixing bugs related to the fact that there are several Parser instances within the wiki
 *        which all could trigger 'ParserFirstCallInit'. E.g. special page transclusion will clear
 *        all variables defined before! Variables should have one store per Parser instance.
 */

if ( ! defined( 'MEDIAWIKI' ) ) { die( ); }
 
$wgExtensionCredits['parserhook'][] = array(
	'path'           => __FILE__,
	'name'           => 'Variables',
	'descriptionmsg' => 'variables-desc',
	'version'        => ExtVariables::VERSION,
	'author'         => array( 'Rob Adams', 'Tom Hempel', 'Xiloynaha', '[http://www.mediawiki.org/wiki/User:Danwe Daniel Werner]' ),
	'url'            => 'http://www.mediawiki.org/wiki/Extension:Variables',
);

$dir = dirname( __FILE__ );

// language files:
$wgExtensionMessagesFiles['Variables'     ] = $dir . '/Variables.i18n.php';
$wgExtensionMessagesFiles['VariablesMagic'] = $dir . '/Variables.i18n.magic.php';

unset ( $dir );

// hooks registration:
$wgHooks['ParserFirstCallInit'][] = 'ExtVariables::init';


class ExtVariables {
	
	/**
	 * Version of the 'Variables' extension.
	 * 
	 * @since 1.4
	 * 
	 * @var string
	 */
	const VERSION = '1.4';
	
	var $mVariables = array();
	
	/**
	 * Sets up parser functions
	 * 
	 * @since 1.4
	 */
	static function init( Parser $parser ) {
		global $wgExtVariables, $wgHooks;

		$wgExtVariables = new self();
		$wgHooks['ParserClearState'][] = $wgExtVariables; // hooks registration

		$parser->setFunctionHook( 'vardefine',     array( &$wgExtVariables, 'vardefine' ) );
		$parser->setFunctionHook( 'vardefineecho', array( &$wgExtVariables, 'vardefineecho' ) );
		$parser->setFunctionHook( 'var',           array( &$wgExtVariables, 'varf' ) );
		$parser->setFunctionHook( 'varexists',     array( &$wgExtVariables, 'varexists' ) );	
				
		return true;
	}
	
	function onParserClearState( &$parser ) {
		$this->mVariables = array(); //remove all variables to avoid conflicts with job queue or Special:Import
		return true;
	}
	
	function vardefine( &$parser, $expr = '', $value = '' ) {
		$this->mVariables[ $expr ] = $value;
		return '';
	}
	
	function vardefineecho( &$parser, $expr = '', $value = '' ) {
		$this->mVariables[ $expr ] = $value;
		return $value;
	}
	
	function varf( &$parser, $expr = '', $defaultVal = '' ) {
		if ( isset( $this->mVariables[ $expr ] ) && $this->mVariables[ $expr ] !== '' ) {
			return $this->mVariables[ $expr ];
		} else {
			return $defaultVal;
		}
	}
	
	function varexists( &$parser, $expr = '' ) {
		return array_key_exists( $expr, $this->mVariables );
	}
}
