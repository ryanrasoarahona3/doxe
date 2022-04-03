/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referring to this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icones\'">' + entity + '</span>' + html;
	}
	var icons = {
		'icon-plus': '&#xe114;',
		'icon-minus': '&#xe115;',
		'icon-check': '&#xe116;',
		'icon-cross': '&#xe117;',
		'icon-check2': '&#xf00c;',
		'icon-times': '&#xf00d;',
		'icon-search-plus': '&#xf00e;',
		'icon-search-minus': '&#xf010;',
		'icon-trash': '&#xf014;',
		'icon-rotate-right': '&#xf01e;',
		'icon-calendar': '&#xf073;',
		'icon-home': '&#xe612;',
		'icon-partenaires': '&#xe611;',
		'icon-amis': '&#xe600;',
		'icon-distinctions': '&#xe601;',
		'icon-personnes': '&#xe602;',
		'icon-statistiques': '&#xe603;',
		'icon-alertes': '&#xe604;',
		'icon-fichiers': '&#xe606;',
		'icon-cherche': '&#xe607;',
		'icon-edit': '&#xe608;',
		'icon-gestion': '&#xe609;',
		'icon-achats': '&#xe60a;',
		'icon-envoyer': '&#xe60b;',
		'icon-envoyer_fichier': '&#xe60c;',
		'icon-annonces': '&#xe60d;',
		'icon-telecharger': '&#xe60e;',
		'icon-uploader': '&#xe60f;',
		'icon-associations': '&#xe610;',
		'icon-eye': '&#xe605;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
