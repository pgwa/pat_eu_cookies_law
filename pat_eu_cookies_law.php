/**
 * pat_eu_cookies_law plugin. EU Cookies law compliance plugin for Textpattern CMS.
 * @author:  Patrick LEFEVRE.
 * @link:    https://github.com/cara-tm/EU-Cookies-Law-Compliance
 * @type:    Public
 * @prefs:   no
 * @order:   5
 * @version: 0.1.0
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

	), $atts));

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


	return '<div class="pat_eu_cookies_law"><div id="msg-cookies"></div> <p id="cookie-choices">'.$default['msg'].'<br /><span id="cookies-delay">'.$default['remind'].' <strong id="counter">1:00</strong></span></p></div>'.n._pat_eu_cookies_law_inject( $default['refuse'], $default['no_allowed'], $duration );

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

	// Two variables for convenient insertion into js
	$countries = $prefs['pat_eu_cookies_law_countries'];
	$files = $prefs['pat_eu_cookies_law_js'];

	$out = <<<EOJ
<script>
/*! Simple EU Cookies Law Compliance without dependencies by cara-tm.com, 2017. MIT license - https://github.com/cara-tm/EU-Cookies-Law-Compliance/ */
function EU_cookies_law(r){'use strict';var msg='$refuse',future='$future',minutes=1,no_alowed_cookies='$no_allowed',domain=window.location.hostname,lang=(navigator.language||navigator.browserLanguage),countries=[$countries],affected=0,seconds=60,mins=minutes,accept_cookies=document.getElementById('ok-cookies'),refuse_cookies=document.getElementById('no-cookies');if(false!==navigator.cookieEnabled){for(var i=0;i<countries.length;i++){if(countries[i]===lang.substring(0,2).toUpperCase()){affected=1;break;}}if(affected==0){sanitize_msg('');jsloader(r);}else check_cookies();accept_cookies.onclick=function(evt){evt.preventDefault();launch(evt);};function launch(){future=parseInt(future.substring(0,1));var expires=new Date(new Date().setMonth(new Date().getMonth()+future));cookie_creation('Ok',expires);jsloader(r);sanitize_msg('');}refuse_cookies.onclick=function(evt){var tomorrow=new Date(new Date().setDate(new Date().getDate()+1));cookie_creation('No',tomorrow);sanitize_msg(msg);evt.preventDefault();window.location='';};function getCookie(sName){var oRegex=new RegExp('(?:; )?'+sName+'=([^;]*);?');if(oRegex.test(document.cookie)) return decodeURIComponent(RegExp.$1);else return null;}function check_cookies(){tick();if(getCookie(domain)==='Ok'+domain){sanitize_msg('');jsloader(r);}else if(getCookie(domain)==='No'+domain){sanitize_msg(msg);}}function cookie_creation(c,e){return document.cookie=domain+'='+encodeURIComponent(c+domain)+';expires='+e.toGMTString();}function jsloader(r){var s=[];var a=document.getElementsByTagName("script")[0];if(!window.scriptHasRun){window.scriptHasRun=true;for(var i=0;i<r.length;i++){if(r[i]!==0||!window.scriptHasRun){window.scriptHasRun=true;s[i]=document.createElement('script');s[i].src=r[i];document.getElementsByTagName('head')[0].appendChild(s[i])||a.parentNode.insertBefore(s[i],a);}}}}function tick(){if(minutes!=0&&null!==document.getElementById('counter')){var counter=document.getElementById('counter'),current_minutes=mins-1;seconds--;if(typeof counter.innerHTML!==null)counter.innerHTML=current_minutes.toString()+':'+(seconds<10?'0':'')+String(seconds);if(seconds>0){setTimeout(tick,1000);}else{if(mins>1){countdown(mins-1);}}if(seconds==0){launch();sanitize_msg('');}}else{null!==document.getElementById('cookies-delay')?document.getElementById('cookies-delay').innerHTML='':'';}}}else{sanitize_msg(no_alowed_cookies);}function sanitize_msg(m){document.getElementById("cookies-delay").innerHTML='';return document.getElementById('cookie-choices').innerHTML=m;}};
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

	$textarray['pat_eu_cookies_law_countries'] = 'List of EU country members code';
	$textarray['pat_eu_cookies_law_js'] = 'List of javascript files to load';

	if ( !safe_field('name', 'txp_prefs', "name='pat_eu_cookies_law_countries'") ) {
		safe_insert('txp_prefs', "name='pat_eu_cookies_law_countries', val='\'AT\',\'BE\',\'BG\',\'HR\',\'CZ\',\'CY\',\'DK\',\'EE\',\'FI\',\'FR\',\'DE\',\'EL\',\'HU\',\'IE\',\'IT\',\'LV\',\'LT\',\'LU\',\'MT\',\'NL\',\'PL\',\'PT\',\'SK\',\'SI\',\'ES\',\'SE\',\'GB\',\'UK\'', type=1, event='admin', html='text_input', position=21");
	}
	if ( !safe_field('name', 'txp_prefs', "name='pat_eu_cookies_law_js'") ) {
		safe_insert('txp_prefs', "name='pat_eu_cookies_law_js', val='js/file.js', type=1, event='admin', html='text_input', position=22");
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