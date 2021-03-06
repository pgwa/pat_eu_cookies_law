# pat_eu_cookies_law
EU Cookie Law Compliance: A Textpattern plugin for Third-Party Cookies.

![pat_eu_cookies_law Widget](https://raw.githubusercontent.com/cara-tm/pat_eu_cookies_law/master/pat_eu_cookies_law_widget.png)

A simple solution that respects the EU Cookies law. Concerns only the external third-party cookies (the purpose of this law) but preserves the objectives for web marketers.
Displays a warning message for EU member visitors only. Displays explicit and responsible information messages.
Offers to visitors a double choice: acceptance or refusal of Cookies. Automatically loads js files that generate third party external cookies after a 60 seconds delay on the lack of choice of visitors. 
Keeps the visitors choice in order to avoid excessive call to action: 24 hours for refusals; 1 month (can be set) for acceptances.
Promotes a subtil acceptance of Cookies : displays a message for refusals only but removes all informations for acceptances.
Detects the ban of all cookies based on the browser's preferences.
Support for translations with JSON files.
Low impact that preserves the speed of page display. Pure javascript without requiring third-party libraries.

## _To Do (in process next version)_:
* ~~_Ability to display a message if javascript is desabled in the browser_~~ (done);
* ~~Ability to show a message if cookies support is disabled within the browser~~ (done);
* ~~_Injection into the HTML document as a whole widget (without the need to create the base markup)_~~ (done with a js micro template engine);
* ~~_Use of LocalStorage for modern browsers (Google Chromium 4+, FireFox 3.5+, Safari 4+, Opera 10.5+, IE 8+, iOS 3.2+, Android 2.1+, Opera Mobile 11+) and keep cookie creation for old ones_~~ (done);
* ~~Ability to choose automatic loading for external js files or not, ability to show the widget on demand~~ (done);
* ~~Identify logging-in users and do not load external js files (to avoid unnecessary trackings)~~ (done);
* ~~Load external js files by default for non EU member visitors~~ (done);
* ~~Create a Geolocalisation for non EU residents surfing from a EU member country~~ (done);
* ~~Shrink the plugin script in 2 parts to reduce the amount of code injection~~ (some: reduicing the script to get 4892 bytes and 2149 bytes gziped);
* ~~Add a setting to create sha384 hashes for each integrity attributes into script links (optional)~~ (done);
* ~~Create a tag in order to remove on demand markers ; create a bookmarklet for easy cross domain markers deletion~~ (done);
* ~~Protect script against XSS Attack~~ (done);
* Ability to load external files before user's choice (optional);
* Consider Google AMP support (with the use of pat_if_amp);
* Create a back-admin page for the plugin settings.

# Plugin Preferences

After plugin installation and activation, visit your _Site Preferences_ page to set:

* A comma separated list of js files to load if your visitors accept the use of Cookies into the "List of javascript files to load" field (i.e. http://my-domain.com/js/first-file.js, http://my-domain.com/js/second-file.js);
* The 4 letters countries's codes for the EU members (usefull for country deletion or addition. See the "List of EU country members codes" field).

# JSON Files for Translations

Install the `JSON` folder and its content into your `/root/` directory.
Note. The plugin use international translation by default if you don't use the correcponding JSON files.

# Plugin Attributes

* `lang` (string): the 4 letters language choice for localisation (i.e. `lang="en-us"`). Default: the active language preference sets within the Textpattern _Languages_ page.
* `duration` (string or integer): the delay in months for the saving user's choice. Default: `1 Month` (can be set with a number only. i.e. `1`).
* `force_reload` (boolean `1` or `0`): if set to `1` (true) the page will be reloaded on user acceptation. Default: `0`(false).
* `infos` (string) [named: `section` in v 0.1.7]: a section name where you want to display your legal staatement or confidential policy. That attribute create a link after the counting delay. Defaut: empty (no link creation).
* `more` (string): the label of the link above for translation conveniencies. Default: empty.

# JS files that generate cookies

Some third-parties libraries generate external cookies. For example the Google Analytics script or the scripts used by Social Networks. Create such a file (or multiple ones) for this acceptance by your visitors in order to respect the EU Cookies law.
You can find the [ga-lite script](https://github.com/cara-tm/pat_eu_cookies_law/blob/master/js/ga-lite.min.js) and the file to load (`file.js`) with your Google Analytics account number in this repository for your analytics as an example.

# Advice

Because Googlebot is based, currently, on Chromium 41(See: [https://developers.google.com/search/docs/guides/rendering](https://developers.google.com/search/docs/guides/rendering)) that do not supports Cookies, you can use this plugin to keep a hight level 100/100 Google Page Insights score by placing all Google adWords scripts into one, external then charging the plugin to load it (this plugin keeps an eye on "Cookies" feature disabled within the browser and do not load the file(s) in this case).

# Usage Example

Best place into a page template (perhaps into your "footer"):

    <txp:pat_eu_cookies_law lang="en" duration="2 Months" force_reload="1" infos="legal-statement" more="Read more" />

# CSS sample

Here is a starting point for your 'Cookies' widget:

    #pat_eu_cookies_law {
    	overflow: auto;
    	position: absolute;
    	position: fixed;
    	z-index: 10012;
    	left: 2%;
    	bottom: 5px;
    	width: 60%;
    	min-width: 450px;
    	max-width: 48em;
    	max-width: calc(100% - 48px);
    	height: auto;
    	margin: 24px auto;
    	padding: 1em;
    	text-align: center;
    	cursor: default;
    	-webkit-user-select: none;
    	-moz-user-select: none;
    	-ms-user-select: none;
    	user-select: none
    }

    @media only screen and (max-width: 689px) {

    	#pat_eu_cookies_law {
		overflow: initial;
		position: relative;
		left: 0;
		width: 98%;
		min-width: 0;
		max-width: none;
		max-height: none;
		margin: 0 1% 0;
		padding: 8px 0
    	}

    }

    @media only screen and (max-height: 500px)  and (max-width: 520px) {

		#pat_eu_cookies_law {position: static}

    }
    
    .animated {
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both
    }
    
    @-webkit-keyframes fadeInUp {
        from {
	    opacity: 0;
	    -webkit-transform: translate3d(0, 100%, 0);
	    transform: translate3d(0, 100%, 0)
        }
        to {
	    opacity: 1;
	    -webkit-transform: translate3d(0, 0, 0);
	    transform: translate3d(0, 0, 0)
        }
    }
    
    @keyframes fadeInUp {
        from {
	    opacity: 0;
	    -webkit-transform: translate3d(0, 100%, 0);
	    transform: translate3d(0, 100%, 0)
        }
        to {
	    opacity: 1;
	    -webkit-transform: translate3d(0, 0, 0);
	    transform: translate3d(0, 0, 0)
        }
    }
    
    .fadeInUp {
        -webkit-animation-name: fadeInUp;
        animation-name: fadeInUp
    }
    
    @media (prefers-reduced-motion) {
    
        html .animated {
	        -webkit-animation: unset;
	        animation: unset;
	        -webkit-transition: none;
	        transition: none
        }

    }

    #msg-cookies {
    	width: 93%;
    	padding: 1em 0;
    	color: #979797;
    	border: 1px solid #eee;
    	border-radius: 2px;
    	-moz-box-shadow:  0 17px 17px rgba(0,0,0,.15), 0 5px 10px rgba(0,0,0,.2);
    	-webkit-box-shadow:  0 17px 17px rgba(0,0,0,.15), 0 5px 10px rgba(0,0,0,.2);
    	box-shadow: 0 17px 17px rgba(0,0,0,.15), 0 5px 10px rgba(0,0,0,.2)
    }

    #msg-cookies p {
    	background: #fff;
    	color: #757575;
    	font: 14px/20px 'Open Sans','Helvetica Neue','HelveticaNeue',Helvetica,Arial,sans-serif
    }

    #msg-cookies p:first-child::before {
	    content: '🍪';
	    width: auto;
	    height: 1em;
	    margin-right: .2em;
	    vertical-align: middle;
	    color: #dda000;
	    font-size: 1.4em;
	    line-height: 1;
	    text-shadow:0 0 0 #dda000
    }

    @media only screen and (max-width: 740px) {

		#msg-cookies p {
			left: 0;
			max-width: none;
			width: 94%;
			margin: 0 1% 
		}

    }

    #msg-cookies p a {
    	display: inline-block;
    	width: auto;
    	margin: .2em 0;
    	padding: 6px 8px;
    	background-color: transparent;
    	border: 1px solid #d7f2fe;
    	vertical-align: middle;
    	text-overflow: ellipsis;
    	text-transform: uppercase;
    	white-space: nowrap;
    	color: #039be5;
    	cursor: pointer;
    	box-shadow: none;
    	transition: background-color .2s,box-shadow .2s 
    }

    #msg-cookies a:hover {
    	background-color: rgb(223,241,250);
    	tex-decoration: none;
    	color:#039be5;
    	box-shadow: 0 0 1px #039be5 
    }
    
    #pat_result-cookies {
    	position: absolute;
    	bottom: 10px;
    	width: 98%;
    	margin: 0 1%;
    	text-align: center;
    	font: normal normal normal 100%/1.2 'Helvetica Neue', 'HelveticaNeue', Helvetica, Arial, sans-serif
    }

