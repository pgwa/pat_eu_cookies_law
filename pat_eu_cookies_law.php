/**
 * pat_eu_cookies_law plugin. EU Cookies law compliance plugin for Textpattern CMS.
 * @author:  Patrick LEFEVRE.
 * @link:    https://github.com/cara-tm/EU-Cookies-Law-Compliance
 * @type:    Public
 * @prefs:   yes
 * @order:   5
 * @version: 0.1.1
 * @license: GPLv2
 */

/**
 * This plugin tag registry
 */
if (class_exists('\Textpattern\Tag\Registry')) {
	Txp::get('\Textpattern\Tag\Registry')
		->register('pat_eu_cookies_law');
}


/**
 * This plugin admin callbacks
 */
if (txpinterface == 'admin')
{
	register_callback('pat_eu_cookies_law_prefs', 'prefs', '', 1);
	register_callback('pat_eu_cookies_law_cleanup', 'plugin_lifecycle.pat_eu_cookies_law', 'deleted');
}


/**
 * Main plugin function
 *
 * @param array (this plugin attributes)
 * @param string
 * @return string HTML markup
 */
function pat_eu_cookies_law($atts, $thing = null) {

	global $prefs, $path_to_site;

	extract(lAtts(array(

		'lang'       => $prefs['language'],
		'duration'   => '1 Month',
		'force_reload' => false

	), $atts));

	// js expression for force reload on demand
	$force = ($force_reload ? "window.location='';" : "");

	// The default string entries for international translation
	$default = array(
			'refuse' => 'You refuse external third-party cookies: none, at the initiative of this site, is present on your device.', 
			'msg' => 'This website stores some third parts cookies within your device. You can <a href="#!" title="I accept the use of cookies and I close this message" id="ok-cookies">Accept</a> or <a href="#!" title="I refuse to use Cookies and a message will continue to appear" id="no-cookies">Refuse</a> them.',
			'remind' => 'Time remaining before Cookies automatic launch',
			'no_allowed' => 'Currently, your browser is set to disable cookies (check preferences).'
			);

	// Get content of the json translation file based on the 'lang' plugin's attribute
	$json = @file_get_contents($path_to_site.'/json/pat_eu_cookies_law_'.$lang.'.json');

	// Decode the json datas
	$json_datas = json_decode($json, true);

	// Can we proceed? Simple test within the first json value
	if (isset($json_datas['msg']) ) {
		// Loop throught the json
		foreach($json_datas as $key => $value) {
			// Change default values by json ones
			$default[$key] = $value;
		}
	}


	return '<div class="pat_eu_cookies_law"><div id="msg-cookies"></div> <p id="cookie-choices">'.$default['msg'].'<br /><span id="cookies-delay">'.$default['remind'].' <strong id="counter">1:00</strong></span></p></div>'.n._pat_eu_cookies_law_inject( $default['refuse'], $default['no_allowed'], $duration, $force );

}


/**
 * Javascript injector
 *
 * @param  string ($refuse)     A message for users
 * @param  string ($no_allowed) A message for users
 * @param  string ($future)     The delay for the stored user's choice into the internal cookie
 * @return string               javascript code
 */
