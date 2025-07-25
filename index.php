<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

/** The Pepperminty Wiki core */
$start_time = microtime(true);
mb_internal_encoding("UTF-8");

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

/*
 * Pepperminty Wiki
 * ================
 * Inspired by Minty Wiki by am2064
	* Link: https://github.com/am2064/Minty-Wiki
 * 
 * Credits:
	* Code by @Starbeamrainbowlabs
	* Parsedown - by erusev and others on github from http://parsedown.org/
	* Mathematical Expression rendering
		* Code: @con-f-use <https://github.com/con-f-use>
		* Rendering: MathJax (https://www.mathjax.org/)
 * Bug reports:
	* #2 - Incorrect closing tag - nibreh <https://github.com/nibreh/>
	* #8 - Rogue <datalist /> tag - nibreh <https://github.com/nibreh/>
 */
$guiConfig = <<<'GUICONFIG'
{
	"firstrun_complete": { "type": "checkbox", "description": "Whether the first-run wizard has completed or not.", "default": false },
	"sitename": { "type": "text", "description": "Your wiki's name.", "default": "Pepperminty Wiki" },
	"defaultpage": { "type": "text", "description": "The name of the page that will act as the home page for the wiki. This page will be served if you don't specify a page.", "default": "Main Page" },
	"admindetails_name": { "type": "text", "description": "Your name as the wiki administrator.", "default": "Administrator" },
	"admindetails_email": { "type": "email", "description": "Your email address as the wiki administrator. Will be displayed as a support contact address.", "default": "admin@localhost" },
	"favicon": { "type": "url", "description": "A url that points to the favicon you want to use for your wiki. By default this is set to a data: url of a Peppermint (Credit: by bluefrog23, source: https://openclipart.org/detail/19571/peppermint-candy-by-bluefrog23)", "default": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAB3VBMVEXhERHbKCjeVVXjb2/kR0fhKirdHBziDg6qAADaHh7qLy/pdXXUNzfMAADYPj7ZPDzUNzfbHx/fERHpamrqMTHgExPdHx/bLCzhLS3fVFTjT0/ibm7kRkbiLi7aKirdISHeFBTqNDTpeHjgERHYJCTVODjYQkLaPj6/AADVOTnpbW3cIyPdFRXcJCThMjLiTU3ibW3fVVXaKyvcERH4ODj+8fH/////fHz+Fxf4KSn0UFD/CAj/AAD/Xl7/wMD/EhL//v70xMT/+Pj/iYn/HBz/g4P/IyP/Kyv/7Oz0QUH/9PT/+vr/ior/Dg7/vr7/aGj/QED/bGz/AQH/ERH/Jib/R0f/goL/0dH/qan/YWH/7e3/Cwv4R0f/MTH/enr/vLz/u7v/cHD/oKD/n5//aWn+9/f/k5P/0tL/trb/QUH/cXH/dHT/wsL/DQ3/p6f/DAz/1dX/XV3/kpL/i4v/Vlb/2Nj/9/f/pKT+7Oz/V1f/iIj/jIz/r6//Zmb/lZX/j4//T0//Dw/4MzP/GBj/+fn/o6P/TEz/xMT/b2//Tk7/OTn/HR3/hIT/ODj/Y2P/CQn/ZGT/6Oj0UlL/Gxv//f3/Bwf/YmL/6+v0w8P/Cgr/tbX0QkL+9fX4Pz/qNzd0dFHLAAAAAXRSTlMAQObYZgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQfeCxINNSdmw510AAAA5ElEQVQYGQXBzSuDAQCA8eexKXOwmSZepa1JiPJxsJOrCwcnuchBjg4O/gr7D9zk4uAgJzvuMgcTpYxaUZvSm5mUj7TX7ycAqvoLIJBwStVbP0Hom1Z/ejoxrbaR1Jz6nWinbKWttGRgMSSjanPktRY6mB9WtRNTn7Ilh7LxnNpKq2/x5LnBitfz+hx0qxUaxhZ6vwqq9bx6f2XXvuUl9SVQS38NR7cvln3v15tZ9bQpuWDtZN3Lgh5DWJex3Y+z1KrVhw21+CiM74WZo83DiXq0dVBDYNJkFEU7WrwDAZhRtQrwDzwKQbT6GboLAAAAAElFTkSuQmCC" },
	"logo_url": { "type": "url", "description": "A url that points to the site's logo. Leave blank to disable. When enabled the logo will be inserted next to the site name on every page.", "default": "https://starbeamrainbowlabs.com/images/logos/peppermint.png" },
	"logo_position": { "type": "text", "description": "The side of the site name at which the logo should be placed.", "default": "left" },
	"show_subpages": { "type": "checkbox", "description": "Whether to show a list of subpages at the bottom of the page.", "default": true},
	"subpages_display_depth": { "type": "text", "description": "The depth to which we should display when listing subpages at the bottom the page.", "default": 3},
	"random_page_exclude": { "type": "text", "description": "The pages names matching this regular expression won't be chosen when a random page is being picked to send you to by the random action.", "default": "/^Files\\/.*$/i" },
	"random_page_exclude_redirects": { "type": "checkbox", "description": "Causes the random action to avoid sending the user to a redirect page.", "default": true },
	"redirect_absolute_enable": { "type": "checkbox", "description": "Whether to enable absolute redirects or not. Enable only if you trust everyone who has edit access to your wiki, as it is possible to redirect a page to <em>anywhere</em> on the Internet - including a malicious website - hence the reason why this is disabled by default for safety.", "default": false },
	"footer_message": { "type": "textarea", "description": "A message that will appear at the bottom of every page. May contain HTML.", "default": "All content is under <a href='?page=License' target='_blank'>this license</a>. Please make sure that you read and understand the license, especially if you are thinking about copying some (or all) of this site's content, as it may restrict you from doing so." },
	"editing_message": { "type": "textarea", "description": "A message that will appear just before the submit button on the editing page. May contain HTML.", "default": "<a href='?action=help#20-parser-default' target='_blank'>Formatting help</a> (<a href='https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet' target='_blank'>Markdown Cheatsheet</a>)<br />\nBy submitting your edit or uploading your file, you are agreeing to release your changes under <a href='?action=view&page=License' target='_blank'>this license</a>. Also note that if you don't want your work to be edited by other users of this site, please don't submit it here!" },
	"editing_tags_autocomplete": { "type": "checkbox", "description": "Whether to enable autocomplete for the tags box in the page editor.", "default": true },
	"admindisplaychar": { "type": "text", "description": "The string that is prepended before an admin's name on the nav bar. Defaults to a diamond shape (&#9670;).", "default": "&#9670;" },
	"protectedpagechar": { "type": "text", "description": "The string that is prepended a page's name in the page title if it is protected. Defaults to a lock symbol. (&#128274;)", "default": "&#128274;" },
	"editing": { "type": "checkbox", "description": "Whether editing is enabled.", "default": true},
	"anonedits": { "type": "checkbox", "description": "Whether users who aren't logged in are allowed to edit your wiki.", "default": false },
	"maxpagesize": { "type": "number", "description": "The maximum page size in characters.", "default": 135000 },
	"parser": { "type": "text", "description": "The parser to use when rendering pages. Defaults to an extended version of parsedown (http://parsedown.org/)", "default": "parsedown" },
	"parser_cache": { "type": "checkbox", "description": "Whether parser output should be cached to speed things up. The cache directory is <code>._cache</code> in the data directory - delete it if you experience issues (unlikely).", "default": false },
	"parser_cache_min_size": { "type": "number", "description": "The minimum size a source string must be (in bytes) before it's considered eligible for caching.", "default": 1024 },
	"parser_ext_renderers_enabled": { "type": "checkbox", "description": "Whether to enable external diagram renderer support, which is part of the parsedown parser. See the <code>parser_ext_renderers</code> setting below for more information.", "default": true },
	"parser_ext_renderers": { "type": "parserext", "description": "Used by the parsedown parser as an object mapping fenced code block languages to their respective external renderers. Should be in the form <code>language_code</code> → <code>external renderer definition</code>. See the default for examples on how to define an external renderer. Warning: On Windows, the enforcement of strict time limits is not possible. Beware of DoS attacks!", "default": {
        "nomnoml": {
            "name": "nomnoml",
            "description": "The nomnoml UML diagram renderer. Requires the 'nomnoml' npm package to be globally installed.",
            "url": "http:\/\/nomnoml.com\/",
            "cli": "nomnoml {input_file} {output_file} 0",
            "cli_mode": "file",
            "output_format": "image\/svg+xml",
			"output_classes": [ "invert-when-dark" ]
        },
        "plantuml": {
            "name": "PlantUML",
            "description": "The PlantUML diagram renderer. Supports many different diagram types. Requires plantuml to be installed.",
            "url": "http:\/\/plantuml.com\/",
            "cli": "plantuml -tsvg -pipe",
            "cli_mode": "pipe",
            "output_format": "image\/svg+xml"
        },
        "abc": {
            "name": "ABC Notation",
            "description": "A simple music notation typesetter. Much easier to understand than Lilypond. Requires abcm2ps to be installed.",
            "url": "https:\/\/abcnotation.com\/",
            "cli": "abcm2ps -g -O - -",
            "cli_mode": "pipe",
            "output_format": "image\/svg+xml",
            "output_classes": [ "invert-when-dark" ]
        },
        "latexserver": {
            "name": "Server-Side MathJax",
            "description": "Client-side Mathjax via the 'enable_math_rendering' setting not your thing? Try it server-side instead! Requires the 'mathjax-node-cli' npm package to be globally installed. Note that you obviously don't want to include the latex math inside dolar signs $$ as the reference link tells you to.",
            "url": "https://math.meta.stackexchange.com/q/5020/221181",
            "cli": "tex2svg -- {input_text}",
            "cli_mode": "substitution_pipe",
            "output_format": "image\/svg+xml",
            "output_classes": [ "invert-when-dark" ]
        },
		"svginkscape": {
			"name": "Inkscape SVG",
			"description": "Server-side SVG-to-PNG rendering with inkscape. Requires inkscape to be installed and in your PATH, of course.",
			"url": "https://developer.mozilla.org/en-US/docs/Web/SVG/Element",
			"cli": "inkscape {input_file} -e {output_file}",
			"cli_mode": "file",
			"output_format": "image\/svg+xml",
			"output_classes": [  ]
		}
	} },
	"parser_ext_time_limit": { "type": "number", "description": "The number of seconds external renderers are allowed to run for. Has no effect if external renderers are turned off. Also currently has no effect on Windows.", "default": 5 },
	"parser_ext_allow_anon": { "type": "checkbox", "description": "<p>Whether to allow anonymous users to render new diagrams with the external renderer. When disabled, anonymous users will still be allowed to recall pre-rendered items from the cache, but will be unable to generate brand-new diagrams.</p><p>Note that if you allow anonymous edits this setting won't fully protect you: anonymous users could edit a page and insert a malicious diagram, and then laer a logged in user could unwittingly invoke the external renderer on the anonymous user's behalf.", "default": false },
	"parser_toc_heading_level": { "type": "number", "description": "The level of heading to create when generating a table of contents. Corresponds directly with the HTML h1-h6 tags. A value of 0 disables the heading.", "default": 2 },
	"parser_mangle_external_links": { "type": "checkbox", "description": "Whether <code>[display text](./Page Name.md)</code> style links are transparently handled as internal links. Useful to increase compatibility to other systems that use this style of link such as <a href='https://js.wiki/'>Wiki.js</a>.", "default": false },
	"parser_onebox_enabled": { "type": "checkbox", "description": "Whether oneboxing is enabled or not. Oneboxes are fancy renderings of an internal link with a preview of the text on the linked page. To generate a onebox, an internal link must be on it's own on a line with nothing before or after it.", "default": true },
	"parser_onebox_preview_length": { "type": "number", "description": "The number of characters preview to display in oneboxes. ", "default": 250 },
	"interwiki_index_location": { "type": "text", "description": "The location to find the interwiki wiki definition file, which contains a list of wikis along with their names, prefixes, and root urls. May be a URL, or simply a file path - as it's passed to file_get_contents(). If left blank, interwiki link parsing is disabled.", "default": null },
	"clean_raw_html": { "type": "checkbox", "description": "Whether page sources should be cleaned of HTML before rendering. It is STRONGLY recommended that you keep this option turned on.", "default": true },
	"all_untrusted": { "type": "checkbox", "description": "Whether to treat both page sources and comment text as untrusted input. Untrusted input has additional restrictions to protect against XSS attacks etc. Turn on if your wiki allows anonymous edits.", "default": false},
	"enable_math_rendering": { "type": "checkbox", "description": "Whether to enable client side rendering of mathematical expressions with MathJax (https://www.mathjax.org/). Math expressions should be enclosed inside of dollar signs ($). Turn off if you don't use it.", "default": true },
	"theme_colour": { "type": "text", "description": "The theme colour to set in the <code>&lt;meta name='theme-color' content='value' /&gt;</code> meta tag. Apparently used to customise the UI colour on mobile devices, and also by when platforms such as Discord are generating rich embeds to set the accent colour. Set to an empty string to disable.", "default": "#fc1c1c" },
	"users": { "type": "usertable", "description": "An array of usernames and passwords - passwords should be hashed with password_hash() (the hash action can help here)", "default": {
		"admin": {
			"email": "admin@somewhere.com",
			"password": "$2y$10$kX6QgET6SfL47GsJjxwp/.JE6SSJo4Nx8/wG13eNvLDGIduYTlCXO"
		},
		"user": {
			"email": "example@example.net",
			"password": "$2y$10$tWYjgh5WvaJrwiszZ1e2Keo3ras6mqa4ptqruwUn3de4UB6eV9cnW"
		}
	}},
	"admins": { "type": "array", "description": "An array of usernames that are administrators. Administrators can delete and move pages.", "default": [ "admin" ]},
	"anonymous_user_name": { "type": "text", "description": "The default name for anonymous users.", "default": "Anonymous" },
	"user_page_prefix": { "type": "text", "description": "The prefix for user pages. All user pages will be considered to be under this page. User pages have special editing restrictions that prevent anyone other thant he user they belong to from editing them. Should not include the trailing forward slash.", "default": "Users" },
	"user_preferences_button_text": { "type": "text", "description": "The text to display on the button that lets logged in users change their settings. Defaults to a cog (aka a 'gear' in unicode-land).", "default": "&#x2699; " },
	"password_algorithm": { "type": "text", "description": "The algorithm to utilise when hashing passwords. Takes any value PHP's password_hash() does.", "default": "PASSWORD_DEFAULT" },
	"password_cost": { "type": "number", "description": "The cost to use when hashing passwords.", "default": 12},
	"password_cost_time": { "type": "number", "description": "The desired number of milliseconds to delay by when hashing passwords. Pepperminty Wiki will automatically update the value of password_cost to take the length of time specified here. If you're using PASSWORD_ARGON2I, then the auto-update will be disabled.", "default": 350},
	"password_cost_time_interval": { "type": "number", "description": "The interval, in seconds, at which the password cost should be recalculated. Set to -1 to disable. Default: 1 week", "default": 604800},
	"password_cost_time_lastcheck": { "type": "number", "description": "Pseudo-setting used to keep track of the last recalculation of password_cost. Is updated with the current unix timestamp every time password_cost is recalculated.", "default": 0},
	"new_password_length": { "type": "number", "description": "The length of newly-generated passwords. This is currently used in the user table when creating new accounts.", "default": 32 },
	"require_login_view": { "type": "checkbox", "description": "Whether to require that users login before they do anything else. Best used with the data_storage_dir option.", "default": false},
	"readingtime_enabled": { "type": "checkbox", "description": "Whether to display the estimated reading time beneath the header of every wiki page.", "default": true },
	"readingtime_language": { "type": "text", "description": "The language code to use when estimating the reading time. Possible values: en, ar, de, es, fi, fr, he, it, jw, nl, pl, pt, ru, sk, sv, tr, zh. Unfrotuantely adding multi-language support to the user interface is an absolutely massive undertaking that would take ages, as Peppermitny Wiki waasn't designed with that in mind :-/", "default": "en" },
	"readingtime_action": { "type": "text", "description": "The name of the action to enable the reading time estimation on. You probably shouldn't change this unless you know what you're doing.", "default": "view" },
	"data_storage_dir": { "type": "text", "description": "The directory in which to store all files, except the main index.php.", "default": "." },
	"watchlists_enable": { "type": "checkbox", "description": "Whether the watchlists feature should be enabled or not.", "default": true },
	"delayed_indexing_time": { "type": "number", "description": "The amount of time, in seconds, that pages should be blocked from being indexed by search engines after their last edit. Aka delayed indexing.", "default": 0},
	"nav_links": { "type": "nav", "description": "<p>An array of links and display text to display at the top of the site.<br />Format: <code>\"Display Text\": \"Link\"</code></p><p>You can also use strings here and they will be printed as-is, except the following special strings:</p><ul><li><code>user-status</code> - Expands to the user's login information. e.g. \"Logged in as {name}. | Logout\", or e.g. \"Browsing as Anonymous. | Login\".</li><li><code>search</code> - Expands to a search box.</li><li><code>divider</code> - Expands to a divider to separate stuff.</li><li><code>more</code> - Expands to the \"More...\" submenu.</li></ul>", "default": [
		"user-status",
		[
			"Home",
			"index.php"
		],
		"search",
		[
			"Read",
			"index.php?page={page}"
		],
		[
			"Edit",
			"index.php?action=edit&page={page}"
		],
		[
			"All&nbsp;Pages",
			"index.php?action=list"
		],
		"menu"
	]},
	"nav_links_extra": { "type": "nav", "description": "An array of additional links in the above format that will be shown under \"More\" subsection.", "default": {
		"Moderator": [
			[
				"&#9670; &#x1f6ab; Delete",
				"index.php?action=delete&page={page}"
			],
			[
				"&#9670; &#x1f6a0; Move",
				"index.php?action=move&page={page}"
			],
			[
				"&#9670; &#x1f510; Toggle Protection",
				"index.php?action=protect&page={page}"
			],
			[
				"&#9670; &#x1f527; Edit master settings",
				"index.php?action=configure"
			]
		],
		"Wiki": [
			[
				"&#x1f4ca; Statistics",
				"?action=stats"
			],
			[
				"&#x1f465; All Users",
				"index.php?action=user-list"
			]
		],
		"Navigation": [
			[
				"&#x1f3ab; All&nbsp;Tags",
				"index.php?action=list-tags"
			],
			[
				"&#x1f38a; Random Page",
				"?action=random"
			],
			[
				"&#x1f4c5; Recent changes",
				"?action=recent-changes"
			]
		],
		"Page": [
			[
				"&#x231b; Page History",
				"?action=history&page={page}"
			],
			[
				"&#x1f4e4; Upload",
				"index.php?action=upload"
			],
			[
				"&#x1f52d; Watch",
				"index.php?action=watchlist-edit&do=add&page={page}"
			]
		]
	} },
	"nav_links_bottom": { "type": "nav", "description": "An array of links in the above format that will be shown at the bottom of the page.", "default": [
		[
			"&#x1f5a8; Printable version",
			"index.php?action=view&mode=printable&page={page}"
		],
		[
			"Credits",
			"index.php?action=credits"
		],
		[
			"&#x1f6aa; Help",
			"index.php?action=help"
		]
	]},
	"comment_enabled": { "type": "checkbox", "description": "Whether commenting is enabled or not. If disabled, nobody will be able to post new comments, but existing comments will still be shown (if the <code>feature-comments</code> module is installed, of course -  otherwise this setting will have no effect).", "default": true },
	"comment_hide_all": { "type": "checkbox", "description": "Whether to hide all comments, as if the commenting feature never existed. If you want to enable this setting, consider using the downloader (link in the docs) to exclude the <code>feature-comments</code> module instead.", "default": false },
	"anoncomments": { "type": "checkbox", "description": "Whether to allow anonymous user to make comments. Note that anonymous users are not able to delete their own comments (since they are not logged in, there's no way to know if they were the original poster or not)", "default": false },
	"comment_max_length": { "type": "number", "description": "The maximum allowed length, in characters, for comments", "default": 5000 },
	"comment_min_length": { "type": "number", "description": "The minimum allowed length, in characters, for comments", "default": 10 },
	"comment_time_icon": { "type": "text", "description": "The icon to show next to the time that a comment was posted.", "default": "&#x1f557;" },
	"history_max_revisions": { "type": "number", "description": "The maximum revisions that should be stored. If this limit is reached, them the oldest revision will be deleted. Defaults to -1, which is no limit.", "default": -1 },
	"history_revert_require_moderator": { "type": "checkbox", "description": "Whether a user must be a moderator in order use the page reversion functionality.", "default": true },
	"upload_enabled": { "type": "checkbox", "description": "Whether to allow uploads to the server.", "default": true},
	"upload_allowed_file_types": { "type": "array", "description": "An array of mime types that are allowed to be uploaded.", "default": [
		"image/jpeg",
		"image/png",
		"image/gif",
		"image/webp",
		"image/avif",
		"image/jxl",
		"image/heif", "image/heic",
		"image/svg+xml",
		"video/mp4",
		"video/webm",
		"audio/mp4",
		"audio/mpeg",
		"audio/flac",
		"audio/ogg",
		"application/pdf"
	]},
	"preview_file_type": { "type": "text", "description": "The default file type for previews.", "default": "image/png" },
	"default_preview_size": { "type": "number", "description": "The default size of preview images in pixels.", "default": 640},
	"mime_extension_mappings_location": { "type": "text", "description": "The location of a file that maps mime types onto file extensions and vice versa. Used to generate the file extension for an uploaded file. See the configuration guide for windows instructions.", "default": "/etc/mime.types" },
	"mime_mappings_overrides": { "type": "map", "description": "Override mappings to convert mime types into the appropriate file extension. Used to override the above file if it assigns weird extensions to any mime types.", "default": {
		"text/plain": "txt",
		"audio/mpeg": "mp3"
	}},
	"min_preview_size": { "type": "number", "description": "The minimum allowed size of generated preview images in pixels.", "default": 1 },
	"max_preview_size": { "type": "number", "description": "The maximum allowed size of generated preview images in pixels.", "default": 2048 },
	"avatars_show": { "type": "checkbox", "description": "Whether or not to show avatars requires the 'user-preferences' and 'upload' modules, though uploads themselves can be turned off so long as all avatars have already been uploaded - it's only the 'preview' action that's actually used.", "default": true },
	"avatars_gravatar_enabled": { "type": "checkbox", "description": "Whether gravatars should be displayed if an uploaded avatar is not found. If disabled, users without avatars will show a blank image instead.", "default": true },
	"avatars_size": { "type": "number", "description": "The image size to render avatars at. Does not affect the size they're stored at - only the inline rendered size (e.g. on the recent changes page etc.)", "default": 32 },
	"search_characters_context": { "type": "number", "description": "The number of characters that should be displayed either side of a matching term in the context below each search result.", "default": 75},
	"search_characters_context_total": { "type": "number", "description": "The total number of characters that a search result context should display at most.", "default": 250 },
	"search_title_matches_weighting": { "type": "number", "description": "The weighting to give to search term matches found in a page's title.", "default": 50 },
	"search_tags_matches_weighting": { "type": "number", "description": "The weighting to give to search term matches found in a page's tags.", "default": 15 },
	"search_didyoumean_enabled": { "type": "checkbox", "description": "Whether to enable the 'did you mean?' search query typo correction engine.", "default": false },
	"search_didyoumean_editdistance": { "type": "number", "description": "The maximmum edit distance to search when checking for typos. Increasing this number causes an  exponential increase in the amount of computing power required to correct all spellings.", "default": 2 },
	"search_didyoumean_cost_insert": { "type": "number", "description": "The insert cost to use when calculating levenshtein distances. If this value is changed then the did you mean index must be rebuilt.", "default": 1 },
	"search_didyoumean_cost_delete": { "type": "number", "description": "The delete cost to use when calculating levenshtein distances. If this value is changed then the did you mean index must be rebuilt.", "default": 1 },
	"search_didyoumean_cost_replace": { "type": "number", "description": "The replace cost to use when calculating levenshtein distances. If this value is changed then the did you mean index must be rebuilt.", "default": 1 },
	"search_didyoumean_seed_word": { "type": "text", "description": "The seed word for the didyoumean index tree. Has a number of special properties:<ul><li>Can't be added to the index</li><li>Can't be removed from the index</li><li>Is never suggested</li></ul>Since words are transliterated to lowercase ascii before indexing, it's recommended to set this to a word that contains characters that will never be present after transliteration.", "default": ":peppermint:" },
	"dynamic_page_suggestion_count": { "type": "number", "description": "The number of dynamic page name suggestions to fetch from the server when typing in the page search box. Note that lowering this number doesn't <em>really</em> improve performance. Set to 0 to disable.", "default": 7 },
	"similarpages_enabled": { "type": "checkbox", "description": "Whether similar pages are displayed beneath the content and above the comments on a page", "default": true },
	"similarpages_count": { "type": "number", "description": "The number of similar page suggestions to make.", "default": 3 },
	"defaultaction": { "type": "text", "description": "The default action. This action will be performed if no other action is specified. It is recommended you set this to \"view\" - that way the user automatically views the default page (see above).", "default": "view" },
	"email_debug_dontsend": { "type": "checkbox", "description": "If set to true, emails are logged to the standard error instead of being actually sent.", "default": false },
	"email_subject_utf8": { "type": "checkbox", "description": "Whether to encode the subject of emails sent to allow them to contain unicode characters. Without this, email subjects will be transliterated to ASCII. If utf-8 email subjects are disabled, page names may not be represented properly.", "default": true },
	"email_body_utf8": { "type": "checkbox", "description": "Whether to send emails with utf-8 bodies. If set to false, email bodies will be transliterated to ASCII. If utf-8 email bodies are disabled, page names may not be represented properly.", "default": true },
	"email_verify_addresses": { "type": "checkbox", "description": "Whether user email addresses must be verified in order to send emails to them.", "default": true },
	"updateurl": { "type": "url", "description": "The url from which to fetch updates. Defaults to the master (development) branch. MAKE SURE THAT THIS POINTS TO A *HTTPS* URL, OTHERWISE SOMEONE COULD INJECT A VIRUS INTO YOUR WIKI!", "default": "https://raw.githubusercontent.com/sbrl/pepperminty-wiki/master/index.php" },
	"optimize_pages": { "type": "checkbox", "description": "Whether to optimise all webpages generated.", "default": true },
	"minify_pageindex": { "type": "checkbox", "description": "Whether to minify the page index when saving it. Improves performance slightly (especially on larger wikis), but can make debugging and quick ninja-edits more awkward. Note that this only takes effect when the page index is next saved.", "default": true },
	"http2_server_push": { "type": "checkbox", "description": "Whether HTTP/2.0 server should should be enabled. If true, then 'link' HTTP headers will be attached to rendered pages specifying files to push down. Note that web server support <em>also</em> has to be abled for this to work, as PHP can't push resources to the client on its own.", "default": true },
	"http2_server_push_items": { "type": "server-push", "description": "An array of items to push to clients when rendering pages. Should be in the format <code>[ [type, path], [type, path], ....]</code>, where <code>type</code> is a <a href='https://fetch.spec.whatwg.org/#concept-request-destination'>resource type</a>, and <code>path</code> is a relative url path to a static file to send via <em>HTTP/2.0 Server Push</em>.<br />Note: These resources will only be pushed if your web server also has support for the link: HTTP/2.0 header, and it's a page that being rendered. If it's some other thing that being sent (e.g. an image, error message, event stream, redirect, etc.), then no server push is indicated by <em>Pepperminty Wiki</em>. Test your estup with your browser's developer tools, or <a href='https://http2-push.io/'>This testing site</a>.", "default": [] },
	"max_recent_changes": { "type": "number", "description": "The maximum number of recent changes to display on the recent changes page.", "default": 512 },
	"export_allow_only_admins": { "type": "checkbox", "description": "Whether to only allow adminstrators to export the your wiki as a zip using the page-export module.", "default": false},
	"stats_update_interval": { "type": "number", "description": "The number of seconds which should elapse before a statistics update should be scheduled. Defaults to once a day.", "default": 86400},
	"stats_update_processingtime": { "type": "number", "description": "The maximum number of milliseconds that should be spent at once calculating statistics. If some statistics couldn't fit within this limit, then they are scheduled and updated on the next page load. Note that this is a target only - if an individual statistic takes longer than this, then it won't be interrupted. Defaults to 100ms.", "default": 100},
	"sessionprefix": { "type": "text", "description": "You shouldn't need to change this. The prefix that should be used in the names of the session variables. Defaults to \"auto\", which automatically generates this field. See the readme for more information.", "default": "auto" },
	"sessionlifetime": { "type": "number", "description": "Again, you shouldn't need to change this under normal circumstances. This setting controls the lifetime of a login session. Defaults to 24 hours, but it may get cut off sooner depending on the underlying PHP session lifetime.", "default": 86400 },
	"cookie_secure": { "type": "text", "description": "Whether to set the 'Secure' flag on all cookies. This prevents cookies from being transmitted over an unencrypted connection. Default: auto (sets the flag if HTTPS is detected). Other possible values: false (the flag is never set), true (the flag will always be set, regardless of whether HTTPS is detected or not)", "default": "auto" },
	"disable_peppermint_access_check": { "type": "checkbox", "description": "Disables the access check for peppermint.json on first-run. <strong>VERY DANGEROUS</strong>. Use only for development. Note that it's recommend to block access to peppermint.json for a reason - it contains your site secret and password hashes, so an attacker could do all <em>sorts</em> of nefarious things if it's left unblocked.", "default": false },
	"css_theme_autoupdate_url": { "type": "url", "description": "A url that points to the css theme file to check for updates. If blank, then automatic updates are disabled.", "default": "" },
	"css_theme_autoupdate_interval": { "type": "number", "description": "The interval, in seconds, that updates to the theme should be checked for. Defaults to every week. A value of -1 disables automatic updates.", "default": 604800 },
	"css_theme_autoupdate_lastcheck": { "type": "number", "description": "The timestamp of the last time that updates for the selected theme were last checked for. To disable automatic updates, you should set <code>css_theme_autoupdate_interval</code> to <code>-1</code> instead of changing this setting.", "default": 0 },
	"css_theme_gallery_index_url": { "type": "text", "description": "A url that points  to an index file that contains a list of themes. Used to populate the gallary. Multiple urls are allowed - separate them with a space.", "default": "https://starbeamrainbowlabs.com/labs/peppermint/themes/themeindex.json" },
	"css_theme_gallery_selected_id": { "type": "text", "description": "The id of the currently selected theme. Defaults to the internal default theme.", "default": "default" },
	"css": { "type": "textarea", "description": "A string of css to include. Will be included in the &lt;head&gt; of every page inside a &lt;style&gt; tag. This may also be an absolute url - urls will be referenced via a &lt;link rel='stylesheet' /&gt; tag. If the theme gallery is installed and automatic updates enabled, then the value of this property is managed by the theme gallery and changes may be overwritten (try the css_custom setting instead).", "default": "auto" },
	"css_custom": { "type": "textarea", "description": "A string of custom CSS to include on top of the base theme css. Allows for theme customisations while still enabling automatic updates :D Just like the css setting, this one can also be a url.", "default": "/* Enter your custom css here. */" },
	"cli_enabled": { "type": "checkbox", "description": "Whether the Pepperminty Wiki CLI is enabled or not.", "default": true },
	"cli_prompt": { "type": "text", "description": "The string to use as the prompt in the CLI shell.", "default": "\u0001\u001b[1m\u001b[31m\u0002#\u0001\u001b[0m\u0002 " },
	"sidebar_show": { "type": "checkbox", "description": "Whether to show the sidebar by default to all users or not.", "default": false },
	"sidebar_maxdepth": { "type": "number", "description": "The maximum depth of pages to show in the sidebar. Top-level pages are of depth 0, subpages thereof are of depth 1, etc. Defaults to a depth of 1, which indicates to display both top-level pages and their subpages.", "default": 1 }
}
GUICONFIG;

$settingsFilename = "peppermint.json";

if(file_exists("$settingsFilename.compromised")) {
	http_response_code(500);
	header("content-type: text/plain");
	exit("Error: $settingsFilename.compromised exists on disk, so it's likely you need to block access to 'peppermint.json' from the internet. If you've done this already, please delete $settingsFilename.compromised and reload this page.\n\nIf you've done this check manually, please set the disable_peppermint_access_check setting to false.\n\nThis check was done as part of the first run wizard.");
}

$guiConfig = json_decode($guiConfig);
$settings = new stdClass();
if(!file_exists($settingsFilename)) {
	// Copy the default settings over to the main settings array
	foreach ($guiConfig as $key => $value)
		$settings->$key = $value->default;
	// Generate a random secret
	$settings->secret = bin2hex(random_bytes(16));
	if(file_put_contents("peppermint.json", json_encode($settings, JSON_PRETTY_PRINT)) === false) {
		http_response_code(503);
		header("content-type: text/plain");
		exit("Oops! It looks like $settings->sitename wasn't able to write peppermint.json to disk.\nThis file contains all of $settings->sitename's settings, so it's really important!\nHave you checked that PHP has write access to the directory that index.php is located in (and all it's contents and subdirectories)? Try\n\nsudo chown USERNAME:USERNAME -R path/to/directory\n\nand\n\nsudo chmod -R 0644 path/to/directory;\nsudo chmod -R +X path/too/directory\n\n....where USERNAME is the username that the PHP process is running under.");
	}
}
else
	$settings = json_decode(file_get_contents("peppermint.json"));

if($settings === null) {
	header("content-type: text/plain");
	exit("Error: Failed to decode the settings file! Does it contain a syntax error?");
}

// Fill in any missing properties
$settings_upgraded = false;
foreach($guiConfig as $key => $propertyData) {
	if(!property_exists($settings, $key)) {
		error_log("[PeppermintyWiki/$settings->sitename/settings] Upgrading $key");
		$settings->$key = $propertyData->default;
		$settings_upgraded = true;
	}
}
if($settings_upgraded)
	file_put_contents("peppermint.json", json_encode($settings, JSON_PRETTY_PRINT));

// If the first-run wizard hasn't been completed but we've filled in 1 or more new settings, then we must be a pre-existing wiki upgrading from a previous version. We can guarantee this because of the new firstrun_complete setting	
if(!$settings->firstrun_complete && $settings_upgraded) {
	$settings->firstrun_complete = true;
	file_put_contents("peppermint.json", json_encode($settings, JSON_PRETTY_PRINT));
}

// Insert the default CSS if requested
$defaultCSS = <<<THEMECSS
/**
 * @id				default
 * @name			Default
 * @description		The default theme.
 * @author			Starbeamrainbowlabs
 * @author_link		https://starbeamrainbowlabs.com/
 * @minversion		v0.20
 */

:root {
	--bg-back: #eee8f2;
	--bg-page: #faf8fb;
	--bg-page-inset: hsl(270, 22%, 88%);
	
	--bg-comments-1: hsl(31, 64%, 85%);
	--bg-comments-2: hsla(27, 92%, 68%, 0.64);
	--bg-comments-3: hsla(30, 84%, 72%, 0.54);
	--bg-comments-4: hsla(32, 82%, 62%, 0.3);
	
	--text-dark: #111111;
	--text-light: hsl(0, 0%, 96%);
	--text-bright: hsl(274, 40%, 41%);
	--text-placeholder-light: hsla(0, 0%, 100%, 0.9);
	--text-os-button: #514C4C;
	
	--text-soft: rgba(33, 33, 33, 0.3);
	--text-ultrasoft: rgba(50, 50, 50, 0.3);
	--shadow: rgba(80, 80, 80, 0.5);
	
	--checkerboard-bg: #eee;
	--checkerboard-overlay: rgba(200, 200, 200, 0.2);
	
	--accent-a0: hsl(253, 79%, 88%);
	--accent-a0t: hsla(253, 79%, 88%, 0.3);
	--accent-a1: #9e7eb4;
	--accent-a2: #8a62a7;
	--accent-a3: #442772;
	--accent-a4: hsl(253, 79%, 88%);
	
	--accent-b1: #ffa74d;
	--accent-b2: #fb701a;
	
	--accent-c1: #e60707;
	--accent-c2: #820f0f; /*#8b1a1a*/
	--accent-c3: hsl(0, 59%, 20%); /*#8b1a1a*/
	
	--accent-d1: hsl(160, 73%, 26%);
	--accent-d2: hsl(159, 76%, 23%);
	--accent-d3: hsl(160, 80%, 70%);
	
	--tag: #e2d5eb;
	--file: white;
	
	--addition: #09b400;
	--deletion: #cf1c11;
	--nochange: #847bc7;
	
	/* #ffdb6d #36962c hsl(36, 78%, 80%) hsla(262, 92%, 68%, 0.42) */
}

@media (prefers-color-scheme: dark) {
	:root {
		--bg-back: hsl(270, 29%, 28%);
		--bg-page: hsl(273, 15%, 16%);
		--bg-page-inset: hsl(273, 20%, 20%);
		
		--bg-comments-1: hsl(263, 25%, 16%);
		--bg-comments-2: hsla(264, 45%, 16%, 0.64);
		--bg-comments-3: hsla(266, 64%, 28%, 0.54);
		--bg-comments-4: hsla(268, 82%, 32%, 0.3);
		
		--text-dark: hsl(277, 38%, 89%);
		--text-bright: hsl(274, 75%, 81%);
		/* --text-light: hsl(0, 0%, 6%); */
		
		--text-soft: hsla(0, 0%, 92%, 0.6);
		--text-ultrasoft: hsla(0, 0%, 95%, 0.4);
		
		--shadow: rgba(20, 20, 20, 0.5);
		
		--accent-a0: hsl(253, 79%, 88%);
		--accent-a1: #9e7eb4;
		--accent-a2: #442772;
		--accent-a3: hsl(274, 40%, 41%);
		--accent-a4: hsl(253, 49%, 20%);
		
		--accent-b2: hsl(22, 96%, 64%);
		
		--accent-c1: hsl(5, 100%, 76%);
		--accent-c2: hsl(4, 95%, 68%); /*#8b1a1a*/
		--accent-c3: hsl(2, 90%, 58%);
		
		--accent-d1: hsl(160, 73%, 46%);
		
		--tag: hsl(273, 46%, 27%);
	}
	a { color: hsl(208, 67%, 67%); }
	a:hover { color: hsl(214, 67%, 75%); }
	a:active, a:focus { color: hsl(214, 87%, 85%); }
	a:visited { color: hsl(264, 77%, 65%); }
	
	.invert-when-dark { filter: invert(100%); }
}

/* TODO: Refactor to use the CSS grid */

body { margin: 2rem 0 0 0; background: var(--bg-back); line-height: 1.45em; color: var(--text-dark); font-family: sans-serif; }

nav { display: flex; background-color: var(--accent-a2); color: var(--accent-b1);  }
nav.top { position: absolute; top: 0; left: 0; right: 0; box-shadow: inset 0 -0.6rem 0.8rem -0.5rem var(--shadow); }
nav.bottom { position: absolute; left: 0; right: 0; box-shadow: inset 0 0.8rem 0.8rem -0.5rem var(--shadow); }

nav.mega-menu { display: flex; flex-direction: row; padding-bottom: 0.4em; border-left: 3px solid var(--accent-a3); border-right: 3px solid var(--accent-a3); }
nav.mega-menu .category { padding: 0.3em 1em; }
nav.mega-menu strong { display: block; }
nav.mega-menu span { display: block; }

nav > span { flex: 1; line-height: 2; display: inline-block; margin: 0; padding: 0.3rem 0.5rem; }
nav:not(.mega-menu) > span { border-left: 3px solid var(--accent-a3); border-right: 3px solid var(--accent-a3); }
nav:not(.nav-more-menu) > span { text-align: center; }
nav:not(.nav-more-menu) a { text-decoration: none; color: inherit; }
nav:not(.nav-more-menu):not(.mega-menu) > span > a { font-weight: bolder; }
.nav-divider { color: transparent; }

.nav-more { position: relative; background-color: var(--accent-a3); min-width: 10em; }
label { font-weight: bold; }
label { cursor: pointer; }
.nav-more-menu { z-index: 10000; position: absolute; flex-direction: column; top: 2.6rem; right: 100000px; text-align: left; background-color: var(--accent-a2); border-top: 3px solid var(--accent-a3); border-bottom: 3px solid var(--accent-a3); }
input[type=checkbox]:checked ~ .nav-more-menu { right: -0.2rem; box-shadow: 0.4rem 0.4rem 1rem 0 var(--shadow); }
.nav-more-menu span { white-space: nowrap; }

.inflexible { flex: none; }
.off-screen { position: absolute; top: -1000px; left: -1000px;}

input[type=search] { width: 14rem; padding: 0.3rem 0.4rem; font-size: 1rem; color: var(--text-light); background: var(--accent-a0t); border: 0; border-radius: 0.3rem; }
input[type=search]::-webkit-input-placeholder { color: var(--text-placeholder-light); }
input[type=search]::-moz-placeholder { color: var(--text-placeholder-light); }
input:focus, textarea:focus { outline: 0.15em solid var(--accent-a0); }
input[type=button], input[type=submit] { cursor: pointer; }

.sidebar + .main-container nav.bottom { position: relative; }
.sidebar { position: relative; z-index: 100; margin-top: 0.6rem; padding: 1rem 3rem 2rem 0.4rem; background: var(--accent-a1); box-shadow: inset -0.6rem 0 0.8rem -0.5rem var(--shadow); max-width: 20vw; resize: horizontal; overflow-x: scroll; }
.sidebar a { color: var(--accent-b1); }

.sidebar ul { position: relative; margin: 0.3rem 0.3rem 0.3rem 1rem; padding: 0.3rem 0.3rem 0.3rem 1rem; list-style-type: none; }
.sidebar li { position: relative; margin: 0.3rem; padding: 0.3rem; }

.sidebar ul:before { content: ""; position: absolute; top: 0; left: 0; height: 100%; border-left: 2px dashed var(--accent-a3); }
.sidebar li:before { content: ""; position: absolute; width: 1rem; top: 0.8rem; left: -1.2rem; border-bottom: 2px dashed var(--accent-a3); }

.preview { text-align: center; }
.preview:hover img, .preview:hover video, .preview:hover audio { max-width: 100%; background-color: var(--checkerboard-bg); background-image: linear-gradient(45deg, var(--checkerboard-overlay) 25%, transparent 25%, transparent 75%, var(--checkerboard-overlay) 75%, var(--checkerboard-overlay)), linear-gradient(45deg, var(--checkerboard-overlay) 25%, transparent 25%, transparent 75%, var(--checkerboard-overlay) 75%, var(--checkerboard-overlay)); background-size:2em 2em; background-position:0 0, 1em 1em; }
.image-controls ul { list-style-type: none; margin: 5px; padding: 5px; }
.image-controls li { display: inline-block; margin: 5px; padding: 5px; }
.link-display { margin-left: 0.5rem; }
.button { appearance: button; -moz-appearance: button; -webkit-appearance: button; text-decoration: none; font-size: 0.9em; }

audio, video, img, iframe { max-width: 100%; }
object { width: 100%; height: 90vh; }
figure:not(.preview) { display: inline-block; }
figure:not(.preview) > :first-child { display: block; }
figcaption { text-align: center; }
.avatar { vertical-align: middle; }

.printable { padding: 2rem; }

h1 { text-align: center; }
.sitename { margin-top: 5rem; margin-bottom: 3rem; font-size: 2.5rem; }
.logo { max-width: 4rem; max-height: 4rem; vertical-align: middle; }
.logo.small { max-width: 2rem; max-height: 2rem; }
main:not(.printable) { position: relative; z-index: 1000; padding: 2em 2em 0.5em 2em; background: var(--bg-page); box-shadow: 0 0.1rem 1rem 0.3rem var(--shadow); }

blockquote { padding-left: 1em; border-left: 0.2em solid var(--accent-a3); border-radius: 0.2rem; }

pre { white-space: pre-wrap; padding: 0.3em 0.5em; background: var(--bg-page-inset); border-radius: 0.25em; box-shadow: inset 0 0 0.5em var(--shadow); }
code { font-size: 1.1em; }

a { cursor: pointer; }
a:focus { outline-width: 0.1em; }
a.redlink:link { color: var(--accent-c1); }
a.redlink:visited { color: var(--accent-c2); }
a.redlink:active, a.redlink:focus { color: var(--accent-c3); }
a.interwiki_link::before { content: "\\1f6f8"; display: inline-block; margin-right: 0.25em; }
a.interwiki_link { color: var(--accent-d1); }
a.interwiki_link:visited { color: var(--accent-d2); }
a.interwiki_link:active { color: var(--accent-d3); }

.spoiler		{ background: var(--accent-a2); border: 0.1em dashed var(--accent-b1); border-radius: 0.2em; color: transparent !important; text-decoration: none; }
.spoiler:target	{ color: inherit !important; }

/* Ref https://devdocs.io/html/element/del#Accessibility_concerns it's better than nothing, but I'm not happy with it. How would a screen reader user skipt he spsoiler if they don't want to hear it? */
.spoiler::before, .spoiler::after {
	clip-path: inset(100%); clip: rect(1px, 1px, 1px, 1px);
	position: absolute; width: 1px; height: 1px;
	overflow: hidden; white-space: nowrap;
}
.spoiler::before	{ content: " [spoiler start] ";	}
.spoiler::after		{ content: " [spoiler end] ";	}


.matching-tags-display { display: flex; margin: 0 -2em; padding: 1em 2em; background: hsla(30, 84%, 72%, 0.75); }
.matching-tags-display > label { flex: 0; font-weight: bold; color: var(--accent-a3); }
.matching-tags-display > .tags { flex: 2; }

.search-result { position: relative; }
.search-result::before { content: attr(data-result-number); position: relative; top: 3rem; color: var(--text-soft); font-size: 2rem; }
.search-result::after { content: "Rank: " attr(data-rank); position: absolute; top: 3.8rem; right: 0.7rem; color: var(--text-ultrasoft); }
.search-result > h2 { margin-left: 3rem; }
.search-result-badges { font-size: 1rem; font-weight: normal; }
.search-context { min-height: 3em; max-height: 20em; overflow: hidden; }

.editform { position: relative; }
textarea[name=content] { resize: none; min-height: 75vh; }
.fit-text-mirror { position: absolute; top: 0; left: -10000vw; word-wrap: break-word; white-space: pre-wrap; }

.awesomplete { width: 100%; color: var(--text-dark); }
.awesomplete > ul::before { display: none; }
/* Overly specific to override library css */
.awesomplete > ul[role=listbox] { top: 2.5em; background: var(--accent-a2); }


main label:not(.link-display-label) { display: inline-block; min-width: 16rem; }
input[type=text]:not(.link-display), input[type=password], input[type=url], input[type=email], input[type=number], textarea { margin: 0.5rem 0; }
input[type=text], input[type=password], input[type=url], input[type=email], input[type=number], textarea, #search-box { padding: 0.5rem 0.8rem; background: var(--accent-a4); border: 0; border-radius: 0.3rem; font-size: 1rem; color: var(--text-bright); }
textarea, .fit-text-mirror { min-height: 10em; line-height: 1.3em; font-size: 1.25rem; }
textarea, textarea[name=content] + pre, textarea ~ input[type=submit], #search-box { width: calc(100% - 0.3rem); box-sizing: border-box; }
textarea ~ input[type=submit] { margin: 0.5rem 0; padding: 0.5rem; font-weight: bolder; }
.editform input[type=text]	{ width: calc(100% - 0.3rem); box-sizing: border-box; }
.editform label				{ margin-left: 1em;		}
input.edit-page-button[type='submit'] { width: 49.5%; box-sizing: border-box; }
input[type=radio] { transform: scale(2); }
input[type=submit].large { width: 100%; box-sizing: border-box; padding: 0.5em; font-size: 1.25em; font-weight: bolder; }

.smartsave-restore	{ margin-bottom: 1em;	}

.preview-message { text-align: center; }
@media (min-width: 800px) {
	.jump-to-comments { position: absolute; top: 3.5em; right: 2em; display: block; text-align: right; pointer-events: none; }
}
@media (max-width: 799px) {
	.jump-to-comments { display: inline-block; }
	.link-parent-page { display: inline-block; }
}
.jump-to-comments > a { pointer-events: all; }

.file-gallery { margin: 0.5em; padding: 0.5em; list-style-type: none; }
.file-gallery > li { display: inline-block; min-width: attr(data-gallery-width); padding: 1em; text-align: center; }
.file-gallery > li img, .file-gallery > li video, .file-gallery > li audio { display: block; margin: 0 auto; background-color: var(--file); }

.page-tags-display { margin: 0.5rem 0 0 0; padding: 0; list-style-type: none; }
.page-tags-display li { display: inline-block; margin: 0.5rem; padding: 0.5rem; background: var(--tag); white-space: nowrap; }
.page-tags-display li a { color: var(--accent-b2); text-decoration: none; }
.page-tags-display li::before { content: "\\A"; color: transparent; user-select: none; position: relative; top: 0.03rem; left: -0.9rem; width: 0; height: 0; border-top: 0.6rem solid transparent; border-bottom: 0.6rem solid transparent; border-right: 0.5rem solid var(--tag); }

.page-list { list-style-type: none; margin: 0.3rem; padding: 0.3rem; }
.page-list li:not(.header) { margin: 0.3rem; padding: 0.3rem; }
.page-list li .size { margin-left: 0.7rem; color: var(--text-soft); }
.page-list li .editor { display: inline-block; margin: 0 0.5rem; }
.page-list li .tags { margin: 0 1rem; }
.tag-list { list-style-type: none; margin: 0.5rem; padding: 0.5rem; }
.tag-list li { display: inline-block; margin: 1rem; }
.mini-tag { background: var(--tag); margin: 0 0.4em; padding: 0.2rem 0.4rem; color: var(--accent-b2); text-decoration: none; }

.onebox { display: flex; flex-direction: column;
	background: var(--tag); border: 0.2em solid var(--accent-b1); padding: 0.5em; text-decoration: none; }
.onebox-header { font-weight: bolder; font-size: 125%; border-bottom: 0.1em solid var(--accent-b1); }

.grid-large { display: grid; grid-template-columns: repeat(auto-fit, minmax(25em, 1fr)); grid-auto-rows: min-content; grid-gap: 1em; justify-content: center;}

.theme-item { justify-self: center; text-align: center; }
.theme-item label { min-width: auto; }

.help-section-header::after { content: "#" attr(id); float: right; color: var(--text-soft); font-size: 0.8rem; font-weight: normal; }

.stacked-bar { display: flex; }
.stacked-bar-part	{ break-inside: avoid; white-space: pre; padding: 0.2em 0.3em; }

.cursor-query { cursor: help; }

summary { cursor: pointer; }

.larger { color: var(--addition); }
.smaller, .deletion { color: var(--deletion); }
.nochange { color: var(--nochange); font-style: italic; }
.significant { font-weight: bolder; font-size: 1.1rem; }
.deletion, .deletion > .editor { text-decoration: line-through; }

.highlighted-diff { white-space: pre-wrap; }
.diff-added { background-color: rgba(31, 171, 36, 0.6); color: rgba(23, 125, 27, 1); }
.diff-removed { background-color: rgba(255, 96, 96, 0.6); color: rgba(191, 38, 38, 1); }

.newpage::before { content: "N"; margin: 0 0.3em 0 -1em; font-weight: bolder; text-decoration: underline dotted; }
.move::before { content: "\\1f69a"; font-size: 1.25em; margin: 0 0.1em 0 -1.1em; }
.upload::before { content: "\\1f845"; margin: 0 0.1em 0 -1.1em; }
.new-comment::before { content: "\\1f4ac"; margin: 0 0.1em 0 -1.1em; }
.reversion::before { content: "\\231b"; margin: 0 0.1em 0 -1.1em; }

.similar-page-suggestions { padding: 1em 2em; background: var(--bg-page-inset); position: relative; z-index: 2000; box-shadow: 0 0.1rem 1rem 0.3rem var(--shadow);}
.similar-page-suggestions > h2 { text-align: center; }
.similar-page-suggestions-list { list-style-type:none;
padding: 0; display: grid; grid:auto / repeat(auto-fit, minmax(min(15em, 100%), 1fr)); justify-items: center; grid-gap: 1em; }

.comments { padding: 1em 2em; background: var(--bg-comments-1); box-shadow: 0 0.1rem 1rem 0.3rem var(--shadow); }
.comments .not-logged-in { padding: 0.3em 0.65em; background: var(--bg-comments-2); border-radius: 0.2em; font-style: italic; }
.comments textarea { resize: vertical; }

.comment { margin: 1em 0; padding: 0.01em 0; background: var(--bg-comments-3); }
.comment-header { padding: 0 1em; }
.comment .name { font-weight: bold; }
.comment-body { padding: 0 1em; }
.comment-footer { padding-left: 1em; }
.comment-footer-item { padding: 0 0.3em; }
.comment-footer .delete-button { appearance: button; -moz-appearance: button; -webkit-appearance: button; text-decoration: none; color: var(--text-os-button); }
.permalink-button { text-decoration: none; }
.comments-list .comments-list .comment { margin: 1em; }

.reply-box-container.active { padding: 1em; background: var(--bg-comments-4); }

footer { padding: 2rem; }

/* Small screen adjustments */
@media (max-width: 480px) {
	body {
		margin: 0;
	}
	nav.top, nav.bottom {
		position: static;
		flex-direction: column;
	}
	input[type=checkbox]:checked ~ .nav-more-menu {
		position: static;
	}
	nav.mega-menu {
		flex-direction: column;
	}
	nav.top > span:first-child {
		border-top: 3px solid var(--accent-a3);
	}
	
	main:not(.printable) {
		padding: 1em 1em 0.5em 1em;
	}
	
	.comments {
		padding: 1em;
	}
	
	footer {
		padding: 1em;
	}
}
THEMECSS;

// This will automatically save to peppermint.json if an automatic takes place 
// for another reason (such as password rehashing or user data updates), but it 
// doesn't really matter because the site name isn't going to change all that 
// often, and even if it does it shouldn't matter :P
if($settings->sessionprefix == "auto")
	$settings->sessionprefix = "pepperminty-wiki-" . preg_replace('/[^a-z0-9\-_]/', "-", strtolower($settings->sitename));



/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


/** The version of Pepperminty Wiki currently running. */
$version = "v0.24";
$commit = "63771c4078845471ff62ac3bf76b322d2e13b861";
/// Environment ///
/** Holds information about the current request environment. */
$env = new stdClass();
/** The action requested by the user. @var string */
$env->action = $settings->defaultaction;
/** The page name requested by the remote client. @var string */
$env->page = "";
/** The page name, but run through htmlentities(), thus making it safe to display in an output document. @var string */
$env->page_safe = "";
/** The filename that the page is stored in. @var string */
$env->page_filename = "";
/** Whether we are looking at a history revision or not. @var boolean */
$env->is_history_revision = false;
/** An object holding history revision information for the current request */
$env->history = new stdClass();
/** The revision number requested of the current page @var int */
$env->history->revision_number = -1;
/** The revision data object from the page index for the requested revision */
$env->history->revision_data = false;
/** The user's name if they are logged in. Defaults to `$settings->anonymous_user_name` if the user isn't currently logged in. @var string */
$env->user = $settings->anonymous_user_name;
/** Whether the user is logged in @var boolean */
$env->is_logged_in = false;
/** Whether the user is an admin (moderator) @todo Refactor this to is_moderator, so that is_admin can be for the server owner. @var boolean */
$env->is_admin = false;
/** Whether the current request was made a secure connection or not. @var boolean */
$env->is_secure = !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off';
/** The currently logged in user's data. Please see $settings->users->username if you need to edit this - this is here for convenience :-) */
$env->user_data = new stdClass();
/** The data storage directory. Page filenames should be prefixed with this if you want their content. */
$env->storage_prefix = $settings->data_storage_dir . DIRECTORY_SEPARATOR;
/** Contains performance data statistics for the current request. */
$env->perfdata = new stdClass();
/// Paths ///
/**
 * Contains a bunch of useful paths to various important files.
 * None of these need to be prefixed with `$env->storage_prefix`.
 */
$paths = new stdClass();
/** The pageindex. Contains extensive information about all pages currently in this wiki. Individual entries for pages may be extended with arbitrary properties. */
$paths->pageindex = "pageindex.json";
/** The inverted index used for searching. Use the `search` class to interact with this - otherwise your brain might explode :P */
$paths->searchindex = "invindex.sqlite";
/** The didyoumean index for typo correction. Used by the search class - which also exposes an interface for interacting with it directly. */
$paths->didyoumeanindex =  "didyoumeanindex.sqlite";
/** The index that maps ids to page names. Use the `ids` class to interact with it :-) */
$paths->idindex = "idindex.json";
/** The cache of the most recently calculated statistics. */
$paths->statsindex = "statsindex.json";
/** The interwiki index cache */
$paths->interwiki_index = "interwiki_index.json";
/** The cache directory, minus the trailing slash. Contains cached rendered versions of pages. If things don't update, try deleting this folder.  */
$paths->cache_directory = "._cache";

// Prepend the storage data directory to all the defined paths.
foreach ($paths as &$path) {
	$path = $env->storage_prefix . $path;
}

/** The master settings file @var string */
$paths->settings_file = $settingsFilename;
/** The directory to which the extra bundled data is extracted to @var string */
$paths->extra_data_directory = "._extra_data";
/** The prefix to add to uploaded files */
$paths->upload_file_prefix = "Files/";

// Create the cache directory if it doesn't exist
if(!is_dir($paths->cache_directory))
	mkdir($paths->cache_directory, 0700);

// Set the user agent string
$php_version = ini_get("expose_php") == "1" ? "PHP/".phpversion() : "PHP";
ini_set("user_agent", "$php_version ($settings->sitename; ".PHP_SAPI."; ".PHP_OS." ".php_uname("m")."; ".(PHP_INT_SIZE*8)." bits; rv:$version) Pepperminty-Wiki/$version-".substr($commit, 0, 7));
unset($php_version);

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


/**
 * Get the actual absolute origin of the request sent by the user.
 * @package core
 * @param  array	$s						The $_SERVER variable contents. Defaults to $_SERVER.
 * @param  bool		$use_forwarded_host		Whether to utilise the X-Forwarded-Host header when calculating the actual origin.
 * @return string							The actual origin of the user's request.
 */
function url_origin( $s = false, $use_forwarded_host = false )
{
	global $env;
	if($s === false) $s = $_SERVER;
	$ssl      = $env->is_secure;
	$sp       = strtolower( $s['SERVER_PROTOCOL'] );
	$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
	$port     = $s['SERVER_PORT'];
	$port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
	$host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
	$host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
	return $protocol . '://' . $host;
}

/**
 * Get the full url, as requested by the client.
 * @package core
 * @see		http://stackoverflow.com/a/8891890/1460422	This Stackoverflow answer.
 * @param	array	$s                  The $_SERVER variable. Defaults to $_SERVER.
 * @param	bool		$use_forwarded_host Whether to take the X-Forwarded-Host header into account.
 * @return	string						The full url, as requested by the client.
 */
function full_url($s = false, $use_forwarded_host = false) {
	if($s == false) $s = $_SERVER;
	return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}

/**
 * Get the stem URL at which this Pepperminty Wiki instance is located
 * You can just append ?get_params_here to this and it will be a valid URL.
 * Uses full_url() under the hood.
 * Note that this is based on the URL of the current request.
 * @param	array	$s					The $_SERVER variable (defaults to $_SERVER)
 * @param	bool	$use_forwarded_host	Whether to use the x-forwarded-host header or ignore it.
 * @return	string	The stem url, as described above
 */
function url_stem( $s = false, bool $use_forwarded_host = false) : string {
	// Calculate the stem from the current full URL by stripping everything after the question mark ('?')
	$url_stem = full_url();
	if(mb_strpos($url_stem, "?") !== false) $url_stem = mb_substr($url_stem, 0, mb_strpos($url_stem, "?"));
	return $url_stem;
}

/**
 * Converts a filesize into a human-readable string.
 * @package core
 * @see	http://php.net/manual/en/function.filesize.php#106569	The original source
 * @author	rommel
 * @author	Edited by Starbeamrainbowlabs
 * @param	int		$bytes		The number of bytes to convert.
 * @param	int		$decimals	The number of decimal places to preserve.
 * @return 	string				A human-readable filesize.
 */
function human_filesize($bytes, $decimals = 2)
{
	$sz = ["b", "kib", "mib", "gib", "tib", "pib", "eib", "yib", "zib"];
	$factor = floor((strlen($bytes) - 1) / 3);
	$result = round($bytes / pow(1024, $factor), $decimals);
	return $result . @$sz[$factor];
}

/**
 * Calculates the time since a particular timestamp and returns a
 * human-readable result.
 * @package core
 * @see http://goo.gl/zpgLgq The original source. No longer exists, maybe the wayback machine caught it :-(
 * @param	int		$time	The timestamp to convert.
 * @return	string	The time since the given timestamp as a human-readable string.
 */
function human_time_since($time)
{
	return human_time(time() - $time);
}
/**
 * Renders a given number of seconds as something that humans can understand more easily.
 * @package core
 * @param 	int		$seconds	The number of seconds to render.
 * @return	string	The rendered time.
 */
function human_time($seconds)
{
	$tokens = array (
		31536000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);
	foreach ($tokens as $unit => $text) {
		if ($seconds < $unit) continue;
		$numberOfUnits = floor($seconds / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
	}
}

/**
 * A recursive glob() function.
 * @package core
 * @see http://in.php.net/manual/en/function.glob.php#106595	The original source
 * @author	Mike
 * @param	string	$pattern	The glob pattern to use to find filenames.
 * @param	int		$flags		The glob flags to use when finding filenames.
 * @return	array	An array of the filepaths that match the given glob.
 */
function glob_recursive($pattern, $flags = 0)
{
	$files = glob($pattern, $flags);
	foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
	{
		$prefix = "$dir/";
		// Remove the "./" from the beginning if it exists
		if(substr($prefix, 0, 2) == "./") $prefix = substr($prefix, 2);
		$files = array_merge($files, glob_recursive($prefix . basename($pattern), $flags));
	}
	return $files;
}

/**
 * Resolves a relative path against a given base directory.
 * @since 0.20.0
 * @source	https://stackoverflow.com/a/44312137/1460422
 * @param	string		$path		The relative path to resolve.
 * @param	string|null	$basePath	The base directory to resolve against.
 * @return	string		An absolute path.
 */
function path_resolve(string $path, string $basePath = null) {
	// Make absolute path
	if (substr($path, 0, 1) !== DIRECTORY_SEPARATOR) {
		if ($basePath === null) {
			// Get PWD first to avoid getcwd() resolving symlinks if in symlinked folder
			$path=(getenv('PWD') ?: getcwd()).DIRECTORY_SEPARATOR.$path;
		} elseif (strlen($basePath)) {
			$path=$basePath.DIRECTORY_SEPARATOR.$path;
		}
	}

	// Resolve '.' and '..'
	$components=array();
	foreach(explode(DIRECTORY_SEPARATOR, rtrim($path, DIRECTORY_SEPARATOR)) as $name) {
		if ($name === '..') {
			array_pop($components);
		} elseif ($name !== '.' && !(count($components) && $name === '')) {
			// … && !(count($components) && $name === '') - we want to keep initial '/' for abs paths
			$components[]=$name;
		}
	}

	return implode(DIRECTORY_SEPARATOR, $components);
}

/**
 * Determines if a directory is empty or not.
 * @ref https://stackoverflow.com/a/7497848/1460422
 * @param	string	$dir	The path to the directory to check.
 * @return	boolean	True if the directory is empty, or false if it is not empty.
 */
function is_directory_empty(string $dir) : bool {
	$handle = opendir($dir);
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			closedir($handle);
			return false;
		}
	}
	closedir($handle);
	return true;
}

/**
 * Converts a filepath to a page name.
 * @param  string $filepath The filepath to convert.
 * @return string           The extracted pagename.
 */
function filepath_to_pagename(string $filepath) : string {
	global $env;
	// Strip the storage prefix, but only if it isn't a dot
	if(starts_with($filepath, $env->storage_prefix) && $env->storage_prefix !== ".")
		$filepath = mb_substr($filepath, mb_strlen($env->storage_prefix));
	
	// If a revision number is detected, strip it
	if(preg_match("/\.r[0-9]+$/", $filepath) > 0)
		$filepath = mb_substr($filepath, 0, mb_strrpos($filepath, ".r"));
	
	// Strip the .md file extension
	if(ends_with($filepath, ".md"))
		$filepath = mb_substr($filepath, 0, -3);
	
	return $filepath;
}

/**
 * Gets the name of the parent page to the specified page.
 * @since 0.15.0
 * @package core
 * @param  string		$pagename	The child page to get the parent
 * 									page name for.
 * @return string|bool
 */
function get_page_parent($pagename) {
	if(mb_strpos($pagename, "/") === false)
		return false;
	return mb_substr($pagename, 0, mb_strrpos($pagename, "/"));
}

/**
 * Gets a list of all the sub pages of the current page.
 * @package core
 * @param	object	$pageindex	The pageindex to use to search.
 * @param	string	$pagename	The name of the page to list the sub pages of.
 * @return	object				An object containing all the subpages and their
 *     respective distances from the given page name in the pageindex tree.
 */
function get_subpages($pageindex, $pagename)
{
	$pagenames = get_object_vars($pageindex);
	$result = new stdClass();

	$stem = "$pagename/";
	$stem_length = strlen($stem);
	foreach($pagenames as $entry => $value)
	{
		if(substr($entry, 0, $stem_length) == $stem)
		{
			// We found a subpage

			// Extract the subpage's key relative to the page that we are searching for
			$subpage_relative_key = substr($entry, $stem_length, -3);
			// Calculate how many times removed the current subpage is from the current page. 0 = direct descendant.
			$times_removed = substr_count($subpage_relative_key, "/");
			// Store the name of the subpage we found
			$result->$entry = $times_removed;
		}
	}

	unset($pagenames);
	return $result;
}

/**
 * Makes sure that a subpage's parents exist.
 * Note this doesn't check the pagename itself.
 * @package	core
 * @param	string	$pagename	The pagename to check.
 * @param	bool	$create_dir	Whether to create an associated directory for subpages or not.
 */
function check_subpage_parents(string $pagename, bool $create_dir = true)
{
	global $pageindex, $paths, $env;
	// Save the new pageindex and return if there aren't any more parent pages to check
	if(strpos($pagename, "/") === false)
		return save_pageindex();
	
	$pagename = makepathsafe($pagename); // Just in case
	
	$parent_pagename = substr($pagename, 0, strrpos($pagename, "/"));
	$parent_page_filename = "$parent_pagename.md";
	if(!file_exists($env->storage_prefix . $parent_page_filename))
	{
		// This parent page doesn't exist! Create it and add it to the page index.
		touch($env->storage_prefix . $parent_page_filename, 0);
		
		$newentry = new stdClass();
		$newentry->filename = $parent_page_filename;
		$newentry->size = 0;
		$newentry->lastmodified = 0;
		$newentry->lasteditor = "none";
		$pageindex->$parent_pagename = $newentry;
	}
	if($create_dir) {
		$dirname = $env->storage_prefix . $parent_pagename;
		if(!file_exists($dirname))
			mkdir($dirname, 0755, true);
	}

	check_subpage_parents($parent_pagename, $create_dir);
}

/**
 * Makes a path (or page name) safe.
 * A safe path / page name may not contain:
	* Forward-slashes at the beginning
	* Multiple dots in a row
	* Odd characters (e.g. ?%*:|"<>() etc.)
 * A safe path may, however, contain unicode characters such as éôà etc.
 * @package core
 * @param	string	$string	The string to make safe.
 * @return	string			A safe version of the given string.
 */
function makepathsafe($string)
{
	// Old restrictive system
	//$string = preg_replace("/[^0-9a-zA-Z\_\-\ \/\.]/i", "", $string);
	// Remove reserved characters
	$string = preg_replace("/[?%*:|\"><()\\[\\]]/i", "", $string);
	// Collapse multiple dots into a single dot
	$string = preg_replace("/\.+/", ".", $string);
	// Don't allow slashes at the beginning
	$string = ltrim($string, "\\/");
	// Don't allow dots on their own
	$string = preg_replace(["/^\.\\/|\\/\.$/", "/\\/\.\\//"], ["", "/"], $string);
	return $string;
}

/**
 * Slugifies a given string such that it can only contain a-z0-9-_.
 * Also automatically makes it lowercase.
 * @param	string	$text	The text to operate on.
 * @return	string	The slugified string.
 */
function slugify(string $text) : string {
	return preg_replace("/[^a-zA-Z0-9\-_]/", "", $text);
}

/**
 * Hides an email address from bots. Returns a fragment of HTML that contains the mangled email address.
 * @package core
 * @param	string	$str			The original email address
 * @param	string	$display_text	The display text for the resulting HTML - if null then the original email address is used. Note that because it's base64 encoded and then textContent is used, one does not need to run either htmlentities() or rawurlencode() over this value as it's completely safe.
 * @return	string	The mangled email address as a fragment of HTML.
 */
function hide_email(string $email, string $display_text = null) : string
{
	$enc = json_encode([ $email, $display_text ]);
	$len = strlen($enc);
	$pool = []; for($i = 0; $i < $len; $i++) $pool[] = $i;
	$a = []; $b = [];
	for($i = 0; $i < $len; $i++) {
		$n = random_int(0, $len - $i - 1);
		$j = array_splice($pool, $n, 1)[0]; $b[] = $j;
		// echo("chose ".$enc[$j].", index $j, n $n\n");
		$a[] = $enc[$j];
	}
	$a = base64_encode(implode("|", $a));
	$b = base64_encode(implode("|", $b));
	$span_id = "he-".crypto_id(16);
	return "<a href='#protected-with-javascript' id='$span_id'>[protected with javascript]</a><script>(() => {let c=\"$a|$b\".split('|').map(atob).map(s=>s.split('|'));let d=[],e=document.getElementById('$span_id');c[1].map((n,i)=>d[parseInt(n)]=c[0][i]);d=JSON.parse(d.join(''));e.textContent=d[1]==null?d[0]:d[1];e.setAttribute('href', 'mailto:'+d[0])})();</script>";
}

/**
 * Checks to see if $haystack starts with $needle.
 * @package	core
 * @param	string	$haystack	The string to search.
 * @param	string	$needle		The string to search for at the beginning
 *                        		of $haystack.
 * @return	bool	Whether $needle can be found at the beginning of $haystack.
 */
function starts_with(string $haystack, string $needle) : bool {
	$length = strlen($needle);
	return (substr($haystack, 0, $length) === $needle);
}
/**
 * Checks to see if $hackstack ends with $needle.
 * The matching bookend to starts_with.
 * @package	core
 * @param	string	$haystack	The haystack to search..
 * @param	string	$needle		The needle to look for.
 * @return	bool
 */
function ends_with(string $haystack, string $needle) : bool {
	$length = strlen($needle);
	return (substr($haystack, -$length) === $needle);
}

/**
 * Case-insensitively finds all occurrences of $needle in $haystack. Handles
 * UTF-8 characters correctly.
 * @package core
 * @see	http://www.pontikis.net/tip/?id=16 the source
 * @see	http://www.php.net/manual/en/function.strpos.php#87061	the source that the above was based on
 * @param	string			$haystack	The string to search.
 * @param	string			$needle		The string to find.
 * @return	array|false					An array of match indices, or false if
 *                  					nothing was found.
 */
function mb_stripos_all($haystack, $needle) {
	$s = 0; $i = 0;
	while(is_integer($i)) {
		$i = mb_stripos($haystack, $needle, $s);
		if(is_integer($i)) {
			$aStrPos[] = $i;
			$s = $i + (function_exists("mb_strlen") ? mb_strlen($needle) : strlen($needle));
		}
	}
	if(isset($aStrPos))
		return $aStrPos;
	else
		return false;
}

/**
 * Tests whether a string starts with a specified substring.
 * @package core
 * @param 	string	$haystack	The string to check against.
 * @param 	string	$needle		The substring to look for.
 * @return	bool				Whether the string starts with the specified substring.
 */
function startsWith($haystack, $needle) {
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}
/**
 * Tests whether a string ends with a given substring.
 * @package core
 * @param  string $whole The string to test against.
 * @param  string $end   The substring test for.
 * @return bool          Whether $whole ends in $end.
 */
function endsWith($whole, $end) {
	return (strpos($whole, $end, strlen($whole) - strlen($end)) !== false);
}
/**
 * Replaces the first occurrence of $find with $replace.
 * @package core
 * @param  string $find    The string to search for.
 * @param  string $replace The string to replace the search string with.
 * @param  string $subject The string ot perform the search and replace on.
 * @return string		   The source string after the find and replace has been performed.
 */
function str_replace_once($find, $replace, $subject) {
	$index = strpos($subject, $find);
	if($index !== false)
		return substr_replace($subject, $replace, $index, strlen($find));
	return $subject;
}

/**
 * Returns the system's mime type mappings, considering the first extension
 * listed to be cacnonical.
 * @package core
 * @see http://stackoverflow.com/a/1147952/1460422 From this stackoverflow answer
 * @author	chaos
 * @author	Edited by Starbeamrainbowlabs
 * @return array	An array of mime type mappings.
 */
function system_mime_type_extensions() {
	global $settings;
	$out = array();
	$file = fopen($settings->mime_extension_mappings_location, 'r');
	while(($line = fgets($file)) !== false) {
		$line = trim(preg_replace('/#.*/', '', $line));
		if(!$line)
			continue;
		$parts = preg_split('/\s+/', $line);
		if(count($parts) == 1)
			continue;
		$type = array_shift($parts);
		if(!isset($out[$type]))
			$out[$type] = array_shift($parts);
	}
	fclose($file);
	return $out;
}

/**
 * Converts a given mime type to it's associated file extension.
 * @package core
 * @see http://stackoverflow.com/a/1147952/1460422 From this stackoverflow answer
 * @author	chaos
 * @author	Edited by Starbeamrainbowlabs
 * @param  string $type The mime type to convert.
 * @return string       The extension for the given mime type.
 */
function system_mime_type_extension($type) {
	static $exts;
	if(!isset($exts))
		$exts = system_mime_type_extensions();
	return isset($exts[$type]) ? $exts[$type] : null;
}

/**
 * Returns the system MIME type mapping of extensions to MIME types.
 * @package core
 * @see http://stackoverflow.com/a/1147952/1460422 From this stackoverflow answer
 * @author	chaos
 * @author	Edited by Starbeamrainbowlabs
 * @return array An array mapping file extensions to their associated mime types.
 */
function system_extension_mime_types() {
	global $settings;
	$out = array();
	$file = fopen($settings->mime_extension_mappings_location, 'r');
	while(($line = fgets($file)) !== false) {
		$line = trim(preg_replace('/#.*/', '', $line));
		if(!$line)
			continue;
		$parts = preg_split('/\s+/', $line);
		if(count($parts) == 1)
			continue;
		$type = array_shift($parts);
		foreach($parts as $part)
			$out[$part] = $type;
	}
	fclose($file);
	return $out;
}
/**
 * Converts a given file extension to it's associated mime type.
 * @package core
 * @see http://stackoverflow.com/a/1147952/1460422 From this stackoverflow answer
 * @author	chaos
 * @author	Edited by Starbeamrainbowlabs
 * @param  string $ext The extension to convert.
 * @return string      The mime type associated with the given extension.
 */
function system_extension_mime_type($ext) {
	static $types;
	if(!isset($types))
		$types = system_extension_mime_types();
	$ext = strtolower($ext);
	return isset($types[$ext]) ? $types[$ext] : null;
}

/**
 * Creates an images containing the specified text.
 * Useful for sending errors back to the client.
 * @package core
 * @param	string	$text			The text to include in the image.
 * @param	int		$target_size	The target width to aim for when creating 
 * 									the image. Not not specified, a value is 
 * 									determined automatically.
 * @return	resource				The handle to the generated GD image.
 */
function errorimage($text, $target_size = null)
{
	$width = 0;
	$height = 0;
	$border_size = 10; // in px, if $target_size isn't null has no effect
	$line_spacing = 2; // in px
	$font_size = 5; // 1 - 5
	
	$font_width = imagefontwidth($font_size);	// in px
	$font_height = imagefontheight($font_size);	// in px
	$text_lines = array_map("trim", explode("\n", $text));
	
	if(!empty($target_size)) {
		$width = $target_size;
		$height = $target_size * (2 / 3);
	}
	else {
		$height = count($text_lines) * $font_height + 
			(count($text_lines) - 1) * $line_spacing +
			$border_size * 2;
		foreach($text_lines as $line)
			$width = max($width, $font_width * mb_strlen($line));
		$width += $border_size * 2;
	}
	
	$image = imagecreatetruecolor($width, $height);
	imagefill($image, 0, 0, imagecolorallocate($image, 250, 249, 251)); // Set the background to #faf8fb
	
	$i = 0;
	foreach($text_lines as $line) {
		imagestring($image, $font_size,
			($width / 2) - (($font_width * mb_strlen($line)) / 2),
			$border_size + $i * ($font_height + $line_spacing),
			$line,
			imagecolorallocate($image, 68, 39, 113) // #442772
		);
		$i++;	
	}
	
	return $image;
}

/**
 * Generates a stack trace.
 * @package core
 * @param	bool	$log_trace	Whether to send the stack trace to the error log.
 * @param	bool	$full		Whether to output a full description of all the variables involved.
 * @return	string				A string prepresentation of a stack trace.
 */
function stack_trace($log_trace = true, $full = false)
{
	$result = "";
	$stackTrace = debug_backtrace();
	$stackHeight = count($stackTrace);
	foreach ($stackTrace as $i => $stackEntry)
	{
		$result .= "#" . ($stackHeight - $i) . ": ";
		$result .= (isset($stackEntry["file"]) ? $stackEntry["file"] : "(unknown file)") . ":" . (isset($stackEntry["line"]) ? $stackEntry["line"] : "(unknown line)") . " - ";
		if(isset($stackEntry["function"]))
		{
			$result .= "(calling " . $stackEntry["function"];
			if(isset($stackEntry["args"]) && count($stackEntry["args"]))
			{
				$result .= ": ";
				$result .= implode(", ", array_map($full ? "var_dump_ret" : "var_dump_short", $stackEntry["args"]));
			}
		}
		$result .= ")\n";
	}
	if($log_trace)
		error_log($result);
	return $result;
}
/**
 * Calls var_dump() and returns the output.
 * @package core
 * @param	mixed	$var	The thing to pass to var_dump().
 * @return	string			The output captured from var_dump().
 */
function var_dump_ret($var)
{
	ob_start();
	var_dump($var);
	return ob_get_clean();
}

/**
 * Calls var_dump(), shortening the output for various types.
 * @package core
 * @param	mixed 	$var	The thing to pass to var_dump().
 * @return	string			A shortened version of the var_dump() output.
 */
function var_dump_short($var)
{
	$result = trim(var_dump_ret($var));
	if(substr($result, 0, 6) === "object" || substr($result, 0, 5) === "array")
	{
		$result = substr($result, 0, strpos($result, " ")) . " { ... }";
	}
	return $result;
}

if (!function_exists('getallheaders'))  {
	/**
	 * Polyfill for PHP's native getallheaders() function on platforms that
	 * don't have it.
	 * @package core
	 * @todo	Identify which platforms don't have it and whether we still need this
	 */
	function getallheaders() {
		if (!is_array($_SERVER))
			return [];

		$headers = array();
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
}
/**
 * Renders a timestamp in HTML.
 * @package core
 * @param	int		$timestamp	The timestamp to render.
 * @param	boolean	$absolute	Whether the time should be displayed absolutely, or relative to the current time.
 * @param	boolean	$html		Whether the result should formatted as HTML (true) or plain text (false).
 * @return string         HTML representing the given timestamp.
 */
function render_timestamp($timestamp, $absolute = false, $html = true) {
	$time_rendered = $absolute ? date("Y-m-d g:ia e", $timestamp) : human_time_since($timestamp);
	if($html)
		return "<time class='cursor-query' datetime='".date("c",  $timestamp)."' title='" . date("l jS \of F Y \a\\t h:ia T", $timestamp) . "'>$time_rendered</time>";
	else
		return $time_rendered;
}
/**
 * Renders a page name in HTML.
 * @package core
 * @param  object $rchange The recent change to render as a page name
 * @return string          HTML representing the name of the given page.
 */
function render_pagename($rchange) {
	global $pageindex;
	$pageDisplayName = htmlentities($rchange->page);
	if(isset($pageindex->$pageDisplayName) and !empty($pageindex->$pageDisplayName->redirect))
		$pageDisplayName = "<em>$pageDisplayName</em>";
	$pageDisplayLink = "<a href='?page=" . rawurlencode($rchange->page) . "'>$pageDisplayName</a>";
	return $pageDisplayName;
}
/**
 * Renders an editor's or a group of editors name(s) in HTML.
 * @package core
 * @param  string $editorName The name of the editor to render. Note that this may contain ARBITRARY HTML! In other words, make sure that the editor name(s) are sanitized (e.g. htmlentities()'d) before padding to this function.
 * @return string             HTML representing the given editor's name.
 */
function render_editor($editorName) {
	return "<span class='editor'>&#9998; $editorName</span>";
}

/**
 * Minifies CSS. Uses simple computationally-cheap optimisations to reduce size.
 * CSS Minification ideas by Jean from catswhocode.com
 * @source	http://www.catswhocode.com/blog/3-ways-to-compress-css-files-using-php
 * @since	0.20.0
 * @param	string	$css_str	The string of CSS to minify.
 * @return	string	The minified CSS string.
 */
function minify_css(string $css_str) : string {
	// Remove comments
	$result = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', "", $css_str);
	// Cut down whitespace
	$result = preg_replace('/\s+/', " ", $result);
	// Remove whitespace after colons and semicolons
	$result = str_replace([
		" :", ": ", "; ",
		" { ", " } ", "{ ", " {", "} ", " }",
		", ", "0."
	], [
		":", ":", ";",
		"{", "}", "{", "{", "}", "}",
		",", "."
	], $result);
	return $result;
}

/**
 * Saves the settings file back to peppermint.json.
 * @package	core
 * @return	bool	Whether the settings were saved successfully.
 */
function save_settings() {
	global $paths, $settings;
	return file_put_contents($paths->settings_file, json_encode($settings, JSON_PRETTY_PRINT)) !== false;
}
/**
 * Save the page index back to disk, respecting $settings->minify_pageindex
 * @package	core
 * @return	bool	Whether the page index was saved successfully or not.
 */
function save_pageindex() {
	global $paths, $settings, $pageindex;
	return file_put_contents(
		$paths->pageindex,
		json_encode($pageindex, $settings->minify_pageindex ? 0 : JSON_PRETTY_PRINT)
	);
}

/**
 * Saves the currently logged in user's data back to peppermint.json.
 * @package	core
 * @return	bool	Whether the user's data was saved successfully. Returns false if the user isn't logged in.
 */
function save_userdata() {
	global $env, $settings, $paths;
	
	if(!$env->is_logged_in)
		return false;
	
	$settings->users->{$env->user} = $env->user_data;
	
	return save_settings();
}

/**
 * Figures out the path to the user page for a given username.
 * Does not check to make sure the user acutally exists. 
 * @package core
 * @param  string $username The username to get the path to their user page for.
 * @return string           The path to the given user's page.
 */
function get_user_pagename($username) {
	global $settings;
	return "$settings->user_page_prefix/$username";
}
/**
 * Extracts a username from a user page path.
 * @package core
 * @param  string $userPagename The suer page path to extract from.
 * @return string               The name of the user that the user page belongs to.
 */
function extract_user_from_userpage($userPagename) {
	global $settings;
	$matches = [];
	preg_match("/$settings->user_page_prefix\\/([^\\/]+)\\/?/", $userPagename, $matches);
	
	return $matches[1];
}

/**
 * Sends a plain text email to a user, replacing {username} with the specified username.
 * @package core
 * @param	string	$username	The username to send the email to.
 * @param	string	$subject	The subject of the email.
 * @param	string	$body		The body of the email.
 * @param	bool	$ignore_verification	Whether to ignore user email verification status and send the email anyway. Defaults to false.
 * @return	bool	Whether the email was sent successfully or not. Currently, this may fail if the user doesn't have a registered email address.
 */
function email_user(string $username, string $subject, string $body, bool $ignore_verification = false) : bool
{
	global $version, $env, $settings;
	
	static $literator = null;
	if($literator == null) $literator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', Transliterator::FORWARD);
	
	// If the user doesn't have an email address, then we can't email them :P
	if(empty($settings->users->{$username}->emailAddress))
		return false;
	
	// If email address verification is required but hasn't been done for this user, skip them
	if(empty($env->user_data->emailAddressVerified) && !$ignore_verification)
		return false;
	
	
	$headers = [
		"x-mailer" => ini_get("user_agent"),
		"reply-to" => "$settings->admindetails_name <$settings->admindetails_email>"
	];
	
	// Correctly encode the subject
	if($settings->email_subject_utf8)
		$subject = "=?utf-8?B?" . base64_encode($username) . "?=";
	else
		$subject = $literator->transliterate($subject);
	
	// Correctly encode the message body
	if($settings->email_body_utf8)
		$headers["content-type"] = "text/plain; charset=utf-8";
	else {
		$headers["content-type"] = "text/plain";
		$body = $literator->transliterate($body);
	}
	
	$subject = str_replace("{username}", $username, $subject);
	$body = str_replace("{username}", $username, $body);
	
	$compiled_headers = "";
	foreach($headers as $header => $value)
		$compiled_headers .= "$header: $value\r\n";
	
	if($settings->email_debug_dontsend) {
		error_log("[PeppermintyWiki/$settings->sitename/email] Username: $username ({$settings->users->{$username}->emailAddress})
Subject: $subject
----- Headers -----
$compiled_headers
-------------------
----- Body -----
$body
----------------");
		return true;
	}
	else
		return mail($settings->users->{$username}->emailAddress, $subject, $body, $compiled_headers, "-t");
}
/**
 * Sends a plain text email to a list of users, replacing {username} with each user's name.
 * @package core
 * @param  string[]	$usernames	A list of usernames to email.
 * @param  string	$subject	The subject of the email.
 * @param  string	$body		The body of the email.
 * @return int					The number of emails sent successfully.
 */
function email_users($usernames, string $subject, string $body) : int
{
	$emailsSent = 0;
	foreach($usernames as $username)
	{
		$emailsSent += email_user($username, $subject, $body) ? 1 : 0;
	}
	return $emailsSent;
}

/**
 * Recursively deletes a directory and it's contents.
 * Adapted by Starbeamrainbowlabs
 * @param	string	$path			The path to the directory to delete.
 * @param	bool	$delete_self	Whether to delete the top-level directory. Set this to false to delete only a directory's contents
 * @source https://stackoverflow.com/questions/4490637/recursive-delete
 */
function delete_recursive($path, $delete_self = true) {
	$it = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($path),
		RecursiveIteratorIterator::CHILD_FIRST
	);
	foreach ($it as $file) {
		if (in_array($file->getBasename(), [".", ".."]))
			continue;
		if($file->isDir())
			rmdir($file->getPathname());
		else
			unlink($file->getPathname());
	}
	if($delete_self) rmdir($path);
}

/**
 * Generates a crytographically-safe random id of the given length.
 * @param	int		$length		The length of id to generate.
 * @return	string	The random id.
 */
function crypto_id(int $length) : string {
	// It *should* be the right length already, but it doesn't hurt to be safe
	return substr(strtr(
		base64_encode(random_bytes($length * 0.75)),
		[ "=" => "", "+" => "-", "/" => "_"]
	), 0, $length);
}

/**
 * Returns whether we are both on the cli AND the cli is enabled.
 * @return boolean
 */
function is_cli() : bool {
	global $settings;
	return php_sapi_name() == "cli" &&
		$settings->cli_enabled;
}

function metrics2servertiming(stdClass $perfdata) : string {
	$result = [];
	foreach($perfdata as $key => $value) {
		$result[] = str_replace("_", "", $key).";dur=$value";
	}
	return "foo, ".implode(", ", $result);
}

/**
 * Sets a cookie on the client via the set-cookie header.
 * Uses setcookie() under-the-hood.
 * @param  string $key     The cookie name to set.
 * @param  string $value   The cookie value to set.
 * @param  int    $expires The expiry time to set on the cookie.
 * @return void
 */
function send_cookie(string $key, $value, int $expires) : void {
	global $env, $settings;
	
	$cookie_secure = true;
	switch ($settings->cookie_secure) {
		case "false":
			$cookie_secure = false;
			break;
		case "auto":
		default:
			$cookie_secure = $env->is_secure;
			break;
	}
	
	if(version_compare(PHP_VERSION, "7.3.0") >= 0) {
		// Phew! We're running PHP 7.3+, so we're ok to use the array syntax
		setcookie($key, $value, [
			"expires" => $expires,
			"secure" => $cookie_secure,
			"httponly" => true,
			"samesite" => "Strict"
		]);
	}
	else {
		if(!$env->is_secure) error_log("[pepperminty_wiki/$settings->sitename] Warning: You are using a version of PHP that is less than 7.3. This is not recommended - as the samesite cookie flag can't be set in PHP 7.3-, and this is insecure - as it opens you to session stealing attacks. In addition, browsers have deprecated non-samesite cookies in insecure contexts. Please upgrade today!");
		setcookie($key, $value, $expires, "", "", $cookie_secure, true);
	}
}

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


// If the extra data directory:
//  - doesn't exist already
//  - has an mtime before that of this file
//  - is empty
// ...extract it again
if(!file_exists($paths->extra_data_directory) || 
	filemtime(__FILE__) > filemtime($paths->extra_data_directory)
	|| is_directory_empty($paths->extra_data_directory)) {
	
	$error_message_help = "<p>Have you checked that PHP has write access to the directory that <code>index.php</code> is located in (and all it's contents and subdirectories)? Try <code>sudo chown USERNAME:USERNAME -R path/to/directory</code> and <code>sudo chmod -R 0644 path/to/directory; sudo chmod -R +X path/too/directory</code>, where <code>USERNAME</code> is the username that the PHP process is running under.</p>";
	
	if(file_exists($paths->extra_data_directory))
		delete_recursive($paths->extra_data_directory, false);
	else {
		if(!mkdir($paths->extra_data_directory, 0700)) {
			http_response_code(503);
			exit(page_renderer::render_minimal("Unpacking error - $settings->sitename", "<p>Oops! It looks like $settings->sitename couldn't create the extra data directory to unpack additional files to.</p>$error_message_help"));
		}	
	}
		
	if(!touch($paths->extra_data_directory)) {
		http_response_code(503);
		exit(page_renderer::render_minimal("Unpacking error - $settings->sitename", "<p>Oops! It looks like $settings->sitename isn't able to change the last modified time of the extra data directory.</p>$error_message_help"));
	}
	
	$temp_filename = tempnam(sys_get_temp_dir(), "PeppermintExtract");
	$temp_file = fopen($temp_filename, "wb+");
	if($temp_file === false) {
		http_response_code(503);
		exit(page_renderer::render_minimal("Unpacking error - $settings->sitename", "<p>Oops! $settings->sitename wasn't able to create a new temporary file with <code>tempnam()</code>. Perhaps your server is mis-configured?</p>"));
	}
	$source = fopen(__FILE__, "r");
	if($source === false) {
		http_response_code(503);
		exit(page_renderer::render_minimal("Unpacking error - $settings->sitename", "<p>Oops! $settings->sitename wasn't able to open itself (i.e. <code>index.php</code>) for reading. $error_message_help</p>"));
	}
	
	fseek($source, __COMPILER_HALT_OFFSET__);
	stream_copy_to_stream($source, $temp_file);
	fclose($temp_file);
	
	$extractor = new ZipArchive();
	if(!class_exists("ZipArchive") || !($extractor instanceof ZipArchive)) {
		if(file_exists($paths->extra_data_directory))
			delete_recursive($paths->extra_data_directory);
		exit(page_renderer::render_minimal("Unpacking error - $settings->sitename", "<p>Oops! $settings->sitename wasn't able to unpack itself because the ZipArchive doesn't exist or is faulty. Please install the PHP zip extension (on apt-based systems it's the <code>php-zip</code> package) and then try again later. You can check that it's installed by inspecting the output of <code>php -m</code>, or running the <a href='https://www.php.net/manual/en/function.phpinfo.php'><code>phpinfo()</code> command</a>."));
	}
	$extractor->open($temp_filename);
	$extractor->extractTo($paths->extra_data_directory);
	$extractor->close();
	
	unlink($temp_filename);
	
	unset($error_message_help);
}

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


if(!is_cli()) session_start();
// Make sure that the login cookie lasts beyond the end of the user's session
send_cookie(session_name(), session_id(), time() + $settings->sessionlifetime);
///////// Login System /////////
// Clear expired sessions
if(isset($_SESSION[$settings->sessionprefix . "-expiretime"]) and
   $_SESSION[$settings->sessionprefix . "-expiretime"] < time())
{
	// Clear the session variables
	$_SESSION = [];
	session_destroy();
}

if(isset($_SESSION[$settings->sessionprefix . "-user"]) and
  isset($_SESSION[$settings->sessionprefix . "-pass"]))
{
	// Grab the session variables
	$env->user = $_SESSION[$settings->sessionprefix . "-user"];
	
	// The user is logged in
	$env->is_logged_in = true;
	$env->user_data = $settings->users->{$env->user};
	
}

// Check to see if the currently logged in user is an admin
$env->is_admin = false;
if($env->is_logged_in) {
	foreach($settings->admins as $admin_username){
		if($admin_username == $env->user) {
			$env->is_admin = true;
			break;
		}
	}
}
/////// Login System End ///////

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */



////////////////////
// APIDoc strings //
////////////////////
/**
 * @apiDefine Admin	Only the wiki administrator may use this call.
 */
/**
 * @apiDefine Moderator	Only users loggged with a moderator account may use this call.
 */
/**
 * @apiDefine User		Only users loggged in may use this call.
 */
/**
 * @apiDefine Anonymous	Anybody may use this call.
 */
/**
 * @apiDefine	UserNotLoggedInError
 * @apiError	UserNotLoggedInError	You didn't log in before sending this request.
 */
/**
* @apiDefine	UserNotModeratorError
* @apiError	UserNotModeratorError	You weren't loggged in as a moderator before sending this request.
*/
/**
* @apiDefine	PageParameter
* @apiParam	{string}	page	The page to operate on.
*/
////////////////////

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


/*
 * Sort out the pageindex. Create it if it doesn't exist, and load + parse it
 * if it does.
 */
if(!file_exists($paths->pageindex))
{
	$glob_str = $env->storage_prefix . "*.md";
	$existingpages = glob_recursive($glob_str);
	$existingpages_count = count($existingpages);
	// Debug statements. Uncomment when debugging the pageindex regenerator.
	// var_dump($env->storage_prefix);
	// var_dump($glob_str);
	// var_dump($existingpages);
	$pageindex = new stdClass();
	// We use a for loop here because foreach doesn't loop over new values inserted
	// while we were looping
	for($i = 0; $i < $existingpages_count; $i++)
	{
		$pagefilename = $existingpages[$i];
		
		// Create a new entry
		$newentry = new stdClass();
		$newentry->filename = mb_substr( // Store the filename, whilst trimming the storage prefix
			$pagefilename,
			mb_strlen(preg_replace("/^\.\//iu", "", $env->storage_prefix)) // glob_recursive trim the ./ from returned filenames , so we need to as well
		);
		// Remove the `./` from the beginning if it's still hanging around
		if(mb_substr($newentry->filename, 0, 2) == "./")
			$newentry->filename = mb_substr($newentry->filename, 2);
		$newentry->size = filesize($pagefilename); // Store the page size
		$newentry->lastmodified = filemtime($pagefilename); // Store the date last modified
		// Todo find a way to keep the last editor independent of the page index
		$newentry->lasteditor = "unknown"; // Set the editor to "unknown"
		
		// Extract the name of the (sub)page without the ".md"
		$pagekey = filepath_to_pagename($newentry->filename);
		error_log("pagename '$newentry->filename' → filepath '$pagekey'");
		
		if(file_exists($env->storage_prefix . $pagekey) && // If it exists...
			!is_dir($env->storage_prefix . $pagekey)) // ...and isn't a directory
		{
			// This page (potentially) has an associated file!
			// Let's investigate.
			
			// Blindly add the file to the pageindex for now.
			// Future We might want to do a security check on the file later on.
			// File a bug if you think we should do this.
			$newentry->uploadedfile = true; // Yes this page does have an uploaded file associated with it
			$newentry->uploadedfilepath = $pagekey; // It's stored here
			
			// Work out what kind of file it really is
			$mimechecker = finfo_open(FILEINFO_MIME_TYPE);
			$newentry->uploadedfilemime = finfo_file($mimechecker, $env->storage_prefix . $pagekey);
		}
		
		// Debug statements. Uncomment when debugging the pageindex regenerator.
		// echo("pagekey: ");
		// var_dump($pagekey);
		// echo("newentry: ");
		// var_dump($newentry);
		
		// Subpage parent checker
		if(strpos($pagekey, "/") !== false)
		{
			// We have a sub page people
			// Work out what our direct parent's key must be in order to check to
			// make sure that it actually exists. If it doesn't, then we need to
			// create it.
			$subpage_parent_key = substr($pagekey, 0, strrpos($pagekey, "/"));
			$subpage_parent_filename = "$env->storage_prefix$subpage_parent_key.md";
			if(array_search($subpage_parent_filename, $existingpages) === false)
			{
				// Our parent page doesn't actually exist - create it
				touch($subpage_parent_filename, 0);
				// Furthermore, we should add this page to the list of existing pages
				// in order for it to be indexed
				$existingpages[] = $subpage_parent_filename;
			}
		}
		
		// If the initial revision doesn't exist on disk, create it (if it does, then we handle that later)
		if(function_exists("history_add_revision") && !file_exists("{$pagefilename}.r0")) { // Can't use module_exists - too early
			copy($pagefilename, "{$pagefilename}.r0");
			$newentry->history = [ (object) [
				"type" => "edit",
				"rid" => 0,
				"timestamp" => $newentry->lastmodified,
				"filename" => "{$pagefilename}.r0",
				"newsize" => $newentry->size,
				"sizediff" => $newentry->size,
				"editor" => "unknown"
			] ];
		}

		// Store the new entry in the new page index
		$pageindex->$pagekey = $newentry;
	}
	
	if(function_exists("history_add_revision")) {
		$history_revs = glob_recursive($env->storage_prefix . "*.r*");
		// It's very important that we read the history revisions in the right order and that we don't skip any
		usort($history_revs, function($a, $b) {
			preg_match("/[0-9]+$/", $a, $revid_a);
			$revid_a = intval($revid_a[0]);
			preg_match("/[0-9]+$/", $b, $revid_b);
			$revid_b = intval($revid_b[0]);
			return $revid_a - $revid_b;
		});
		// We can guarantee that the direcotry separator is present on the end - it's added explicitly earlier
		$strlen_storageprefix = strlen($env->storage_prefix);
		foreach($history_revs as $filename) {
			preg_match("/[0-9]+$/", $filename, $revid);
			error_log("raw revid | ".var_export($revid, true));
			if(count($revid) === 0) continue;
			$revid = intval($revid[0]);
			
			$pagename = filepath_to_pagename($filename);
			$filepath_stripped = substr($filename, $strlen_storageprefix);
			
			if(!isset($pageindex->$pagename->history))
				$pageindex->$pagename->history = [];
			
			if(isset($pageindex->$pagename->history[$revid]))
				continue;
			
			error_log("pagename: $pagename, revid: $revid, pageindex entry: ".var_export($pageindex->$pagename, true));
			$newsize = filesize($filename);
			$prevsize = 0;
			if($revid > 0 && isset($pageindex->$pagename->history[$revid - 1])) {
				$prevsize = filesize(end($pageindex->$pagename->history)->filename);
			}
			$pageindex->$pagename->history[$revid] = (object) [
				"type" => "edit",
				"rid" => $revid,
				"timestamp" => filemtime($filename),
				"filename" => $filepath_stripped,
				"newsize" => $newsize,
				"sizediff" => $newsize - $prevsize,
				"editor" => "unknown"
			];
		}
	}
	
	save_pageindex();
	unset($existingpages);
}
else
{
	$pageindex_read_start = microtime(true);
	$pageindex = json_decode(file_get_contents($paths->pageindex));
	$env->perfdata->pageindex_decode_time = round((microtime(true) - $pageindex_read_start)*1000, 3);
	header("x-pageindex-decode-time: " . $env->perfdata->pageindex_decode_time . "ms");
	unset($pageindex_read_start);
}

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


//////////////////////////
///// Page id system /////
//////////////////////////
if(!file_exists($paths->idindex))
	file_put_contents($paths->idindex, "{}");
$idindex_decode_start = microtime(true);
$idindex = json_decode(file_get_contents($paths->idindex));
$env->perfdata->idindex_decode_time = round((microtime(true) - $idindex_decode_start)*1000, 3);
/**
 * Provides an interface to interact with page ids.
 * @package core
 */
class ids
{
	/**
	 * Gets the page id associated with the given page name.
	 * If it doesn't exist in the id index, it will be added.
	 * @package core
	 * @param	string	$pagename	The name of the page to fetch the id for.
	 * @return	int		The id for the specified page name.
	 */
	public static function getid($pagename)
	{
		global $idindex;
		
		$pagename_norm = Normalizer::normalize($pagename, Normalizer::FORM_C);
		foreach ($idindex as $id => $entry)
		{
			// We don't need to normalise here because we normralise when assigning ids
			if($entry == $pagename_norm)
				return $id;
		}
		
		// This pagename doesn't have an id - assign it one quick!
		return self::assign($pagename);
	}

	/**
	 * Gets the page name associated with the given page id.
	 * Be warned that if the id index is cleared (e.g. when the search index is
	 * rebuilt from scratch), the id associated with a page name may change!
	 * @package core
	 * @param	int		$id		The id to fetch the page name for.
	 * @return	string	The page name currently associated with the specified id.
	 */
	public static function getpagename($id)
	{
		global $idindex;

		if(!isset($idindex->$id))
			return false;
		else
			return $idindex->$id;
	}
	
	/**
	 * Moves a page in the id index from $oldpagename to $newpagename.
	 * Note that this function doesn't perform any special checks to make sure
	 * that the destination name doesn't already exist.
	 * @package core
	 * @param	string	$oldpagename	The old page name to move.
	 * @param	string	$newpagename	The new page name to move the old page name to.
	 */
	public static function movepagename($oldpagename, $newpagename)
	{
		global $idindex, $paths;
		
		$pageid = self::getid(Normalizer::normalize($oldpagename, Normalizer::FORM_C));
		$idindex->$pageid = Normalizer::normalize($newpagename, Normalizer::FORM_C);
		
		file_put_contents($paths->idindex, json_encode($idindex));
	}
	
	/**
	 * Removes the given page name from the id index.
	 * Note that this function doesn't handle multiple entries with the same
	 * name. Also note that it may get re-added during a search reindex if the
	 * page still exists.
	 * @package core
	 * @param	string	$pagename	The page name to delete from the id index.
	 */
	public static function deletepagename($pagename)
	{
		global $idindex, $paths;
		
		// Get the id of the specified page
		$pageid = self::getid($pagename);
		// Remove it from the pageindex
		unset($idindex->$pageid);
		// Save the id index
		file_put_contents($paths->idindex, json_encode($idindex));
	}
	
	/**
	 * Clears the id index completely.
	 * Will break the inverted search index! Make sure you rebuild the search
	 * index (if the search module is installed, of course) if you want search
	 * to still work. Of course, note that will re-add all the pages to the id
	 * index.
	 * @package core
	 */
	public static function clear()
	{
		global $paths, $idindex;
		// Delete the old id index
		unlink($paths->idindex);
		// Create the new id index
		file_put_contents($paths->idindex, "{}");
		// Reset the in-memory id index
		$idindex = new stdClass();
	}

	/**
	 * Assigns an id to a pagename. Doesn't check to make sure that
	 * pagename doesn't already exist in the id index.
	 * @package core
	 * @param	string	$pagename	The page name to assign an id to.
	 * @return	int					The id assigned to the specified page name.
	 */
	protected static function assign($pagename)
	{
		global $idindex, $paths;
		
		$pagename = Normalizer::normalize($pagename, Normalizer::FORM_C);

		$nextid = count(array_keys(get_object_vars($idindex)));
		// Increment the generated id until it's unique
		while(isset($idindex->nextid))
			$nextid++;
		
		// Update the id index
		$idindex->$nextid = $pagename;

		// Save the id index
		file_put_contents($paths->idindex, json_encode($idindex));

		return $nextid;
	}
}
//////////////////////////
//////////////////////////

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

///////////////////////////////////////////////////////////////////////////////
////////////////////// Security and Consistency Measures //////////////////////
///////////////////////////////////////////////////////////////////////////////

// Work around an Opera + Syntaxtic bug where there is no margin at the left
// hand side if there isn't a query string when accessing a .php file.
if(!is_cli() && !isset($_GET["action"]) && !isset($_GET["page"]) && basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) == "index.php")
{
	http_response_code(302);
	header("location: " . dirname(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
	exit();
}

// Make sure that the action is set
if(empty($_GET["action"]))
	$_GET["action"] = $settings->defaultaction;
// Make sure that the page is set
if(empty($_GET["page"]) or strlen($_GET["page"]) === 0)
	$_GET["page"] = $settings->defaultpage;

// Redirect the user to the safe version of the path if they entered an unsafe character
if(makepathsafe($_GET["page"]) !== $_GET["page"])
{
	http_response_code(301);
	header("location: index.php?action=" . rawurlencode($_GET["action"]) . "&page=" . makepathsafe($_GET["page"]));
	header("x-requested-page: " . $_GET["page"]);
	header("x-actual-page: " . makepathsafe($_GET["page"]));
	exit();
}


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


/**
 * Renders the HTML page that is sent to the client.
 * @package core
 */
class page_renderer
{
	/**
	 * The root HTML template that all pages are built from.
	 * @var string
	 * @package core
	 */
	public static $html_template = "<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<title>{title}</title>
		<meta name='viewport' content='width=device-width, initial-scale=1' />
		<meta name='generator' content='Pepperminty Wiki v0.24' />
		<meta name='application-name' content='Pepperminty Wiki v0.24' />
		<link rel='shortcut-icon' href='{favicon-url}' />
		<link rel='icon' href='{favicon-url}' />
		{header-html}
	</head>
	<body>
		{body}
		<!-- Took {generation-time-taken}ms to generate -->
	</body>
</html>
";
	/**
	 * The main content template that is used to render normal wiki pages.
	 * @var string
	 * @package core
	 */
	public static $main_content_template = "{navigation-bar}
		<h1 class='sitename'>{sitename}</h1>
		<main>
		{content}
		</main>
		{extra}
		<footer>
			<p>{footer-message}</p>
			<p>Powered by Pepperminty Wiki v0.24, which was built by <a href='//starbeamrainbowlabs.com/'>Starbeamrainbowlabs</a>. Send bugs to 'bugs at starbeamrainbowlabs dot com' or <a href='//github.com/sbrl/Pepperminty-Wiki' title='Github Issue Tracker'>open an issue</a>.</p>
			<p>Your local friendly moderators are {admins-name-list}.</p>
			<p>This wiki is managed by {admin-details}.</p>
		</footer>
		{navigation-bar-bottom}
		{all-pages-datalist}";
	/**
	 * A specially minified content template that doesn't include the navbar and
	 * other elements not suitable for printing.
	 * @var string
	 * @package core
	 */
	public static $minimal_content_template = "<main class='printable'>{content}</main>
		<footer class='printable'>
			<hr class='footerdivider' />
			<p><em>From {sitename}, which is managed by {admin-details-name}.</em></p>
			<p>{footer-message}</p>
			<p><em>Timed at {generation-date}</em></p>
			<p><em>Powered by Pepperminty Wiki v0.24.</em></p>
		</footer>";
	
	/**
	 * An array of items indicating the resources to ask the web server to push
	 * down to the client with HTTP/2.0 server push.
	 * Format: [ [type, path], [type, path], .... ]
	 * @var array[]
	 */
	protected static $http2_push_items = [];
	
	
	/**
	 * A string of extrar HTML that should be included at the bottom of the page <head>.
	 * @var string
	 */
	private static $extraHeaderHTML = "";
	
	/**
	 * The javascript snippets that will be included in the page.
	 * @var string[]
	 * @package core
	 */
	private static $jsSnippets = [];
	/**
	 * The urls of the external javascript files that should be referenced
	 * by the page.
	 * @var string[]
	 * @package core
	 */
	private static $jsLinks = [];
	
	/**
	 * The navigation bar divider.
	 * @package core
	 * @var string
	 */
	public static $nav_divider = "<span class='nav-divider inflexible'> | </span>";
	
	
	/**
	 * An array of functions that have been registered to process the
	 * find / replace array before the page is rendered. Note that the function
	 * should take a *reference* to an array as its only argument.
	 * @var array
	 * @package core
	 */
	protected static $part_processors = [];

	/**
	 * Registers a function as a part post processor.
	 * This function's use is more complicated to explain. Pepperminty Wiki
	 * renders pages with a very simple templating system. For example, in the
	 * template a page's content is denoted by `{content}`. A function
	 * registered here will be passed all the components of a page _just_
	 * before they are dropped into the template. Note that the function you
	 * pass in here should take a *reference* to the components, as the return
	 * value of the function passed is discarded.
	 * @package core
	 * @param  callable $function The part preprocessor to register.
	 */
	public static function register_part_preprocessor($function) {
		global $settings;

		// Make sure that the function we are about to register is valid
		if(!is_callable($function))
		{
			http_response_code(500);
			exit(page_renderer::render("$settings->sitename - Module Error", "<p>$settings->sitename has got a misbehaving module installed that tried to register an invalid HTML handler with the page renderer. Please contact $settings->sitename's administrator {$settings->admindetails_name} at ".hide_email($settings->admindetails_email)."."));
		}

		self::$part_processors[] = $function;

		return true;
	}
	
	/**
	 * Renders a HTML page with the content specified.
	 * @package core
	 * @param	string	$title			The title of the page.
	 * @param	string	$content		The (HTML) content of the page.
	 * @param	bool	$body_template	The HTML content template to use.
	 * @return	string	The rendered HTML, ready to send to the client :-)
	 */
	public static function render($title, $content, $body_template = false)
	{
		global $settings, $env, $start_time, $version;
		
		// Hrm, we can't seem to get this working
		// This example URL works: https://httpbin.org/response-headers?Server=httpbin&Content-Type=text%2Fplain%3B+charset%3DUTF-8&Server-Timing=sql-1%3Bdesc%3D%22MySQL%20lookup%20Server%22%3Bdur%3D100%2Csql-2%3Bdur%3D900%3Bdesc%3D%22MySQL%20shard%20Server%20%231%22%2Cfs%3Bdur%3D600%3Bdesc%3D%22FileSystem%22%2Ccache%3Bdur%3D300%3Bdesc%3D%22Cache%22%2Cother%3Bdur%3D200%3Bdesc%3D%22Database%20Write%22%2Cother%3Bdur%3D110%3Bdesc%3D%22Database%20Read%22%2Ccpu%3Bdur%3D1230%3Bdesc%3D%22Total%20CPU%22
		// ..... but setting headers here doesn't (though we haven't tried sending an identical header to the above example yet)
		// header("Server-Timing: foo;desc=\"Test\";dur=123");
		// header("Server-Timing: ".metrics2servertiming($env->perfdata));

		if($body_template === false)
			$body_template = self::$main_content_template;

		if(strlen($settings->logo_url) > 0) {
			// A logo url has been specified
			$logo_html = "<img aria-hidden='true' class='logo" . (isset($_GET["printable"]) ? " small" : "") . "' src='$settings->logo_url' />";
			switch($settings->logo_position) {
				case "left":
					$logo_html = "$logo_html $settings->sitename";
					break;
				case "right":
					$logo_html .= " $settings->sitename";
					break;
				default:
					throw new Exception("Invalid logo_position '$settings->logo_position'. Valid values are either \"left\" or \"right\" and are case sensitive.");
			}
		}
		
		// Push the logo via HTTP/2.0 if possible
		if($settings->favicon[0] === "/") self::$http2_push_items[] = ["image", $settings->favicon];
		
		$parts = [
			"{body}" => $body_template,

			"{sitename}" => $logo_html,
			"v0.24" => $version,
			"{favicon-url}" => $settings->favicon,
			"{header-html}" => self::get_header_html(),

			"{navigation-bar}" => self::render_navigation_bar($settings->nav_links, $settings->nav_links_extra, "top"),
			"{navigation-bar-bottom}" => self::render_navigation_bar($settings->nav_links_bottom, [], "bottom"),

			"{admin-details}" => hide_email(
				$settings->admindetails_email,
				htmlentities($settings->admindetails_name)
			),
			"{admin-details-name}" => $settings->admindetails_name,

			"{admins-name-list}" => implode(", ", array_map(function($username) { return page_renderer::render_username($username); }, $settings->admins)),

			"{generation-date}" => date("l jS \of F Y \a\\t h:ia T"),

			"{all-pages-datalist}" => self::generate_all_pages_datalist(),

			"{footer-message}" => $settings->footer_message,

			/// Secondary Parts ///

			"{content}" => $content,
			"{extra}" => "",
			"{title}" => htmlentities($title),
		];

		// Pass the parts through the part processors
		foreach(self::$part_processors as $function) {
			$function($parts);
		}

		$result = self::$html_template;

		$result = str_replace(array_keys($parts), array_values($parts), $result);

		$result = str_replace("{generation-time-taken}", round((microtime(true) - $start_time)*1000, 2), $result);
		// Send the HTTP/2.0 server push indicators if possible - but not if we're sending a redirect page
		if(!headers_sent() && (http_response_code() < 300 || http_response_code() >= 400)) self::send_server_push_indicators();
		return $result;
	}
	/**
	 * Renders a normal HTML page.
	 * @package core
	 * @param  string $title   The title of the page.
	 * @param  string $content The content of the page.
	 * @return string          The rendered page.
	 */
	public static function render_main($title, $content) {
		return self::render($title, $content, self::$main_content_template);
	}
	/**
	 * Renders a minimal HTML page. Useful for printable pages.
	 * @package core
	 * @param  string $title   The title of the page.
	 * @param  string $content The content of the page.
	 * @return string          The rendered page.
	 */
	public static function render_minimal($title, $content) {
		return self::render($title, $content, self::$minimal_content_template);
	}
	
	/**
	 * Sends the currently registered HTTP2 server push items to the client.
	 * @return int|false	The number of resource hints included in the link: header, or false if server pushing is disabled.
	 */
	public static function send_server_push_indicators() {
		global $settings;
		if(!$settings->http2_server_push)
			return false;
		
		// Render the preload directives
		$link_header_parts = [];
		foreach(self::$http2_push_items as $push_item)
			$link_header_parts[] = "<{$push_item[1]}>; rel=preload; as={$push_item[0]}";
		
		// Send them in a link: header
		if(!empty($link_header_parts))
			header("link: " . implode(", ", $link_header_parts));
		
		return count(self::$http2_push_items);
	}
	
	/**
	 * Renders the header HTML.
	 * @package core
	 * @return string The rendered HTML that goes in the header.
	 */
	public static function get_header_html()
	{
		global $settings;
		$result = self::$extraHeaderHTML;
		$result .= self::get_css_as_html();
		$result .= self::_get_js();
		
		if(!empty($settings->theme_colour))
			$result .= "\t\t<meta name='theme-color' content='$settings->theme_colour' />\n";
		
		// We can't use module_exists here because sometimes global $modules
		// hasn't populated yet when we get called O.o
		if(class_exists("search"))
			$result .= "\t\t<link rel='search' type='application/opensearchdescription+xml' href='?action=opensearch-description' title='$settings->sitename Search' />\n";
		
		if(!empty($settings->enable_math_rendering)) {
			$result .= "<script type='text/x-mathjax-config'>
		MathJax.Hub.Config({
			tex2jax: {
				inlineMath: [ ['$','$'], ['\\\\(','\\\\)'] ],
				processEscapes: true,
				skipTags: ['script','noscript','style','textarea','pre','code']
			}
		});
	</script>";
		}
		
		return $result;
	}
	/**
	 * Figures out whether $settings->css is a url, or a string of css.
	 * A url is something starting with "protocol://" or simply a "/".
	 * Before v0.20, this method took no arguments and checked $settings->css directly.
	 * @since	0.20.0
	 * @param	string	$str	The CSS string to check.
	 * @return	bool	True if it's a url - false if we assume it's a string of css.
	 */
	public static function is_css_url($str) {
		global $settings;
		return preg_match("/^[^\/]*\/\/|^\/[^\*]/", $str);
	}
	/**
	 * Renders all the CSS as HTML.
	 * @package core
	 * @return string The css as HTML, ready to be included in the HTML header.
	 */
	public static function get_css_as_html()
	{
		global $settings, $defaultCSS;
		
		$result = "";
		$css = "";
		if(self::is_css_url($settings->css)) {
			if($settings->css[0] === "/") // Push it if it's a relative resource
				self::add_server_push_indicator("style", $settings->css);
			$result .= "<link rel='stylesheet' href='$settings->css' />\n";
		} else {
			$css .= $settings->css == "auto" ? $defaultCSS : $settings->css;
			
			if(!empty($settings->optimize_pages))
				$css = minify_css($css);
			
		}
		
		if(!empty($settings->css_custom)) {
			if(self::is_css_url($settings->css_custom)) {
				if($settings->css_custom[0] === "/") // Push it if it's a relative resource
					self::add_server_push_indicator("style", $settings->css);
				$result .= "<link rel='stylesheet' href='$settings->css_custom' />\n";
			}
			if(!empty(trim($settings->css_custom))) {
				$css .= "\n/*** Custom CSS ***/\n";
				$css .= !empty($settings->optimize_pages) ? minify_css($settings->css_custom) : $settings->css_custom;
				$css .= "\n/******************/";
			}
		}
		$result .= "<style>\n$css\n</style>\n";
		
		return $result;
	}
	
	
	/**
	 * Adds the specified url to a javascript file as a reference to the page.
	 * @package core
	 * @param string $scriptUrl The url of the javascript file to reference.
	 */
	public static function add_js_link(string $scriptUrl) {
		static::$jsLinks[] = $scriptUrl;
	}
	/**
	 * Adds a javascript snippet to the page.
	 * @package core
	 * @param string $script The snippet of javascript to add.
	 */
	public static function add_js_snippet(string $script) {
		static::$jsSnippets[] = $script;
	}
	/**
	 * Renders the included javascript header for inclusion in the final
	 * rendered page.
	 * @package core
	 * @return	string	The rendered javascript ready for inclusion in the page.
	 */
	private static function _get_js() {
		$result = "<!-- Javascript -->\n";
		foreach(static::$jsSnippets as $snippet)
			$result .= "<script defer>\n$snippet\n</script>\n";
		foreach(static::$jsLinks as $link) {
			// Push it via HTTP/2.0 if it's relative
			if($link[0] === "/") self::add_server_push_indicator("script", $link);
			$result .= "<script src='" . $link . "' defer></script>\n";
		}
		return $result;
	}
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	/**
	 * Adds a string of HTML to the header of the rendered page.
	 * @param string $html The string of HTML to add.
	 */
	public static function add_header_html($html) {
		self::$extraHeaderHTML .= $html;
	}
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	/**
	 * Adds a resource to the list of items to indicate that the web server should send via HTTP/2.0 Server Push.
	 * Note: Only specify static files here, as you might end up with strange (and possibly dangerous) results!
	 * @param string $type The resource type. See https://fetch.spec.whatwg.org/#concept-request-destination for more information.
	 * @param string $path The *relative url path* to the resource.
	 */
	public static function add_server_push_indicator($type, $path) {
		self::$http2_push_items[] = [ $type, $path ];
	}
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	
	/**
	 * Renders a navigation bar from an array of links. See
	 * $settings->nav_links for format information.
	 * @package core
	 * @param array	$nav_links			The links to add to the navigation bar.
	 * @param array	$nav_links_extra	The extra nav links to add to
	 *                               	the "More..." menu.
	 * @param string $class				The class(es) to assign to the rendered
	 * 									navigation bar.
	 */
	public static function render_navigation_bar($nav_links, $nav_links_extra, $class = "") {
		global $settings, $env;
		
		$mega_menu = false;
		if(is_object($nav_links)) {
			$mega_menu = true;
			$class = trim("$class mega-menu");
			$links_list = [];
			$keys = array_keys(get_object_vars($nav_links));
			foreach($keys as $key) {
				$links_list[] =  "category\0$key";
				$links_list = array_merge(
					$links_list,
					$nav_links->$key
				);
			}
			$nav_links = $links_list;
		}
		
		$result = "<nav class='$class'>\n";
		$is_first_category = true;
		// Loop over all the navigation links
		foreach($nav_links as $item) {
			if(!is_string($item)) {
				// Output the item as a link to a url
				$result .= "<span><a href='" . str_replace(
					[ "{page}", "&" ],
					[ rawurlencode($env->page), "&amp;" ],
					$item[1]
				) . "'>$item[0]</a></span>";
				continue;
			}
			
			// Extract the item key - a null character can be used to separate extra data from an item type
			$item_key = $item;
			if(strpos($item_key, "\0") !== false)
				$item_key = substr($item_key, 0, strpos($item_key, "\0"));
			
			// The item is a string
			switch($item_key) {
				//keywords
				case "user-status": // Renders the user status box
					if($env->is_logged_in) {
						$result .= "<span class='inflexible logged-in" . ($env->is_logged_in ? " moderator" : " normal-user") . "'>";
						if(module_exists("feature-user-preferences")) {
							$result .= "<a href='?action=user-preferences' aria-label='Change user preferences'>$settings->user_preferences_button_text</a>";
						}
						$result .= self::render_username($env->user);
						$result .= " <small>(<a href='index.php?action=logout'>Logout</a>)</small>";
						$result .= "</span>";
						//$result .= page_renderer::$nav_divider;
					}
					else {
						$returnto_url = $env->action !== "logout" ? $_SERVER["REQUEST_URI"] : "?action=view&page=" . rawurlencode($settings->defaultpage);
						$result .= "<span class='not-logged-in'><a href='index.php?action=login&returnto=" . rawurlencode($returnto_url) . "' rel='nofollow'>Login</a></span>";
					}
					break;

				case "search": // Renders the search bar
					$result .= "<span class='inflexible'><form method='get' action='index.php' style='display: inline;'><input type='search' name='page' list='allpages' placeholder='&#x1f50e; Type a page name here and hit enter' /><input type='hidden' name='search-redirect' value='true' /></form></span>";
					break;

				case "divider": // Renders a divider
					$result .= page_renderer::$nav_divider;
					break;

				case "menu": // Renders the "More..." menu
					$result .= "<span class='inflexible nav-more'><label for='more-menu-toggler'>More...</label>
<input type='checkbox' class='off-screen' id='more-menu-toggler' />";
					$result .= page_renderer::render_navigation_bar($nav_links_extra, [], "nav-more-menu");
					$result .= "</span>";
					break;
				
				case "category": // Renders a category header
					if(!$is_first_category) $result .= "</span>";
					$result .= "<span class='category'><strong>" . substr($item, 9) . "</strong>";
					$is_first_category = false;
					break;

				// It isn't a keyword, so just output it directly
				default:
					$result .= "<span>$item</span>";
			}
		}
		if($mega_menu) $result .= "</span>";

		$result .= "</nav>";
		return $result;
	}
	/**
	 * Renders a username for inclusion in a page.
	 * @package core
	 * @param  string $name The username to render.
	 * @return string       The username rendered in HTML.
	 */
	public static function render_username($name) {
		global $settings;
		$result = "";
		$result .= "<a href='?page=" . rawurlencode(get_user_pagename($name)) . "'>";
		if($settings->avatars_show)
			$result .= "<img class='avatar' aria-hidden='true' src='?action=avatar&user=" . rawurlencode($name) . "&size=$settings->avatars_size' /> ";
		if(in_array($name, $settings->admins))
			$result .= $settings->admindisplaychar;
		$result .= htmlentities($name);
		$result .= "</a>";

		return $result;
	}
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	/**
	 * Renders the datalist for the search box as HTML.
	 * @package core
	 * @return string The search box datalist as HTML.
	 */
	public static function generate_all_pages_datalist() {
		global $settings, $pageindex;
		
		$result = "<datalist id='allpages'>\n";
		
		// If dynamic page sugggestions are enabled, then we should send a loading message instead.
		if($settings->dynamic_page_suggestion_count > 0) {
			$result .= "<option value='Loading suggestions...' />";
		} else {
			$arrayPageIndex = get_object_vars($pageindex);
			$sorter = new Collator("");
			uksort($arrayPageIndex, function($a, $b) use($sorter) : int {
				return $sorter->compare($a, $b);
			});
			
			foreach($arrayPageIndex as $pagename => $pagedetails) {
				$escapedPageName = str_replace('"', '&quot;', $pagename);
				$result .= "\t\t\t<option value=\"$escapedPageName\" />\n";
			}
		}
		$result .= "\t\t</datalist>";

		return $result;
	}
}

// HTTP/2.0 Server Push static items
foreach($settings->http2_server_push_items as $push_item) {
	page_renderer::add_server_push_indicator($push_item[0], $push_item[1]);
}

// Math rendering support
if(!empty($settings->enable_math_rendering))
{
	page_renderer::add_js_link("https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML");
}
// alt+enter support in the search box
page_renderer::add_js_snippet('// Alt + Enter support in the top search box
window.addEventListener("load", function(event) {
	let search_box = document.querySelector("input[type=search]"),
		alt_pressed = false;
	document.addEventListener("keyup", (event) => {
		if(event.keyCode !== 18) return;
		alt_pressed = false;
		console.info("[search box/alt-tracker] alt released");
	});
	document.addEventListener("keydown", (event) => {
		if(event.keyCode !== 18) return;
		alt_pressed = true;
		console.info("[search box/alt-tracker] alt pressed");
	});
	
	search_box.form.addEventListener("submit", (event) => {
		if(!alt_pressed) {
			console.log("[search box/form] Alt wasn\'t pressed");
			event.target.removeAttribute("target");
			return;
		}
		
		console.log("[search box/form] Fiddling target");
		
		event.target.setAttribute("target", "_blank");
		setTimeout(() => {
			alt_pressed = false;
		}, 100);
	});
});
');

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


/// Finish setting up the environment object ///
$env->page = $_GET["page"] ?? $_POST["page"];
$env->page_safe = htmlentities($env->page);
if(isset($_GET["revision"]) and is_numeric($_GET["revision"]))
{
	// We have a revision number!
	$env->is_history_revision = true;
	$env->history->revision_number = intval($_GET["revision"]);
	
	// Make sure that the revision exists for later on
	if(!isset($pageindex->{$env->page}->history[$env->history->revision_number]))
	{
		http_response_code(404);
		exit(page_renderer::render_main("404: Revision Not Found - $env->page - $settings->sitename", "<p>Revision #{$env->history->revision_number} of $env->page doesn't appear to exist. Try viewing the <a href='?action=history&page=" . rawurlencode($env->page) . "'>list of revisions for $env->page</a>, or viewing <a href='?page=" . rawurlencode($env->page) . "'>the latest revision</a> instead.</p>"));
	}
	
	$env->history->revision_data = $pageindex->{$env->page}->history[$env->history->revision_number];
}
// Construct the page's filename
$env->page_filename = $env->storage_prefix;
if($env->is_history_revision)
	$env->page_filename .= $pageindex->{$env->page}->history[$env->history->revision_number]->filename;
else if(isset($pageindex->{$env->page}))
	$env->page_filename .= $pageindex->{$env->page}->filename;

$env->action = slugify($_GET["action"]);

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */



//////////////////////////////////////
///// Extra consistency measures /////
//////////////////////////////////////

// CHANGED: The search redirector has now been moved to below the module registration system, as it was causing a warning here

// Redirect the user to the login page if:
//  - A login is required to view this wiki
//  - The user isn't already requesting the login page
// Note we use $_GET here because $env->action isn't populated at this point
if(
    !is_cli() &&
    $settings->require_login_view === true && // If this site requires a login in order to view pages
   !$env->is_logged_in && // And the user isn't logged in
   !in_array($_GET["action"], [ "login", "checklogin", "opensearch-description", "invindex-rebuild", "stats-update" ])) // And the user isn't trying to login, or get the opensearch description, or access actions that apply their own access rules
{
	// Redirect the user to the login page
	http_response_code(307);
	header("x-login-required: yes");
	$url = "?action=login&returnto=" . rawurlencode($_SERVER["REQUEST_URI"]) . "&required=true";
	header("location: $url");
	exit(page_renderer::render("Login required - $settings->sitename", "<p>$settings->sitename requires that you login before you are able to access it.</p>
		<p><a href='$url'>Login</a>.</p>"));
}
//////////////////////////////////////
//////////////////////////////////////

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */



/*
 * ███    ███  ██████  ██████  ██    ██ ██      ███████ ███████
 * ████  ████ ██    ██ ██   ██ ██    ██ ██      ██      ██
 * ██ ████ ██ ██    ██ ██   ██ ██    ██ ██      █████   ███████
 * ██  ██  ██ ██    ██ ██   ██ ██    ██ ██      ██           ██
 * ██      ██  ██████  ██████   ██████  ███████ ███████ ███████
 */

/** A list of all the currently loaded modules. Not guaranteed to be populated until an action is executed. @var array */
$modules = [];

/**
 * Registers a module.
 * @package core
 * @param  array	$moduledata	The module data to register.
 */
function register_module($moduledata)
{
	global $modules;
	//echo("registering module\n");
	//var_dump($moduledata);
	$modules[] = $moduledata;
}

/**
 * Checks to see whether a module with the given id exists.
 * @package core
 * @param  string   $id	 The id to search for.
 * @return bool     Whether a module is currently loaded with the given id.
 */
function module_exists($id)
{
	global $modules;
	foreach($modules as $module)
	{
		if($module["id"] == $id)
			return true;
	}
	return false;
}


/*
 *  █████   ██████ ████████ ██  ██████  ███    ██ ███████
 * ██   ██ ██         ██    ██ ██    ██ ████   ██ ██
 * ███████ ██         ██    ██ ██    ██ ██ ██  ██ ███████
 * ██   ██ ██         ██    ██ ██    ██ ██  ██ ██      ██
 * ██   ██  ██████    ██    ██  ██████  ██   ████ ███████
 */

$actions = new stdClass();

/**
 * Registers a new action handler.
 * @package core
 * @param	string		$action_name	The action to register.
 * @param	callable	$func			The function to call when the specified
 * 										action is requested.
 */
function add_action($action_name, $func)
{
	global $actions;
	$actions->$action_name = $func;
}

/**
 * Figures out whether a given action is currently registered.
 * Only guaranteed to be accurate in inside an existing action function
 * @package	core
 * @param	string	$action_name	The name of the action to search for
 * @return	bool		Whether an action with the specified name exists.
 */
function has_action($action_name)
{
	global $actions;
	return !empty($actions->$action_name);
}


/*
 * ███████  █████  ██    ██ ██ ███    ██  ██████
 * ██      ██   ██ ██    ██ ██ ████   ██ ██
 * ███████ ███████ ██    ██ ██ ██ ██  ██ ██   ███
 *      ██ ██   ██  ██  ██  ██ ██  ██ ██ ██    ██
 * ███████ ██   ██   ████   ██ ██   ████  ██████
*/

$save_preprocessors = [];

/**
 * Register a new proprocessor that will be executed just before
 * an edit is saved.
 * @package core
 * @param	callable	$func	The function to register.
 */
function register_save_preprocessor($func)
{
	global $save_preprocessors;
	$save_preprocessors[] = $func;
}


/*
 * ██   ██ ███████ ██      ██████
 * ██   ██ ██      ██      ██   ██
 * ███████ █████   ██      ██████
 * ██   ██ ██      ██      ██
 * ██   ██ ███████ ███████ ██
 */

$help_sections = [];

/**
 * Adds a new help section to the help page.
 * @package core
 * @param string $index   The string to index the new section under.
 * @param string $title   The title to display above the section.
 * @param string $content The content to display.
 */
function add_help_section($index, $title, $content)
{
	global $help_sections;
	
	$help_sections[$index] = [
		"title" => $title,
		"content" => $content
	];
}

if(!empty($settings->enable_math_rendering))
	add_help_section("22-mathematical-mxpressions", "Mathematical Expressions", "<p>$settings->sitename supports rendering of mathematical expressions. Mathematical expressions can be included practically anywhere in your page. Expressions should be written in LaTeX and enclosed in dollar signs like this: <code>&#36;x^2&#36;</code>.</p>
	<p>Note that expression parsing is done on the viewer's computer with javascript (specifically MathJax) and not by $settings->sitename directly (also called client side rendering).</p>");


/*
 * ███████ ████████  █████  ████████ ███████
 * ██         ██    ██   ██    ██    ██
 * ███████    ██    ███████    ██    ███████
 *      ██    ██    ██   ██    ██         ██
 * ███████    ██    ██   ██    ██    ███████
*/

/** An array of the currently registerd statistic calculators. Not guaranteed to be populated until the requested action function is called. */
$statistic_calculators = [];

/**
 * Registers a statistic calculator against the system.
 * @package core
 * @param	array	$stat_data	The statistic object to register.
 */
function statistic_add($stat_data) {
	global $statistic_calculators;
	$statistic_calculators[$stat_data["id"]] = $stat_data;
}

/**
 * Checks whether a specified statistic has been registered.
 * @package	core
 * @param	string	$stat_id	The id of the statistic to check the existence of.
 * @return	bool		Whether the specified statistic has been registered.
 */
function has_statistic($stat_id) {
	global $statistic_calculators;
	return !empty($statistic_calculators[$stat_id]);
}

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */



$parsers = [
	"none" => [
		"parser" => function() {
			throw new Exception("No parser registered!");
		},
		"hash_generator" => function() {
			throw new Exception("No parser registered!");
		}
	]
];
/**
 * Registers a new parser.
 * @package	core
 * @param	string		$name			The name of the new parser to register.
 * @param	callable	$parser_code	The function to register as a new parser.
 * @param	callable	$hash_generator	A function that should take a single argument of the input source text, and return a unique hash for that content. The return value is used as the filename for cache entries, so should be safe to use as such.
 */
function add_parser($name, $parser_code, $hash_generator) {
	global $parsers;
	if(isset($parsers[$name]))
		throw new Exception("Can't register parser with name '$name' because a parser with that name already exists.");

	$parsers[$name] = [
		"parser" => $parser_code,
		"hash_generator" => $hash_generator
	];
}
/**
 * Parses the specified page source using the parser specified in the settings
 * into HTML.
 * The specified parser may (though it's unlikely) render it to other things.
 * @package core
 * @param	string	$source		The source to render.
 * @param	bool	$use_cache	Whether to use the on-disk cache. Has no effect if parser caching is disabled in peppermint.json, or the source string is too small.
 * @param	bool	$untrusted	Whether the source string is 'untrusted' - i.e. a user comment. Untrusted source disallows HTML and protects against XSS attacks.
 * @return	string	The source rendered to HTML.
 */
function parse_page_source($source, $untrusted = false, $use_cache = true) {
	global $settings, $paths, $parsers, $version;
	$start_time = microtime(true);
	
	if(!$settings->parser_cache || strlen($source) < $settings->parser_cache_min_size) $use_cache = false;
	
	if(!isset($parsers[$settings->parser]))
		exit(page_renderer::render_main("Parsing error - $settings->sitename", "<p>Parsing some page source data failed. This is most likely because $settings->sitename has the parser setting set incorrectly. Please contact " . hide_email($settings->admindetails_email, $settings->admindetails_name) . ", $settings->sitename's Administrator."));
	
/* Not needed atm because escaping happens when saving, not when rendering *
	if($settings->clean_raw_html)
		$source = htmlentities($source, ENT_QUOTES | ENT_HTML5);
*/
	
	$cache_id = $parsers[$settings->parser]["hash_generator"]($source);
	$cache_file = "{$paths->cache_directory}/{$cache_id}.html";
	
	$result = null;
	if($use_cache && file_exists($cache_file)) {
		$result = file_get_contents($cache_file);
		$result .= "\n<!-- cache: hit, id: $cache_id, took: " . round((microtime(true) - $start_time)*1000, 5) . "ms -->\n";
	}
	if($result == null) {
		$result = $parsers[$settings->parser]["parser"]($source, $untrusted);
		// If we should use the cache and we failed to write to it, warn the admin.
		// It's not terribible if we can't write to the cache directory (so we shouldn't stop dead & refuse service), but it's still of concern.
		if($use_cache && !file_put_contents($cache_file, $result))
			error_log("[PeppermintyWiki/$settings->sitename/parser_engine] Warning: Failed to write to cache file $cache_file.");
		
		$result .= "\n<!-- cache: " . ($use_cache ? "miss" : "n/a") . ", id: $cache_id, took: " . round((microtime(true) - $start_time)*1000, 5) . "ms -->\n";
	}
	
	return $result;
}

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


//////////////////////////////////////////////////////////////////


/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Password hashing action",
	"version" => "0.7",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a utility action (that anyone can use) called hash that hashes a given string. Useful when changing a user's password.",
	"id" => "action-hash",
	"code" => function() {
		/**
		 * @api {get} ?action=hash&string={text} Hash a password
		 * @apiName Hash
		 * @apiGroup Utility
		 * @apiPermission Anonymous
		 * 
		 * @apiParam {string}	string	The string to hash.
		 * @apiParam {bool}	raw		Whether to return the hashed password as a raw string instead of as part of an HTML page.
		 *
		 * @apiError	ParamNotFound	The string parameter was not specified.
		 */
		
		/*
		 * ██   ██  █████  ███████ ██   ██ 
		 * ██   ██ ██   ██ ██      ██   ██ 
		 * ███████ ███████ ███████ ███████ 
		 * ██   ██ ██   ██      ██ ██   ██ 
		 * ██   ██ ██   ██ ███████ ██   ██
		 */
		add_action("hash", function() {
			global $settings;
			
			if(!isset($_GET["string"])) {
				http_response_code(422);
				exit(page_renderer::render_main("Missing parameter", "<p>The <code>GET</code> parameter <code>string</code> must be specified.</p>
		<p>It is strongly recommended that you utilise this page via a private or incognito window in order to prevent your password from appearing in your browser history.</p>"));
			}
			else if(!empty($_GET["raw"])) {
				header("content-type: text/plain");
				exit(hash_password($_GET["string"]));
			}
			else {
				exit(page_renderer::render_main("Hashed string", "<p>Algorithm: <code>$settings->password_algorithm</code></p>\n<p><code>" . htmlentities($_GET["string"]) . "</code> → <code>" . hash_password($_GET["string"]) . "</code></p>"));
			}
		});
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Page protection",
	"version" => "0.2.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Exposes Pepperminty Wiki's new page protection mechanism and makes the protect button in the 'More...' menu on the top bar work.",
	"id" => "action-protect",
	"code" => function() {
		/**
		 * @api {get} ?action=protect&page={pageName} Toggle the protection of a page.
		 * @apiName Protect
		 * @apiGroup Page
		 * @apiPermission Moderator
		 * 
		 * @apiParam {string}	page	The page name to toggle the protection of.
		 */
		
		/*
		 * ██████  ██████   ██████  ████████ ███████  ██████ ████████ 
		 * ██   ██ ██   ██ ██    ██    ██    ██      ██         ██    
		 * ██████  ██████  ██    ██    ██    █████   ██         ██    
		 * ██      ██   ██ ██    ██    ██    ██      ██         ██    
		 * ██      ██   ██  ██████     ██    ███████  ██████    ██    
		 */
		add_action("protect", function() {
			global $env, $pageindex, $paths, $settings;

			// Make sure that the user is logged in as an admin / mod.
			if($env->is_admin)
			{
				// They check out ok, toggle the page's protection.
				$page = $env->page;
				
				if(!isset($pageindex->$page->protect))
				{
					$pageindex->$page->protect = true;
				}
				else if($pageindex->$page->protect === true)
				{
					$pageindex->$page->protect = false;
				}
				else if($pageindex->$page->protect === false)
				{
					$pageindex->$page->protect = true;
				}
				
				// Save the pageindex
				save_pageindex();
				
				$state = ($pageindex->$page->protect ? "enabled" : "disabled");
				$title = "Page protection $state.";
				exit(page_renderer::render_main($title, "<p>Page protection for $env->page_safe has been $state.</p><p><a href='?action=$settings->defaultaction&page=".rawurlencode($env->page)."'>Go back</a>."));
			}
			else
			{
				exit(page_renderer::render_main("Error protecting page", "<p>You are not allowed to protect pages because you are not logged in as a mod or admin. Please try logging out if you are logged in and then try logging in as an administrator.</p>"));
			}
		});
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Random Page",
	"version" => "0.3.1",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds an action called 'random' that redirects you to a random page.",
	"id" => "action-random",
	"code" => function() {
		global $settings;
		/**
		 * @api {get} ?action=random[&mode={modeName}] Redirects to a random page
		 * @apiName Random
		 * @apiGroup Page
		 * @apiPermission Anonymous
		 *
		 * @apiParam	{string}	mode	The view mode to redirect to. This parameter is basically just passed through to the direct. It works in the same way as the mode parameter on the view action does.
		 */
		
		add_action("random", function() {
			global $pageindex;
			
			$mode = slugify($_GET["mode"] ?? "");
			
			$pageNames = array_keys(get_object_vars($pageindex));
			
			// Filter out pages we shouldn't send the user to
			$pageNames = array_values(array_filter($pageNames, function($pagename) {
				global $settings, $pageindex;
				if($settings->random_page_exclude_redirects &&
					isset($pageindex->$pagename->redirect) &&
					$pageindex->$pagename->redirect === true)
					return false;
				return preg_match($settings->random_page_exclude, $pagename) === 0 ? true : false;
			}));
			
			$randomPageName = $pageNames[array_rand($pageNames)];
			
			http_response_code(307);
			$redirect_url = "?page=" . rawurlencode($randomPageName);
			if(!empty($mode)) $redirect_url .= "&mode=$mode";
			header("location: $redirect_url");
		});
		
		add_help_section("26-random-redirect", "Jumping to a random page", "<p>$settings->sitename has a function that can send you to a random page. To use it, click <a href='?action=random'>here</a>. $settings->admindetails_name ($settings->sitename's adminstrator) may have added it to one of the menus.</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Raw page source",
	"version" => "0.9.1",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a 'raw' action that shows you the raw source of a page.",
	"id" => "action-raw",
	"code" => function() {
		global $settings;
		/**
		 * @api {get} ?action=raw&page={pageName}[&typeheader={typeName}] Get the raw source code of a page
		 * @apiName RawSource
		 * @apiGroup Page
		 * @apiPermission Anonymous
		 * 
		 * @apiParam	{string}	page		The page to return the source of.
		 * @apiParam	{string}	typeheader	Optional; v0.22+. The content-type header to set on the response. If not set, defaults to text/markdown. Valid values: plaintext (returns text/plain). Does NOT change the content delivered. Useful for debugging if your browser doesn't display text returned with text/markdown.
		 *
		 * @apiSuccessExample Example response:
		 * HTTP/1.1 200 OK
		 * content-type: text/markdown
		 * content-disposition: inline
		 * x-tags: foo, bar, baz
		 *
		 * Some text here
		 */
		
		/*
		 * ██████   █████  ██     ██ 
		 * ██   ██ ██   ██ ██     ██ 
		 * ██████  ███████ ██  █  ██ 
		 * ██   ██ ██   ██ ██ ███ ██ 
		 * ██   ██ ██   ██  ███ ███  
		 */
		add_action("raw", function() {
			global $pageindex, $env;
			
			if(empty($pageindex->{$env->page})) {
				http_response_code(404);
				exit("Error: The page with the name $env->page could not be found.\n");
			}
			if(isset($_GET["typeheader"]) && $_GET["typeheader"] == "plaintext")
				header("content-type: text/plain");
			else
				header("content-type: text/markdown");
			header("content-disposition: inline");
			header("content-length: " . filesize($env->page_filename));
			header("x-tags: " . implode(", ", str_replace(
				["\n", ":"], "",
				$pageindex->{$env->page}->tags
			)));
			exit(file_get_contents($env->page_filename));
		});
		
		add_help_section("800-raw-page-content", "Viewing Raw Page Content", "<p>Although you can use the edit page to view a page's source, you can also ask $settings->sitename to send you the raw page source and nothing else. This feature is intented for those who want to automate their interaction with $settings->sitename.</p>
		<p>To use this feature, navigate to the page for which you want to see the source, and then alter the <code>action</code> parameter in the url's query string to be <code>raw</code>. If the <code>action</code> parameter doesn't exist, add it. Note that when used on an file's page this action will return the source of the description and not the file itself.</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "API status",
	"version" => "0.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Provides a basic JSON status action that provides a few useful bits of information for API consumption.",
	"id" => "api-status",
	"code" => function() {
		global $settings;
		/**
		 * @api {get} ?action=status[&minified=type]	Get the json-formatted status of this wiki
		 * @apiName Status
		 * @apiGroup Stats
		 * @apiPermission Anonymous
		 *
		 * @apiParam	{bool}	Whether or not the result should be minified JSON. Default: false
		 */
		
	 	/*
	 	 * ███████ ████████  █████  ████████ ██    ██ ███████
	 	 * ██         ██    ██   ██    ██    ██    ██ ██
	 	 * ███████    ██    ███████    ██    ██    ██ ███████
	 	 *      ██    ██    ██   ██    ██    ██    ██      ██
	 	 * ███████    ██    ██   ██    ██     ██████  ███████
	 	 */
		add_action("status", function() {
			global $version, $env, $settings, $actions;
			
			$minified = ($_GET["minified"] ?? "false") == "true";
			
			$action_names = array_keys(get_object_vars($actions));
			sort($action_names);
			
			$result = new stdClass();
			$result->status = "ok";
			$result->version = $version;
			$result->available_actions = $action_names;
			$result->wiki_name = $settings->sitename;
			$result->logo_url = $settings->favicon;
			if(module_exists("page-sitemap"))
				$result->sitemap_url = url_stem()."?action=sitemap";
			
			header("content-type: application/json");
			exit($minified ? json_encode($result) : json_encode($result, JSON_PRETTY_PRINT) . "\n");
		});
		
		add_help_section("960-api-status", "Wiki Status API", "<p>$settings->sitename has a <a href='?action=status'>status page</a> that returns some basic information about the current state of the wiki in <a href='http://www.secretgeek.net/json_3mins'>JSON</a>. It can be used as a connection tester - as the Pepperminty Wiki Android app does.</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Sidebar",
	"version" => "0.3.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a sidebar to the left hand side of every page. Add '\$settings->sidebar_show = true;' to your configuration, or append '&sidebar=yes' to the url to enable. Adding to the url sets a cookie to remember your setting.",
	"id" => "extra-sidebar",
	"code" => function() {
		global $settings;
		
		$show_sidebar = false;
		
		// Show the sidebar if it is enabled in the settings
		if(isset($settings->sidebar_show) && $settings->sidebar_show === true)
			$show_sidebar = true;
		
		// Also show and persist the sidebar if the special GET parameter
		// sidebar is seet
		if(!$show_sidebar && isset($_GET["sidebar"]))
		{
			$show_sidebar = true;
			// Set a cookie to persist the display of the sidebar
			send_cookie("sidebar_show", "true", time() + (60 * 60 * 24 * 30));
		}
		
		// Show the sidebar if the cookie is set
		if(!$show_sidebar && isset($_COOKIE["sidebar_show"]))
			$show_sidebar = true;
		
		// Delete the cookie and hide the sidebar if the special GET paramter
		// nosidebar is set
		if(isset($_GET["nosidebar"]))
		{
			$show_sidebar = false;
			unset($_COOKIE["sidebar_show"]);
			send_cookie("sidebar_show", null, time() - 3600);
		}
		
		page_renderer::register_part_preprocessor(function(&$parts) use ($show_sidebar) {
			global $settings, $pageindex, $env;
			
			// Don't render a sidebar if the user is logging in and a login is
			// required in order to view pages.
			if($settings->require_login_view && in_array($env->action, [ "login", "checklogin" ]))
				return false;
			
			if($show_sidebar && !isset($_GET["printable"]))
			{
				// Show the sidebar
				$exec_start = microtime(true);
				
				// Sort the pageindex
				$sorted_pageindex = get_object_vars($pageindex);
				
				$sorter = new Collator("");
				uksort($sorted_pageindex, function($a, $b) use($sorter) : int {
					return $sorter->compare($a, $b);
				});
				
				$sidebar_contents = "";
				$sidebar_contents .= render_sidebar($sorted_pageindex);
				
				$parts["{body}"] = "<aside class='sidebar'>
			$sidebar_contents
			<!-- Sidebar rendered in " . (microtime(true) - $exec_start) . "s -->
		</aside>
		<div class='main-container'>" . $parts["{body}"] . "</div>
		<!-------------->
		<style>
			body { display: flex; }
			.main-container { flex: 1; }
		</style>";
			}
		});
		
		add_help_section("50-sidebar", "Sidebar", "<p>$settings->sitename has an optional sidebar which displays a list of all the current pages and their respective subpages that it is currently hosting in a tree like structure. It may or may not be enabled.</p>
		<p>If it isn't enabled, it can be enabled for your current browser only by appending <code>sidebar=yes</code> to the current page's query string.</p>
		<p>If it is enabled, it can be disabled for your current browser only by appending <code>nosidebar</code> to the current page's query string.</p>");
	}
]);

/**
 * Renders the sidebar for a given pageindex.
 * @package	extra-sidebar
 * @param	array		$pageindex		The pageindex to render the sidebar for
 * @param	string		$root_pagename	The pagename that should be considered the root of the rendering. You don't usually need to use this, it is used by the algorithm itself since it is recursive.
 * @return	string		A HTML rendering of the sidebar for the given pageindex.
 */
function render_sidebar($pageindex, $root_pagename = "", $depth = 0)
{
	global $settings;
	
	if($depth > $settings->sidebar_maxdepth)
		return null;
	
	if(mb_strlen($root_pagename) > 0) $root_pagename .= "/";
	
	$result = "<ul";
	// If this is the very root of the tree, add an extra class to it
	if($root_pagename == "") $result .= " class='sidebar-tree'";
	$result .=">";
	$subpages_added = 0;
	foreach ($pageindex as $pagename => $details)
	{
		// If we have a valid root pagename, and it isn't present at the
		// beginning of the current pagename, skip it
		if($root_pagename !== "" && strpos($pagename, $root_pagename) !== 0)
			continue;
		
		// The current page is the same as the root page, skip it
		if($pagename == $root_pagename)
			continue;

		// If the page already appears on the sidebar, skip it
		if(strpos($result, ">$pagename<\a>") !== false)
			continue;
		
		$pagename_relative = substr($pagename, strlen($root_pagename));
		
		// If the part of the current pagename that comes after the root
		// pagename has a slash in it, skip it as it is a sub-sub page.
		if(strpos($pagename_relative, "/") !== false)
			continue;
		
		$subpage_sidebar = render_sidebar($pageindex, $pagename, $depth + 1);
		
		if($subpage_sidebar === null) {
			$result .= "<li><a href='?action=$settings->defaultaction&page=$pagename'>$pagename_relative</a></li>";
		}
		else {
			$result .= "<li><details open>
				<summary><a href='?action=$settings->defaultaction&page=$pagename'>$pagename_relative</a></summary>
					$subpage_sidebar
				</details></li>\n";
		}
		$subpages_added++;
	}
	$result .= "</ul>\n";
	
	if($subpages_added === 0) return null;
	
	return $result == "<ul></ul>\n" ? "" : $result;
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Command-line interface",
	"version" => "0.1.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Allows interaction with Pepperminty Wiki on the command line.",
	"id" => "feature-cli",
	"code" => function() {
		global $settings;
		
		cli_register("version", "Shows the current version of Pepperminty Wiki", function(array $_args) : int {
			global $version, $commit;
			echo("$version-".substr($commit, 0, 7)."\n");
			return 0;
		});
		
		cli_register("help", "Displays this message", function(array $_args) : int {
			global $version, $commit, $cli_commands;
			echo("***** Pepperminty Wiki CLI *****
$version-".substr($commit, 0, 7)."

This is the command-line interface for Pepperminty Wiki.

Commands:
");
			
			foreach($cli_commands as $name => $data) {
				echo("    $name    {$data->description}\n");
			}
			
			return 0;
		});
		
		cli_register("shell", "Starts the Pepperminty Wiki shell", function(array $_args) : int {
			cli_shell();
			return 0;
		});
		
		cli_register("exit", "Exits the Pepperminty Wiki shell", function(array $args) {
			$exit_code = 0;
			if(!empty($args)) $exit_code = intval($args[0]);
			exit($exit_code);
		});
		
		add_help_section("999-cli", "Command Line Interface", "<p>System administrators can interact with $settings->sitename via a command-line interface if they have console or terminal-level access to the server that $settings->sitename runs on.</p>
		<p>To do this, system administrators can display the CLI-specific help by changing directory (with the <code>cd</code> command) to be next to <code>index.php</code>, and executing the following:</p>
		<pre><code>php index.php</code></pre>");
	}
]);

/**
 * Ensures that the current execution environment is the command-line interface.
 * This function will not return if thisthe current execution environment is not the CLI.
 * @return void
 */
function ensure_cli() {
	global $settings;
	if(php_sapi_name() == "cli") return true;
	
	header("content-type: text/plain");
	exit("Oops! Somewhere along the way Pepperminty Wiki's command-line interface was invoked by accident.
This is unfortunately an unrecoverable fatal error. Please get in touch with $settings->admindetails_name, $settings->sitename's administrator (their email address is $settings->admindetails_email).
");
}

/**
 * Parses $_SERVER["argv"] and provides a command-line interface.
 * This function kill the process if the current execution environment is not the CLI.
 * @return void
 */
function cli() {
	global $version, $commit;
	ensure_cli();
	
	$args = array_slice($_SERVER["argv"], 1);
	
	switch($args[0] ?? "") {
		case "version":
		case "shell":
			exit(cli_exec($args[0]));
		
		case "exec":
			file_put_contents("php://stderr", "Executing {$args[1]}\n");
			exit(cli_exec(array_slice($args, 1)) ? 0 : 1);
			break;
		
		case "help":
		default:
			echo("***** Pepperminty Wiki CLI *****
$version-".substr($commit, 0, 7)."

This is the command-line interface for Pepperminty Wiki.

Usage:
php ./index.php {subcommand}

Commands:
    help                  Displays this message
    version               Shows the current version of Pepperminty Wiki
    shell                 Starts the Pepperminty Wiki shell
    exec \"{command}\"      Executes a Pepperminty Wiki shell command
");
			break;
	}
	
	exit(0);
}

/**
 * Starts the Pepperminty Wiki CLI Shell.
 * This function kill the process if the current execution environment is not the CLI.
 * @return [type] [description]
 */
function cli_shell() {
	global $settings;
	ensure_cli();
	
	echo(wordwrap("Welcome to the Pepperminty Wiki CLI shell!
Type \"help\" (without quotes) to get help.

Be warned that you are effectively the superuser for your wiki right now, with completely unrestricted access!

"));
	
	while(true) {
		$next_line = readline($settings->cli_prompt);
		if($next_line === false) { echo("\nexit\n"); exit(0); }
		if(strlen($next_line) == 0) continue;
		
		readline_add_history($next_line);
		$exit_code = -1;
		try {
			$exit_code = cli_exec($next_line);
		}
		catch (Exception $error) {
			echo("***** Error *****\n");
			echo($error);
		}
		echo("<<<< $exit_code <<<<\n");
	}
}

/**
 * Executes a given Pepperminty Wiki shell command.
 * This function kill the process if the current execution environment is not the CLI.
 * The returned exit code functions as a normal shell process exit code does.
 * @param	string|array	$string		The shell command to execute.
 * @return	int				The exit code of the command executed.
 */
function cli_exec($string) : int {
	global $settings, $cli_commands;
	ensure_cli();
	
	$parts = is_string($string) ? preg_split("/\s+/", $string) : $string;
	if(!is_array($parts))
		throw new Exception("Error: Invalid type. Expected an array of parts or a string to execute.");
	
	if(!isset($cli_commands->{$parts[0]})) {
		echo("Error: The command with the name {$parts[0]} could not be found (try the help command instead).\n");
		return 1;
	}
	
	// Apparently you still have to assign a callable to a variable in order to call it dynamically like this. Ref: core/100-run.php
	$method = $cli_commands->{$parts[0]}->code;
	return $method(array_slice($parts, 1));
}

$cli_commands = new stdClass();

/**
 * Registers a new CLI command.
 * Throws an error if a CLI command with the specified name already exists.
 * @param  string   $name        The name of command.
 * @param  string   $description The description of the command.
 * @param  callable $function    The function to execute when this command is executed. An array is passed as the first and only argument containing the arguments passed when the command was invoked.
 * @return void
 */
function cli_register(string $name, string $description, callable $function) {
	global $cli_commands;
	
	if(isset($cli_commands->$name))
		throw new Exception("Error: A CLI command with the name $name has already been registered (description: {$cli_commands->$name->description})");
	
	$cli_commands->$name = (object) [
		"description" => $description,
		"code" => $function
	];
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Page Comments",
	"version" => "0.3.5",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds threaded comments to the bottom of every page.",
	"id" => "feature-comments",
	"code" => function() {
		global $env, $settings;
		
		// Don't do anything if we're not wanted
		if($settings->comment_hide_all)
			return;
		
		/**
		 * @api {post} ?action=comment	Comment on a page
		 * @apiName Comment
		 * @apiGroup Comment
		 * @apiPermission User
		 * @apiDescription	Posts a comment on a page, optionally in reply to another comment. Currently, comments must be made by a logged-in user.
		 * 
		 * @apiParam {string}	message	The comment text. Supports the same syntax that the renderer of the main page supports. The default is extended markdown - see the help page of the specific wiki for more information.
		 * @apiParam {string}	replyto	Optional. If specified the comment will be posted in reply to the comment with the specified id.
		 * 
		 *
		 * @apiError	CommentNotFound	The comment to reply to was not found.
		 */
		
		/*
		 *   ██████  ██████  ███    ███ ███    ███ ███████ ███    ██ ████████
		 *  ██      ██    ██ ████  ████ ████  ████ ██      ████   ██    ██
		 *  ██      ██    ██ ██ ████ ██ ██ ████ ██ █████   ██ ██  ██    ██
		 *  ██      ██    ██ ██  ██  ██ ██  ██  ██ ██      ██  ██ ██    ██
		 *   ██████  ██████  ██      ██ ██      ██ ███████ ██   ████    ██
		 */
		add_action("comment", function() {
			global $settings, $env;
			
			$reply_to = $_POST["replyto"] ?? null;
			$message = $_POST["message"] ?? "";
			
			if(!$settings->comment_enabled) {
				http_response_code(401);
				exit(page_renderer::render_main("Commenting disabled - $settings->sitename", "<p>Your comment couldn't be posted because $settings->sitename currently has commenting disabled. Here's the comment you tried to post:</p>
				<textarea readonly>".htmlentities($message)."</textarea>"));
			}
			
			if(!$env->is_logged_in && !$settings->anoncomments) {
				http_response_code(401);
				exit(page_renderer::render_main("Error posting comment - $settings->sitename", "<p>Your comment couldn't be posted because you're not logged in. You can login <a href='?action=index'>here</a>. Here's the comment you tried to post:</p>
				<textarea readonly>".htmlentities($message)."</textarea>"));
			}
			
			$message_length = strlen($message);
			if($message_length < $settings->comment_min_length) {
				http_response_code(422);
				exit(page_renderer::render_main("Error posting comment - $settings->sitename", "<p>Your comment couldn't be posted because it was too short. $settings->sitename needs at ".htmlentities($settings->comment_min_length)." characters in a comment in order to post it.</p>"));
			}
			if($message_length > $settings->comment_max_length) {
				http_response_code(422);
				exit(page_renderer::renderer_main("Error posting comment - $settings->sitename", "<p>Your comment couldn't be posted because it was too long. $settings->sitename can only post comments that are up to ".htmlentities($settings->comment_max_length)." characters in length, and yours was $message_length characters. Try splitting it up into multiple comments! Here's the comment you tried to post:</p>
				<textarea readonly>".htmlentities($message)."</textarea>"));
			}
			
			// Figure out where the comments are stored
			$comment_filename = get_comment_filename($env->page);
			if(!file_exists($comment_filename)) {
				if(file_put_contents($comment_filename, "[]\n") === false) {
					http_response_code(503);
					exit(page_renderer::renderer_main("Error posting comment - $settings->sitename", "<p>$settings->sitename ran into a problem whilst creating a file to save your comment to! Please contact " . hide_email($settings->admindetails_email, $settings->admindetails_name) . ", $settings->sitename's administrator and tell them about this problem.</p>"));
				}
			}
			
			$comment_data = json_decode(file_get_contents($comment_filename));
			
			$new_comment = new stdClass();
			$new_comment->id = generate_comment_id();
			$new_comment->timestamp = date("c");
			$new_comment->username = $env->user;
			$new_comment->logged_in = $env->is_logged_in;
			$new_comment->message = $message;
			$new_comment->replies = [];
			
			if($reply_to == null)
				$comment_data[] = $new_comment;
			else {
				$parent_comment = find_comment($comment_data, $reply_to);
				if($parent_comment === false) {
					http_response_code(422);
					exit(page_renderer::render_main("Error posting comment - $settings->sitename", "<p>$settings->sitename couldn't post your comment because it couldn't find the parent comment you replied to. It's possible that $settings->admindetails_name, $settings->sitename's administrator, deleted the comment. Here's the comment you tried to post:</p>
					<textarea readonly>".htmlentities($message)."</textarea>"));
				}
				
				$parent_comment->replies[] = $new_comment;
				
				// Get an array of all the parent comments we need to notify
				$comment_thread = fetch_comment_thread($comment_data, $parent_comment->id);
				
				$email_subject = "[Notification] $env->user replied to your comment on $env->page - $settings->sitename";
				
				foreach($comment_thread as $thread_comment) {
					$email_body = "Hello, {username}!\n" . 
						"It's $settings->sitename here, letting you know that " . 
						"someone replied to your comment (or a reply to your comment) on $env->page.\n" . 
						"\n" . 
						"They said:\n" . 
						"\n" . 
						"$new_comment->message" . 
						"\n" . 
						"You said on " . date("c", strtotime($thread_comment->timestamp)) . ":\n" . 
						"\n" . 
						"$thread_comment->message\n" . 
						"\n";
					
					// If the user is anonymous, this will fail and return false (it accounts for non-existent users automatically)
					email_user($thread_comment->username, $email_subject, $email_body);
				}
			}
			
			// Save the comments back to disk
			if(file_put_contents($comment_filename, json_encode($comment_data, JSON_PRETTY_PRINT)) === false) {
				http_response_code(503);
				exit(page_renderer::renderer_main("Error posting comment - $settings->sitename", "<p>$settings->sitename ran into a problem whilst saving your comment to disk! Please contact " . hide_email($settings->admindetails_email, $settings->admindetails_name) . ", $settings->sitename's administrator and tell them about this problem.</p>"));
			}
			
			// Add a recent change if the recent changes module is installed
			if(module_exists("feature-recent-changes")) {
				add_recent_change([
					"type" => "comment",
					"timestamp" => time(),
					"page" => $env->page,
					"user" => $env->user,
					"reply_depth" => $comment_thread !== null ? count($comment_thread) : 0,
					"comment_id" => $new_comment->id
				]);
			}
			
			http_response_code(307);
			header("location: ?action=view&page=" . rawurlencode($env->page) . "&commentsuccess=yes#comment-$new_comment->id");
			exit(page_renderer::render_main("Comment posted successfully - $settings->sitename", "<p>Your comment on $env->page_safe was posted successfully. If your browser doesn't redirect you automagically, please <a href='?action=view&page=".rawurlencode($env->page)."commentsuccess=yes#comment-$new_comment->id'>click here</a> to go to the comment you posted on the page you were viewing.</p>"));
		});
		
		
		/**
		 * @api {post} ?action=comment-delete&page={page_name}&delete_id={id_to_delete}	Delete a comment
		 * @apiName CommentDelete
		 * @apiGroup Comment
		 * @apiPermission User
		 * @apiDescription	Deletes a comment with the specified id. If you aren't the one who made the comment in the first place, then you must be a moderator or better to delete it.
		 * 
		 * @apiUse		PageParameter
		 * @apiParam	{string}	delete_id	The id of the comment to delete.
		 * 
		 * @apiError	CommentNotFound	The comment to delete was not found.
		 */
		
		/*
		 *  ██████  ██████  ███    ███ ███    ███ ███████ ███    ██ ████████
		 * ██      ██    ██ ████  ████ ████  ████ ██      ████   ██    ██
		 * ██      ██    ██ ██ ████ ██ ██ ████ ██ █████   ██ ██  ██    ██ █████
		 * ██      ██    ██ ██  ██  ██ ██  ██  ██ ██      ██  ██ ██    ██
		 *  ██████  ██████  ██      ██ ██      ██ ███████ ██   ████    ██
		 * ██████  ███████ ██      ███████ ████████ ███████
		 * ██   ██ ██      ██      ██         ██    ██
		 * ██   ██ █████   ██      █████      ██    █████
		 * ██   ██ ██      ██      ██         ██    ██
		 * ██████  ███████ ███████ ███████    ██    ███████
		 */
		add_action("comment-delete", function () {
			global $env, $settings;
			
			
			if(!isset($_GET["delete_id"])) {
				http_response_code(400);
				exit(page_renderer::render_main("Error - Deleting Comment - $settings->sitename", "<p>You didn't specify the id of a comment to delete.</p>"));
			}
			
			// Make sure that the user is logged in before deleting a comment
			if(!$env->is_logged_in) {
				http_response_code(307);
				header("location: ?action=login&returnto=" . rawurlencode("?action=comment-delete&page=" . rawurlencode($env->page) . "&id=" . rawurlencode($_GET["delete_id"])));
			}
			
			$comment_filename = get_comment_filename($env->page);
			$comments = json_decode(file_get_contents($comment_filename));
			$target_id = $_GET["delete_id"];
			
			$comment_to_delete = find_comment($comments, $target_id);
			if($comment_to_delete->username !== $env->user && !$env->is_admin) {
				http_response_code(401);
				exit(page_renderer::render_main("Error - Deleting Comment - $settings->sitename", "<p>You can't delete the comment with the id <code>" . htmlentities($target_id) . "</code> on the page <em>$env->page_safe</em> because you're logged in as " . page_renderer::render_username($env->user) . ", and " . page_renderer::render_username($comment_to_delete->username) . " made that comment. Try <a href='?action=logout'>Logging out</a> and then logging in again as " . page_renderer::render_username($comment_to_delete->username) . ", or as a moderator or better."));
			}
			
			if(!delete_comment($comments, $_GET["delete_id"])) {
				http_response_code(404);
				exit(page_renderer::render_main("Comment not found - Deleting Comment - $settings->sitename", "<p>The comment with the id <code>" . htmlentities($_GET["delete_id"]) . "</code> on the page <em>$env->page_safe</em> wasn't found. Perhaps it was already deleted?</p>"));
			}
			
			if(!file_put_contents($comment_filename, json_encode($comments))) {
				http_response_code(503);
				exit(page_renderer::render_main("Server Error - Deleting Comment - $settings->sitename", "<p>While $settings->sitename was able to delete the comment with the id <code>" . htmlentities($target_id) . "</code> on the page <em>$env->page_safe</em>, it couldn't save the changes back to disk. Please contact " . hide_email($settings->admindetails_email, $settings->admindetails_name) . ", $settings->sitename's local friendly administrator about this issue.</p>"));
			}
			
			exit(page_renderer::render_main("Comment Deleted - $settings->sitename", "<p>The comment with the id <code>" . htmlentities($target_id) . "</code> on the page <em>$env->page_safe</em> has been deleted successfully. <a href='?page=" . rawurlencode($env->page) . "&redirect=no'>Go back</a> to $env->page_safe.</p>"));
		});
		/**
		 * @api {post} ?action=comments-fetch&page={page_name}	Fetch the comments for a page
		 * @apiName CommentsFetch
		 * @apiGroup Comment
		 * @apiPermission Anonymous
		 * @apiDescription	Fetches the comments for the specified page. Returns them in a nested JSON structure.
		 * 
		 * @apiUse		PageParameter
		 * @apiError	PageNoteFound	The page to fetch the comments for was not found.
		 */
		
		/*
 		 *  ██████  ██████  ███    ███ ███    ███ ███████ ███    ██ ████████
 		 * ██      ██    ██ ████  ████ ████  ████ ██      ████   ██    ██
 		 * ██      ██    ██ ██ ████ ██ ██ ████ ██ █████   ██ ██  ██    ██ █████
 		 * ██      ██    ██ ██  ██  ██ ██  ██  ██ ██      ██  ██ ██    ██
 		 *  ██████  ██████  ██      ██ ██      ██ ███████ ██   ████    ██
 		 *  
 		 * ███████ ███████ ████████  ██████ ██   ██
 		 * ██      ██         ██    ██      ██   ██
 		 * █████   █████      ██    ██      ███████
 		 * ██      ██         ██    ██      ██   ██
 		 * ██      ███████    ██     ██████ ██   ██
		 */
		add_action("comments-fetch", function() {
			global $env;
			
			$comments_filename = get_comment_filename($env->page);
			if(!file_exists($comments_filename)) {
				http_response_code(404);
				header("content-type: text/plain");
				exit("Error: No comments file was found for the page '$env->page_safe'.");
			}
			
			$comments_data = json_decode(file_get_contents($comments_filename));
			
			$result = json_encode($comments_data);
			header("content-type: application/json");
			header("content-length: " . strlen($result));
			exit($result);
		});
		
		
		if($env->action == "view") {
			page_renderer::register_part_preprocessor(function(&$parts) {
				global $env, $settings;
				$comments_filename = get_comment_filename($env->page);
				$comments_data = file_exists($comments_filename) ? json_decode(file_get_contents($comments_filename)) : [];
				
				
				$comments_html = "<aside class='comments'>" . 
					"<h2 id='comments'>Comments</h2>\n";
				
				if($env->is_logged_in && $settings->comment_enabled) {
					$comments_html .= "<form class='comment-reply-form' method='post' action='?action=comment&page=" . rawurlencode($env->page) . "'>\n" . 
						"<h3>Post a Comment</h3>\n" . 
						"\t<textarea name='message' placeholder='Type your comment here. You can use the same syntax you use when writing pages.'></textarea>\n" . 
						"\t<input type='hidden' name='replyto' />\n" . 
						"\t<input type='submit' value='Post Comment' />\n" . 
						"</form>\n";
				}
				elseif($settings->comment_enabled) {
					$comments_html .= "<form class='comment-reply-form disabled no-login'>\n" . 
					"\t<textarea disabled name='message' placeholder='Type your comment here. You can use the same syntax you use when writing pages.'></textarea>\n" . 
					"\t<p class='not-logged-in'><a href='?action=login&returnto=" . rawurlencode("?action=view&page=" . rawurlencode($env->page) . "#comments") . "'>Login</a> to post a comment.</p>\n" . 
					"\t<input type='hidden' name='replyto' />\n" . 
					"\t<input disabled type='submit' value='Post Comment' title='Login to post a comment.' />\n" . 
					"</form>\n";
				}
				
				$comments_html .= render_comments($comments_data);
				
				$comments_html .= "</aside>\n";
				
				$to_comments_link = "<div class='jump-to-comments'><a href='#comments'>Jump to comments</a></div>";
				
				$parts["{extra}"] = $comments_html . $parts["{extra}"];
				
				$parts["{content}"] = str_replace_once("</h1>", "</h1>\n$to_comments_link", $parts["{content}"]);
			});
			
			$reply_js_snippet = <<<'REPLYJS'
///////////////////////////////////
///////// Commenting Form /////////
///////////////////////////////////
window.addEventListener("load", function(event) {
	var replyButtons = document.querySelectorAll(".reply-button");
	for(let i = 0; i < replyButtons.length; i++) {
		replyButtons[i].addEventListener("click", display_reply_form);
		replyButtons[i].addEventListener("touchend", display_reply_form);
	}
});

function display_reply_form(event)
{
	// Deep-clone the comment form
	var replyForm = document.querySelector(".comment-reply-form").cloneNode(true);
	replyForm.classList.add("nested");
	// Set the comment we're replying to
	replyForm.querySelector("[name=replyto]").value = event.target.parentElement.parentElement.parentElement.dataset.commentId;
	// Display the newly-cloned commenting form
	var replyBoxContiner = event.target.parentElement.parentElement.parentElement.querySelector(".reply-box-container");
	replyBoxContiner.classList.add("active");
	replyBoxContiner.appendChild(replyForm);
	// Hide the reply button so it can't be pressed more than once - that could
	// be awkward :P
	event.target.parentElement.removeChild(event.target);
}

REPLYJS;
			page_renderer::add_js_snippet($reply_js_snippet);
			
		}
		
		add_help_section("29-commenting", "Commenting", "<p>$settings->sitename has a threaded commenting system on every page. You can find it below each page's content, and can either leave a new comment, or reply to an existing one. If you reply to an existing one, then the authors of all the comments above yours will get notified by email of your reply - so long as they have an email address registered in their preferences.</p>");
	}
]);

/**
 * Given a page name, returns the absolute file path in which that page's
 * comments are stored.
 * @package feature-comments
 * @param  string $pagename The name pf the page to fetch the comments filename for.
 * @return string           The path to the file that the 
 */
function get_comment_filename($pagename)
{
	global $env;
	$pagename = makepathsafe($pagename);
	return "$env->storage_prefix$pagename.comments.json";
}

/**
 * Generates a new random comment id.
 * @package feature-comments
 * @return string A new random comment id.
 */
function generate_comment_id()
{
	$result = base64_encode(random_bytes(16));
	$result = str_replace(["+", "/", "="], ["-", "_"], $result);
	return $result;
}

/**
 * Finds the comment with specified id by way of an almost-breadth-first search.
 * @package feature-comments
 * @param  array $comment_data	The comment data to search.
 * @param  string $comment_id	The id of the comment to  find.
 * @return object				The comment data with the specified id, or
 *                       		false if it wasn't found.
 */
function find_comment($comment_data, $comment_id)
{
	$subtrees = [];
	foreach($comment_data as $comment)
	{
		if($comment->id === $comment_id)
			return $comment;
		
		if(count($comment->replies) > 0) {
			$subtrees[] = $comment->replies;
		}
	}
	
	foreach($subtrees as $subtree)
	{
		$subtree_result = find_comment($subtree, $comment_id);
		if($subtree_result !== false)
			return $subtree_result;
	}
	
	return false;
}

/**
 * Deletes the first comment found with the specified id.
 * @package feature-comments
 * @param	array	$comment_data	An array of threaded comments to delete the comment from.
 * @param	string	$target_id		The id of the comment to delete.
 * @return	bool					Whether the comment was found and deleted or not.
 */
function delete_comment(&$comment_data, $target_id)
{
	$comment_count = count($comment_data);
	if($comment_count === 0) return false;
	
	for($i = 0; $i < $comment_count; $i++) {
		if($comment_data[$i]->id == $target_id) {
			if(count($comment_data[$i]->replies) == 0) {
				unset($comment_data[$i]);
				// Reindex the comment list before returning
				$comment_data = array_values($comment_data);
			}
			else {
				unset($comment_data[$i]->username);
				$comment_data[$i]->message = "_[Deleted]_";
			}
			return true;
		}
		if(count($comment_data[$i]->replies) > 0 &&
			delete_comment($comment_data[$i]->replies, $target_id))
			return true;
	}
	
	
	return false;
}

/**
 * Fetches all the parent comments of the specified comment id, including the
 * comment itself at the end.
 * Useful for figuring out who needs notifying when a new comment is posted.
 * @package feature-comments
 * @param	array		$comment_data	The comment data to search.
 * @param	string		$comment_id		The comment id to fetch the thread for.
 * @return	object[]	A list of the comments in the thread, with the deepest
 * 						one at the end.
 */
function fetch_comment_thread($comment_data, $comment_id)
{
	foreach($comment_data as $comment)
	{
		// If we're the comment they're looking for, then return ourselves as
		// the beginning of a thread
		if($comment->id === $comment_id)
			return [ $comment ];
		
		if(count($comment->replies) > 0) {
			$subtree_result = fetch_comment_thread($comment->replies, $comment_id);
			if($subtree_result !== false) {
				// Prepend ourselves to the result
				array_unshift($subtree_result, $comment);
				return $subtree_result; // Return the comment thread
			}
		}
	}
	
	return false;
}

/**
 * Renders a given comments tree to html.
 * @package feature-comments
 * @param	object[]	$comments_data	The comments tree to render.
 * @param	integer		$depth			For internal use only. Specifies the depth
 * 										at which the comments are being rendered.
 * @return	string		The given comments tree as html.
 */
function render_comments($comments_data, $depth = 0)
{
	global $settings, $env;
	
	if(count($comments_data) == 0) {
		if($depth == 0)
			return "<p><em>No comments here! Start the conversation above.</em></p>";
		else
			return "";
	}
	
	$result = "<div class='comments-list" . ($depth > 0 ? " nested" : "") . "' data-depth='$depth'>";
	
	//$comments_data = array_reverse($comments_data);
	for($i = count($comments_data) - 1; $i >= 0; $i--) {
		$comment = $comments_data[$i];
		
		$result .= "\t<div class='comment' id='comment-$comment->id' data-comment-id='$comment->id'>\n";
		$result .= "\t<p class='comment-header'><span class='name'>" . page_renderer::render_username($comment->username ?? "<em>Unknown</em>") . "</span> said:</p>";
		$result .= "\t<div class='comment-body'>\n";
		$result .= "\t\t" . parse_page_source($comment->message, true);
		$result .= "\t</div>\n";
		$result .= "\t<div class='reply-box-container'></div>\n";
		$result .= "\t<p class='comment-footer'>";
		$result .= "\t\t<span class='comment-footer-item'><button class='reply-button'>Reply</button></span>\n";
		if($env->user == $comment->username || $env->is_admin)
			$result .= "<span class='comment-footer-item'><a href='?action=comment-delete&page=" . rawurlencode($env->page) . "&delete_id=" . rawurlencode($comment->id) . "' class='delete-button' title='Permanently delete this comment'>Delete</a></span>\n";
		$result .= "\t\t<span class='comment-footer-item'><a class='permalink-button' href='#comment-$comment->id' title='Permalink to this comment'>&#x1f517;</a></span>\n";
		$result .= "\t\t<span class='comment-footer-item'><time datetime='" . date("c", strtotime($comment->timestamp)) . "' title='The time this comment was posted'>$settings->comment_time_icon " . date("l jS \of F Y \a\\t h:ia T", strtotime($comment->timestamp)) . "</time></span>\n";
		$result .= "\t</p>\n";
		$result .= "\t" . render_comments($comment->replies, $depth + 1) . "\n";
		$result .= "\t</div>";
	}
	$result .= "</div>";
	
	return $result;
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */


register_module([
	"name" => "First run wizard",
	"version" => "0.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Displays a special page to aid in setting up a new wiki for the first time.",
	"id" => "feature-firstrun",
	"code" => function() {
		global $settings, $env;
		
		
		// NOTE: We auto-detect pre-existing wikis in 01-settings.fragment.php
		if(!$settings->firstrun_complete && preg_match("/^firstrun/", $env->action) !== 1) {
			http_response_code(307);
			header("location: ?action=firstrun");
			exit("Redirecting you to the first-run wizard....");
		}
		
		/**
		 * @api {get} ?action=firstrun	Display the firstrun page
		 * @apiName FirstRun
		 * @apiGroup Settings
		 * @apiPermission Anonymous
		 * 
		 */
		
		/*
 	 	 * ███████ ██ ██████  ███████ ████████ ██████  ██    ██ ███    ██
 		 * ██      ██ ██   ██ ██         ██    ██   ██ ██    ██ ████   ██
 		 * █████   ██ ██████  ███████    ██    ██████  ██    ██ ██ ██  ██
 		 * ██      ██ ██   ██      ██    ██    ██   ██ ██    ██ ██  ██ ██
 		 * ██      ██ ██   ██ ███████    ██    ██   ██  ██████  ██   ████
		 */
		add_action("firstrun", function() {
			global $settings, $settingsFilename, $version;
			
			if($settings->firstrun_complete) {
				http_response_code(400);
				exit(page_renderer::render_main("Setup complete - Error - $settings->sitename", "<p>Oops! Looks like $settings->sitename is already setup and ready to go! Go to the <a href='?action=$settings->defaultaction&page=".rawurlencode($settings->defaultpage)."'>" . htmlentities($settings->defaultpage)."</a> to get started!</p>"));
			}
			
			if(!module_exists("page-login")) {
				http_response_code(503);
				exit(page_renderer::render_main("Build error - Pepperminty Wiki", "<p>The <code>page-login</code> wasn't included in this build of Pepperminty Wiki, so the first-run installation wizard will not work correctly.</p>
				<p>You can still complete the setup manually, however! Once done, set <code>firstrun_complete</code> in peppermint.json to <code>true</code>.</p>"));
			}
			
			if(!$settings->disable_peppermint_access_check &&
				php_sapi_name() !== "cli-server") { // The CLI server is single threaded, so it can't support loopback requests
				$request_url = full_url();
				$request_url = preg_replace("/\/(index.php)?\?.*$/", "/$settingsFilename", $request_url);
				@file_get_contents($request_url);
				// $http_response_header is a global reserved variable. More information: https://devdocs.io/php/reserved.variables.httpresponseheader
				$response_code = intval(explode(" ", $http_response_header[0])[1]);
				if($response_code >= 200 && $response_code < 300) {
					file_put_contents("$settingsFilename.compromised", "compromised");
					http_response_code(307);
					header("location: index.php");
					exit();
				}
			}
			else {
				error_log("[PeppermintyWiki/firstrun] Warning: The public peppermint.json access check has been disabled (either manually or because you're using a local PHP development server with php -S ....). It's strongly recommended you ensure that access from outside is blocked to peppermint.json to avoid (many) security issues and other nastiness such as stealing of site secrets and password hashes.");
			}
			
			// TODO: Check the environment here first
			//  - Check for required modules?
			
			// TODO: Add a button to skip the firstrun wizard & do your own manual setup
			
			// TODO: Add option to configure theme auto-update here - make sure it doesn't do anything until configuration it complete!
			
			$system_checks = implode("\n\t\t\t", array_map(function($check) {
				$result = "<li>";
				if(!$check[0]) $result .= "<strong>";
				$result .= $check[0] ? "&#x2705;" : ($check[2] === "optional" ? "&#x26a0;&#xfe0f;" : "&#x274c;");
				$result .= " {$check[1]}";
				if(!$check[0]) $result .= "</strong>";
				return $result;
			}, do_system_checks()));
			
			$result = "<h1>Welcome!</h1>
<p>Welcome to Pepperminty Wiki.</p>
<p>Fill out the below form to get your wiki up and running!</p>
<p>Optionally, <a target='_blank' href='https://starbeamrainbowlabs.com/blog/viewtracker.php?action=record&post-id=pepperminty-wiki/$version&format=text'>click this link</a> to say hi and let Starbeamrainbowlabs know that you're setting up a new Pepperminty Wiki $version instance.</p>
<form method='post' action='?action=firstrun-complete'>
	<fieldset>
		<legend>System requirements</legend>
		
		<ul>
			$system_checks
		</ul>
	</fieldset>
	<fieldset>
		<legend>Authorisation</legend>
		
		<p><em>Find your wiki secret in the <code>secret</code> property inside <code>peppermint.json</code>. Don't forget to avoid copying the quotes surrounding the value!</em></p>
		<label for='secret'>Wiki Secret:</label>
		<input type='password' id='secret' name='secret' />
	</fieldset>
	<fieldset>
		<legend>Admin account details</legend>
		
		<label for='username'>Username:</label>
		<input type='text' id='username' name='username' placeholder='e.g. bob, admin' required />
		<br />
		<label for='username'>Email address:</label>
		<input type='text' id='email-address' name='email-address' required />
		<br />
		<p><em>Longer is better! Aim for at least 14 characters.</em></p>
		<label for='username'>Password:</label>
		<input type='password' id='password' name='password' required />
		<br />
		<label for='username'>Repeat Password:</label>
		<input type='password' id='password-again' name='password-again' required />
	</fieldset>
	<fieldset>
		<legend>Wiki details</legend>
		
		<label for='wiki-name'>Wiki Name:</label>
		<input type='text' id='wiki-name' name='wiki-name' placeholder=\"e.g. Bob's Rockets Compendium\" required />
		<!-- FUTURE: Have a logo url box here? -->
		<p><em>The location on the server's disk to store the wiki data. Relative paths are ok - the default is <code>.</code> (i.e. the current directory).</em></p>
		<label for='data-dir'>Data Storage Directory:</label>
		<input type='text' id='data-dir' name='data-dir' value='.' required />
	</fieldset>
	
	<input type='submit' value='Create Wiki!' />
</form>";
			
			exit(page_renderer::render_main("Welcome! - Pepperminty Wiki", $result));
		});
		
		
		/**
		 * @api {post} ?action=firstrun-complete	Complete the first-run wizard.
		 * @apiName FirstRunComplete
		 * @apiGroup Settings
		 * @apiPermission Anonymous
		 *
		 * @apiParam	{string}	username		The username for the first admin account
		 * @apiParam	{string}	password		The password for the first admin account
		 * @apiParam	{string}	password-again	The password repeated for the first admin account
		 * @apiParam	{string}	email-address	The email address for the first admin account
		 * @apiParam	{string}	wiki-name		The name of the wiki. Saved to $settings->sitename
		 * @apiParam	{string}	data-dir		The directory on the server to save the wiki data to. Saved to $settings->data_storage_dir.
		 */
		add_action("firstrun-complete", function() {
			global $version, $commit, $settings;
			
			if($settings->firstrun_complete) {
				http_response_code(400);
				exit(page_renderer::render_main("Setup complete - Error - $settings->sitename", "<p>Oops! Looks like $settings->sitename is already setup and ready to go! Go to the <a href='?action=$settings->defaultaction&page=".rawurlencode($settings->defaultpage)."'>" . htmlentities($settings->defaultpage)."</a> to get started!</p>"));
			}
			
			if($_POST["secret"] !== $settings->secret) {
				http_response_code(401);
				exit(page_renderer::render_main("Incorrect secret - Pepperminty Wiki", "<p>Oops! That secret was incorrect. Open <code>peppermint.json</code> that is automatically written to the directory alongside the <code>index.php</code> that you uploaded to your web server and copy the value of the <code>secret</code> property into the wiki secret box on the previous page, taking care to avoid copying the quotation marks.</p>"));
			}
			
			// $_POST: username, email-address, password, password-again, wiki-name, data-dir
			
			if(empty($_POST["username"])) {
				http_response_code(400);
				exit(page_renderer::render_main("Missing information - Error - Pepperminty Wiki", "<p>Oops! Looks like you forgot to enter a username. Try going back in your browser and filling one in.</p>"));
			}
			if(empty($_POST["email-address"])) {
				http_response_code(400);
				exit(page_renderer::render_main("Missing information - Error - Pepperminty Wiki", "<p>Oops! Looks like you forgot to enter an email address. Try going back in your browser and filling one in.</p>"));
			}
			if(filter_var($_POST["email-address"], FILTER_VALIDATE_EMAIL) === false) {
				http_response_code(400);
				exit(page_renderer::render_main("Invalid email address - Error - Pepperminty Wiki", "<p>Oops! Looks like that email address isn't valid. Try going back in your browser and correcting it.</p>"));
			}
			if(empty($_POST["password"]) || empty($_POST["password-again"])) {
				http_response_code(400);
				exit(page_renderer::render_main("Missing information - Error - Pepperminty Wiki", "<p>Oops! Looks like you forgot to enter a password. Try going back in your browser and filling one in.</p>"));
			}
			if($_POST["password"] !== $_POST["password-again"]) {
				http_response_code(422);
				exit(page_renderer::render_main("Password mismatch - Error - Pepperminty Wiki", "<p>Oops! Looks like the passwords you entered aren't the same. Try going back in your browser and entering it again.</p>"));
			}
			if(empty($_POST["wiki-name"])) {
				http_response_code(400);
				exit(page_renderer::render_main("Missing information - Error - Pepperminty Wiki", "<p>Oops! Looks like you forgot to enter a name for your wiki. Try going back in your browser and filling one in.</p>"));
			}
			if(empty($_POST["data-dir"])) {
				http_response_code(400);
				exit(page_renderer::render_main("Missing information - Error - Pepperminty Wiki", "<p>Oops! Looks like you forgot to enter a directory on the server to store the wiki's data in. Try going back in your browser and filling one in. Relative paths are ok - the default is <code>.</code> (i.e. the current directory).</p>"));
			}
			
			// Generate the user data object & replace the pre-generated users
			$user_data = new stdClass();
			$user_data->password = hash_password($_POST["password"]);
			$user_data->emailAddress = $_POST["email-address"];
			$settings->users = new stdClass();
			$settings->users->{$_POST["username"]} = $user_data;
			$settings->admins = [ $_POST["username"] ]; // Don't forget to mark them as a mod
			
			// Apply the settings
			$settings->firstrun_complete = true;
			$settings->sitename = htmlentities($_POST["wiki-name"]);
			$settings->data_storage_dir = $_POST["data-dir"];
			
			if(!save_settings()) {
				http_response_code(500);
				exit(page_renderer::render_main("Server Error - Pepperminty Wiki", "<p>Oops! Pepperminty Wiki was unable to save your settings back to disk. This can happen if Pepperminty Wiki does not have write permissions on it's own directory and the files contained within (except <code>index.php</code> of course).</p>
				<p>Try contacting your server owner and ask them to correct it. If you are the server owner, you may need to run <code>sudo chown -R WEBSERVER_USERNAME:WEBSERVER_USERNAME PATH/TO/WIKI/DIRECTORY</code>, replacing the bits in UPPERCASE.</p>"));
			}
			
			http_response_code(201);
			exit(page_renderer::render_main("Setup complete! - Pepperminty Wiki", "<p>Congratulations! You've completed the Pepperminty Wiki setup.</p>
			<p><a href='?action=$settings->defaultaction'>Click here</a> to start using $settings->sitename, your new wiki!</p>"));
		});
	}
]);


function do_system_checks() {
	$checks = [
		function_exists("mb_strpos")
			? [true, "php-mbstring is installed"]
			: [false, "php-mbstring is not installed"],
		class_exists("Transliterator") && class_exists("Collator")
			? [true, "php-intl is installed for item sorting, search indexing, and sending non-utf8 emails"]
			: [false, "php-intl is not installed (needed for item sorting, search indexing, and sending non-utf8 emails)"]
	];
	if(module_exists("feature-upload")) {
		$checks[] = class_exists("Imagick") 
			? [true, "php-imagick is installed for preview generation"]
			: [false, "php-imagick is not installed (needed for image preview generation)", "optional"];
		$checks[] = function_exists("finfo_file") 
			? [true, "php-fileinfo is installed for upload file type checking"]
			: [false, "php-fileinfo is not installed (needed for file type checking on uploads)", "optional"];
	}
	if(module_exists("page-export"))
		$checks[] = class_exists("ZipArchive")
			? [true, "php-zip is installed for compressing exports"]
			: [false, "php-zip is not install (needed for compressing exports)", "optional"];
	if(module_exists("lib-search-engine") or module_exists("feature-search-didyoumean"))
		$checks[] = extension_loaded("sqlite3")
			? [true, "php-sqlite3 is installed for search indexing"]
			: [false, "php-sqlite3 is not installed (needed for search indexing)", "optional"];
	
	return $checks;
}


/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Settings GUI",
	"version" => "0.1.8",
	"author" => "Starbeamrainbowlabs",
	"description" => "The module everyone has been waiting for! Adds a web based gui that lets mods change the wiki settings.",
	"id" => "feature-guiconfig",
	"code" => function() {
		global $settings;
		/**
		 * @api {get} ?action=configure Get a page to change the global wiki settings
		 * @apiName ConfigureSettings
		 * @apiGroup Utility
		 * @apiPermission Moderator
		 */
		
		/*
		 *  ██████  ██████  ███    ██ ███████ ██  ██████  ██    ██ ██████  ███████
		 * ██      ██    ██ ████   ██ ██      ██ ██       ██    ██ ██   ██ ██
		 * ██      ██    ██ ██ ██  ██ █████   ██ ██   ███ ██    ██ ██████  █████
		 * ██      ██    ██ ██  ██ ██ ██      ██ ██    ██ ██    ██ ██   ██ ██
		 *  ██████  ██████  ██   ████ ██      ██  ██████   ██████  ██   ██ ███████
 	 	 */
		add_action("configure", function() {
			global $settings, $env, $guiConfig, $version, $commit;
			
			if(!$env->is_admin)
			{
				$errorMessage = "<p>You don't have permission to change $settings->sitename's master settings.</p>\n";
				if(!$env->is_logged_in)
					$errorMessage .= "<p>You could try <a href='?action=login&returnto=%3Faction%3Dconfigure'>logging in</a>.</p>";
				else
					$errorMessage .= "<p>You could try <a href='?action=logout&returnto=%3Faction%3Dconfigure'>logging out</a> and then <a href='?action=login&returnto=%3Faction%3Dconfigure'>logging in</a> again with a different account that has the appropriate privileges.</a>.</p>";
				exit(page_renderer::render_main("Error - $settings->sitename", $errorMessage));
			}
			
			$content = "<h1>Master Control Panel</h1>\n";
			$content .= "<p>This page lets you configure $settings->sitename's master settings. Please be careful - you can break things easily on this page if you're not careful!</p>\n";
			if(module_exists("feature-user-table"))
				$content .= "<p><em>Looking to manage $settings->sitename's users? Try the <a href='?action=user-table'>user table</a> instead!</em></p>\n";
			if(module_exists("feature-theme-gallery"))
				$content .= "<p><em>Want to change $settings->sitename's theme? Try the <a href='?action=theme-gallery'>theme gallery</a>!</em></p>";
			$content .= "<p>You're currently running Pepperminty Wiki $version+" . substr($commit, 0, 7) . ".</p>\n";
			$content .= "<h2>Actions</h2>";
			
			$content .= "<button class='action-invindex-rebuild' title='Rebuilds the index that is consulted when searching the wiki. Hit this button if some pages are not showing up.'>Rebuild Search Index</button>\n";
			$content .= "<progress class='action-invindex-rebuild-progress' min='0' max='100' value='0' style='display: none;'></progress><br />\n";
			$content .= "<output class='action-invindex-rebuild-latestmessage'></output><br />\n";
			
			$invindex_rebuild_script = <<<SCRIPT
window.addEventListener("load", function(event) {
	document.querySelector(".action-invindex-rebuild").addEventListener("click", function(event) {
		var rebuildActionEvents = new EventSource("?action=invindex-rebuild");
		var latestMessageElement = document.querySelector(".action-invindex-rebuild-latestmessage");
		var progressElement = document.querySelector(".action-invindex-rebuild-progress");
		rebuildActionEvents.addEventListener("message", function(event) {
			console.log(event);
			let message = event.data; 
			latestMessageElement.value = event.data;
			let parts = message.match(/^\[\s*(\d+)\s+\/\s+(\d+)\s*\]/);
			if(parts != null) {
				progressElement.style.display = "";
				progressElement.min = 0;
				progressElement.max = parseInt(parts[2]);
				progressElement.value = parseInt(parts[1]);
			}
			if(message.startsWith("Done! Saving new search index to"))
				rebuildActionEvents.close();
		});
		// Close the connection on error & don't try again
		rebuildActionEvents.addEventListener("error", (_event) => rebuildActionEvents.close());
	});
});
SCRIPT;

			page_renderer::add_js_snippet($invindex_rebuild_script);
			
			$content .= "<h2>Settings</h2>";
			$content .= "<p>Mouse over the name of each setting to see a description of what it does.</p>\n";
			$content .= "<form action='?action=configure-save' method='post'>\n";
			
			foreach($guiConfig as $configKey => $configData)
			{
				// Don't display the site secret~!
				// Apparently it got lost in translation, but I'll be re-adding
				// it again at some point I'm sure - so support for it is
				// included here.
				if($configKey == "sitesecret") continue;
				
				$reverse = false;
				$inputControl = "";
				$label = "<label for='setting-$configKey' title=\"$configData->description\" class='cursor-query'>$configKey</label>";
				switch($configData->type)
				{
					case "url":
					case "email":
					case "number":
					case "text":
						$inputControl = "<input type='$configData->type' id='$configKey' name='$configKey' value='".htmlentities($settings->$configKey)."' />";
						break;
					case "textarea":
						$inputControl = "<textarea id='$configKey' name='$configKey'>".htmlentities($settings->$configKey)."</textarea>";
						break;
					case "checkbox":
						$reverse = true;
						$inputControl = "<input type='checkbox' id='$configKey' name='$configKey' " . ($settings->$configKey ? " checked" : "") . " />";
						break;
					case "usertable":
						$label = "";
						if(module_exists("feature-user-table"))
							$inputControl = "<p>The users can be managed in the <a href='?action=user-table'>User Table</a>.</p>";
						else
							$inputControl = "<p><em>The users can be managed in the user table, but the required module <code>feature-user-table</code> is not installed.</em></p>";
						break;
					default:
						$label = "";
						$inputControl = "<p><em>Sorry! The <code>".htmlentities($configKey)."</code> setting isn't editable yet through the gui. Please try editing <code>peppermint.json</code> for the time being.</em></p>";
						break;
				}
				
				$content .= "<div class='setting-configurator'>\n\t";
				$content .= $reverse ? "$inputControl\n\t$label" : "$label\n\t$inputControl";
				$content .= "\n</div>\n";
			}
			
			$content .= "<input type='submit' value='Save Settings' />";
			$content .= "</form>\n";
			
			exit(page_renderer::render_main("Master Control Panel - $settings->sitename", $content));
		});
		
		/**
		 * @api {post} ?action=configure-save Save changes to the global wiki settings
		 * @apiName ConfigureSettings
		 * @apiGroup Utility
		 * @apiPermission Moderator
		 */
		
		/*
		 *  ██████  ██████  ███    ██ ███████ ██  ██████  ██    ██ ██████  ███████
		 * ██      ██    ██ ████   ██ ██      ██ ██       ██    ██ ██   ██ ██
		 * ██      ██    ██ ██ ██  ██ █████   ██ ██   ███ ██    ██ ██████  █████ █████
		 * ██      ██    ██ ██  ██ ██ ██      ██ ██    ██ ██    ██ ██   ██ ██
		 *  ██████  ██████  ██   ████ ██      ██  ██████   ██████  ██   ██ ███████
		 * ███████  █████  ██    ██ ███████
		 * ██      ██   ██ ██    ██ ██
		 * ███████ ███████ ██    ██ █████
		 *      ██ ██   ██  ██  ██  ██
		 * ███████ ██   ██   ████   ███████
		 */
		add_action("configure-save", function () {
			global $env, $settings, $paths, $defaultCSS;
			
		    // If the user isn't an admin, then the regular configuration page will display an appropriate error
			if(!$env->is_admin)
			{
				http_response_code(307);
				header("location: ?action=configure");
				exit();
			}
			
			// Build a new settings object
			$newSettings = new stdClass();
			foreach($settings as $configKey => $rawValue)
			{
				$configValue = $rawValue;
				if(isset($_POST[$configKey]))
				{
					$decodedConfigValue = json_decode($_POST[$configKey]);
					if(json_last_error() === JSON_ERROR_NONE)
						$configValue = $decodedConfigValue;
					else
						$configValue = $_POST[$configKey];
					
					// Convert bool settings to a bool, since POST
					// parameters don't decode correctly.
					if(is_bool($settings->$configKey))
						$configValue = in_array($configValue, [ 1, "on"], true) ? true : false;
					
					// If the CSS hasn't changed, then we can replace it with
					// 'auto' - this will ensure that upon update the new
					// default CSS will be used. Also make sure we ignore line
					// ending nonsense & differences here, since they really
					// don't matter
					if($configKey === "css" && str_replace("\r\n", "\n", $defaultCSS) === str_replace("\r\n", "\n", $configValue))
						$configValue = "auto";
				}
				
				$newSettings->$configKey = $configValue;
			}
			
			// Take a backup of the current settings file
			rename($paths->settings_file, "$paths->settings_file.bak");
			// Save the new settings file
			file_put_contents($paths->settings_file, json_encode($newSettings, JSON_PRETTY_PRINT));
			
			$content = "<h1>Master settings updated sucessfully</h1>\n";
			$content .= "<p>$settings->sitename's master settings file has been updated successfully. A backup of the original settings has been created under the name <code>peppermint.json.bak</code>, just in case. You can <a href='?action=configure'>go back</a> and continue editing the master settings file, or you can go to the <a href='?action=view&page=" . rawurlencode($settings->defaultpage) . "'>" . htmlentities($settings->defaultpage) . "</a>.</p>\n";
			$content .= "<p>For reference, the newly generated master settings file is as follows:</p>\n";
			$content .= "<textarea name='content'>";
				$content .= json_encode($newSettings, JSON_PRETTY_PRINT);
			$content .= "</textarea>\n";
			exit(page_renderer::render_main("Master Settings Updated - $settings->sitename", $content));
		});
		
		add_help_section("800-raw-page-content", "Viewing Raw Page Content", "<p>Although you can use the edit page to view a page's source, you can also ask $settings->sitename to send you the raw page source and nothing else. This feature is intented for those who want to automate their interaction with $settings->sitename.</p>
		<p>To use this feature, navigate to the page for which you want to see the source, and then alter the <code>action</code> parameter in the url's query string to be <code>raw</code>. If the <code>action</code> parameter doesn't exist, add it. Note that when used on an file's page this action will return the source of the description and not the file itself.</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Page History",
	"version" => "0.4.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds the ability to keep unlimited page history, limited only by your disk space. Note that this doesn't store file history (yet). Currently depends on feature-recent-changes for rendering of the history page.",
	"id" => "feature-history",
	"code" => function() {
		/**
		 * @api {get} ?action=history&page={pageName}[&format={format}] Get a list of revisions for a page
		 * @apiName History
		 * @apiGroup Page
		 * @apiPermission Anonymous
		 * 
		 * @apiUse PageParameter
		 * @apiParam {string}	format	The format to return the list of pages in. available values: html, json, text. Default: html
		 */
		
		/*
		 * ██   ██ ██ ███████ ████████  ██████  ██████  ██    ██
		 * ██   ██ ██ ██         ██    ██    ██ ██   ██  ██  ██
		 * ███████ ██ ███████    ██    ██    ██ ██████    ████
		 * ██   ██ ██      ██    ██    ██    ██ ██   ██    ██
		 * ██   ██ ██ ███████    ██     ██████  ██   ██    ██
		 */
		add_action("history", function() {
			global $settings, $env, $pageindex;
			
			$supported_formats = [ "html", "json", "text" ];
			$format = $_GET["format"] ?? "html";
			
			switch($format) {
				case "html":
					$content = "<h1>History for $env->page_safe</h1>\n";
					if(!empty($pageindex->{$env->page}->history))
					{
						$content .= "\t\t<ul class='page-list'>\n";
						foreach(array_reverse($pageindex->{$env->page}->history) as $revisionData)
						{
							// Only display edits & reverts for now
							if(!in_array($revisionData->type, [ "edit", "revert" ]))
								continue;
							
							// The number (and the sign) of the size difference to display
							$size_display = ($revisionData->sizediff > 0 ? "+" : "") . $revisionData->sizediff;
							$size_display_class = $revisionData->sizediff > 0 ? "larger" : ($revisionData->sizediff < 0 ? "smaller" : "nochange");
							if($revisionData->sizediff > 500 or $revisionData->sizediff < -500)
							$size_display_class .= " significant";
							$size_title_display = human_filesize($revisionData->newsize - $revisionData->sizediff) . " -> " .  human_filesize($revisionData->newsize);
							
							$content .= "\t\t\t<li>";
							$content .= "<a href='?page=" . rawurlencode($env->page) . "&revision=$revisionData->rid'>#$revisionData->rid</a> " . render_editor(page_renderer::render_username($revisionData->editor)) . " " . render_timestamp($revisionData->timestamp) . " <span class='cursor-query $size_display_class' title='$size_title_display'>($size_display)</span>";
							if($env->is_logged_in || ($settings->history_revert_require_moderator && $env->is_admin && $env->is_logged_in))
								$content .= " <small>(<a class='revert-button' href='?action=history-revert&page=" . rawurlencode($env->page) . "&revision=$revisionData->rid'>restore this revision</a>)</small>";
							$content .= "</li>\n";
						}
						$content .= "\t\t</ul>";
					}
					else
					{
						$content .= "<p style='text-align: center;'><em>(None yet! Try editing this page and then coming back here.)</em></p>\n";
					}
					exit(page_renderer::render_main("$env->page - History - $settings->sitename", $content));
				
				case "json":
					$page_history = $pageindex->{$env->page}->history ?? [];
					
					foreach($page_history as &$history_entry) {
						unset($history_entry->filename);
					}
					header("content-type: application/json");
					exit(json_encode($page_history, JSON_PRETTY_PRINT));
				
				case "csv":
					$page_history = $pageindex->{$env->page}->history ?? [];
					
					header("content-type: text/csv");
					echo("revision_id,timestamp,type,editor,newsize,sizediff\n");
					foreach($page_history as $hentry) {
						echo("$hentry->rid,$hentry->timestamp,$hentry->type,$hentry->editor,$hentry->newsize,$hentry->sizediff\n");
					}
					exit();
				
				default:
					http_response_code(400);
					exit(page_renderer::render_main("Format Error - $env->page - History - $settings->sitename", "<p>The format <code>" . htmlentities($format) . "</code> isn't currently supported. Supported formats: html, json, csv"));
			}
			
		});
		
		/**
		 * @api {get} ?action=history-revert&page={pageName}&revision={rid}	Revert a page to a previous version
		 * @apiName HistoryRevert
		 * @apiGroup Editing
		 * @apiPermission User
		 * @apiUse	PageParameter
		 * @apiUse	UserNotLoggedInError
		 * @apiUse	UserNotModeratorError
		 * 
		 * @apiParam {string}	revision	The page revision number to revert to.
		 */
		/*
		 * ██   ██ ██ ███████ ████████  ██████  ██████  ██    ██
		 * ██   ██ ██ ██         ██    ██    ██ ██   ██  ██  ██
		 * ███████ ██ ███████    ██    ██    ██ ██████    ████  █████
		 * ██   ██ ██      ██    ██    ██    ██ ██   ██    ██
		 * ██   ██ ██ ███████    ██     ██████  ██   ██    ██
		 * 
		 * ██████  ███████ ██    ██ ███████ ██████  ████████
		 * ██   ██ ██      ██    ██ ██      ██   ██    ██
		 * ██████  █████   ██    ██ █████   ██████     ██
		 * ██   ██ ██       ██  ██  ██      ██   ██    ██
		 * ██   ██ ███████   ████   ███████ ██   ██    ██
		 */
		add_action("history-revert", function() {
			global $env, $settings, $pageindex;
			
			if((!$env->is_admin && $settings->history_revert_require_moderator) ||
				!$env->is_logged_in) {
				http_response_code(401);
				exit(page_renderer::render_main("Unauthorised - $settings->sitename", "<p>You can't revert pages to a previous revision because " . ($settings->history_revert_require_moderator && $env->is_logged_in ? "you aren't logged in as a moderator. You can try <a href='?action=logout'>logging out</a> and then" : "you aren't logged in. You can try") . " <a href='?action=login&returnto=" . rawurlencode("?action=history-revert&revision={$env->history->revision_number}&page=" . rawurlencode($env->page)) . "'>logging in</a>.</p>"));
			}
			
			$current_revision_filepath = "$env->storage_prefix/{$pageindex->{$env->page}->filename}";
			
			// Figure out what we're saving
			$newsource = file_get_contents($env->page_filename); // The old revision content - the Pepperminty Wiki core sorts this out for us
			$oldsource = file_get_contents($current_revision_filepath); // The current revision's content
			
			// Save the old content over the current content
			file_put_contents($current_revision_filepath, $newsource);
			
			// NOTE: We don't run the save preprocessors here because they are run when a page is edited - reversion is special and requires different treatment.
			// FUTURE: We may want ot refactor the save preprocessor system ot take a single object instead - then we can add as many params as we like and we could execute the save preprocessors as normal :P
			
			// Add the old content as a new revision
			$result = history_add_revision(
				$pageindex->{$env->page},
				$newsource,
				$oldsource,
				true, // Yep, go ahead and save the page index
				"revert" // It's a revert, not an edit
			);
			
			// Update the redirect metadata, if the redirect module is installed
			if(module_exists("feature-redirect"))
				update_redirect_metadata($pageindex->{$env->page}, $newsource);
			
			// Add an entry to the recent changes log, if the module exists
			if($result !== false && module_exists("feature-recent-changes"))
				add_recent_change([
					"type" => "revert",
					"timestamp" => time(),
					"page" => $env->page,
					"user" => $env->user,
					"newsize" => strlen($newsource),
					"sizediff" => strlen($newsource) - strlen($oldsource)
				]);
			
			if($result === false) {
				http_response_code(503);
				exit(page_renderer::render_main("Server Error - Revert - $settings->sitename", "<p>A server error occurred when $settings->sitename tried to save the reversion of <code>$env->page_safe</code>. Please contact $settings->sitename's administrator $settings->admindetails_name, whose email address can be found at the bottom of every page (including this one).</p>"));
			}
			
			http_response_code(201);
			exit(page_renderer::render_main("Reverting $env->page - $settings->sitename", "<p>$env->page_safe has been reverted back to revision {$env->history->revision_number} successfully.</p>
			<p><a href='?page=" . rawurlencode($env->page) . "'>Go back</a> to the page, or continue <a href='?action=history&page = " . rawurlencode($env->page) . "'>reviewing its history</a>.</p>"));
			
			// $env->page_filename
			// 
		});
		
		register_save_preprocessor("history_add_revision");
		
		if(module_exists("feature-stats")) {
			statistic_add([
				"id" => "history_most_revisions",
				"name" => "Most revised page",
				"type" => "scalar",
				"update" => function($old_stats) {
					global $pageindex;
					
					$target_pagename = "";
					$target_revisions = -1;
					foreach($pageindex as $pagename => $pagedata) {
						if(!isset($pagedata->history))
							continue;
						
						$revisions_count = count($pagedata->history);
						if($revisions_count > $target_revisions) {
							$target_revisions = $revisions_count;
							$target_pagename = $pagename;
						}
					}
					
					$result = new stdClass(); // completed, value, state
					$result->completed = true;
					$result->value = "(no revisions saved yet)";
					if($target_revisions > -1) {
						$result->value = "$target_revisions (<a href='?page=" . rawurlencode($target_pagename) . "'>" . htmlentities($target_pagename) . "</a>)";
					}
					
					return $result;
				}
			]);
		}
	}
]);

/**
 * Adds a history revision against a page.
 * Note: Does not update the current page content! This function _only_ 
 * records a new revision against a page name. Thus it is possible to have a 
 * disparity between the history revisions and the actual content displayed in 
 * the current revision if you're not careful!
 * @package	feature-history
 * @param	object	$pageinfo		The pageindex object of the page to operate on.
 * @param	string	$newsource		The page content to save as the new revision.
 * @param	string	$oldsource		The old page content that is the current revision (before the update).
 * @param	bool	$save_pageindex	Whether the page index should be saved to disk.
 * @param	string	$change_type	The type of change to record this as in the history revision log
 */
function history_add_revision(&$pageinfo, &$newsource, &$oldsource, $save_pageindex = true, $change_type = "edit") {
	global $env, $paths, $settings, $pageindex;
	
	if(!isset($pageinfo->history))
		$pageinfo->history = [];
	
	// Save the *new source* as a revision
	// This results in 2 copies of the current source, but this is ok
	// since any time someone changes something, it creates a new revision
	// Note that we can't save the old source here because we'd have no
	// clue who edited it since $pageinfo has already been updated by
	// this point
	
	// TODO Store tag changes here
	// Calculate the next revision id - we can't just count the revisions here because we might have a revision limit
	$nextRid = !empty($pageinfo->history) ? end($pageinfo->history)->rid + 1 : 0;
	$ridFilename = "$pageinfo->filename.r$nextRid";
	// Insert a new entry into the history
	$pageinfo->history[] = [
		"type" => $change_type, // We might want to store other types later (e.g. page moves)
		"rid" => $nextRid,
		"timestamp" => time(),
		"filename" => $ridFilename,
		"newsize" => strlen($newsource),
		"sizediff" => strlen($newsource) - strlen($oldsource),
		"editor" => $pageinfo->lasteditor
	];
	
	// Save the new source as a revision
	$result = file_put_contents("$env->storage_prefix$ridFilename", $newsource);
	
	if($result !== false &&
		$settings->history_max_revisions > -1) {
		while(count($pageinfo->history) > $settings->history_max_revisions) {
			// We've got too many revisions - trim one off & delete it
			$oldest_revision = array_shift($pageinfo->history);
			unlink("$env->storage_prefix/$oldest_revision->filename");
		}
	}
	
	// Save the edited pageindex
	if($result !== false && $save_pageindex)
		$result = save_pageindex();
	
	
	return $result;
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Interwiki links",
	"version" => "0.1.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds interwiki link support. Set the interwiki_index_location setting at an index file to activate support.",
	"id" => "feature-interwiki-links",
	"code" => function() {
		global $env, $settings, $paths;
		if(!empty($settings->interwiki_index_location)) {
			// Generate the interwiki index cache file if it doesn't exist already
			// NOTE: If you want to update the cache file, just delete it & it'll get regenerated automagically :-)
			if(!file_exists($paths->interwiki_index))
				interwiki_index_update();
			else
				$env->interwiki_index = json_decode(file_get_contents($paths->interwiki_index));
		}
		
		$doc_help = "<p>$settings->sitename doesn't currently support interwiki links, but if you'd like it to, please contact ".htmlentities($settings->admindetails_name)." ($settings->sitename's administrator) through their contact details at the bottom of every page and point them at <a href='https://starbeamrainbowlabs.com/labs/peppermint/_docpress/06.5-Interwiki-Links.html'>the documentation on how to set it up</a>. It's really easy, and they can always <a href='https://github.com/sbrl/Pepperminty-Wiki/issues/new'>open an issue</a> if they get stuck :-)</p>\n";
		if(!empty($env->interwiki_index)) {
			$doc_help = <<<HELP_BLOCK
<p>$settings->sitename supports inter-wiki links. Such a link sends the user elsewhere on the internet. By prefixing a page name with a prefix, the convenience of the internal link syntax described above can be exploited to send users elsewhere without having to type out full urls! Here are few examples (note that these prefixes are only examples, and probably aren't available on $settings->sitename - check the list below for supported prefixes):</p>

<pre><code>[[another_wiki:Apples]]
[[trees:Apple Trees]]
[[history:The Great Rainforest|rainforest]]
[[any prefix here:page name|Display text]]
</code></pre>

<p>Note that unlike normal internal links, the page name is case-sensitive and can't be case-corrected automatically. The wikis supported by $settings->sitename are as follows:</p>

{supported_interwikis}

<p>This list can be edited by $settings->admindetails_name, $settings->sitename's administrator. Documentation on how to do that is <a href='https://starbeamrainbowlabs.com/labs/peppermint/__nightdocs/06.5-Interwiki-Links.html'>available here</a>.</p>
HELP_BLOCK;

			$doc_help_insert = "<table><tr><th>Name</th><th>Prefix</th>\n";
			foreach($env->interwiki_index as $interwiki_def)
				$doc_help_insert .= "<tr><td>".htmlentities($interwiki_def->name)."</td><td><code>$interwiki_def->prefix</code></td></tr>\n";
			$doc_help_insert .= "</table>";
			
			$doc_help = str_replace("{supported_interwikis}", $doc_help_insert, $doc_help);
		}
		
		add_help_section("22-interwiki-links", "Interwiki Links", $doc_help);
	}
]);

/**
 * Updates the interwiki index cache file.
 * If the interwiki_index_location isn't defined, then this function will do
 * nothing.
 */
function interwiki_index_update() {
	global $env, $settings, $paths;
	
	if(empty($settings->interwiki_index_location))
		return;
	
	$env->interwiki_index = new stdClass();
	$interwiki_csv_handle = fopen($settings->interwiki_index_location, "r");
	if($interwiki_csv_handle === false)
		throw new Exception("Error: Failed to read interwiki index from '{$settings->interwiki_index_location}'.");
	
	fgetcsv($interwiki_csv_handle); // Discard the header line
	while(($interwiki_data = fgetcsv($interwiki_csv_handle))) {
		$interwiki_def = new stdClass();
		$interwiki_def->name = $interwiki_data[0];
		$interwiki_def->prefix = $interwiki_data[1];
		$interwiki_def->root_url = $interwiki_data[2];
		
		$env->interwiki_index->{$interwiki_def->prefix} = $interwiki_def;
	}
	
	file_put_contents($paths->interwiki_index, json_encode($env->interwiki_index, JSON_PRETTY_PRINT));
}

/**
 * Parses an interwiki pagename into it's component parts.
 * @package interwiki-links
 * @param  string	$interwiki_pagename	The interwiki pagename to parse.
 * @return string[]	An array containing the parsed components of the interwiki pagename, in the form ["prefix", "page_name"].
 */
function interwiki_pagename_parse($interwiki_pagename) {
	if(strpos($interwiki_pagename, ":") === false)
		return null;
	$result = explode(":", $interwiki_pagename, 2);
	return array_map("trim", $result);
}

/**
 * Resolves an interwiki pagename to the associated
 * interwiki definition object.
 * @package interwiki-links
 * @param	string		$interwiki_pagename	An interwiki pagename. Should be in the form "prefix:page name".
 * @return	stdClass	The interwiki definition object.
 */
function interwiki_pagename_resolve($interwiki_pagename) {
	global $env;
	
	if(empty($env->interwiki_index))
		return null;
	
	// If it's not an interwiki link, then don't bother confusing ourselves
	if(strpos($interwiki_pagename, ":") === false)
		return null;
	
	[$prefix, $pagename] = interwiki_pagename_parse($interwiki_pagename); // Shorthand destructuring - introduced in PHP 7.1
	
	if(empty($env->interwiki_index->$prefix))
		return null;
	
	return $env->interwiki_index->$prefix;
}
/**
 * Converts an interwiki pagename into a url.
 * @package interwiki-links
 * @param	string	$interwiki_pagename		The interwiki pagename (in the form "prefix:page name")
 * @return	string	A url that points to the specified interwiki page.
 */
function interwiki_get_pagename_url($interwiki_pagename) {
	$interwiki_def = interwiki_pagename_resolve($interwiki_pagename);
	if($interwiki_def == null)
		return null;
	
	[$prefix, $pagename] = interwiki_pagename_parse($interwiki_pagename);
	
	return str_replace(
		"%s", rawurlencode($pagename),
		$interwiki_def->root_url
	);
}

/**
 * Returns whether a given pagename is an interwiki link or not.
 * Note that this doesn't guarantee that it's a _valid_ interwiki link - only that it looks like one :P
 * @package interwiki-links
 * @param	string	$pagename	The page name to check.
 * @return	bool	Whether the given page name is an interwiki link or not.
 */
function is_interwiki_link($pagename) {
	return strpos($pagename, ":") !== false;
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Reading time estimator",
	"version" => "0.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Displays the approximate reading time for a page beneath it's title.",
	"id" => "feature-readingtime",
	"code" => function() {
		
		page_renderer::register_part_preprocessor(function(&$parts) {
			global $env, $settings;
			// Only insert for the view action
			if($env->action !== $settings->readingtime_action || !$settings->readingtime_enabled)
				return;
			
			$reading_time = estimate_reading_time(
				file_get_contents($env->page_filename),
				$settings->readingtime_language
			);
			
			$insert = "<small><em>{$reading_time[0]} - {$reading_time[1]} minute read</em></small>";
			if($reading_time[0] === $reading_time[1])
				$insert = "<small><em>{$reading_time[0]} minute read</em></small>";
			
			// TODO: Create a canonical way to insert something just below the header - this might be tough though 'cause the that isn't handled by the page_renderer though
			$insert = "\n\t\t\t<p class='system-text-insert readingtime-estimate'>$insert</p>";
			$parts["{content}"] = substr_replace(
				$parts["{content}"],
				"</h1>$insert",
				strpos($parts["{content}"], "</h1>"),
				5
			);
		});
	}
]);

/**
 * Estimates the reading time for a given lump of text.
 * Ref https://github.com/sbrl/Pepperminty-Wiki/issues/172 (has snippet of
 * original code from Firefox & link to study from which the numbers are
 * taken).
 * @param	string	$text	The text to estimate for.
 * @param	string	$lang	The language code of the text - defaults to "en"
 * @return	array	An array in the form [ low_time, high_time ] in minutes
 */
function estimate_reading_time(string $text, string $lang = "en") : array {
	$chars_count = mb_strlen(preg_replace("/\s+?/", "", strtr($text, [
		"[" => "", "]" => "", "(" => "", ")" => "",
		"|" => "", "#" => "", "*" => ""
	])));
	$langs = [
		"en" => (object) [ "cpm" => 987, "variance" => 118 ],
		"ar" => (object) [ "cpm" => 612, "variance" => 88 ],
		"de" => (object) [ "cpm" => 920, "variance" => 86 ],
		"es" => (object) [ "cpm" => 1025, "variance" => 127 ],
		"fi" => (object) [ "cpm" => 1078, "variance" => 121 ],
		"fr" => (object) [ "cpm" => 998, "variance" => 126 ],
		"he" => (object) [ "cpm" => 833, "variance" => 130 ],
		"it" => (object) [ "cpm" => 950, "variance" => 140 ],
		"jw" => (object) [ "cpm" => 357, "variance" => 56 ],
		"nl" => (object) [ "cpm" => 978, "variance" => 143 ],
		"pl" => (object) [ "cpm" => 916, "variance" => 126 ],
		"pt" => (object) [ "cpm" => 913, "variance" => 145 ],
		"ru" => (object) [ "cpm" => 986, "variance" => 175 ],
		"sk" => (object) [ "cpm" => 885, "variance" => 145 ],
		"sv" => (object) [ "cpm" => 917, "variance" => 156 ],
		"tr" => (object) [ "cpm" => 1054, "variance" => 156 ],
		"zh" => (object) [ "cpm" => 255, "variance" => 29 ],
	];
	if(!isset($langs[$lang]))
		return null;
	
	return [
		ceil($chars_count / ($langs[$lang]->cpm + $langs[$lang]->variance)),
		ceil($chars_count / ($langs[$lang]->cpm - $langs[$lang]->variance))
	];
}


/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Recent Changes",
	"version" => "0.5.3",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds recent changes. Access through the 'recent-changes' action.",
	"id" => "feature-recent-changes",
	"code" => function() {
		global $settings, $env, $paths;
		
		// Add the recent changes json file to $paths for convenience.
		$paths->recentchanges = $env->storage_prefix . "recent-changes.json";
		// Create the recent changes json file if it doesn't exist
		if(!file_exists($paths->recentchanges))
			file_put_contents($paths->recentchanges, "[]");
		
		/**
		 * @api {get} ?action=recent-changes[&offset={number}][&count={number}][&format={code}] Get a list of recent changes
		 * @apiName RecentChanges
		 * @apiGroup Stats
		 * @apiPermission Anonymous
		 *
		 * @apiParam	{number}	offset	If specified, start returning changes from this many changes in. 0 is the beginning.
		 * @apiParam	{number}	count	If specified, return at most this many changes. A value of 0 means no limit (the default) - apart from the limit on the number of changes stored by the server (configurable in pepppermint.json).
		 * @apiParam	{string}	format	The format to return the recent changes in. Valid values: html, json, csv, atom. Default: html.
		 */
		/*
		 * ██████  ███████  ██████ ███████ ███    ██ ████████         
		 * ██   ██ ██      ██      ██      ████   ██    ██            
		 * ██████  █████   ██      █████   ██ ██  ██    ██            
		 * ██   ██ ██      ██      ██      ██  ██ ██    ██            
		 * ██   ██ ███████  ██████ ███████ ██   ████    ██            
		 * 
		 *  ██████ ██   ██  █████  ███    ██  ██████  ███████ ███████ 
		 * ██      ██   ██ ██   ██ ████   ██ ██       ██      ██      
		 * ██      ███████ ███████ ██ ██  ██ ██   ███ █████   ███████ 
		 * ██      ██   ██ ██   ██ ██  ██ ██ ██    ██ ██           ██ 
		 *  ██████ ██   ██ ██   ██ ██   ████  ██████  ███████ ███████
		 */
		add_action("recent-changes", function() {
			global $settings, $paths, $pageindex;
			
			$format = $_GET["format"] ?? "html";
			$offset = intval($_GET["offset"] ?? 0);
			$count = intval($_GET["count"] ?? 0);
			
			$recent_changes = json_decode(file_get_contents($paths->recentchanges));
			
			// Limit the number of changes displayed if requested
			if($count > 0)
				$recent_changes = array_slice($recent_changes, $offset, $count);
			
			switch($format) {
				case "html":
					$content = "\t\t<h1>Recent Changes</h1>\n";
					
					if(count($recent_changes) > 0)
						$content .= render_recent_changes($recent_changes);
					else // No changes yet :(
						$content .= "<p><em>None yet! Try making a few changes and then check back here.</em></p>\n";
						
					page_renderer::add_header_html("\t<link rel=\"alternate\" type=\"application/atom+xml\" href=\"?action=recent-changes&amp;format=atom\" />
		<link rel=\"alternate\" type=\"text/csv\" href=\"?action=recent-changes&amp;format=csv\" />
		<link rel=\"alternate\" type=\"application/json\" href=\"?action=recent-changes&amp;format=json\" />");
					
					exit(page_renderer::render("Recent Changes - $settings->sitename", $content));
					break;
				case "json":
					$result = json_encode($recent_changes);
					header("content-type: application/json");
					header("content-length: " . strlen($result));
					exit($result);
					break;
				case "csv":
					if(empty($recent_changes)) {
						http_response_code(404);
						header("content-type: text/plain");
						exit("No changes made been recorded yet. Make some changes and then come back later!");
					}
					
					$result = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
					fputcsv($result, array_keys(get_object_vars($recent_changes[0])));
					foreach($recent_changes as $recent_change)
						fputcsv($result, array_values(get_object_vars($recent_change)));
					rewind($result);
					
					header("content-type: text/csv");
					header("content-length: " . fstat($result)["size"]);
					exit(stream_get_contents($result));
					break;
				case "atom":
					$result = render_recent_change_atom($recent_changes);
					header("content-type: application/atom+xml");
					header("content-length: " . strlen($result));
					exit($result);
				default:
					http_response_code(406);
					header("content-type: text/plain");
					header("content-length: 42");
					exit("Error: That format code was not recognised.");
			}
			
			
		});
		
		register_save_preprocessor(function(&$pageinfo, &$newsource, &$oldsource) {
			global $env, $settings, $paths;
			
			// Work out the old and new page lengths
			$oldsize = strlen($oldsource);
			$newsize = strlen($newsource);
			// Calculate the page length difference
			$size_diff = $newsize - $oldsize;
			
			$newchange = [
				"type" => "edit",
				"timestamp" => time(),
				"page" => $env->page,
				"user" => $env->user,
				"newsize" => $newsize,
				"sizediff" => $size_diff
			];
			if($oldsize == 0)
				$newchange["newpage"] = true;
			
			add_recent_change($newchange);
		});
		
		add_help_section("800-raw-page-content", "Recent Changes", "<p>The <a href='?action=recent-changes'>recent changes</a> page displays a list of all the most recent changes that have happened around $settings->sitename, arranged in chronological order. It can be found in the \"More...\" menu in the top right by default.</p>
		<p>Each entry displays the name of the page in question, who edited it, how long ago they did so, and the number of characters added or removed. Pages that <em>currently</em> redirect to another page are shown in italics, and hovering over the time since the edit wil show the exact time that the edit was made.</p>");
	}
]);

/**
 * Adds a new recent change to the recent changes file.
 * @package	feature-recent-changes
 * @param	array	$rchange	The new change to add.
 */
function add_recent_change($rchange)
{
	global $settings, $paths;
	
	$recentchanges = json_decode(file_get_contents($paths->recentchanges), true);
	array_unshift($recentchanges, $rchange);
	
	// Limit the number of entries in the recent changes file if we've
	// been asked to.
	if(isset($settings->max_recent_changes))
		$recentchanges = array_slice($recentchanges, 0, $settings->max_recent_changes);
	
	// Save the recent changes file back to disk
	file_put_contents($paths->recentchanges, json_encode($recentchanges, JSON_PRETTY_PRINT));
}

/**
 * Renders a list of recent changes to HTML.
 * @package	feature-recent-changes
 * @param	array	$recent_changes		The recent changes to render.
 * @return	string	The given recent changes as HTML.
 */
function render_recent_changes($recent_changes)
{
	global $pageindex;
	
	// Cache the number of recent changes we are dealing with
	$rchange_count = count($recent_changes);
	
	// Group changes made on the same page and the same day together
	for($i = 0; $i < $rchange_count; $i++)
	{
		for($s = $i + 1; $s < $rchange_count; $s++)
		{
			// Break out if we have reached the end of the day we are scanning
			if(date("dmY", $recent_changes[$i]->timestamp) !== date("dmY", $recent_changes[$s]->timestamp))
				break;
			
			// If we have found a change that has been made on the same page and
			// on the same day as the one that we are scanning for, move it up
			// next to the change we are scanning for.
			if($recent_changes[$i]->page == $recent_changes[$s]->page &&
				date("j", $recent_changes[$i]->timestamp) === date("j", $recent_changes[$s]->timestamp))
			{
				// FUTURE: We may need to remove and insert instead of swapping changes around if this causes some changes to appear out of order.
				$temp = $recent_changes[$i + 1];
				$recent_changes[$i + 1] = $recent_changes[$s];
				$recent_changes[$s] = $temp;
				$i++;
			}
		}
	}
	
	$content = "<ul class='page-list'>\n";
	$last_time = 0;
	for($i = 0; $i < $rchange_count; $i++)
	{
		$rchange = $recent_changes[$i];
		
		if($last_time !== date("dmY", $rchange->timestamp))
			$content .= "<li class='header'><h2>" . date("jS F", $rchange->timestamp) . "</h2></li>\n";
		
		$rchange_results = [];
		for($s = $i; $s < $rchange_count; $s++)
		{
			if($recent_changes[$s]->page !== $rchange->page)
				break;
			
			$rchange_results[$s] = render_recent_change($recent_changes[$s]);
			$i++;
		}
		// Take one from i to account for when we tick over to the next
		// iteration of the main loop
		$i -= 1;
		
		$next_entry = implode("\n", $rchange_results);
		// If the change count is greater than 1, then we should enclose it
		// in a <details /> tag.
		if(count($rchange_results) > 1)
		{
			reset($rchange_results);
			$rchange_first = $recent_changes[key($rchange_results)];
			end($rchange_results);
			$rchange_last = $recent_changes[key($rchange_results)];
			
			$pageDisplayHtml = render_pagename($rchange_first);
			$timeDisplayHtml = render_timestamp($rchange_first->timestamp);
			$users = [];
			foreach($rchange_results as $key => $rchange_result)
			{
				if(!in_array($recent_changes[$key]->user, $users))
					$users[] = $recent_changes[$key]->user; 
			}
			foreach($users as &$user)
				$user = page_renderer::render_username($user);
			$userDisplayHtml = render_editor(implode(", ", $users));
			
			$next_entry = "<li><details><summary><a href='?page=" . rawurlencode($rchange_first->page) . "'>$pageDisplayHtml</a> $userDisplayHtml $timeDisplayHtml</summary><ul class='page-list'>$next_entry</ul></details></li>";
			
			$content .= "$next_entry\n";
		}
		else
		{
			$content .= implode("\n", $rchange_results);
		}
		
		$last_time = date("dmY", $rchange->timestamp);
	}
	$content .= "\t\t</ul>";
	
	return $content;
}

/**
 * Given a page name and timestamp, returns the associated page revision number.
 * @param	string	$pagename	The page name to obtain the revision number for.
 * @param	int		$timestamap	The timestamp at which the revision was saved.
 * @return	int		The revision number of the given page at the given time.
 */
function find_revisionid_timestamp($pagename, $timestamp) {
	global $pageindex;
	
	if(!isset($pageindex->$pagename) || !isset($pageindex->$pagename->history))
		return null;
	
	foreach($pageindex->$pagename->history as $historyEntry){
		if($historyEntry->timestamp == $timestamp) {
			return $historyEntry->rid;
			break;
		}
	}
}

/**
 * Renders a single recent change
 * @package	feature-recent-changes
 * @param	object	$rchange	The recent change to render.
 * @return	string				The recent change, rendered to HTML.
 */
function render_recent_change($rchange)
{
	global $pageindex;
	$pageDisplayHtml = render_pagename($rchange);
	$editorDisplayHtml = render_editor(page_renderer::render_username($rchange->user));
	$timeDisplayHtml = render_timestamp($rchange->timestamp);
	
	$revisionId = find_revisionid_timestamp($rchange->page, $rchange->timestamp);
	
	$result = "";
	$resultClasses = [];
	$rchange_type = isset($rchange->type) ? $rchange->type : "edit";
	switch($rchange_type)
	{
		case "revert":
		case "edit":
			// The number (and the sign) of the size difference to display
			$size_display = ($rchange->sizediff > 0 ? "+" : "") . $rchange->sizediff;
			$size_display_class = $rchange->sizediff > 0 ? "larger" : ($rchange->sizediff < 0 ? "smaller" : "nochange");
			if($rchange->sizediff > 500 or $rchange->sizediff < -500)
				$size_display_class .= " significant";
			
			
			$size_title_display = human_filesize($rchange->newsize - $rchange->sizediff) . " -> " .  human_filesize($rchange->newsize);
			
			if(!empty($rchange->newpage))
				$resultClasses[] = "newpage";
			if($rchange_type === "revert")
				$resultClasses[] = "reversion";
			
			$result .= "<a href='?page=" . rawurlencode($rchange->page) . (!empty($revisionId) ? "&amp;revision=$revisionId" : "") . (!empty($pageindex->{$rchange->page}->redirect) ? "&amp;redirect=no" : "" ) . "'>$pageDisplayHtml</a> $editorDisplayHtml $timeDisplayHtml <span class='$size_display_class' title='$size_title_display'>($size_display)</span>";
			break;
			
		case "deletion":
			$resultClasses[] = "deletion";
			$result .= "$pageDisplayHtml $editorDisplayHtml $timeDisplayHtml";
			break;
		
		case "move":
			$resultClasses[] = "move";
			$result .= "$rchange->oldpage &rarr; <a href='?page=" . rawurlencode($rchange->page) . "'>$pageDisplayHtml</a> $editorDisplayHtml $timeDisplayHtml";
			break;
		
		case "upload":
			$resultClasses[] = "upload";
			$result .= "<a href='?page=$rchange->page'>$pageDisplayHtml</a> $editorDisplayHtml $timeDisplayHtml (" . human_filesize($rchange->filesize) . ")";
			break;
		case "comment":
			$resultClasses[] = "new-comment";
			$result .= "<a href='?page=$rchange->page#comment-" . (!empty($rchange->comment_id) ? "$rchange->comment_id" : "unknown_comment_id") . "'>$pageDisplayHtml</a> $editorDisplayHtml";
	}
	
	$resultAttributes = " " . (count($resultClasses) > 0 ? "class='" . implode(" ", $resultClasses) . "'" : "");
	$result = "\t\t\t<li$resultAttributes>$result</li>\n";
	
	return $result;
}

/**
 * Renders a list of recent changes as an Atom 1.0 feed.
 * Requires the XMLWriter PHP class.
 * @param	array	$recent_changes		The array of recent changes to render.
 * @return	string	The recent changes as an Atom 1.0 feed.
 */
function render_recent_change_atom($recent_changes) {
	global $version, $settings;
	// See http://www.atomenabled.org/developers/syndication/#sampleFeed for easy-to-read Atom 1.0 docs
	
	$full_url_stem = url_stem();
	
	$xml = new XMLWriter();
	$xml->openMemory();
	$xml->setIndent(true); $xml->setIndentString("\t");
	$xml->startDocument("1.0", "utf-8");
	
	$xml->startElement("feed");
	$xml->writeAttribute("xmlns", "http://www.w3.org/2005/Atom");
	
	$xml->startElement("generator");
	$xml->writeAttribute("uri", "https://github.com/sbrl/Pepperminty-Wiki/");
	$xml->writeAttribute("version", $version);
	$xml->text("Pepperminty Wiki");
	$xml->endElement();
	
	$xml->startElement("link");
	$xml->writeAttribute("rel", "self");
	$xml->writeAttribute("type", "application/atom+xml");
	$xml->writeAttribute("href", full_url());
	$xml->endElement();
	
	$xml->startElement("link");
	$xml->writeAttribute("rel", "alternate");
	$xml->writeAttribute("type", "text/html");
	$xml->writeAttribute("href", "$full_url_stem?action=recent-changes&format=html");
	$xml->endElement();
	
	$xml->startElement("link");
	$xml->writeAttribute("rel", "alternate");
	$xml->writeAttribute("type", "application/json");
	$xml->writeAttribute("href", "$full_url_stem?action=recent-changes&format=json");
	$xml->endElement();
	
	$xml->startElement("link");
	$xml->writeAttribute("rel", "alternate");
	$xml->writeAttribute("type", "text/csv");
	$xml->writeAttribute("href", "$full_url_stem?action=recent-changes&format=csv");
	$xml->endElement();
	
	$xml->writeElement("updated", date(DateTime::ATOM));
	$xml->writeElement("id", full_url());
	$xml->writeElement("icon", $settings->favicon);
	$xml->writeElement("title", "$settings->sitename - Recent Changes");
	$xml->writeElement("subtitle", "Recent Changes on $settings->sitename");
	
	foreach($recent_changes as $recent_change) {
		if(empty($recent_change->type))
			$recent_change->type = "edit";
		
		$xml->startElement("entry");
		
		// Change types: revert, edit, deletion, move, upload, comment
		$type = $recent_change->type;
		$url = "$full_url_stem?page=".rawurlencode($recent_change->page);
		
		$content = "<ul>
	<li><strong>Change type:</strong> $recent_change->type</li>
	<li><strong>User:</strong>  $recent_change->user</li>
	<li><strong>Page name:</strong> $recent_change->page</li>
	<li><strong>Timestamp:</strong> ".date(DateTime::RFC1123, $recent_change->timestamp)."</li>\n";
		
		switch($type) {
			case "revert":
			case "edit":
				$type = ($type == "revert" ? "Reversion of" : "Edit to");
				$revision_id = find_revisionid_timestamp($recent_change->page, $recent_change->timestamp);
				if(!empty($revision_id))
					$url .= "&revision=$revision_id";
				$content .= "<li><strong>New page size:</strong> ".human_filesize($recent_change->newsize)."</li>
			<li><strong>Page size difference:</strong> ".($recent_change->sizediff > 0 ? "+" : "")."$recent_change->sizediff</li>\n";
				break;
			case "deletion": $type = "Deletion of"; break;
			case "move": $type = "Movement of"; break;
			case "upload":
				$type = "Upload of";
				$content .= "\t<li><strong>File size:</strong> ".human_filesize($recent_change->filesize)."</li>\n";
				break;
			case "comment":
				$type = "Comment on";
				$url .= "#comment-$recent_change->comment_id";
				break;
		}
		$content .= "</ul>";
		
		
		$xml->startElement("title");
		$xml->writeAttribute("type", "text");
		$xml->text("$type $recent_change->page by $recent_change->user");
		$xml->endElement();
		
		$xml->writeElement("id", $url);
		$xml->writeElement("updated", date(DateTime::ATOM, $recent_change->timestamp));
		
		$xml->startElement("content");
		$xml->writeAttribute("type", "html");
		$xml->text($content);
		$xml->endElement();
		
		$xml->startElement("link");
		$xml->writeAttribute("rel", "alternate");
		$xml->writeAttribute("type", "text/html");
		$xml->writeAttribute("href", $url);
		$xml->endElement();
		
		$xml->startElement("author");
		$xml->writeElement("name", $recent_change->user);
		$xml->writeElement("uri", "$full_url_stem?page=".rawurlencode("$settings->user_page_prefix/$recent_change->user"));
		$xml->endElement();
		
		$xml->endElement();
	}
	
	$xml->endElement();
	
	return $xml->flush();
}


/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Redirect pages",
	"version" => "0.3.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds support for redirect pages. Uses the same syntax that Mediawiki does.",
	"id" => "feature-redirect",
	"code" => function() {
		global $settings;
		
		register_save_preprocessor("update_redirect_metadata");
		
		$help_html = "<p>$settings->sitename supports redirect pages. To create a redirect page, enter something like <code># REDIRECT [[pagename]]</code> on the first line of the redirect page's content. This <em>must</em> appear as the first line of the page, with no whitespace before it. You can include content beneath the redirect if you want, too (such as a reason for redirecting the page).</p>";
		if($settings->redirect_absolute_enable == true) $help_html .= "<p>$settings->sitename also has absolute redirects enabled (e.g. if you want to make your main page point to the all pages list). To make a page an absolute redirect page, enter the following on the first line: <code># REDIRECT [all pages](?action=list)</code>. This example will cause the page to become a redirect to the all pages list. Of course, you  can change the <code>?action=list</code> bit to be any regular URL you like (relative or absolute)</p>";
		
		// Register a help section
		add_help_section("25-redirect", "Redirect Pages", $help_html);
	}
]);

/**
 * Updates the metadata associated with redirects in the pageindex entry
 * specified utilising the provided page content.
 * @package	redirect
 * @param	object	$index_entry	The page index entry object to update.
 * @param	string	$pagedata		The page content to operate on.
 */
function update_redirect_metadata(&$index_entry, &$pagedata) {
	$matches = [];
	if(preg_match("/^# ?REDIRECT ?\[\[([^\]]+)\]\]/i", $pagedata, $matches) === 1)
	{
		//error_log("matches: " . var_export($matches, true));
		// We have found a redirect page!
		// Update the metadata to reflect this.
		$index_entry->redirect = true;
		$index_entry->redirect_target = $matches[1];
		$index_entry->redirect_absolute = false;
	}
	// We don't disable absolute redirects here, because it's the view action that processes them - we only register them here. Checking here would result in pages that are supposed to be redirects being missed if redirect_absolute_enable is turned on after such a page is created.
	elseif(preg_match("/^# ?REDIRECT ?\[[^\]]+\]\(([^)]+)\)/", $pagedata, $matches) === 1) {
		$index_entry->redirect = true;
		$index_entry->redirect_target = $matches[1];
		$index_entry->redirect_absolute = true;
	}
	else
	{
		// This page isn't a redirect. Unset the metadata just in case.
		if(isset($index_entry->redirect))
			unset($index_entry->redirect);
		if(isset($index_entry->redirect_target))
			unset($index_entry->redirect_target);
		if(isset($index_entry->redirect_absolute))
			unset($index_entry->redirect_absolute);
	}
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Did you mean? support",
	"version" => "0.1.1",
	"author" => "Starbeamrainbowlabs",
	"description" => "*Experimental* Ever searched for something but couldn't find it because you couldn't spell it correctly? This module is for you! It adds spelling correction for search queries based on the words in the inverted search index.",
	"id" => "feature-search-didyoumean",
	"depends" => [ "lib-search-engine", "lib-storage-box" ],
	"code" => function() {
		/*
		██████  ███████ ██████  ██    ██ ██ ██      ██████
		██   ██ ██      ██   ██ ██    ██ ██ ██      ██   ██
		██████  █████   ██████  ██    ██ ██ ██      ██   ██
		██   ██ ██      ██   ██ ██    ██ ██ ██      ██   ██
		██   ██ ███████ ██████   ██████  ██ ███████ ██████
		*/
		add_action("didyoumean-rebuild", function() {
			global $env, $settings;
			if($env->is_admin ||
				(
					!empty($_POST["secret"]) &&
					$_POST["secret"] === $settings->secret
				)
			)
				search::didyoumean_rebuild();
			else
			{
				http_response_code(401);
				exit(page_renderer::render_main("Error - didyoumean index regenerator - $settings->sitename", "<p>Error: You aren't allowed to regenerate the didyoumean index. Try logging in as an admin, or setting the <code>secret</code> POST parameter to $settings->sitename's secret - which can be found in $settings->sitename's <code>peppermint.json</code> file.</p>"));
			}
		});
		
		/*
 		 *  ██████ ██      ██
		 * ██      ██      ██
		 * ██      ██      ██
		 * ██      ██      ██
 		 *  ██████ ███████ ██
		 */
		 
	 	if(module_exists("feature-cli")) {
	 		cli_register("didyoumean", "Query and manipulate the didyoumean index", function(array $args) : int {
				global $settings;
	 			if(count($args) < 1) {
	 				echo("didyoumean: query and manipulate the didyoumean index
Usage:
	didyoumean {subcommand} 

Subcommands:
	rebuild           Rebuilds the didyoumean index
	correct {word}    Corrects {word} using the didyoumean index (careful: the index is case-sensitive and operates on transliterated text *only*)
	lookup {word}     Looks up {word} in the didyoumean index and displays all the (unsorted) results.
");
	 				return 0;
	 			}
	 			
	 			switch($args[0]) {
	 				case "rebuild":
	 					search::didyoumean_rebuild();
	 					break;
					case "correct":
						if(count($args) < 2) {
							echo("Error: Not enough arguments\n");
							return 1;
						}
						$correction = search::didyoumean_correct($args[1]);
						if($correction === null) $correction = "(nothing found)";
						echo("$correction\n");
						break;
					case "lookup":
						if(count($args) < 2) {
							echo("Error: Not enough arguments\n");
							return 1;
						}
						search::didyoumean_load();
						$results = search::$didyoumeanindex->lookup(
							$args[1],
							$settings->search_didyoumean_editdistance
						);
						echo(implode("\n", $results));
						echo("\n");
						break;
	 			}
	 			
	 			return 0;
	 		});
	 	}

	}
]);

/**
 * Calculates the standard deviation of an array of numbers.
 * @source https://stackoverflow.com/a/57694168/1460422
 * @param	array	$array	The array of numbers to calculate the standard deviation of.
 * @return	float	The standard deviation of the numbers in the given array.
 */
function standard_deviation(array $array): float {
    $size = count($array);
    $mean = array_sum($array) / $size;
    $squares = array_map(function ($x) use ($mean) {
        return pow($x - $mean, 2);
    }, $array);

    return sqrt(array_sum($squares) / ($size - 1));
}

/**
 * A serialisable BK-Tree Implementation.
 * Ref: https://nullwords.wordpress.com/2013/03/13/the-bk-tree-a-data-structure-for-spell-checking/
 */
class BkTree {
	private $box = null;
	
	/**
	 * The seed word of the tree.
	 * This word is the root node of the tree, and has a number of special properties::
	 *  - It's never removed
	 *  - It can't be added
	 *  - It is never returned as a suggestion
	 * This is essential because we can't delete the root node of the tree without effectively rebuilding the entire thing, because the root node of the three doesn't have a parent.
	 * @var string
	 */
	private $seed_word = null;
	
	private $cost_insert = 1;
	private $cost_delete = 1;
	private $cost_replace = 1;
	
	public function __construct(string $filename, string $seed_word) {
		$this->box = new StorageBox($filename);
		$this->seed_word = $seed_word;
		
		$this->init();
	}
	
	private function init() : void {
		if(!$this->box->has("node|0")) {
			// If the root node of the tree doesn't exist, create it
			$new = new stdClass();
			$new->value = $this->seed_word;
			$new->children = new stdClass(); // [ "id" => int, "distance" => int ]
			$this->box->set("node|0", $new);
			$this->increment_node_count();
		}
	}
	
	/**
	 * Set the levenshtien insert/delete/replace costs.
	 * Note that if these values change, the entire tree needs to be rebuilt.
	 * @param int $insert  The insert cost.
	 * @param int $delete  The cost to delete a character.
	 * @param int $replace The cost to replace a character.
	 */
	public function set_costs(int $insert, int $delete, int $replace) : void {
		$this->cost_insert = $insert;
		$this->cost_delete = $delete;
		$this->cost_replace = $replace;
	}
	/**
	 * Get the current levenshtein costs.
	 * @return stdClass The current levenshtein insert/delete/replace costs.
	 */
	public function get_costs() : stdClass {
		return (object) [
			"insert" => $this->cost_insert,
			"delete" => $this->cost_delete,
			"replace" => $this->cost_replace
		];
	}
	
	/**
	 * A utility function for calculating edit distance.
	 * Warning: Do not use this internally! It is *slow*. It's much faster to do this directly. This exists only for external use.
	 * @param	string	$a	The first string.
 	 * @param	string	$b	The second string to compare against.
	 * @return	int		The computed edit distance.
	 */
	public function edit_distance(string $a, string $b) : int {
		return levenshtein($a, $b, $this->cost_insert, $this->cost_replace, $this->cost_delete);
	}
	
	private function get_node_count() : int {
		if(!$this->box->has("node_count"))
			$this->set_node_count(0);
		return $this->box->get("node_count");
	}
	private function set_node_count(int $value) : void {
		$this->box->set("node_count", $value);
	}
	private function increment_node_count() : void {
		$this->box->set("node_count", $this->box->get("node_count") + 1);
	}
	
	/**
	 * Adds a string to the tree.
	 * Note that duplicates can be added if you're not careful!
	 * @param	string	$string				The string to add.
	 * @param	int		$starting_node_id	The id fo node to start insertion from. Defaults to 0 - for internal use only.
	 * @return	int		The depth at which the new node was added.
	 */
	public function add(string $string, int $starting_node_id = 0) : ?int {
		// Can't add the seed word to the tree
		if($string == $this->seed_word)
			return null;
		// PHP's levenshtein() function only works on strings up to 255 chars, apparently
		if(strlen($string) > 255)
			return null;
			
		if(!$this->box->has("node|$starting_node_id"))
			throw new Exception("Error: Failed to find node with id $starting_node_id to begin insertion");
		
		// if($string == "bunny") echo("\nStart $string\n");
		
		$next_node = $this->box->get("node|$starting_node_id"); // Grab the root to start with
		$next_node_id = $starting_node_id;
		$depth = 0; $visted = 0;
		while(true) {
			$visted++;
			$distance = levenshtein($string, $next_node->value, $this->cost_insert, $this->cost_replace, $this->cost_delete);
			
			if(isset($next_node->children->$distance)) {
				$child_id = $next_node->children->$distance;
				$next_node = $this->box->get("node|$child_id");
				$next_node_id = $child_id;
				$depth++;
				continue; // Continue on the outer while loop
			}
			
			// If we got here, then no existing children have the same edit distance
			// Note that here we don't push to avoid the overhead from either array_push() (considerable) or count() (also considerable).
			
			// Create the new child node
			$new_id = $this->get_node_count();
			$this->box->set("node|$new_id", (object) [
				"value" => $string,
				"children" => new stdClass()
			]);
			// Create the edge that points from the existing node to the new node
			$next_node->children->$distance = $new_id;
			$this->box->set("node|$next_node_id", $next_node);
			$this->increment_node_count();
			break;
		}
		return $depth;
	}
	
	/**
	 * Removes a string from the tree.
	 * BUG: If this deletes the root node, then it's all over and it will crash
 	 * @param	string	$string	The string to remove.
	 * @return	bool	Whether the removal was successful.
	 */
	public function remove(string $string) : bool {
		global $settings;
		// Not allowed to remove the seed word
		if($string == $this->seed_word) {
			error_log("[PeppermintyWiki/$settings->sitename/DidYouMean-BkTree] Blocked an attempt to remove the seed word $this->seed_word");
			return false;
		}
		
		$stack = [ [ "node" => $this->box->get("node|0"), "id" => 0 ] ];
		$node_target = $stack[0]["node"];
		$node_target_id = 0;
		
		while($node_target->value !== $string) {
			$distance = levenshtein($string, $node_target->value, $this->cost_insert, $this->cost_replace, $this->cost_delete);
			
			// Failed to recurse to find the node with the value in question
			if(!isset($node_target->children->$distance))
				return false;
			
			$node_target_id = $node_target->children->$distance;
			$node_target = $this->box->get("node|$node_target_id");
			$stack[] = [ "node" => $node_target, "id" => $node_target_id ];
		}
		
		// The last item but 1 on the stack is the parent node
		$parent = $stack[count($stack) - 2];
		
		// 1. Delete the connection from parent -> target
		foreach($parent["node"]->children as $distance => $id) {
			if($id == $node_target_id) {
				unset($parent["node"]->children->$distance);
				break;
			}
		}
		
		// Save the parent node's back to disk
		// Note that we do this *before* sorting out the orphans, since it's possible that $this->add() will modify it further
		$this->box->set("node|{$parent["id"]}", $parent["node"]);
		
		// 2. Iterate over the target's children (if any) and re-hang them from the parent
		
		// Hang the now orphaned children and all their decendants from the parent
		foreach($node_target->children as $distance => $id) {
			$orphan = $this->box->get("node|$id");
			$substack = [ [ "node" => $orphan, "id" => $id ] ]; $substack_top = 0;
			while($substack_top >= 0) {
				$next = $substack[$substack_top];
				unset($substack[$substack_top]);
				$substack_top--;
				
				$this->box->delete("node|{$next["id"]}"); // Delete the orphan node
				$this->add($next["node"]->value, $parent["id"]); // Re-hang it from the parent
				
				foreach($next["node"]->children as $distance => $sub_id) {
					$substack[++$substack_top] = [
						"node" => $this->box->get("node|$sub_id"),
						"id" => $sub_id
					];
				}
			}
		}
		
		// Delete the target node
		$this->box->delete("node|$node_target_id");
		
		return true;
	}
	
	public function trace(string $string) : array {
		$stack = [
			(object) [ "node" => $this->box->get("node|0"), "id" => 0 ]
		];
		$node_target = $stack[0]->node;
		
		while($node_target->value !== $string) {
			$distance = levenshtein($string, $node_target->value, $this->cost_insert, $this->cost_replace, $this->cost_delete);
			
			// var_dump($node_target);
			
			// Failed to recurse to find the node with the value in question
			if(!isset($node_target->children->$distance))
				return null;
			
			$node_target_id = $node_target->children->$distance;
			$node_target = $this->box->get("node|$node_target_id");
			$stack[] = (object) [ "node" => $node_target, "id" => $node_target_id ];
		}
		return $stack;
	}
	
	/**
	 * Generator that walks the BK-Tree and iteratively yields results.
	 * Note that the returned array is *not* sorted.
	 * @param	string	$string			The search string.
	 * @param	integer	$max_distance	The maximum edit distance to search.
	 * @param	integer	$count			The number of results to return. 0 = All results found. Note that results will be in a random order.
	 * @return	array<string>			Similar resultant strings from the BK-Tree.
	 */
	public function lookup(string $string, int $max_distance = 1, int $count = 0) : array {
		// global $settings;
		// error_log("[PeppermintyWiki/$settings->sitename/BkTree/lookup]".var_export($string, true).", dist ".var_export($max_distance, true).", count:".var_export($count, true));
		if($this->get_node_count() == 0) return null;
		
		$result = []; $result_count = 0;
		$stack = [ $this->box->get("node|0") ];
		$stack_top = 0;
		
		$nodes = 0;
		
		// https://softwareengineering.stackexchange.com/a/226162/58491
		while($stack_top >= 0) {
			// Take the topmost node off the stack
			$node_current = $stack[$stack_top];
			unset($stack[$stack_top]);
			$stack_top--;
			$nodes++;
			
			$distance = levenshtein($string, $node_current->value, $this->cost_insert, $this->cost_replace, $this->cost_delete);
			
			// If the edit distance from the target string to this node is within the tolerance, store it
			// If it's the seed word, then we shouldn't return it either
			if($distance <= $max_distance && $node_current->value != $this->seed_word) {
				$result[] = $node_current->value;
				$result_count++;
				if($count != 0 && $result_count >= $count) break;
			}
			
			for($child_distance = $distance - $max_distance; $child_distance <= $distance + $max_distance; $child_distance++) {
				if(!isset($node_current->children->$child_distance))
					continue;
				
				$stack[++$stack_top] = $this->box->get("node|{$node_current->children->$child_distance}");
			}
		}
		
		error_log("Nodes traversed: $nodes\n");
		
		return $result;
	}
	
	/**
	 * Calculate statistics about the BK-Tree.
	 * Useful for analysing a tree's structure.
	 * If the tree isn't balanced, you may need to insert items in a different order.
	 * @return array An array of statistics about this BK-Tree.
	 */
	public function stats() : array {
		$result = [
			"depth_max" => 0,
			"depth_min_leaf" => INF,
			"depth_average" => 0,
			"depth_average_noleaf" => 0,
			"depth_standard_deviation" => [],
			"child_count_average" => 0,
			"child_count_max" => 0,
			"nodes" => $this->get_node_count(),
			"leaves" => 0,
			"non_leaves" => 0
		];
		
		$start_time = microtime(true);
		
		$stack = [ [ "node" => $this->box->get("node|0"), "depth" => 0 ] ];
		
		// https://softwareengineering.stackexchange.com/a/226162/58491
		while(!empty($stack)) {
			// Take the top-most node off the stack
			$current = array_pop($stack);
			
			// Operate on the node
			$result["depth_standard_deviation"][] = $current["depth"];
			$result["depth_average"] += $current["depth"];
			if($current["depth"] > $result["depth_max"])
				$result["depth_max"] = $current["depth"];
			if(empty($current["node"]->children) && $current["depth"] < $result["depth_min_leaf"])
				$result["depth_min_leaf"] = $current["depth"];
			
			$child_count = count((array)($current["node"]->children));
			$result["child_count_average"] += $child_count;
			if($child_count > $result["child_count_max"])
				$result["child_count_max"] = $child_count;
			if($child_count > 0) {
				$result["depth_average_noleaf"] += $current["depth"];
				$result["non_leaves"]++;
			}
			else
				$result["leaves"]++;
			
			// Iterate over the child nodes
			foreach($current["node"]->children as $child_distance => $child_id) {
				$stack[] = [
					"node" => $this->box->get("node|$child_id"),
					"depth" => $current["depth"] + 1
				];
			}
		}
		$result["depth_average"] /= $result["nodes"];
		$result["depth_average_noleaf"] /= $result["non_leaves"];
		$result["child_count_average"] /= $result["nodes"];
		$result["depth_standard_deviation"] = standard_deviation($result["depth_standard_deviation"]);
		
		$result["time_taken"] = microtime(true) - $start_time;
		
		return $result;
	}
	
	/**
	 * Iteratively walks the BkTree.
	 * Warning: This is *slow*
	 * @return Generator<stdClass> A generator that iteratively walks the tree and yields every item therein that's connected to the root node.
	 */
	public function walk() {
		$stack = [ (object)[
			"id" => 0,
			"node" => $this->box->get("node|0"),
			"parent_id" => -1,
			"parent" => null,
			"depth" => 0
		] ];
		$stack_top = 0;
		
		// https://softwareengineering.stackexchange.com/a/226162/58491
		while(!empty($stack)) {
			// Take the topmost node off the stack
			$current = $stack[$stack_top];
			unset($stack[$stack_top]);
			$stack_top--;
			
			// echo("Visiting "); var_dump($current);
			yield $current;
			
			// Iterate over the child nodes
			foreach($current->node->children as $child_distance => $child_id) {
				$stack_top++;
				$stack[$stack_top] = (object) [
					"id" => $child_id,
					"node" => $this->box->get("node|{$current->node->children->$child_distance}"),
					"parent_id" => $current->id,
					"parent" => $current->node,
					"depth" => $current->depth + 1
				];
			}
		}
	}
	
	public function clear() : void {
		$this->box->clear();
		$this->init();
	}
	
	/**
	 * Saves changes to the tree back to disk.
	 * @return	void
	 */
	public function close() {
		$this->box->close();
	}
}





/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Search",
	"version" => "0.13.3",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds proper search functionality to Pepperminty Wiki using an inverted index to provide a full text search engine. If pages don't show up, then you might have hit a stop word. If not, try requesting the `invindex-rebuild` action to rebuild the inverted index from scratch.",
	"id" => "feature-search",
	// After refactoring, we'll need to specify dependencies like this
	"depends" => [ "lib-search-engine" ],
	"code" => function() {
		global $settings, $paths;
		
		/**
		 * @api {get} ?action=index&page={pageName} Get an index of words for a given page
		 * @apiName SearchIndex
		 * @apiGroup Search
		 * @apiPermission Anonymous
		 * @apiDescription For debugging purposes. Be warned - the format could change at any time!
		 * 
		 * @apiParam {string}	page	The page to generate a word index page.
		 */
		
		/*
		 * ██ ███    ██ ██████  ███████ ██   ██ 
		 * ██ ████   ██ ██   ██ ██       ██ ██  
		 * ██ ██ ██  ██ ██   ██ █████     ███   
		 * ██ ██  ██ ██ ██   ██ ██       ██ ██  
		 * ██ ██   ████ ██████  ███████ ██   ██ 
		 */
		add_action("index", function() {
			global $settings, $env;
			
			$breakable_chars = "\r\n\t .,\\/!\"£$%^&*[]()+`_~#";
			
			header("content-type: text/plain");
			
			$source = file_get_contents("$env->storage_prefix$env->page.md");
			
			$index = search::index_generate($source);
			
			echo("Page name: $env->page\n");
			echo("--------------- Source ---------------\n");
			echo($source); echo("\n");
			echo("--------------------------------------\n\n");
			echo("---------------- Index ---------------\n");
			foreach($index as $term => $entry) {
				echo("$term: {$entry["freq"]} matches | " . implode(", ", $entry["offsets"]) . "\n");
			}
			echo("--------------------------------------\n");
		});
		
		/**
		 * @api {get} ?action=invindex-rebuild Rebuild the inverted search index from scratch
		 * @apiDescription	Causes the inverted search index to be completely rebuilt from scratch. Can take a while for large wikis!
		 * @apiName			SearchInvindexRebuild
		 * @apiGroup		Search
		 * @apiPermission	Admin
		 *
		 * @apiParam	{string}	secret		Optional. Specify the secret from peppermint.json here in order to rebuild the search index without logging in.
		 */
		
		/*
		 * ██ ███    ██ ██    ██ ██ ███    ██ ██████  ███████ ██   ██          
		 * ██ ████   ██ ██    ██ ██ ████   ██ ██   ██ ██       ██ ██           
		 * ██ ██ ██  ██ ██    ██ ██ ██ ██  ██ ██   ██ █████     ███  █████     
		 * ██ ██  ██ ██  ██  ██  ██ ██  ██ ██ ██   ██ ██       ██ ██           
		 * ██ ██   ████   ████   ██ ██   ████ ██████  ███████ ██   ██          
		 * 
		 * ██████  ███████ ██████  ██    ██ ██ ██      ██████                  
		 * ██   ██ ██      ██   ██ ██    ██ ██ ██      ██   ██                 
		 * ██████  █████   ██████  ██    ██ ██ ██      ██   ██                 
		 * ██   ██ ██      ██   ██ ██    ██ ██ ██      ██   ██                 
		 * ██   ██ ███████ ██████   ██████  ██ ███████ ██████                  
		 */
		add_action("invindex-rebuild", function() {
			global $env, $settings;
			if($env->is_admin ||
				(
					!empty($_POST["secret"]) &&
					$_POST["secret"] === $settings->secret
				)
			)
				search::invindex_rebuild();
			else
			{
				http_response_code(401);
				exit(page_renderer::render_main("Error - Search index regenerator - $settings->sitename", "<p>Error: You aren't allowed to regenerate the search index. Try logging in as an admin, or setting the <code>secret</code> POST parameter to $settings->sitename's secret - which can be found in $settings->sitename's <code>peppermint.json</code> file.</p>"));
			}
		});
		
		
		/**
		 * @api {get} ?action=idindex-show Show the id index
		 * @apiDescription	Outputs the id index. Useful if you need to verify that it's working as expected. Output is a json object.
		 * @apiName			SearchShowIdIndex
		 * @apiGroup		Search
		 * @apiPermission	Anonymous
		 */
		add_action("idindex-show", function() {
			global $idindex;
			header("content-type: application/json; charset=UTF-8");
			exit(json_encode($idindex, JSON_PRETTY_PRINT));
		});
		
		/**
		 * @api {get} ?action=search&query={text}[&format={format}]	Search the wiki for a given query string
		 * @apiName Search
		 * @apiGroup Search
		 * @apiPermission Anonymous
		 * 
		 * @apiParam {string}	query	The query string to search for.
		 * @apiParam {string}	format	Optional. Valid values: html, json. In json mode an object is returned with page names as keys, values as search result information - sorted in ranking order.
		 */
		
		/*
		 * ███████ ███████  █████  ██████   ██████ ██   ██ 
		 * ██      ██      ██   ██ ██   ██ ██      ██   ██ 
		 * ███████ █████   ███████ ██████  ██      ███████ 
		 *      ██ ██      ██   ██ ██   ██ ██      ██   ██ 
		 * ███████ ███████ ██   ██ ██   ██  ██████ ██   ██ 
		 */
		add_action("search", function() {
			global $settings, $env, $pageindex, $paths;
			
			// Create the inverted index if it doesn't exist.
			if(!file_exists($paths->searchindex))
				search::invindex_rebuild(false);
				
			// Create the didyoumean index if it doesn't exist.
			if(module_exists("feature-search-didyoumean") && !file_exists($paths->didyoumeanindex))
				search::didyoumean_rebuild(false);
			
			if(!isset($_GET["query"]))
				exit(page_renderer::render("No Search Terms - Error - $settings->sitename", "<p>You didn't specify any search terms. Try typing some into the box above.</p>"));
			
			$search_start = microtime(true);
			
			
			$time_start = microtime(true);
			search::invindex_load($paths->searchindex);
			$env->perfdata->invindex_decode_time = round((microtime(true) - $time_start)*1000, 3);
			
			$time_start = microtime(true);
			$query_parsed = null;
			$results = search::invindex_query($_GET["query"], $query_parsed);
			$resultCount = count($results);
			$env->perfdata->invindex_query_time = round((microtime(true) - $time_start)*1000, 3);
			
			header("x-invindex-load-time: {$env->perfdata->invindex_decode_time}ms");
			header("x-invindex-query-time: {$env->perfdata->invindex_query_time}ms");
			
			$start = microtime(true);
			// FUTURE: When we implement $_GET["offset"] and $_GET["count"] or something we can optimise here
			foreach($results as $key => &$result) {
				$filepath = $env->storage_prefix . $result["pagename"] . ".md";
				if(!file_exists($filepath)) {
					error_log("[PeppermintyWiki/$settings->sitename/search] Search engine returned {$result["pagename"]} as a result (maps to $filepath), but it doesn't exist on disk (try rebuilding the search index).");
					continue; // Something strange is happening
				}
				$result["context"] = search::extract_context(
					$result["pagename"],
					$query_parsed,
					file_get_contents($filepath)
				);
			}
			// This is absolutely *essential*, because otherwise we hit a very strange bug whereby PHP duplicates the value of the last iterated search result. Ref https://bugs.php.net/bug.php?id=70387 - apparently "documented behaviour"
			unset($result);
			$env->perfdata->context_generation_time = round((microtime(true) - $start)*1000, 3);
			header("x-context-generation-time: {$env->perfdata->context_generation_time}ms");
			
			$env->perfdata->search_time = round((microtime(true) - $search_start)*1000, 3);
			header("x-search-time: {$env->perfdata->search_time}ms");
			
			if(!empty($_GET["format"]) && $_GET["format"] == "json") {
				header("content-type: application/json");
				$json_results = new stdClass();
				foreach($results as $key => $result)
					$json_results->{$result["pagename"]} = $result;
				exit(json_encode($json_results));
			}

			$title = $_GET["query"] . " - Search results - $settings->sitename";
			
			$content = "<section>\n";
			$content .= "<h1>Search Results</h1>";
			
			/// Search Box ///
			$content .= "<form method='get' action=''>\n";
			$content .= "	<input type='search' id='search-box' name='query' placeholder='Type your query here and then press enter.' value='" . htmlentities($_GET["query"], ENT_HTML5 | ENT_QUOTES) . "' />\n";
			$content .= "	<input type='hidden' name='action' value='search' />\n";
			$content .= "</form>";
			
			$content .= "<p>Found $resultCount " . ($resultCount === 1 ? "result" : "results") . " in " . $env->perfdata->search_time . "ms. ";
			
			$query = $_GET["query"];
			if(isset($pageindex->$query)) {
				$content .= "There's a page on $settings->sitename called <a href='?page=" . rawurlencode($query) . "'>".htmlentities($query)."</a>.";
			}
			else
			{
				$content .= "There isn't a page called ".htmlentities($query)." on $settings->sitename, but you ";
				if((!$settings->anonedits && !$env->is_logged_in) || !$settings->editing) {
					$content .= "do not have permission to create it.";
					if(!$env->is_logged_in) {
						$content .= " You could try <a href='?action=login&returnto=" . rawurlencode($_SERVER["REQUEST_URI"]) . "'>logging in</a>.";
					}
				}
				else {
					$content .= "can <a href='?action=edit&page=" . rawurlencode($query) . "'>create it</a>.";
				}
			}
			$content .= "<br /><small><em>Pssst! Power users can make use of $settings->sitename's advanced query syntax. Learn about it <a href='?action=help#27-search'>here</a>!</em></small></p>";
			
			if(module_exists("page-list")) {
				// TODO: Refactor this to use STAS
				$nterms = search::tokenize($query);
				$nterms_regex = implode("|", array_map(function($nterm) {
					return preg_quote(strtolower(trim($nterm)));
				}, $nterms));
				$all_tags = get_all_tags();
				$matching_tags = [];
				foreach($all_tags as $tag) {
					if(preg_match("/$nterms_regex/i", trim($tag)) > 0)
						$matching_tags[] = $tag;
				}
				
				if(count($matching_tags) > 0) {
					$content .= "<p class='matching-tags-display'><label>Matching tags</label><span class='tags'>";
					foreach($matching_tags as $tag) {
						$content .= "\t<a href='?action=list-tags&tag=" . rawurlencode($tag)  ."' class='mini-tag'>" . htmlentities($tag) . "</a> \n";
					}
					$content .= "</span></p>";
				}
			}
			
			$i = 0; // todo use $_GET["offset"] and $_GET["result-count"] or something
			foreach($results as $result)
			{
				$pagename_display = htmlentities($result["pagename"]);
				$link = "?page=" . rawurlencode($result["pagename"]);
				$pagesource = file_get_contents($env->storage_prefix . $result["pagename"] . ".md");
				
				//echo("Extracting context for result " . $result["pagename"] . ".\n");
				$context = $result["context"];
				if(mb_strlen($context) === 0)
					$context = mb_substr($pagesource, 0, $settings->search_characters_context * 2);
				//echo("'Generated search context for " . $result["pagename"] . ": $context'\n");
				$context = search::highlight_context(
					$query_parsed,
					preg_replace('/</u', '&lt;', $context)
				);
				/*if(strlen($context) == 0)
				{
					$context = search::strip_markup(file_get_contents("$env->page.md", null, null, null, $settings->search_characters_context * 2));
					if($pageindex->{$env->page}->size > $settings->search_characters_context * 2)
						$context .= "...";
				}*/
				
				$tag_list = "<span class='tags'>";
				foreach($pageindex->{$result["pagename"]}->tags ?? [] as $tag) $tag_list .= "<a href='?action=list-tags&tag=" . rawurlencode($tag) . "' class='mini-tag'>".htmlentities($tag)."</a>";
				$tag_list .= "</span>\n";
				
				// Make redirect pages italics
				if(!empty($pageindex->{$result["pagename"]}->redirect))
					$pagename_display = "<em>$pagename_display</em>";
				
				// We add 1 to $i here to convert it from an index to a result
				// number as people expect it to start from 1
				$content .= "<div class='search-result' data-result-number='" . ($i + 1) . "' data-rank='" . $result["rank"] . "'>\n";
				$content .= "	<h2><a href='$link'>$pagename_display</a> <span class='search-result-badges'>$tag_list</span></h2>\n";
				$content .= "	<p class='search-context'>$context</p>\n";
				$content .= "</div>\n";
				
				$i++;
			}
			
			$content .= "</section>\n";
			
			header("content-type: text/html; charset=UTF-8");
			exit(page_renderer::render($title, $content));
			
			//header("content-type: text/plain");
			//var_dump($results);
		});
		
/*
 *  ██████  ██    ██ ███████ ██████  ██    ██
 * ██    ██ ██    ██ ██      ██   ██  ██  ██
 * ██    ██ ██    ██ █████   ██████    ████  █████
 * ██ ▄▄ ██ ██    ██ ██      ██   ██    ██
 *  ██████   ██████  ███████ ██   ██    ██
 *     ▀▀
 * ███████ ███████  █████  ██████   ██████ ██   ██ ██ ███    ██ ██████  ███████ ██   ██
 * ██      ██      ██   ██ ██   ██ ██      ██   ██ ██ ████   ██ ██   ██ ██       ██ ██
 * ███████ █████   ███████ ██████  ██      ███████ ██ ██ ██  ██ ██   ██ █████     ███
 *      ██ ██      ██   ██ ██   ██ ██      ██   ██ ██ ██  ██ ██ ██   ██ ██       ██ ██
 * ███████ ███████ ██   ██ ██   ██  ██████ ██   ██ ██ ██   ████ ██████  ███████ ██   ██
 */

		/**
		 * @api {get} ?action=query-searchindex&query={text}	Inspect the internals of the search results for a query
		 * @apiName Search
		 * @apiGroup Search
		 * @apiPermission Anonymous
		 * 
		 * @apiParam {string}	query	The query string to search for.
		 */
		add_action("query-searchindex", function() {
			global $env, $paths;
			
			if(empty($_GET["query"])) {
				http_response_code(400);
				header("content-type: text/plain");
				exit("Error: No query specified. Specify it with the 'query' GET parameter.");
			}
			
			$env->perfdata->searchindex_decode_start = microtime(true);
			search::invindex_load($paths->searchindex);
			$env->perfdata->searchindex_decode_time = (microtime(true) - $env->perfdata->searchindex_decode_start) * 1000;
			$env->perfdata->searchindex_query_start = microtime(true);
			$query_stas = null;
			$searchResults = search::invindex_query($_GET["query"], $query_stas);
			$env->perfdata->searchindex_query_time = (microtime(true) - $env->perfdata->searchindex_query_start) * 1000;
			
			header("content-type: application/json");
			$result = new stdClass();
			$result->time_format = "ms";
			$result->decode_time = $env->perfdata->searchindex_decode_time;
			$result->query_time = $env->perfdata->searchindex_query_time;
			if(isset($env->perfdata->didyoumean_correction))
				$result->didyoumean_correction_time = $env->perfdata->didyoumean_correction;
			$result->total_time = $result->decode_time + $result->query_time;
			// $result->stas = search::stas_parse(search::stas_split($_GET["query"]));
			$result->stas = $query_stas;
			$result->search_results = $searchResults;
			exit(json_encode($result, JSON_PRETTY_PRINT));
		});
		
		/**
		 * @api {get} ?action=stas-parse&query={text}	Debug search queries
		 * @apiDescription Debug Pepperminty Wiki's understanding of search queries.
		 * If you want something machine-readable, check out the new stas property on the object returned by query-searchindex.
		 * @apiName SearchSTASParse
		 * @apiGroup Search
		 * @apiPermission Anonymous
		 * 
		 * @apiParam {string}	query	The query string to parse.
		 */
		add_action("stas-parse", function() {
			global $settings;
			
			if(!isset($_GET["query"])) {
				http_response_code(400);
				header("x-status: failed");
				header("x-problem: no-query-specified");
				exit(page_renderer::render_main("Error - STAS Query Analysis - $settings->sitename", "<p>No query was present in the <code>query</code> GET parameter.</p>"));
			}
			
			$tokens = search::stas_split($_GET["query"]);
			$stas_query = search::stas_parse($tokens);
			
			$result = "";
			foreach($tokens as $token) {
				if(in_array(substr($token, 1), $stas_query["exclude"])) {
					$result .= "<span title='explicit exclude' style='color: red; text-decoration: dotted line-through;'>" . htmlentities(substr($token, 1)) . "</span> ";
					continue;
				}
				
				$term = null;
				$token_part = $token;
				if($token_part[0] == "+") $token_part = substr($token_part, 1);
				if(strpos($token_part, ":") !== false) $token_part = explode(":", $token_part, 2)[1];
				foreach($stas_query["terms"] as $c_term) {
					// echo(var_export($token_part, true) . " / {$c_term["term"]}\n");
					if($c_term["term"] == $token_part) {
						$term = $c_term;
						break;
					}
				}
				if($term == null) {
					$result .= "<span title='unknown' style='color: black; text-decoration: wavy underline;'>".htmlentities($token)."</span> ";
					continue;
				}
				
				$title = "?";
				$style = "";
				switch($term["weight"]) {
					case -1: $style .= "color: grey; text-decoration: wavy line-through;"; $title = "stop word"; break;
					case 1: $style .= "color: blue;"; $title = "normal word"; break;
				}
				if($term["weight"] > 1) {
					$style .= "color: darkblue; font-weight: bold;";
					$title = "weighted word";
				}
				if($term["weight"] !== -1) {
					switch($term["location"]) {
						case "body": $style = "color: cyan"; $title = "body only"; break;
						case "title": $style .= "font-weight: bolder; font-size: 1.2em; color: orange;"; $title = "searching title only"; $token = $token_part; break;
						case "tags": $style .= "font-weight: bolder; color: purple;"; $title = "searching tags only"; $token = $token_part; break;
						case "all": $title .= ", searching everywhere";
					}
				}
				$title .= ", weight: {$term["weight"]}";
				
				$result .= "<span title='$title' style='$style'>".htmlentities($token)."</span> ";
			}
			
			exit(page_renderer::render_main("STAS Query Analysis - $settings->sitename", "<p>$settings->sitename understood your query to mean the following:</p>
				<blockquote>$result</blockquote>"));
		});
	
/*
 *  ██████  ██████  ███████ ███    ██ ███████ ███████  █████  ██████   ██████ ██   ██
 * ██    ██ ██   ██ ██      ████   ██ ██      ██      ██   ██ ██   ██ ██      ██   ██
 * ██    ██ ██████  █████   ██ ██  ██ ███████ █████   ███████ ██████  ██      ███████
 * ██    ██ ██      ██      ██  ██ ██      ██ ██      ██   ██ ██   ██ ██      ██   ██
 *  ██████  ██      ███████ ██   ████ ███████ ███████ ██   ██ ██   ██  ██████ ██   ██
 */
		/**
		 * @api {get} ?action=opensearch-description	Get the opensearch description file
		 * @apiName OpenSearchDescription
		 * @apiGroup Search
		 * @apiPermission Anonymous
		 */
		add_action("opensearch-description", function () {
			global $settings;
			$siteRoot = htmlentities(full_url() . "/index.php", ENT_XML1);
			if(!isset($_GET["debug"]))
				header("content-type: application/opensearchdescription+xml");
			else
				header("content-type: text/plain");
			
			exit('<?xml version="1.0" encoding="UTF-8"?' . '>' . // hack The build system strips it otherwise O.o I should really fix that.
"\n<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">
	<ShortName>Search $settings->sitename</ShortName>
	<Description>Search $settings->sitename, which is powered by Pepperminty Wiki.</Description>
	<Tags>$settings->sitename Wiki</Tags>
	<Image type=\"image/png\">$settings->favicon</Image>
	<Attribution>Search content available under the license linked to at the bottom of the search results page.</Attribution>
	<Developer>Starbeamrainbowlabs (https://github.com/sbrl/Pepperminty-Wiki/graphs/contributors)</Developer>
	<InputEncoding>UTF-8</InputEncoding>
	<OutputEncoding>UTF-8</OutputEncoding>
	
	<Url type=\"text/html\" method=\"get\" template=\"$siteRoot?action=view&amp;search-redirect=yes&amp;page={searchTerms}&amp;offset={startIndex?}&amp;count={count}\" />
	<Url type=\"application/x-suggestions+json\" template=\"$siteRoot?action=suggest-pages&amp;query={searchTerms}&amp;type=opensearch\" />
</OpenSearchDescription>");
		});
		
		
		/**
		 * @api {get} ?action=suggest-pages[&type={type}]	Get page name suggestions for a query
		 * @apiName OpenSearchDescription
		 * @apiGroup Search
		 * @apiPermission Anonymous
		 *
		 * @apiParam	{string}	text	The search query string to get search suggestions for.
		 * @apiParam	{string}	type	The type of result to return. Default value: json. Available values: json, opensearch
		 */
		add_action("suggest-pages", function() {
			global $settings, $pageindex;
			
			if($settings->dynamic_page_suggestion_count === 0) {
				header("content-type: application/json");
				header("content-length: 3");
				exit("[]\n");
			}
			
			if(empty($_GET["query"])) {
				http_response_code(400);
				header("content-type: text/plain");
				exit("Error: You didn't specify the 'query' GET parameter.");
			}
			
			$type = $_GET["type"] ?? "json";
			
			if(!in_array($type, ["json", "opensearch"])) {
				http_response_code(406);
				header("content-type: text/plain");
				exit("Error: The type '$type' is not one of the supported output types. Available values: json, opensearch. Default: json");
			}
			
			$query = search::$literator->transliterate($_GET["query"]);
			
			// Rank each page name
			$results = [];
			foreach($pageindex as $pageName => $entry) {
				$results[] = [
					"pagename" => $pageName,
					// Costs: Insert: 1, Replace: 8, Delete: 6
					"distance" => levenshtein($query, search::$literator->transliterate($pageName), 1, 8, 6)
				];
			}
			
			// Sort the page names by distance from the original query
			usort($results, function($a, $b) {
				if($a["distance"] == $b["distance"])
					return strcmp($a["pagename"], $b["pagename"]);
				return $a["distance"] < $b["distance"] ? -1 : 1;
			});
			
			// Send the results to the user
			$suggestions = array_slice($results, 0, $settings->dynamic_page_suggestion_count);
			switch($type)
			{
				case "json":
					header("content-type: application/json");
					exit(json_encode($suggestions));
				case "opensearch":
					$opensearch_output = [
						$_GET["query"],
						array_map(function($suggestion) { return $suggestion["pagename"]; }, $suggestions)
					];
					header("content-type: application/x-suggestions+json");
					exit(json_encode($opensearch_output));
			}
		});
		
		if($settings->dynamic_page_suggestion_count > 0)
		{
			page_renderer::add_js_snippet('/// Dynamic page suggestion system
// Micro snippet 8 - Promisified GET (fetched 20th Nov 2016)
function get(u){return new Promise(function(r,t,a){a=new XMLHttpRequest();a.onload=function(b,c){b=a.status;c=a.response;if(b>199&&b<300){r(c)}else{t(c)}};a.open("GET",u,true);a.send(null)})}

window.addEventListener("load", function(event) {
	var searchBox = document.querySelector("input[type=search]");
	searchBox.dataset.lastValue = "";
	searchBox.addEventListener("keyup", function(event) {
		// Make sure that we don\'t keep sending requests to the server if nothing has changed
		if(searchBox.dataset.lastValue == event.target.value)
			return;
		searchBox.dataset.lastValue = event.target.value;
		// Fetch the suggestions from the server
		get("?action=suggest-pages&query=" + encodeURIComponent(event.target.value)).then(function(response) {
			var suggestions = JSON.parse(response),
				dataList = document.getElementById("allpages");
			
			// If the server sent no suggestions, then we shouldn\'t replace the contents of the datalist
			if(suggestions.length == 0)
				return;
			
			console.info(`Fetched suggestions for ${event.target.value}:`, suggestions.map(s => s.pagename));
			
			// Remove all the existing suggestions
			while(dataList.firstChild) {
				dataList.removeChild(dataList.firstChild);
			}
			
			// Add the new suggestions to the datalist
			var optionsFrag = document.createDocumentFragment();
			suggestions.forEach(function(suggestion) {
				var suggestionElement = document.createElement("option");
				suggestionElement.value = suggestion.pagename;
				suggestionElement.dataset.distance = suggestion.distance;
				optionsFrag.appendChild(suggestionElement);
			});
			dataList.appendChild(optionsFrag);
		});
	});
});
');
		}
		
		if(module_exists("feature-cli")) {
			cli_register("search", "Query and manipulate the search index", function(array $args) : int {
				if(count($args) < 1) {
					echo("search: query and manipulate the search index
Usage:
    search {subcommand} 

Subcommands:
    rebuild     Rebuilds the search index
");
					return 0;
				}
				
				switch($args[0]) {
					case "rebuild":
						search::invindex_rebuild();
						break;
				}
				
				return 0;
			});
		}
		
		add_help_section("27-search", "Searching", "<p>$settings->sitename has an integrated full-text search engine, allowing you to search all of the pages on $settings->sitename and their content. To use it, simply enter your query into the page name box and press enter. If a page isn't found with the exact name of your query terms, a search will be performed instead.</p>
		<p>Additionally, advanced users can take advantage of some extra query syntax that $settings->sitename supports, which is inspired by popular search engines:</p>
		<table>
		<tr><th style='width: 33%;'>Example</th><th style='width: 66%;'>Meaning</th></tr>
		<tr><td><code>cat -dog</code></td><td>Search for pages containing \"cat\", but not \"dog\". This syntax does not make sense on it's own - other words must be present for it to take effect.</td>
		<tr><td><code>+glass marble</code></td><td>Double the weighting of the word \"glass\".</td>
		<tr><td><code>intitle:rocket</code></td><td>Search only page titles for \"rocket\".</td>
		<tr><td><code>intags:bill</code></td><td>Search only tags for \"bill\".</td>
		<tr><td><code>inbody:satellite</code></td><td>Search only the page body for \"satellite\".</td>
		</table>
		<p>More query syntax will be added in the future, so keep an eye on <a href='https://github.com/sbrl/Pepperminty-Wiki/releases/'>the latest releases</a> of <em>Pepperminty Wiki</em> to stay up-to-date (<a href='https://github.com/sbrl/Pepperminty-Wiki/releases.atom'>Atom / RSS feed available here</a>).</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Similar Pages",
	"version" => "0.1",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a few suggestions of similar pages below the main content and above the comments of a page. Requires the search engine.",
	"id" => "feature-similarpages",
	"depends" => [ "lib-search-engine", "feature-search" ],
	"code" => function() {
		global $settings;
		/**
		 * @api {get} ?action=suggest-similar&page={pageName} Get similar page suggestions
		 * @apiName SuggestSimilar
		 * @apiGroup Page
		 * @apiPermission Anonymous
		 * 
		 * @apiParam {string}	page	The page to return suggestions for.
		 */
		
		/*
		 * ███████ ██    ██  ██████   ██████  ███████ ███████ ████████
		 * ██      ██    ██ ██       ██       ██      ██         ██
		 * ███████ ██    ██ ██   ███ ██   ███ █████   ███████    ██
		 *      ██ ██    ██ ██    ██ ██    ██ ██           ██    ██
		 * ███████  ██████   ██████   ██████  ███████ ███████    ██
		 * 
		 * ███████ ██ ███    ███ ██ ██       █████  ██████
		 * ██      ██ ████  ████ ██ ██      ██   ██ ██   ██
		 * ███████ ██ ██ ████ ██ ██ ██      ███████ ██████
		 *      ██ ██ ██  ██  ██ ██ ██      ██   ██ ██   ██
		 * ███████ ██ ██      ██ ██ ███████ ██   ██ ██   ██
		 */
		add_action("suggest-similar", function() {
			global $pageindex, $env;
			
			$format = $_GET["format"] ?? "text";
			
			
			// TODO: Supportr history revisions here? $env->page_filename might do this for us - we should check into the behaviour here
			$similarpages = similar_suggest(
				$env->page,
				file_get_contents($env->page_filename)
			);
			
			switch ($format) {
				case "text":
					header("content-type: text/plain");
					foreach($similarpages as $pagename => $rank) {
						echo("$pagename | $rank\n");
					}
					break;
				
				case "csv":
					header("content-type: text/csv");
					echo("pagename,rank\n");
					foreach($similarpages as $pagename => $rank)
						echo("$pagename,$rank\n");
					break;
				
				case "json":
					header("content-type: application/json");
					echo(json_encode($similarpages));
				
				default:
					http_response_code(400);
					header("content-type: text/plain");
					exit("Error: The format $format wasn't recognised.\nAvailable formats for this action: text, json, csv");
					break;
			}
		});
		
		page_renderer::register_part_preprocessor(function(&$parts) {
			global $env;
			if($env->action !== "view")
				return;
			
			$html = "<aside class='similar-page-suggestions'><h2>Similar Pages</h2>\n\t\t<ul class='similar-page-suggestions-list'>\n";
			$start_time = microtime(true);
			$suggestions = similar_suggest(
				$env->page,
				file_get_contents($env->page_filename)
			);
			$env->perfdata->similar_suggest = round((microtime(true) - $start_time) * 1000, 2);
			foreach($suggestions as $suggested_pagename => $rank)
				$html .= "<li data-rank='$rank'><a href='?page=".rawurlencode($suggested_pagename)."'>".htmlentities($suggested_pagename)."</a></li>\n";
			$html .= "</ul>\n\t\t<!-- Took {$env->perfdata->similar_suggest}ms to compute similar page suggestions -->\n\t\t</aside>\n";
			
			$parts["{extra}"] = $html . $parts["{extra}"];
		});
	}
]);

/**
 * Given a page name, returns a list fo similar pages.
 * @param	string	$pagename	The name of the page to return suggestions for.
 * @param	string	$content	The content of the given page.
 * @return	array	A list of suggested page names in the format pagename => rank.
 */
function similar_suggest(string $pagename, string $content, bool $limit_output = true) : array {
	global $settings;
	$content_search = search::$literator->transliterate($content);
	$index = search::index_generate($content_search);
	$title_tokens = search::tokenize($pagename);
	foreach($title_tokens as $token) {
		if(in_array($token, search::$stop_words)) continue;
		$index[$token] = [ "freq" => 10000, "fromtitle" => true ];
	}
	search::index_sort_freq($index, true);
	search::invindex_load();
	
	
	$our_pageid = ids::getid($pagename);
	$pages = [];
	$max_count = -1;
	$i = 0;
	foreach($index as $term => $data) {
		// Only search the top 20% most common words
		// Stop words are skipped automagically
		// if($i > $max_count * 0.2) break;
		// Skip words shorter than 3 characters
		if(strlen($term) < 3) continue;
		
		// if($i > 10) break;
		
		// If this one is less than 0.2x the max frequency count, break out
		if(!isset($data["fromtitle"]))
			$max_count = max($max_count, $data["freq"]);
		if($data["freq"] < $max_count * 0.2 || $data["freq"] <= 1) break;
		
		// Check is it's present just in case (todo figure out if it's necessary)
		if(!search::invindex_term_exists($term)) continue;
		
		$otherpages = search::invindex_term_getpageids($term);
		foreach($otherpages as $pageid) {
			if($pageid == $our_pageid) continue;
			if(!isset($pages[$pageid]))
				$pages[$pageid] = 0;
			
			$amount = search::invindex_term_getoffsets($term, $pageid)->freq;
			if(isset($data["fromtitle"]))
				$amount *= 5;
			$pages[$pageid] += $amount;
		}
		
		$i++;
	}
	
	arsort($pages, SORT_NUMERIC);
	
	$result = []; $i = 0;
	foreach($pages as $pageid => $count) {
		if($limit_output && $i >= $settings->similarpages_count) break;
		$result[ids::getpagename($pageid)] = $count;
		$i++;
	}
	return $result;
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Statistics",
	"version" => "0.4.5",
	"author" => "Starbeamrainbowlabs",
	"description" => "An extensible statistics calculation system. Comes with a range of built-in statistics, but can be extended by other modules too.",
	"id" => "feature-stats",
	"code" => function() {
		global $settings, $env;
		
		/**
		 * @api {get} ?action=stats Show wiki statistics
		 * @apiName Stats
		 * @apiGroup Utility
		 * @apiPermission Anonymous
		 * @apiVersion 0.15.0
		 * @apiParam	{string}	format	Specify the format the data should be returned in. Supported formats: html (default), json.
		 * @apiParam	{string}	stat	HTML format only. If specified the page for the stat with this id is sent instead of the list of scalar stats.
		 */
		
		/*
		 * ███████ ████████  █████  ████████ ███████
		 * ██         ██    ██   ██    ██    ██
		 * ███████    ██    ███████    ██    ███████
		 *      ██    ██    ██   ██    ██         ██
		 * ███████    ██    ██   ██    ██    ███████
		 */
		add_action("stats", function() {
			global $settings, $statistic_calculators;
			
			$allowed_formats = [ "html", "json" ];
			$format = slugify($_GET["format"] ?? "html");
			
			if(!in_array($format, $allowed_formats)) {
				http_response_code(400);
				exit(page_renderer::render_main("Format error - $settings->sitename", "<p>Error: The format '$format' is not currently supported by this action on $settings->sitename. Supported formats: " . implode(", ", $allowed_formats) . "."));
			}
			
			$stats = stats_load();
			
			if($format == "json") {
				header("content-type: application/json");
				exit(json_encode($stats, JSON_PRETTY_PRINT));
			}
			
			$stat_pages_list = "<a href='?action=stats'>Main</a> | ";
			foreach($statistic_calculators as $stat_id => $stat_calculator) {
				if($stat_calculator["type"] == "scalar")
					continue;
				$stat_pages_list .= "<a href='?action=stats&stat=" . rawurlencode($stat_id) . "'>{$stat_calculator["name"]}</a> | ";
			}
			$stat_pages_list = trim($stat_pages_list, " |");
			
			if(!empty($_GET["stat"]) && !empty($statistic_calculators[$_GET["stat"]])) {
				$stat_calculator = $statistic_calculators[$_GET["stat"]];
				$content = "<h1>{$stat_calculator["name"]} - Statistics</h1>\n";
				$content .= "<p>$stat_pages_list</p>\n";
				switch($stat_calculator["type"]) {
					case "page-list":
						if(!module_exists("page-list")) {
							$content .= "<p>$settings->sitename doesn't current have the page listing module installed, so HTML rendering of this statistic is currently unavailable. Try " . hide_email($settings->admindetails_email, "contacting ".htmlentities($settings->admindetails_name)) . ", $settings->sitename's administrator and asking then to install the <code>page-list</code> module.</p>";
							break;
						}
						$content .= "<p><strong>Count:</strong> " . count($stats->{$_GET["stat"]}->value) . "</p>\n";
						$content .= generate_page_list($stats->{$_GET["stat"]}->value);
						break;
					
					case "page":
						$content .= $stat_calculator["render"]($stats->{$_GET["stat"]});
						break;
				}
			}
			else
			{
				$content = "<h1>Statistics</h1>\n";
				$content .= "<p>This page contains a selection of statistics about $settings->sitename's content. They are updated automatically about every " . trim(str_replace(["ago", "1 "], [""], human_time($settings->stats_update_interval))) . ", although $settings->sitename's local friendly moderators may update them earlier (you can see their names at the bottom of every page).</p>\n";
				$content .= "<p>$stat_pages_list</p>\n";
				
				$content .= "<table class='stats-table'>\n";
				$content .= "\t<tr><th>Statistic</th><th>Value</th></tr>\n\n";
				foreach($statistic_calculators as $stat_id => $stat_calculator) {
					if($stat_calculator["type"] !== "scalar")
						continue;
					
					$content .= "\t<tr><td>{$stat_calculator["name"]}</td><td>{$stats->$stat_id->value}</td></tr>\n";
				}
				$content .= "</table>\n";
			}
			
			exit(page_renderer::render_main("Statistics - $settings->sitename", $content));
		});
		
		/**
		 * @api {get|post} ?action=stats-update Recalculate the wiki's statistics
		 * @apiName UpdateStats
		 * @apiGroup Utility
		 * @apiPermission Administrator
		 * @apiVersion 0.15.0
		 * @apiParam	{string}	secret	POST only, optional. If you're not logged in, you can specify the wiki's sekret instead (find it in peppermint.json) using this parameter.
		 * @apiParam	{bool}		force	Whether the statistics should be recalculated anyway - even if they have already recently been recalculated. Default: no. Supported values: yes, no.
		 */
		
		/*
		 * ███████ ████████  █████  ████████ ███████
		 * ██         ██    ██   ██    ██    ██
		 * ███████    ██    ███████    ██    ███████
		 *      ██    ██    ██   ██    ██         ██
		 * ███████    ██    ██   ██    ██    ███████
		 * 
		 * ██    ██ ██████  ██████   █████  ████████ ███████
		 * ██    ██ ██   ██ ██   ██ ██   ██    ██    ██
		 * ██    ██ ██████  ██   ██ ███████    ██    █████
		 * ██    ██ ██      ██   ██ ██   ██    ██    ██
		 *  ██████  ██      ██████  ██   ██    ██    ███████
		 */
		add_action("stats-update", function() {
			global $env, $paths, $settings;
			
			
			if(!$env->is_admin &&
				(
					empty($_POST["secret"]) ||
					$_POST["secret"] !== $settings->secret
				)
			)
				exit(page_renderer::render_main("Error - Recalculating Statistics - $settings->sitename", "<p>You need to be logged in as a moderator or better to get $settings->sitename to recalculate it's statistics. If you're logged in, try <a href='?action=logout'>logging out</a> and logging in again as a moderator. If you aren't logged in, try <a href='?action=login&returnto=%3Faction%3Dstats-update'>logging in</a>.</p>"));
			
			// Delete the old stats cache
			if(file_exists($paths->statsindex))
				unlink($paths->statsindex);
			
			update_statistics(true, ($_GET["force"] ?? "no") == "yes");
			header("content-type: application/json");
			echo(file_get_contents($paths->statsindex) . "\n");
		});
		
		add_help_section("150-statistics", "Statistics", "<p>$settings->sitename records some statistics about itself, including the number of pages, the longest pages, the most wanted pages, the most linked-to pages, and more. They are updated roughly every " . human_time($settings->stats_update_interval) . ", though moderators may occasionally update them sooner.</p>
		<p>You can see these statistics <a href='?action=stats'>here</a>.</p>");
		
		//////////////////////////
		/// Built-in Statisics ///
		//////////////////////////
		

		statistic_add([
			"id" => "user_count",
			"name" => "Users",
			"type" => "scalar",
			"update" => function($old_stats) {
				global $settings;
				
				$result = new stdClass(); // completed, value, state
				$result->completed = true;
				$result->value = count(get_object_vars($settings->users));
				return $result;
			}
		]);
		
		statistic_add([
			"id" => "longest-pages",
			"name" => "Longest Pages",
			"type" => "page-list",
			"update" => function($old_stats) {
				global $pageindex;
				
				$result = new stdClass(); // completed, value, state
				$pages = [];
				foreach($pageindex as $pagename => $pagedata) {
					$pages[$pagename] = $pagedata->size;
				}
				arsort($pages);
				
				$result->value = array_keys($pages);
				$result->completed = true;
				return $result;
			}
		]);

		statistic_add([
			"id" => "page_count",
			"name" => "Page Count",
			"type" => "scalar",
			"update" => function($old_stats) {
				global $pageindex;
				
				$result = new stdClass(); // completed, value, state
				$result->completed = true;
				$result->value = count(get_object_vars($pageindex));
				return $result;
			}
		]);

		statistic_add([
			"id" => "file_count",
			"name" => "File Count",
			"type" => "scalar",
			"update" => function($old_stats) {
				global $pageindex;
				
				$result = new stdClass(); // completed, value, state
				$result->completed = true;
				$result->value = 0;
				foreach($pageindex as $pagename => $pagedata) {
					if(!empty($pagedata->uploadedfile) && $pagedata->uploadedfile)
						$result->value++;
				}
				return $result;
			}
		]);

		statistic_add([
			"id" => "redirect_count",
			"name" => "Redirect Pages",
			"type" => "scalar",
			"update" => function($old_stats) {
				global $pageindex;
				
				$result = new stdClass(); // completed, value, state
				$result->completed = true;
				$result->value = 0;
				foreach($pageindex as $pagename => $pagedata) {
					if(!empty($pagedata->redirect) && $pagedata->redirect)
						$result->value++;
				}
				return $result;
			}
		]);
		
		// Perform an automatic recalculation of the statistics if needed, but only if we're not on the CLI
		if($env->action !== "stats-update" && !is_cli())
			update_statistics(false);
		
		
		/*
		 *  ██████ ██      ██
		 * ██      ██      ██
		 * ██      ██      ██
		 * ██      ██      ██
		 *  ██████ ███████ ██
		 */
		if(module_exists("feature-cli")) {
			cli_register("stats", "Interact with and update the wiki statistics", function(array $args) : int {
				global $settings, $env;
				if(count($args) < 1) {
					echo("stats: interact with an manipulate the wiki statistics
Usage:
	stats {subcommand}

Subcommands:
	recalculate     Recalculates the statistics
	show            Shows the current statistics
");
					return 0;
				}
				
				switch($args[0]) {
					case "recalculate":
						echo("Updating statistics - ");
						$start_time = microtime(true);
						update_statistics(true, true);
						echo("done in ".round((microtime(true) - $start_time) * 1000, 2)."ms\n");
						echo("Recalculated {$env->perfdata->stats_recalcuated} statistics in {$env->perfdata->stats_calctime}ms (not including serialisation / saving to disk)\n");
						break;
					case "show":
						$stats = stats_load();
						foreach($stats as $name => $stat) {
							$lastupdated = render_timestamp($stat->lastupdated, true, false);
							if(is_object($stat->value)) {
								echo("*** $stat->name *** (last updated $lastupdated)\n");
								$i = 0;
								foreach($stat->value as $key => $value) {
									if($i >= 25) break;
									echo("$key: $value\n");
									$i++;
								}
							}
							else if(is_array($stat->value)) {
								// Display array differently, and truncate to 25 entries
								echo("*** $stat->name *** (last updated $lastupdated)\n");
								echo(implode("\n", array_slice($stat->value, 0, 25)));
								echo("\n");
							}
							else
								echo("$stat->name: ".var_export($stat->value, true)." (last updated $lastupdated)\n");
							
							echo("\n");
						}
						break;
				}
				return 0;
			});
		}
	}
]);

/**
 * Updates the wiki's statistics.
 * @package feature-stats
 * @param  bool $update_all Whether all the statistics should be checked and recalculated, or just as many as we have time for according to the settings.
 * @param  bool $force      Whether we should recalculate statistics that don't currently require recalculating anyway.
 */
function update_statistics($update_all = false, $force = false)
{
	global $settings, $env, $paths, $statistic_calculators;
	
	// If the firstrun wizard isn't complete, then there's no point in updating the statistics index
	if(isset($settings->firstrun_complete) && $settings->firstrun_complete == false)
		return;
	
	$stats_mtime = file_exists($paths->statsindex) ? filemtime($paths->statsindex) : 0;
	
	// Clear the existing statistics if we are asked to recalculate them all
	if($force)
		stats_save(new stdClass());
	// If the stats index exists and has been modified recently, then don't 
	// even bother to load it
	// This is an important optimisation, because json_decode is *slow*
	else if(file_exists($paths->statsindex) && time() - $stats_mtime < $settings->stats_update_interval)
		return;
	
	$stats = stats_load();
	
	$start_time = microtime(true);
	$ran_out_of_time = false;
	$stats_updated = 0;
	foreach($statistic_calculators as $stat_id => $stat_calculator)
	{
		// If statistic doesn't exist or it's out of date then we should recalculate it.
		// Otherwise, leave it and continue on to the next stat.
		if(!empty($stats->$stat_id) && $start_time - $stats->$stat_id->lastupdated < $settings->stats_update_interval)
			continue;
		
		$mod_start_time = microtime(true);
		
		// Run the statistic calculator, passing in the existing stats data
		$calculated = $stat_calculator["update"](!empty($stats->$stat_id) ? $stats->$stat_id : new stdClass());
		
		$new_stat_data = new stdClass();
		$new_stat_data->id = $stat_id;
		$new_stat_data->name = $stat_calculator["name"];
		$new_stat_data->lastupdated = $calculated->completed ? $mod_start_time : $stats->$stat_id->lastupdated;
		$new_stat_data->value = $calculated->value;
		if(!empty($calculated->state))
			$new_stat_data->state = $calculated->state;
		
		// Save the new statistics
		$stats->$stat_id = $new_stat_data;
		
		$stats_updated++;
		
		// Check to make sure we haven't run out of time to update the statistics this session
		if(!$update_all && microtime(true) - $start_time >= $settings->stats_update_processingtime) {
			$ran_out_of_time = true;
			break;
		}
	}
	
	$env->perfdata->stats_recalcuated = $stats_updated;
	$env->perfdata->stats_calctime = round((microtime(true) - $start_time)*1000, 3);
	
	if(!is_cli()) {
		header("x-stats-recalculated: {$env->perfdata->stats_recalcuated}");
		//round((microtime(true) - $pageindex_read_start)*1000, 3)
		header("x-stats-calctime: {$env->perfdata->stats_calctime}ms");
	}
	
	stats_save($stats);
	// If we ran out of time, reset the mtime for performance reasons (see the 
	// beginning of this function)
	if($ran_out_of_time) 
		touch($paths->statsindex, $stats_mtime);
}

/**
 * Loads and returns the statistics cache file.
 * @package	feature-stats
 * @return	object		The loaded & decoded statistics.
 */
function stats_load()
{
	global $paths;
	static $stats = null;
	if($stats == null)
		$stats = file_exists($paths->statsindex) ? json_decode(file_get_contents($paths->statsindex)) : new stdClass();
	return $stats;
}
/**
 * Saves the statistics back to disk.
 * @package	feature-stats
 * @param	object	The statistics cache to save.
 * @return	bool	Whether saving succeeded or not.
 */
function stats_save($stats)
{
	global $paths;
	return file_put_contents($paths->statsindex, json_encode($stats, JSON_PRETTY_PRINT) . "\n");
}


/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Theme Gallery",
	"version" => "0.4.1",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a theme gallery page and optional automatic theme updates. Contacts a remote server, where IP addresses are stored in automatic server logs for security and attack mitigation purposes.",
	"id" => "feature-theme-gallery",
	"code" => function() {
		global $settings, $env;
		/**
		 * @api {get} ?action=theme-gallery Display the theme gallery
		 * @apiName ThemeGallery
		 * @apiGroup Utility
		 * @apiPermission Moderator
		 */
		
		add_action("theme-gallery", function() {
			global $settings, $env;
			
			if(!$env->is_admin) {
				$errorMessage = "<p>You don't have permission to change $settings->sitename's theme.</p>\n";
				if(!$env->is_logged_in)
					$errorMessage .= "<p>You could try <a href='?action=login&returnto=%3Faction%3Dconfigure'>logging in</a>.</p>";
				else
					$errorMessage .= "<p>You could try <a href='?action=logout&returnto=%3Faction%3Dconfigure'>logging out</a> and then <a href='?action=login&returnto=%3Faction%3Dconfigure'>logging in</a> again with a different account that has the appropriate privileges.</a>.</p>";
				exit(page_renderer::render_main("Error - $settings->sitename", $errorMessage));
			}
			
			$gallery_urls = explode(" ", $settings->css_theme_gallery_index_url);
			
			if(!isset($_GET["load"]) || $_GET["load"] !== "yes") {
				$result = "<h1>Theme Gallery</h1>
				<p>Load the theme gallery? A HTTP request will be made to the following endpoints:</p>
				<ul>";
				foreach($gallery_urls as $url) {
					$result .= "<li><a href='".htmlentities($url)."'>".htmlentities($url)."</a></li>\n";
				}
				$result .= "</ul>
				<p>...with the following user agent string: <code>".htmlentities (ini_get("user_agent"))."</code></p>
				<p>No external HTTP requests will be made without your consent.</p>
				<p><a href='?action=theme-gallery&load=yes'>Ok, load the gallery</a>.</p>
				<p> <a href='javascript:history.back();'>Actually, take me back</a>.</p>";
				exit(page_renderer::render_main("Theme Gallery - $settings->sitename", $result));
			}
			
			$themes_available = [];
			
			foreach($gallery_urls as $url) {
				if(empty($url)) continue;
				$next_obj = json_decode(@file_get_contents($url));
				if(empty($next_obj)) {
					http_response_code(503);
					exit(page_renderer::render_main("Error - Theme Gallery - $settings->sitename", "<p>Error: Failed to download theme index file from <code>" . htmlentities($url) . "</code>."));
				}
				
				foreach($next_obj as $theme) {
					$theme->index_url = $url;
					$theme->root = dirname($url) . "/{$theme->id}";
					$theme->url = "{$theme->root}/theme.css";
					$theme->preview_large = "{$theme->root}/preview_large.png";
					$theme->preview_small = "{$theme->root}/preview_small.png";
					$themes_available[] = $theme;
				}
			}
			
			$sorter = new Collator("");
			usort($themes_available, function($a, $b) use ($sorter) : int {
				return $sorter->compare($a->name, $b->name);
			});
			
			
			
			$content = "<h1>Theme Gallery</h1>
			<p>$settings->sitename is currently using ".(strlen($settings->css_theme_autoupdate_url) > 0 ? "an external" : "the internal")." theme".(strlen($settings->css_theme_autoupdate_url) > 0 ? " (<a href='?action=theme-gallery-select&amp;theme-selector=default-internal'>reset to the internal default theme</a>)" : "").".</p>
			<form method='get' action='index.php'>
			<input type='hidden' name='action' value='theme-gallery-select' />
			<div class='grid-large theme-list'>\n";
			foreach($themes_available as $theme) {
				$selected = $theme->id == $settings->css_theme_gallery_selected_id ? " selected" : "";
				$content .= "<div class='theme-item'>
					<a href='" . htmlentities($theme->preview_large) . "'><img src='" . htmlentities($theme->preview_small) . "' title='Click to enlarge' /></a><br />
					<input type='radio' id='" . htmlentities($theme->id) . "' name='theme-selector' value='" . htmlentities($theme->id) . "' required$selected />
					<label class='link-display-label' for='" . htmlentities($theme->id) . "'>" . htmlentities($theme->name) . "</label>
					<p>" . str_replace("\n", "</p>\n<p>", htmlentities($theme->description)) . "</p>
					<p>By <a href='" . htmlentities($theme->author_link) . "'>" . htmlentities($theme->author) . "</a> (<a href='" . htmlentities($theme->url) . "'>View CSS</a>, <a href='" . htmlentities($theme->index_url) . "'>View Index</a>)
				</div>";
			}
			$content .= "</div>
			<p><strong>Warning: If you've altered $settings->sitename's CSS by changing the value of the <code>css</code> setting, then your changes will be overwritten by clicking the button below! If necessary, move your changes to the <code>css_custom</code> setting first before continuing here.</strong></p>
			<input type='submit' class='large' value='Change Theme' />
			</form>";
			
			exit(page_renderer::render_main("Theme Gallery - $settings->sitename", "$content"));
			
		});
		
		/**
		 * @api {get} ?action=theme-gallery-select&theme-selector=theme-id	Set the site theme
		 * @apiName ThemeGallerySelect
		 * @apiGroup Utility
		 * @apiPermission Moderator
		 * 
		 * @apiParam	{string}	theme-selector	The id of the theme to switch into, or 'default-internal' to switch back to the internal theme.
		 */
		add_action("theme-gallery-select", function() {
			global $env, $settings, $guiConfig;
			
			if(!$env->is_admin) {
				$errorMessage = "<p>You don't have permission to change $settings->sitename's theme.</p>\n";
				if(!$env->is_logged_in)
					$errorMessage .= "<p>You could try <a href='?action=login&returnto=%3Faction%3Dconfigure'>logging in</a>.</p>";
				else
					$errorMessage .= "<p>You could try <a href='?action=logout&returnto=%3Faction%3Dconfigure'>logging out</a> and then <a href='?action=login&returnto=%3Faction%3Dconfigure'>logging in</a> again with a different account that has the appropriate privileges.</a>.</p>";
				exit(page_renderer::render_main("Error - $settings->sitename", $errorMessage));
			}
			
			if(!isset($_GET["theme-selector"])) {
				http_response_code(400);
				exit(page_renderer::render_main("No theme selected - Error - $settings->sitename", "<p>Oops! Looks like you didn't select a theme. Try <a href='?action=theme-gallery'>going back</a> and selecting one.</p>"));
			}
			
			if($_GET["theme-selector"] === "default-internal") {
				$settings->css_theme_gallery_selected_id = $guiConfig->css_theme_gallery_selected_id->default;
				$settings->css_theme_autoupdate_url = $guiConfig->css_theme_autoupdate_url->default;
				$settings->css = $guiConfig->css->default;
				
				if(!save_settings()) {
					http_response_code(503);
					exit(page_renderer::render_main("Server error - $settings->sitename", "<p>Oops! $settings->sitename wasn't able to save the <code>peppermint.json</code> settings file back to disk. If you're the administrator, try checking the permissions on disk. If not, try contacting $settings->sitename's administrator, who's contact details can be found at the bottom of every page.</p>"));
				}
				
				exit(page_renderer::render_main("Theme reset - Theme Gallery - $settings->sitename", "<p>$settings->sitename's theme has been reset  to the internal theme.</p>
				<p>Go to the <a href='?action=$settings->defaultaction'>homepage</a>.</p>"));
			}
			
			// Set the new theme's id
			$settings->css_theme_gallery_selected_id = $_GET["theme-selector"];
			$gallery_urls = explode(" ", $settings->css_theme_gallery_index_url);
			
			// Find the URL of the selected theme
			// FUTURE: Figure out a way to pass this information through the UI interface instead to avoid a re-download?
			$theme_autoupdate_url = null;
			foreach($gallery_urls as $url) {
				$next_index = json_decode(@file_get_contents($url));
				if(empty($next_index)) {
					error_log("[PeppermintyWiki/$settings->sitename/theme_gallery] Error: Failed to download theme index file from '$url' when setting the wiki theme.");
					continue;
				}
				foreach($next_index as $next_theme) {
					if($next_theme->id == $settings->css_theme_gallery_selected_id) {
						$theme_autoupdate_url = dirname($url) . "/{$next_theme->id}/theme.css";
						break;
					}
				}
				if($theme_autoupdate_url !== null) break;
			}
			if($theme_autoupdate_url === null) {
				http_response_code(503);
				exit(page_renderer::render_main("[PeppermintyWiki/$settings->sitename/theme_gallery] Failed to set theme - Error - $settings->sitename)", "<p>Oops! $settings->sitename couldn't find the theme you selected. Perhaps it has been changed or deleted, or perhaps there was an error during the download process.</p>
				<p>Try <a href='?action=theme-gallery'>heading back to the theme gallery</a> and trying again.</p>"));
			}
			$settings->css_theme_autoupdate_url = $theme_autoupdate_url;
			
			if(!theme_update(true)) {
				http_response_code(503);
				exit(page_renderer::render_main("Failed to download theme - $settings->sitename", "<p>Oops! $settings->sitename wasn't able to download the theme you selected. If you're the administrator, try checking the PHP server logs. If not, try contacting $settings->sitename's administrator, who's contact details can be found at the bottom of every page.</p>"));
			}
			
			// TODO: Add option to disable theme updates
			
			if(!save_settings()) {
				http_response_code(503);
				exit(page_renderer::render_main("Server error - $settings->sitename", "<p>Oops! $settings->sitename wasn't able to save the <code>peppermint.json</code> settings file back to disk. If you're the administrator, try checking the permissions on disk. If not, try contacting $settings->sitename's administrator, who's contact details can be found at the bottom of every page.</p>"));
			}
			
			http_response_code(200);
			exit(page_renderer::render_main("Theme Changed - $settings->sitename", "<p>$settings->sitename's theme was changed successfully to ".htmlentities($settings->css_theme_gallery_selected_id).".</p>
			<p>Go to the <a href='?action=$settings->defaultaction'>homepage</a>.</p>"));
		});
		
		if($env->is_admin) add_help_section("945-theme-gallery", "Changing the theme", "<p>$settings->sitename allows you to change the theme by selecting a theme from the public theme gallery. You can <a href='?action=theme-gallery'>visit the theme gallery</a> to take a look. The theme gallery does make a remote HTTP request, but a warning is displayed before this is performed. Once a theme is downloaded, occasional (but infrequent) HTTP requests are made to make sure it is up to date.</p>
		<p>Note that when using a theme from the  theme gallery, the internal theme is disabled. There is a button to disable any loaded theme gallery theme though.</p>
		<p>The default theme has support for the <a href='https://starbeamrainbowlabs.com/blog/article.php?article=posts/353-prefers-color-scheme.html'><code>prefers-color-scheme</code></a> CSS media query, enabling it to be dark or light depending on your operating system preference.</p>");
	}
]);

/**
 * Updates the currently selected theme by fetching it from a remote url.
 * @param	bool	$force_update Whether to force an update - even if we've already updated recently.
 * @return	bool	Whether the update was sucessful. It might fail because of network issues, or the theme update requires a newer version of Pepperminty Wiki than is currently installed.
 */
function theme_update($force_update = false) : bool {
	global $version, $settings;
	
	// If there's no url to update from or updates are disabled, then we're done here
	if(empty($settings->css_theme_autoupdate_url) || $settings->css_theme_autoupdate_interval < 0)
		return true;
	
	// If it's not time for an update, then end here
	// ...unless we're supposed to force an update
	if(time() - $settings->css_theme_autoupdate_lastcheck < $settings->css_theme_autoupdate_interval || !$force_update)
		return true;
	
	// Fetch the new css
	$new_css = @file_get_contents($settings->css_theme_autoupdate_url);
	// Make sure it's valid
	if(empty($new_css)) {
		error_log("[PeppermintyWiki/$settings->sitename/theme_gallery] Error: Failed to update theme: Got an error while trying to download theme update from $settings->css_theme_autoupdate_url");
		return false;
	}
	
	// TODO: Check the hash against themeindex.json?
	
	$min_version_loc = strpos($new_css, "@minversion") + strlen("@minversion");
	$min_version = substr($new_css, $min_version_loc, strpos($new_css, "\n", $min_version_loc));
	if(version_compare($version, $min_version) == -1) {
		error_log("[PeppermintyWiki/$settings->sitename/theme_gallery] Error: Failed to update theme: $settings->css_theme_gallery_selected_id requires Pepperminty Wiki $min_version, but $version is installed.");
		return false;
	}
	
	// If the css is identical to the string we've got stored already, then no point in updating
	if($new_css == $settings->css)
		return true;
	
	$settings->css = $new_css;
	
	return save_settings();
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Uploader",
	"version" => "0.7.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds the ability to upload files to Pepperminty Wiki. Uploaded files act as pages and have the special 'File/' prefix.",
	"id" => "feature-upload",
	"code" => function() {
		global $settings;
		/**
		 * @api {get} ?action=upload[&avatar=yes] Get a page to let you upload a file.
		 * @apiName UploadFilePage
		 * @apiGroup Upload
		 * @apiPermission User
		 *
		 * @apiParam	{bool}	avatar	Optional. If true then a special page to upload your avatar is displayed instead.
		 */
		
		/**
		 * @api {post} ?action=upload Upload a file
		 * @apiName UploadFile
		 * @apiGroup Upload
		 * @apiPermission User
		 *
		 * @apiParam {string}	name		The name of the file to upload.
		 * @apiParam {string}	description	A description of the file.
		 * @apiParam {file}		file		The file to upload.
		 * @apiParam {bool}	avatar		Whether this upload should be uploaded as the current user's avatar. If specified, any filenames provided will be ignored.
		 *
		 * @apiUse	UserNotLoggedInError
		 * @apiError	UploadsDisabledError	Uploads are currently disabled in the wiki's settings.
		 * @apiError	UnknownFileTypeError	The type of the file you uploaded is not currently allowed in the wiki's settings.
		 * @apiError	ImageDimensionsFiledError	PeppermintyWiki couldn't obtain the dimensions of the image you uploaded.
		 * @apiError	DangerousFileError		The file uploaded appears to be dangerous.
		 * @apiError	DuplicateFileError		The filename specified is a duplicate of a file that already exists.
		 * @apiError	FileTamperedError		Pepperminty Wiki couldn't verify that the file wasn't tampered with during theupload process.
		 */
		
		/*
		 * ██    ██ ██████  ██       ██████   █████  ██████
		 * ██    ██ ██   ██ ██      ██    ██ ██   ██ ██   ██
		 * ██    ██ ██████  ██      ██    ██ ███████ ██   ██
		 * ██    ██ ██      ██      ██    ██ ██   ██ ██   ██
		 *  ██████  ██      ███████  ██████  ██   ██ ██████
		 */
		add_action("upload", function() {
			global $settings, $env, $pageindex, $paths;
			
			$is_avatar = !empty($_POST["avatar"]) || !empty($_GET["avatar"]);
			
			switch($_SERVER["REQUEST_METHOD"])
			{
				case "GET":
					// Send upload page
					
					if(!$settings->upload_enabled)
						exit(page_renderer::render("Upload Disabled - $setting->sitename", "<p>You can't upload anything at the moment because $settings->sitename has uploads disabled. Try contacting $settings->admindetails_name, your site Administrator. <a href='javascript:history.back();'>Go back</a>.</p>"));
					if(!$env->is_logged_in)
						exit(page_renderer::render("Upload Error - $settings->sitename", "<p>You are not currently logged in, so you can't upload anything.</p>
		<p>Try <a href='?action=login&returnto=" . rawurlencode("?action=upload") . "'>logging in</a> first.</p>"));
					
					if($is_avatar) {
						exit(page_renderer::render("Upload avatar - $settings->sitename", "<h1>Upload avatar</h1>
			<p>Select an image below, and then press upload. $settings->sitename currently supports the following file types (though not all of them may be suitable for an avatar): " . htmlentities(implode(", ", $settings->upload_allowed_file_types)) . "</p>
			<form method='post' action='?action=upload' enctype='multipart/form-data'>
				<label for='file'>Select a file to upload.</label>
				<input type='file' name='file' id='file-upload-selector' tabindex='1' />
				<br />
				
				<p class='editing_message'>$settings->editing_message</p>
				<input type='hidden' name='avatar' value='yes' />
				<input type='submit' value='Upload' tabindex='20' />
			</form>"));
					}
					
					exit(page_renderer::render("Upload - $settings->sitename", "<h1>Upload file</h1>
		<p>Select an image or file below, and then type a name for it in the box. This server currently supports uploads up to " . human_filesize(get_max_upload_size()) . " in size.</p>
		<p>$settings->sitename currently supports uploading of the following file types: " . implode(", ", $settings->upload_allowed_file_types) . ".</p>
		<form method='post' action='?action=upload' enctype='multipart/form-data'>
			<label for='file-upload-selector'>Select a file to upload.</label>
			<input type='file' name='file' id='file-upload-selector' tabindex='1' />
			<br />
			<label for='file-upload-name'>Name:</label>
			<input type='text' name='name' id='file-upload-name' tabindex='5'  />
			<br />
			<label for='description'>Description:</label>
			<textarea name='description' tabindex='10'></textarea>
			<p class='editing_message'>$settings->editing_message</p>
			<input type='submit' value='Upload' tabindex='20' />
		</form>
		<script>
			document.getElementById('file-upload-selector').addEventListener('change', function(event) {
				var newName = event.target.value.substring(event.target.value.lastIndexOf(\"\\\\\") + 1, event.target.value.lastIndexOf(\".\"));
				console.log('Changing content of name box to:', newName);
				document.getElementById('file-upload-name').value = newName;
			});
		</script>"));
					
					break;
				
				case "POST":
					// Receive file
					
					if(!$settings->editing) {
						exit(page_renderer::render_main("Upload failed - $settings->sitename", "<p>Your upload couldn't be processed because editing is currently disabled on $settings->sitename. Please contact ".htmlentities($settings->admindetails_name).", $settings->sitename's administrator for more information - their contact details can be found at the bottom of this page. <a href='index.php'>Go back to the main page</a>."));
					}
					
					// Make sure uploads are enabled
					if(!$settings->upload_enabled)
					{
						if(!empty($_FILES["file"]) && file_exists($_FILES["file"]["tmp_name"]))
							unlink($_FILES["file"]["tmp_name"]);
						http_response_code(412);
						exit(page_renderer::render("Upload failed - $settings->sitename", "<p>Your upload couldn't be processed because uploads are currently disabled on $settings->sitename. <a href='index.php'>Go back to the main page</a>.</p>"));
					}
					
					// Make sure that the user is logged in
					if(!$env->is_logged_in)
					{
						if(!empty($_FILES["file"]) && file_exists($_FILES["file"]))
							unlink($_FILES["file"]["tmp_name"]);
						http_response_code(401);
						exit(page_renderer::render("Upload failed - $settings->sitename", "<p>Your upload couldn't be processed because you are not logged in.</p><p>Try <a href='?action=login&returnto=" . rawurlencode("?action=upload") . "'>logging in</a> first."));
					}
					
					// Check for php upload errors
					if($_FILES["file"]["error"] > 0)
					{
						if(!empty($_FILES["file"]) && !empty($_FILES["file"]["tmp_name"]) && file_exists($_FILES["file"]["tmp_name"]))
							unlink($_FILES["file"]["tmp_name"]);
						if($_FILES["file"]["error"] == 1 || $_FILES["file"]["error"] == 2)
							http_response_code(413); // file is too large
						else
							http_response_code(500); // something else went wrong
						exit(page_renderer::render("Upload failed - $settings->sitename", "<p>Your upload couldn't be processed because " . (($_FILES["file"]["error"] == 1 || $_FILES["file"]["error"] == 2) ? "the file is too large" : "an error occurred") . ".</p><p>Please contact ".htmlentities($settings->admindetails_name).", $settings->sitename's administrator for help.</p>"));

					}
					
					if(!function_exists("finfo_file")) {
						http_response_code(503);
						exit(page_renderer::render("Upload failed - Server error - $settings->sitename", "<p>Your upload couldn't be processed because <code>fileinfo</code> is not installed on the server. This is required to properly check the file type of uploaded files.</p>><p>Please contact ".htmlentities($settings->admindetails_name).", $settings->sitename's administrator for help.</p>"));
					}
					
					// Calculate the target name, removing any characters we
					// are unsure about.
					// Also trim off whitespace (from both ends), and full stops (from the end)
					$target_name = rtrim(trim(makepathsafe($_POST["name"] ?? "Users/$env->user/Avatar")), ".");
					$temp_filename = $_FILES["file"]["tmp_name"];
					
					$mimechecker = finfo_open(FILEINFO_MIME_TYPE);
					$mime_type = finfo_file($mimechecker, $temp_filename);
					finfo_close($mimechecker);
					
					if(!in_array($mime_type, $settings->upload_allowed_file_types))
					{
						http_response_code(415);
						exit(page_renderer::render("Unknown file type - Upload error - $settings->sitename", "<p>$settings->sitename recieved the file you tried to upload successfully, but detected that the type of file you uploaded is not in the allowed file types list. The file has been discarded.</p>
						<p>The file you tried to upload appeared to be of type <code>$mime_type</code>, but $settings->sitename currently only allows the uploading of the following file types: <code>" . htmlentities(implode("</code>, <code>", $settings->upload_allowed_file_types)) . "</code>.</p>
						<p><a href='?action=$settings->defaultaction'>Go back</a> to the Main Page.</p>"));
					}
					
					// Perform appropriate checks based on the *real* filetype
					if($is_avatar && substr($mime_type, 0, strpos($mime_type, "/")) !== "image") {
						http_response_code(415);
						exit(page_renderer::render_main("Error uploading avatar - $settings->sitename", "<p>That file appears to be unsuitable as an avatar, as $settings->sitename has detected it to be of type <code>".htmlentities($mime_type)."</code>, which doesn't appear to be an image. Please try <a href='?action=upload&avatar=yes'>uploading a different file</a> to use as your avatar.</p>"));
					}
					
					switch(substr($mime_type, 0, strpos($mime_type, "/")))
					{
						case "image":
							$extra_data = [];
							// Check SVG uploads with a special function
							$imagesize = $mime_type !== "image/svg+xml" ? getimagesize($temp_filename, $extra_data) : upload_check_svg($temp_filename);
							
							// Make sure that the image size is defined
							if(!is_int($imagesize[0]) or !is_int($imagesize[1]))
							{
								http_response_code(415);
								exit(page_renderer::render("Upload Error - $settings->sitename", "<p>Although the file that you uploaded appears to be an image, $settings->sitename has been unable to determine it's dimensions. The uploaded file has been discarded. <a href='?action=upload'>Go back to try again</a>.</p>
								<p>You may wish to consider <a href='https://github.com/sbrl/Pepperminty-Wiki'>opening an issue</a> against Pepperminty Wiki (the software that powers $settings->sitename) if this isn't the first time that you have seen this message.</p>"));
							}
							break;
					}
					
					$file_extension = system_mime_type_extension($mime_type);
					
					// Override the detected file extension if a file extension
					// is explicitly specified in the settings
					if(isset($settings->mime_mappings_overrides->$mime_type))
						$file_extension = $settings->mime_mappings_overrides->$mime_type;
					
					if(in_array($file_extension, [ "phtml", "php5", "php", ".htaccess", "asp", "aspx" ]))
					{
						http_response_code(415);
						exit(page_renderer::render("Upload Error - $settings->sitename", "<p>The file you uploaded appears to be dangerous and has been discarded. Please contact $settings->sitename's administrator for assistance.</p>
						<p>Additional information: The file uploaded appeared to be of type <code>".htmlentities($mime_type)."</code>, which mapped onto the extension <code>".htmlentities($file_extension)."</code>. This file extension has the potential to be executed accidentally by the web server.</p>"));
					}
					
					// Remove dots from both ends, just in case
					$file_extension = trim($file_extension, ".");
					
					// Rewrite the name to include the _actual_ file extension we've cleverly calculated :D
					
					// The path to the place (relative to the wiki data root)
					// that we're actually going to store the uploaded file itself
					$new_filename = "$paths->upload_file_prefix$target_name.$file_extension";
					// The path (relative, as before) to the description file
					$new_description_filename = "$new_filename.md";
					
					// The page path that the new file will be stored under
					$new_pagepath = $new_filename;
					
					// Rewrite the paths to store avatars in the right place
					if($is_avatar) {
						$new_pagepath = $target_name;
						$new_filename = "$target_name.$file_extension";
					}
					
					if(isset($pageindex->$new_pagepath) && !$is_avatar)
						exit(page_renderer::render("Upload Error - $settings->sitename", "<p>A page or file has already been uploaded with the name '".htmlentities($new_filename)."'. Try deleting it first. If you do not have permission to delete things, try contacting one of the moderators.</p>"));
					
					// Delete the previously uploaded avatar, if it exists
					// In the future we _may_ not need this once we have
					// file history online.
					if($is_avatar && isset($pageindex->$new_pagepath) && $pageindex->$new_pagepath->uploadedfile)
						unlink($pageindex->$new_pagepath->uploadedfilepath);

					// Make sure that the place we're uploading to exists
					if(!file_exists(dirname($env->storage_prefix . $new_filename)))
						mkdir(dirname($env->storage_prefix . $new_filename), 0775, true);
					
					if(!move_uploaded_file($temp_filename, $env->storage_prefix . $new_filename))
					{
						http_response_code(409);
						exit(page_renderer::render("Upload Error - $settings->sitename", "<p>The file you uploaded was valid, but $settings->sitename couldn't verify that it was tampered with during the upload process. This probably means that either is a configuration error, or that $settings->sitename has been attacked. Please contact ".htmlentities($settings->admindetails_name).", your $settings->sitename Administrator.</p>"));
					}
					
					$description = $_POST["description"] ?? "_(No description provided)_\n";
					
					// Escape the raw html in the provided description if the setting is enabled
					if($settings->clean_raw_html)
						$description = htmlentities($description, ENT_QUOTES);
					
					file_put_contents($env->storage_prefix . $new_description_filename, $description);
					
					// Construct a new entry for the pageindex
					$entry = new stdClass();
					// Point to the description's filepath since this property
					// should point to a markdown file
					$entry->filename = $new_description_filename;
					$entry->size = strlen($description ?? "(No description provided)");
					$entry->lastmodified = time();
					$entry->lasteditor = $env->user;
					$entry->uploadedfile = true;
					$entry->uploadedfilepath = $new_filename;
					$entry->uploadedfilemime = $mime_type;
					// Add the new entry to the pageindex
					// Assign the new entry to the image's filepath as that
					// should be the page name.
					$pageindex->$new_pagepath = $entry;
					
					// Generate a revision to keep the page history up to date
					if(module_exists("feature-history"))
					{
						$oldsource = ""; // Only variables can be passed by reference, not literals
						history_add_revision($entry, $description, $oldsource, false);
					}
					
					// Save the pageindex
					save_pageindex();
					
					if(module_exists("feature-recent-changes"))
					{
						add_recent_change([
							"type" => "upload",
							"timestamp" => time(),
							"page" => $new_pagepath,
							"user" => $env->user,
							"filesize" => filesize($env->storage_prefix . $entry->uploadedfilepath)
						]);
					}
					
					header("location: ?action=view&page=".rawurlencode($new_pagepath)."&upload=success");
					
					break;
			}
		});
		
		/**
		 * @api {get} ?action=preview&page={pageName}[&size={someSize}] Get a preview of a file
		 * @apiName PreviewFile
		 * @apiGroup Upload
		 * @apiPermission Anonymous
		 *
		 * @apiParam {string}	page		The name of the file to preview.
		 * @apiParam {number}	size		Optional. The size fo the resulting preview. Will be clamped to fit within the bounds specified in the wiki's settings. May also be set to the keyword 'original', which will cause the original file to be returned with it's appropriate mime type instead.
		 *
		 * @apiError	PreviewNoFileError	No file was found associated with the specified page.
		 * @apiError	PreviewUnknownFileTypeError	Pepperminty Wiki was unable to generate a preview for the requested file's type.
		 */
		
		/*
		 * ██████  ██████  ███████ ██    ██ ██ ███████ ██     ██
		 * ██   ██ ██   ██ ██      ██    ██ ██ ██      ██     ██
		 * ██████  ██████  █████   ██    ██ ██ █████   ██  █  ██
		 * ██      ██   ██ ██       ██  ██  ██ ██      ██ ███ ██
		 * ██      ██   ██ ███████   ████   ██ ███████  ███ ███
		 */
		add_action("preview", function() {
			global $settings, $env, $pageindex, $start_time;
			
			// Disable Javascript in all SVGs
			// Doesn't hurt to serve it for other images too just in case some wacky new format supports Javascript for some crazy reason
			header("Content-Security-Policy: default-src *; script-src 'none'; script-src-elem 'none'; script-src-attr 'none'");
			
			if(empty($pageindex->{$env->page}->uploadedfilepath))
			{
				$im = errorimage("The page '$env->page_safe' doesn't have an associated file.");
				header("content-type: image/png");
				imagepng($im);
				exit();
			}
			
			$filepath = realpath($env->storage_prefix . $pageindex->{$env->page}->uploadedfilepath);
			$mime_type = $pageindex->{$env->page}->uploadedfilemime;
			$shortFilename = str_replace(["\r", "\n", "\""], "", substr($filepath, 1 + (strrpos($filepath, '/') !== false ? strrpos($filepath, '/') : -1)));
			
			header("content-disposition: inline; filename=\"$shortFilename\"");
			header("last-modified: " . gmdate('D, d M Y H:i:s T', $pageindex->{$env->page}->lastmodified));
			
			// If the size is set to original, then send (or redirect to) the original image
			// Also do the same for SVGs if svg rendering is disabled.
			if(isset($_GET["size"]) and $_GET["size"] == "original" or
				(empty($settings->render_svg_previews) && $mime_type == "image/svg+xml"))
			{
				// Get the file size
				$filesize = filesize($filepath);
				
				// Send some headers
				header("content-length: $filesize");
				header("content-type: $mime_type");
				
				// Open the file and send it to the user
				$handle = fopen($filepath, "rb");
				fpassthru($handle);
				fclose($handle);
				exit();
			}
			
			// Determine the target size of the image
			$target_size = 512;
			if(isset($_GET["size"]))
				$target_size = intval($_GET["size"]);
			if($target_size < $settings->min_preview_size)
				$target_size = $settings->min_preview_size;
			if($target_size > $settings->max_preview_size)
				$target_size = $settings->max_preview_size;
			
			// Determine the output file type
			$output_mime = $settings->preview_file_type;
			if(isset($_GET["type"]) and in_array($_GET["type"], [ "image/png", "image/jpeg", "image/webp" ]))
				$output_mime = $_GET["type"];
			
			/// ETag handling ///
			// Generate the etag and send it to the client
			$preview_etag = sha1("$output_mime|$target_size|$filepath|$mime_type");
			$allheaders = getallheaders();
			$allheaders = array_change_key_case($allheaders, CASE_LOWER);
			if(!isset($allheaders["if-none-match"]))
				header("etag: $preview_etag");
			else {
				if($allheaders["if-none-match"] === $preview_etag)
				{
					http_response_code(304);
					header("x-generation-time: " . (microtime(true) - $start_time));
					exit();
				}
			}
			/// ETag handling end ///
			
			/* Disabled until we work out what to do about caching previews *
			$previewFilename = "$filepath.preview.$outputFormat";
			if($target_size === $settings->default_preview_size)
			{
				// The request is for the default preview size
				// Check to see if we have a preview pre-rendered
				
			}
			*/
			
			
			if(!class_exists("Imagick")) {
				http_response_code(503);
				header("content-type: text/plain");
				exit("Error: The PHP Imagick extension is required to perform this operation but is not installed. Please contact the system administrator.");
			}
			
			$preview = new Imagick();
			switch(substr($mime_type, 0, strpos($mime_type, "/")))
			{
				case "image":
					$preview->readImage($filepath);
					break;
				
				case "application":
					if($mime_type == "application/pdf") {
						$preview = new imagick();
						$preview->readImage("{$filepath}[0]");
						$preview->setResolution(300,300);
						$preview->setImageColorspace(255);
						break;
					}
				
				case "video":
				case "audio":
					if($settings->data_storage_dir == ".")
					{
						// The data storage directory is the current directory
						// Redirect to the file isntead
						http_response_code(307);
						header("location: " . $pageindex->{$env->page}->uploadedfilepath);
						exit();
					}
					// TODO: Add support for ranges here.
					// Get the file size
					$filesize = filesize($filepath);
					
					// Send some headers
					header("content-length: $filesize");
					header("content-type: $mime_type");
					
					// Open the file and send it to the user
					$handle = fopen($filepath, "rb");
					fpassthru($handle);
					fclose($handle);
					exit();
					break;
				
				default:
					http_response_code(501);
					$preview = errorimage("Unrecognised file type '$mime_type'.", $target_size);
					header("content-type: image/png");
					imagepng($preview);
					exit();
			}
			
			// Scale the image down to the target size
			$preview->resizeImage($target_size, $target_size, imagick::FILTER_LANCZOS, 1, true);
			
			// Send the completed preview image to the user
			header("content-type: $output_mime");
			header("x-generation-time: " . (microtime(true) - $start_time) . "s");
			$outputFormat = substr($output_mime, strpos($output_mime, "/") + 1);
			$preview->setImageFormat($outputFormat);
			echo($preview->getImageBlob());
			/* Disabled while we work out what to do about caching previews *
			// Save a preview file if there isn't one alreaddy
			if(!file_exists($previewFilename))
				file_put_contents($previewFilename, $preview->getImageBlob());
			*/
		});
		
		/*
		 * ██████  ██████  ███████ ██    ██ ██ ███████ ██     ██
		 * ██   ██ ██   ██ ██      ██    ██ ██ ██      ██     ██
		 * ██████  ██████  █████   ██    ██ ██ █████   ██  █  ██
		 * ██      ██   ██ ██       ██  ██  ██ ██      ██ ███ ██
		 * ██      ██   ██ ███████   ████   ██ ███████  ███ ███
		 *
		 * ██████  ██ ███████ ██████  ██       █████  ██    ██ ███████ ██████
		 * ██   ██ ██ ██      ██   ██ ██      ██   ██  ██  ██  ██      ██   ██
		 * ██   ██ ██ ███████ ██████  ██      ███████   ████   █████   ██████
		 * ██   ██ ██      ██ ██      ██      ██   ██    ██    ██      ██   ██
		 * ██████  ██ ███████ ██      ███████ ██   ██    ██    ███████ ██   ██
		 */
		page_renderer::register_part_preprocessor(function(&$parts) {
			global $pageindex, $env, $settings;
			// Don't do anything if the action isn't view
			if($env->action !== "view")
				return;
			
			if(isset($pageindex->{$env->page}->uploadedfile) and $pageindex->{$env->page}->uploadedfile == true)
			{
				// We are looking at a page that is paired with an uploaded file
				$filepath = $pageindex->{$env->page}->uploadedfilepath;
				$mime_type = $pageindex->{$env->page}->uploadedfilemime;
				$dimensions = $mime_type !== "image/svg+xml" ? getimagesize($env->storage_prefix . $filepath) : getsvgsize($env->storage_prefix . $filepath);
				$fileTypeDisplay = slugify(substr($mime_type, 0, strpos($mime_type, "/")));
				$previewUrl = htmlentities("?action=preview&size=$settings->default_preview_size&page=" . rawurlencode($env->page));
				$originalUrl = htmlentities($env->storage_prefix == "./" && $mime_type !== "image/svg+xml" ? $filepath : "?action=preview&size=original&page=" . rawurlencode($env->page));
				if($mime_type == "application/pdf")
					$fileTypeDisplay = "pdf";
				
				$preview_html = "";
				switch($fileTypeDisplay)
				{
					case "application":
					case "image":
						$preview_sizes = [ 256, 512, 768, 1024, 1440, 1920 ];
						$preview_html .= "\t\t\t<figure class='preview'>
				<a href='$originalUrl'><img src='$previewUrl' /></a>
				<nav class='image-controls'>
					<ul><li><a href='$originalUrl'>&#x01f304; Original $fileTypeDisplay</a></li>";
						if($mime_type !== "image/svg+xml")
						{
							$preview_html .= "<li>Other Sizes: ";
							foreach($preview_sizes as $size)
								$preview_html .= "<a href='?action=preview&page=" . rawurlencode($env->page) . "&size=$size'>$size" . "px</a> ";
							$preview_html .= "</li>";
						}
						$preview_html .= "</ul></nav>\n\t\t\t</figure>";
						break;
					
					case "video":
						$preview_html .= "\t\t\t<figure class='preview'>
				<video src='$previewUrl' controls preload='metadata'>Your browser doesn't support HTML5 video, but you can still <a href='$previewUrl'>download it</a> if you'd like.</video>
			</figure>";
						break;
					
					case "audio":
						$preview_html .= "\t\t\t<figure class='preview'>
				<audio src='$previewUrl' controls preload='metadata'>Your browser doesn't support HTML5 audio, but you can still <a href='$previewUrl'>download it</a> if you'd like.</audio>
			</figure>";
						break;
					
					case "pdf":
						$preview_html .= "\t\t\t<object type='application/pdf' data='$originalUrl'></object>";
						break;
					
					default:
						$preview_html .= "\t\t\t<p><em>No preview is available, but you can download it instead:</em></p>
						<a class='button' href='$originalUrl'>Download</a>";
						break;
				}
				
				$fileInfo = [];
				$fileInfo["Name"] = htmlentities(str_replace("Files/", "", $filepath));
				$fileInfo["Type"] = htmlentities($mime_type);
				$fileInfo["Size"] = human_filesize(filesize($env->storage_prefix . $filepath));
				switch($fileTypeDisplay)
				{
					case "image":
						$dimensionsKey = $mime_type !== "image/svg+xml" ? "Original dimensions" : "Native size";
						$fileInfo[$dimensionsKey] = "$dimensions[0] x $dimensions[1]";
						break;
				}
				$fileInfo["Uploaded by"] = $pageindex->{$env->page}->lasteditor;
				$fileInfo["Short markdown embed code"] = "<input type='text' class='short-embed-markdown-code' value='![" . htmlentities($fileInfo["Name"], ENT_QUOTES | ENT_HTML5) . "](" . htmlentities($filepath, ENT_QUOTES | ENT_HTML5) . " | right | 350x350)' readonly /> <button class='short-embed-markdown-button'>Copy</button>";
				
				if($mime_type == "image/svg+xml")
					$fileInfo["Warning"] = "Warning: SVG images may contain Javascript. Although $settings->sitename disables execution of Javascript in SVGs, if you download an SVG and view it in your browser directly the Javascript may execute. <strong>Make sure you trust the source of this SVG before downloading!</strong>";
				
				$preview_html .= "\t\t\t<h2>File Information</h2>
			<table>";
				foreach ($fileInfo as $displayName => $displayValue)
				{
					$preview_html .= "<tr><th>".htmlentities($displayName)."</th><td>$displayValue</td></tr>\n";
				}
				$preview_html .= "</table>";
				
				$parts["{content}"] = str_replace("</h1>", "</h1>\n$preview_html", $parts["{content}"]);
			}
		});
		
		// Add the snippet that copies the embed markdown code to the clipboard
		page_renderer::add_js_snippet('window.addEventListener("load", function(event) {
	let button = document.querySelector(".short-embed-markdown-button");
	if(button == null) return;
	button.addEventListener("click", function(inner_event) {
		let input = document.querySelector(".short-embed-markdown-code");
		input.select();
		button.innerHTML = document.execCommand("copy") ? "Copied!" : "Failed to copy :-(";
	});
});');
		
		// Register a section on the help page on uploading files
		add_help_section("28-uploading-files", "Uploading Files", "<p>$settings->sitename supports the uploading of files, though it is up to " . $settings->admindetails_name . ", $settings->sitename's administrator as to whether it is enabled or not (uploads are currently " . (($settings->upload_enabled) ? "enabled" : "disabled") . ").</p>
		<p>Currently Pepperminty Wiki (the software that $settings->sitename uses) only supports the uploading of images, videos, and audio, although more file types should be supported in the future (<a href='//github.com/sbrl/Pepperminty-Wiki/issues'>open an issue on GitHub</a> if you are interested in support for more file types).</p>
		<p>Uploading a file is actually quite simple. Click the &quot;Upload&quot; option in the &quot;More...&quot; menu to go to the upload page. The upload page will tell you what types of file $settings->sitename allows, and the maximum supported filesize for files that you upload (this is usually set by the web server that the wiki is running on).</p>
		<p>Use the file chooser to select the file that you want to upload, and then decide on a name for it. Note that the name that you choose should not include the file extension, as this will be determined automatically. Enter a description that will appear on the file's page, and then click upload.</p>");
	}
]);

/**
 * Calculates the actual maximum upload size supported by the server
 * Returns a file size limit in bytes based on the PHP upload_max_filesize and
 * post_max_size
 * @package feature-upload
 * @author	Lifted from Drupal by @meustrus from Stackoverflow
 * @see		http://stackoverflow.com/a/25370978/1460422 Source Stackoverflow answer
 * @return	int		The maximum upload size supported bythe server, in bytes.
 */
function get_max_upload_size()
{
	static $max_size = -1;
	if ($max_size < 0) {
		// Start with post_max_size.
		$max_size = parse_size(ini_get('post_max_size'));
		// If upload_max_size is less, then reduce. Except if upload_max_size is
		// zero, which indicates no limit.
		$upload_max = parse_size(ini_get('upload_max_filesize'));
		if ($upload_max > 0 && $upload_max < $max_size) {
			$max_size = $upload_max;
		}
	}
	return $max_size;
}
/**
 * Parses a PHP size to an integer
 * @package feature-upload
 * @author	Lifted from Drupal by @meustrus from Stackoverflow
 * @see		http://stackoverflow.com/a/25370978/1460422 Source Stackoverflow answer
 * @param	string	$size	The size to parse.
 * @return	int		The number of bytees represented by the specified
 * 							size string.
 */
function parse_size($size) {
	$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
	$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
	if ($unit) {
		// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
		return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
	} else {
		return round($size);
	}
}
/**
 * Checks an uploaded SVG file to make sure it's (at least somewhat) safe.
 * Sends an error to the client if a problem is found.
 * @package feature-upload
 * @param  string $temp_filename The filename of the SVG file to check.
 * @return int[]                The size of the SVG image.
 */
function upload_check_svg($temp_filename)
{
	global $settings;
	// Check for script tags
	if(strpos(file_get_contents($temp_filename), "<script") !== false)
	{
		http_response_code(415);
		exit(page_renderer::render("Upload Error - $settings->sitename", "<p>$settings->sitename detected that you uploaded an SVG image and performed some extra security checks on your file. Whilst performing these checks it was discovered that the file you uploaded contains some Javascript, which could be dangerous. The uploaded file has been discarded. <a href='?action=upload'>Go back to try again</a>.</p>
		<p>You may wish to consider <a href='https://github.com/sbrl/Pepperminty-Wiki'>opening an issue</a> against Pepperminty Wiki (the software that powers $settings->sitename) if this isn't the first time that you have seen this message.</p>"));
	}

	// Find and return the size of the SVG image
	return getsvgsize($temp_filename);
}

/**
 * Calculates the size of the specified SVG file.
 * @package feature-upload
 * @param	string	$svgFilename	The filename to calculate the size of.
 * @return	int[]					The width and height respectively of the
 * 									specified SVG file.
 */
function getsvgsize($svgFilename)
{
	global $settings;
	libxml_disable_entity_loader(true); // Ref: XXE Billion Laughs Attack, issue #152
	$rawSvg = file_get_contents($svgFilename);
	$svg = simplexml_load_string($rawSvg); // Load it as XML
	unset($rawSvg);
	if($svg === false)
	{
		http_response_code(415);
		exit(page_renderer::render("Upload Error - $settings->sitename", "<p>When $settings->sitename tried to open your SVG file for checking, it found some invalid syntax. The uploaded file has been discarded. <a href='?action=upload'>Go back to try again</a>.</p>"));
	}
	$rootAttrs = $svg->attributes();
	$imageSize = false;
	if(isset($rootAttrs->width) and isset($rootAttrs->height))
		$imageSize = [ intval($rootAttrs->width), intval($rootAttrs->height) ];
	else if(isset($rootAttrs->viewBox))
		$imageSize = array_map("intval", array_slice(explode(" ", $rootAttrs->viewBox), -2, 2));
	
	return $imageSize;
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "User Preferences",
	"version" => "0.4.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a user preferences page, letting people do things like change their email address and password.",
	"id" => "feature-user-preferences",
	"code" => function() {
		global $env, $settings;
		/**
		 * @api {get} ?action=user-preferences Get a user preferences configuration page
		 * @apiName UserPreferences
		 * @apiGroup Settings
		 * @apiPermission User
		 */
		
		 /*
 		 * ██    ██ ███████ ███████ ██████
 		 * ██    ██ ██      ██      ██   ██
 		 * ██    ██ ███████ █████   ██████  █████
 		 * ██    ██      ██ ██      ██   ██
 		 *  ██████  ███████ ███████ ██   ██
 		 * 
 		 * ██████  ██████  ███████ ███████ ███████
 		 * ██   ██ ██   ██ ██      ██      ██
 		 * ██████  ██████  █████   █████   ███████
 		 * ██      ██   ██ ██      ██           ██
 		 * ██      ██   ██ ███████ ██      ███████
 		 */
		add_action("user-preferences", function() {
			global $env, $settings;
			
			if(!$env->is_logged_in)
			{
				exit(page_renderer::render_main("Error  - $settings->sitename", "<p>Since you aren't logged in, you can't change your preferences. This is because stored preferences are tied to each registered user account. You can login <a href='?action=login&returnto=" . rawurlencode("?action=user-preferences") . "'>here</a>.</p>"));
			}
			
			$statusMessages = [
				"change-password" => "Password changed successfully!"
			];
			
			if(!isset($env->user_data->emailAddress)) {
				$env->user_data->emailAddress = "";
				save_userdata();
			}
			
			$content = "<h2>User Preferences</h2>\n";
			if(isset($_GET["success"]) && $_GET["success"] === "yes") {
				$content .= "<p class='user-prefs-status-message'><em>" . $statusMessages[$_GET["operation"]] . "</em></p>\n";
			}
			
			if(has_action("watchlist") && module_exists("feature-watchlist")) {
				$content .= "<p><em>Looking for your watchlist? Find it <a href='?action=watchlist'>here</a>!</em></p>";
			}
			
			// If avatar support is present, allow the user to upload a new avatar
			if(has_action("avatar") && module_exists("feature-upload")) {
				$content .= "<a href='?action=upload&avatar=yes' class='preview'><figure>\n";
				$content .= "\t<img class='avatar' src='?action=avatar&user=" . urlencode($env->user) . "&size=256' title='Your current avatar - click to upload a new one' />\n";
				$content .= "<figcaption>Upload a new avatar</figcaption>\n";
				$content .= "</figure></a><br />\n";
			}
			$content .= "<label for='username'>Username:</label>\n";
			$content .= "<input type='text' name='username' value='".htmlentities($env->user)."' readonly />\n";
			$content .= "<form method='post' action='?action=save-preferences'>\n";
			$content .= "	<label for='email-address'>Email Address:</label>\n";
			$content .= "	<input type='email' id='email-address' name='email-address' placeholder='e.g. bob@bobsrockets.com' value='".htmlentities($env->user_data->emailAddress)."}' />\n";
			$content .= "	<p><small>Used to send you notifications etc. Never shared with anyone except ".htmlentities($settings->admindetails_name).", $settings->sitename's administrator.</small></p>\n";
			if($settings->email_verify_addresses) {
				$content .= "	<p>Email verification status: <strong>".(empty($env->user_data->emailAddressVerified) ? "not " : "")."verified</strong> <small><em>(Email address verification is required in order to receive emails (other than the verification email itself, of course). Click the link in the verification email sent to you to verify your address, or change it here to get another verification email - changing it to the same email address is ok)</em></small></p>";
			}
			$content .= "	<input type='submit' value='Save Preferences' />\n";
			$content .= "</form>\n";
			$content .= "<h3>Change Password</h3\n>";
			$content .= "<form method='post' action='?action=change-password'>\n";
			$content .= "	<label for='old-pass'>Current Password:</label>\n";
			$content .= "	<input type='password' name='current-pass'  />\n";
			$content .= "	<br />\n";
			$content .= "	<label for='new-pass'>New Password:</label>\n";
			$content .= "	<input type='password' name='new-pass' />\n";
			$content .= "	<br />\n";
			$content .= "	<label for='new-pass-confirm'>Confirm New Password:</label>\n";
			$content .= "	<input type='password' name='new-pass-confirm' />\n";
			$content .= "	<br />\n";
			$content .= "	<input type='submit' value='Change Password' />\n";
			$content .= "</form>\n";
			
			if($env->is_admin)
				$content .= "<p>As an admin, you can also <a href='?action=configure'>edit $settings->sitename's master settings</a>.</p>\n";
			
			exit(page_renderer::render_main("User Preferences - $settings->sitename", $content));
		});
		
		/**
		 * @api {post} ?action=save-preferences Save your user preferences
		 * @apiName UserPreferencesSave
		 * @apiGroup Settings
		 * @apiPermission User
		 */
		add_action("save-preferences", function() {
			global $env, $settings;
			
			if(!$env->is_logged_in)
			{
				http_response_code(400);
				exit(page_renderer::render_main("Error Saving Preferences - $settings->sitename", "<p>You aren't logged in, so you can't save your preferences. Try <a href='?action=login&returnto=" . rawurlencode("?action=user-preferences") . "'>logging in</a> first.</p>"));
			}
			
			if(isset($_POST["email-address"])) {
				if(mb_strlen($_POST["email-address"]) > 320) {
					http_response_code(413);
					exit(page_renderer::render_main("Error Saving Email Address - $settings->sitename", "<p>The email address you supplied (<code>".htmlentities($_POST['email-address'])."</code>) is too long. Email addresses can only be 320 characters long. <a href='javascript:window.history.back();'>Go back</a>."));
				}
				
				if(mb_strpos($_POST["email-address"], "@") === false) {
					http_response_code(422);
					exit(page_renderer::render_main("Error Saving Email Address - $settings->sitename", "<p>The email address you supplied (<code>".htmlentities($_POST['email-address'])."</code>) doesn't appear to be valid. <a href='javascript:window.history.back();'>Go back</a>."));
				}
				$old_address = $env->user_data->emailAddress ?? null;
				$env->user_data->emailAddress = $_POST["email-address"];
				
				// If email address verification is required and the email 
				// address has changed, send a verification email now
				if($settings->email_verify_addresses) {
					if(empty($env->user_data->emailAddressVerified) && $old_address !== $_POST["email-address"])
						$env->user_data->emailAddressVerified = false;
					
					if(empty($env->user_data->emailAddressVerified) && !email_verify_addresses($env->user)) {
						http_response_code(503);
						exit(page_renderer::render_main("Server error sending verification code - $settings->sitename", "<p>$settings->sitename tried to send you an email to verify your email address, but was unable to do so. The changes to your settings have not been saved. Please contact ".htmlentities($settings->admindetails_name).", whose email address can be found at the bottom of this page.</p>"));
					}
				}
			}
			
			// Save the user's preferences
			if(!save_userdata()) {
				http_response_code(503);
				exit(page_renderer::render_main("Error Saving Preferences - $settings->sitename", "<p>$settings->sitename had some trouble saving your preferences! Please contact ".htmlentities($settings->admindetails_name).", $settings->sitename's administrator and tell them about this error if it still occurs in 5 minutes. They can be contacted by email at this address: ".hide_email($settings->admindetails_email, $settings->admindetails_name).".</p>"));
			}
			
			exit(page_renderer::render_main("Preferences Saved Successfully - $settings->sitename", "<p>Your preferences have been saved successfully! You could go back your <a href='?action=user-preferences'>preferences page</a>, or on to the <a href='?page=" . rawurlencode($settings->defaultpage) . "'>".htmlentities($settings->defaultpage)."</a>.</p>
<p>If you changed your email address, a verification code will have been sent to the email address you specified. Click on the link provided to verify your new email address.</p>"));
		});
		
		/**
		 * @api {get}	?action=email-address-verify&code={code}	Verify the current user's email address
		 * @apiName			EmailAddressVerify
		 * @apiGroup		Settings
		 * @apiPermission	User
		 *
		 * @apiParam	{string}	code	The verfication code.
		 *
		 * @apiError	VerificationCodeIncorrect	The supplied verification code is not correct.
		 */
		add_action("email-address-verify", function() {
			global $env, $settings;
			
			if(!$env->is_logged_in) {
				http_response_code(307);
				header("x-status: failed");
				header("x-problem: not-logged-in");
				exit(page_renderer::render_main("Not logged in - $settings->sitename", "<p>You aren't logged in, so you can't verify your email address. Try <a href='?action=login&amp;returnto=".rawurlencode("?action=email-address-verify&code=".rawurlencode($_GET["code"]??""))."'>logging in</a>.</p>"));
			}
			
			if($env->user_data->emailAddressVerified) {
				header("x-status: success");
				exit(page_renderer::render_main("Already verified - $settings->sitename", "<p>Your email address is already verified, so you don't need to verify it again.</p>\n<p> <a href='index.php'>Go to the main page</a>.</p>"));
			}
			
			if(empty($_GET["code"])) {
				http_response_code(400);
				header("x-status: failed");
				header("x-problem: no-code-specified");
				exit(page_renderer::render_main("No verification code specified  - $settings->sitename", "<p>No verification code specified. Do so with the <code>code</code> GET parameter, or try making sure you copied the email address from the email you were sent correctly.</p>"));
			}
			
			if($env->user_data->emailAddressVerificationCode !== $_GET["code"]) {
				http_resonse_code(400);
				header("x-status: failed");
				header("x-problem: code-incorrect");
				exit(page_renderer::render_main("Verification code incorrect", "<p>That  verification code was incorrect. Try specifying another one, or going to your <a href='?action=user-preferences'>user preferences</a> and changing your email address to re-send another code (changing it to the same email address is ok).</p>"));
			}
			
			// The code supplied must be valid
			unset($env->user_data->emailAddressVerificationCode);
			$env->user_data->emailAddressVerified = true;
			
			if(!save_settings()) {
				http_response_code(503);
				header("x-status: failed");
				header("x-problem: server-error-disk-io");
				exit(page_renderer::render_main("Server error - $settings->sitename", "<p>Your verification code was correct, but $settings->sitename was unable to update your user details because it failed to write the changes to disk. Please contact ".htmlentities($settings->admindetails_name).", whose email address can be found at the bottom of the page.</p>"));
			}
			
			header("x-status: success");
			exit(page_renderer::render_main("Email Address Verified - $settings->sitename", "<p>Your email address was verified successfully. <a href='index.php'>Go to the main page</a>, or to your <a href='?action=user-preferences'>user preferences</a> to make further changes.</p>"));
		});
		
		/**
		 * @api	{post}	?action=change-password	Change your password
		 * @apiName			ChangePassword
		 * @apiGroup		Settings
		 * @apiPermission	User
		 *
		 * @apiParam	{string}	current-pass		Your current password.
		 * @apiParam	{string}	new-pass			Your new password.
		 * @apiParam	{string}	new-pass-confirm	Your new password again, to make sure you've typed it correctly.
		 *
		 * @apiError	PasswordMismatchError	The new password fields don't match.
		 */
		add_action("change-password", function() {
		    global $env, $settings;
			
			// Make sure the new password was typed correctly
			// This comes before the current password check since that's more intensive
			if($_POST["new-pass"] !== $_POST["new-pass-confirm"]) {
				exit(page_renderer::render_main("Password mismatch - $settings->sitename", "<p>The new password you typed twice didn't match! <a href='javascript:history.back();'>Go back</a>.</p>"));
			}
			// Check the current password
			if(!verify_password($_POST["current-pass"], $env->user_data->password)) {
				exit(page_renderer::render_main("Password mismatch - $settings->sitename", "<p>Error: You typed your current password incorrectly! <a href='javascript:history.back();'>Go back</a>.</p>"));
			}
			
			// All's good! Go ahead and change the password.
			$env->user_data->password = hash_password($_POST["new-pass"]);
			// Save the userdata back to disk
			if(!save_userdata()) {
				http_response_code(503);
				exit(page_renderer::render_main("Error Saving Password - $settings->sitename", "<p>While you entered your old password correctly, $settings->sitename encountered an error whilst saving your password to disk! Your password has not been changed. Please contact ".htmlentities($settings->admindetails_name)." for assistance (you can find their email address at the bottom of this page)."));
			}
			
			http_response_code(307);
			header("location: ?action=user-preferences&success=yes&operation=change-password");
			exit(page_renderer::render_main("Password Changed Successfully", "<p>You password was changed successfully. <a href='?action=user-preferences'>Go back to the user preferences page</a>.</p>"));
		});
		
		
		/*
		 *  █████  ██    ██  █████  ████████  █████  ██████
		 * ██   ██ ██    ██ ██   ██    ██    ██   ██ ██   ██
		 * ███████ ██    ██ ███████    ██    ███████ ██████
		 * ██   ██  ██  ██  ██   ██    ██    ██   ██ ██   ██
		 * ██   ██   ████   ██   ██    ██    ██   ██ ██   ██
		 */
		
 		/**
 		 * @api	{get}	?action=avatar&user={username}[&size={size}]	Get a user's avatar
 		 * @apiName			Avatar
 		 * @apiGroup		Upload
 		 * @apiPermission	Anonymous
 		 *
 		 * @apiParam	{string}	user			The username to fetch the avatar for
 		 * @apiParam	{string}	size			The preferred size of the avatar
 		 */
		add_action("avatar", function() {
			global $settings, $pageindex;
			
			$size = intval($_GET["size"] ?? 32);
			
			/// Use gravatar if there's some issue with the requested user
			
			// No user specified
			if(empty($_GET["user"])) {
				http_response_code(200);
				header("x-reason: no-user-specified");
				header("content-type: image/png");
				header("content-length: 101");
				exit(base64_decode("iVBORw0KGgoAAAANSUhEUgAAAFAAAABQAQMAAAC032DuAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAABBJREFUGBljGAWjYBTQDQAAA3AAATXTgHYAAAAASUVORK5CYII="));
			}
			
			$requested_username = $_GET["user"];
			$has_avatar = !empty($pageindex->{"Users/$requested_username/Avatar"}) && $pageindex->{"Users/$requested_username/Avatar"}->uploadedfile === true;
			
			if(!$settings->avatars_gravatar_enabled && !$has_avatar) {
				http_response_code(404);
				header("x-reason: no-avatar-found-gravatar-disabled");
				header("content-type: image/png");
				header("content-length: 101");
				exit(base64_decode("iVBORw0KGgoAAAANSUhEUgAAAFAAAABQAQMAAAC032DuAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAABBJREFUGBljGAWjYBTQDQAAA3AAATXTgHYAAAAASUVORK5CYII=")); // TODO: Refactor out into a separate function
			}
			
			// The user hasn't uploaded an avatar
			if(!$has_avatar) {
				$user_fragment = !empty($settings->users->$requested_username->emailAddress) ? $settings->users->$requested_username->emailAddress : $requested_username;
				
				http_response_code(307);
				header("x-reason: no-avatar-found");
				header("x-hash-method: " . ($user_fragment === $requested_username ? "username" : "email_address"));
				header("location: https://gravatar.com/avatar/" . md5($user_fragment) . "?default=identicon&rating=g&size=$size");
				exit();
			}
			
			// The user has uploaded an avatar, so we can redirec to the regular previewer :D
			
			http_response_code(307);
			header("x-reason: found-local-avatar");
			header("location: ?action=preview&size=$size&page=" . urlencode("Users/$requested_username/Avatar"));
			header("content-type: text/plain");
			exit("This user's avatar can be found at Files/$requested_username/Avatar");
		});
		
		// Display a help section on the user preferences, but only if the user
		// is logged in and so able to access them
		if($env->is_logged_in) {
			add_help_section("910-user-preferences", "User Preferences", "<p>As you are logged in, $settings->sitename lets you configure a selection of personal preferences. These can be viewed and tweaked to you liking over on the <a href='?action=user-preferences'>preferences page</a>, which can be accessed at any time by clicking the cog icon (it looks something like this: <a href='?action=user-preferences'>$settings->user_preferences_button_text</a>), though the administrator of $settings->sitename (".htmlentities($settings->admindetails_name).") may have changed its appearance.</p>");
		}
		
		if($settings->avatars_show) {
			add_help_section("915-avatars", "Avatars", "<p>$settings->sitename allows you to upload an avatar and have it displayed next to your name. If you don't have an avatar uploaded yet, then $settings->sitename will take a <a href='https://www.techopedia.com/definition/19744/hash-function'>hash</a> of your email address and ask <a href='https://gravatar.com'>Gravatar</a> for for your Gravatar instead. If you haven't told $settings->sitename what your email address is either, a hash of your username is used instead. If you don't have a gravatar, then $settings->sitename asks Gravatar for an identicon instead.</p>
			<p>Your avatar on $settings->sitename currently looks like this: <img class='avatar' src='?action=avatar&user=" . rawurlencode($env->user) . "' />" . ($settings->upload_enabled ? " - you can upload a new one by going to your <a href='?action=user-preferences'>preferences</a>, or <a href='?action=upload&avatar=yes' />clicking here</a>." : ", but $settings->sitename currently has uploads disabled, so you can't upload a new one directly to $settings->sitename. You can, however, set your email address in your <a href='?action=user-preferences'>preferences</a> and <a href='https://en.gravatar.com/'>create a Gravatar</a>, and then it should show up here on $settings->sitename shortly.") . "</p>");
		}
	}
]);

/**
 * Sends a verification email to the specified user, assuming they need to 
 * verify their email address.
 * If a user does not need to verify their email address, no verification email 
 * is sent and true is returned.
 * @param	string	$username	The name of the user to send the verification code to.
 * @return	bool	Whether the verification code was sent successfully. If a user does not need to verify their email address, this returns true.
 */
function email_user_verify(string $username) : bool {
	global $settings;
	
	$user_data = $settings->users->$username;
	
	if(!empty($user_data->emailAddressVerified) &&
		$user_data->emailAddressVerified === true) {
		return true;
	}
	
	// Generate a verification code
	$user_data->emailAddressVerificationCode = crypto_id(64);
	if(!save_settings())
		return false;
	
	return email_user(
		$username,
		"Verify your account - $settings->sitename",
		"Hey there! Click this link to verify your account on $settings->sitename:

".url_stem()."?action=email-address-verify&code=$user_data->emailAddressVerificationCode

$settings->sitename requires that you verify your email address in order to use it.

--$settings->sitename
Powered by Pepperminty Wiki",
		true // ignore that the user's email address isn't verified
	);
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "User Organiser",
	"version" => "0.1.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a organiser page that lets moderators (or better) control the reegistered user accounts, and perform adminstrative actions such as password resets, and adding / removing accounts.",
	"id" => "feature-user-table",
	"code" => function() {
		global $settings, $env;
		
		/**
		 * @api {get} ?action=user-table	Get the user table
		 * @apiName UserTable
		 * @apiGroup Settings
		 * @apiPermission Moderator
		 */
		
		/*
 	 	 * ██    ██ ███████ ███████ ██████
 		 * ██    ██ ██      ██      ██   ██
 		 * ██    ██ ███████ █████   ██████  █████
 		 * ██    ██      ██ ██      ██   ██
 		 *  ██████  ███████ ███████ ██   ██
 		 *
 		 * ████████  █████  ██████  ██      ███████
		 *    ██    ██   ██ ██   ██ ██      ██
 		 *    ██    ███████ ██████  ██      █████
 		 *    ██    ██   ██ ██   ██ ██      ██
 		 *    ██    ██   ██ ██████  ███████ ███████
		 */
		add_action("user-table", function() {
			global $settings, $env;
			
			if(!$env->is_logged_in || !$env->is_admin) {
				http_response_code(401);
				exit(page_renderer::render_main("Unauthorised - User Table - $settings->sitename", "<p>Only moderators (or better) may access the user table. You could try <a href='?action=logout'>logging out</a> and then <a href='?action=login&returnto=index.php%3Faction%3Duser-table'>logging in</a> again as a moderator, or alternatively visit the <a href='?action=user-list'>user list</a> instead, if that's what you're after.</p>"));
			}
			
			$content = "<h2>User Table</h2>
			<p>(Warning! Deleting a user will wipe <em>all</em> their user data! It won't delete any pages they've created, their user page, or their avatar though, as those are part of the wiki itself.)</p>
			<table class='user-table'>
				<tr><th>Username</th><th>Email Address</th><th></th></tr>\n";
			
			foreach($settings->users as $username => $user_data) {
				$content .= "<tr>";
				$content .= "<td>" . page_renderer::render_username($username) . "</td>";
				if(!empty($user_data->emailAddress))
					$content .= "<td><a href='mailto:" . htmlentities($user_data->emailAddress, ENT_HTML5 | ENT_QUOTES) . "'>" . htmlentities($user_data->emailAddress) . "</a></td>\n";
				else
					$content .= "<td><em>(None provided)</em></td>\n";
				$content .= "<td>";
				if(module_exists("feature-user-preferences"))
					$content .= "<form method='post' action='?action=set-password' class='inline-form'>
						<input type='hidden' name='user' value='$username' />
						<input type='password' name='new-pass' placeholder='New password' />
						<input type='submit' value='Reset Password' />
					</form> | ";
				$content .= "<a href='?action=user-delete&user=" . rawurlencode($username) . "'>Delete User</a>";
				$content .= "</td></tr>";
			}
			
			$content .= "</table>\n";
			
			$content .= "<h3>Add User</h3>
			<form method='post' action='?action=user-add'>
				<input type='text' id='new-username' name='user' placeholder='Username' required />
				<input type='email' id='new-email' name='email' placeholder='Email address - optional' />
				<input type='submit' value='Add user' />
			</form>";
			
			exit(page_renderer::render_main("User Table - $settings->sitename", $content));
		});
		
		/**
		 * @api {post} ?action=user-add	Create a user account
		 * @apiName UserAdd
		 * @apiGroup Settings
		 * @apiPermission Moderator
		 *
		 * @apiParam	{string}	user	The username for the new user.
		 * @apiParam	{string}	email	Optional. Specifies the email address for the new user account.
		 */
		
		/*
 		 * ██    ██ ███████ ███████ ██████         █████  ██████  ██████
 		 * ██    ██ ██      ██      ██   ██       ██   ██ ██   ██ ██   ██
 		 * ██    ██ ███████ █████   ██████  █████ ███████ ██   ██ ██   ██
 		 * ██    ██      ██ ██      ██   ██       ██   ██ ██   ██ ██   ██
 		 *  ██████  ███████ ███████ ██   ██       ██   ██ ██████  ██████
		 */
		add_action("user-add", function() {
			global $settings, $env;
			
			if(!$env->is_admin) {
				http_response_code(401);
				exit(page_renderer::render_main("Error: Unauthorised - Add User - $settings->sitename", "<p>Only moderators (or better) may create users. You could try <a href='?action=logout'>logging out</a> and then <a href='?action=login&returnto%2Findex.php%3Faction%3Duser-table'>logging in</a> again as a moderator, or alternatively visit the <a href='?action=user-list'>user list</a> instead, if that's what you're after.</p>"));
			}
			
			if(!isset($_POST["user"])) {
				http_response_code(400);
				header("content-type: text/plain");
				exit("Error: No username specified in the 'user' post parameter.");
			}
			
			$new_username = $_POST["user"];
			$new_email = $_POST["email"] ?? null;
			
			if(preg_match('/[^0-9a-zA-Z\-_]/', $new_username) !== 0) {
				http_response_code(400);
				exit(page_renderer::render_main("Error: Invalid Username - Add User - $settings->sitename", "<p>The username <code>" . htmlentities($new_username) . "</code> contains some invalid characters. Only <code>a-z</code>, <code>A-Z</code>, <code>0-9</code>, <code>-</code>, and <code>_</code> are allowed in usernames. <a href='javascript:window.history.back();'>Go back</a>.</p>"));
			}
			if(!empty($new_email) && !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
				http_response_code(400);
				exit(page_renderer::render_main("Error: Invalid Email Address - Add User - $settings->sitename", "<p>The email address <code>" . htmlentities($new_email) . "</code> appears to be invalid. <a href='javascript:window.history.back();'>Go back</a>.</p>"));
			}
			
			$new_password = generate_password($settings->new_password_length);
			
			$user_data = new stdClass();
			$user_data->password = hash_password($new_password);
			if(!empty($new_email))
				$user_data->emailAddress = $new_email;
			
			$settings->users->$new_username = $user_data;
			
			if(!save_settings()) {
				http_response_code(503);
				exit(page_renderer::render_main("Error: Failed to save settings - Add User - $settings->sitename", "<p>$settings->sitename failed to save the new user's data to disk. Please contact ".htmlentities($settings->admindetails_name)." for assistance (their email address can be found at the bottom of this page).</p>"));
			}
			
			
			$welcome_email_result = email_user($new_username, "Welcome!", "Welcome to $settings->sitename, {username}! $env->user has created you an account. Here are your details:

Url: " . substr(full_url(), 0, strrpos(full_url(), "?")) . "
Username: {username}
Password: $new_password

It is advised that you change your password as soon as you login. You can do this by clicking the cog next to your name once you've logged in, and scrolling to the 'change password' heading.

If you need any assistance, then the help page you can access at the bottom of every page on $settings->sitename has information on most aspects of $settings->sitename.


--$settings->sitename, powered by Pepperminty Wiki
https://github.com/sbrl/Pepperminty-Wiki/
");
			
			$content = "<h2>Add User</h2>
			<p>The new user was added to $settings->sitename sucessfully! Their details are as follows:</p>
			<ul>
				<li>Username: <code>$new_username</code></li>";
			if(!empty($new_email))
				$content .= "	<li>Email Address: <code>".htmlentities($new_email)."</code></li>\n";
			if(!$welcome_email_result)
				$content .= "	<li>Password: <code>".htmlentities($new_password)."</code></li>\n";
			$content .= "</ul>\n";
			if($welcome_email_result)
				$content .= "<p>An email has been sent to the email address given above containing their login details.</p>\n";
			
			$content .= "<p><a href='?action=user-table'>Go back</a> to the user table.</p>\n";
			
			http_response_code(201);
			exit(page_renderer::render_main("Add User - $settings->sitename", $content));
		});
		
		
		/**
		 * @api {post} ?action=set-password	Set a user's password
		 * @apiName UserAdd
		 * @apiGroup Settings
		 * @apiPermission Moderator
		 *
		 * @apiParam	{string}	user		The username of the account to set the password for.
		 * @apiParam	{string}	new-pass	The new password for the specified username.
		 */
		
		/*
 		 * ███████ ███████ ████████
 		 * ██      ██         ██
 		 * ███████ █████      ██ █████
 		 *      ██ ██         ██
 		 * ███████ ███████    ██
 		 * 
 		 * ██████   █████  ███████ ███████ ██     ██  ██████  ██████  ██████
 		 * ██   ██ ██   ██ ██      ██      ██     ██ ██    ██ ██   ██ ██   ██
 		 * ██████  ███████ ███████ ███████ ██  █  ██ ██    ██ ██████  ██   ██
 		 * ██      ██   ██      ██      ██ ██ ███ ██ ██    ██ ██   ██ ██   ██
 		 * ██      ██   ██ ███████ ███████  ███ ███   ██████  ██   ██ ██████
		 */
		add_action("set-password", function() {
			global $env, $settings;
			
			if(!$env->is_admin) {
				http_response_code(401);
				exit(page_renderer::render_main("Error - Set Password - $settings->sitename", "<p>Error: You aren't logged in as a moderator, so you don't have permission to set a user's password.</p>"));
			}
			if(empty($_POST["user"])) {
				http_response_code(400);
				exit(page_renderer::render_main("Error - Set Password - $settings->sitename", "<p>Error: No username was provided via the 'user' POST parameter.</p>"));
			}
			if(empty($_POST["new-pass"])) {
				http_response_code(400);
				exit(page_renderer::render_main("Error - Set Password - $settings->sitename", "<p>Error: No password was provided via the 'new-pass' POST parameter.</p>"));
			}
			
			if(empty($settings->users->{$_POST["user"]})) {
				http_response_code(404);
				exit(page_renderer::render_main("User not found - Set Password - $settings->sitename", "<p>Error: No user called '".htmlentities($_POST["user"])."' was found, so their password can't be set. Perhaps you forgot to create the user first?</p>"));
			}
			
			$settings->users->{$_POST["user"]}->password = hash_password($_POST["new-pass"]);
			if(!save_settings()) {
				http_response_code(503);
				exit(page_renderer::render_main("Server Error - Set Password - $settings->sitename", "<p>Error: $settings->sitename couldn't save the settings back to disk! Nothing has been changed. Please context ".htmlentities($settings->admindetails_name).", whose email address can be found at the bottom of this page.</p>"));
			}
			
			exit(page_renderer::render_main("Set Password - $settings->sitename", "<p>" . htmlentities($_POST["user"]) . "'s password has been set successfully. <a href='?action=user-table'>Go back</a> to the user table.</p>"));
		});
		
		
		/**
		 * @api {post} ?action=user-delete	Delete a user account
		 * @apiName UserDelete
		 * @apiGroup Settings
		 * @apiPermission Moderator
		 *
		 * @apiParam	{string}	user		The username of the account to delete. username.
		 */
		
		/*
 		 * ██    ██ ███████ ███████ ██████
 		 * ██    ██ ██      ██      ██   ██
 		 * ██    ██ ███████ █████   ██████  █████
 		 * ██    ██      ██ ██      ██   ██
 		 *  ██████  ███████ ███████ ██   ██
 		 * 
 		 * ██████  ███████ ██      ███████ ████████ ███████
 		 * ██   ██ ██      ██      ██         ██    ██
 		 * ██   ██ █████   ██      █████      ██    █████
 		 * ██   ██ ██      ██      ██         ██    ██
 		 * ██████  ███████ ███████ ███████    ██    ███████
		 */
		add_action("user-delete", function() {
			global $env, $settings;
			
			if(!$env->is_admin || !$env->is_logged_in) {
				http_response_code(403);
				exit(page_renderer::render_main("Error - Delete User - $settings->sitename", "<p>Error: You aren't logged in as a moderator, so you don't have permission to delete a user's account.</p>"));
			}
			if(empty($_GET["user"])) {
				http_response_code(400);
				exit(page_renderer::render_main("Error - Delete User - $settings->sitename", "<p>Error: No username was provided in the <code>user</code> POST variable.</p>"));
			}
			if(empty($settings->users->{$_GET["user"]})) {
				http_response_code(404);
				exit(page_renderer::render_main("User not found - Delete User - $settings->sitename", "<p>Error: No user called ".htmlentities($_GET["user"])." was found, so their account can't be delete. Perhaps you spelt their account name incorrectly?</p>"));
			}
			
			email_user($_GET["user"], "Account Deletion", "Hello, {$_GET["user"]}!

This is a notification email from $settings->sitename to let you know that $env->user has deleted your user account, so you won't be able to log in to your account anymore.

If this was done in error, then please contact a moderator, or $settings->admindetails_name ($settings->sitename's Administrator) - whose email address can be found at the bottom of every page on $settings->sitename.

--$settings->sitename
Powered by Pepperminty Wiki

(Received this email in error? Please contact $settings->sitename's administrator as detailed above, as replying to this email may or may not reach a human at the other end)");
			
			// Actually delete the account
			unset($settings->users->{$_GET["user"]});
			
			if(!save_settings()) {
				http_response_code(503);
				exit(page_renderer::render_main("Server Error - Delete User - $settings->sitename", "<p>Error: $settings->sitename couldn't save the settings back to disk! Nothing has been changed. Please context ".htmlentities($settings->admindetails_name).", whose email address can be found at the bottom of this page.</p>"));
			}
			
			exit(page_renderer::render_main("Delete User - $settings->sitename", "<p>" . htmlentities($_GET["user"]) . "'s account has been deleted successfully. <a href='?action=user-table'>Go back</a> to the user table.</p>"));
		});
		
		
		if($env->is_admin) add_help_section("949-user-table", "Managing User Accounts", "<p>As a moderator on $settings->sitename, you can use the <a href='?action=user-table'>User Table</a> to adminstrate the user accounts on $settings->sitename. It allows you to perform actions such as adding and removing accounts, and resetting passwords.</p>");
	}
]);
/**
 * Generates a new (cryptographically secure) random password that's also readable (i.e. consonant-vowel-consonant).
 * This implementation may be changed in the future to use random dictionary words instead - ref https://xkcd.com/936/
 * @param	string	$length	The length of password to generate.
 * @return	string	The generated random password.
 */
function generate_password($length) {
	$vowels = "aeiou";
	$consonants = "bcdfghjklmnpqrstvwxyz";
	$result = "";
	for($i = 0; $i < $length; $i++) {
		if($i % 2 == 0)
			$result .= $consonants[random_int(0, strlen($consonants) - 1)];
		else
			$result .= $vowels[random_int(0, strlen($vowels) - 1)];
	}
	return $result;
}


/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "User watchlists",
	"version" => "0.1.4",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds per-user watchlists. When a page on a user's watchlist is edited, a notification email is sent.",
	"id" => "feature-watchlist",
	"code" => function() {
		/**
		 * @api {get} ?action=watchlist&foormat=format		Get your watchlist
		 * @apiName Watchlist
		 * @apiGroup Settings
		 * @apiPermission User
		 * 
		 * @apiParam {string}	format	The format to return the watchlist in.
		 * 
		 * @apiError	WatchlistsDisabled	Watchlists are disabled because the watchlists_enable setting is set to false.
		 * @apiError	NotLoggedIn			You aren't logged in, so you can't edit your watchlist (only logged in users have a watchlist).
		 * @apiError	NoEmailAddress		The currently logged in user doesn't have an email address specified in their account.
		 */
		
		/*
		 * ██     ██  █████  ████████  ██████ ██   ██ ██      ██ ███████ ████████
		 * ██     ██ ██   ██    ██    ██      ██   ██ ██      ██ ██         ██
		 * ██  █  ██ ███████    ██    ██      ███████ ██      ██ ███████    ██
		 * ██ ███ ██ ██   ██    ██    ██      ██   ██ ██      ██      ██    ██
		 *  ███ ███  ██   ██    ██     ██████ ██   ██ ███████ ██ ███████    ██
		 */
		add_action("watchlist", function() {
			global $settings, $env;
			
			if(!$settings->watchlists_enable) {
				http_response_code(403);
				header("x-problem: watchlists-disabled");
				exit(page_renderer::render_main("Watchlists disabled - $settings->sitename", "<p>Sorry, but watchlists are currently disabled on $settings->sitename. Contact your moderators to learn - their details are at the bottom of every page.</p>"));
			}
			
			if(!$env->is_logged_in) {
				http_response_code(401);
				header("x-problem: not-logged-in");
				exit(page_renderer::render_main("Not logged in - $settings->sitename", "<p>Only logged in users can have watchlists. Try <a href='?action=login&amp;returnto=".rawurlencode("?action=watchlist")."'>logging in</a>."));
			}
			
			if(empty($env->user_data->emailAddress)) {
				http_response_code(422);
				header("x-problem: no-email-address-in-user-preferences");
				exit(page_renderer::render_main("No email address specified -$settings->sitename", "<p>You are logged in, but have not specified an email address to send notifications to. Try specifying one in your <a href='?action=user-preferences'>user preferences</a> and then coming back here.</p>"));
			}
			
			$format = slugify($_GET["format"] ?? "html");
			
			$watchlist = [];
			if(!empty($env->user_data->watchlist))
				$watchlist = $env->user_data->watchlist;
			
			$mime_type = "text/html";
			$content = "";
			switch ($format) {
				case "html":
					$content .= "<h1>Watchlist</h1>";
					if(!empty($watchlist)) {
						$content .= "<ul class='page-list watchlist'>\n";
						foreach($watchlist as $pagename) {
							$content .= "<li><a href='?action=watchlist-edit&amp;page=".rawurlencode($pagename)."&amp;do=remove&amp;returnto=".rawurlencode("?action=watchlist&success=yes")."' title='Remove from watchlist'>&#x274c;</a> <a href='?page=".rawurlencode($pagename)."'>".htmlentities($pagename)."</a></li>";
						}
						$content .= "</ul>";
						$content .= "<p>You can also <a href='?action=watchlist-edit&amp;do=clear&amp;returnto=".rawurlencode("?action=watchlist")."'>clear your entire list</a> and start again.</p>";
					}
					else {
						$content .= "<p><em>You don't have any pages on your watchlist. Try visiting some pages and adding them to your watchlist and then coming back here.</em></p>";
					}
					$content = page_renderer::render_main("Watchlist - $settings->sitename", $content);
					break;
				
				case "text":
					$mime_type = "text/plain";
					foreach($watchlist as $pagename)
						$content .= "$pagename\n";
					break;
				
				case "json":
					$mime_type = "application/json";
					$content = json_encode($watchlist);
					break;
					
				default:
					http_response_code(400);
					header("content-type: text/plain");
					exit("Sorry, the format '$format' wasn't recognised. This action currently supports these formats: html, json, text");
					break;
			}
			
			header("content-type: $mime_type");
			header("content-length: " . strlen($content));
			exit($content);
		});
		
		
		/**
		 * @api {get} ?action=watchlist-edit&do={do_verb}[&page={pagename}][&returnto=url] Edit your watchlist
		 * @apiName WatchlistEdit
		 * @apiGroup Settings
		 * @apiPermission User
		 * 
		 * @apiParam {string}	pagename	The name of the page to operate on.
		 * @apiParam {string}	do			The thing to do. Supported verbs: add, remove, clear. The first 2 require the page GET parameter to be specified, but the clear verb doesn't (as it clears the entire list).
		 * @apiParam {string}	returnto	Optional. Specifies a URL to redirect to (with the http status code 302) upon success.
		 *
		 * @apiError	WatchlistsDisabled	Watchlists are disabled because the watchlists_enable setting is set to false.
		 * @apiError	NotLoggedIn			You aren't logged in, so you can't edit your watchlist (only logged in users have a watchlist).
		 * @apiError	NoEmailAddress		The currently logged in user doesn't have an email address specified in their account.
		 * @apiError	DoVerbNotRecognised	The specified do verb was not recognised. Supported verbs: add, remove, clear (a canonical list is returned with this error).
		 *
		 * @apiError	PageNotFoundOnWiki		The page name specified was not found on the wiki, so it can't be watched.
		 * @apiError	PageNotFoundOnWatchlist	The page name was not found in your watchlist.
		 */
		
		/*
		 * ███████ ██████  ██ ████████
		 * ██      ██   ██ ██    ██
		 * █████   ██   ██ ██    ██
		 * ██      ██   ██ ██    ██
		 * ███████ ██████  ██    ██
		 */
		add_action("watchlist-edit", function () {
			global $settings, $env, $pageindex;
			
			// The thing we should do.
			$do = slugify($_GET["do"] ?? "null");
			// The location we should redirect to after doing it successfully, if anywhere
			$returnto = empty($_GET["returnto"]) ? null : $_GET["returnto"];
			
			if(!$settings->watchlists_enable) {
				http_response_code(403);
				header("x-status: failed");
				header("x-problem: watchlists-disabled");
				exit(page_renderer::render_main("Watchlists disabled - $settings->sitename", "<p>Sorry, but watchlists are currently disabled on $settings->sitename. Contact your moderators to ask about this - their details are at the bottom of every page (including this one).</p>"));
			}
			
			if(!$env->is_logged_in) {
				http_response_code(401);
				header("x-status: failed");
				header("x-problem: not-logged-in");
				exit(page_renderer::render_main("Not logged in - $settings->sitename", "<p>Only logged in users can have watchlists. Try <a href='?action=login&amp;returnto=".rawurlencode("?action=watchlist-edit&do=$do&returnto=".htmlentities($returnto))."'>logging in</a>.</p>"));
			}
			
			if(empty($env->user_data->emailAddress)) {
				http_response_code(422);
				header("x-status: failed");
				header("x-problem: no-email-address-in-user-preferences");
				exit(page_renderer::render_main("No email address specified -$settings->sitename", "<p>You are logged in, but have not specified an email address to send notifications to. Try specifying one in your <a href='?action=user-preferences'>user preferences</a> and then coming back here.</p>"));
			}
			
			// If the watchlist doesn't exist, create it
			// Note that saving this isn't essential - so we don't bother unless we perform some other action too.
			if(!isset($env->user_data->watchlist) || !is_array($env->user_data->watchlist))
				$env->user_data->watchlist = [];
			
			switch($do) {
				case "add":
					if(empty($pageindex->{$env->page})) {
						http_response_code(404);
						header("x-status: failed");
						header("x-problem: page-not-found-on-wiki");
						exit(page_renderer::render_main("Page not found - Error - $settings->sitename", "<p>Oops! The page name <em>".htmlentities($env->page)."</em> couldn't be found on $settings->sitename. Try <a href='?action=edit&page=".rawurlencode($env->page)."'>creating it</a> and trying to add it to your watchlist again!</p>"));
					}
					if(in_array($env->page, $env->user_data->watchlist)) {
						http_response_code(422);
						header("x-status: failed");
						header("x-problem: watchlist-page-already-present");
						exit(page_renderer::render_main("Already on watchlist - Error - $settings->sitename", "<p>The page with the name <em>".htmlentities($env->page)."</em> is already on your watchlist, so it can't be added again.</p>"));
					}
					// Add the new page to the watchlist
					$env->user_data->watchlist[] = $env->page;
					// Sort the list
					$collator = new Collator("");
					$collator->sort($env->user_data->watchlist, SORT_NATURAL | SORT_FLAG_CASE);
					// Save back to disk
					save_settings();
					break;
				case "remove":
					$index = array_search($env->page, $env->user_data->watchlist);
					if($index === false) {
						http_response_code(400);
						header("x-status: failed");
						header("x-problem: watchlist-item-not-found");
						exit(page_renderer::render_main("Watchlist item not found - Error - $settings->sitename", "<p>Oops! The page with the name <em>$env->page_safe</em> isn't currently on your watchlist, so it couldn't be removed. Perhaps you already removed it?</p>
						<p>Try going <a href='?action=watchlist'>back to your watchlist</a>.</p>"));
					}
					array_splice($env->user_data->watchlist, $index, 1);
					save_settings();
					break;
				case "clear":
					$env->user_data->watchlist = [];
					save_settings();
					break;
				default:
					http_response_code(400);
					header("x-status: failed");
					header("x-problem: watchlist-do-verb-not-recognised");
					header("content-type: text/plain");
					exit("Error: The do verb '$do' wasn't recognised. Current verbs supported: add, remove, clear");
			}
			
			$message = "Your watchlist was updated successfully.";
			if(!empty($returnto)) {
				http_response_code(302);
				header("x-status: success");
				header("location: $returnto");
				$message .= " <a href='".htmlentities($returnto)."'>Click here</a> to return to your previous page.";
			}
			else
				$message .= " <a href='javascript:history.back();'>Go back</a> to your previous page, or <a href='?action=watchlist'>review your watchlist</a>.</a>";
			exit(page_renderer::render_main("Watchlist update successful", "<p>$message</p>"));
		});
		
		if(!module_exists("page-edit")) {
			error_log("[PeppermintyWiki/$settings->sitename/feature-watchlist] Note: Without the page-edit module, the feature-watchlist module doesn't make much sense. If you don't want anonymous people to edit your wiki, try the 'anonedits' setting.");
			return false;
		}
		
		register_save_preprocessor(function($indexentry, $new_data, $old_data) {
			global $version, $commit, $env, $settings;
			
			$usernames = [];
			foreach($settings->users as $username => $user_data) {
				// Skip if this is the user that made the edit
				if($username == $env->user)
					continue;
				
				// If the user's watchlist is empty, then there's no point in checking it
				if(empty($user_data->watchlist))
					continue;
				
				// If it's not in the watchlist, then we shouldn't send an email
				if(!in_array($env->page, $user_data->watchlist))
					continue;
				
				$usernames[] = $username;
			}
			
			$chars_changed = strlen($new_data) - strlen($old_data);
			$chars_changed_text = ($chars_changed < 0 ? "removes " : "adds ") . "$chars_changed characters";
			
			$url_stem = url_stem();
			
			email_users(
				$usernames,
				"{$env->page} was updated by {$env->user} - $settings->sitename",
				"Hey there!

{$env->page} was updated by {$env->user} at ".render_timestamp(time(), true, false).", which $chars_changed_text.

View the latest revision here: {$url_stem}?page=".rawurlencode($env->page)."

---------- New page text ----------
$new_data
-----------------------------------

--$settings->sitename, powered by Pepperminty Wiki $version-".substr($commit, 0, 7)."

(P.S. Don't reply to this email, because it may not recieve a reply. Instead try contacting $settings->admindetails_name at $settings->admindetails_email, who is $settings->sitename's administrator if you have any issues.)
"
			);
		});
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Library: Search engine",
	"version" => "0.13.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "A library module that provides the backend to the search engine module.",
	"id" => "lib-search-engine",
	"depends" => [ "lib-storage-box" ],
	"code" => function() {
		
	}
]);


/*
███████ ███████  █████  ██████   ██████ ██   ██
██      ██      ██   ██ ██   ██ ██      ██   ██
███████ █████   ███████ ██████  ██      ███████
     ██ ██      ██   ██ ██   ██ ██      ██   ██
███████ ███████ ██   ██ ██   ██  ██████ ██   ██
*/

/**
 * Holds a collection to methods to manipulate various types of search index.
 * @package search
 */
class search
{
	/**
	 * Words that we should exclude from the inverted index.
	 * @source	http://xpo6.com/list-of-english-stop-words/
	 * @var string[]
	 */
	public static $stop_words = [
		"a", "about", "above", "above", "across", "after", "afterwards", "again",
		"against", "all", "almost", "alone", "along", "already", "also",
		"although", "always", "am", "among", "amongst", "amoungst", "amount",
		"an", "and", "another", "any", "anyhow", "anyone", "anything", "anyway",
		"anywhere", "are", "around", "as", "at", "back", "be", "became",
		"because", "become", "becomes", "becoming", "been", "before",
		"beforehand", "behind", "being", "below", "beside", "besides",
		"between", "beyond", "bill", "both", "bottom", "but", "by", "call",
		"can", "can't", "cannot", "co", "con", "could", "couldnt", "cry", "de",
		"describe", "detail", "do", "done", "down", "due", "during", "each",
		"eg", "eight", "either", "eleven", "else", "elsewhere", "empty",
		"enough", "etc", "even", "ever", "every", "everyone", "everything",
		"everywhere", "except", "few", "fill", "find",
		"fire", "first", "five", "for", "former", "formerly", "found",
		"four", "from", "front", "full", "further", "get", "give", "go", "had",
		"has", "hasnt", "have", "he", "hence", "her", "here", "hereafter",
		"hereby", "herein", "hereupon", "hers", "herself", "him", "himself",
		"his", "how", "however", "ie", "if", "in", "inc", "indeed",
		"interest", "into", "is", "it", "its", "it's", "itself", "keep", "last",
		"latter", "latterly", "least", "less", "ltd", "made", "many", "may",
		"me", "meanwhile", "might", "mine", "more", "moreover", "most",
		"mostly", "move", "much", "must", "my", "myself", "name", "namely",
		"neither", "never", "nevertheless", "next", "nine", "no", "none",
		"nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on",
		"once", "one", "only", "onto", "or", "other", "others", "otherwise",
		"our", "ours", "ourselves", "out", "over", "own", "part", "per",
		"perhaps", "please", "put", "rather", "re", "same", "see", "seem",
		"seemed", "seeming", "seems", "serious", "several", "she", "should",
		"show", "side", "since", "sincere", "six", "sixty", "so", "some",
		"somehow", "someone", "something", "sometime", "sometimes",
		"somewhere", "still", "such", "system", "take", "ten", "than", "that",
		"the", "their", "them", "themselves", "then", "thence", "there",
		"thereafter", "thereby", "therefore", "therein", "thereupon", "these",
		"they", "thickv", "thin", "third", "this", "those", "though", "three",
		"through", "throughout", "thru", "thus", "to", "together", "too", "top",
		"toward", "towards", "twelve", "twenty", "two", "un", "under", "until",
		"up", "upon", "us", "very", "via", "was", "we", "well", "were", "what",
		"whatever", "when", "whence", "whenever", "where", "whereafter",
		"whereas", "whereby", "wherein", "whereupon", "wherever", "whether",
		"which", "while", "whither", "who", "whoever", "whole", "whom", "whose",
		"why", "will", "with", "within", "without", "would", "yet", "you",
		"your", "yours", "yourself", "yourselves"
	];
	
	/**
	 * The StorageBox that contains the inverted index.
	 * @var StorageBox
	 */
	private static $invindex = null;
	/**
	 * The 'did you mean?' index for typo correction.
	 * Only populated if the feature-search-didyoumean module is present.
	 * @var BkTree
	 */
	public static $didyoumeanindex = null;
	
	/**
	 * The transliterator that can be used to transliterate strings.
	 * Transliterated strings are more suitable for use with the search index.
	 * Note that this is no longer wrapped in a function as of v0.21 for 
	 * performance reasons.
	 * @var Transliterator
	 */
	public static $literator = null;
	
	/**
	 * Sorter for sorting lists of *transliterated* strings.
	 * Should work for non-transliterated strings too.
	 * @var Collator
	 */
	private static $sorter;
	
	/**
	 * Initialises the search system.
	 * Do not call this function! It is called automatically.
	 */
	public static function init() {
		self::$literator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);
		self::$sorter = new Collator("");
	}
	
	/**
	 * Logs a progress message in the right format depending on the current
	 * environment.
	 * @param string $message The message to log.
	 */
	private static function log_progress(string $message, bool $sameline = false) : void {
		if(is_cli()) {
			if($sameline) $message = "$message\r";
			else $message = "$message\n";
			echo($message);
		}
		else {
			echo("data: $message\n\n");
			flush();
		}
	}
	
	/**
	 * Loads the didyoumean index.
	 * Don't forget to call this before making any search queries if didyoumean
	 * typo correction is enabled.
	 * Note that calling it multiple times has no effect. Returns true if the
	 * didyoumean index is already loaded.
	 * @param	string	$filename	The filename of the didyoumean index.
	 * @param	string	$seed_word	The seed word. If this changes, the index must be rebuilt.
	 * @return	bool	Whether the index was loaded successfully or not. Returns false if the feature-search-didyoumean module is not present.
	 */
	public static function didyoumean_load() : bool {
		global $settings, $paths;
		if(!module_exists("feature-search-didyoumean"))
			return false;
		
		// Avoid loading twice
		if(is_a(self::$didyoumeanindex, BkTree::class))
			return true;
		
		self::$didyoumeanindex = new BkTree(
			$paths->didyoumeanindex,
			$settings->search_didyoumean_seed_word
		);
		self::$didyoumeanindex->set_costs(
			$settings->search_didyoumean_cost_insert,
			$settings->search_didyoumean_cost_delete,
			$settings->search_didyoumean_cost_replace
		);
		return true;
	}
	
	/**
	 * Returns a correction for a given word according to the didyoumean index.
	 * Note that this is quite an expensive call.
	 * Check that the word exists in the regular search index first, and that
	 * it's not a stop word before calling this function.
	 * @param	string	$term	The term to correct.
	 * @return	string|null		The closest correction found, or null if none could be located.
	 */
	public static function didyoumean_correct(string $term) : ?string {
		global $settings, $paths, $env;
		$start_time = microtime(true);
		
		// Load the didyoumean index, but only if it's enabled etc
		if(!module_exists("feature-search-didyoumean") || !$settings->search_didyoumean_enabled)
			return null;
		
		// If it's not loaded already, load the didyoumean index on-demand
		if(self::$didyoumeanindex == null)
			search::didyoumean_load($paths->searchindex);
		
		$results = self::$didyoumeanindex->lookup(
			$term,
			$settings->search_didyoumean_editdistance
		);
		if(!empty($results)) {
			usort($results, function($a, $b) : int {
				return self::$sorter->compare($a, $b);
			});
		}
		
		if(!isset($env->perfdata->didyoumean_correction))
			$env->perfdata->didyoumean_correction = 0; 
		$env->perfdata->didyoumean_correction += (microtime(true) - $start_time) * 1000;
		return $results[0] ?? null;
	}
	
	public static function didyoumean_rebuild(bool $output = true) : void {
		global $env;
		if($output && !is_cli()) {
			header("content-type: text/event-stream");
			ob_end_flush();
		}
		
		$env->perfdata->didyoumean_rebuild = microtime(true);
		
		if($output) self::log_progress("Beginning didyoumean index rebuild");
		if($output) self::log_progress("Loading indexes");
		
		self::invindex_load();
		self::didyoumean_load();
		
		if($output) self::log_progress("Populating index");
		
		self::$didyoumeanindex->clear();
		$i = 0;
		foreach(self::$invindex->get_keys("|") as $key) {
			$key = $key["key"];
			
			if(self::$didyoumeanindex->add($key) === null && $output)
				self::log_progress("[$i] Skipping '$key' as it's too long");
			elseif($output && $i % 1500 == 0) self::log_progress("[$i] Added '$key'", true);
			$i++;
		}
		self::log_progress(""); // Blank newline
		if($output) self::log_progress("Syncing to disk...");
		
		// Closing = saving, but we can't use it afterwards
		self::$didyoumeanindex->close();
		
		// Just in case it's loaded again later
		self::$didyoumeanindex = null;
		
		$env->perfdata->didyoumean_rebuild = round(microtime(true) - $env->perfdata->didyoumean_rebuild, 4);
		if($output) self::log_progress("didyoumean index rebuild complete in {$env->perfdata->didyoumean_rebuild}s");
	}
	
	/**
	 * Converts a source string into an index of search terms that can be
	 * merged into an inverted index.
	 * Automatically transliterates the source string.
	 * @param  string $source The source string to index.
	 * @return array         An index represents the specified string.
	 */
	public static function index_generate(string $source) : array {
		// We don't need to normalise or transliterate here because self::tokenize() does this for us
		$source = html_entity_decode($source, ENT_QUOTES);
		$source_length = mb_strlen($source);
		
		$index = [];
		
		$terms = self::tokenize($source, true);
		foreach($terms as $term) {
			// Skip over stop words (see https://en.wikipedia.org/wiki/Stop_words)
			if(in_array($term[0], self::$stop_words)) continue;
			
			if(!isset($index[$term[0]]))
				$index[$term[0]] = [ "freq" => 0, "offsets" => [] ];
			
			$index[$term[0]]["freq"]++;
			$index[$term[0]]["offsets"][] = $term[1];
		}
		
		return $index;
	}
	
	/**
	 * Converts a source string into a series of raw tokens.
	 * @param	string	$source				The source string to process.
	 * @param	bool	$capture_offsets	Whether to capture & return the character offsets of the tokens detected. If true, then each token returned will be an array in the form [ token, char_offset ].
	 * @return	array	An array of raw tokens extracted from the specified source string.
	 */
	public static function tokenize(string $source, bool $capture_offsets = false) : array {
		
		$flags = PREG_SPLIT_NO_EMPTY; // Don't return empty items
		if($capture_offsets)
			$flags |= PREG_SPLIT_OFFSET_CAPTURE;
		
		// We don't need to normalise here because the transliterator handles that
		$source = self::$literator->transliterate($source);
		$source = preg_replace('/[\[\]\|\{\}\/]/u', " ", $source);
		return preg_split("/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))|\|/u", $source, -1, $flags);
	}
	
	/**
	 * Removes (most) markdown markup from the specified string.
	 * Stripped strings are not suitable for indexing!
	 * @param	string	$source	The source string to process.
	 * @return	string			The stripped string.
	 */
	public static function strip_markup(string $source) : string {
		return preg_replace('/([\"*_\[\]]| - |`)/u', "", $source);
	}
	
	/**
	 * Rebuilds the master inverted index and clears the page id index.
	 * @param	bool	$output	Whether to send progress information to the user's browser.
	 */
	public static function invindex_rebuild(bool $output = true) : void {
		global $pageindex, $env, $paths, $settings;
		$env->perfdata->invindex_rebuild = microtime(true);
		
		if($output && !is_cli()) {
			header("content-type: text/event-stream");
			ob_end_flush();
		}
		
		
		// Clear the id index out
		ids::clear();
		
		// Clear the existing inverted index out
		if(self::$invindex == null)
			self::invindex_load($paths->searchindex);
		self::$invindex->clear();
		self::$invindex->set("|termlist|", []);
		
		// Reindex each page in turn
		$i = 0; $max = count(get_object_vars($pageindex));
		$missing_files = 0;
		foreach($pageindex as $pagename => $pagedetails)
		{
			$page_filename = $env->storage_prefix . $pagedetails->filename;
			if(!file_exists($page_filename)) {
				if(!is_cli()) echo("data: ");
				echo("[" . ($i + 1) . " / $max] Error: Can't find $page_filename\n");
				flush();
				$i++; $missing_files++;
				continue;
			}
			// We do not transliterate or normalise here because the indexer will take care of this for us
			$index = self::index_generate(file_get_contents($page_filename));
			
			$pageid = ids::getid($pagename);
			self::invindex_merge($pageid, $index);
			
			if($output) {
				$message = "[" . ($i + 1) . " / $max] Added $pagename (id #$pageid) to the new search index.";
				if(!is_cli()) $message = "data: $message\n\n";
				else $message = "$message\r";
				echo($message);
				flush();
			}
			
			$i++;
		}
		
		$msg = "Syncing to disk....";
		if(!is_cli()) $msg = "data: $msg\n\n";
		else $msg = "$msg\r";
		echo($msg);
		
		self::invindex_close();
		
		$env->perfdata->invindex_rebuild = round(microtime(true) - $env->perfdata->invindex_rebuild, 4);
		
		if($output && !is_cli()) {
			echo("data: Search index rebuilding complete in {$env->perfdata->invindex_rebuild}s.\n\n");
			echo("data: Couldn't find $missing_files pages on disk. If $settings->sitename couldn't find some pages on disk, then you might need to manually correct $settings->sitename's page index (stored in pageindex.json).\n\n");
			echo("data: Done! Saving new search index to '$paths->searchindex'.\n\n");
		}
		if(is_cli()) echo("\nSearch index rebuilding complete in {$env->perfdata->invindex_rebuild}s.\n");
	}
	
	/**
	 * Sorts an index alphabetically.
	 * This allows us to do a binary search instead of a regular
	 * sequential search.
	 * @param	array	$index	The index to sort.
	 */
	public static function index_sort(&$index) {
		$sorter = self::$sorter;
		uksort($index, function($a, $b) use($sorter) : int {
			return $sorter->compare($a, $b);
		});
	}
	/**
	 * Sorts an index by frequency.
	 * @param  array $index The index to sort.
	 */
	public static function index_sort_freq(&$index) {
		uasort($index, function($a, $b) : int {
			return $b["freq"] > $a["freq"];
		});
	}
	
	/**
	 * Compares two *regular* indexes to find the differences between them.
	 * @param	array	$oldindex	The old index.
	 * @param	array	$newindex	The new index.
	 * @param	array	$changed	An array to be filled with the nterms of all the changed entries.
	 * @param	array	$removed	An array to be filled with the nterms of all  the removed entries.
	 */
	public static function index_compare($oldindex, $newindex, &$changed, &$removed) {
		foreach($oldindex as $nterm => $entry) {
			if(!isset($newindex[$nterm]))
				$removed[] = $nterm;
		}
		foreach($newindex as $nterm => $entry) {
			if(!isset($oldindex[$nterm]) or // If this word is new
			   $newindex[$nterm] !== $oldindex[$nterm]) // If this word has changed
				$changed[$nterm] = $newindex[$nterm];
		}
	}
	
	/**
	 * Loads a connection to an inverted index.
	 */
	public static function invindex_load() {
		global $env, $paths;
		// If the inverted index is alreayd loaded, it doesn't need loading again
		if(self::$invindex !== null) return;
		$start_time = microtime(true);
		self::$invindex = new StorageBox($paths->searchindex);
		$env->perfdata->searchindex_load_time = round((microtime(true) - $start_time)*1000, 3);
	}
	
	/**
	 * Closes the currently open inverted index.
	 */
	public static function invindex_close() {
		global $env;
		
		$start_time = microtime(true);
		self::$invindex->close();
		self::$invindex = null;
		$env->perfdata->searchindex_close_time = round((microtime(true) - $start_time)*1000, 3);
	}
	
	/**
	 * Merge an index into an inverted index.
	 * @param	int		$pageid		The id of the page to assign to the index that's being merged.
	 * @param	array	$index		The regular index to merge.
	 * @param	array	$removals	An array of index entries to remove from the inverted index. Useful for applying changes to an inverted index instead of deleting and remerging an entire page's index.
	 */
	public static function invindex_merge($pageid, &$index, &$removals = []) : void {
		if(self::$invindex == null)
			throw new Exception("Error: Can't merge into an inverted index that isn't loaded.");
		
		if(!self::$invindex->has("|termlist|"))
			self::$invindex->set("|termlist|", []);
		$termlist = self::$invindex->get("|termlist|");
		
		// Remove all the subentries that were removed since last time
		foreach($removals as $nterm) {
			// Delete the offsets
			self::$invindex->delete("$nterm|$pageid");
			// Delete the item from the list of pageids containing this term
			$nterm_pageids = self::$invindex->get_arr_simple($nterm);
			array_splice($nterm_pageids, array_search($pageid, $nterm_pageids), 1);
			if(empty($nterm_pageids)) { // No need to keep the pageid list if there's nothing in it
				self::$invindex->delete($nterm);
				// Update the termlist if we're deleting the term completely
				$termlist_loc = array_search($nterm, $termlist);
				if($termlist_loc !== false) array_splice($termlist, $termlist_loc, 1);
			}
			else
				self::$invindex->set_arr_simple($nterm, $nterm_pageids);
		}
		
		// Merge all the new / changed index entries into the inverted index
		foreach($index as $nterm => $newentry) {
			// if(!is_string($nterm)) $nterm = strval($nterm);
			if(!self::$invindex->has($nterm)) {
				self::$invindex->set_arr_simple($nterm, []);
				$termlist[] = $nterm;
			}
			
			// Update the nterm pageid list
			$nterm_pageids = self::$invindex->get_arr_simple($nterm);
			if(array_search($pageid, $nterm_pageids) === false) {
				$nterm_pageids[] = $pageid;
				self::$invindex->set_arr_simple($nterm, $nterm_pageids);
			}
			
			// Store the offset list
			self::$invindex->set("$nterm|$pageid", $newentry);
		}
		
		self::$invindex->set("|termlist|", $termlist);
	}
	
	/**
	 * Deletes the given pageid from the given pageindex.
	 * @param  int		$pageid		The pageid to remove.
	 */
	public static function invindex_delete(int $pageid) {
		$termlist = self::$invindex->get("|termlist|");
		foreach($termlist as $nterm) {
			$nterm_pageids = self::$invindex->get_arr_simple($nterm);
			$nterm_loc = array_search($pageid, $nterm_pageids);
			// If this nterm doesn't appear in the list, we're not interested
			if($nterm_loc === false)
				continue;
			
			// Delete it from the ntemr list
			array_splice($nterm_pageids, $nterm_loc, 1);
			
			// Delete the offset list
			self::$invindex->delete("$nterm|$pageid");
			
			// If this term doesn't appear in any other documents, delete it
			if(count($nterm_pageids) === 0) {
				self::$invindex->delete($nterm);
				array_splice($termlist, array_search($nterm, $termlist), 1);
			}
			else // Save the document id list back, since it still contains other pageids
				self::$invindex->set_arr_simple($nterm, $nterm_pageids);
		}
		// Save the termlist back to the store
		self::$invindex->set("|termlist|", $termlist);
	}
	
	
	/*
	 * ███████ ████████  █████  ███████
	 * ██         ██    ██   ██ ██
	 * ███████    ██    ███████ ███████
	 *      ██    ██    ██   ██      ██
	 * ███████    ██    ██   ██ ███████
	 */
	
	/**
	 * Splits a query string into tokens. Does not require that the input string be transliterated.
	 * Was based on my earlier explode_adv: https://starbeamrainbowlabs.com/blog/article.php?article=posts/081-PHP-String-Splitting.html
	 * Now improved to be strtok-based, since it's much faster.
	 * Example I used when writing this: https://www.php.net/manual/en/function.strtok.php#94463
	 * @param	string	$query	The query string to split.
	 */
	public static function stas_split($query) {
		$query = self::$literator->transliterate($query);
		
		$terms = [];
		$next_token = strtok($query, " \r\n\t");
		while(true) {
			if(strpos($next_token, '"') !== false)
				$next_token .= " " . strtok('"') . '"';
			if(strpos($next_token, "'") !== false)
				$next_token .= " " . strtok("'") . "'";

			$terms[] = $next_token;
			
			$next_token = strtok(" \r\n\t");
			if($next_token === false) break;
		}
		
		return $terms;
	}

	/**
	 * Parses an array of query tokens into an associative array of search directives.
	 * Supported syntax derived from these sources:
		 * https://help.duckduckgo.com/duckduckgo-help-pages/results/syntax/
		 * https://docs.microsoft.com/en-us/windows/win32/lwef/-search-2x-wds-aqsreference

	 * @param	string[]	$tokens	The array of query tokens to parse.
	 */
	public static function stas_parse($tokens) {
		global $settings;
		
		/* Supported Syntax *
		 * 
		 * -term				exclude a term
		 * +term				double the weighting of a term
		 * terms !dest terms	redirect entire query (minus the !bang) to interwiki with registered shortcut dest
		 * prefix:term			apply prefix operator to term
		 * "term"				exactly this term (don't try and correct)
		 */
		$result = [
			"terms" => [],
			"exclude" => [],
			"interwiki" => null
		];
		
		
		$count = count($tokens);
		for($i = count($tokens) - 1; $i >= 0; $i--) {
			// Look for excludes
			if($tokens[$i][0] == "-") {
				if(in_array(substr($tokens[$i], 1), self::$stop_words)) {
					$result["tokens"][] = [
						"term" => substr($tokens[$i], 1),
						"weight" => -1,
						"location" => "all",
						"exact" => false
					];
				}
				else // FUTURE: Correct excludes too
					$result["exclude"][] = substr($tokens[$i], 1);
				
				continue;
			}

			// Look for weighted terms
			if($tokens[$i][0] == "+") {
				if(in_array(substr($tokens[$i], 1), self::$stop_words)) {
					$result["tokens"] = [ "term" => substr($tokens[$i], 1), "weight" => -1, "location" => "all" ];
				}
				else {
					$term = trim(substr($tokens[$i], 1), '"');
					$result["terms"][] = [
						"term" => $term,
						"weight" => 2,
						"location" => "all",
						// if it's different, then there were quotes
						"exact" => substr($tokens[$i], 1) != $term
					];
				}
				continue;
			}

			// Look for interwiki searches
			// You can only go to 1 interwiki destination at once, so we replace any previous finding with this one
			if($tokens[$i][0] == "!" || substr($tokens[$i], -1) == "!")
				$result["interwiki"] = trim($tokens[$i], "!");
			
			// Look for colon directives in the form directive:term
			// Also supports prefix:"quoted term with spaces", quotes stripped automatically
			/*** Example directives *** (. = implemented, * = not implemented)
			 . intitle		search only page titles for term
			 . intags		search only tags for term
			 . inbody		search page body only for term
			 * before		search only pages that were last modified before term
			 * after		search only pages that were last modified after term
			 * size			search only pages that match the size spec term (e.g. 1k+ -> more than 1k bytes, 2k- -> less than 2k bytes, >5k -> more than 5k bytes, <10k -> less than 10k bytes)
			 **************************/
			if(strpos($tokens[$i], ":") !== false) {
				$parts = explode(":", $tokens[$i], 2);
				
				$exact = false;
				$term = trim($parts[1], '"');
				// If we trim off quotes, then it must be because it should be exact
				if($parts[1] != $term) $exact = true;
				
				switch($parts[0]) {
					case "intitle": // BUG: What if a normal word is found in a title?
						$result["terms"][] = [
							"term" => $term,
							"weight" => $settings->search_title_matches_weighting * mb_strlen($parts[1]),
							"location" => "title",
							"exact" => $exact
						];
						break;
					case "intags":
						$result["terms"][] = [
							"term" => $term,
							"weight" => $settings->search_tags_matches_weighting * mb_strlen($parts[1]),
							"location" => "tags",
							"exact" => $exact
						];
						break;
					case "inbody":
						$result["terms"][] = [
							"term" => $term,
							"weight" => 1,
							"location" => "body",
							"exact" => $exact
						];
						break;
					default:
						if(!isset($result[$parts[0]]))
							$result[$parts[0]] = [];
						$result[$parts[0]][] = $term;
						break;
				}
				continue;
			}
			
			$exact = false;
			$term = trim($tokens[$i], '"');
			// If we trim off quotes, then it must be because it should be exact
			if($tokens[$i] != $term) $exact = true;
			
			// Doesn't appear to be particularly special *shrugs*
			// Set the weight to -1 if it's a stop word
			$result["terms"][] = [
				"term" => $term,
				"weight" => in_array($tokens[$i], self::$stop_words) ? -1 : 1,
				"location" => "all",
				"exact" => $exact // If true then we shouldn't try to autocorrect it
			];
		}
		
		
		// Correct typos, but only if that's enabled
		if(module_exists("feature-search-didyoumean") && $settings->search_didyoumean_enabled) {
			$terms_count = count($result["terms"]);
			for($i = 0; $i < $terms_count; $i++) {
				// error_log("[stas_parse/didyoumean] Now looking at #$i:  ".var_export($result["terms"][$i], true)."(total count: $terms_count)");
				if($result["terms"][$i]["exact"] || // Skip exact-only
					$result["terms"][$i]["weight"] < 1 || // Skip stop & irrelevant words
					self::invindex_term_exists($result["terms"][$i]["term"]))
						continue;
				
				// It's not a stop word or in the index, try and correct it
				// self::didyoumean_correct auto-loads the didyoumean index on-demand
				$correction = self::didyoumean_correct($result["terms"][$i]["term"]);
				// Make a note if we fail to correct a term
				if(!is_string($correction)) {
					$result["terms"][$i]["corrected"] = false;
					continue;
				}
				
				$result["terms"][$i]["term_before"] = $result["terms"][$i]["term"];
				$result["terms"][$i]["term"] = $correction;
				$result["terms"][$i]["corrected"] = true;
			}
		}
		
		return $result;
	}
	
	/**
	 * Determines whether a term exists in the currently loaded inverted search
	 * index.
	 * Note that this only checked for precisely $term. See
	 * search::didyoumean_correct() for typo correction.
	 * @param	string	$term	The term to search for.
	 * @return	bool	Whether term exists in the inverted index or not.
	 */
	public static function invindex_term_exists(string $term) {
		// In the inverted index $term should have a list of page names in it
		// if the temr exists in the index, and won't exists if not
		return self::$invindex->has($term);
	}
	
	/**
	 * Returns the page ids that contain the given (transliterated) search term.
	 * @param  string $term The search term to look for.
	 * @return string[]       The list of page ids that contain the given term.
	 */
	public static function invindex_term_getpageids(string $term) {
		return self::$invindex->get_arr_simple($term);
	}
	
	/**
	 * Gets the offsets object for a given term on a given page.
	 * The return object is in the form { freq: 4, offsets: [2,3,4] }
	 * @param	string	$term	The term to search for.
	 * @param	int		$pageid	The id of the page to retrieve the offsets list for.
	 * @return	object	The offsets object as described above.
	 */
	public static function invindex_term_getoffsets(string $term, int $pageid) {
		return self::$invindex->get("$term|$pageid");
	}
	
	/**
	 * Searches the given inverted index for the specified search terms.
	 * Note that this automatically pushes the query string through STAS which
	 * can be a fairly expensive operation, so use 2nd argument if you need
	 * to debug the STAS parsing result if possible.
	 * @param	string		$query		The search query. If an array is passed, it is assumed it has already been pre-parsed with search::stas_parse().
	 * @param	&stdClass	$query_stas	An object to fill with the result of the STAS parsing.
	 * @return	array	An array of matching pages.
	 */
	public static function invindex_query($query, &$query_stas = null)
	{
		global $settings, $pageindex;
		
		$query_stas = self::stas_parse(
			self::stas_split(self::$literator->transliterate($query))
		);
		
		/* Sub-array format:
		 * [
		 * 	nterms : [ nterm => frequency, nterm => frequency, .... ],
		 * 	offsets_body : int[],
		 * 	matches_title : int,
		 * 	matches_tags : int
		 * ]
		 */
		$matching_pages = [];
		$match_template = [
			"nterms" => [],
			"offsets_body" => [],
			"rank_title" => 0,
			"rank_tags" => 0
		];
		
		// Query the inverted index
		foreach($query_stas["terms"] as $term_def) {
			if($term_def["weight"] == -1)
				continue; // Skip stop words
			
			if(!in_array($term_def["location"], ["all", "body"]))
				continue; // Skip terms we shouldn't search the page body for
			
			if(!self::$invindex->has($term_def["term"]))
				continue; // Skip if it's not in the index
			
			// For each page that contains this term.....
			$term_pageids = self::$invindex->get_arr_simple($term_def["term"]);
			foreach($term_pageids as $pageid) {
				// Check to see if it contains any words we should exclude
				$skip = false;
				foreach($query_stas["exclude"] as $excl_term) {
					if(self::$invindex->has("$excl_term|$pageid")) {
						$skip = true;
						break;
					}
				}
				if($skip) continue;
				
				// Get the list of offsets
				$page_offsets = self::$invindex->get("{$term_def["term"]}|$pageid");
				
				if(!isset($matching_pages[$pageid]))
					$matching_pages[$pageid] = $match_template; // Arrays are assigned by copy in php
				
				// Add it to the appropriate $matching_pages entry, not forgetting to apply the weighting
				$matching_pages[$pageid]["offsets_body"] = array_merge(
					$matching_pages[$pageid]["offsets_body"],
					$page_offsets->offsets
				);
				$matching_pages[$pageid]["nterms"][$term_def["term"]] = $page_offsets->freq * $term_def["weight"];
			}
			
		}
		
		// Query page titles & tags
		foreach($query_stas["terms"] as $term_def) {
			// No need to skip stop words here, since we're doing a normal 
			// sequential search anyway
			if(!in_array($term_def["location"], ["all", "title", "tags"]))
				continue; // Skip terms we shouldn't search the page body for
			
			// Loop over the pageindex and search the titles / tags
			reset($pageindex); // Reset array/object pointer
			foreach($pageindex as $pagename => $pagedata) {
				// Setup a variable to hold the current page's id
				$pageid = null; // Cache the page id
				
				$lit_title = self::$literator->transliterate($pagename);
				$lit_tags = isset($pagedata->tags) ? self::$literator->transliterate(implode(" ", $pagedata->tags)) : null;
				$pageid = null; // populated on-demand
				
				// Make sure that the title & tags don't contain a term we should exclude
				$skip = false;
				foreach($query_stas["exclude"] as $excl_term) {
					if(mb_strpos($lit_title, $excl_term) !== false) {
						$skip = true;
						if($pageid === null) $pageid = ids::getid($pagename);
						// Delete it from the candidate matches (it might be present in the tags / title but not the body)
						if(isset($matching_pages[$pageid]))
							unset($matching_pages[$pageid]);
						break;
					}
				}
				if($skip) continue;
				
				// Consider matches in the page title
				if(in_array($term_def["location"], ["all", "title"])) {
					// FUTURE: We may be able to optimise this further by using preg_match_all + preg_quote instead of mb_stripos_all. Experimentation / benchmarking is required to figure out which one is faster
					$title_matches = mb_stripos_all($lit_title, $term_def["term"]);
					$title_matches_count = $title_matches !== false ? count($title_matches) : 0;
					if($title_matches_count > 0) {
						if($pageid === null) $pageid = ids::getid($pagename);
						// We found the qterm in the title
						if(!isset($matching_pages[$pageid]))
						$matching_pages[$pageid] = $match_template; // Assign by copy
						
						$matching_pages[$pageid]["rank_title"] += $title_matches_count * $term_def["weight"] * $settings->search_title_matches_weighting;
					}
				}
				
				// If this page doesn't have any tags, skip it
				if($lit_tags == null)
					continue;
				
				if(!in_array($term_def["location"], ["all", "tags"]))
					continue; // If we shouldn't search the tags, no point in continuing
				
				// Consider matches in the page's tags
				$tag_matches = isset($pagedata->tags) ? mb_stripos_all($lit_tags, $term_def["term"]) : false;
				$tag_matches_count = $tag_matches !== false ? count($tag_matches) : 0;
				
				if($tag_matches_count > 0) {// And we found the qterm in the tags
					if($pageid === null) $pageid = ids::getid($pagename);
					
					if(!isset($matching_pages[$pageid]))
						$matching_pages[$pageid] = $match_template; // Assign by copy
					
					$matching_pages[$pageid]["rank_tags"] += $tag_matches_count * $term_def["weight"] * $settings->search_tags_matches_weighting;
				}
			}
		}
		
		// TODO: Implement the rest of STAS here
		
		reset($matching_pages);
		foreach($matching_pages as $pageid => &$pagedata) {
			$pagedata["pagename"] = ids::getpagename($pageid);
			$pagedata["rank"] = 0;
			
			$pageOffsets = [];
			
			// Loop over each search term found on this page
			reset($pagedata["nterms"]);
			foreach($pagedata["nterms"] as $pterm => $frequency) {
				// Add the number of occurrences of this search term to the ranking
				// Multiply it by the length of the word
				$pagedata["rank"] += $frequency * strlen($pterm);
			}
			
			// Consider matches in the title / tags
			$pagedata["rank"] += $pagedata["rank_title"] + $pagedata["rank_tags"];
			
			// TODO: Consider implementing kernel density estimation here.
			// https://en.wikipedia.org/wiki/Kernel_density_estimation
			// We want it to have more of an effect the more words that are present in the query. Maybe a logarithmic function would be worth investigating here?
			
			// TODO: Remove items if the computed rank is below a threshold
		}
		unset($pagedata); // Ref https://bugs.php.net/bug.php?id=70387
		
		uasort($matching_pages, function($a, $b) {
			if($a["rank"] == $b["rank"]) return 0;
			return ($a["rank"] < $b["rank"]) ? +1 : -1;
		});
		
		return $matching_pages;
	}
	
	/**
	 * Extracts a context string (in HTML) given a search query that could be displayed
	 * in a list of search results.
	 * @param	string	$pagename	The name of the paget that this source belongs to. Used when consulting the inverted index.
	 * @param	string	$query_parsed	The *parsed* search query to generate the context for (basically the output of search::stas_parse()).
	 * @param	string	$source		The page source to extract the context from.
	 * @return	string				The generated context string.
	 */
	public static function extract_context($pagename, $query_parsed, $source)
	{
		global $settings;
		
		$pageid = ids::getid($pagename);
		$nterms = $query_parsed["terms"];
		
		// Query the inverted index for offsets
		$matches = [];
		foreach($nterms as $nterm) {
			// Skip if the page isn't found in the inverted index for this word
			if(!self::$invindex->has("{$nterm["term"]}|$pageid"))
				continue;
			
			$nterm_offsets = self::$invindex->get("{$nterm["term"]}|$pageid")->offsets;
			
			foreach($nterm_offsets as $next_offset)
				$matches[] = [ $nterm["term"], $next_offset ];
		}
		
		// Sort the matches by offset
		usort($matches, function($a, $b) {
			if($a[1] == $b[1]) return 0;
			return ($a[1] > $b[1]) ? +1 : -1;
		});
		
		$sourceLength = mb_strlen($source);
		
		$contexts = [];
		
		$matches_count = count($matches);
		$total_context_length = 0;
		for($i = 0; $i < $matches_count; $i++) {
			$next_context = [
				"from" => max(0, $matches[$i][1] - $settings->search_characters_context),
				"to" => min($sourceLength, $matches[$i][1] + mb_strlen($matches[$i][0]) + $settings->search_characters_context)
			];
			
			if(end($contexts) !== false && end($contexts)["to"] > $next_context["from"]) {
				// This next context overlaps with the previous one
				// Extend the last one instead of adding a new one
				
				// The array pointer is pointing at the last element now because we called end() above
				
				// Update the total context length counter appropriately
				$total_context_length += $next_context["to"] - $contexts[key($contexts)]["to"];
				$contexts[key($contexts)]["to"] = $next_context["to"];
			}
			else { // No overlap here! Business as usual.
				$contexts[] = $next_context;
				// Update the total context length counter as normal
				$total_context_length += $next_context["to"] - $next_context["from"];
			}
			
			
			end($contexts);
			$last_context = &$contexts[key($contexts)];
			if($total_context_length > $settings->search_characters_context_total) {
				// We've reached the limit on the number of characters this context should contain. Trim off the context to fit and break out
				$last_context["to"] -= $total_context_length - $settings->search_characters_context_total;
				break;
			}
		}
		
		$contexts_text = [];
		foreach($contexts as $context) {
			$contexts_text[] = substr($source, $context["from"], $context["to"] - $context["from"]);
		}
		
		// BUG: Make sure that a snippet is centred on the word in question if we have to cut it short
		
		$result = implode(" … ", $contexts_text);
		end($contexts); // If there's at least one item in the list and were not at the very end of the page, add an extra ellipsis
		if(isset($contexts[0]) && $contexts[key($contexts)]["to"] < $sourceLength) $result .= "… ";
		// Prepend an ellipsis if the context doesn't start at the beginning of a page
		if(isset($contexts[0]) && $contexts[0]["from"] > 0) $result = " …$result";
		
		return $result;
	}
	
	/**
	 * Highlights the keywords of a context string.
	 * @param	array	$query_parsed	The *parsed* query to use when highlighting (the output of search::stas_parse())
	 * @param	string	$context	The context string to highlight.
	 * @return	string				The highlighted (HTML) string.
	 */
	public static function highlight_context($query_parsed, $context)
	{
		$qterms = $query_parsed["terms"];
		
		foreach($qterms as $qterm) {
			// Stop words are marked by STAS
			if($qterm["weight"] == -1)
				continue;
			
			// From http://stackoverflow.com/a/2483859/1460422
			$context = preg_replace("/" . preg_replace('/\\//u', "\/", preg_quote($qterm["term"])) . "/iu", "<strong class='search-term-highlight'>$0</strong>", $context);
		}
		
		return $context;
	}
}
// Run the init function
search::init();




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Library: Storage box",
	"version" => "0.13.1",
	"author" => "Starbeamrainbowlabs",
	"description" => "A library module that provides a fast cached key-value store backed by SQLite. Used by the search engine.",
	"id" => "lib-storage-box",
	"code" => function() {
		
	}
]);

/*
███████ ████████  ██████  ██████   █████   ██████  ███████ ██████   ██████  ██   ██
██         ██    ██    ██ ██   ██ ██   ██ ██       ██      ██   ██ ██    ██  ██ ██
███████    ██    ██    ██ ██████  ███████ ██   ███ █████   ██████  ██    ██   ███
     ██    ██    ██    ██ ██   ██ ██   ██ ██    ██ ██      ██   ██ ██    ██  ██ ██
███████    ██     ██████  ██   ██ ██   ██  ██████  ███████ ██████   ██████  ██   ██
*/

/**
 * Represents a key-value data store.
 * 
 */
class StorageBox {
	const MODE_JSON = 0;
	const MODE_ARR_SIMPLE = 1;
	
	/**
	 * The SQLite database connection.
	 * @var \PDO
	 */
	private $db;
	
	/**
	 * A cache of values.
	 * @var object[]
	 */
	private $cache = [];
	
	/**
	 * A cache of prepared SQL statements.
	 * @var \PDOStatement[]
	 */
	private $query_cache = [];
	
	/**
	 * Initialises a new store connection.
	 * @param	string	$filename	The filename that the store is located in.
	 */
	function __construct(string $filename) {
		$firstrun = !file_exists($filename);
		if(!file_exists($filename)) touch($filename);
		$this->db = new \PDO("sqlite:$filename"); // HACK: This might not work on some systems, because it depends on the current working directory
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if($firstrun) {
			$this->query("CREATE TABLE IF NOT EXISTS store (key TEXT UNIQUE NOT NULL, value TEXT)");
		}
	}
	/**
	 * Makes a query against the database.
	 * @param	string	$sql		The (potentially parametised) query to make.
	 * @param	array	$variables	Optional. The variables to substitute into the SQL query.
	 * @return	\PDOStatement		The result of the query, as a PDOStatement.
	 */
	private function query(string $sql, array $variables = []) {
		// Add to the query cache if it doesn't exist
		if(!isset($this->query_cache[$sql]))
			$this->query_cache[$sql] = $this->db->prepare($sql);
		$this->query_cache[$sql]->execute($variables);
		return $this->query_cache[$sql]; // fetchColumn(), fetchAll(), etc. are defined on the statement, not the return value of execute()
	}
	
	/**
	 * Determines if the given key exists in the store or not.
	 * @param	string	$key	The key to test.
	 * @return	bool	Whether the key exists in the store or not.
	 */
	public function has(string $key) : bool {
		if(isset($this->cache[$key]))
			return true;
		return $this->query(
			"SELECT COUNT(key) FROM store WHERE key = :key;",
			[ "key" => $key ]
		)->fetchColumn() > 0;
	}
	
	/**
	 * Returns an iterable that returns all the keys that do not contain the given string.
	 * @param	string			$exclude		The string to search for when excluding keys.
	 * @return	PDOStatement	The iterable. Use a foreach loop on it.
	 */
	public function get_keys(string $exclude) : \PDOStatement {
		return $this->query(
			"SELECT key FROM store WHERE key NOT LIKE :containing;",
			[ "containing" => "%$exclude%" ]
		);
	}
	
	/**
	 * Gets a value from the store.
	 * @param	string	$key	The key value is stored under.
	 * @return	mixed	The stored value.
	 */
	public function get(string $key) {
		// If it's not in the cache, insert it
		if(!isset($this->cache[$key])) {
			$this->cache[$key] = [ "modified" => false, "value" => json_decode($this->query(
				"SELECT value FROM store WHERE key = :key;",
				[ "key" => $key ]
			)->fetchColumn()) ];
		}
		return $this->cache[$key]["value"];
	}
	public function get_arr_simple(string $key, string $delimiter = "|") {
		// If it's not in the cache, insert it
		if(!isset($this->cache[$key])) {
			$this->cache[$key] = [
				"modified" => false,
				"value" => explode($delimiter, $this->query(
					"SELECT value FROM store WHERE key = :key;",
					[ "key" => $key ]
				)->fetchColumn())
			];
		}
		return $this->cache[$key]["value"];
	}
	
	/**
	 * Sets a value in the data store.
	 * Note that this does NOT save changes to disk until you close the connection!
	 * @param	string	$key	The key to set the value of.
	 * @param	mixed	$value	The value to store.
	 */
	public function set(string $key, $value) : void {
		if(!isset($this->cache[$key])) $this->cache[$key] = [];
		$this->cache[$key]["value"] = $value;
		$this->cache[$key]["modified"] = true;
		$this->cache[$key]["mode"] = self::MODE_JSON;
	}
	public function set_arr_simple(string $key, $value, string $delimiter = "|") : void {
		if(!isset($this->cache[$key])) $this->cache[$key] = [];
		$this->cache[$key]["value"] = $value;
		$this->cache[$key]["modified"] = true;
		$this->cache[$key]["delimiter"] = $delimiter;
		$this->cache[$key]["mode"] = self::MODE_ARR_SIMPLE;
	}
	
	/**
	 * Deletes an item from the data store.
	 * @param	string	$key	The key of the item to delete.
	 * @return	bool	Whether it was really deleted or not. Note that if it doesn't exist, then it can't be deleted.
	 */
	public function delete(string $key) : bool {
		// Remove it from the cache
		if(isset($this->cache[$key]))
			unset($this->cache[$key]);
		// Remove it from disk
		// TODO: Queue this action for the transaction later
		return $this->query(
			"DELETE FROM store WHERE key = :key;",
			[ "key" => $key ]
		)->rowCount() > 0;
	}
	
	/**
	 * Empties the store.
	 */
	public function clear() : void {
		// Empty the cache;
		$this->cache = [];
		// Empty the disk
		$this->query("DELETE FROM store;");
	}
	
	/**
	 * Syncs changes to disk and closes the PDO connection.
	 */
	public function close() : void {
		$this->db->beginTransaction();
		foreach($this->cache as $key => $value_data) {
			// If it wasn't modified, there's no point in saving it, is there?
			if(!$value_data["modified"])
				continue;
			
			$this->query(
				"INSERT OR REPLACE INTO store(key, value) VALUES(:key, :value)",
				[
					"key" => $key,
					"value" => $value_data["mode"] == self::MODE_ARR_SIMPLE ?
						implode($value_data["delimiter"], $value_data["value"]) :
						json_encode($value_data["value"])
				]
			);
		}
		$this->db->commit();
		$this->db = null;
	}
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Credits",
	"version" => "0.8.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds the credits page. You *must* have this module :D",
	"id" => "page-credits",
	"code" => function() {
		/**
		 * @api {get} ?action=credits Get the credits page
		 * @apiName Credits
		 * @apiGroup Utility
		 * @apiPermission Anonymous
		 */
		
		/*
		 *  ██████ ██████  ███████ ██████  ██ ████████ ███████ 
		 * ██      ██   ██ ██      ██   ██ ██    ██    ██      
		 * ██      ██████  █████   ██   ██ ██    ██    ███████ 
		 * ██      ██   ██ ██      ██   ██ ██    ██         ██ 
		 *  ██████ ██   ██ ███████ ██████  ██    ██    ███████ 
		 */
		add_action("credits", function() {
			global $settings, $version, $env, $pageindex, $modules;
			
			$credits = [
				"Code" => [
					"author" => "Starbeamrainbowlabs",
					"author_url" => "https://starbeamrainbowlabs.com/",
					"thing_url" => "https://github.com/sbrl/Pepperminty-Wiki/",
					"icon" => "https://avatars0.githubusercontent.com/u/9929737?v=3&s=24"
				],
				"Mime type to file extension mapper" => [
					"author" => "Chaos",
					"author_url" => "https://stackoverflow.com/users/47529/chaos",
					"thing_url" => "https://stackoverflow.com/a/1147952/1460422",
					"icon" => "https://www.gravatar.com/avatar/aaee40db39ad6b164cfb89cb6ad4d176?s=328&d=identicon&s=24"
				],
				"Parsedown" => [
					"author" => "Emanuil Rusev and others",
					"author_url" => "https://github.com/erusev/",
					"thing_url" => "https://github.com/erusev/parsedown/",
					"icon" => "https://avatars1.githubusercontent.com/u/184170?v=3&s=24"
				],
				"CSS Minification Code" => [
					"author" => "Jean",
					"author_url" => "http://www.catswhocode.com/",
					"thing_url" => "http://www.catswhocode.com/blog/3-ways-to-compress-css-files-using-php"
				],
				"Slightly modified version of Slimdown" => [
					"author" => "Johnny Broadway",
					"author_url" => "https://github.com/jbroadway",
					"thing_url" => "https://gist.github.com/jbroadway/2836900",
					"icon" => "https://avatars2.githubusercontent.com/u/87886?v=3&s=24"
				],
				"Insert tab characters into textareas" => [
					"author" => "Unknown",
					"author_url" => "http://stackoverflow.com/q/6140632/1460422",
					"thing_url" => "https://jsfiddle.net/2wAzx/13/",
				],
				"Default Favicon" => [
					"author" => "bluefrog23",
					"author_url" => "https://openclipart.org/user-detail/bluefrog23/",
					"thing_url" => "https://openclipart.org/detail/19571/peppermint-candy-by-bluefrog23"
				],
				"Bug Reports" => [
					"author" => "nibreh",
					"author_url" => "https://github.com/nibreh/",
					"thing_url" => "",
					"icon" => "https://avatars2.githubusercontent.com/u/7314006?v=3&s=24"
				],
				"More Bug Reports (default credentials + downloader; via Gitter)" => [
					"author" => "Tyler Spivey",
					"author_url" => "https://github.com/tspivey/",
					"thing_url" => "",
					"icon" => "https://avatars2.githubusercontent.com/u/709407?v=4&s=24"
				],
				"PR #135: Fix repeated page names on sidebar" => [
					"author" => "ikisler",
					"author_url" => "https://github.com/ikisler",
					"thing_url" => "https://github.com/sbrl/Pepperminty-Wiki/pull/135",
					"icon" => "https://avatars3.githubusercontent.com/u/12506147?v=3&s=24"
				],
				"PR #136: Fix issue where bottom nav is cut off" => [
					"author" => "ikisler",
					"author_url" => "https://github.com/ikisler",
					"thing_url" => "https://github.com/sbrl/Pepperminty-Wiki/pull/136",
					"icon" => "https://avatars3.githubusercontent.com/u/12506147?v=3&s=24"
				],
				"PR #140: Edit Previewing" => [
					"author" => "ikisler",
					"author_url" => "https://github.com/ikisler",
					"thing_url" => "https://github.com/sbrl/Pepperminty-Wiki/pull/140",
					"icon" => "https://avatars3.githubusercontent.com/u/12506147?v=3&s=24"
				],
				"Issue #153: Authenticated DOS attack (XXD billion laughs attack)" => [
					"author" => "ProDigySML",
					"author_url" => "https://github.com/ProDigySML",
					"thing_url" => "https://github.com/sbrl/Pepperminty-Wiki/issues/152",
					"icon" => "https://avatars3.githubusercontent.com/u/16996819?s=24&v=4"
				],
				"Many miscellaneus bug reports and PRs to fix things" => [
					"author" => "Sean Feeney",
					"author_url" => "https://github.com/SeanFromIT",
					"thing_url" => "https://gitter.im/Pepperminty-Wiki/Lobby?at=5d786927460a6f5a1600f1c1",
					"icon" => "https://avatars3.githubusercontent.com/u/10387753?s=24&v=4"
				],
				"Inverted logic fix in the peppermint.json access detector (#179)" => [
					"author" => "Kevin Otte",
					"author_url" => "https://www.nivex.net/",
					"thing_url" => "https://github.com/sbrl/Pepperminty-Wiki/pull/179",
					"icon" => "https://avatars3.githubusercontent.com/u/3833404?s=24&v=4"
				],
				"IIS web server documentation" => [
					"author" => "Nathan Nance",
					"author_url" => "https://github.com/npnance",
					"thing_url" => "https://github.com/sbrl/Pepperminty-Wiki/pull/229",
					"icon" => "https://avatars.githubusercontent.com/u/975340?s=24"
				]
			];
			
			//// Credits html renderer ////
			$credits_html = "<ul>\n";
			foreach($credits as $thing => $author_details)
			{
				$credits_html .= "	<li>";
				$credits_html .= "<a href='" . htmlentities($author_details["thing_url"]) . "'>".htmlentities($thing)."</a> by ";
				if(isset($author_details["icon"]))
				$credits_html .= "<img class='logo small' style='vertical-align: middle;' src='" . htmlentities($author_details["icon"]) . "' /> ";
				$credits_html .= "<a href='" . htmlentities($author_details["author_url"]) . "'>" . $author_details["author"] . "</a>";
				$credits_html .= "</li>\n";
			}
			$credits_html .= "</ul>";
			///////////////////////////////
			
			//// Module html renderer ////
			$modules_html = "<table>
	<tr>
		<th>Name</th>
		<th>Version</th>
		<th>Author</th>
		<th>Description</th>
	</tr>";
			foreach($modules as $module)
			{
				$modules_html .= "	<tr>
		<td title='" . $module["id"] . "'>" . $module["name"] . "</td>
		<td>" . $module["version"] . "</td>
		<td>" . $module["author"] . "</td>
		<td>" . $module["description"] . "</td>
	</tr>\n";
			}
			$modules_html .= "</table>";
			//////////////////////////////
			
			$title = "Credits - $settings->sitename";
			$content = "<h1>$settings->sitename credits</h1>
	<p>$settings->sitename is powered by Pepperminty Wiki - an entire wiki packed inside a single file, which was built by <a href='//starbeamrainbowlabs.com'>Starbeamrainbowlabs</a>, and can be found <a href='//github.com/sbrl/Pepperminty-Wiki/'>on GitHub</a> (contributors will also be listed here in the future). Pepperminty Wiki is licensed under the <a target='_blank' href='https://www.mozilla.org/en-US/MPL/2.0/'>Mozilla Public License 2.0</a> (<a target='_blank' href='https://tldrlegal.com/license/mozilla-public-license-2.0-(mpl-2)'>simple version</a>).</p>
	<h2>Main Credits</h2>
	$credits_html
	<h2>Site status</h2>
	<table>
		<tr><th>Site name:</th><td>$settings->sitename (<a href='?action=update'>{$settings->admindisplaychar}Update</a>, <a href='?action=configure'>{$settings->admindisplaychar} &#x1f527; Edit master settings</a>, <a href='?action=user-table'>{$settings->admindisplaychar} &#x1f465; Edit user table</a>, <a href='?action=export'>&#x1f3db; Export as zip - Check for permission first</a>)</td></tr>
		<tr><th>Pepperminty Wiki version:</th><td>$version</td></tr>
		<tr><th>Number of pages:</th><td>" . count(get_object_vars($pageindex)) . "</td></tr>
		<tr><th>Number of modules:</th><td>" . count($modules) . "</td></tr>\n";
			if(module_exists("page-sitemap")) {
				$content .= "<tr><th>Sitemap:</th><td><a href='?action=sitemap'>View</a>";
				if($env->is_admin)
					$content .= " | Don't forget to add <code>Sitemap: http://example.com/path/to/index.php?action=sitemap</code> to your <code>robots.txt</code>";
				$content .= "</td></tr>";
			}
			$content .= "\t</table>
		<h2>Installed Modules</h2>
	$modules_html";
			exit(page_renderer::render_main($title, $content));
		});
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Debug Information",
	"version" => "0.4",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a debug action for administrator use only that collects a load of useful information to make reporting bugs easier.",
	"id" => "page-debug-info",
	"code" => function() {
		global $settings, $env;
		/**
		 * @api {get} ?action=debug	Get a debug dump
		 * @apiName Debug
		 * @apiGroup Utility
		 * @apiPermission Moderator
		 *
		 * @apiUse UserNotModeratorError
		 *
		 * @apiParam {string}	secret	Optional. If you're not logged in as a moderator or better, then specifying the secret works as a substitute.
		 */
		
		/*
		 * ██████  ███████ ██████  ██    ██  ██████  
		 * ██   ██ ██      ██   ██ ██    ██ ██       
		 * ██   ██ █████   ██████  ██    ██ ██   ███ 
		 * ██   ██ ██      ██   ██ ██    ██ ██    ██ 
		 * ██████  ███████ ██████   ██████   ██████
		*/
		add_action("debug", function() {
			global $settings, $env, $paths, $version, $commit;
			header("content-type: text/plain");
			
			if(!$env->is_admin && (!isset($_GET["secret"]) && $_GET["secret"] !== $settings->secret)) {
				exit("You must be logged in as an moderator in order to generate debugging information.");
			}
			
			$title = "$settings->sitename debug report";
			echo("$title\n");
			echo(str_repeat("=", strlen($title)) . "\n");
			echo("Powered by Pepperminty Wiki version $version+" . substr($commit, 0, 7) . ".\n");
			echo("This report may contain personal information.\n\n");
			echo("Environment: ");
			echo(debug_mask_secrets(var_export($env, true)));
			echo("\nPaths: ");
			echo(var_export($paths, true));
			echo("\nServer information:\n");
			echo("uname -a: " . php_uname() . "\n");
			echo("Path: " . getenv("PATH") . "\n");
			echo("Temporary directory: " . sys_get_temp_dir() . "\n");
			echo("Server: " . $_SERVER["SERVER_SOFTWARE"] . "\n");
			echo("Web root: " . $_SERVER["DOCUMENT_ROOT"] . "\n");
			echo("Web server user: " . exec("whoami") . "\n");
			echo("PHP version: " . phpversion() . "\n");
			echo("index.php location: " . __FILE__ . "\n");
			echo("index.php file permissions: " . substr(sprintf('%o', fileperms("./index.php")), -4) . "\n");
			echo("Current folder permissions: " . substr(sprintf('%o', fileperms(".")), -4) . "\n");
			echo("Storage directory permissions: " . substr(sprintf('%o', fileperms($env->storage_prefix)), -4) . "\n");
			echo("Loaded extensions: " . implode(", ", get_loaded_extensions()) . "\n");
			echo("Settings:\n-----\n");
			echo(debug_mask_secrets(var_export($settings, true)));
			echo("\n-----\n");
			exit();
		});
		
		if($env->is_admin) {
			add_help_section("950-debug-information", "Gathering debug information", "<p>As a moderator, $settings->sitename gives you the ability to generate a report on $settings->sitename's installation of Pepperminty Wiki for debugging purposes.</p>
			<p>To generate such a report, visit the <code>debug</code> action or <a href='?action=debug'>click here</a>.</p>");
		}
	}
]);

/**
 * Masks secrets in debug output.
 * @param  string $text The text to mask.
 * @return string       The masked text.
 */
function debug_mask_secrets($text) {
	$lines = explode("\n", $text);
	foreach ($lines as &$line) {
		if(preg_match("/'(secret|sitesecret|email(?:Address)?|password)'/i", $line)) $line = "********* secret *********"; 
	}
	
	return implode("\n", $lines);
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Page deleter",
	"version" => "0.10.3",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds an action to allow administrators to delete pages.",
	"id" => "page-delete",
	"code" => function() {
		global $settings;
		/**
		 * @api {post} ?action=delete Delete a page
		 * @apiDescription	Delete a page and all its associated data.
		 * @apiName DeletePage
		 * @apiGroup Page
		 * @apiPermission Moderator
		 * 
		 * @apiParam {string}	page		The name of the page to delete.
		 * @apiParam {string}	delete		Set to 'yes' to actually delete the page.
		 *
		 * @apiUse	UserNotModeratorError
		 * @apiError	PageNonExistentError	The specified page doesn't exist
		 */
		
		/*
		 * ██████  ███████ ██      ███████ ████████ ███████ 
		 * ██   ██ ██      ██      ██         ██    ██      
		 * ██   ██ █████   ██      █████      ██    █████   
		 * ██   ██ ██      ██      ██         ██    ██      
		 * ██████  ███████ ███████ ███████    ██    ███████ 
		 */
		add_action("delete", function() {
			global $pageindex, $settings, $env, $paths, $modules;
			if(!$settings->editing)
			{
				exit(page_renderer::render_main("Error: Editing disabled - Deleting $env->page", "<p>You tried to delete $env->page_safe, but editing is disabled on this wiki.</p>
				<p>If you wish to delete this page, please re-enable editing on this wiki first.</p>
				<p><a href='index.php?page=".rawurlencode($env->page)."'>Go back to $env->page_safe</a>.</p>
				<p>Nothing has been changed.</p>"));
			}
			if(!$env->is_admin)
			{
				exit(page_renderer::render_main("Error: Insufficient permissions - Deleting $env->page", "<p>You tried to delete $env->page_safe, but you as aren't a moderator you don't have permission to do that.</p>
				<p>You could try <a href='index.php?action=login&returnto=".rawurlencode("?action=delete&page=".rawurlencode($env->page))."'>logging in</a> as an admin, or asking one of $settings->sitename's friendly moderators (find their names at the bottom of every page!) to delete it for you.</p>"));
			}
			if(!isset($pageindex->{$env->page}))
			{
				exit(page_renderer::render_main("Error: Non-existent page - Deleting $env->page", "<p>You tried to delete $env->page_safe, but that page doesn't appear to exist in the first place. <a href='?'>Go back</a> to the $settings->defaultpage.</p>"));
			}
			
			if(!isset($_GET["delete"]) or $_GET["delete"] !== "yes")
			{
				exit(page_renderer::render_main("Deleting $env->page", "<p>You are about to <strong>delete</strong> <em>$env->page_safe</em>" . (module_exists("feature-history")?" and all its revisions":"") . (module_exists("feature-comments")?" and all its comments":"") . ". You can't undo this!</p>
				<p><a href='index.php?action=delete&amp;page=".rawurlencode($env->page)."&amp;delete=yes'>Click here to delete $env->page_safe.</a></p>
				<p><a href='index.php?action=view&amp;page=".rawurlencode($env->page)."'>Click here to go back and view the page.</a>"));
			}
			$page = $env->page;
			// Delete the associated file if it exists
			if(!empty($pageindex->$page->uploadedfile))
			{
				unlink($env->storage_prefix . $pageindex->$page->uploadedfilepath);
			}
			
			// While we're at it, we should delete all the revisions too
			foreach($pageindex->{$env->page}->history as $revisionData)
			{
				unlink($env->storage_prefix . $revisionData->filename);
			}
			
			// If the commenting module is installed and the page has comments,
			// delete those too
			if(module_exists("feature-comments") and
				file_exists(get_comment_filename($env->page)))
			{
				unlink(get_comment_filename($env->page));
			}
			
			// Delete the page from the page index
			unset($pageindex->$page);
			
			// Save the new page index
			save_pageindex();
			
			
			// Delete the page from the search index, if that module is installed
			if(module_exists("feature-search")) {
				$pageid = ids::getid($env->page);
				search::invindex_load($paths->searchindex);
				search::invindex_delete($pageid);
				search::invindex_close();
			}
			
			// Remove the page's name from the id index
			ids::deletepagename($env->page);
			
			// Delete the page from the disk
			unlink("$env->storage_prefix$env->page.md");
			
			// Add a recent change announcing the deletion if the recent changes
			// module is installed
			if(module_exists("feature-recent-changes"))
			{
				add_recent_change([
					"type" => "deletion",
					"timestamp" => time(),
					"page" => $env->page,
					"user" => $env->user,
				]);
			}
			
			exit(page_renderer::render_main("Deleting $env->page - $settings->sitename", "<p>$env->page_safe has been deleted. <a href='index.php'>Go back to the main page</a>.</p>"));
		});
		
		// Register a help section
		add_help_section("60-delete", "Deleting Pages", "<p>If you are logged in as an adminitrator, then you have the power to delete pages. To do this, click &quot;Delete&quot; in the &quot;More...&quot; menu when browsing the pge you wish to delete. When you are sure that you want to delete the page, click the given link.</p>
		<p><strong>Warning: Once a page has been deleted, you can't bring it back! You will need to recover it from your backup, if you have one (which you really should).</strong></p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Page editor",
	"version" => "0.19",
	"author" => "Starbeamrainbowlabs",
	"description" => "Allows you to edit pages by adding the edit and save actions. You should probably include this one.",
	"id" => "page-edit",
	"extra_data" => [
		"diff.min.js" => "https://cdnjs.cloudflare.com/ajax/libs/jsdiff/2.2.2/diff.min.js",
		"awesomplete.min.js" => "https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.js",
		"awesomplete.min.css" => "https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.css"
	],
	
	"code" => function() {
		global $settings, $env;
		
		
		/**
		 * @api {get} ?action=edit&page={pageName}[&newpage=yes]	Get an editing page
		 * @apiDescription	Gets an editing page for a given page. If you don't have permission to edit the page in question, a view source pagee is returned instead.
		 * @apiName			EditPage
		 * @apiGroup		Editing
		 * @apiPermission	Anonymous
		 * 
		 * @apiUse PageParameter
		 * @apiParam	{string}	newpage		Optional. Set to 'yes' if a new page is being created. Only affects a few bits of text here and there, and the HTTP response code recieved on success from the `save` action.
		 * @apiParam	{string}	unknownpagename	Optional. Set to 'yes' if the name of the page to be created is currently unknown. If set, a page name box will be shown too.
		 */
		
		/*
		 *           _ _ _
		 *   ___  __| (_) |_
		 *  / _ \/ _` | | __|
		 * |  __/ (_| | | |_
		 *  \___|\__,_|_|\__|
		 *             %edit%
		 */
		add_action("edit", function() {
			global $pageindex, $settings, $env, $paths;
			
			$unknownpagename = isset($_GET["unknownpagename"]) && strlen(trim($_GET["unknownpagename"])) > 0;
			$filename = "$env->storage_prefix$env->page.md";
			$creatingpage = !isset($pageindex->{$env->page});
			if((isset($_GET["newpage"]) and $_GET["newpage"] == "true") or $creatingpage)
				$title = "Creating $env->page";
			else if(isset($_POST['preview-edit']) && isset($_POST['content']))
				$title = "Preview Edits for $env->page";
			else if($unknownpagename)
				$title = "Creating new page";
			else
				$title = "Editing $env->page";
			
			$pagetext = ""; $page_tags = "";
			if(isset($pageindex->{$env->page}) && !$unknownpagename)
				$pagetext = file_get_contents($filename);
			if(!$unknownpagename)
				$page_tags = htmlentities(implode(", ", (!empty($pageindex->{$env->page}->tags)) ? $pageindex->{$env->page}->tags : []));
			
			$isOtherUsersPage = $settings->user_page_prefix == mb_substr($env->page, 0, mb_strlen($settings->user_page_prefix)) and // The current page is a user page of some sort
				(
					!$env->is_logged_in or // the user isn't logged in.....
					extract_user_from_userpage($env->page) !== $env->user // ...or it's not under this user's own name
				);
			
			if((!$env->is_logged_in and !$settings->anonedits) or // if we aren't logged in and anonymous edits are disabled
				!$settings->editing or // or editing is disabled
				(
					isset($pageindex->{$env->page}) and // or if the page exists
					isset($pageindex->{$env->page}->protect) and // the protect property exists
					$pageindex->{$env->page}->protect and // the protect property is true
					!$env->is_admin // the user isn't an admin
				) or
				$isOtherUsersPage // this page actually belongs to another user
			)
			{
				if(!$creatingpage) {
					// The page already exists - let the user view the page source
					$sourceViewContent = "<p>$settings->sitename does not allow anonymous users to make edits. You can view the source of $env->page_safe below, but you can't edit it. You could, however, try <a href='index.php?action=login&returnto=" . rawurlencode($_SERVER["REQUEST_URI"]) . "'>logging in</a>.</p>\n";
					
					if($env->is_logged_in)
						$sourceViewContent = "<p>$env->page_safe is protected, and you aren't an administrator or moderator. You can view the source of $env->page_safe below, but you can't edit it.</p>\n";
					
					if(!$settings->editing)
						$sourceViewContent = "<p>$settings->sitename currently has editing disabled, so you can't make changes to this page at this time. Please contact ".htmlentities($settings->admindetails_name).", $settings->sitename's administrator for more information - their contact details can be found at the bottom of this page. Even so, you can still view the source of this page. It's disabled below:</p>";
					
					if($isOtherUsersPage)
						$sourceViewContent = "<p>$env->page_safe is a special user page which acutally belongs to " . htmlentities(extract_user_from_userpage($env->page)) . ", another user on $settings->sitename. Because of this, you are not allowed to edit it (though you can always edit your own page and any pages under it if you're logged in). You can, however, vieww it's source below.</p>";
					
					// Append a view of the page's source
					$sourceViewContent .= "<textarea name='content' readonly>".htmlentities($pagetext)."</textarea>";
					
					exit(page_renderer::render_main("Viewing source for $env->page", $sourceViewContent));
				}
				else {
					$errorMessage = "<p>The page <code>$env->page_safe</code> does not exist, but you do not have permission to create it.</p><p>If you haven't already, perhaps you should try <a href='index.php?action=login&amp;returnto=" . rawurlencode($_SERVER["REQUEST_URI"]) . "'>logging in</a>.</p>\n";
					
					if($isOtherUsersPage) {
						$errorMessage = "<p>The page <code>$env->page_safe</code> doesn't exist, but you can't create it because it's a page belonging to another user.</p>\n";
						if(!$env->is_logged_in)
							$errorMessage .= "<p>You could try <a href='?action=login&amp;returnto=" . rawurlencode($_SERVER["REQUEST_URI"]) . "'>logging in</a>.</p>\n";
					}
						
					http_response_code(404);
					exit(page_renderer::render_main("404 - $env->page", $errorMessage));
				}
			}
			
			$content = "<h1>".htmlentities($title)."</h1>\n";
			
			if(!$env->is_logged_in and $settings->anonedits) {
				$content .= "<p><strong>Warning: You are not logged in! Your IP address <em>may</em> be recorded.</strong></p>";
			}
			if(isset($_GET["redirected_from"])) {
				$content .= "<p><em>Redirected from <a href='?page=".rawurlencode($_GET["redirected_from"])."&amp;redirect=no'>".htmlentities($_GET["redirected_from"])."</a></em></p>\n";
			}
			
			// Include preview, if set
			if(isset($_POST['preview-edit']) && isset($_POST['content'])) {
				// Need this for the prev-content-hash to prevent the conflict page from appearing
				$old_pagetext = $pagetext;

				// set the page content to the newly edited content
				$pagetext = $_POST['content'];

				// Set the tags to the new tags, if needed
				if(isset($_POST['tags']))
					$page_tags = $_POST['tags'];

				// Insert the "view" part of the page we're editing
				$content .=  "<p class='preview-message'><strong>This is only a preview, so your edits haven't been saved! Scroll down to continue editing.</strong></p>" . parse_page_source($pagetext);

			}

			$content .= "<button class='smartsave-restore' title=\"Only works if you haven't changed the editor's content already!\">Restore Locally Saved Content</button>
			<form method='post' name='edit-form' action='index.php?action=preview-edit&amp;page=" . rawurlencode($env->page) . "' class='editform'>
					<input type='hidden' name='prev-content-hash' value='" . generate_page_hash(isset($old_pagetext) ? $old_pagetext : $pagetext) . "' />";
			if($unknownpagename)
				$content .= "<div><label for='page'>Page Name:</label>
					<input type='text' id='page' name='page' value='' placeholder='Enter the name of the page here.' title='Enter the name of the page here.' />
					<input type='hidden' name='prevent_save_if_exists' value='yes' />";
			$content .= "<textarea name='content' autofocus tabindex='1'>".htmlentities($pagetext)."</textarea>
					<pre class='fit-text-mirror'></pre>
					<input type='text' id='tags' name='tags' value='" . htmlentities($page_tags, ENT_HTML5 | ENT_QUOTES) . "' placeholder='Enter some tags for the page here. Separate them with commas.' title='Enter some tags for the page here. Separate them with commas.' tabindex='2' />
					<p class='editing-message'>$settings->editing_message</p>
					<input name='preview-edit' class='edit-page-button' type='submit' value='Preview Changes' tabindex='4' />
					<input name='submit-edit' class='edit-page-button' type='submit' value='Save Page' tabindex='3' />
					</form>";
			// Allow tab characters in the page editor
			page_renderer::add_js_snippet("window.addEventListener('load', function(event) {
	// Adapted from https://jsfiddle.net/2wAzx/13/
	document.querySelector(\"[name=content]\").addEventListener(\"keydown\", (event) => {
		if(event.keyCode !== 9) return true;
		var currentValue = event.target.value, startPos = event.target.selectionStart, endPos = event.target.selectionEnd;
		event.target.value = currentValue.substring(0, startPos) + \"\\t\" + currentValue.substring(endPos);
		event.target.selectionStart = event.target.selectionEnd = startPos + 1;
		event.stopPropagation(); event.preventDefault();
		return false;
	});
});");
			
			// Utilise the mirror to automatically resize the textarea to fit it's content
			page_renderer::add_js_snippet('function updateTextSize(textarea, mirror, event) {
	let textareaFontSize = parseFloat(getComputedStyle(textarea).fontSize);
	
	let textareaWidth = textarea.getBoundingClientRect().width;// - parseInt(textarea.style.padding);
	mirror.style.width = `${textareaWidth}px`;
	mirror.innerText = textarea.value;
	textarea.style.height = `${mirror.offsetHeight + (textareaFontSize * 5)}px`;
}
function trackTextSize(textarea) {
	let mirror = textarea.nextElementSibling;
	textarea.addEventListener("input", updateTextSize.bind(null, textarea, mirror));
	updateTextSize(textarea, mirror, null);
}
window.addEventListener("load", function(event) {
	trackTextSize(document.querySelector("textarea[name=content]"));
});
');
			
			// ~
			
			/// ~~~ Smart saving ~~~ ///
			page_renderer::add_js_snippet('window.addEventListener("load", function(event) {
	"use strict";
	// Smart saving
	let getSmartSaveKey = function() { return document.querySelector("main h1").innerHTML.replace("Creating ", "").replace("Editing ", "").trim(); }
	// Saving
	document.querySelector("textarea[name=content]").addEventListener("keyup", function(event) { window.localStorage.setItem(getSmartSaveKey(), event.target.value) });
	// Loading
	var editor = document.querySelector("textarea[name=content]");
	let smartsave_restore = function() {
		editor.value = localStorage.getItem(getSmartSaveKey());
	}
	
	if(editor.value.length === 0) // Don\'t restore if there\'s data in the editor already
		smartsave_restore();
	
	document.querySelector(".smartsave-restore").addEventListener("click", function(event) {
		event.stopPropagation();
		event.preventDefault();
		smartsave_restore();
	});
});');
			// Why would anyone want this disabled?
			if($settings->editing_tags_autocomplete) {
				page_renderer::add_js_link("$paths->extra_data_directory/page-edit/awesomplete.min.js");
				page_renderer::add_js_snippet('window.addEventListener("load", async (event) => {
	// FUTURE: Optionally cache this?
	let response = await fetch("?action=list-tags&format=text");
	if(!response.ok) {
		console.warn(`Warning: Failed to fetch tags list with status code ${response.status} ${response.statusText}`);
		return;
	}
	
	let tags = (await response.text()).split("\n");
	console.log(tags);
	window.input_tags_completer = new Awesomplete(
		document.querySelector("#tags"), {
			list: tags,
			filter: function(text, input) {
				console.log(arguments);
				// Avoid suggesting tags that are already present
				if(input.split(/,\s*/).includes(text.value)) return false;
				return Awesomplete.FILTER_CONTAINS(text, input.match(/[^,]*$/)[0]);
			},
			item: function(text, input) {
				return Awesomplete.ITEM(text, input.match(/[^,]*$/)[0]);
			},
			replace: function(text) {
				var before = this.input.value.match(/^.+,\s*|/)[0];
				this.input.value = before + text + ", ";
			}
		}
	);
});
				');
				$content .= "<link rel=\"stylesheet\" href=\"$paths->extra_data_directory/page-edit/awesomplete.min.css\" />";
			}
			
			exit(page_renderer::render_main("$title - $settings->sitename", $content));
		});
		
		/**
		 * @api {post} ?action=preview-edit&page={pageName}[&newpage=yes]	Get a preview of an edit to a page
		 * @apiDescription	Gets a preview of the current edit state of a given page
		 * @apiName 		PreviewPage
		 * @apiGroup		Editing
		 * @apiPermission	Anonymous
		 * 
		 * @apiUse	PageParameter
		 * @apiParam	{string}	newpage 	Set to 'yes' if a new page is being created.
		 * @apiParam	{string}	preview-edit 	Set to a value to preview an edit of a page.
		 */

		/*
		 *
		 * ██████  ██████  ███████ ██    ██ ██ ███████ ██     ██
		 * ██   ██ ██   ██ ██      ██    ██ ██ ██      ██     ██
		 * ██████  ██████  █████   ██    ██ ██ █████   ██  █  ██
		 * ██      ██   ██ ██       ██  ██  ██ ██      ██ ███ ██
		 * ██      ██   ██ ███████   ████   ██ ███████  ███ ███
		 *
		 * ███████ ██████  ██ ████████ 
		 * ██      ██   ██ ██    ██    
		 * █████   ██   ██ ██    ██    
		 * ██      ██   ██ ██    ██    
		 * ███████ ██████  ██    ██    
		 *
		 */
		add_action("preview-edit", function() {
			global $pageindex, $settings, $env, $actions;

			if(isset($_POST['preview-edit']) && isset($_POST['content'])) {
				// preview changes
				get_object_vars($actions)['edit']();
			}
			else {
				// save page
				get_object_vars($actions)['save']();
			}

			
		});
		
		/**
		 * @api {post} ?action=acquire-edit-key&page={pageName}		Acquire an edit key for a page
		 * @apiDescription	Returns an edit key that can be used to programmatically save an edit to a page. It does _not_ necessarily mean that such an edit will be saved. For example, editing might be disabled, or you might not have permission to save an edit on a particular page.
		 * @apiName 		AcquireEditKey
		 * @apiGroup		Editing
		 * @apiPermission	Anonymous
		 * 
		 * @apiUse		PageParameter
		 * @apiParam	{string}	format	The format to return the edit key in. Possible values: text, json. Default: text.
		 * @apiParam	{string}	prevent_save_if_exists	Optional. If set to 'yes', then if a page exists with the specified page name the save is aborted and an error page returned instead.
		 * @apiParam	{string}	page	The name of the page to save the edit to. Note that in this specific instance *only*, the page name can be specified over GET or POST (POST will override GET if both are present). 
		 */
		
		/*
 		 *  █████   ██████  ██████  ██    ██ ██ ██████  ███████
 		 * ██   ██ ██      ██    ██ ██    ██ ██ ██   ██ ██
 		 * ███████ ██      ██    ██ ██    ██ ██ ██████  █████ █████
 		 * ██   ██ ██      ██ ▄▄ ██ ██    ██ ██ ██   ██ ██
 		 * ██   ██  ██████  ██████   ██████  ██ ██   ██ ███████
 		 *                     ▀▀
 		 * 
 		 * ███████ ██████  ██ ████████
 		 * ██      ██   ██ ██    ██
 		 * █████   ██   ██ ██    ██ █████
 		 * ██      ██   ██ ██    ██
 		 * ███████ ██████  ██    ██
 		 * 
 		 * ██   ██ ███████ ██    ██
 		 * ██  ██  ██       ██  ██
 		 * █████   █████     ████
 		 * ██  ██  ██         ██
 		 * ██   ██ ███████    ██
		 */
		add_action("acquire-edit-key", function() {
			global $env;
			
			if(!file_exists($env->page_filename)) {
				http_response_code(404);
				header("content-type: text/plain");
				exit("Error: The page '$env->page' couldn't be found.");
			}
			
			$format = $_GET["format"] ?? "text";
			$page_hash = generate_page_hash(file_get_contents($env->page_filename));
			
			switch($format) {
				case "text":
					header("content-type: text/plain");
					exit("$env->page\t$page_hash");
				case "json":
					$result = new stdClass();
					$result->page = $env->page;
					$result->key = $page_hash;
					header("content-type: application/json");
					exit(json_encode($result));
				default:
					http_response_code(406);
					header("content-type: text/plain");
					exit("Error: The format $format is not currently known. Supported formats: text, json. Default: text.\nThink this is a bug? Open an issue at https://github.com/sbrl/Pepperminty-Wiki/issues/new");
			}
		});
		
		/**
		 * @api {post} ?action=save&page={pageName}	Save an edit to a page
		 * @apiDescription	Saves an edit to a page. If an edit conflict is encountered, then a conflict resolution page is returned instead.
		 * @apiName			SavePage
		 * @apiGroup		Editing
		 * @apiPermission	Anonymous
		 * 
		 * @apiUse	PageParameter
		 * @apiParam	{string}	newpage		GET only. Set to 'yes' to indicate that this is a new page that is being saved. Only affects the HTTP response code you recieve upon success.
		 * @apiParam	{string}	content		POST only. The new content to save to the given filename.
		 * @apiParam	{string}	tags		POST only. A comma-separated list of tags to assign to the current page. Will replace the existing list of tags, if any are present.
		 * @apiParam	{string}	prev-content-hash	POST only. The hash of the original content before editing. If this hash is found to be different to a hash computed of the currentl saved content, a conflict resolution page will be returned instead of saving the provided content.
		 * 
		 * @apiError	UnsufficientPermissionError	You don't currently have sufficient permissions to save an edit.
		 */
		
		/*
		 *
		 *  ___  __ ___   _____
		 * / __|/ _` \ \ / / _ \
		 * \__ \ (_| |\ V /  __/
		 * |___/\__,_| \_/ \___|
		 *                %save%
		 */
		add_action("save", function() {
			global $pageindex, $settings, $env, $save_preprocessors, $paths;
			// Update the page name in the main environment, since the page name may be submitted via the POST form
			if(isset($_POST["page"])) {
				$env->page = $_POST["page"];
				$env->page_safe = htmlentities($env->page);
			}
			
			if(!$settings->editing)
			{
				http_response_code(403);
				header("x-failure-reason: editing-disabled");
				header("location: index.php?page=" . rawurlencode($env->page));
				exit(page_renderer::render_main("Error saving edit", "<p>Editing is currently disabled on this wiki.</p>"));
			}
			if(!$env->is_logged_in and !$settings->anonedits)
			{
				http_response_code(403);
				header("refresh: 5; url=index.php?page=" . rawurlencode($env->page));
				header("x-login-required: yes");
				exit("You are not logged in, so you are not allowed to save pages on $settings->sitename. Redirecting in 5 seconds....");
			}
			if((
				isset($pageindex->{$env->page}) and
				isset($pageindex->{$env->page}->protect) and
				$pageindex->{$env->page}->protect
			) and !$env->is_admin)
			{
				http_response_code(403);
				header("refresh: 5; url=index.php?page=" . rawurlencode($env->page));
				header("x-failure-reason: protected-page");
				exit(htmlentities($env->page) . " is protected, and you aren't logged in as an administrator or moderator. Your edit was not saved. Redirecting in 5 seconds...");
			}
			if($settings->user_page_prefix == mb_substr($env->page, 0, mb_strlen($settings->user_page_prefix)) and ( // The current page is a user page of some sort
				!$env->is_logged_in or // the user isn't logged in.....
				(
					extract_user_from_userpage($env->page) !== $env->user and // ...or it's not under this user's own name
					!$env->is_admin // ....and the user is not an admin/moderator
				)
			) ) {
				http_response_code(403);
				header("x-failure-reason: permissions-other-user-page");
				header("content-type: text-plain");
				header("refresh: 5; url=index.php?page=" . rawurlencode($env->page));
				exit("Error: The page {$env->page} is a user page. You must be logged in as either that user or a moderator in order to edit it.");
			}
			if(!isset($_POST["content"]))
			{
				http_response_code(400);
				header("refresh: 5; url=index.php?page=" . rawurlencode($env->page));
				header("x-failure-reason: no-content");
				header("content-type: text-plain");
				exit("Bad request: No content specified.");
			}
			if(isset($_POST["prevent_save_if_exists"]) && isset($pageindex->{$env->page})) {
				http_response_code(409);
				exit(page_renderer::render_main("Error saving new page - ".htmlentities($env->page)." - $settings->sitename", "<p>Error: A page with that name already exists. Things you can do:</p>
				<ul>
					<li>View the existing page here: <a target='_blank' href='?action={$settings->defaultaction}&page=".rawurlencode($env->page)."'>".htmlentities($env->page)."</a></li>
					<li><a href='javascript:history.back();'>Go back to continu editing and change the page name</a></li>
				</ul>
				<p>For reference, the page content you attempted to submit is shown below:</p>
				<textarea name='content'>".htmlentities($_POST["content"])."</textarea>"));
			}
			
			// Make sure that the directory in which the page needs to be saved exists
			if(!is_dir(dirname("$env->storage_prefix$env->page.md")))
			{
				// Recursively create the directory if needed
				mkdir(dirname("$env->storage_prefix$env->page.md"), 0775, true);
			}
			
			// Read in the new page content
			$pagedata = $_POST["content"];
			// We don't need to santise the input here as Parsedown has an
			// option that does this for us, and is _way_ more intelligent about
			// it.
			
			// Read in the new page tags, so long as there are actually some
			// tags to read in
			$page_tags = [];
			if(strlen(trim($_POST["tags"])) > 0) {
				$page_tags = explode(",", $_POST["tags"]);
				// Trim off all the whitespace
				foreach($page_tags as &$tag) {
					$tag = trim($tag);
				}
				// Ignore empty tags
				$page_tags = array_filter($page_tags, function($value) {
					return !is_null($value) && $value !== '';
				});
			}
			
			// Check for edit conflicts
			if(!empty($pageindex->{$env->page}) && file_exists($env->storage_prefix . $pageindex->{$env->page}->filename))
			{
				$existing_content_hash = sha1_file($env->storage_prefix . $pageindex->{$env->page}->filename);
				if(isset($_POST["prev-content-hash"]) and
				$existing_content_hash != $_POST["prev-content-hash"])
				{
					$existingPageData = htmlentities(file_get_contents($env->storage_prefix . $env->storage_prefix . $pageindex->{$env->page}->filename));
					// An edit conflict has occurred! We should get the user to fix it.
					$content = "<h1>Resolving edit conflict - $env->page_safe</h1>";
					if(!$env->is_logged_in and $settings->anonedits)
					{
						$content .= "<p><strong>Warning: You are not logged in! Your IP address <em>may</em> be recorded.</strong></p>";
					}
					$content .= "<p>An edit conflict has arisen because someone else has saved an edit to $env->page_safe since you started editing it. Both texts are shown below, along the differences between the 2 conflicting revisions. To continue, please merge your changes with the existing content. Note that only the text in the existing content box will be kept when you press the \"Resolve Conflict\" button at the bottom of the page.</p>
					
					<form method='post' action='index.php?action=save&amp;page=" . rawurlencode($env->page) . "&amp;action=save' class='editform'>
					<h2>Existing content</h2>
					<textarea id='original-content' name='content' autofocus tabindex='1'>$existingPageData</textarea>
					
					<h2>Differences</h2>
					<div id='highlighted-diff' class='highlighted-diff'></div>
					<!--<pre class='highlighted-diff-wrapper'><code id='highlighted-diff'></code></pre>-->
					
					<h2>Your content</h2>
					<textarea id='new-content'>".htmlentities($pagedata)."</textarea>
					<input type='text' name='tags' value='" . htmlentities($_POST["tags"]) . "' placeholder='Enter some tags for the page here. Separate them with commas.' title='Enter some tags for the page here. Separate them with commas.' tabindex='2' />
					<p class='editing_message'>$settings->editing_message</p>
					<input name='submit-edit' type='submit' value='Resolve Conflict' tabindex='3' />
					</form>";
					
					// Insert a reference to jsdiff to generate the diffs
					$diff_script = <<<'DIFFSCRIPT'
window.addEventListener("load", function(event) {
	var destination = document.getElementById("highlighted-diff"),
	diff = JsDiff.diffWords(document.getElementById("original-content").value, document.getElementById("new-content").value),
	output = "";
	diff.forEach(function(change) {
		var classes = "token";
		if(change.added) classes += " diff-added";
		if(change.removed) classes += " diff-removed";
		output += `<span class='${classes}'>${change.value}</span>`;
	});
	destination.innerHTML = output;
});
DIFFSCRIPT;

					page_renderer::add_js_link("$paths->extra_data_directory/page-edit/diff.min.js");
					page_renderer::add_js_snippet($diff_script);
					
					http_response_code(409);
					header("x-failure-reason: edit-conflict");
					exit(page_renderer::render_main("Edit Conflict - $env->page - $settings->sitename", $content));
				}
			}
			
			// -----~~~==~~~-----
			
			// Update the inverted search index
			
			if(module_exists("feature-search")) {
				// Construct an index for the old and new page content
				$oldindex = [];
				$oldpagedata = ""; // We need the old page data in order to pass it to the preprocessor
				if(file_exists("$env->storage_prefix$env->page.md")) {
					$oldpagedata = file_get_contents("$env->storage_prefix$env->page.md");
					$oldindex = search::index_generate($oldpagedata);
				}
				$newindex = search::index_generate($pagedata);
				
				// Compare the indexes of the old and new content
				$additions = [];
				$removals = [];
				search::index_compare($oldindex, $newindex, $additions, $removals);
				// Load in the inverted index
				search::invindex_load($paths->searchindex);
				// Merge the changes into the inverted index
				search::invindex_merge(ids::getid($env->page), $additions, $removals);
				// Save the inverted index back to disk
				search::invindex_close();
			}
			
			// -----~~~==~~~-----
			
			if(file_put_contents("$env->storage_prefix$env->page.md", $pagedata) !== false)
			{
				// Make sure that this page's parents exist
				check_subpage_parents($env->page);
				
				// Update the page index
				if(!isset($pageindex->{$env->page}))
				{
					$pageindex->{$env->page} = new stdClass();
					$pageindex->{$env->page}->filename = "$env->page.md";
				}
				$pageindex->{$env->page}->size = strlen($_POST["content"]);
				$pageindex->{$env->page}->lastmodified = time();
				if($env->is_logged_in)
					$pageindex->{$env->page}->lasteditor = $env->user;
				else // TODO: Add an option to record the user's IP here instead
					$pageindex->{$env->page}->lasteditor = "anonymous";
				$pageindex->{$env->page}->tags = $page_tags;
				
				// A hack to resave the pagedata if the preprocessors have
				// changed it. We need this because the preprocessors *must*
				// run _after_ the pageindex has been updated.
				$pagedata_orig = $pagedata;
				
				// Execute all the preprocessors
				foreach($save_preprocessors as $func)
					$func($pageindex->{$env->page}, $pagedata, $oldpagedata);
				
				if($pagedata !== $pagedata_orig)
					file_put_contents("$env->storage_prefix$env->page.md", $pagedata);
				
				save_pageindex();
				
				if(isset($_GET["newpage"]))
					http_response_code(201);
				else
					http_response_code(200);
				
//				header("content-type: text/plain");
				header("location: index.php?page=" . rawurlencode($env->page) . "&edit_status=success&redirect=no");
				exit();
			}
			else
			{
				header("x-failure-reason: server-error");
				http_response_code(507);
				exit(page_renderer::render_main("Error saving page - $settings->sitename", "<p>$settings->sitename failed to write your changes to the server's disk. Your changes have not been saved, but you might be able to recover your edit by pressing the back button in your browser.</p>
				<p>Please tell the administrator of this wiki (" . htmlentities($settings->admindetails_name) . ") about this problem.</p>"));
			}
		});
		
		add_help_section("15-editing", "Editing", "<p>To edit a page on $settings->sitename, click the edit button on the top bar. Note that you will probably need to be logged in. If you do not already have an account you will need to ask $settings->sitename's administrator for an account since there is no registration form. Note that the $settings->sitename's administrator may have changed these settings to allow anonymous edits.</p>
		<p>Editing is simple. The edit page has a sizeable box that contains a page's current contents. Once you are done altering it, add or change the comma separated list of tags in the field below the editor and then click save page.</p>
		<p>A reference to the syntax that $settings->sitename supports can be found below.</p>");
		
		add_help_section("17-user-pages", "User Pages", "<p>If you are logged in, $settings->sitename allocates you your own user page that only you can edit. On $settings->sitename, user pages are sub-pages of the <a href='?page=" . rawurlencode($settings->user_page_prefix) . "'>" . htmlentities($settings->user_page_prefix) . "</a> page, and each user page can have a nested structure of pages underneath it, just like a normal page. Your user page is located at <a href='?page=" . rawurlencode(get_user_pagename($env->user)) . "'>" . htmlentities(get_user_pagename($env->user)) . "</a>. " .
			(module_exists("page-user-list") ? "You can see a list of all the users on $settings->sitename and visit their user pages on the <a href='?action=user-list'>user list</a>." : "")
		 . "</p>");
	}
]);
/**
 * Generates a unique hash of a page's content for edit conflict detection
 * purposes.
 * @param	string	$page_data	The page text to hash.
 * @return	string				A hash of the given page text.
 */
function generate_page_hash($page_data) {
	return sha1($page_data);
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Export",
	"version" => "0.5.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a page that you can use to export your wiki as a .zip file. Uses \$settings->export_only_allow_admins, which controls whether only admins are allowed to export the wiki.",
	"id" => "page-export",
	"code" => function() {
		global $settings;
		
		/**
		 * @api		{get}	?action=export	Export the all the wiki's content
		 * @apiDescription	Export all the wiki's content. Please ask for permission before making a request to this URI. Note that some wikis may only allow moderators to export content.
		 * @apiName		Export
		 * @apiGroup	Utility
		 * @apiPermission	Anonymous
		 *
		 * @apiError	InsufficientExportPermissionsError	The wiki has the export_allow_only_admins option turned on, and you aren't logged into a moderator account.
		 * @apiError	CouldntOpenTempFileError		Pepperminty Wiki couldn't open a temporary file to send the compressed archive to.
		 * @apiError	CouldntCloseTempFileError		Pepperminty Wiki couldn't close the temporary file to finish creating the zip archive ready for downloading.
		 */
		
		/*
		 * ███████ ██   ██ ██████   ██████  ██████  ████████ 
		 * ██       ██ ██  ██   ██ ██    ██ ██   ██    ██    
		 * █████     ███   ██████  ██    ██ ██████     ██    
		 * ██       ██ ██  ██      ██    ██ ██   ██    ██    
		 * ███████ ██   ██ ██       ██████  ██   ██    ██    
		 */
		add_action("export", function() {
			global $settings, $pageindex, $env;
			
			if($settings->export_allow_only_admins && !$env->is_admin)
			{
				http_response_code(401);
				exit(page_renderer::render("Export error - $settings->sitename", "Only administrators of $settings->sitename are allowed to export the wiki as a zip. <a href='?action=$settings->defaultaction&page='>Return to the ".htmlentities($settings->defaultpage)."</a>."));
			}
			
			$tmpfilename = tempnam(sys_get_temp_dir(), "pepperminty-wiki-");
			
			$zip = new ZipArchive();
			
			if($zip->open($tmpfilename, ZipArchive::CREATE) !== true) {
				http_response_code(507);
				exit(page_renderer::render("Export error - $settings->sitename", "Pepperminty Wiki was unable to open a temporary file to store the exported data in. Please contact $settings->sitename's administrator (" . htmlentities($settings->admindetails_name) . " at " . hide_email($settings->admindetails_email) . ") for assistance."));
			}
			
			foreach($pageindex as $entry) {
				$zip->addFile("$env->storage_prefix$entry->filename", $entry->filename);
				if(isset($entry->uploadedfilepath))
					$zip->addFile($entry->uploadedfilepath);
			}
			
			if($zip->close() !== true) {
				http_response_code(500);
				exit(page_renderer::render("Export error - $settings->sitename", "Pepperminty wiki was unable to close the temporary zip file after creating it. Please contact $settings->sitename's administrator (" . htmlentities($settings->admindetails_name) . " at " . hide_email($settings->admindetails_email) . ") for assistance (this might be a bug)."));
			}
			
			header("content-type: application/zip");
			header("content-disposition: attachment; filename=".str_replace(["\r", "\n", "\""], "", $settings->sitename)."-export.zip");
			header("content-length: " . filesize($tmpfilename));
			
			$zip_handle = fopen($tmpfilename, "rb");
			fpassthru($zip_handle);
			fclose($zip_handle);
			unlink($tmpfilename);
		});
		
		// Add a section to the help page
		add_help_section("50-export", "Exporting", "<p>$settings->sitename supports exporting the entire wiki's content as a zip. Note that you may need to be a moderator in order to do this. Also note that you should check for permission before doing so, even if you are able to export without asking.</p>
		<p>To perform an export, go to the credits page and click &quot;Export as zip - Check for permission first&quot;.</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Help page",
	"version" => "0.10.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a rather useful help page. Access through the 'help' action. This module also exposes help content added to Pepperminty Wiki's inbuilt invisible help section system.",
	"id" => "page-help",
	"code" => function() {
		global $settings;
		
		/**
		 * @api		{get}	?action=help[&dev=yes]	Get a help page
		 * @apiDescription	Get a customised help page. This page will be slightly different for every wiki, depending on their name, settings, and installed modules.
		 * @apiName		Help
		 * @apiGroup	Utility
		 * @apiPermission	Anonymous
		 *
		 * @apiParam	{string}	dev		Set to 'yes' to get a developer help page instead. The developer help page gives some general information about which modules and help page sections are registered, and other various (non-sensitive) settings.
		 */
		
		/*
		 * ██   ██ ███████ ██      ██████  
		 * ██   ██ ██      ██      ██   ██ 
		 * ███████ █████   ██      ██████  
		 * ██   ██ ██      ██      ██      
		 * ██   ██ ███████ ███████ ██      
		 */
		add_action("help", function() {
			global $env, $paths, $settings, $version, $help_sections, $actions;
			
			// Sort the help sections by key
			ksort($help_sections, SORT_NATURAL);
			
			if(isset($_GET["dev"]) and $_GET["dev"] == "yes") {
				$title = "Developers Help - $settings->sitename";
				$content = "<p>$settings->sitename runs on Pepperminty Wiki, an entire wiki packed into a single file. This page contains some information that developers may find useful.</p>
				<p>A full guide to developing a Pepperminty Wiki module can be found <a href='//github.com/sbrl/Pepperminty-Wiki/blob/master/Module_API_Docs.md#module-api-documentation'>on GitHub</a>.</p>
				<h3>Registered Help Sections</h3>
				<p>The following help sections are currently registered:</p>
				<table><tr><th>Index</th><th>Title</th><th>Length</th></tr>\n";
				$totalSize = 0;
				foreach($help_sections as $index => $section)
				{
					$sectionLength = strlen($section["content"]);
					$totalSize += $sectionLength;
					
					$content .= "\t\t\t<tr><td>$index</td><td>" . $section["title"] . "</td><td>" . human_filesize($sectionLength) . "</td></tr>\n";
				}
				$content .= "\t\t\t<tr><th colspan='2' style='text-align: right;'>Total:</th><td>" . human_filesize($totalSize) . "</td></tr>\n";
				$content .= "\t\t</table>\n";
				$content .= "<h3>Registered Actions</h3>";
				$registeredActions = array_keys(get_object_vars($actions));
				sort($registeredActions);
				$content .= "<p>The following actions are currently registered:</p>\n";
				$content .= "<p>" . implode(", ", $registeredActions) . "</p>";
				$content .= "<h3>Environment</h3>\n";
				$content .= "<ul>\n";
				$content .= "<li>$settings->sitename's root directory is " . (!is_writeable(__DIR__) ? "not " : "") . "writeable.</li>\n";
				$content .= "<li>The page index is currently " . human_filesize(filesize($paths->pageindex)) . " in size, and took " . $env->perfdata->pageindex_decode_time . "ms to decode.</li>";
				if(module_exists("feature-search")) {
					$content .= "<li>The search index is currently " . human_filesize(filesize($paths->searchindex)) . " in size.</li>";
				}
				
				$content .= "<li>The id index is currently " . human_filesize(filesize($paths->idindex)) . " in size, and took " . $env->perfdata->idindex_decode_time . "ms to decode.</li>";
				
				$content .= "</ul>\n";
				
				$content .= "<h3>Data</h3>\n";
				
				$wikiSize = new stdClass();
				$wikiSize->all = 0;
				$wikiSize->images = 0;
				$wikiSize->audio = 0;
				$wikiSize->videos = 0;
				$wikiSize->pages = 0;
				$wikiSize->history = 0;
				$wikiSize->indexes = 0;
				$wikiSize->other = 0;
				$wikiFiles = glob_recursive($env->storage_prefix . "*");
				foreach($wikiFiles as $filename)
				{
					$extension = strtolower(substr($filename, strrpos($filename, ".") + 1));
					if($extension === "php") continue; // Skip php files
					
					$nextFilesize = filesize($filename);
					$wikiSize->all += $nextFilesize;
					if($extension[0] === "r") // It's a revision of a page
						$wikiSize->history += $nextFilesize;
					else if($extension == "md") // It's a page
						$wikiSize->pages += $nextFilesize;
					else if(in_array($extension, ["json", "sqlite"])) // It's an index
						$wikiSize->indexes += $nextFilesize;
					else if(in_array($extension, [ // It's an uploaded image
						"jpg", "jpeg", "png", "gif", "webp", "svg",
						"jxl", "avif", "hiec", "hief"
					]))
						$wikiSize->images += $nextFilesize;
					else if(in_array($extension, [ "flac", "mp3", "ogg", "wav", "aac", "m4a" ])) // It's an audio file
						$wikiSize->audio += $nextFilesize;
					else if(in_array($extension, [ "avi", "mp4", "m4v", "webm" ])) // It's a video file
						$wikiSize->videos += $nextFilesize;
					else
						$wikiSize->other += $nextFilesize;
				}
				
				$content .= "<p>$settings->sitename is currently " . human_filesize($wikiSize->all) . " in size.</p>\n";
				$content .= "<div class='stacked-bar'>
					<div class='stacked-bar-part' style='flex: $wikiSize->indexes; background: hsla(191, 100%, 41%, 0.6)'>Indexes: " . human_filesize($wikiSize->indexes) . "</div>
					<div class='stacked-bar-part' style='flex: $wikiSize->pages; background: hsla(112, 83%, 40%, 0.6)'>Pages: " . human_filesize($wikiSize->pages) . "</div>
					<div class='stacked-bar-part' style='flex: $wikiSize->history; background: hsla(116, 84%, 25%, 0.68)'>Page History: " . human_filesize($wikiSize->history) . "</div>
					<div class='stacked-bar-part' style='flex: $wikiSize->images; background: hsla(266, 88%, 47%, 0.6)'>Images: " . human_filesize($wikiSize->images) . "</div>\n";
				if($wikiSize->audio > 0)
					$content .= "<div class='stacked-bar-part' style='flex: $wikiSize->audio; background: hsla(237, 68%, 38%, 0.64)'>Audio: " . human_filesize($wikiSize->audio) . "</div>\n";
				if($wikiSize->videos > 0)
					$content .= "<div class='stacked-bar-part' style='flex: $wikiSize->videos; background: hsla(338, 79%, 54%, 0.64)'>Videos: " . human_filesize($wikiSize->videos) . "</div>\n";
				if($wikiSize->other > 0)
				$content .= "<div class='stacked-bar-part' style='flex: $wikiSize->other; background: hsla(62, 55%, 90%, 0.6)'>Other: " . human_filesize($wikiSize->other) . "</div>\n";
				$content .= "</div>";
			}
			else {
				$title = "Help - $settings->sitename";
				
				$content = "	<h1>$settings->sitename Help</h1>
		<p>Welcome to $settings->sitename!</p>
		<p>$settings->sitename is powered by Pepperminty Wiki, a complete wiki in a box you can drop into your server and expect it to just <em>work</em>.</p>
		
		<h2 id='contents' class='help-section-header'>Contents</h2>
		<ol>";
			foreach($help_sections as $index => $section)
				$content .= "<li><a href='#{$index}'>{$section["title"]}</a></li>\n";
				
			$content .= "</ol>\n";
				// Todo Insert a table of contents here?
				
				foreach($help_sections as $index => $section) {
					// Todo add a button that you can click to get a permanent link
					// to this section.
					$content .= "<h2 id='$index' class='help-section-header'>{$section["title"]}</h2>\n";
					$content .= $section["content"] . "\n";
				}
			}
			
			exit(page_renderer::render_main($title, $content));
		});
		
		// Register a help section on general navigation
		add_help_section("5-navigation", "Navigating", "<p>All the navigation links can be found on the top bar, along with a search box (if your site administrator has enabled it). There is also a &quot;More...&quot; menu in the top right that contains some additional links that you may fine useful.</p>
		<p>This page, along with the credits page, can be found on the bar at the bottom of every page.</p>");
		
		add_help_section("1-extra", "Extra Information", "<p>You can find out which version of Pepperminty Wiki $settings->sitename is using by visiting the <a href='?action=credits'>credits</a> page.</p>
		<p>Information for developers can be found on <a href='?action=help&dev=yes'>this page</a>.</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Page list",
	"version" => "0.12",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a page that lists all the pages in the index along with their metadata.",
	"id" => "page-list",
	"code" => function() {
		global $settings;
		
		/**
		 * @api		{get}	?action=list[&format={format}]	List all pages 
		 * @apiDescription	Gets a list of all the pages currently stored on the wiki.
		 * @apiName		ListPages
		 * @apiGroup	Page
		 * @apiPermission	Anonymous
		 *
		 * @apiParam	{string}	format	The format to return the page list in. Default: html. Other foramts available: json, text
		 * @apiParam	{string}	filter	Since Pepperminty Wiki v0.24, optional. If specified, returns only the page names that contain the given substring.
		 */
		
		/*
		 * ██      ██ ███████ ████████ 
		 * ██      ██ ██         ██    
		 * ██      ██ ███████    ██    
		 * ██      ██      ██    ██    
		 * ███████ ██ ███████    ██    
		 */
		add_action("list", function() {
			global $pageindex, $settings;
			
			$supported_formats = [ "html", "json", "text" ];
			$format = $_GET["format"] ?? "html";
			$filter = $_GET["filter"] ?? null;
			
			
			$array_pageindex = get_object_vars($pageindex);
			$transformed_pageindex = $filter == null ? $array_pageindex : [];
			if($filter !== null) {
				foreach($array_pageindex as $pagename => $entry) {
					if(mb_strpos($pagename, $filter) === false) continue;
					$transformed_pageindex[$pagename] = $entry;
				}
			}
			$sorter = new Collator("");
			uksort($transformed_pageindex, function($a, $b) use($sorter) : int {
				return $sorter->compare($a, $b);
			});
			
			switch($format) {
				case "html":
					$title = "All Pages";
					$content = "	<h1>$title on $settings->sitename</h1>";
					if($filter !== null)
						$content .= "	<p><em>Listing pages containing the text \"$filter\". <a href='?action=list'>List all pages</a>.</em></p>";
					$content .= generate_page_list(array_keys($transformed_pageindex));
					exit(page_renderer::render_main("$title - $settings->sitename", $content));
					break;
					
				case "json":
					header("content-type: application/json");
					exit(json_encode(array_keys($transformed_pageindex), JSON_PRETTY_PRINT));
				
				case "text":
					header("content-type: text/plain");
					exit(implode("\n", array_keys($transformed_pageindex)));
				
				default:
					http_response_code(400);
					exit(page_renderer::render_main("Format error - $settings->sitename", "<p>Error: The format '".htmlentities($format)."' is not currently supported by this action on $settings->sitename. Supported formats: " . htmlentities(implode(", ", $supported_formats)) . "."));
			}
			
		});
		
		/**
		 * @api		{get}	?action=list-tags[&tag=]	Get a list of tags or pages with a certain tag
		 * @apiDescription	Gets a list of all tags on the wiki. Adding the `tag` parameter causes a list of pages with the given tag to be returned instead.
		 * @apiName		ListTags
		 * @apiGroup	Utility
		 * @apiPermission	Anonymous
		 * 
		 * @apiParam	{string}	tag		Optional. If provided a list of all the pages with that tag is returned instead.
		 * @apiParam	{string}	format	Optional. If specified sets the format of the returned result. Supported values: html, json. Default: html
		 */
		
		/*
		 * ██      ██ ███████ ████████ ████████  █████   ██████  ███████ 
		 * ██      ██ ██         ██       ██    ██   ██ ██       ██      
		 * ██      ██ ███████    ██ █████ ██    ███████ ██   ███ ███████ 
		 * ██      ██      ██    ██       ██    ██   ██ ██    ██      ██ 
		 * ███████ ██ ███████    ██       ██    ██   ██  ██████  ███████ 
		 */
		add_action("list-tags", function() {
			global $pageindex, $settings;
			
			$supported_formats = [ "html", "json", "text" ];
			$format = $_GET["format"] ?? "html";
			
			if(!in_array($format, $supported_formats)) {
				http_response_code(400);
				exit(page_renderer::render_main("Format error - $settings->sitename", "<p>Error: The format '".htmlentities($format)."' is not currently supported by this action on $settings->sitename. Supported formats: " . htmlentities(implode(", ", $supported_formats)) . "."));
			}
			
			if(!isset($_GET["tag"]))
			{
				// Render a list of all tags
				$all_tags = get_all_tags();
				
				$sorter = new Collator("");
				$sorter->sort($all_tags, Collator::SORT_STRING);
				
				switch($format) {
					case "html":
						$content = "<h1>All tags</h1>
						<ul class='tag-list'>\n";
						foreach($all_tags as $tag) {
							$content .= "			<li><a href='?action=list-tags&amp;tag=" . rawurlencode($tag) . "' class='mini-tag'>".htmlentities($tag)."</a></li>\n";
						}
						$content .= "</ul>\n";
						
						exit(page_renderer::render("All tags - $settings->sitename", $content));
						break;
					
					case "json":
						header("content-type: application/json");
						exit(json_encode($all_tags, JSON_PRETTY_PRINT));
					
					case "text":
						header("content-type: text/plain");
						exit(implode("\n", $all_tags));
				}
			}
			$tag = $_GET["tag"];
			
			$pagelist = [];
			foreach($pageindex as $pagename => $pagedetails)
			{
				if(empty($pagedetails->tags)) continue;
				if(in_array($tag, $pagedetails->tags))
					$pagelist[] = $pagename;
			}
			
			$sorter = new Collator("");
			$sorter->sort($pagelist, Collator::SORT_STRING);
			
			switch($format)
			{
				case "html":
					$content = "<h1>Tag List: ".htmlentities($tag)."</h1>\n";
					$content .= generate_page_list($pagelist);
					
					$content .= "<p>(<a href='?action=list-tags'>All tags</a>)</p>\n";
					
					exit(page_renderer::render("$tag - Tag List - $settings->sitename", $content));
				
				case "json":
					header("content-type: application/json");
					exit(json_encode($pagelist, JSON_PRETTY_PRINT));
				
				case "text":
					header("content-type: text/plain");
					exit(implode("\n", $pagelist));
			}
			
		});
		
		
		
		
		
		statistic_add([
			"id" => "tag-count",
			"name" => "Number of Tags",
			"type" => "scalar",
			"update" => function($old_data) {
				global $pageindex;
				
				$result = new stdClass(); // value, state, completed
				$result->value = count(get_all_tags());
				$result->completed = true;
				return $result;
			}
		]);
		statistic_add([
			"id" => "tags-per-page",
			"name" => "Average Number of Tags per Page",
			"type" => "scalar",
			"update" => function($old_data) {
				global $pageindex;
				$tag_counts = [];
				foreach($pageindex as $page_entry)
					$tag_counts[] = count($page_entry->tags ?? []);
				
				$result = new stdClass(); // value, state, completed
				$result->value = empty($tag_counts) ? -1 : round(array_sum($tag_counts) / count($tag_counts), 3);
				$result->completed = true;
				return $result;
			}
		]);
		statistic_add([
			"id" => "most-tags",
			"name" => "Most tags on a single page",
			"type" => "scalar",
			"update" => function($old_data) {
				global $pageindex;
				
				$highest_tag_count = 0;
				$highest_tag_page = "";
				foreach($pageindex as $pagename => $page_entry) {
					if(count($page_entry->tags ?? []) > $highest_tag_count) {
						$highest_tag_count = count($page_entry->tags ?? []);
						$highest_tag_page = $pagename;
					}
				}
				
				$result = new stdClass(); // value, state, completed
				$result->value = "$highest_tag_count (<a href='?page=" . rawurlencode($highest_tag_page) . "'>" . htmlentities($highest_tag_page) . "</a>)";
				$result->completed = true;
				return $result;
			}
		]);
		statistic_add([
			"id" => "untagged-pages",
			"name" => "Untagged Pages",
			"type" => "page-list",
			"update" => function($old_data) {
				global $pageindex;
				
				$untagged_pages = [];
				foreach($pageindex as $pagename => $page_entry) {
					if(empty($page_entry->tags) || count($page_entry->tags ?? []) == 0)
						$untagged_pages[] = $pagename;
				}
				
				sort($untagged_pages, SORT_STRING | SORT_FLAG_CASE);
				
				$result = new stdClass(); // value, state, completed
				$result->value = $untagged_pages;
				$result->completed = true;
				return $result;
			}
		]);
		
		add_help_section("30-all-pages-tags", "Listing pages and tags", "<p>All the pages and tags on $settings->sitename are listed on a pair of pages to aid navigation. The list of all pages on $settings->sitename can be found by clicking &quot;All Pages&quot; on the top bar. The list of all the tags currently in use can be found by clicking &quot;All Tags&quot; in the &quot;More...&quot; menu in the top right.</p>
		<p>Each tag on either page can be clicked, and leads to a list of all pages that possess that particular tag.</p>
		<p>Redirect pages are shown in italics. A page's last known editor is also shown next to each entry on a list of pages, along with the last known size (which should correct, unless it was changed outside of $settings->sitename) and the time since the last modification (hovering over this will show the exact time that the last modification was made in a tooltip).</p>");
	}
]);

/**
 * Gets a list of all the tags currently used across the wiki.
 * @package	page-list
 * @since	0.15.0
 * @return	string[]	A list of all unique tags present on all pages across the wiki.
 */
function get_all_tags()
{
	global $pageindex;
	
	$all_tags = [];
	foreach($pageindex as $page_entry) {
		if(empty($page_entry->tags))
			continue;
			
		foreach($page_entry->tags as $tag) {
			if(!in_array($tag, $all_tags))
				$all_tags[] = $tag;
		}
	}
	return $all_tags;
}

/**
 * Renders a list of pages as HTML.
 * @package	page-list
 * @param	string[]	$pagelist	A list of page names to include in the list.
 * @return	string					The specified list of pages as HTML.
 */
function generate_page_list($pagelist)
{
	global $pageindex;
	// ✎ &#9998; 🕒 &#128338;
	$result = "<ul class='page-list'>\n";
	foreach($pagelist as $pagename)
	{
		// Construct a list of tags that are attached to this page ready for display
		$tags = "";
		// Make sure that this page does actually have some tags first
		if(isset($pageindex->$pagename->tags))
		{
			foreach($pageindex->$pagename->tags as $tag)
			{
				$tags .= "<a href='?action=list-tags&amp;tag=" . rawurlencode($tag) . "' class='mini-tag'>".htmlentities($tag)."</a>, ";
			}
			$tags = substr($tags, 0, -2); // Remove the last ", " from the tag list
		}
		
		$pageDisplayName = htmlentities($pagename);
		if(isset($pageindex->$pagename) and
			!empty($pageindex->$pagename->redirect))
			$pageDisplayName = "<em>$pageDisplayName</em>";
		
		$url = "index.php?page=" . rawurlencode($pagename);
		if(isset($pageindex->$pagename->redirect) && $pageindex->$pagename->redirect == true)
			$url .= "&amp;redirect=no";
		
		$result .= "<li><a href='$url'>$pageDisplayName</a>
		<em class='size'>(" . human_filesize($pageindex->$pagename->size) . ")</em>
		<span class='editor'><span class='texticon cursor-query' title='Last editor'>&#9998;</span> " . htmlentities($pageindex->$pagename->lasteditor) . "</span>
		<time class='cursor-query' title='" . date("l jS \of F Y \a\\t h:ia T", $pageindex->$pagename->lastmodified) . "'>" . human_time_since($pageindex->$pagename->lastmodified) . "</time>
		<span class='tags'>$tags</span></li>";
	}
	$result .= "		</ul>\n";
	
	return $result;
}




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Login",
	"version" => "0.9.7",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a pair of actions (login and checklogin) that allow users to login. You need this one if you want your users to be able to login.",
	"id" => "page-login",
	"code" => function() {
		global $settings;
		
		/**
		 * @api		{get}	?action=login[&failed=yes][&returnto={someUrl}]	Get the login page
		 * @apiName		Login
		 * @apiGroup	Authorisation
		 * @apiPermission	Anonymous
		 *
		 * @apiParam	{string}	failed		Setting to yes causes a login failure message to be displayed above the login form.
		 * @apiParam	{string}	returnto	Set to the url to redirect to upon a successful login.
		 */
		
		/*
		 * ██       ██████   ██████  ██ ███    ██
		 * ██      ██    ██ ██       ██ ████   ██
		 * ██      ██    ██ ██   ███ ██ ██ ██  ██
		 * ██      ██    ██ ██    ██ ██ ██  ██ ██
		 * ███████  ██████   ██████  ██ ██   ████
		 */
		add_action("login", function() {
			global $settings, $env;
			
			// Build the action url that will actually perform the login
			$login_form_action_url = "index.php?action=checklogin";
			if(isset($_GET["returnto"]))
				$login_form_action_url .= "&amp;returnto=" . rawurlencode($_GET["returnto"]);
			
			if($env->is_logged_in && !empty($_GET["returnto"]))
			{
				http_response_code(307);
				header("location: " . $_GET["returnto"]);
			}
			
			header("x-robots-tag: noindex, nofollow");
			page_renderer::add_header_html('<meta name="robots" content="noindex, nofollow" />');
			
			
			$title = "Login to $settings->sitename";
			$content = "<h1>Login to $settings->sitename</h1>\n";
			if(isset($_GET["failed"]))
				$content .= "\t\t<p><em>Login failed.</em></p>\n";
			if(isset($_GET["required"]))
				$content .= "\t\t<p><em>$settings->sitename requires that you login before continuing.</em></p>\n";
			if(isset($_GET["returnto"]))
				$content .= "\t\t<p>Login to be redirected to <code>".htmlentities($_GET["returnto"])."</code>.</p>";
			$content .= "\t\t<form method='post' action='$login_form_action_url'>
				<label for='user'>Username:</label>
				<input type='text' name='user' id='user' autofocus />
				<br />
				<label for='pass'>Password:</label>
				<input type='password' name='pass' id='pass' />
				<br />
				<input type='submit' value='Login' />
			</form>\n";
			exit(page_renderer::render_main($title, $content));
		});
		
		/**
		 * @api		{post}	?action=checklogin	Perform a login
		 * @apiName		CheckLogin
		 * @apiGroup	Authorisation
		 * @apiPermission	Anonymous
		 *
		 * @apiParam	{string}	user		The user name to login with.
		 * @apiParam	{string}	pass		The password to login with.
		 * @apiParam	{string}	returnto	The URL to redirect to upon a successful login.
		 *
		 * @apiError	InvalidCredentialsError	The supplied credentials were invalid. Note that this error is actually a redirect to ?action=login&failed=yes (with the returnto parameter appended if you supplied one)
		 */
		
		/*
 		 * ██████ ██   ██ ███████  ██████ ██   ██
		 * ██     ██   ██ ██      ██      ██  ██
		 * ██     ███████ █████   ██      █████
		 * ██     ██   ██ ██      ██      ██  ██
 		 * ██████ ██   ██ ███████  ██████ ██   ██
 		 *
		 * ██       ██████   ██████  ██ ███    ██
		 * ██      ██    ██ ██       ██ ████   ██
		 * ██      ██    ██ ██   ███ ██ ██ ██  ██
		 * ██      ██    ██ ██    ██ ██ ██  ██ ██
		 * ███████  ██████   ██████  ██ ██   ████
		 */
		add_action("checklogin", function() {
			global $settings, $env;
			
			if(!isset($_POST["user"]) or !isset($_POST["pass"])) {
				http_response_code(302);
				$nextUrl = "index.php?action=login&failed=yes&badrequest=yes";
				if(!empty($_GET["returnto"]))
				$nextUrl .= "&returnto=" . rawurlencode($_GET["returnto"]);
				header("location: $nextUrl");
				exit();
			}
			
			// Actually do the login
			
			// The user wants to log in
			$user = $_POST["user"];
			$pass = $_POST["pass"];
			
			// Verify their password
			if(empty($settings->users->$user) || !verify_password($pass, $settings->users->$user->password)) {
				// Login failed :-(
				http_response_code(302);
				header("x-login-success: no");
				$nextUrl = "index.php?action=login&failed=yes";
				if(!empty($_GET["returnto"]))
					$nextUrl .= "&returnto=" . rawurlencode($_GET["returnto"]);
				header("location: $nextUrl");
				exit();
			}
			
			// Success! :D
			
			// Avoid a session fixation attack
			// Ref https://guides.codepath.com/websecurity/Session-Fixation
			session_regenerate_id(true);
			
			send_cookie(session_name(), session_id(), time() + $settings->sessionlifetime);
			
			
			// Update the environment
			$env->is_logged_in = true;
			$env->user = $user;
			$env->user_data = $settings->users->{$env->user};
			
			$new_password_hash = hash_password_update($pass, $settings->users->$user->password);
			
			// Update the password hash
			if($new_password_hash !== null) {
				$env->user_data->password = $new_password_hash;
				if(!save_userdata()) {
					http_response_code(503);
					exit(page_renderer::render_main("Login Error - $settings->sitename", "<p>Your credentials were correct, but $settings->sitename was unable to log you in as an updated hash of your password couldn't be saved. Updating your password hash to the latest and strongest hashing algorithm is an important part of keeping your account secure.</p>
					<p>Please contact ".htmlentities($settings->admindetails_name).", $settings->sitename's adminstrator, for assistance (their email address can be found at the bottom of every page, including this one).</p>"));
				}
				error_log("[PeppermintyWiki/$settings->sitename/login] Updated password hash for $user.");
			}
			
			// If the email address is still in the old field, migrate it
			if(!empty($settings->users->{$user}->email)) {
				$settings->users->{$user}->emailAddress = $settings->users->{$user}->email;
				unset($settings->users->{$user}->email);
				save_settings();
			}
			
			$_SESSION["$settings->sessionprefix-user"] = $user;
			$_SESSION["$settings->sessionprefix-pass"] = $new_password_hash ?? hash_password($pass);
			$_SESSION["$settings->sessionprefix-expiretime"] = time() + 60*60*24*30; // 30 days from now
			
			// Redirect to wherever the user was going
			http_response_code(302);
			header("x-login-success: yes");
			if(isset($_GET["returnto"])) {
				$returnto_redirect = $_GET["returnto"];
				if(strpos($returnto_redirect, "?") === false) {
					http_response_code(307);
					header("location: ?action=view");
					exit(page_renderer::render_main("Login error - $settings->sitename", "<p>Your credentials were correct, but the 'returnto' URL specified (in the <code>returnto</code> GET parameter) did not contain a question mark. To protect you from being redirected to another site, $settings->sitename only allows redirects that do not leave $settings->sitename.</p>"));
				}
				// Ensure that this redirect takes to only somewhere else in this site
				$returnto_redirect = substr($returnto_redirect, strpos($returnto_redirect, "?"));
				header("location: $returnto_redirect");
			}
			else
				header("location: index.php");
			exit();
		});
		
		add_action("hash-cost-test", function() {
			global $env;
			
			header("content-type: text/plain");
			
			if(!$env->is_logged_in || !$env->is_admin) {
				http_response_code(401);
				header("content-type: text/plain");
				exit("Error: Only moderators are allowed to use this action.");
			}
			
			$time_compute = microtime(true);
			$cost = hash_password_compute_cost(true);
			$time_compute = (microtime(true) - $time_compute)*1000;
			
			$time_cost = microtime(true);
			password_hash("testing", PASSWORD_DEFAULT, [ "cost" => $cost ]);
			$time_cost = (microtime(true) - $time_cost)*1000;
			
			echo("Calculated cost: $cost ({$time_cost}ms)\n");
			echo("Time taken: {$time_compute}ms\n");
			exit(date("r"));
		});
		
		// Register a section on logging in on the help page.
		add_help_section("30-login", "Logging in", "<p>In order to edit $settings->sitename and have your edit attributed to you, you need to be logged in. Depending on the settings, logging in may be a required step if you want to edit at all. Thankfully, loggging in is not hard. Simply click the &quot;Login&quot; link in the top left, type your username and password, and then click login.</p>
		<p>If you do not have an account yet and would like one, try contacting " . hide_email($settings->admindetails_email, $settings->admindetails_name) . ", $settings->sitename's administrator and ask them nicely to see if they can create you an account.</p>");
		
		// Re-check the password hashing cost, if necessary
		do_password_hash_code_update();
	}
]);

/**
 * Recalculates and updates the password hashing cost.
 */
function do_password_hash_code_update() {
	global $settings, $paths;
	
	// There's no point if we're using Argon2i, as it doesn't take a cost
	if(defined("PASSWORD_ARGON2I") && hash_password_properties()["algorithm"] == PASSWORD_ARGON2I)
		return;
	
	// Skip rechecking if the automatic check has been disabled
	if($settings->password_cost_time_interval == -1)
		return;
	// Skip the recheck if we've done one recently
	if(isset($settings->password_cost_time_lastcheck) &&
		time() - $settings->password_cost_time_lastcheck < $settings->password_cost_time_interval)
		return;
	
	$new_cost = hash_password_compute_cost();
	
	// Save the new cost, but only if it's higher than the old one
	if($new_cost > $settings->password_cost)
		$settings->password_cost = $new_cost;
	// Save the current time in the settings
	$settings->password_cost_time_lastcheck = time();
	file_put_contents($paths->settings_file, json_encode($settings, JSON_PRETTY_PRINT));
}

/**
 * Figures out the appropriate algorithm & options for hashing passwords based
 * on the current settings.
 * @return array The appropriate password hashing algorithm and options.
 */
function hash_password_properties() {
	global $settings;
	
	$result = [
		"algorithm" => constant($settings->password_algorithm),
		"options" => [ "cost" => $settings->password_cost ]
	];
	if(defined("PASSWORD_ARGON2I") && $result["algorithm"] == PASSWORD_ARGON2I)
		$result["options"] = [];
	return $result;
}
/**
 * Hashes the given password according to the current settings defined
 * in $settings.
 * @package	page-login
 * @param	string	$pass	The password to hash.
 *
 * @return	string	The hashed password. Uses password_hash() under-the-hood, but with some additional extras to avoid known issues.
 */
function hash_password($pass) {
	$props = hash_password_properties();
	return password_hash(
		base64_encode(hash("sha384", $pass)),
		$props["algorithm"],
		$props["options"]
	);
}
/**
 * Verifies a user's password against a pre-generated hash.
 * @param	string	$pass	The user's password.
 * @param	string	$hash	The hash to compare against.
 * @return	bool	Whether the password matches the has or not.
 */
function verify_password($pass, $hash) {
	$pass_transformed = base64_encode(hash("sha384", $pass));
	return password_verify($pass_transformed, $hash);
}
/**
 * Determines if the provided password needs re-hashing or not.
 * @param  string $pass The password to check.
 * @param  string $hash The hash of the provided password to check.
 * @return string|null  Returns null if an updaste is not required - otherwise returns the new updated hash.
 */
function hash_password_update($pass, $hash) {
	$props = hash_password_properties();
	if(password_needs_rehash($hash, $props["algorithm"], $props["options"])) {
		return hash_password($pass);
	}
	return null;
}
/**
 * Computes the appropriate cost value for password_hash based on the settings
 * automatically.
 * Starts at 10 and works upwards in increments of 1. Goes on until a value is
 * found that's greater than the target - or 10x the target time elapses.
 * @param	bool	$verbose	Whether to output verbose progress information to the client or not.
 * @return	int		The automatically calculated password hashing cost.
 */
function hash_password_compute_cost($verbose = false) {
	global $settings;
	$props = hash_password_properties();
	if($props["algorithm"] == PASSWORD_ARGON2I)
		return null;
	$props["options"]["cost"] = 10;
	
	$target_cost_time = $settings->password_cost_time / 1000; // The setting is in ms
	
	do {
		$props["options"]["cost"]++;
		$start_i = microtime(true);
		password_hash("testing", $props["algorithm"], $props["options"]);
		$end_i =  microtime(true);
		if($verbose) echo("Attempt | cost = {$props["options"]["cost"]}, time = " . ($end_i - $start_i)*1000 . "ms\n");
		// Iterate until we find a cost high enough
		// ....but don't keep going forever - try for at most 10x the target
		// time in total (in case the specified algorithm doesn't take a
		// cost parameter)
	} while($end_i - $start_i < $target_cost_time);
	
	return $props["options"]["cost"];
}


/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Logout",
	"version" => "0.6.1",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds an action to let users user out. For security reasons it is wise to add this module since logging in automatically opens a session that is valid for 30 days.",
	"id" => "page-logout",
	"code" => function() {
		
		/**
		 * @api		{post}	?action=logout	Logout
		 * @apiDescription	Logout. Make sure that your bot requests this URL when it is finished - this call not only clears your cookies but also clears the server's session file as well. Note that you can request this when you are already logged out and it will completely wipe your session on the server.
		 * @apiName		Logout
		 * @apiGroup	Authorisation
		 * @apiPermission	Anonymous
		 */
		
		/*
		 * ██       ██████   ██████   ██████  ██    ██ ████████ 
		 * ██      ██    ██ ██       ██    ██ ██    ██    ██    
		 * ██      ██    ██ ██   ███ ██    ██ ██    ██    ██    
		 * ██      ██    ██ ██    ██ ██    ██ ██    ██    ██    
		 * ███████  ██████   ██████   ██████   ██████     ██    
		 */
		add_action("logout", function() {
			global $env;
			$env->is_logged_in = false;
			unset($env->user);
			unset($env->user_data);
			//clear the session variables
			$_SESSION = [];
			session_destroy();
			
			exit(page_renderer::render_main("Logout Successful", "<h1>Logout Successful</h1>
		<p>Logout Successful. You can login again <a href='index.php?action=login'>here</a>.</p>"));
		});
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Page mover",
	"version" => "0.9.6",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds an action to allow administrators to move pages.",
	"id" => "page-move",
	"code" => function() {
		global $settings;
		
		/**
		 * @api		{get}	?action=move[&new_name={newPageName}]	Move a page
		 * @apiName		Move
		 * @apiGroup	Page
		 * @apiPermission	Moderator
		 * 
		 * @apiParam	{string}	new_name	The new name to move the page to. If not set a page will be returned containing a move page form.
		 *
		 * @apiUse UserNotModeratorError
		 * @apiError	EditingDisabledError	Editing is disabled on this wiki, so pages can't be moved.
		 * @apiError	PageExistsAtDestinationError	A page already exists with the specified new name.
		 * @apiError	NonExistentPageError		The page you're trying to move doesn't exist in the first place.
		 * @apiError	PreExistingFileError		A pre-existing file on the server's file system was detected.
		 */
		
		/*
		 * ███    ███  ██████  ██    ██ ███████ 
		 * ████  ████ ██    ██ ██    ██ ██      
		 * ██ ████ ██ ██    ██ ██    ██ █████   
		 * ██  ██  ██ ██    ██  ██  ██  ██      
		 * ██      ██  ██████    ████   ███████ 
		 */
		add_action("move", function() {
			global $pageindex, $settings, $env, $paths;
			if(!$settings->editing)
			{
				exit(page_renderer::render_main("Moving $env->page - error", "<p>You tried to move $env->page_safe, but editing is disabled on this wiki.</p>
				<p>If you wish to move this page, please re-enable editing on this wiki first.</p>
				<p><a href='index.php?page=".rawurlencode($env->page)."'>Go back to $env->page_safe</a>.</p>
				<p>Nothing has been changed.</p>"));
			}
			if(!$env->is_admin)
			{
				exit(page_renderer::render_main("Moving $env->page - Error", "<p>You tried to move $env->page_safe, but you do not have permission to do that.</p>
				<p>You should try <a href='index.php?action=login&amp;returnto=".rawurlencode("?action=move&page=".rawurlencode($env->page))."'>logging in</a> as an admin.</p>"));
			}
			
			if(!isset($_GET["new_name"]) or strlen($_GET["new_name"]) == 0)
				exit(page_renderer::render_main("Moving $env->page", "<h2>Moving $env->page_safe</h2>
				<form method='get' action='index.php'>
					<input type='hidden' name='action' value='move' />
					<label for='old_name'>Old Name:</label>
					<input type='text' name='page' value='$env->page_safe' readonly />
					<br />
					<label for='new_name'>New Name:</label>
					<input type='text' name='new_name' />
					<br />
					<input type='submit' value='Move Page' />
				</form>"));
			
			$new_name = makepathsafe($_GET["new_name"]);
			
			$page = $env->page;
			if(!isset($pageindex->$page))
				exit(page_renderer::render_main("Moving $env->page - Error", "<p>You tried to move $env->page_safe to ".htmlentities($new_name).", but the page with the name $env->page_safe does not exist in the first place.</p>
				<p>Nothing has been changed.</p>"));
			
			if($env->page == $new_name)
				exit(page_renderer::render_main("Moving $env->page - Error", "<p>You tried to move $env->page_safe, but the new name you gave is the same as it's current name.</p>
				<p>It is possible that you tried to use some characters in the new name that are not allowed and were removed.</p>
				<p>Page names may <em>not</em> contain any of these characters: <code>?%*:|\"&gt;&lt;()[]</code></p>"));
			
			if(isset($pageindex->$page->uploadedfile) and
				file_exists($new_name))
				exit(page_renderer::render_main("Moving $env->page - Error - $settings->sitename", "<p>Whilst moving the file associated with $env->page_safe, $settings->sitename detected a pre-existing file on the server's file system. Because $settings->sitename can't determine whether the existing file is important to another component of $settings->sitename or it's host web server, the move has been aborted - just in case.</p>
				<p>If you know that this move is actually safe, please get your site administrator (" . htmlentities($settings->admindetails_name) . ") to perform the move manually. Their contact address can be found at the bottom of every page (including this one).</p>"));
				
			// Make sure that the parent page exists
			$do_create_dir = true;
			if(strpos($new_name, "/", $do_create_dir) === false)
				$do_create_dir = false;
			check_subpage_parents($new_name, $do_create_dir);
			
			// Move the page in the page index
			$pageindex->$new_name = new stdClass();
			foreach($pageindex->$page as $key => $value) {
				$pageindex->$new_name->$key = $value;
			}
			unset($pageindex->$page);
			$pageindex->$new_name->filename = "$new_name.md";
			
			// If this page has an associated file, then we should move that too
			if(!empty($pageindex->$new_name->uploadedfile)) {
				// Update the filepath to point to the description and not the image
				$pageindex->$new_name->filename = $pageindex->$new_name->filename . ".md";
				// Move the file in the pageindex
				$pageindex->$new_name->uploadedfilepath = $new_name;
				// Move the file on disk
				rename($env->storage_prefix . $env->page, $env->storage_prefix . $new_name);
			}
			
			// Come to think about it, we should probably move the history while we're at it
			foreach($pageindex->$new_name->history as &$revisionData) {
				// We're only interested in edits
				if($revisionData->type !== "edit") continue;
				$newRevisionName = $pageindex->$new_name->filename . ".r$revisionData->rid";
				// Move the revision to it's new name
				rename(
					$env->storage_prefix . $revisionData->filename,
					$env->storage_prefix . $newRevisionName
				);
				// Update the pageindex entry
				$revisionData->filename = $newRevisionName;
			}
			
			// Save the updated pageindex
			save_pageindex();
			
			// Move the page on the disk
			rename("$env->storage_prefix$env->page.md", "$env->storage_prefix$new_name.md");
			
			// Move the page in the id index
			ids::movepagename($page, $new_name);
			
			// Move the comments file as well, if it exists
			if(file_exists("$env->storage_prefix$env->page.comments.json")) {
				rename(
					"$env->storage_prefix$env->page.comments.json",
					"$env->storage_prefix$new_name.comments.json"
				);
			}
			
			// Add a recent change announcing the move if the recent changes
			// module is installed
			if(module_exists("feature-recent-changes"))
			{
				add_recent_change([
					"type" => "move",
					"timestamp" => time(),
					"oldpage" => $page,
					"page" => $new_name,
					"user" => $env->user
				]);
			}
			
			// Exit with a nice message
			exit(page_renderer::render_main("Moving " . htmlentities($env->page), "<p><a href='index.php?page=" . rawurlencode($env->page) . "'>" . htmlentities($env->page) . "</a> has been moved to <a href='index.php?page=" . rawurlencode($new_name) . "'>" . htmlentities($new_name) . "</a> successfully.</p>"));
		});
		
		// Register a help section
		add_help_section("60-move", "Moving Pages", "<p>If you are logged in as an administrator, then you have the power to move pages. To do this, click &quot;Move&quot; in the &quot;More...&quot; menu when browsing the pge you wish to move. Type in the new name of the page, and then click &quot;Move Page&quot;.</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Sitemap",
	"version" => "0.1.1",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds XML sitemap generation. Additional manual setup is required to notify search engines about the sitemap. See the Features FAQ in the documentation (or your wiki's credits page) for more information.",
	"id" => "page-sitemap",
	"code" => function() {
		global $settings;
		/**
		 * @api {get} ?action=sitemap	Get an XML sitemap
		 * @apiName Sitemap
		 * @apiGroup Page
		 * @apiPermission Anonymous
		 */
		
		/*
		 * ██████   █████  ██     ██ 
		 * ██   ██ ██   ██ ██     ██ 
		 * ██████  ███████ ██  █  ██ 
		 * ██   ██ ██   ██ ██ ███ ██ 
		 * ██   ██ ██   ██  ███ ███  
		 */
		add_action("sitemap", function() {
			global $pageindex, $env;
			
			$full_url_stem = full_url();
			
			// Reference: https://www.sitemaps.org/protocol.html
			$xml = new XmlWriter();
			$xml->openMemory();
			$xml->startDocument("1.0", "utf-8");
			
			$xml->startElement("urlset");
			$xml->writeAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
			
			foreach($pageindex as $pagename => $pagedata) {
				$xml->startElement("url");
				$xml->writeElement("loc", "$full_url_stem?page=".rawurlencode($pagename));
				if(isset($pagedata->lastmodified))
					$xml->writeElement("lastmod", date("Y-m-d", $pagedata->lastmodified));
				$xml->endElement();
			}
			
			$xml->endElement();
			
			$sitemap = $xml->flush();
			
			header("content-type: application/xml");
			header("content-disposition: inline");
			header("content-length: " . strlen($sitemap));
			exit($sitemap);
		});
		
		add_help_section("800-raw-page-content", "Viewing Raw Page Content", "<p>Although you can use the edit page to view a page's source, you can also ask $settings->sitename to send you the raw page source and nothing else. This feature is intented for those who want to automate their interaction with $settings->sitename.</p>
		<p>To use this feature, navigate to the page for which you want to see the source, and then alter the <code>action</code> parameter in the url's query string to be <code>raw</code>. If the <code>action</code> parameter doesn't exist, add it. Note that when used on an file's page this action will return the source of the description and not the file itself.</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Update",
	"version" => "0.6.2",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds an update page that downloads the latest stable version of Pepperminty Wiki. This module is currently outdated as it doesn't save your module preferences.",
	"id" => "page-update",
	"code" => function() {
		
		/**
		 * @api		{get}	?action=update[do=yes]	Update the wiki
		 * @apiDescription	Update the wiki by downloading  a new version of Pepperminty Wiki from the URL specified in the settings. Note that unless you change the url from it's default, all custom modules installed will be removed. **Note also that this plugin is currently out of date. Use with extreme caution!**
		 * @apiName			Update
		 * @apiGroup		Utility
		 * @apiPermission	Moderator
		 * 
		 * @apiParam	{string}	do		Set to 'yes' to actually do the upgrade. Omission causes a page asking whether an update is desired instead.
		 * @apiParam	{string}	secret	The wiki's secret string that's stored in the settings.
		 *
		 * @apiUse UserNotModeratorError
		 * @apiParam	InvalidSecretError	The supplied secret doesn't match up with the secret stored in the wiki's settings.
		 */
		
		/*
		 * ██    ██ ██████  ██████   █████  ████████ ███████ 
		 * ██    ██ ██   ██ ██   ██ ██   ██    ██    ██      
		 * ██    ██ ██████  ██   ██ ███████    ██    █████   
		 * ██    ██ ██      ██   ██ ██   ██    ██    ██      
		 *  ██████  ██      ██████  ██   ██    ██    ███████ 
		 */
		add_action("update", function() {
			global $settings, $env;
			
			if(!$env->is_admin)
			{
				http_response_code(401);
				exit(page_renderer::render_main("Update - Error", "<p>You must be an administrator to do that.</p>"));
			}
			
			if(!isset($_GET["do"]) or $_GET["do"] !== "true" or $_GET["do"] !== "yes")
			{
				exit(page_renderer::render_main("Update $settings->sitename", "<p>This page allows you to update $settings->sitename.</p>
				<p>Currently, $settings->sitename is using $settings->version of Pepperminty Wiki.</p>
				<p>This script will automatically download and install the latest version of Pepperminty Wiki from the url of your choice (see settings), regardless of whether an update is actually needed (version checking isn't implemented yet).</p>
				<p>To update $settings->sitename, fill out the form below and click click the update button.</p>
				<p>Note that a backup system has not been implemented yet! If this script fails you will loose your wiki's code and have to re-build it.</p>
				<form method='get' action=''>
					<input type='hidden' name='action' value='update' />
					<input type='hidden' name='do' value='true' />
					<label for='secret'>$settings->sitename's secret code</label>
					<input type='text' name='secret' value='' />
					<input type='submit' value='Update' />
				</form>"));
			}
			
			if(!isset($_GET["secret"]) or $_GET["secret"] !== $settings->sitesecret)
			{
				exit(page_renderer::render_main("Update $settings->sitename - Error", "<p>You forgot to enter $settings->sitename's secret code or entered it incorrectly. $settings->sitename's secret can be found in the settings portion of <code>index.php</code>.</p>"));
			}
			
			$settings_separator = "/////////////// Do not edit below this line unless you know what you are doing! ///////////////";
			
			$log = "Beginning update...\n";
			
			$log .= "I am <code>" . __FILE__ . "</code>.\n";
			$oldcode = file_get_contents(__FILE__);
			$log .= "Fetching new code...";
			$newcode = file_get_contents($settings->updateurl);
			$log .= "done.\n";
			
			$log .= "Rewriting <code>" . __FILE__ . "</code>...";
			$settings = substr($oldcode, 0, strpos($oldcode, $settings_separator));
			$code = substr($newcode, strpos($newcode, $settings_separator));
			$result = $settings . $code;
			$log .= "done.\n";
			
			$log .= "Saving...";
			file_put_contents(__FILE__, $result);
			$log .= "done.\n";
			
			$log .= "Update complete. I am now running on the latest version of Pepperminty Wiki.";
			$log .= "The version number that I have updated to can be found on the credits or help ages.";
			
			exit(page_renderer::render_main("Update - Success", "<ul><li>" . implode("</li><li>", explode("\n", $log)) . "</li></ul>"));
		});
	}
]);



/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "User list",
	"version" => "0.1",
	"author" => "Starbeamrainbowlabs",
	"description" => "Adds a 'user-list' action that generates a list of users. Supports json output with 'format=json' in the queyr string.",
	"id" => "page-user-list",
	"code" => function() {
		global $settings;
		/**
		 * @api {get} ?action=user-list[&format=json] List all users
		 * @apiName UserList
		 * @apiGroup Utility
		 * @apiPermission Anonymous
		 */
		
		/*
		 * ██    ██ ███████ ███████ ██████        ██      ██ ███████ ████████
		 * ██    ██ ██      ██      ██   ██       ██      ██ ██         ██
		 * ██    ██ ███████ █████   ██████  █████ ██      ██ ███████    ██
		 * ██    ██      ██ ██      ██   ██       ██      ██      ██    ██
		 *  ██████  ███████ ███████ ██   ██       ███████ ██ ███████    ██
		 */
		add_action("user-list", function() {
			global $env, $settings;
			
			$userList = array_keys(get_object_vars($settings->users));
			if(!empty($_GET["format"]) && $_GET["format"] === "json")
			{
				header("content-type: application/json");
				exit(json_encode($userList));
			}
			
			$content = "<h1>User List</h1>\n";
			$content .= "<ul class='page-list user-list invisilist'>\n";
			foreach($userList as $username)
				$content .= "\t<li>" . page_renderer::render_username($username) . "</li>\n";
			$content .= "</ul>\n";
			
			exit(page_renderer::render_main("User List - $settings->sitename", $content));
		});
		
		add_help_section("800-raw-page-content", "Viewing Raw Page Content", "<p>Although you can use the edit page to view a page's source, you can also ask $settings->sitename to send you the raw page source and nothing else. This feature is intented for those who want to automate their interaction with $settings->sitename.</p>
		<p>To use this feature, navigate to the page for which you want to see the source, and then alter the <code>action</code> parameter in the url's query string to be <code>raw</code>. If the <code>action</code> parameter doesn't exist, add it. Note that when used on an file's page this action will return the source of the description and not the file itself.</p>");
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Page viewer",
	"version" => "0.16.12",
	"author" => "Starbeamrainbowlabs",
	"description" => "Allows you to view pages. You really should include this one.",
	"id" => "page-view",
	// Another random protection to ensure the credits are included
	// The aim is to make it as annoying as possible to disentangle the credits from the rest of Pepperminty Wiki
	// If you're reading this, you should know that credits are really important - lots of people have put in a huge amount of time and effort to make Pepperminty Wiki what it is today!
	"depends" => [ "page-credits" ],
	"code" => function() {
		/**
		 * @api	{get}	?action=view[&page={pageName}][&revision=rid][&printable=yes][&mode={mode}]	View a page
		 * @apiName			View
		 * @apiGroup		Page
		 * @apiPermission	Anonymous
		 * 
		 * @apiUse PageParameter
		 * @apiParam	{number}	revision	The revision number to display.
		 * @apiParam	{string}	mode		Optional. The display mode to use. Can hold the following values: 'normal' - The default. Sends a normal page. 'printable' - Sends a printable version of the page. 'contentonly' - Sends only the content of the page, not the extra stuff around it. 'parsedsourceonly' - Sends only the raw rendered source of the page, as it appears just after it has come out of the page parser. Useful for writing external tools (see also the `raw` action).
		 * @apiParam	{string}	redirect	Optional. If set to 'no' (without quotes), causes the specified page to be shown - even if it would normally result in a redirect.
		 * @apiParam	{string}	redirected_from	Optional. Cosmetic parameter that displays the name of the page that was redirected from. You will not normally need to use this.
		 *
		 * @apiError	NonExistentPageError	The page doesn't exist and editing is disabled in the wiki's settings. If editing isn't disabled, you will be redirected to the edit page instead.
		 * @apiError	NonExistentRevisionError	The specified revision was not found.
		 */
		
		/*
		 * ██    ██ ██ ███████ ██     ██ 
		 * ██    ██ ██ ██      ██     ██ 
		 * ██    ██ ██ █████   ██  █  ██ 
		 *  ██  ██  ██ ██      ██ ███ ██ 
		 *   ████   ██ ███████  ███ ███  
		 */
		add_action("view", function() {
			global $pageindex, $settings, $env;
			
			// Check to make sure that the page exists
			$page = $env->page;
			if(!isset($pageindex->$page)) {
				// TODO: make this intelligent so we only redirect if the user is actually able to create the page
				if($settings->editing) {
					// Editing is enabled, redirect to the editing page
					$redirectUrl = "index.php?action=edit&newpage=yes&page=".rawurlencode($env->page);
					if(isset($_GET["redirected_from"]))
						$redirectUrl .= "&redirected_from=".rawurlencode($_GET["redirected_from"]);
					http_response_code(307); // Temporary redirect
					header("location: $redirectUrl");
					exit();
				} else {
					// Editing is disabled, show an error message
					http_response_code(404);
					exit(page_renderer::render_main("404: Page not found - $env->page - $settings->sitename", "<p>$env->page_safe does not exist.</p><p>Since editing is currently disabled on this wiki, you may not create this page. If you feel that this page should exist, try contacting this wiki's Administrator (see the bottom of this page for their contact details).</p>"));
				}
			}
			
			header("last-modified: " . gmdate('D, d M Y H:i:s T', $pageindex->{$env->page}->lastmodified));
			
			// Perform a redirect if the requested page is a redirect page
			if(isset($pageindex->$page->redirect) && 
			   $pageindex->$page->redirect === true) // If this is a redirect page.....
			{
				$send_redirect = true;
				if(isset($_GET["redirect"]) && $_GET["redirect"] == "no")
					$send_redirect = false;
				if((isset($pageindex->$page->redirect_absolute) &&
					$pageindex->$page->redirect_absolute == true && // ...and it's absolute....
					$settings->redirect_absolute_enable === false)) // ...and absolute redirects are not enabled
					$send_redirect = false;
			   
				
				if($send_redirect) {
					// TODO: Send an explanatory page along with the redirect
					http_response_code(307);
					$redirectUrl = "?action=$env->action&redirected_from=" . rawurlencode($env->page);
					
					$hashCode = "";
					$newPage = $pageindex->$page->redirect_target;
					if(strpos($newPage, "#") !== false) {
						// Extract the part after the hash symbol
						$hashCode = substr($newPage, strpos($newPage, "#") + 1);
						// Remove the hash from the new page name
						$newPage = substr($newPage, 0, strpos($newPage, "#"));
					}
					$redirectUrl .= "&page=" . rawurlencode($newPage);
					if(!empty($pageindex->$newPage->redirect))
						$redirectUrl .= "&redirect=no";
					if(strlen($hashCode) > 0)
						$redirectUrl .= "#".htmlentities($hashCode);
					
					// Support absolute redirect URLs
					if(isset($pageindex->$page->redirect_absolute) && $pageindex->$page->redirect_absolute === true)
						$redirectUrl = $pageindex->$page->redirect_target;
					
					header("location: $redirectUrl");
					exit();
				}
			}
			
			$title = "$env->page - $settings->sitename";
			if(isset($pageindex->$page->protect) && $pageindex->$page->protect === true)
				$title = $settings->protectedpagechar . $title;
			$content = "";
			if(!$env->is_history_revision)
				$content .= "<h1>$env->page</h1>\n";
			else {
				$content .= "<h1>Revision #{$env->history->revision_number} of $env->page_safe</h1>\n";
				$content .= "<p class='system-text-insert revision-note'><em>(Revision saved by ".htmlentities($env->history->revision_data->editor)." " . render_timestamp($env->history->revision_data->timestamp) . ". <a href='?page=" . rawurlencode($env->page) . "'>Jump to the current revision</a> or see a <a href='?action=history&page=" . rawurlencode($env->page) . "'>list of all revisions</a> for this page.)</em></p>\n";
			}
			
			// Add a visit parent page link if we're a subpage
			if(get_page_parent($env->page) !== false)
				$content .= "<p class='system-text-insert link-parent-page'><em><a href='?action=view&page=" . rawurlencode(get_page_parent($env->page)) . "'>&laquo; " . htmlentities(get_page_parent($env->page)) . "</a></em></p>\n";
			
			// Add an extra message if the requester was redirected from another page
			if(isset($_GET["redirected_from"]))
				$content .= "<p class='system-text-insert'><em>Redirected from <a href='?page=" . rawurlencode($_GET["redirected_from"]) . "&redirect=no'>" . htmlentities($_GET["redirected_from"]) . "</a>.</em></p>\n";
			
			$parsing_start = microtime(true);
			
			$rawRenderedSource = parse_page_source(file_get_contents($env->page_filename));
			$content .= $rawRenderedSource;
			
			if(!empty($pageindex->$page->tags)) {
				$content .= "<ul class='page-tags-display'>\n";
				foreach($pageindex->$page->tags as $tag)
					$content .= "<li><a href='?action=list-tags&tag=" . rawurlencode($tag) . "'>".htmlentities($tag)."</a></li>\n";
				$content .= "\n</ul>\n";
			}
			/*else
			{
				$content .= "<aside class='page-tags-display'><small><em>(No tags yet! Add some by <a href='?action=edit&page=" . rawurlencode($env->page) .  "'>editing this page</a>!)</em></small></aside>\n";
			}*/
			
			if($settings->show_subpages) {
				$subpages = get_object_vars(get_subpages($pageindex, $env->page));
				
				if(count($subpages) > 0) {
					$content .= "<hr />";
					$content .= "Subpages: ";
					foreach($subpages as $subpage => $times_removed) {
						if($times_removed <= $settings->subpages_display_depth) {
							$content .= "<a href='?action=view&page=" . rawurlencode($subpage) . "'>".htmlentities($subpage)."</a>, ";
						}
					}
					// Remove the last comma from the content
					$content = substr($content, 0, -2);
				}
			}
			
			$content .= "\n\t\t<!-- Took " . round((microtime(true) - $parsing_start) * 1000, 2) . "ms to parse page source -->\n";
			
			// Prevent indexing of this page if it's still within the noindex
			// time period
			if(isset($settings->delayed_indexing_time) and
				time() - $pageindex->{$env->page}->lastmodified < $settings->delayed_indexing_time)
				header("x-robots-tag: noindex");
			
			$settings->footer_message = "$env->page_safe was last edited by {$pageindex->{$env->page}->lasteditor} at " . date('h:ia T \o\n j F Y', $pageindex->{$env->page}->lastmodified) . ".</p>\n<p>" . $settings->footer_message; // Add the last edited time to the footer
			
			$mode = isset($_GET["mode"]) ? strtolower(trim($_GET["mode"])) : "normal";
			switch($mode) {
				case "contentonly":
					// Content only mode: Send only the content of the page
					exit($content);
				case "parsedsourceonly":
					// Parsed source only mode: Send only the raw rendered source
					exit($rawRenderedSource);
				case "printable":
					// Printable mode: Sends a printable version of the page
					exit(page_renderer::render_minimal($title, $content));
				case "normal":
				default:
					// Normal mode: Send a normal page
					exit(page_renderer::render_main($title, $content));
			}
		});
	}
]);




/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */

register_module([
	"name" => "Parsedown",
	"version" => "0.12.3",
	"author" => "Emanuil Rusev & Starbeamrainbowlabs",
	"description" => "An upgraded (now default!) parser based on Emanuil Rusev's Parsedown Extra PHP library (https://github.com/erusev/parsedown-extra), which is licensed MIT. Please be careful, as this module adds some weight to your installation.",
	"extra_data" => [
		/********** Parsedown versions **********
		 * Parsedown Core:		1.8.0-beta-7	*
		 * Parsedown Extra:		0.8.0-beta-1	*
		 * Parsedown Extreme:	0.1.6 *removed* *
		 ****************************************/
		"Parsedown.php" => "https://raw.githubusercontent.com/erusev/parsedown/1610e4747c88a53676f94f752b447f4eff03c28d/Parsedown.php",
		// "ParsedownExtra.php" => "https://raw.githubusercontent.com/erusev/parsedown-extra/91ac3ff98f0cea243bdccc688df43810f044dcef/ParsedownExtra.php",
		// "Parsedown.php" => "https://raw.githubusercontent.com/erusev/parsedown/3825db53a2be5d9ce54436a9cc557c6bdce1808a/Parsedown.php",
		"ParsedownExtra.php" => "https://raw.githubusercontent.com/erusev/parsedown-extra/352d03d941fc801724e82e49424ff409175261fd/ParsedownExtra.php"
		// Parsedown Extreme is causing PHP 7.4+ errors, and isn't rendering correctly with the security features we have turned on.
		// "ParsedownExtended.php" => "https://raw.githubusercontent.com/BenjaminHoegh/ParsedownExtended/8e1224e61a199cb513c47398353a27f6ba822da6/ParsedownExtended.php"
		// "ParsedownExtreme.php" => "https://raw.githubusercontent.com/BenjaminHoegh/parsedown-extreme/adae4136534ad1e4159fe04c74c4683681855b84/ParsedownExtreme.php"
	],
	"id" => "parser-parsedown",
	"code" => function() {
		global $settings;
		
		$parser = new PeppermintParsedown();
		$parser->setInternalLinkBase("?page=%s");
		add_parser("parsedown", function($source, $untrusted) use ($parser) {
			global $settings;
			$parser->setsafeMode($untrusted || $settings->all_untrusted);
			$parser->setMarkupEscaped($settings->clean_raw_html);
			$result = $parser->text($source);
			
			return $result;
		}, function($source) {
			global $version, $settings, $pageindex;
			$id_text = "$version|$settings->parser|$source";
			
			// Find template includes
			preg_match_all(
				'/\{\{\s*([^|]+)\s*(?:\|[^}]*)?\}\}/',
				$source, $includes
			);
			foreach($includes[1] as $include_pagename) {
				if(empty($pageindex->$include_pagename))
					continue;
				$id_text .= "|$include_pagename:" . parsedown_pagename_resolve(
					$pageindex->$include_pagename->lastmodified
				);
			}
			
			return str_replace(["+","/"], ["-","_"], base64_encode(hash(
				"sha256",
				$id_text,
				true
			)));
		});
		
		add_action("parsedown-render-ext", function() {
			global $settings, $env, $paths;
			
			if(!$settings->parser_ext_renderers_enabled) {
				http_response_code(403);
				header("content-type: image/png");
				imagepng(errorimage("Error: External diagram renderer support\nhas been disabled on $settings->sitename.\nTry contacting {$settings->admindetails_name}, $settings->sitename's administrator."));
				exit();
			}
			
			if(!isset($_GET["source"])) {
				http_response_code(400);
				header("content-type: image/png");
				imagepng(errorimage("Error: No source text \nspecified."));
				exit();
			}
			
			if(!isset($_GET["language"])) {
				http_response_code(400);
				header("content-type: image/png");
				imagepng(errorimage("Error: No external renderer \nlanguage specified."));
				exit();
			}
			
			$source = $_GET["source"];
			$language = $_GET["language"];
			
			if(!isset($settings->parser_ext_renderers->$language)) {
				$message = "Error: Unknown language {$_GET["language"]}.\nSupported languages:\n";
				foreach($settings->parser_ext_renderers as $language => $spec)
					$message .= "$spec->name ($language)\n";
					
				http_response_code(400);
				header("content-type: image/png");
				imagepng(errorimage(trim($message)));
				exit();
			}
			
			$renderer = $settings->parser_ext_renderers->$language;
			
			$cache_id = hash("sha256",
				hash("sha256", $language) . 
				hash("sha256", $source) . 
				($_GET["immutable_key"] ?? "")
			);
			
			$cache_file_location = "{$paths->cache_directory}/render_ext/$cache_id." . system_mime_type_extension($renderer->output_format);
			
			// If it exists on disk already, then serve that instead
			if(file_exists($cache_file_location)) {
				header("cache-control: public, max-age=31536000, immutable");
				header("content-type: $renderer->output_format");
				header("content-length: " . filesize($cache_file_location));
				header("x-cache: render_ext/hit");
				readfile($cache_file_location);
				exit();
			}
			
			if(!$settings->parser_ext_allow_anon && !$env->is_logged_in) {
				http_response_code(401);
				header("content-type: image/png");
				imagepng(errorimage(wordwrap("Error: You aren't logged in, that image hasn't yet been cached, and $settings->sitename does not allow anonymous users to invoke external renderers, so that image can't be generated right now. Try contacting $settings->admindetails_name, $settings->sitename's administrator (their details can be found at the bottom of every page).")));
				exit();
			}
			
			// Create the cache directory if doesn't exist already
			if(!file_exists(dirname($cache_file_location)))
				mkdir(dirname($cache_file_location), 0750, true);
			
			$cli_to_execute = $renderer->cli;
			
			$descriptors = [
				0 => null,		// stdin
				1 => null,		// stdout
				2 => tmpfile()	// stderr
			];
			
			switch ($renderer->cli_mode) {
				case "pipe":
					// Fill stdin with the input text
					$descriptors[0] = tmpfile();
					fwrite($descriptors[0], $source);
					fseek($descriptors[0], 0);
					
					// Pipe the output to be the cache file
					$descriptors[1] = fopen($cache_file_location, "wb+");
					break;
				
				case "substitution_pipe":
					// Update the command that we're going to execute
					$cli_to_execute = str_replace(
						"{input_text}",
						escapeshellarg($source),
						$cli_to_execute
					);
					
					// Set the descriptors
					$descriptors[0] = tmpfile();
					$descriptors[1] = fopen($cache_file_location, "wb+");
					break;
				
				case "file":
					$descriptors[0] = tmpfile();
					fwrite($descriptors[0], $source);
					$descriptors[1] = tmpfile();
					
					$cli_to_execute = str_replace(
						[ "{input_file}", "{output_file}" ],
						[
							escapeshellarg(stream_get_meta_data($descriptors[0])["uri"]),
							escapeshellarg($cache_file_location)
						],
						$cli_to_execute
					);
					break;
				
				default:
					http_response_code(503);
					header("cache-control: no-cache, no-store, must-revalidate");
					header("content-type: image/png");
					imagepng(errorimage("Error: Unknown external renderer mode '$renderer->cli_mode'.\nPlease contact $settings->admindetails_name, $settings->sitename's administrator."));
					exit();
					break;
			}
			
			if('\\' !== DIRECTORY_SEPARATOR) {
				// We're not on Windows, so we can use timeout to force-kill if it takes too long
				$cli_to_execute = "timeout {$settings->parser_ext_time_limit} $cli_to_execute";
			}
			
			$start_time = microtime(true);
			$process_handle = proc_open(
				$cli_to_execute,
				$descriptors,
				$pipes,
				null, // working directory
				null // environment variables
			);
			if(!is_resource($process_handle)) {
				fclose($descriptors[0]);
				fclose($descriptors[1]);
				fclose($descriptors[2]);
				
				if(file_exists($cache_file_location)) unlink($cache_file_location);
				
				http_response_code(503);
				header("cache-control: no-cache, no-store, must-revalidate");
				header("content-type: image/png");
				imagepng(errorimage("Error: Failed to start external renderer.\nIs $renderer->name installed?"));
				exit();
			}
			// Wait for it to exit
			$exit_code = proc_close($process_handle);
			
			fclose($descriptors[0]);
			fclose($descriptors[1]);
			
			$time_taken = round((microtime(true) - $start_time) * 1000, 2);
			
			if($exit_code !== 0 || !file_exists($cache_file_location)) {
				fseek($descriptors[2], 0);
				$error_details = stream_get_contents($descriptors[2]);
				// Delete the cache file, which is guaranteed to exist because
				// we pre-emptively create it above
				if(file_exists($cache_file_location)) unlink($cache_file_location);
				
				http_response_code(503);
				header("content-type: image/png");
				imagepng(errorimage(
					"Error: The external renderer ($renderer->name)\nexited with code $exit_code,\nor potentially did not create the output file.\nDetails:\n" . wordwrap($error_details)
				));
				exit();
			}
			
			header("cache-control: public, max-age=31536000, immutable");
			header("content-type: $renderer->output_format");
			header("content-length: " . filesize($cache_file_location));
			header("x-cache: render_ext/miss, renderer took {$time_taken}ms");
			readfile($cache_file_location);
		});
		
		/*
 		 * ███████ ████████  █████  ████████ ██ ███████ ████████ ██  ██████ ███████
 		 * ██         ██    ██   ██    ██    ██ ██         ██    ██ ██      ██
 		 * ███████    ██    ███████    ██    ██ ███████    ██    ██ ██      ███████
 		 *      ██    ██    ██   ██    ██    ██      ██    ██    ██ ██           ██
 		 * ███████    ██    ██   ██    ██    ██ ███████    ██    ██  ██████ ███████
 		 */
		statistic_add([
			"id" => "wanted-pages",
			"name" => "Wanted Pages",
			"type" => "page",
			"update" => function($old_stats) {
				global $pageindex, $env;
				
				$result = new stdClass(); // completed, value, state
				$pages = [];
				foreach($pageindex as $pagename => $pagedata) {
					if(!file_exists($env->storage_prefix . $pagedata->filename))
						continue;
					$page_content = file_get_contents($env->storage_prefix . $pagedata->filename);
					
					$page_links = PeppermintParsedown::extract_page_names($page_content);
					foreach($page_links as $linked_page) {
						// We're only interested in pages that don't exist
						if(!empty($pageindex->$linked_page)) continue;
						
						if(empty($pages[$linked_page]))
							$pages[$linked_page] = 0;
						$pages[$linked_page]++;
					}
				}
				
				arsort($pages);
				
				$result->value = $pages;
				$result->completed = true;
				return $result;
			},
			"render" => function($stats_data) {
				$result = "<h2>$stats_data->name</h2>\n";
				$result .= "<table class='wanted-pages'>\n";
				$result .= "\t<tr><th>Page Name</th><th>Linking Pages</th></tr>\n";
				foreach($stats_data->value as $pagename => $linking_pages) {
					$result .= "\t<tr><td>$pagename</td><td>$linking_pages</td></tr>\n";
				}
				$result .= "</table>\n";
				return $result;
			}
		]);
		statistic_add([
			"id" => "orphan-pages",
			"name" => "Orphan Pages",
			"type" => "page-list",
			"update" => function($old_stats) {
				global $pageindex, $env;
				
				$result = new stdClass(); // completed, value, state
				$pages = [];
				foreach($pageindex as $pagename => $pagedata) {
					if(!file_exists($env->storage_prefix . $pagedata->filename))
						continue;
					$page_content = file_get_contents($env->storage_prefix . $pagedata->filename);
					
					$page_links = PeppermintParsedown::extract_page_names($page_content);
					
					foreach($page_links as $linked_page) {
						// We're only interested in pages that exist
						if(empty($pageindex->$linked_page)) continue;
						
						$pages[$linked_page] = true;
					}
				}
				
				$orphaned_pages = [];
				foreach($pageindex as $pagename => $page_data) {
					if(empty($pages[$pagename]))
						$orphaned_pages[] = $pagename;
				}
				
				$sorter = new Collator("");
				$sorter->sort($orphaned_pages);
				
				$result->value = $orphaned_pages;
				$result->completed = true;
				return $result;
			}
		]);
		statistic_add([
			"id" => "most-linked-to-pages",
			"name" => "Most Linked-To Pages",
			"type" => "page",
			"update" => function($old_stats) {
				global $pageindex, $env;
				
				$result = new stdClass(); // completed, value, state
				$pages = [];
				foreach($pageindex as $pagename => $pagedata) {
					if(!file_exists($env->storage_prefix . $pagedata->filename))
						continue;
					$page_content = file_get_contents($env->storage_prefix . $pagedata->filename);
					
					$page_links = PeppermintParsedown::extract_page_names($page_content);
					
					foreach($page_links as $linked_page) {
						// We're only interested in pages that exist
						if(empty($pageindex->$linked_page)) continue;
						
						if(empty($pages[$linked_page]))
							$pages[$linked_page] = 0;
						$pages[$linked_page]++;
					}
				}
				
				arsort($pages);
				
				$result->value = $pages;
				$result->completed = true;
				return $result;
			},
			"render" => function($stats_data) {
				global $pageindex;
				$result = "<h2>$stats_data->name</h2>\n";
				$result .= "<table class='most-linked-to-pages'>\n";
				$result .= "\t<tr><th>Page Name</th><th>Linking Pages</th></tr>\n";
				foreach($stats_data->value as $pagename => $link_count) {
					$pagename_display = !empty($pageindex->$pagename->redirect) && $pageindex->$pagename->redirect ? "<em>$pagename</em>" : $pagename;
					$result .= "\t<tr><td><a href='?page=" . rawurlencode($pagename) . "'>$pagename_display</a></td><td>$link_count</td></tr>\n";
				}
				$result .= "</table>\n";
				return $result;
			}
		]);
		
		add_help_section("20-parser-default", "Editor Syntax",
		"<p>$settings->sitename's editor uses an extended version of <a href='http://parsedown.org/'>Parsedown</a> to render pages, which is a fantastic open source Github flavoured markdown parser. You can find a quick reference guide on Github flavoured markdown <a href='https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet'>here</a> by <a href='https://github.com/adam-p/'>adam-p</a>, or if you prefer a book <a href='https://www.gitbook.com/book/roachhd/master-markdown/details'>Mastering Markdown</a> by KB is a good read, and free too!</p>
		<h3>Tips</h3>
		<ul>
			<li>Put 2 spaces at the end of a line to add a soft line break. Leave a blank line to add a head line break (i.e. a new paragraph).</li>
			<li>If you don't like the default id given to a header, you can add a custom one instead. Put it in curly braces after the heading name like this: <code># Heading Name {#HeadingId}</code>. Then you can link to like like this: <code>[[Page name#HeadingId}]]</code>. You can also link to a heading id on the current page by omitting the page name: <code>[[#HeadingId]]</code>. Finally, a heading id is automatically generated for every heading by default. Take the heading name, make it lowercase, and replace the spaces with dashes <code>.</code>, and that's the heading ID that you can link to (although sometimes some special characters are removed).</li>
		</ul>
		<h3>Extra Syntax</h3>
		<p>$settings->sitename's editor also supports some extra custom syntax, some of which is inspired by <a href='https://mediawiki.org/'>Mediawiki</a>.
		<table>
			<tr><th style='width: 40%'>Type this</th><th style='width: 20%'>To get this</th><th>Comments</th></th>
			<tr><td><code>[[Internal link]]</code></td><td><a href='?page=Internal%20link'>Internal Link</a></td><td>An internal link.</td></tr>
			<tr><td><code>[[Internal link|Display Text]]</code></td><td><a href='?page=Internal%20link'>Display Text</a></td><td>An internal link with some display text.</td></tr>
			<tr><td><code>![Alt text](http://example.com/path/to/image.png | 256x256 | right)</code></td><td><img src='http://example.com/path/to/image.png' alt='Alt text' style='float: right; max-width: 256px; max-height: 256px;' /></td><td>An image floating to the right of the page that fits inside a 256px x 256px box, preserving aspect ratio.</td></tr>
			<tr><td><code>![Alt text](http://example.com/path/to/image.png | 256x256 | caption)</code></td><td><figure><img src='http://example.com/path/to/image.png' alt='Alt text' style='max-width: 256px; max-height: 256px;' /><figcaption>Alt text</figcaption></figure></td><td>An image with a caption that fits inside a 256px x 256px box, preserving aspect ratio. The presence of the word <code>caption</code> in the regular braces causes the alt text to be taken and displayed below the image itself.</td></tr>
			<tr><td><code>![Alt text](Files/Cheese.png)</code></td><td><img src='index.php?action=preview&page=Files/Cheese.png' alt='Alt text' style='' /></td><td>An example of the short url syntax for images. Simply enter the page name of an image (or video / audio file), and Pepperminty Wiki will sort out the url for you.</td></tr>
			<tr><td><code>Some text ==marked text== more text</code></td><td>Some text <mark>marked text</mark> more text</td><td>Marked / highlighted text</td></tr>
			<tr><td><code>Some text^superscript^ more text</code></td><td>Some text<sup>superscript</sup> more text</td><td>Superscript</td></tr>
			<tr><td><code>Some text~subscript~ more text</code></td><td>Some text<sub>subscript</sub> more text</td><td>Subscript (note that we use a single tilda <code>~</code> here - a double results in strikethrough text instead)</td></tr>
			<tr><td><code>[ ] Unticked checkbox</code></td><td><input type='checkbox' disabled /> Unticked checkbox</td><td>An unticked checkbox. Must be at the beginning of a line or directly after a list item (e.g. <code> - </code> or <code>1. </code>).</td></tr>
			<tr><td><code>[x] Ticked checkbox</code></td><td><input type='checkbox' checked='checked' disabled /> Ticked checkbox</td><td>An ticked checkbox. The same rules as unticked checkboxes apply here too.</td></tr>
			<tr><td><code>some text &gt;!spoiler text!&lt; more text</code></td><td>some text <a class='spoiler' href='#spoiler-example' id='spoiler-example'>spoiler text</a> more text</td><td>A spoiler. Users must click it to reveal the content hidden beneath.</td></tr>
			<tr><td><code>some text ||spoiler text|| more text</code></td><td>some text <a class='spoiler' href='#spoiler-example-2' id='spoiler-example-2'>spoiler text</a> more text</td><td>Alternative spoiler syntax inspired by <a href='https://support.discord.com/hc/en-us/articles/360022320632-Spoiler-Tags-'>Discord</a>.</td></tr>
			<tr><td><code>[__TOC__]</code></td><td></td><td>An automatic table of contents. Note that this must be on a line by itself with no text before or after it on that line for it to work.</td></tr>
		</table>
		<p>Note that the all image image syntax above can be mixed and matched to your liking. The <code>caption</code> option in particular must come last or next to last.</p>
		<h4>Templating</h4>
		<p>$settings->sitename also supports including one page in another page as a <em>template</em>. The syntax is very similar to that of Mediawiki. For example, <code>{{Announcement banner}}</code> will include the contents of the \"Announcement banner\" page, assuming it exists.</p>
		<p>You can also use variables. Again, the syntax here is very similar to that of Mediawiki - they can be referenced in the included page by surrrounding the variable name in triple curly braces (e.g. <code>{{{Announcement text}}}</code>), and set when including a page with the bar syntax (e.g. <code>{{Announcement banner | importance = high | text = Maintenance has been planned for tonight.}}</code>). Currently the only restriction in templates and variables is that you may not include a closing curly brace (<code>}</code>) in the page name, variable name, or value.</p>
		<h5>Special Variables</h5>
		<p>$settings->sitename also supports a number of special built-in variables. Their syntax and function are described below:</p>
		<table>
			<tr><th>Type this</th><th>To get this</th></tr>
			<tr><td><code>{{{@}}}</code></td><td>Lists all variables and their values in a table.</td></tr>
			<tr><td><code>{{{#}}}</code></td><td>Shows a 'stack trace', outlining all the parent includes of the current page being parsed.</td></tr>
			<tr><td><code>{{{~}}}</code></td><td>Outputs the requested page's name.</td></tr>
			<tr><td><code>{{{*}}}</code></td><td>Outputs a comma separated list of all the subpages of the current page.</td></tr>
			<tr><td><code>{{{+}}}</code></td><td>Shows a gallery containing all the files that are sub pages of the current page.</td></tr>
		</table>
		<p>Note that a page <em>does not</em> need to be included as a template to use these variables.");
		if($settings->parser_ext_renderers_enabled) {
			$doc_help = "<p>$settings->sitename supports external renderers. External renderers take the content of a code fence block, like this:</p>
			<pre><code>```language_code
Insert text here
```</code></pre>
<p>...and render it to an image. This is based on the <code>language_code</code> specified, as is done in the above example. Precisely what the output of a external renderer is depends on the external renderers defined, but $settings->sitename currently has the following external renderers registered:</p>
<table>
<tr><th>Name</th><th>Language code</th><th>Description</th><th>Reference Link</th></tr>
";
			
			foreach($settings->parser_ext_renderers as $code => $renderer) {
				$row = array_map(function($value) { return htmlentities($value, ENT_COMPAT|ENT_HTML5); }, [
					$renderer->name,
					$code,
					$renderer->description,
					$renderer->url
				]);
				$row[3] = "<a href='$row[3]'>&#x1f517;</a>";
				$doc_help .= "<tr><td>".implode("</td><td>", $row)."</td></tr>\n";
			}
			$doc_help .= "</table>
			$settings->admindetails_name can register more external renderers - see the <a href='https://starbeamrainbowlabs.com/labs/peppermint/__nightdocs/06.8-External-Renderers.html'>documentation</a> for more information.</p>";
			
			add_help_section("24-external-renderers", "External Renderers", $doc_help);
		}
	}
]);

require_once("$paths->extra_data_directory/parser-parsedown/Parsedown.php");
require_once("$paths->extra_data_directory/parser-parsedown/ParsedownExtra.php");
// require_once("$paths->extra_data_directory/parser-parsedown/ParsedownExtended.php");

/**
 * Attempts to 'auto-correct' a page name by trying different capitalisation
 * combinations.
 * 
 * @param	string	$pagename	The page name to auto-correct.
 * @return	string	The auto-corrected page name.
 */
function parsedown_pagename_resolve($pagename) {
	global $pageindex;
	
	// If the page doesn't exist, check varying different
	// capitalisations to see if it exists under some variant.
	if(!empty($pageindex->$pagename))
		return $pagename;
	
	$pagename = ucfirst($pagename);
	if(!empty($pageindex->$pagename))
		return $pagename;
	
	$pagename = ucwords($pagename);
	return $pagename;
}

/*
 * ██████   █████  ██████  ███████ ███████ ██████   ██████  ██     ██ ███    ██
 * ██   ██ ██   ██ ██   ██ ██      ██      ██   ██ ██    ██ ██     ██ ████   ██
 * ██████  ███████ ██████  ███████ █████   ██   ██ ██    ██ ██  █  ██ ██ ██  ██
 * ██      ██   ██ ██   ██      ██ ██      ██   ██ ██    ██ ██ ███ ██ ██  ██ ██
 * ██      ██   ██ ██   ██ ███████ ███████ ██████   ██████   ███ ███  ██   ████
 * 
 * ███████ ██   ██ ████████ ███████ ███    ██ ███████ ██  ██████  ███    ██ ███████
 * ██       ██ ██     ██    ██      ████   ██ ██      ██ ██    ██ ████   ██ ██
 * █████     ███      ██    █████   ██ ██  ██ ███████ ██ ██    ██ ██ ██  ██ ███████
 * ██       ██ ██     ██    ██      ██  ██ ██      ██ ██ ██    ██ ██  ██ ██      ██
 * ███████ ██   ██    ██    ███████ ██   ████ ███████ ██  ██████  ██   ████ ███████
*/
/**
 * The Peppermint-flavoured Parsedown parser.
 */
class PeppermintParsedown extends ParsedownExtra
{
	/**
	 * A long random and extremely unlikely string to identify where we want
	 * to put a table of contents.
	 * Hopefully nobody is unlucky enough to include this in a page...!
	 * @var string
	 */
	private const TOC_ID = "█yyZiy9c9oHVExhVummYZId_dO9-fvaGFvgQirEapxOtaL-s7WnK34lF9ObBoQ0EH2kvtd6VKcAL2█";
	
	/**
	 * The base directory with which internal links will be resolved.
	 * @var string
	 */
	private $internalLinkBase = "./%s";
	
	/**
	 * The parameter stack. Used for recursive templating.
	 * @var array
	 */
	protected $paramStack = [];
	
	/**
	 * Creates a new Peppermint Parsedown instance.
	 */
	function __construct()
	{
        parent::__construct();
		
		array_unshift($this->BlockTypes["["], "TableOfContents");
		array_unshift($this->BlockTypes["["], "OneBox");
		
		// Prioritise our internal link parsing over the regular link parsing
		$this->addInlineType("[", "InternalLink", true);
		// Prioritise the checkbox handling - this is fine 'cause it doesn't step on InternalLink's toes
		$this->addInlineType("[", "Checkbox", true);
		// Prioritise our image parser over the regular image parser
		$this->addInlineType("!", "ExtendedImage", true);
		
		$this->addInlineType("{", "Template");
		$this->addInlineType("=", "Mark");
		$this->addInlineType("^", "Superscript");
		$this->addInlineType("~", "Subscript");
		
		$this->addInlineType(">", "Spoiler", true);
		$this->addInlineType("|", "Spoiler", true);
	}
	
	/**
	 * Helper method to add an inline type.
	 * @param string  $char          The char to match against.
	 * @param string  $function_id   The name bit of the function to call.
	 * @param boolean $before_others Whether to prioritise this function over other existing ones (default: false).
	 */
	protected function addInlineType(string $char, string $function_id, bool $before_others = false) {
		if(mb_strlen($char) > 1)
			throw new Exception("Error: '$char' is longer than a single character.");
		if(!isset($this->InlineTypes[$char]) or !is_array($this->InlineTypes[$char]))
			$this->InlineTypes[$char] = [];
		
		if(strpos($this->inlineMarkerList, $char) === false)
			$this->inlineMarkerList .= $char;
		
		if(!$before_others)
			$this->InlineTypes[$char][] = $function_id;
		else
			array_unshift($this->InlineTypes[$char], $function_id);
	}
	
	/*
	 * Override the text method here to insert the table of contents after
	 * rendering has been completed
	 */
	public function text($text) {
		$result = parent::text($text);
		$toc_html = $this->generateTableOfContents();
		$result = str_replace(self::TOC_ID, $toc_html, $result);
		
		return $result;
	}
	
	
	/*
	 * ████████ ███████ ███    ███ ██████  ██       █████  ████████ ██ ███    ██  ██████
	 *    ██    ██      ████  ████ ██   ██ ██      ██   ██    ██    ██ ████   ██ ██
	 *    ██    █████   ██ ████ ██ ██████  ██      ███████    ██    ██ ██ ██  ██ ██   ███
	 *    ██    ██      ██  ██  ██ ██      ██      ██   ██    ██    ██ ██  ██ ██ ██    ██
	 *    ██    ███████ ██      ██ ██      ███████ ██   ██    ██    ██ ██   ████  ██████
	 * %templating
	 */
	/**
	 * Parses templating definitions.
	 * @param string $fragment The fragment to parse it out from.
	 */
	protected function inlineTemplate($fragment)
	{
		global $env, $pageindex;
		
		// Variable parsing
		if(preg_match("/\{\{\{([^}]+)\}\}\}/", $fragment["text"], $matches))
		{
			$params = [];
			if(!empty($this->paramStack))
			{
				$stackEntry = array_slice($this->paramStack, -1)[0];
				$params = !empty($stackEntry) ? $stackEntry["params"] : null;
			}
			
			$variableKey = trim($matches[1]);
			
			$variableValue = false;
			switch ($variableKey) {
				case "@": // Lists all variables and their values
					if(!empty($params)) {
						$variableValue = "<table>
	<tr><th>Key</th><th>Value</th></tr>\n";
						foreach($params as $key => $value) {
							$variableValue .= "\t<tr><td>" . $this->escapeText($key) . "</td><td>" . $this->escapeText($value) . "</td></tr>\n";
						}
						$variableValue .= "</table>";
					}
					else {
						$variableValue = "<em>(no variables are currently defined)</em>";
					}
					break;
				case "#": // Shows a stack trace
					$variableValue = "<ol start=\"0\">\n";
					$variableValue .= "\t<li>$env->page</li>\n";
					foreach($this->paramStack as $curStackEntry) {
						$variableValue .= "\t<li>" . $curStackEntry["pagename"] . "</li>\n";
					}
					$variableValue .= "</ol>\n";
					break;
				case "~": // Show requested page's name
					if(!empty($this->paramStack))
						$variableValue = $this->escapeText($env->page);
					break;
				case "*": // Lists subpages
					$subpages = get_subpages($pageindex, $env->page);
					$variableValue = [];
					foreach($subpages as $pagename => $depth)
					{
						$variableValue[] = $pagename;
					}
					$variableValue = implode(", ", $variableValue);
					if(strlen($variableValue) === 0)
						$variableValue = "<em>(none yet!)</em>";
					break;
				case "+": // Shows a file gallery for subpages with files
					// If the upload module isn't present, then there's no point
					// in checking for uploaded files
					if(!module_exists("feature-upload"))
						break;
					
					$variableValue = [];
					$subpages = get_subpages($pageindex, $env->page);
					foreach($subpages as $pagename => $depth)
					{
						// Make sure that this is an uploaded file
						if(!$pageindex->$pagename->uploadedfile)
							continue;
						
						$mime_type = $pageindex->$pagename->uploadedfilemime;
						
						$previewSize = 300;
						$previewUrl = "?action=preview&size=$previewSize&page=" . rawurlencode($pagename);
						
						$previewType = substr($mime_type, 0, strpos($mime_type, "/"));
						if($mime_type == "application/pdf")
							$previewType = "pdf";
						
						$previewHtml = "";
						switch($previewType)
						{
							case "video":
								$previewHtml .= "<video src='$previewUrl' controls preload='metadata'>".$this->escapeText($pagename)."</video>\n";
								break;
							case "audio":
								$previewHtml .= "<audio src='$previewUrl' controls preload='metadata'>".$this->escapeText($pagename)."</audio>\n";
								break;
							case "pdf":
								$previewHtml .= "<object type='application/pdf' data='$previewUrl' style='width: {$previewStyle}px;'></object>";
								break;
							case "application":
							case "image":
							default:
								$previewHtml .= "<img src='$previewUrl' />\n";
								break;
						}
						$previewHtml = "<a href='?page=" . rawurlencode($pagename) . "'>$previewHtml$pagename</a>";
						
						$variableValue[$pagename] = "<li style='min-width: $previewSize" . "px; min-height: $previewSize" . "px;'>$previewHtml</li>";
					}
					
					if(count($variableValue) === 0)
						$variableValue["default"] = "<li><em>(No files found)</em></li>\n";
					$variableValue = implode("\n", $variableValue);
					$variableValue = "<ul class='file-gallery'>$variableValue</ul>";
					break;
			}
			if(isset($params[$variableKey]))
				$variableValue = $this->escapeText($params[$variableKey]);
			
			if($variableValue !== false)
			{
				return [
					"extent" => strlen($matches[0]),
					"element" => [
						"name" => "span",
						"attributes" => [
							"class" => "template-var-value"
						],
						// rawHtml is fine here 'cause we escape above
						// Note also that we *must* return some sort of element here: we can't just return rawHtml directly. It needs to be a property of an element.
						"rawHtml" => $variableValue
					]
				];
			}
		}
		else if(preg_match("/\{\{([^}]+)\}\}/", $fragment["text"], $matches))
		{
			$templateElement = $this->templateHandler($matches[1]);
			
			if(!empty($templateElement))
			{
				return [
					"extent" => strlen($matches[0]),
					"element" => $templateElement
				];
			}
		}
	}
	
	/**
	 * Handles parsing out templates - recursively - and the parameter stack associated with it.
	 * @param	string	$source		The source string to process.
	 * @return	array	The parsed result
	 */
	protected function templateHandler($source)
	{
		global $pageindex, $env;
		
		
		$parts = preg_split("/\\||¦/", trim($source, "{}"));
		$parts = array_map("trim", $parts);
		
		// Extract the name of the template page
		$templatePagename = array_shift($parts);
		// If the page that we are supposed to use as the template doesn't
		// exist, then there's no point in continuing.
		if(empty($pageindex->$templatePagename))
			return false;
		
		// Parse the parameters
		$params = [];
		$i = 0;
		foreach($parts as $part)
		{
			if(strpos($part, "=") !== false)
			{
				// This param contains an equals sign, so it's a named parameter
				$keyValuePair = explode("=", $part, 2);
				$keyValuePair = array_map("trim", $keyValuePair);
				$params[$keyValuePair[0]] = $keyValuePair[1];
			}
			else
			{
				// This isn't a named parameter
				$params["$i"] = trim($part);
				
				$i++;
			}
		}
		// Add the parsed parameters to the parameter stack
		$this->paramStack[] = [
			"pagename" => $templatePagename,
			"params" => $params
		];
		
		$templateFilePath = $env->storage_prefix . $pageindex->$templatePagename->filename;
		
		// We use linesElements here to avoid resetting $this->DefinitionData (which is done in ->textElements)
		// Consequently, we have to do what textElements does directly
		$parsedTemplateSource = $this->linesElements(explode("\n",
			trim(str_replace(["\r\n", "\r"], "\n", file_get_contents($templateFilePath)), "\n")
		));
		// Render it out. Important to preserve scope.
		$parsedTemplateSource = $this->elements($parsedTemplateSource);
		
		// HACK: Find/replace to ensure variables are inserted inside HTML. Note this does NOTE support special variables inside HTML - only simple ones.
		// This would cause issues if we later allow variables to be unset.
		foreach($params as $param_key => $param_value) {
			$parsedTemplateSource = str_replace("{{{".$param_key."}}}", $param_value, $parsedTemplateSource);
		}
		
		// Remove the parsed parameters from the stack
		array_pop($this->paramStack);
		
		return [
			"name" => "div",
			"element" => [
				"rawHtml" => $parsedTemplateSource,
			],
			"attributes" => [
				"class" => "template"
			]
		];
	}
	
	
	/*
	 *  ██████ ██   ██ ███████  ██████ ██   ██ ██████   ██████  ██   ██
	 * ██      ██   ██ ██      ██      ██  ██  ██   ██ ██    ██  ██ ██
	 * ██      ███████ █████   ██      █████   ██████  ██    ██   ███
	 * ██      ██   ██ ██      ██      ██  ██  ██   ██ ██    ██  ██ ██
	 *  ██████ ██   ██ ███████  ██████ ██   ██ ██████   ██████  ██   ██
	 * %checkbox
	 */
	protected function inlineCheckbox($fragment) {
		// We're not interested if it's not at the beginning of a line
		if(strpos($fragment["context"], $fragment["text"]) !== 0)
			return;
		
		// If it doesn't match, then we're not interested
		if(preg_match('/\[([ x])\]/u', $fragment["text"], $matches) !== 1)
			return;
		
		$checkbox_content = $matches[1];
		
		$result = [
			"extent" => 3,
			"element" => [
				"name" => "input",
				"attributes" => [
					"type" => "checkbox",
					"disabled" => "disabled"
				]
			]
		];
		
		if($checkbox_content === "x")
			$result["element"]["attributes"]["checked"] = "checked";
		
		return $result;
	}
	
	
	/*
 	 * ███    ███  █████  ██████  ██   ██ ███████ ██████
 	 * ████  ████ ██   ██ ██   ██ ██  ██  ██      ██   ██
 	 * ██ ████ ██ ███████ ██████  █████   █████   ██   ██
 	 * ██  ██  ██ ██   ██ ██   ██ ██  ██  ██      ██   ██
 	 * ██      ██ ██   ██ ██   ██ ██   ██ ███████ ██████
 	 * 
 	 * ████████ ███████ ██   ██ ████████
 	 *    ██    ██       ██ ██     ██
 	 *    ██    █████     ███      ██
 	 *    ██    ██       ██ ██     ██
 	 *    ██    ███████ ██   ██    ██
	 * %markedtext
 	 */
	protected function inlineMark($fragment) {
		// A question mark makes the PCRE 1-or-more + there lazy instead of greedy.
		// Ref https://www.rexegg.com/regex-quantifiers.html#greedytrap
		if(preg_match('/==([^=]+?)==/', $fragment["text"], $matches) !== 1)
			return;
		
		$marked_text = $matches[1];
		
		$result = [
			"extent" => strlen($matches[0]),
			"element" => [
				"name" => "mark",
				"handler" => [
					"function" => "lineElements",
					"argument" => $marked_text,
					"destination" => "elements"
				]
			]
		];
		return $result;
	}
	
	
	/*
	 *  ██████ ██    ██ ██████      ██  ██████ ██    ██ ██████  ███████ ██████
	 * ██      ██    ██ ██   ██    ██  ██      ██    ██ ██   ██ ██      ██   ██
	 * ███████ ██    ██ ██████    ██   ███████ ██    ██ ██████  █████   ██████
	 *      ██ ██    ██ ██   ██  ██         ██ ██    ██ ██      ██      ██   ██
	 * ██████   ██████  ██████  ██     ██████   ██████  ██      ███████ ██   ██
	 * 
	 *  ██████  ██████ ██████  ██ ██████  ████████
	 * ██      ██      ██   ██ ██ ██   ██    ██
	 * ███████ ██      ██████  ██ ██████     ██
	 *      ██ ██      ██   ██ ██ ██         ██
	 * ██████   ██████ ██   ██ ██ ██         ██
	 * %subsuperscript
	 */
	protected function inlineSuperscript($fragment) {
		if(preg_match('/\^([^^]+?)\^/', $fragment["text"], $matches) !== 1)
			return;
		
		$superscript_text = $matches[1];
		
		$result = [
			"extent" => strlen($matches[0]),
			"element" => [
				"name" => "sup",
				"handler" => [
					"function" => "lineElements",
					"argument" => $superscript_text,
					"destination" => "elements"
				]
			]
		];
		return $result;
	}
	protected function inlineSubscript($fragment) {
		if(preg_match('/~([^~]+?)~/', $fragment["text"], $matches) !== 1)
			return;
		
		$subscript_text = $matches[1];
		
		$result = [
			"extent" => strlen($matches[0]),
			"element" => [
				"name" => "sub",
				"handler" => [
					"function" => "lineElements",
					"argument" => $subscript_text,
					"destination" => "elements"
				]
			]
		];
		return $result;
	}
	
	
	/*
	 * ███████ ██████   ██████  ██ ██      ███████ ██████
	 * ██      ██   ██ ██    ██ ██ ██      ██      ██   ██
	 * ███████ ██████  ██    ██ ██ ██      █████   ██████
	 *      ██ ██      ██    ██ ██ ██      ██      ██   ██
	 * ███████ ██       ██████  ██ ███████ ███████ ██   ██
	 * %spoiler
	 */
	protected function inlineSpoiler($fragment) {
		if(preg_match('/(?:\|\||>!)([^|]+?)(?:\|\||!<)/', $fragment["text"], $matches) !== 1)
			return;
		
		$spoiler_text = $matches[1];
		$id = "spoiler-".crypto_id(24);
		
		$result = [
			"extent" => strlen($matches[0]),
			"element" => [
				"name" => "a",
				"attributes" => [
					"id" => $id,
					"class" => "spoiler",
					"href" => "#$id"
				],
				"handler" => [
					"function" => "lineElements",
					"argument" => $spoiler_text,
					"destination" => "elements"
				]
			]
		];
		return $result;
	}
	
	
	/*
	 * ██ ███    ██ ████████ ███████ ██████  ███    ██  █████  ██
	 * ██ ████   ██    ██    ██      ██   ██ ████   ██ ██   ██ ██
	 * ██ ██ ██  ██    ██    █████   ██████  ██ ██  ██ ███████ ██
	 * ██ ██  ██ ██    ██    ██      ██   ██ ██  ██ ██ ██   ██ ██
	 * ██ ██   ████    ██    ███████ ██   ██ ██   ████ ██   ██ ███████
	 * 
	 * ██      ██ ███    ██ ██   ██ ███████
	 * ██      ██ ████   ██ ██  ██  ██
	 * ██      ██ ██ ██  ██ █████   ███████
	 * ██      ██ ██  ██ ██ ██  ██       ██
	 * ███████ ██ ██   ████ ██   ██ ███████
	 * %internallinks
	 */
	/**
	 * Parses internal links
	 * @param  string $fragment The fragment to parse.
	 */
	protected function inlineInternalLink($fragment)
	{
		global $pageindex, $env;
		
		if(preg_match('/^\[\[([^\]]*?)\]\]([^\s!?",;.()\[\]{}*=+\/]*)/u', $fragment["text"], $matches) === 1) {
			// 1: Parse parameters out
			// -------------------------------
			$link_page = str_replace(["\r", "\n"], [" ", " "], trim($matches[1]));
			$display = $link_page . trim($matches[2]);
			if(strpos($matches[1], "|") !== false || strpos($matches[1], "¦") !== false)
			{
				// We have a bar character
				$parts = preg_split("/\\||¦/", $matches[1], 2);
				$link_page = trim($parts[0]); // The page to link to
				$display = trim($parts[1]); // The text to display
			}
			
			
			// 2: Parse the hash out
			// -------------------------------
			$hash_code = "";
			if(strpos($link_page, "#") !== false)
			{
				// We want to link to a subsection of a page
				$hash_code = substr($link_page, strpos($link_page, "#") + 1);
				$link_page = substr($link_page, 0, strpos($link_page, "#"));
				
				// If $link_page is empty then we want to link to the current page
				if(strlen($link_page) === 0)
					$link_page = $env->page;
			}
			
			
			// 3: Page name auto-correction
			// -------------------------------
			$is_interwiki_link = module_exists("feature-interwiki-links") && is_interwiki_link($link_page);
			// Try different variants on the pagename to try and get it to 
			// match something automagically
			if(!$is_interwiki_link && empty($pageindex->$link_page))
				$link_page = parsedown_pagename_resolve($link_page);
			
			
			// 4: Construct the full url
			// -------------------------------
			$link_url = null;
			// If it's an interwiki link, then handle it as such
			if($is_interwiki_link)
				$link_url = interwiki_get_pagename_url($link_page);
			
			// If it isn't (or it failed), then try it as a normal link instead
			if(empty($link_url)) {
				$link_url = str_replace(
					"%s", rawurlencode($link_page),
					$this->internalLinkBase
				);
				// We failed to handle it as an interwiki link, so we should 
				// tell everyone that
				$is_interwiki_link = false;
			}
			
			// 5: Construct the title
			// -------------------------------
			$title = $link_page;
			if($is_interwiki_link)
				$title = interwiki_pagename_resolve($link_page)->name . ": " . interwiki_pagename_parse($link_page)[1] . " (Interwiki)";
			
			if(strlen($hash_code) > 0)
				$link_url .= "#$hash_code";
			
			
			// 6: Result encoding
			// -------------------------------
			$result = [
				"extent" => strlen($matches[0]),
				"element" => [
					"name" => "a",
					"text" => $display,
					
					"attributes" => [
						"href" => $link_url,
						"title" => $title
					]
				]
			];
			
			// Attach some useful classes based on how we handled it
			$class_list = [];
			// Interwiki links can never be redlinks
			if(!$is_interwiki_link && empty($pageindex->{makepathsafe($link_page)}))
				$class_list[] = "redlink";
			if($is_interwiki_link)
				$class_list[] = "interwiki_link";
			
			$result["element"]["attributes"]["class"] = implode(" ", $class_list);
			
			return $result;
		}
	}
	
	/*
	███  █████  ███  ██        ██ ██████     ███    ███ ██████  ██
	██  ██   ██  ██ ██        ██  ██   ██    ████  ████ ██   ██  ██
	██  ███████  ██ ██       ██   ██████     ██ ████ ██ ██   ██  ██
	██  ██   ██  ██ ██      ██    ██   ██    ██  ██  ██ ██   ██  ██
	███ ██   ██ ███  ██ ██ ██     ██████  ██ ██      ██ ██████  ██
	*/
	// This wraps the native [display text](url) syntax
	protected function inlineLink($fragment) {
		global $settings;
		
		// If this feature is disabled, defer to the parent implementation unconditionally
		if(!$settings->parser_mangle_external_links)
			return parent::inlineLink($fragment);
		
		// Extract the URL from the internal link. If it fails defer to the parent function
		// 1 = display text, 2 = url
		if(preg_match("/^\[([^[\]]+?)\]\(\s*([^()]+)\s*\)/", $fragment["text"], $matches) !== 1)
			return parent::inlineLink($fragment);
		
		// Check the URL. If it doesn't match our *exact* format, then it doesn't happen.
		if(!is_string($matches[2]) || preg_match("/^\.\/(.+)\.md$/", $matches[2], $matches_url) !== 1)
			return parent::inlineLink($fragment);
		
		// The page name is made safe when Pepperminty Wiki does initial consistency checks (if it's unsafe it results in a 301 redirect)
		$page_name = parsedown_pagename_resolve($matches_url[1]);
		
		$internal_link_text = "[[${page_name}]]";
		if(!empty($matches[1])) // If the display text isn't empty, then respect it
			$internal_link_text = "[[${page_name}¦{$matches[1]}]]";
		
		$result = $this->inlineInternalLink([
			"text" => $internal_link_text
		]);
		$result["extent"] = strlen($fragment["text"]); // Parsedown isn't mb_ friendly
		return $result;
	}
	
	/*
	 * ███████ ██   ██ ████████ ███████ ███    ██ ██████  ███████ ██████
	 * ██       ██ ██     ██    ██      ████   ██ ██   ██ ██      ██   ██
	 * █████     ███      ██    █████   ██ ██  ██ ██   ██ █████   ██   ██
	 * ██       ██ ██     ██    ██      ██  ██ ██ ██   ██ ██      ██   ██
	 * ███████ ██   ██    ██    ███████ ██   ████ ██████  ███████ ██████
	 * 
	 * ██ ███    ███  █████   ██████  ███████ ███████
	 * ██ ████  ████ ██   ██ ██       ██      ██
	 * ██ ██ ████ ██ ███████ ██   ███ █████   ███████
	 * ██ ██  ██  ██ ██   ██ ██    ██ ██           ██
	 * ██ ██      ██ ██   ██  ██████  ███████ ███████
	 * %extendedimages
 	 */
 	/**
 	 * Parses the extended image syntax.
 	 * @param  string $fragment The source fragment to parse.
 	 */
	protected function inlineExtendedImage($fragment)
	{
		global $pageindex;
		
		if(preg_match('/^!\[(.*)\]\(([^|¦)]+)\s*(?:(?:\||¦)([^|¦)]*))?(?:(?:\||¦)([^|¦)]*))?(?:(?:\||¦)([^)]*))?\)/', $fragment["text"], $matches))
		{
			/*
			 * 0 - Everything
			 * 1 - Alt text
			 * 2 - Url
			 * 3 - First param (optional)
			 * 4 - Second param (optional)
			 * 5 - Third param (optional)
			 */
			$altText = $matches[1];
			$imageUrl = trim(str_replace("&amp;", "&", $matches[2])); // Decode & to allow it in preview urls
			$param1 = empty($matches[3]) ? false : strtolower(trim($matches[3]));
			$param2 = empty($matches[4]) ? false : strtolower(trim($matches[4]));
			$param3 = empty($matches[5]) ? false : strtolower(trim($matches[5]));
			$floatDirection = false;
			$imageSize = false;
			$imageCaption = false;
			$shortImageUrl = false;
			
			if($this->isFloatValue($param1))
			{
				// Param 1 is a valid css float: ... value
				$floatDirection = $param1;
				$imageSize = $this->parseSizeSpec($param2);
			}
			else if($this->isFloatValue($param2))
			{
				// Param 2 is a valid css float: ... value
				$floatDirection = $param2;
				$imageSize = $this->parseSizeSpec($param1);
			}
			else if($this->isFloatValue($param3))
			{
				$floatDirection = $param3;
				$imageSize = $this->parseSizeSpec($param1);
			}
			else if($param1 === false and $param2 === false)
			{
				// Neither params were specified
				$floatDirection = false;
				$imageSize = false;
			}
			else
			{
				// Neither of them are floats, but at least one is specified
				// This must mean that the first param is a size spec like
				// 250x128.
				$imageSize = $this->parseSizeSpec($param1);
			}
			
			if($param1 !== false && strtolower(trim($param1)) == "caption")
				$imageCaption = true;
			if($param2 !== false && strtolower(trim($param2)) == "caption")
				$imageCaption = true;
			if($param3 !== false && strtolower(trim($param3)) == "caption")
				$imageCaption = true;
			
			//echo("Image url: $imageUrl, Pageindex entry: " . var_export(isset($pageindex->$imageUrl), true) . "\n");
			
			if(isset($pageindex->$imageUrl) and $pageindex->$imageUrl->uploadedfile)
			{
				// We have a short url! Expand it.
				$shortImageUrl = $imageUrl;
				$imageUrl = "index.php?action=preview";
				if($imageSize !== false) $imageUrl .= "&size=" . max($imageSize["x"], $imageSize["y"]);
				else $imageUrl .= "&size=original";
				// This has to be last in order for the extension auto-detection to work correctly
				$imageUrl .= "&page=" . rawurlencode($shortImageUrl);
			}
			
			$style = "";
			if($imageSize !== false)
				$style .= " max-width: " . $imageSize["x"] . "px; max-height: " . $imageSize["y"] . "px;";
			if($floatDirection)
				$style .= " float: $floatDirection;";
			
			$urlExtension = pathinfo($imageUrl, PATHINFO_EXTENSION);
			$urlType = system_extension_mime_type($urlExtension);
			$embed_type = substr($urlType, 0, strpos($urlType, "/"));
			if($urlType == "application/pdf") $embed_type = "pdf";
			
			// Check if the URL is a recognised external provider
			$ext_provider_result = $this->__make_embed($imageUrl, $altText);
			if($ext_provider_result !== null) $embed_type = "ext_provider";
			
			$result = [];
			switch($embed_type)
			{
				case "pdf":
					$imageUrl = preg_replace("/&size=[0-9]+/", "&size=original", $imageUrl);
					if($imageSize === false) $style .= "width: 100%;";
					else $style = str_replace("max-width", "width", $style);
					
					$result = [
						"extent" => strlen($matches[0]),
						"element" => [
							"name" => "object",
							"text" => "",
							"attributes" => [
								"type" => "application/pdf",
								"data" => $imageUrl,
								"style" => trim($style)
							]
						]
					];
					break;
				case "ext_provider":
					$result = [
						"extent" => strlen($matches[0]),
						"element" => $ext_provider_result
					];
					break;
				case "audio":
					$result = [
						"extent" => strlen($matches[0]),
						"element" => [
							"name" => "audio",
							"text" => $altText,
							"attributes" => [
								"src" => $imageUrl,
								"controls" => "controls",
								"preload" => "metadata",
								"style" => trim($style)
							]
						]
					];
					break;
				case "video":
					$result = [
						"extent" => strlen($matches[0]),
						"element" => [
							"name" => "video",
							"text" => $altText,
							"attributes" => [
								"src" => $imageUrl,
								"controls" => "controls",
								"preload" => "metadata",
								"style" => trim($style)
							]
						]
					];
					break;
				case "image":
				default:
					// If we can't work out what it is, then assume it's an image
					$result = [
						"extent" => strlen($matches[0]),
						"element" => [
							"name" => "img",
							"attributes" => [
								"src" => $imageUrl,
								"alt" => $altText,
								"title" => $altText,
								"style" => trim($style)
							]
						]
					];
					break;
			}
			
			// ~ Image linker ~
			
			$imageHref = $shortImageUrl !== false ? "?page=" . rawurlencode($shortImageUrl) : $imageUrl;
			if($embed_type !== "pdf") {
				$result["element"] = [
					"name" => "a",
					"attributes" => [
						"href" => $imageHref
					],
					"text" => [$result["element"]],
					"handler" => "elements"
				];
			}
			
			// ~
			
			if($imageCaption) {
				$rawStyle = $result["element"]["text"][0]["attributes"]["style"];
				$containerStyle = preg_replace('/^.*float/', "float", $rawStyle);
				$mediaStyle = preg_replace('/\s*float.*;/', "", $rawStyle);
				$result["element"] = [
					"name" => "figure",
					"attributes" => [
						"style" => $containerStyle
					],
					"text" => [
						$result["element"],
						[
							"name" => "figcaption",
							// We use lineElements here because of issue #209 - in short it makes it appear as part of the same document to avoid breaking footnotes
							// Specifically ->text() calls ->textElements(), which resets $this->DefinitionData, which is used to hold information about footnotes
							// lineElements = inline text, and linesElements = multiline text
							"handler" => [
								"function" => "lineElements",
								"argument" => $altText,
								"destination" => "elements"
							]
						],
					],
					"handler" => "elements"
				];
				$result["element"]["text"][0]["attributes"]["style"] = $mediaStyle;
			}
			return $result;
		}
	}
	
	/**
	 * Makes an embed HTML tree for a given URL.
	 * Example URLs include YouTube links, Vimeo, etc.
	 * @param  string $url	The URL to generate an embed for.
	 * @return array|null	The embed HTML tree, or null if generation failed (e.g. if the URL wasn't recognised).
	 */
	protected function __make_embed(string $url_text, string $alt_text) {
		// 1: URL parsing
		$url = parse_url($url_text);
		$url["host"] = preg_replace("/^www\./", "", $url["host"] ?? "");
		parse_str($url["query"] ?? "", $query);
		// 2: Pre-generation transforms
		switch($url["host"]) {
			case "player.vimeo.com":
				$url["host"] = "vimeo.com";
				$url["path"] = preg_replace("/^\/video/", "", $url["path"]);
				break;
			case "youtu.be":
				$query["v"] = preg_replace("/^\//", "", $url["path"]);
				$url["host"] = "youtube.com";
				$url["path"] = "/watch";
				break;
		}
		// 3: Actual embed generation
		switch ($url["host"]) {
			case "youtube.com":
				if($url["path"] !== "/watch" || empty($query["v"]))
					return null;
				
				return [
					"name" => "iframe",
					"attributes" => [
						"src" => "https://www.youtube.com/embed/{$query["v"]}",
						"frameborder" => "0",
						"allow" => "fullscreen; encrypted-media; picture-in-picture"
					],
					"text" => "YouTube: $alt_text"
				];
				break;
			
			case "vimeo.com":
				if(strlen($url["path"]) <= 1 || preg_match("/[^0-9\/]/", $url["path"]) === 1) return null;
				return [
					"name" => "iframe",
					"attributes" => [
						"src" => "https://player.vimeo.com/video{$url["path"]}",
						"frameborder" => "0",
						"allow" => "fullscreen; picture-in-picture"
					],
					"text" => "Vimeo: $alt_text"
				];
				break;
			
			default:
				return null;
		}
	}
	
	
	/*
	 *  ██████  ██████  ██████  ███████     ██████  ██       ██████   ██████ ██   ██
	 * ██      ██    ██ ██   ██ ██          ██   ██ ██      ██    ██ ██      ██  ██
	 * ██      ██    ██ ██   ██ █████       ██████  ██      ██    ██ ██      █████
	 * ██      ██    ██ ██   ██ ██          ██   ██ ██      ██    ██ ██      ██  ██
	 *  ██████  ██████  ██████  ███████     ██████  ███████  ██████   ██████ ██   ██
	 * 
	 * ██    ██ ██████   ██████  ██████   █████  ██████  ███████
	 * ██    ██ ██   ██ ██       ██   ██ ██   ██ ██   ██ ██
	 * ██    ██ ██████  ██   ███ ██████  ███████ ██   ██ █████
	 * ██    ██ ██      ██    ██ ██   ██ ██   ██ ██   ██ ██
	 *  ██████  ██       ██████  ██   ██ ██   ██ ██████  ███████
	 * %codeblockupgrade
	 */
	
	protected function blockFencedCodeComplete($block) {
		global $settings;
		$result = parent::blockFencedCodeComplete($block);
		
		// Default value: "text"
		$language = preg_replace("/^language-/", "", $block["element"]["element"]["attributes"]["class"] ?? "language-text");
		
		if(!isset($settings->parser_ext_renderers->$language))
			return $result;
		
		$text = $result["element"]["element"]["text"];
		$renderer = $settings->parser_ext_renderers->$language;
		
		$result["element"] = [
			"name" => "p",
			"element" => [
				"name" => "img",
				"attributes" => [
					"alt" => "Diagram rendered by {$renderer->name}",
					"src" => "?action=parsedown-render-ext&language=".rawurlencode($language)."&immutable_key=".hash("crc32b", json_encode($renderer))."&source=".rawurlencode($text)
				]
			]
		];
		if(!empty($renderer->output_classes))
			$result["element"]["element"]["attributes"]["class"] = implode(" ", $renderer->output_classes);
		
		return $result;
	}
	
	/*
	 * ██   ██ ███████  █████  ██████  ███████ ██████
	 * ██   ██ ██      ██   ██ ██   ██ ██      ██   ██
	 * ███████ █████   ███████ ██   ██ █████   ██████
	 * ██   ██ ██      ██   ██ ██   ██ ██      ██   ██
	 * ██   ██ ███████ ██   ██ ██████  ███████ ██   ██
	 * %header %toc
	 */
	
	private $headingIdsUsed = [];
	private $tableOfContents = [];
	
	/**
	 * Inserts an item into the table of contents.
	 * @param int    $level The level to insert it at (valid values: 1 - 6)
	 * @param string $id    The id of the item.
	 * @param string $text  The text to display.
	 */
	protected function addTableOfContentsEntry(int $level, string $id, string $text) : void {
		$new_obj = (object) [
			"level" => $level,
			"id" => $id,
			"text" => $text,
			"children" => []
		];
		
		if(count($this->tableOfContents) == 0) {
			$this->tableOfContents[] = $new_obj;
			return;
		}
		
		$lastEntry = end($this->tableOfContents);
		if($level > $lastEntry->level) {
			$this->insertTableOfContentsObject($new_obj, $lastEntry);
			return;
		}
		$this->tableOfContents[] = $new_obj;
		return;
	}
	private function insertTableOfContentsObject(object $obj, object $target) {
		if($obj->level - 1 > $target->level && !empty($target->children)) {
			$this->insertTableOfContentsObject($obj, end($target->children));
			return;
		}
		$target->children[] = $obj;
	}
	
	protected function generateTableOfContents() : string {
		global $settings;
		
		$elements = [ $this->generateTableOfContentsElement($this->tableOfContents) ];
		if($settings->parser_toc_heading_level > 1)
			array_unshift(
				$elements,
				[ "name" => "h$settings->parser_toc_heading_level", "text" => "Table of Contents", "attributes" => [ "id" => "table-of-contents" ] ]
			);
		
		return trim($this->elements($elements), "\n");
	}
	private function generateTableOfContentsElement($toc) : array {
		$elements = [];
		foreach($toc as $entry) {
			$next = [
				"name" => "li",
				"attributes" => [
					"data-level" => $entry->level
				],
				"elements" => [ [
					"name" => "a",
					"attributes" => [
						"href" => "#$entry->id"
					],
					"handler" => [
						"function" => "lineElements",
						"argument" => $entry->text,
						"destination" => "elements"
					]
				] ]
			];
			if(isset($entry->children))
				$next["elements"][] = $this->generateTableOfContentsElement($entry->children);
			
			$elements[] = $next;
		}
		
		return [
			"name" => "ul",
			"elements" => $elements
		];
	}
	
	protected function blockHeader($line) {
		// This function overrides the header function defined in ParsedownExtra
		$result = parent::blockHeader($line);
		
		// If this heading doesn't have an id already, add an automatic one
		if(!isset($result["element"]["attributes"]["id"])) {
			$heading_id = str_replace(" ", "-",
				mb_strtolower(makepathsafe(
					$result["element"]["handler"]["argument"]
				))
			);
			$suffix = "";
			while(in_array($heading_id . $suffix, $this->headingIdsUsed)) {
				$heading_number = intval(str_replace("_", "", $suffix));
				if($heading_number == 0) $heading_number++;
				$suffix = "_" . ($heading_number + 1);
			}
			$result["element"]["attributes"]["id"] = $heading_id . $suffix;
			$this->headingIdsUsed[] = $result["element"]["attributes"]["id"];
		}
		
		$this->addTableOfContentsEntry(
			intval(strtr($result["element"]["name"], [ "h" => "" ])),
			$result["element"]["attributes"]["id"],
			$result["element"]["handler"]["argument"]
		);
		
		return $result;
	}
	
	/*
	 * Inserts a special string to identify where we need to put the table of contents later
	 */
	protected function blockTableOfContents($fragment) {
		// Indent? Don't even want to know
		if($fragment["indent"] > 0) return;
		// If it doesn't match, then we're not interested
		if(preg_match('/^\[_*(?:TOC|toc)_*\]$/u', $fragment["text"], $matches) !== 1)
			return;
		
		$result = [
			"element" => [ "text" => self::TOC_ID ]
		];
		return $result;
	}
	
	/*
	 *  ██████  ███    ██ ███████ ██████   ██████  ██   ██ ██ ███    ██  ██████
	 * ██    ██ ████   ██ ██      ██   ██ ██    ██  ██ ██  ██ ████   ██ ██
	 * ██    ██ ██ ██  ██ █████   ██████  ██    ██   ███   ██ ██ ██  ██ ██   ███
	 * ██    ██ ██  ██ ██ ██      ██   ██ ██    ██  ██ ██  ██ ██  ██ ██ ██    ██
	 *  ██████  ██   ████ ███████ ██████   ██████  ██   ██ ██ ██   ████  ██████
	 */
	protected function blockOneBox($fragment, $current_block) {
		global $env, $settings, $pageindex;
		// error_log("FRAGMENT ".var_export($fragment, true));
		
		if($fragment["indent"] > 0 || !$settings->parser_onebox_enabled) return;
		if(preg_match('/^\[\[\[([^\]]*?)\]\]\]$/u', $fragment["text"], $matches) !== 1)
			return;
		
		// 1: Parse parameters out
		// -------------------------------
		$link_page = trim(str_replace(["\r", "\n"], [" ", " "], $matches[1]));
		
		if(empty($pageindex->$link_page)) return;
		
		$link_page_content = file_get_contents($env->storage_prefix.$pageindex->$link_page->filename);
		
		$preview = $link_page_content;
		if(mb_strlen($preview) > $settings->parser_onebox_preview_length)
			$preview = mb_substr($preview, 0, $settings->parser_onebox_preview_length) . "…";
		
		// 2: Generate onebox
		// -------------------------------
		$result = [
			"element" => [
				"name" => "a",
				"attributes" => [
					"class" => "onebox",
					"href" => "?page=".rawurlencode($link_page)
				],
				"elements" => [
					[
						"name" => "div",
						"attributes" => [ "class" => "onebox-header" ],
						"text" => $link_page
					],
					[
						"name" => "div",
						"attributes" => [ "class" => "onebox-preview" ],
						"text" => $preview
					]
				]
			]
		];
		return $result;
	}
	
	# ~
	# Static Methods
	# ~
	
	/**
	 * Extracts the page names from internal links in a given markdown source.
	 * Does not actually _parse_ the source - only extracts via a regex.
	 * @param	string	$page_text	The source text to extract a list of page names from.
	 * @return	array	A list of page names that the given source text links to.
	 */
	public static function extract_page_names($page_text) {
		global $pageindex;
		preg_match_all("/\[\[([^\]]+)\]\]/u", $page_text, $linked_pages);
		if(count($linked_pages[1]) === 0)
			return []; // No linked pages here
		
		$result = [];
		foreach($linked_pages[1] as $linked_page) {
			// Strip everything after the | and the #
			$linked_page = preg_replace("/[|¦#].*/u", "", $linked_page);
			if(strlen($linked_page) === 0)
				continue;
			// Make sure we try really hard to find this page in the
			// pageindex
			$altered_linked_page = $linked_page;
			if(!empty($pageindex->{ucfirst($linked_page)}))
				$altered_linked_page = ucfirst($linked_page);
			else if(!empty($pageindex->{ucwords($linked_page)}))
				$altered_linked_page = ucwords($linked_page);
			else // Our efforts were in vain, so reset to the original
				$altered_linked_page = $linked_page;
			
			$result[] = $altered_linked_page;
		}
		
		return $result;
	}
	
	
	/*
	 * ██    ██ ████████ ██ ██      ██ ████████ ██ ███████ ███████
	 * ██    ██    ██    ██ ██      ██    ██    ██ ██      ██
	 * ██    ██    ██    ██ ██      ██    ██    ██ █████   ███████
	 * ██    ██    ██    ██ ██      ██    ██    ██ ██           ██
	 *  ██████     ██    ██ ███████ ██    ██    ██ ███████ ███████
	 * %utilities
	 */
	
	/**
	 * Returns whether a string is a valid float: XXXXXX; value.
	 * Used in parsing the extended image syntax.
	 * @param	string	$value The value check.
	 * @return	bool	Whether it's valid or not.
	 */
	private function isFloatValue(string $value)
	{
		return in_array(strtolower($value), [ "left", "right" ]);
	}
	
	/**
	 * Parses a size specifier into an array.
	 * @param	string	$text	The source text to parse. e.g. "256x128"
	 * @return	array|bool	The parsed size specifier. Example: ["x" => 256, "y" => 128]. Returns false if parsing failed.
	 */
	private function parseSizeSpec(string $text)
	{
		if(strpos($text, "x") === false)
			return false;
		$parts = explode("x", $text, 2);
		
		if(count($parts) != 2)
			return false;
		
		array_map("trim", $parts);
		array_map("intval", $parts);
		
		if(in_array(0, $parts))
			return false;
		
		return [
			"x" => $parts[0],
			"y" => $parts[1]
		];
	}
	
	/**
	 * Escapes the source text via htmlentities.
	 * @param	string	$text	The text to escape.
	 * @return	string	The escaped string.
	 */
	protected function escapeText($text)
	{
		return htmlentities($text, ENT_COMPAT | ENT_HTML5);
	}
	
	/**
	 * Sets the base url to be used for internal links. '%s' will be replaced
	 * with a URL encoded version of the page name.
	 * @param string $url The url to use when parsing internal links.
	 */
	public function setInternalLinkBase($url)
	{
		$this->internalLinkBase = $url;
	}
}



// %next_module% //

//////////////////////////////////////////////////////////////////

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */



// Execute each module's code
foreach($modules as $moduledata) {
	$moduledata["code"]();
}
// Make sure that the credits page exists
if(!isset($actions->credits))
{
	exit(page_renderer::render_main("Error - $settings->$sitename", "<p>No credits page detected. The credits page is a required module!</p>"));
}

// If we're on the CLI, then start it
if(!defined("PEPPERMINTY_WIKI_BUILD") &&
	module_exists("feature-cli") &&
	$settings->cli_enabled &&
	php_sapi_name() == "cli")
	cli();

//////////////////////////////////
/// Final Consistency Measures ///
//////////////////////////////////

if(!isset($pageindex->{$env->page}) && isset($pageindex->{ucwords($env->page)})) {
	http_response_code(307);
	header("location: ?page=" . ucwords($env->page));
	header("content-type: text/plain");
	exit("$env->page doesn't exist on $settings->sitename, but " . ucwords($env->page) . " does. You should be redirected there automatically.");
}

// Redirect to the search page if there isn't a page with the requested name
if(!isset($pageindex->{$env->page}) and isset($_GET["search-redirect"]))
{
	http_response_code(307);
	$url = "?action=search&query=" . rawurlencode($env->page);
	header("location: $url");
	exit(page_renderer::render_minimal("Non existent page - $settings->sitename", "<p>There isn't a page on $settings->sitename with that name. However, you could <a href='$url'>search for this page name</a> in other pages.</p>
		<p>Alternatively, you could <a href='?action=edit&page=" . rawurlencode($env->page) . "&create=true'>create this page</a>.</p>"));
}

//////////////////////////////////


// Perform the appropriate action
if(isset($actions->{$env->action})) {
	$req_action_data = $actions->{$env->action};
	$req_action_data();
}
else {
	exit(page_renderer::render_main("Error - $settings->sitename", "<p>No action called $env->action has been registered. Perhaps you are missing a module?</p>"));
}
__halt_compiler();PK   �SV�@�  GY     page-edit/diff.min.js�\}{�H��{�m�9V
B��"b�	s��3d�s؛G/�D$a�c�ݯ��[�L&{w�ٱ%�{U��^���g����]��TD��Ǝ9;�]&�u'1��L{98Q��R�8Z���d
����k�����O~�섻s���GO/&�L7Z�J� `�6a1Ox|�=�T�������R?
�zl3��,��	K�Ԡ�$Z�.�*���-G�"1؍�NY��h��E��]{4J�z�ㅟ��c�8��=xH�v
?8tэN�������<m�J�������yPm����Ԇ)b�]��BE�0J���XQ }a��B����C���/xl��U���,�[����`ry^�P4lů:P>��.�Ǿ$9��9԰� Z��������!Ga���4����(N`�[�p��|�x��W�� 3XD)g�(�҃Ɂر1mɗ��d�]h�G���'"�$b��������^�<��8���;a/g��=v|�����/������I�b��g'��lx��nx���,SA��w���ۋ�`��/J�7oO���~�=�{��ώOߝ��~1t��·���?�j�s�}،��bozǯ�������^��g8֫��eo������{�������pY'���i���wb��0"���;�������*�ߟ�.p�K|ك9v_���@�ȓ�E�x��ɟ��p0�S��������G�ҽ�݀>�l����JPX:���KӞ�����E�N�0x�r0��{����3�.~��mvz> b��aإ������w�>Ѭ6�]\�{;쟟����@�c��3��h�@��߱S���`�_��;p�LCI0 ���`< రFv����K�츇������������þ��%Y�b�XX����X��>N[V��RL�dǯ� �Yz-PzV/�W���X�G�+GΌ�iٲ��%�}�?-�8M*�% ͫ�w�/SֳMo�U�ye����W*�i/��x�FW0n�q;��krz���/k���-B��?���K��8d��
e�:�����PuJY��9����x
��|�~����p���ǚ;�����GE��F�1�e6���YC_��������q���i�Zwk#����� ��[���4.�4q هP�\�)�ت��������h�����k۠�hB�m��H0�-1����Ëhk4�5�rÝ���_FQ
��^���/G��RԵ�6	s
C�FJAK�9s�$嘻׊�I�6@R�u�}� ��A�|��f'ۓ.Vλ]����v:���msa��7]˅�K�\6�[jT�L���D��� ���U��	(v��5N�f�=4`'���m.7��t�b�>����uW��WAZn��S(DVü�0
�6�?��Qx���xj����9�����[�����xk��^��2���l�bsڗ�oÛ��R_a�`��b�/h���D�|<N�4 ����0���O���Q�%��� ;y��y
�g�����Xc���r�(K A��n�-O��h�	T;�S>��o���P��������|�?�)���=�(�yOy�dh�a�}Xb�}$���ӁKl��M�,"6�4�H۔@�m����36�R����e�3���RQC�N��D�Z
���o���4���_��3疹���nA�b��T"���/ �	�����L}w*)��[���I�[1�F@��`�	��7��p���;c}	 K� ����7�<���#��6鈮B���Z��!;��Nt�ǸkY7�D�CL���°�l���*3��-���m���:1�M�t٪�]\7��'��\&+�{����o��'�����"�j6�&���������zI���(�ë�ūTąI��d�*����Z��4����>����-6�����9�6ߟ�)��\==Y��?����V �����w)�h����e��ӭ
�rQx��6������v�^��j��_����zA��㮯�{)�+��+�鬹���l{+��\���E�6�3�~FC�vl5�	�'�Ԙ«o5ړ�q{\��wXgf9��U\���Et��N��=OZ�XȒH>	��V)�.c?NR�,�Y ҋNi
A��QPE�Ă:K}
 �C5��[D���.!LOR�3��#����	�z�jK����;`�g<�g�h��v��g&�'k��Wg��U���,�C�\��%?�J(Uk^�0O$Ron�om�hi�k�^� �w0��L4�T�h�����@^�R۔�x#��\���)Yzdˇ�Ƴn�E�
hL}�5lV�=�KΪd"�Ӓ\����SD_��� �o@]��`�x�ĹANF�����C��,$��L��"����ݠ=O�L�3o�b
��&@���@�W�v�I�0AvH:M�����P�_d�^����Vn,y૆���{Ւ�7EKM�'�q<�j���%-������-���롟S���#��k�&0i��k`<���kmJWNr'm�����m��.SMx�����:�r��o����3�3ܝL�bW�#��f��q�O�a�u���C����"�՚ςvpd!�Y�bL�W�F4�P������dL�����)��|�|�I�D([�Ci�K����P(䀹����$����m��҂��r�j����h-*���jT*�1vy�Q�k�R?���^�k��1��'� D��`��t�h�� �"NoO"�-tBuo�f^Bh��s����ۈ`9�Qثw ˘���䎓�;���x<����O�N�2�F)��� �����[���rZ:@R��z�X��&� ��r�L����&��dHv���-h05&����a�I�� �	�@�	�P��yw�#k<��s]��恺����&H�i�fB:��<��6��Ln|�$j.��N�TGF h��������
6B��dBH.%'���.2�$aY�+j$������h�3b@�1������D�������ղ����U�n�*��V�PHρ5�wĊ'��(w��pEf�$��&��|?�m�]%i?�8e8��ݐ���`��R��L�`}A����]l�Yn6�-�e��j�̪s#�F
�k�"ޏ��:��p���	
�lb|�)e�ce"�E�؀�
?�5$gggmtG�rrۅ�tK-c}%��-���z�ȋ�xb�_co`}�+��W��%�dn��
<(u�܆.M̦��ʘ �ڑ)6�i-2�����y*f 3���]��<��\�����`�~f&�N�U�M������&6��ǰ��&���0*���z��
�����pi�� ��@u���6)#Tv�ő��z[ZP�ԇ���zm�y������/��Y�HI;�Q`�z�z���������� �������N츐<n��ɲh)�;[	�F��%۷�nic"֝�fO��զA�j9��O��!��d�]U�/���.ޢ���	�!cl)�-�
�jy`�hR�t|4�_+�5
�'Z-�J`��PhSTjժ2j}4^Feq�>@��u�E륙lى*|�b�%��U<�S�s��H�>Wlo���#�
/�H|��h��ؘ�Zܗ���xg�f��&?�6,;���-_t�RtZ�h�ҧЕ����EE�GF1�������ab�� ��V�	$��㉂�DD����!�Crl}t��ㄇ���\XA��w���,��:h������u�]��q���k��WD��������D���a���w�1���uk�m����tܨ]���?�ϟ���G�٣�W�.W�^�5{�^]U����V���l�#x����ܴ s�@���8D��Zs�����܄g4f�g�S���VPPO�I��|*:�AtI2�ϋ�D�!�=��.��}��!��_�'0�D x ��u
�9,�
��nx��
���eR��t��.!��45���k�F@�ϜI�^~��!D|QՎB�2�&����2/�0+C��J���Q9�z�g���$�oP(";�U�� ��kЅq˨�
��P廯��f`����l�ɼ$��ԴuEv�e�;�J���&X��7���+�h\�\kKa>��[�c�������l8�����/����>$j���!���,�玾�2�>��ٜ?g���`��"���/C �A��W�(Q�B��'�,r��}_��$np�$QD_$P�K���݄�{WDji�7*���	D� �@��Q)Ĝ@�3H2�� ܼ�����LD&V���C ���ia���@g�##�oiO��[܄�.(�,�^��&4��ofE&������i��!�1��ڑ��ni�2�A�f�32:W�ֱP~R-��㿛q�$;���k�r��Ά�ݺ�6������Z�yf��S�D�yBY)�����*  ���UI0Ǳ� �����J0��a �	\1f���nU�L����"c�/��¦�8�!�=T�Z���.�n���i��=�=�����I��e�dٶ@U�-���p#BW`/Ys� j�"h�B��Q�DEM�t<*�i�y���8	X
���d6�P�T#_�1�|N�_�g�ж�^��fK+��-r�
�]��5��W*�*�w�Uܭ�IA��F�"I�?o�:�P��N�L�$�SMf�� k�4�#_�Ǘ��"�8V�`�����2K����(N�v��ۅ�J9���Sĥ�����;0����a-98Q�;j���v!ه~��~�m*`��>�,����9	Z~ B8 �h�{�1a�EF�8��0�R� yz��2���z��<��4���'	@�`Q�5"X��po azH(o����عg��%�o-���$�$�b��|U``B��n���p�7[ߢ)��g�"��U
���P*���$Ϭᬲ�M;4SYX��mo���(�Ƿ:u�ܲ�X�������LE��*�!�f̗��Z��Fhw_���Q��YF���B�G��1c�njQ��Q�c외��9	�U8g�����-�x�!�T^l"q�P4/�,=���Vah��b0Z���1�O�&�F�r`���I1����r�D�ݐO)K���(?���Sm�:У1A�}V����.��A��T�k��3�%ǹ�_8ΥEmK��q������§��Az�����F��p���iݐ{׋c`H9��BC���~����,������K�vmJW����8� �f�m(!��HL���JϽ@�9��˰���m�@ǣ�;����?<z�<D�@vo�y�3ǫ?�xe�:������/Tz�� ���c%�K�M5ݏ�x��2bp$���ь�7�@w���ҧ���4V�$�ѵ�����0I;�V`�U(���V�(�NĆ�������LВ\��R؛7�����I��P�8��x���9�<D6aR�ߡz|K�c��51+wIY�u^
,5��9�Ht�(r�����5�:'�5^� ݯ��O֍���ȷ�����&��*P�Ò%��+�k������c�t�O�j(��չ
h~2�z^Z}P�0^��F���M�8�H�y�)���Zh��E��T�-1�c@�^eN�k��;uv
�l�u:������>�#^8n����M.�T��\1;Q�2��"��>�#�X�6ٍ�ȫ��TD�r!D a׾�T�5�vj6��5�x�j�O8�;tS���Q���Y��8O1�DZ�\� �2��\j��F!���T �Uh���|>��2�m~X�k����??��3��'�53+X$H7�b��SG���K�	��)i�ڞ�ڴ�'�'kO����TX��}��Ę܂G,��\\�/vRQ��vY���U�w��~���ɏN()mo��N㧒H����V?��)p�i�Z�.o�z��jf���}S71�zk�(&ub��敎Ik\]��q�eD݊4Cᮚ�]�}yKGݢ%S{A	0�h���� _�)�>��&+N�B���O���[<�t����J�,�~�3�km��C��C}$�ߠuN�"4�x�"�,G��@��U��aH�^�^�@-�#�^^^�O袰�N�P�ͻpF7��D��ڴ�ԫ ��-�f�F�	L%�G�V���T�V�`��rP
d(�5ʲ�)��EW�y�~[BI�.��wG3�m�R�tR��^�I�&p@�:eP��V�\n�#�Z��rf/xS������N���"-.�&�g�1;��燝���@DV161�Ѻ�.���A���p-G�_�`5�ҫ갭z�;����L��Ъ�#�/���^��7����^�
�Q�	&
�� nd�z�_�@"��mE�H"�[����o|���I���d}=�L�U��g�ɞ�.���0
Uf2qJ۹讀h�d�����op��B����VS�LE��{-�@1���"#�P/X#�z�'�=���y���4<_��k)h� �i��l�|$���<�}!�Ⱦ�wuz� ���wxn͝�[s�sk����L0�8ݔ:�cvib�qP��%����ӗ\�؝���:h�:���j���$�j$<^�r�q���m�I��68�6
=酈�=q�55���Ӏ�ULH��e^`����}D+�.IeI�:n$N��é�G�]�(�q҆�4�{gK�4[�O�0�`��	�6[3V�(�m���q�=oصɑ�tj8M�8��U>[������;P'B{5 ӷ� �N�w �ܑ�)�kTjg���R����(��9Fk�J>#�Y�e2�qsN�F�Ւq;����z�m�nm^�ʂ� $�Z_�x�
a)���n-,��^/`��`b��s�0*����\��qAmt%Cu����4>�q�|�L�=1�W,	�;�2U������ѩ-�A"��qs���&��O�6�qo3')�HrҊ���N).�k��Z�?]XK �GcJ�o��G��L�J�+�xղB]o!1kV�R���Yh�sqY1Z��6[Rz|���1x�tb�p��3���1�1���MMr�6�:J�.l�������#�_���QI5�X��j-�gq}2����$��jM9�PȒn��Y��^i����*�#	��'�(6F(n � H	��y��Y��J�~>WA�ç2�'���x�8�E��_��6F�'&�_ab��;��?�(f����/�,�������NY,�En�/k�j���an���P"�3Z#n�GV.�J.<9-H�{��ߓ�4My�]`$Xn��7�w@��A�A���\W*7�����]^�F���f����+�-��S���b�L:�G�G����2�@κk!�O�A���L�KJ�
.��8s�xb2�@�y�o�u �0���@;�6^;����1�'��En+��e#[�7�/���K��ĠA+X?�44<2�B_���l�0|a����W�rǋ�����W�{�8A�ꛅ�Vwf)P��N��o�����=b��
�z�ǌny��'=75����s_F_$s��	,Y2�cơ�z��j���ˆ|%᫖Y�զ��j�B��$�̌@���Y���U���|���c��o(	��������c���c�d���2���_������38���IM�!���.^jh���W�Q�~���z�m+�u'H�y��"p�m�[�-{0��Tq�M�l��b�Ԛ��ʥy�+���yѻ���t|�����'�?�|SL�ns�8���s� G��\-5�(U~��ਜ��z�� *[���tS�d���ߕ�t�����V!Ϡ��{�lㄊ%ϩ$HQ��aA�
>�",����pq�W:�6�PK   �SV�U�
  8     page-edit/awesomplete.min.js�Ymo�8��_asCZӊ�=���F6M��d���pH��,�	�2�#�4���~3$�f;/8�>����p^��a:�?��ˌi�tNY��'�y��N���'L(��;/D�y.<U>v����C,;,:�2~
�2��<Ye�e���Ї8+<�n6!�g�D�(�OK��;��#��pa^�y��dS�،�=W�Y���]�Y`���3��[�T�2OSF9��K�8�YTĮ����X:�c�Pk�g�fIcH_:?�0y+�����b1c�VFM4����T�DhO�awE�[E�u�H�
�V:	~�ŉXӄ�IR�Y����>y��ϡ�7��U�W��B
�@|q�0��c�u�'ł	��)�|�dx,��P��xx�O4�u� ���{��!lk߯(��kҸ4����8�����P�G%��t���M*JG���2H�B��s`$�?���;n�.qu�d��Q���"���xT��q����|>'/QK��2)K�|g���A�C( n��6�Jߒ~��<d��h �,���q���"��z����LW.��!4�_�"~<�l��ѐ��_�Tb�b��2�|8=�s�i&�����������lzxrvIU.�BY�������bz�ۿO���>�J�\������r8N��ߨd�,N�^�~zxtLu<���7��R�=F�ʹ�o�~�<Yd�b�O����i�DH�/�h�����d��������<�i�q�A�B5�Tpj��J��8ꁫ2�iЖ�~���H��# �RLj|v��<!aw�_�٣>�7H�pصf)=!S���Ӧ\�N.;�\�N�qNVHȎd�ȴ
@���B��0��Wj~��L�Ld���l1�^�F0����,+��=�Uc���*!	��ΞRȌ����� ���1�1�{=o� ��z�>,�x:XJ#�g6�Anϧ�6 w���/𹊿IM��W$�8��JV�5��k�p����|��WÄ����!�6r� �S��"\�b���Y"8zC�,\-��1֮�@)��פ:�P��|�1P�x��1��=��4S�c� �cL�c+,�98����L L:���< �ڝ��x���V��%�
�ȢM�+m�"kSYQ�c�@T�~�O����\��k,y��d�����a��Yn�Ͱ�P��z}mI �,uosi�o)s�ce�VP:H�>'n�b���=fP�:@���zڂ�9�R��Z�j�q�\��)�;�+^�-�>rH�y�BX��uu3΃x��@�zso�N]�\ų������. �I�M�^s
=A��1!X�z=f���G��GyS�Q�m�f&  �=��EQ�O�X�0��s�<��G�+X�Y��&q	�%$z;ym	w���5E�E]�RE�nKԬ��8���s.Y3c�Ut`�"Tc�9NQ��1C�Y--��V;��d��j���Ϫ���jσA��� ټ�Z���M.e��T���x���=t�f�Ww6y�V��l�ul5~c���Ts�%�a�z[������A�����iя-�X�+����x0�U��!.%��L�u�i�� |J 
2&����vn��z0�ԯ�Q�'�p������(k��`�}^�}X�&�ݙ��d_��:e�Ņ]է��K��tw@���l!vZ�?���k��9���ّ��X�j�U�hN=��ؔ��<����b5�Y�DM*��i��"�۪n�n\L��Φ���c���Ae܌��_���}C�\y�(CB�Pg[�Uqw��I�t��FkȆ�q��Ѝ ��B�Y�_ʹ�M��Ō�^:��2V�RAs�a*���_'7�y����(;�W����;tc:��ng��	ʛM�)�����H� ���s�oO9ĸ�w_��N#₭aԨ�h/�FTu/7���&���Z��	��9rV�A�%5Ӷ=���~�Ы>�}��O���Y��~��}��v�^R�`"�
��Z�����{������	�h!R�7�D��ur�ĚzU�=�LψX�>������c@����~�v%j#���vw���B��c��K�y�!�)�ķc��75�������_'ӯ/�%���Y�nz��-�	�Z��rmP���#�F��V�Dўd�oWR�о\i�*�X�}C�1r�5SK<*�p�80��;$Gu���·W=pv���"��?��}<0�d�{l�V^�T�3t���{���	���7�v���s���>��=7��A�����.!m�~2#|9e:
g�K3<�۠��)���&J7�3�9�9����2���F��y�Ï8���*�����ƥ��"�V�j��4n���?�Ɵ	$��oJ����`Q>����=Q���Ƥ%\Y9���o��Pga�m1��|�� [�9���<0�0��Ӎ�b"@�H���F)
�{Y���h�	�K;��؁c�P]	����o��4=Ɖ���M-�!��՜��8����H�s�,<��`��c����0
�fw�/�7V�-$�\-�\�A�ƒ��Y���������?�'�篯�oV���;J��������[�J�X4��^�����~7f��_J�A l��[�}�es�=�'h��0�1q�ggK��dy������`���J&�@Ԇ�M�v̐���\==>8u(�\��8��>t�/��<-2��o���A�@������Qy!�RD���i�@dl!�?�-Z��/PK   �SVy&wH�  �     page-edit/awesomplete.min.cssuT�n�0��W�V��
��I���V�����T�`��۲M�4꿯!!$3��͛g0[�7�U y����vBz�ؖh�����w����cw�x�ф�ި& �JZ I�v�t q�ZI(W���ƁbA�˾Hm�p��R����DAHF?��6dFk��Z�P�Y����l��R�$7N�Cq�*��a���Z&D�њ�R��_��4Z��(�T�6l�W�e{(蘐�'xu��N�dP��rޤ-�?MO��:2��t���&S'N�UH[���q1:df7I�L�ĕ9;�ϧ{�*&̚�liu��C��F�Oi�M�C�~��Z�O&�1��j�9S0ɦ��X�.f?><�	or�Q�/b�/�^�J=2���W܍�%�{\E:�=�X�e�Ƒ�����/�(�h���c`>�*��`Ǎq(�ꊞ;4K���f���ޠ��^\�ċ�ud�~�RW�d����G[�n��h�J�խ3��,��q����^�ܝ��/��I�](o�7�X#��
��;��iS�Oq�F���,�.��2'��_�sp�@��?B2>�I<i�A<<ŨRҳ;�Ηbv}��y�?�Y��aj�?�n��	l�?��(⿿��O�q4�������?PK   �SV���g#  E�     parser-parsedown/Parsedown.php�=kw�6���+�ǆ���������&�6�i�qNϹ���l�"U������~1 H  �����v�8"	��0��bmm��~��O������b��_d�zQ|I�&.:�{����2�WEa�so�i�Q��F� @�?�a��^D��K�(�+_�"�^>;~��]�&/�]{	��I���O	�k?��o~��hO0�DSܣ��M/I�?�!��&��FL�0I���,:D�n���N��^�����\�bN ?��ik����
�mx��4!�� �����J0Np �(��܋?,0�\��9���ʜh��y���i����"���8̊������"�R<!#.��3e86I�>`�,c��M��P7Ay��@�u�y^乗za/��O-��I�S/������.�.���/o�[��(&]���.�i&%���"G�pꇗֆب�CyL	���O�h*'���� ��ܖ)?ű��.gЍ?%S�_|B�A4�˦�--^�}t�9����������i����t�C�<���ֹ�XbֶTZz��@�f�?m]�_)�'o���G~R�Oz�?]]��q������2�m��( hOq����k�z�]�_���ˀS���j�GQࢼFC��j6��&)�'�m�(/ ┿l��ZQ�2�cF�1 �(Ry�{NG~�(�.J�.ʅ��ў���+=O������%�/���s/�?|�1���/*J��k���dOyN��~*='�Lz�\!I�7��d�.�B�r1�Y���AН~ZP��������[��Mq�B�����e��r^���J��R��]�+��DX��S�L��|�
|���������_��~�����֯?Z��d��'~eC+~>??���`̙��'M,u$����М�y(Q��1'r����_ߋ__@��3"j�27�>��pU�UT!�'<�r��ʟc�hRۂ�\ܾQ�l����Tq��Q���u�L����3b������@H�)�!��Mf����z��r�E;�A�F�h�ʻ��3W*#��j�	Q�-	�-�,�-ub����rA������VJ�Z������v���V�9��v��,�R?�R�zk��?1L	af����S<%�4X;���ɸ_xA���~+�EqJ!�������Ph�ȗez���q�?�w��.P���UюG/[�`/m9�ȭ�u�Tay.t����_�k�-?��E�'�J߲OV��z�q��\B�"�J9�Wl��<;����|˰`��+��6�G�J](i�0����FE�ح��9Ng���7���rȤ(@S"W	'�@�2Rvʄ��	.��-:$��!3�[w����i�̏���7�pXM y�w?����p
�������FY�Qtf;��)�s[1��UlCyL ,0�W2ڹ���f� �7��Q�r��КFT���&d�O����p|�cWt@hЂ��J~��A� 6n���/���K�:[#�y�|j(4����M5,ɚ�_��4�&��C�u�ľ�Δ�tb/�Ay5�u:��
�+)�x���D���M��߽ػ����iʂ��f��Mh�F+j�0��y�J:����Cj2��|��x�$��Ĩb�b�nE��@b5M@Fm+�%��������p8�]���pȼ ���_�ØE!�8�e����bC��,'�NQ�y��t�K�ػ�%�l���_%����)a�;����l�t��fQ��
��#b4~�?�I�!ꈬU���[���λ�]5p�Q:gM$��t�-�&����)�q����l��L��x�-����}g�"��,���t�?�j��!�C�{*3�+��Z��DD�B�"���*����Y�-){*���k�:2*��m��tV�x!i�����i�0��&��'I�&E��.�6��+��;���&j���iֈ�[�`�,�k��A�$��LUKsf/���
���X݀^Q`�IXDI6l@�D?X�v��1ʯ�H�	 ����L#�@k���XdX���9M\��D��"�Gʤ���,n�%�,�2dWQs��&0gz0ĉܯEtƑ���h�5���Iٖa���[�����%�"��q�
���L�d�G��r�a�+�'��BPGx�kɢ8�Al�Ƈ�h�5Ld���5&ц"_��%���(,G�RHa֕=H�������(�^����]?�����I����n�������������nn�o�L!ލC<���'��鯯/�愙g�Fbq�^��<Q�����pNg�(ׁ��e;h�L��,y�O@K��V��	�&<�i��	�9b6&�� ���W^�G�zG��rY��q���W�����uݢ���̃5/�ɢ�G�.�@�8�Y�+���Ϫ� ��m{g��z���g����z��ӓ��N�O��ӟ߾zz��רEVDn	���z���8>~�Z�^��3�ⷓ_���5� ��gOON^>��1:9>}{BZ{v�CҗE_���K�����{;ttBE��~��Q8��Pϒ���m�䴓�t:���6�ֻ9�EGf��z&��+&�Q
�01*�;�l4�A/K�ڥZ��_t(�;[Y��F]e�����z��� �f��,ղw���4BŠI�.��+]�2b�(�A���QArHk����ʒ�b�ݍ#��bl��X��+I��tJ��q�~X�Α"��*���f��l�;�س'f� �θ�h~%�*����ұԈ��S���ed6�&�d�t��ˋ/���7���)N���
��ٽ��(B~�
��P��p=��Zƞ����BW�hA�4�w��� BI����؁x&�:b�w�?�?�v~�m{#w\�^�z/��^:����������9�!��7[���m��>D~��C�"�=���[�����h,�8|<�y �jDE|_�ܗ�tѮ&�)kvW��Kj�ueP�̓1:,G��Ձ���u6M�A�}=����g=*\�Bf��᧳h��c̒/��Q���1�M}�b\���Ny�4��c �B�n0P@����(d6o�_[��.�Ufm��SӠ�	��-A�n׭�����`�\\�j��u����P�|�?RӇJ�?!N�e.N�k��
�b��\�d��oR/�0_����Pr��1��_C����뻎� ������6�}b���V�v-!?�5S'��l����	{��g�m�5�d�UD
ޣ5T�Z��E��%(77 ��{f.�0����@0A��H�{���nnv��)��0�S
��F���Й�1�s��x�C>|߲�HZ����7��vJ��2]��L�� 
"��LRD�(��n��JM�s�GMNbn����ej����Y�����`M��>�����)�::��W����ո��\�4����P�t�a�!�W�C��U�t�����L��r_ZU�51RYv�,�����6�C��^~��^��!�E��Cǳ�E�=0E#����(Le�~젞ߞ���,��K4���!�F�WϹm�2q
��F�t��M���͌1N:ơ*N�XE���GdP��*��j3x��犰t#��m��#+H�c��Y�7[q�"�d��ޠ������ i����k�U�:��C�5-#m��[��;]ּ`Ii�>�Qw��U�푣1��B%�a��U��Gq V �L�@�;�@�;DWѐ$���&�2�,�U�����UJmd�6�
\�b�A⟅|V���YӁ�8����#]-+���J,>)���wń�fv7;%�Gѹ�v�(�Cp�;�]�����撥u��%+󭢋e�z0�A����]����k)�D�>�<�=��n�B�Q��$�qKH�B�5���] ?<�K]^�#&0|��y��*;P畾m��Ul��́��[��|�p����)����㡫��P�;?�fPq�c1`w��[�����Z�7��h�]�Í���2.�r�۪�,�P��ڌ�%%�,s�2dz���O�k�����{^?P�v��f�
���C�G�,���O|ݣ�@Sm�I�{ZRٌ3j��HK�l=�8m�J{�Jd`#Ns�j��m6zz��w�!�6Q��eX�FV|��W���
�a�k�YLjf(��G�1+-)�A`M>4�&�F^m�����X���J�V�"�硜�M�rn��U�L��� _�NeJ��%+��A]��Ԩ�p�� 6��X�vy�_�*i�.�����[lm�˼W���2��Z][�Q{y@[��콞r��``o�"�}1j����DĀ�`�����⋑ъ"� ��L��Z{s����q�EW�?�՜���!��;Bz�������0���{����i�Z�K���vC�}�P����]Y`�.ƄMm񬜃r��i#�S��wm�H>C�s�����_
d�<l�*������'��)6�����d���~z�'8H��/n�.���G��˦we:�"f1U�5�;�y�M�o5zU�\R�ln����d�Mխ�ox3vo��nޏo�߽���o޻m�Wˠ�i�	�ݴcg	�1R�jwX(�e�Ux��ht�DҺ�zQ�N4����k��y�x�v�aw�jdA��Ѡ�-��+hXE�:���|,�AΠ}� �K�&k��թ�F6]\���2�qh�p��.�ά���R��\�B�?{��[��cs�C���v��od=�L#�R6�c��:��N1wu�LJ����2��³3�w�J��������;<��I�@� /x�e0ߌq<_̼�O̗1���U��w	qU�p�	����a(��~0ߥ�E�|��rU�)�����b����|��\�w;خU���J��_�Ka����Gý��_�}�hZ�E�� ;�F�k�|`�
�Z��䋺���V��0����u�mY�?� �$���[~��l���� ��.P6��8�� �v��Q�����M4���A�=<�B2
 �΃
��y�!���T�FK~�<�,��e~���#4���3QN������H�ǫt��j�鯦d��C��?T�囈�&��c�$0l�6J���a�B>��T�#n5OE�
�7Nm9��v!�*B�q֦�4d?�OXyۊ�{d�%�	�%0O���ț�6�e�Qb	�`3d�΂Sl�Է����@]$�Aҡ)��ANZ"��_�Q�E��Ӈj�kvb�-Lg'��yj#25����'�K�bϙ%Y3R�@$0�P鹱���$��gs_�P��[V R!�+�1�Ѣ�R6����e-���(���T�gR~���VW�>dIa��b������(i�Szᩈ��侜 ��&��@�4u�Os!�m�_�ISr}�検iR݈븨-�ǔ���/3R�㗲�n\O#B%a����ll֚���ۚ�:w��&�0�Z<uk�*lSˆVFnT����vP��ׅ#)#��3���x�B����d�u}"��TC�s�B����p��X�1m2h��'"4����=p!��uC�Fх$ɷ��ӧqZ��O7��Y|&x�o����mۅ�r�n�
g,�յR,7vip��g�s�u˃�Se�sZ�e��L?s�9/a�:W�����s���ˢX!p���KE��nk�.�q�	���v��f��an�uN��ϳ�h�f7��䋍�{�1bK0^�\ܜ^��d�3�̢$\_y�4��3���y��o8��?u������ww ��oC`��l����z�[�x4rȌ��������%��_�1۞�R���Q��~r�C~�:GucF7��Z-~s�;P;���+�̮P�2�� ����LP�ڇA��H��F�|�ߐ/�j��L<$����*����A&�s�T�M���=�L�X���]��@h�����=�:~��ĸ��r�j����P����{���}���F�υe+�	�5۔ʻ���*�����bCiW�ʌfNd	,��6��7Qvj��D���a4Xv����ݢZ]��`��Yg�$Vʾ.C+�%) ��a�
�wZ�jvD����Y�f��]�і
�,��E��/u������	3}�!S�F�� ���Zy��N{5X�J�#�Ê�
�x�
����u(��z���T��^h�d}P>|�H	��P�+����W���{a(P.�\iT���2���})�1�é�R�-�G�,�ލ����58q���;��h�'�[�c$l˕�O�C��R�6��	hS̽?�����ƣd�͆�\;��&?���m��1�vkc�nc��޸9�w#|���>)y�q�	��U�
KBC��vuY~��d�T�Ah��h�ʓDF�lnꑳ���� �&��.!Z#�W7��,>h�Dk�<��a���){��o�-��'D,��r���ث�Ժ<Dd����5c,	}��!'��W���(˵`1�k�[������;\�dsS8}]F��Qt=]w�<#�Ѫ��;��*�f n�Y��z���u������cw����6D��	-�d��(�Ǩ?�?6�u���j'�9�Υ)1�����6	��tb{����wj���u� ��'�G@�� 4/
��	k����*���8�˗��p�ƥ[�������0<�� S�U}��}ͤY�ױW�ᖀ�  I^!0Q������|��D߹�u�9�,T��X_����M���������,L��q{t>�o��K_���ln;��������ś�ӳgO?}{rlIf��W�5��6�f5)�Rt���H5�|4n~�]G�I*�k؈�lQ��u�Skt�n���@�hܣ��bpHՖ��b#�/GK��w�19݈A�U�e�Q}�/﹫�4�)Ƥ"���,����+9H\[��ZM�l��+EO�`*\2~rƮK�@ހ@>R�:����R��W���;"J9W�G�m
�{�L�z���6S���w�����q�#�C�G�9��m+G�c$/}J&a�v2L�q�纜���h�!��1�U��>��e��WUA+��.�'34;�X�T�r�kb��PK�l�\R����[��r�y(�����e��3�-@�*��h��*Rh�}�ʘHm٢�(6�P�`}��7��>ǋt��2֞��8kZ��"����\��V#��&���ъg-�*�.�"�d�n� ��< V�ס�a"�̭]�Z��>X��c^�l"�,����ZΎ)�<8R�g�����ݽ�5�-�t҇e@�
�/���C�ñ�&U����U�1��k�+-�!*�Lp��]J�W^��M��d54Qo���Gm'U�!�`��66z�����/��l�v����s?=aˍ⨖U��UL�Y��@��GW8��X�s�	F*�Y^�y\�K,�+z��S�tb��c�L�%) B�>�"W)�<�w��u�K�D�蚏���M�\f.}A8����.�쾫���nD�ܘp�k[���ص��,7�d6,_�eV&3x+5�F.����m}��.úMhK�:b���k�UClZv�i%� ���TT�4~��,��!X~�]i���$���J�ա���N-�vdNmf���C�#�j���um���AioA���!\��Oo��90��d%��\�K)�3L_39ܲ�I.�10C�3<��ˉfi�@�����t�3
+��n�Y2t����i ~�S:�o���^T��9�E�z�]DHm�t��s����ZDm�>ܹ���JI
�V��-��_�`+;�B�E��{�2M��{=�Ь,�R̅�p�����Oj`��Oߜ"�a�p�ӛG�́��6����As!WS����]����1�C&r{��c��L��3p�`"(.b���>�0���k���+��ľ2���s��]v�Y��_[i��S��v����Kl����Ne�xW5�x�]��ħ�*�q䭵{ɥ�saX�c]+��9]�]-��z�=3ۛv]/�*����xG��e��@���W�΢���&�xL�a0W���S-#B�4��?��*8Gm]F�4�k��N8�.j?c�� �j�m��sPȔ��!�-x�B=O���G8;�?b�<¥����}�[���ew�w��C�j?iA >�b߆��0����Ӥ�.I�3zN>�5����ä:�*��):��d1Oe�r��a�<�ȶC1��)�FC�4��4�D��|��_ћč�g���m��ԋ�^�2F��5����M��r���d�
~	DK���) �(PWB��yeY� �'���띺0*&˶��u%	�������өQ��NF~-(��ee����:���~$�M(m���y�OtOf��� �������������7dq �쁌�����櫊�4�,���a<��;H8���((TA1�U��J��ģG��ul>��ˎ��g�rԵ�I��!�����̹ѩ4A�cޥdL��UM��nU�22T��hY)��
_s�%[� 4g�N��'���+b��K���煏�lɇ���k�i��0�T�V�v��s=�8x�����n��С�:�?-��:4Ԑ�ل?m�Ӆ?=�����(:$.ewp�h�aɲ�0܆HZ8
��F�7�w�����n�.$�b������y�k��,�rF���o���v� ����3����j0�yL*�աʰm�o�p�*��3�-EvtnE�|F��t�7���z��=�go�����9<8z?J���9۶Sn�*�5�Ύ= 	H�L����8`���^8��x~��-@S#@��2�'��qJ�AZ�9�H�e<���(]��ñ@�|�?�9��	���M�g}���T��s��٧}��b:'z��XE�^A����\'^�_z!�U&&}�'C�hx�K,f2s���S6xp��a��#�QZ.��>�O9�b�%���F|I�F�>Ȗ������O�W^�G"4��f��saro��PK   �SV_
G�,  �E  #   parser-parsedown/ParsedownExtra.php�ko�8�{~�	*)�#��v۸M�k�� M�h�l�!Kt,T��z$)�?v��ݐԃ�HJNr8p]l�X��p�3���f����%��/n�b?����}����*�6��pxd�|1���'y�o����,@۞���n�!�J�
���"(�y��l��2C�R��h'k7⨇n|GA>]|8�|u��A��7Cwn�� ͒`�g�tw@<R��y�a��>�S�x�������Ќ#��z�v�?v�/����$j�{d������5���y���|N&���}��?��B�Z�M��������F��#�A�СS-���?�*��P�9����ll[��{$8E.
�'�Q�e}h�W�v��{���㿄�������Ț����/�( [~	XcӢ�r��b��ۀJ���_����w���,�3��j�-0(��,1��VH�$q���(]��V�1�f���brf#k7��*7/�	��[˞ a妗g��ջ�@9'�-k��Y��@o

YlJ��o�E��@A��bz�|S#c?רp��D(�{[> O�rܨ1����^L�"�5Nn0�-��YpQ���&�7�oB�ö5|7���4=x��Z�d��X n��~%U91� Mq%�Z�?��;�5e��,��Pͼ�<�\Y<�yz�s�Sޠ�����j�Lp�'Q�E�KT���+�	o���&R=p���-�r���Wy2*p��
���t:�'�tv4A�}��|�#"�H&�Nb{tN��UFp#�I�f2�͈ �_�d�S� 5[[xF�X��7`��Ǡ�9�	�K)P����(����3�'��8f�؃}�q|ncL�.pH��1�ׄ�;
`�T`���6�~���Gy��^�L�a�u�CP���&r#���ຖ��8J��<�ob��$���(9����1�����kG���!�rFq�
ğE�A(�`�O8L�Q�$"�Hx�XoBL�M��AaRRΌ����p6!�TTzˋ�E�
K�|�������O$/T�����&1�k7��u�A��W'�\d�1%H:ݛ�ݬ�GXoU���5�\��ВxXf�9[�	�S�&��߄P?�D!{�a
D� �
�B���nr���yT$et���P��Om�Ĥ&���:�̝8�T����>t��g�HrW�~
�tEu`���P@��{�{u��ӈ*��@b~A�X�3�?�eB&js'#�f'r��<�:�Y��z�S�����hp�#9�a[�m]��vQ��|_2�J�Y|e��c������]ϛ%�D4�@��僑��"�i�H��T�E�'	�:���郣̟����4��r柱���l|F�/W�R�V�+�b�U� a��Y���M��l������iV��ց�P�:�E���З�g��r~~uv=�p���ׯg���-������O��GO�!e>����C��ǖ�r�q���ֿ��9�����Kb}�sɺFub0��`���R�� 
���Sw�/!axd�/V����dzן�;�	��E��9[���9��ܞ���G���ʬ@@Y�w�Lj���N�r4gIE��Ƿ�>�[V-�g��ej���To��;T<-u�-eJ�;"���mMg1m�i�^u$2 ��M�b2��6g���K��%�<ix�J'6�`Y��Bᰴ�j�!����V��p�)��6|����ᝎ�)������d }���$�G���̥I�ҵ����Ԅ�c��7j�?�$L�������mso1�,�w��{���)��{�c&	��!,�ਥ��\�t#��8L!$ry���r�@�f�KI����~�4�줠�ّS�7e��Rdq�n���Vl1��'����{f�>P�sǪ:+Mb��{c�<69��D��`0|� �Ī�ne�
Y�n�V���&�_�I�M1*��꺮�7#vu�Uр���=2�l�������) H�%�H,k���^'g�R7t�kRt�Y�|:퓲qߡI�5J`��A�nY�i �cP���4���H�Z�i�Q��9[�k%�=]�����Rw��#XE�s�P��Ļ������.�]F%%=d���d�}�h�׷>[Kv벰p4:A�'F��llar�(�μ�=�.8�=A!A�Ǣ[����ŗ�#!���%�e _��U@=�@���I�@(厠����h<��.�� B$�QR߿��F��
��F0h&��HV鄗'DX����X	p��"�ؠ)Nx<v���S3�b��_k��W^��p�<:""��f������k@����u0)5� �eh9k�*|����Np�H4~P�����Y�y'JO��c�C��
f);;v��o��E=&���KHs=�|յ~��He�ƭo1�&NKi5����d��W���d�1�i
�8g�ei�k4������S�0��N7���WX��$}�� I3��Q`�g�K	�t4��CK�T6�M�~͂]�l����(�^��{��M,���7 YCY�]�������}���/|t}�n�1�p�)/:�;hfO��p����;P���K��G���#����vE	neMQW��?�m[5U���	�D�#��3q��g�N�RB�����I�B�K��y�%�T2S������o���sy��e�W\!t��hl�U��m����}�Q�d�E��`{�k�YT͍���F�sn�B'$�Z>�vD�sk �w�Ԏ���,d9��>oM�O7�b��.mW�>���!_��/zS��j!&"4im����z�����Oc-K�0���\DWň��ZqY�t�Tu[m4�"�4�|�TV��QE�kqP���	0��hV^Z�[ꍵ�ږ�7��<���R2Ib�x86���gP]n*sD%k�4�N�%��6���Lb�x�Ca��s��^]
D>7�:��C�O��M����a�3��������7�l�T/�)��.�\r�K����^��oϥ�0�؅!}_NF�C}4*.���|����e~v�����t�ہ6��o�TPլ�n��f�N1�S֜&��T[0���E�>4@�q��n��zb�@��ي���h���o 	S��ƬdEw�.��`��d�#)���Uon�8��'KI��߇���7o_��:<��s�&��p��xN����PM�Ij��%(��_.?�^^�n�ef��=N#:|;z�Z&D<&�_/�ś�sy�z/�J���/?��>__\_�]�7K�>￑^6P���Oo߾��Aw��q�>�H�X��5l�a��->�c��`cXI[ժ�K��t�{H�B�bq�kV5.I�O�����r|�E�k�[��C������G����\�gaFW���K\H�[̄Gq����6&W���4Yd2��mm��3G�:���dg@��0S9�H���̍</��({>����!K�?&[~�����#��64���:]�2����[ua}�}�{.8gt��G�M�E��o	 �D�Eb��*���9�����Yš�������o�(�i��U� V׿@޺'�P�[L��"�����NɌ�������R,-w9��8����Q��?p����W̄�d�tw0���w�r!� �v�PK?   �SV�@�  GY             ��    page-edit/diff.min.jsPK?   �SV�U�
  8             ��>  page-edit/awesomplete.min.jsPK?   �SVy&wH�  �             ��#*  page-edit/awesomplete.min.cssPK?   �SV���g#  E�             ���,  parser-parsedown/Parsedown.phpPK?   �SV_
G�,  �E  #           ���P  parser-parsedown/ParsedownExtra.phpPK      u  �^    