Here is the minified version (size: 1.92 Kb):

    #pat_eu_cookies_law{overflow:auto;position:absolute;position:fixed;z-index:10012;left:2%;bottom:5px;width:60%;min-width:450px;max-width:48em;max-width:calc(100% - 48px);height:auto;max-height:calc(100% - 48px);margin:24px auto;padding:1em;text-align:center;cursor:default;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}@media only screen and (max-width:689px){#pat_eu_cookies_law{overflow:initial;position:relative;left:0;width:98%;min-width:0;max-width:none;max-height:none;margin:0 1% 0;padding:8px 0}}@media only screen and (max-height:500px) and (max-width:520px){#pat_eu_cookies_law{position:static}}#msg-cookies{width:93%;padding:1em 0;color:#979797;border:1px solid #eee;border-radius:2px;-moz-box-shadow:0 17px 17px rgba(0,0,0,.15),0 5px 10px rgba(0,0,0,.2);-webkit-box-shadow:0 17px 17px rgba(0,0,0,.15),0 5px 10px rgba(0,0,0,.2);box-shadow:0 17px 17px rgba(0,0,0,.15),0 5px 10px rgba(0,0,0,.2)}#msg-cookies p{background:#fff;color:#757575;font:14px/20px 'Open Sans','Helvetica Neue','HelveticaNeue',Helvetica,Arial,sans-serif}#msg-cookies p:first-child::before{content:'🍪';width:auto;height:1em;margin-right:.2em;vertical-align:middle;color:#dda000;font-size:1.4em;line-height:1;text-shadow:0 0 0 #dda000}@media only screen and (max-width:740px){#msg-cookies p{left:0;max-width:none;width:94%;margin:0 1%}}#msg-cookies p a{display:inline-block;width:auto;margin:.2em 0;padding:6px 8px;background-color:transparent;border:1px solid #d7f2fe;vertical-align:middle;text-overflow:ellipsis;text-transform:uppercase;white-space:nowrap;color:#039be5;cursor:pointer;box-shadow:none;transition:background-color .2s,box-shadow .2s}#msg-cookies a:hover{background-color:rgb(223,241,250);tex-decoration:none;color:#039be5;box-shadow:0 0 1px #039be5}#pat_result-cookies{position:absolute;bottom:10px;width:98%;margin:0 1%;text-align:center;font:normal normal normal 100%/1.2 'Helvetica Neue','HelveticaNeue',Helvetica,Arial,sans-serif}
 

# Advices

Don't miss to create a _Legal_ page within your website and precise the purpose and the use of external third-party Cookies; the delay for automatic acceptance of the use of Cookies stored into the visitor's device; and the keeping time for Cookies acceptances and future visits. Thus, you will be into the total respect of the EU law and the respect of legal informations for your visitors.

# GDPR

https://eugdprcompliant.com/cookies-consent-gdpr/

# Changelog

* 9th August 2018: pre-release v0.1.7: two methods included (localStorage or Cookie), widget injected from a "micro js template engine", disabled javascript detection within the browser (warning message). 
* 17th March 2018: version 0.1.6
* 3td January 2018: version 0.1.5
* 2nd January 2018: version 0.1.4
* 16th December 2017: version 0.1.3
* 8th December 2017: version 0.1.2
* 27th July 2017: version 0.1.1
* 15th July 2017: version 0.1