function _pat_eu_cookies_law_inject($refuse, $no_allowed, $future) {

	global $prefs;

	// Variable convertion for convenient insertion into js. Removes all spaces
	$list = preg_replace('/\s+/', '', $prefs['pat_eu_cookies_law_js']);
	// Converts to an array
	$list = explode(',', $list);
	// Affects each quoted values
	foreach($list as $key => $value) {
		$list[$key] = '"'.$value.'"';
	}
	// $files is a list of quoted strings
	$files = implode($list, ',');

	$out = <<<EOJ
<script>
/*! Simple EU Cookies Law Compliance without dependencies by cara-tm.com, 2017. MIT license - https://github.com/cara-tm/EU-Cookies-Law-Compliance/ */
function EU_cookies_law(b){'use strict';function d(C){return document.getElementById('cookies-delay').innerHTML='',document.getElementById('cookie-choices').innerHTML=C}var f='$refuse',g='$future',k=window.location.hostname,l=navigator.language||navigator.browserLanguage,n=[{$prefs['pat_eu_cookies_law_countries']}],o=0,p=60,q=1,t=document.getElementById('ok-cookies'),u=document.getElementById('no-cookies');if(!1!==navigator.cookieEnabled){for(var w=function(){g=parseInt(g.substring(0,1));var C=new Date(new Date().setMonth(new Date().getMonth()+g));z('Ok',C),A(b),d('')},x=function(C){var D=new RegExp('(?:; )?'+C+'=([^;]*);?');return D.test(document.cookie)?decodeURIComponent(RegExp.$1):null},y=function(){B(),x(k)==='Ok'+k?(d(''),A(b)):x(k)==='No'+k&&d(f)},z=function(C,D){return document.cookie=k+'='+encodeURIComponent(C+k)+';expires='+D.toGMTString()},A=function(C){var D=[],E=document.getElementsByTagName('script')[0];if(!window.scriptHasRun){window.scriptHasRun=!0;for(var F=0;F<C.length;F++)0===C[F]&&window.scriptHasRun||(window.scriptHasRun=!0,D[F]=document.createElement('script'),D[F].src=C[F],document.getElementsByTagName('head')[0].appendChild(D[F])||E.parentNode.insertBefore(D[F],E))}},B=function(){if(null!==document.getElementById('counter')){var C=document.getElementById('counter');p--,null!==typeof C.innerHTML&&(C.innerHTML=(q-1).toString()+':'+(10>p?'0':'')+(p+'')),0<p?setTimeout(B,1e3):1<q,0==p&&(w(),d(''))}else null===document.getElementById('cookies-delay')?'':document.getElementById('cookies-delay').innerHTML=''},v=0;v<n.length;v++)if(n[v]===l.substring(0,2).toUpperCase()){o=1;break}0==o?(d(''),A(b)):y(),t.onclick=function(C){C.preventDefault(),w(C),$force},u.onclick=function(C){C.preventDefault();var D=new Date(new Date().setDate(new Date().getDate()+1));z('No',D),d(f),window.location=''}}else d('$no_allowed')};
/*! Array of third part JS files to load */ 
EU_cookies_law([$files]);
</script>
EOJ;

	return $out;

}


/**
 * This plugin preferences installation
 *
 * @param
 * @return null Safe_field insertions
 */
function pat_eu_cookies_law_prefs() {

	global $textarray;

	$textarray['pat_eu_cookies_law_countries'] = 'List of EU country members codes';
	$textarray['pat_eu_cookies_law_js'] = 'List of javascript files to load';

	if ( !safe_field('name', 'txp_prefs', "name='pat_eu_cookies_law_countries'") ) {
		safe_insert('txp_prefs', "name='pat_eu_cookies_law_countries', val='\'AT\',\'BE\',\'BG\',\'HR\',\'CZ\',\'CY\',\'DK\',\'EE\',\'FI\',\'FR\',\'DE\',\'EL\',\'HU\',\'IE\',\'IT\',\'LV\',\'LT\',\'LU\',\'MT\',\'NL\',\'PL\',\'PT\',\'SK\',\'SI\',\'ES\',\'SE\',\'GB\',\'UK\'', type=1, event='admin', html='text_input', position=21");
	}
	if ( !safe_field('name', 'txp_prefs', "name='pat_eu_cookies_law_js'") ) {
		safe_insert('txp_prefs', "name='pat_eu_cookies_law_js', val=".hu."'js/file.js', type=1, event='admin', html='text_input', position=22");
	}

}


/**
 * This plugin lifecycle for deletion
 *
 * @param
 * @return null Safe_field deletions
 */
function pat_eu_cookies_law_cleanup() {

	// Array of tables & rows to be removed
	$els = array('txp_prefs' => 'pat_eu_cookies_law_countries', 'txp_prefs' => 'pat_eu_cookies_law_js');
	// Process actions
	foreach ($els as $table => $row) {
		safe_delete($table, "name LIKE '".str_replace('_', '\_', $row)."\_%'");
		safe_repair($table);
	}

}
