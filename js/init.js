 less = {
		env: "development",
		async: false,
		fileAsync: false,
		poll: 1000,
		functions: {},
		dumpLineNumbers: "comments",
		relativeUrls: false,
		//rootpath: ":/a.com/"
	  };

jQuery(function($) {
  //CERCLE_CONFIGURATION
	var gestion = 'http://gestion.cercledesbenevoles.fr/';
	window.gestion = 'http://gestion.cercledesbenevoles.fr/';

  window.ParsleyValidator.setLocale('fr');
	var tooltips = $( "[title]" ).tooltip({
      position: {
        my: "center bottom+40",
        at: "center bottom"
      }
    });

	// Dates
	$.datepicker.setDefaults(
	  $.extend(
		{'dateFormat':'dd/mm/yy'}
	  )
	);
	$.datepicker.regional['fr'] = {clearText: 'Effacer', clearStatus: '',
    closeText: 'Fermer', closeStatus: 'Fermer sans modifier',
    prevText: '&lt;Préc', prevStatus: 'Voir le mois précédent',
    nextText: 'Suiv&gt;', nextStatus: 'Voir le mois suivant',
    currentText: 'Courant', currentStatus: 'Voir le mois courant',
    monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
    'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
    monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
    'Jul','Aoû','Sep','Oct','Nov','Déc'],
    monthStatus: 'Voir un autre mois', yearStatus: 'Voir un autre année',
    weekHeader: 'Sm', weekStatus: '',
    dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
    dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
    dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
    dayStatus: 'Utiliser DD comme premier jour de la semaine', dateStatus: 'Choisir le DD, MM d',
    dateFormat: 'dd/mm/yy', firstDay: 0,
    initStatus: 'Choisir la date', isRTL: false};
 	$.datepicker.setDefaults($.datepicker.regional['fr']);

	$( "#jo_date" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
      	yearRange: "1900:"+new Date().getFullYear()
      });

    $( "#date_declaration_jo" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
      	yearRange: "1900:"+new Date().getFullYear()
      });

	$( "#naissance_debut" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
      	yearRange: "1900:"+new Date().getFullYear(),
      	onClose: function( selectedDate ) {
        	$( "#naissance_fin" ).datepicker( "option", "minDate", selectedDate );
        }
      });
	$( "#naissance_fin" ).datepicker({
		changeMonth: true,
      	changeYear: true,
        yearRange: "1900:"+new Date().getFullYear(),
      	onClose: function( selectedDate ) {
        	$( "#naissance_debut" ).datepicker( "option", "maxDate", selectedDate );
        }
      });

    $( "#naissance_debut_president" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
      	yearRange: "1900:"+new Date().getFullYear(),
      	onClose: function( selectedDate ) {
        	$( "#naissance_fin_president" ).datepicker( "option", "minDate", selectedDate );
        }
      });
	$( "#naissance_fin_president" ).datepicker({
		changeMonth: true,
      	changeYear: true,
        yearRange: "1900:"+new Date().getFullYear(),
      	onClose: function( selectedDate ) {
        	$( "#naissance_debut_president" ).datepicker( "option", "maxDate", selectedDate );
        }
      });

    $( "#date_paiement_debut" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
        yearRange: "2010:"+new Date().getFullYear(),
      	onClose: function( selectedDate ) {
        	$( "#date_paiement_fin" ).datepicker( "option", "minDate", selectedDate );
        }
      });




	$( "#date_paiement_fin" ).datepicker({
		changeMonth: true,
      	changeYear: true,
      	yearRange: "2010:"+new Date().getFullYear(),
     	onClose: function( selectedDate ) {
        	$( "#date_paiement_debut" ).datepicker( "option", "maxDate", selectedDate );
        }
      });


       $( "#saisie_debut" ).datepicker({
      	changeMonth: true,
      	changeYear: true,
        yearRange: "2010:"+new Date().getFullYear(),
      	onClose: function( selectedDate ) {
        	$( "#saisie_fin" ).datepicker( "option", "minDate", selectedDate );
        }
      });

	$( "#saisie_fin" ).datepicker({
		changeMonth: true,
      	changeYear: true,
      	yearRange: "2010:"+new Date().getFullYear(),
     	onClose: function( selectedDate ) {
        	$( "#saisie_debut" ).datepicker( "option", "maxDate", selectedDate );
        }
      });


});
