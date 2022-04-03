console.log("gestion");

jQuery(function($) {

	$.fn.enregistre = function (type) {
		if(type=='ouvre') {
			$( "#dialog-modal-enregistrement" ).dialog({
			  modal: true,
			  closeOnEscape: false,
			  open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog || ui).hide(); }
			});
		}

		if(type=='ferme') {
			$( "#dialog-modal-enregistrement" ).dialog( "close" );
		}

	}


	//////////////////////
	//
	//     Recherche
	//
	//////////////////////

	$("#action_recherche").bind("click", function() {
		console.log("recherche");
    	 var form = $(this).closest("form").attr('id');
    	 var auto = $( "#auto").val();

    	 $( "#affiche_resultats").val(0);
    	 $('#zone_tableau').html();
    	 $( "#nbr_resultats").html();
    	 // Récupère le modèle du tableau
    	 $('#zone_tableau').html($('#modele_tableau').html());

    	 history.pushState({}, '', 'http://'+window.location.hostname + window.location.pathname);

    	// Calcul du nombre de résultats
		console.log($('#'+form).serialize());
    	 $.ajax({
       		url: 'json/recherche.php',
       		type: 'get',
        	dataType: 'json',
        	data: $('#'+form).serialize(),
        	success: function(data) {
					console.log(this.url+this.data);
        			if (data.erreur) {
        				alert(data.erreur);
        				return false;
        			}

        			// Supprimer le retour du formulaire si besoin
        			$('#retour').remove();

        			var vitesse = 500;
             		if (auto == 1) vitesse = 0;
             		$( "form.recherche" ).slideUp(vitesse, function() {
							$( "#action_modif_recherche" ).show();
							$( "#action_recherche" ).hide();
							$( "#zone_resultats" ).show();
							$( "#nbr_resultats").html(data.nbr_resultats);
							if (data.nbr_resultats>1) $( "#label_resultat").html('résultats');
							else $( "#label_resultat").html('résultat');
							$( "#affiche_resultats").val(1);


							// Choix de la méthode d'affichage du tableau en fx du nombre de résultats
							if (data.nbr_resultats < 200) {
								$.ajax({
									url: 'json/recherche.php',
									type: 'get',
									dataType: 'json',
									data: $('#'+form).serialize(),
									success: function(data){
										$('#tab_resultats').dynatable({
												dataset: {
													records: data.records
												}
										});
									},
									 error: function(jqXHR, textStatus, errorThrown){
										alert('ERREUR : '+errorThrown+textStatus+jqXHR);
									}
								});
							} else {
								$('#tab_resultats').dynatable({
								features: {
									paginate: true
								},
								  dataset: {
									ajax: true,
									ajaxUrl: 'json/recherche.php?'+$('#'+form).serialize(),
									ajaxOnLoad: true,
									ajaxMethod: 'GET',
									records: []
								  }
								});
							}

						});
            	},
             	error: function(jqXHR, textStatus, errorThrown){
             	alert('ERREUR : '+errorThrown+textStatus+jqXHR);
             }

    	});

    	return false;
  	});

  	$("#action_modif_recherche").bind("click", function() {

    	 $( "#action_modif_recherche" ).hide();
    	 $( "#action_recherche" ).show();

    	 $( "form.recherche" ).slideDown(1000, function() {
   		 // Animation complete.
  		});
    	return false; //Prevent Default and event bubbling.
  	});


	function initTable(table) {
		// Tables
		$(table).dynatable({
		  table: {
			defaultColumnIdStyle: 'trimDash'
		  },
		   features: {
			paginate: true,
			sort: true,
			pushState: true,
			search: true,
			recordCount: true,
			perPageSelect: true
		  },
		  table: {
			defaultColumnIdStyle: 'camelCase',
			columns: null,
			headRowSelector: 'thead tr', // or e.g. tr:first-child
			bodyRowSelector: 'tbody tr',
			headRowClass: null
		  },
		  inputs: {
			queries: null,
			sorts: null,
			multisort: ['ctrlKey', 'shiftKey', 'metaKey'],
			page: null,
			queryEvent: 'blur change',
			recordCountTarget: null,
			recordCountPlacement: 'after',
			paginationLinkTarget: null,
			paginationLinkPlacement: 'after',
			paginationPrev: 'Précédent',
			paginationNext: 'Suivant',
			paginationGap: [1,2,2,1],
			searchTarget: null,
			searchPlacement: 'before',
			perPageTarget: null,
			perPagePlacement: 'before',
			perPageText: 'Afficher ',
			recordCountText: 'Affiche ',
			processingText: 'Recherche...'
		  },

		  dataset: {
			ajax: false,
			ajaxUrl: null,
			ajaxCache: null,
			ajaxOnLoad: false,
			ajaxMethod: 'GET',
			ajaxDataType: 'json',
			totalRecordCount: null,
			queries: null,
			queryRecordCount: null,
			page: null,
			perPageDefault: 10,
			perPageOptions: [10,20,50,100],
			sorts: null,
			sortsKeys: null,
			sortTypes: {},
			records: null
		  },
		  /*
		  // Built-in writer functions,
		  // can be overwritten, any additional functions
		  // provided in writers will be merged with
		  // this default object.
		  writers: {
			_rowWriter: defaultRowWriter,
			_cellWriter: defaultCellWriter,
			_attributeWriter: defaultAttributeWriter
		  },
		  // Built-in reader functions,
		  // can be overwritten, any additional functions
		  // provided in readers will be merged with
		  // this default object.
		  readers: {
			_rowReader: null,
			_attributeReader: defaultAttributeReader
		  },
		  */
		  params: {
			dynatable: 'dynatable',
			queries: 'queries',
			sorts: 'sorts',
			page: 'page',
			perPage: 'perPage',
			offset: 'offset',
			records: 'résultats',
			record: null,
			queryRecordCount: 'queryRecordCount',
			totalRecordCount: 'totalRecordCount'
		  }

		});
	}

	//////////////////////
	//
	//  Autocompletion
	//
	//////////////////////

	/*
	$('#nom_association').autocomplete({
    	source : '/json/autocomplete.php?section=association',
    	 minLength : 2
	});
	*/

	$('#presse').autocomplete({
    	source : '/json/autocomplete.php?section=presse',
    	 minLength : 2
	});


	// Autocomplete avec sélection

	$.fn.autoCompleteSection = function (section,taille,multiple,destination) {
    	// json = adresse de la page diffusant les données
    	// taille = taille mini pour lançer la recherche
    	// multiple = choix multiple autorisé ou non
    	// destination = input de destination de la valeur sélectionnée

    	var nom = this.attr('id');

    	$('#'+nom).autocomplete({
    	 source : gestion+'json/autocomplete.php?section='+section,
    	 minLength : taille,
    	 open: function() { $('ul.ui-autocomplete.ui-front.ui-menu.ui-widget.ui-widget-content.ui-corner-all').width(300) }  ,
    	 select: function( event, ui ) {
    	 	$('#'+nom).removeAttr('value');

    	 	if (destination.length>0) $('#'+destination).val(ui.item.id);

    	 	$( "#"+nom+"_resultat" ).append( '<li class="'+nom+'_'+ui.item.id+'">'+ui.item.value+'<a href="#" class="'+nom+'_remove ui-icon ui-icon-circle-close right"></a><input type="hidden" name="'+nom+'_tab_resultat[]" value="'+ui.item.id+'" class="'+nom+'_'+ui.item.id+'"></li>' );
    	 	if (multiple==false) 	$('#'+nom).hide();
    	 	$('#'+nom).val('');
    	 	$('#'+nom).trigger( "selection", [ "Custom", "Event" ] );
    	 	return false;
    	 }
		});

		$( "#"+nom+"_resultat" ).on( 'click',  'a.'+nom+'_remove', function(event) {
			$( this ).parent().remove();
			if (multiple==false) $('#'+nom).show();
			if (destination.length>0) $('#'+destination).val('');
			$('#'+nom).trigger( "suppression", [ "Custom", "Event" ] );
			return false;
		});

	};


	//////////////////////
	//
	//  Boutons d'action
	//
	//////////////////////


	// Supprimer fichier
	$(document.body).on('click','a.suppr_fichier',function(event) {
		event.preventDefault();
		var element = $(this).attr('form-fichier');
		$('#file_upload').uploadify('cancel', element);
		return false;
	});

	// Afficher plus
	$(".modifier").on('click','a.plus',function(event) {
		var action = $(this).attr('form-action');
		var type = $(this).attr('form-type');
		var id = $(this).attr('form-id');
		var urlLoad = "/controleurs/contenus/"+action+'/'+type+'.php?plus=1&id='+id;

		$("#contenu_formulaire").empty().off("*");
		$("#contenu_formulaire").load(urlLoad, function() {
				$('#conteneur.detail').hide();
				$('#loader').hide();
		});
		return false;
	});


	// Avancement d'état dans un formulaire
	function avanceInscription (type) {
		$("html, body").animate({ scrollTop: 0 }, "slow");

		if (window.etapeInscription > 0) $('.etape'+window.etapeInscription).hide();
		$('.actif').removeClass('courrant');

		if (type=="suite") {
			window.etapeInscription ++;
			$('#lien'+window.etapeInscription).removeClass('inactif');
			$('#lien'+window.etapeInscription).addClass('actif');
		}
		else if (type=="retour") window.etapeInscription --;
		else window.etapeInscription = type;

		$('#lien'+window.etapeInscription).addClass('courrant');

		$('.etape'+window.etapeInscription).show();

		if (window.etapeInscription == 1) $('.retour_inscription').hide();
		else $('.retour_inscription').show();

		if ($("article.etape").length == window.etapeInscription) {
			$('#action_valider').html('Valider');
			$('#action_valider').show();
		}
		if (window.etapeInscription < $("article.etape").length) {
			$('#action_valider').html('Étape suivante');
			$('#action_valider').show();
		}

	}

	// Navigation formulaire
	$('#nav_form' ).on('click','li.actif',function(event) {
		var page = $(this).attr('id').substr(4);
		avanceInscription (page);
	});

	// Boutons génériques
	$(document.body ).on('click','button',function(event) {
		if (!$(this).hasClass('ajouter_externe') && !$(this).hasClass('bouton-ajouter')) {

			event.preventDefault();

			var action = $(this).attr('form-action');
			var type = $(this).attr('form-type');
			var type_utilisateur = $(this).attr('form-type-utilisateur');
			var id = $(this).attr('form-id');

	   		if (action) {

				// Inscription
				if (action=='inscription') {
					var form = $(this).closest("form").attr('id');
					var validation = false;

					if (type=='debut') {
						$('.choix_formulaire').hide();
						$('#action_valider').html('Étape suivante');
						$('#zone_validation_inscription').show();
						$('#nav_form').show();
						window.etapeInscription = 1;
						$('.etape'+window.etapeInscription).show();
						$('#lien'+window.etapeInscription).removeClass('inactif');
						$('#lien'+window.etapeInscription).addClass('actif');
						$('#lien'+window.etapeInscription).addClass('courrant');
					}
					if (type == 'retour') {
						avanceInscription (type);
					}

				}

				// Export CSV
				if (action=='export-csv') {

					/*
					$.post('/json/export_csv.php','', function(retData) {
						alert(retData.url);
					  $("body").append("<iframe src='" + retData.url+ "' style='display: none;' ></iframe>");
					});
					-*/

					 var $preparingFileModal = $("#preparing-file-modal");

					$preparingFileModal.dialog({ modal: true });

					$.fileDownload('/json/export_csv.php', {
						successCallback: function(url) {

							$preparingFileModal.dialog('close');
						},
						failCallback: function(responseHtml, url) {

							$preparingFileModal.dialog('close');
							$("#error-modal").dialog({ modal: true });
						}
					});
					return false;
				}


				// Ouverture nouvelle page
				if (action=='lien') {
					var url = $(this).attr('form-element');
					window.location.href=url;
				}

				// Ouverture nouvelle page
				else if (action=='telecharger') {
					if ( $(this).hasClass('carte') ) {
						$(this).removeClass('carte');
						var id_laf = $(this).attr('form-id-laf');
						$('.zone_carte.id_'+id_laf).append('&nbsp;<strong style="background-color:rgb(20, 183, 202);color:rgb(228, 228, 229);padding:5px;">OK</strong>');
					}
					var fichier = $(this).attr('form-element');
					window.open('/telecharger/'+fichier,'_blank');
				}

             	// Email
				else if (action=='mail') {
					var destinataire = $(this).attr('form-element');
					$( "#dialog-modal-email  #retour").html('');
        			$( "#dialog-modal-email form").show();

					$( "#dialog-modal-email #envoyer_email").append('<input type ="hidden" name="destinataire" id="destinataire" value="'+destinataire+'"> ');
             		dialogEmail = $( "#dialog-modal-email" ).dialog({
					  autoOpen: true,
					  height: 450,
					  width: 600,
					  modal: false,
					  buttons: {
						"Envoyer": envoyerEmail,
						Cancel: function() {
						  dialogEmail.dialog( "close" );
						}
					  }
					});
             	}

             	// Envoyer des fichiers attestations
				else if (action=='envoyer_multiple') {
					$("#dialog-modal-envoyer form")[0].reset()

					$( "#dialog-modal-envoyer  #retour").html('');
        			$( "#dialog-modal-envoyer form").show();

        			var code = $(this).attr('form-element');
					var type = $(this).attr('form-type');
					var date = $(this).attr('form-date');
					var contenu = '';

					if (type==1) {
						contenu += '<input type="checkbox" name="fichiers[]" id="fichiers[]" value="AAS_'+code+'"> Attestation association<br class="clear">';
						contenu += '<input type="checkbox" name="fichiers[]" id="fichiers[]" value="AAE_'+code+'"> Attestation élus<br class="clear">';
						contenu += '<input type="checkbox" name="fichiers[]" id="fichiers[]" value="AAB_'+code+'"> Attestation bénévoles<br class="clear">';
					} else if (type==2) {
						contenu += '<input type="checkbox" name="fichiers[]" id="fichiers[]" value="ACO_'+code+'"> Attestation collectivité<br class="clear">';
						contenu += '<input type="checkbox" name="fichiers[]" id="fichiers[]" value="ACB_'+code+'"> Attestation bénévoles<br class="clear">';
					}
					$( "#dialog-modal-envoyer #zone_choix").html(contenu);
					$( "#dialog-modal-envoyer h3").html('Envoyer les attestations '+date);

             		dialogEnvoyer = $( "#dialog-modal-envoyer" ).dialog({
					  autoOpen: true,
					  height: 450,
					  width: 600,
					  modal: false,
					  buttons: {
						"Envoyer": envoyerAttestations,
						Cancel: function() {
						  dialogEnvoyer.dialog( "close" );
						}
					  }
					});
             	}

             	// Envoyer un fichier
				else if (action=='envoyer_fichier') {
					$("#dialog-modal-envoyer-fichier form")[0].reset()

					$( "#dialog-modal-envoyer-fichier  #retour").html('');
        			$( "#dialog-modal-envoyer-fichier form").show();

        			var code = $(this).attr('form-element');
					var type = $(this).attr('form-type');

					if (type == 'personnes_achat') $( "#dialog-modal-envoyer-fichier #zone_president").html('');
					else $( "#dialog-modal-envoyer-fichier #zone_president").html('<input type="checkbox" name="president" value="1"> Président actuel de l\'association <br class="clear">');

					var contenu = '<input type="hidden" name="fichier" id="fichier" value="'+code+'"> ';
					contenu += '<input type="hidden" name="type" id="type" value="'+type+'"> ';

					$( "#dialog-modal-envoyer-fichier form").append(contenu);
					//$( "#dialog-modal-envoyer-fichier h3").html('Envoyer l\'attestation '+date);

             		dialogEnvoyer = $( "#dialog-modal-envoyer-fichier" ).dialog({
					  autoOpen: true,
					  height: 450,
					  width: 600,
					  modal: false,
					  buttons: {
						"Envoyer": envoyerFichier,
						Cancel: function() {
						  dialogEnvoyer.dialog( "close" );
						}
					  }
					});
             	}

             	// Envoyer un mot de passe
				else if (action=='envoyer_password') {
					$("#dialog-modal-envoyer-password form")[0].reset()

					$( "#dialog-modal-envoyer-password  #retour").html('');
        			$( "#dialog-modal-envoyer-password form").show();

        			var id = $(this).attr('form-element');
					var type = $(this).attr('form-type');


					var contenu = '<input type="hidden" name="id" id="id" value="'+id+'"> ';
					contenu += '<input type="hidden" name="type" id="type" value="'+type+'"> ';

					$( "#dialog-modal-envoyer-password form").append(contenu);
					//$( "#dialog-modal-envoyer-fichier h3").html('Envoyer l\'attestation '+date);

             		dialogEnvoyer = $( "#dialog-modal-envoyer-password" ).dialog({
					  autoOpen: true,
					  height: 450,
					  width: 600,
					  modal: false,
					  buttons: {
						"Envoyer": envoyerPassword,
						Cancel: function() {
						  dialogEnvoyer.dialog( "close" );
						}
					  }
					});
             	}

             	// Télécharger des fichiers
             	else if (action=='telecharger_multiple') {
             		var code = $(this).attr('form-element');
					var type = $(this).attr('form-type');
					var date = $(this).attr('form-date');
					var contenu = '';

					if (type==1) {
						contenu += '<button type="button" form-action="telecharger" form-element="AAS_'+code+'"  class="action telecharger " ></button> Attestation association<br class="clear">';
						contenu += '<button type="button" form-action="telecharger" form-element="AAE_'+code+'"  class="action telecharger " ></button> Attestation élus<br class="clear">';
						contenu += '<button type="button" form-action="telecharger" form-element="AAB_'+code+'"  class="action telecharger " ></button> Attestation bénévoles<br class="clear">';
					} else if (type==2) {
						contenu += '<button type="button" form-action="telecharger" form-element="ACO_'+code+'"  class="action telecharger " ></button> Attestation collectivité<br class="clear">';
						contenu += '<button type="button" form-action="telecharger" form-element="ACB_'+code+'"  class="action telecharger " ></button> Attestation bénévoles<br class="clear">';
					}
					$( "#dialog-modal-telecharger #zone_choix").html(contenu);
					$( "#dialog-modal-telecharger h3").html('Télécharger les attestations '+date);

					dialogTelecharger = $( "#dialog-modal-telecharger" ).dialog({
					  autoOpen: true,
					  height: 250,
					  width: 350,
					  modal: false,
					  buttons: {

						Cancel: function() {
						  dialogTelecharger.dialog( "close" );
						}
					  }
					});
             	}


				// Supprimer un élément dans une page de liste
				else if (action=='supprimer') {

					var donnees = $(this).attr('form-element');

					$( "#dialog-modal" ).dialog({
						modal: true,
						 open: function() {
						  var markup = 'Souhaitez-vous supprimer cet élément ?';
						  $(this).html(markup);
						},
						buttons: {
							"Supprimer": function() {

								$.ajax({
									url: 'json/supprime.php',
									type: 'post',
									dataType: 'json',
									data: donnees,
									success: function(data) {
										// Retour page associations
										if(data.retour=='personnes_associations') {
											$("#contenu_formulaire").load('/controleurs/forms/associations/ca.php?id='+data.id+'&id_lien='+data.annee);
											$("#contenu_ca").load('/controleurs/contenus/associations/conseil_administration.php?id='+data.id);
										}
										if(data.retour=='personnes') {
											window.location.href='/personnes';
        									return false;
										}

										if(data.retour=='associations') {
											 window.location.href='/associations';
        									return false;
										}

									 },
									 error: function(jqXHR, textStatus, errorThrown){
										alert('ERREUR : '+errorThrown+textStatus+jqXHR);
									 }
								});
								$( this ).dialog( "close" );
							},
							"Annuler": function() {
								$( this ).dialog( "close" );

								return false;
							}
						  }
					});
				}


				else {
					var urlLoad = "/controleurs/forms/"+action+'/'+type+'.php?id='+id+'&action='+action+'&type='+type+'&type_utilisateur='+type_utilisateur;
					if ( !!$(this).attr('form-id-lien')  ) urlLoad+='&id_lien='+ $(this).attr('form-id-lien');

					//alert(urlLoad);

					$('#loader').show();
					$("#contenu_formulaire").empty().off("*");
					$("#contenu_formulaire").load(urlLoad, function() {
						$('#conteneur.detail').hide();
						$('#loader').hide();
					});
				}
			}
			return false;
		}

	});

	/*
	$(document.body).on('click','button',function(event) {
		// Affichage du détail
		if ($(this).hasClass('action') ) {
			var action = $(this).attr('form-action');
			var type = $(this).attr('form-type');
			var id = $(this).attr('form-id');

			// Ouverture nouvelle page
			if (action=='lien') {
				var url = $(this).attr('form-element');
				window.location.href=url;
			}
		}
	});
	*/

	// Annuler
	$(document.body).on( 'click',  '#action_annuler', function(event) {
		$("#contenu_formulaire").empty().off("*");
		$('#conteneur').show();
		return false;
	});



	//////////////////////////////
	//
	//  Validation formulaire
	//
	//////////////////////////////


	// Envoyer email

	function envoyerEmail() {
		$.ajax({
       		url: 'json/email.php',
       		type: 'post',
        	dataType: 'json',
        	data: $('#envoyer_email').serialize(),
        	success: function(data) {
        		if (data.etat == false) {
        			$( "#dialog-modal-email  #retour").html('<span class="alerte">'+data.message+'</span>');
        		} else {
        			$(".ui-dialog-buttonpane button:contains('Envoyer')").hide();
        			$( "#dialog-modal-email  #retour").html('<span class="alerte">'+data.message+'</span>');
        			$( "#dialog-modal-email form").hide();
        		}

             },
             error: function(jqXHR, textStatus, errorThrown){
             	alert('ERREUR : '+errorThrown+textStatus+jqXHR);
             }
    	});
	}

	// Envoyer des attestations

	function envoyerAttestations() {
		$.ajax({
       		url: 'json/email.php',
       		type: 'post',
        	dataType: 'json',
        	data: $('#envoyer_fichier').serialize(),
        	success: function(data) {

        		if (data.etat == false) {
        			$( "#dialog-modal-envoyer  #retour").html('<span class="alerte">'+data.message+'</span>');
        		} else {
        			$(".ui-dialog-buttonpane button:contains('Envoyer')").hide();
        			$( "#dialog-modal-envoyer  #retour").html('<span class="alerte">'+data.message+'</span>');
        			$( "#dialog-modal-envoyer form").hide();
        		}

             },
             error: function(jqXHR, textStatus, errorThrown){
             	alert('ERREUR : '+errorThrown+textStatus+jqXHR);
             }
    	});
	}

	// Envoyer un mot de passe
	function envoyerPassword() {
		$.ajax({
       		url: 'json/password.php',
       		type: 'post',
        	dataType: 'json',
        	data: $('#envoyer_password').serialize(),
        	success: function(data) {

        		if (data.etat == false) {
        			$( "#dialog-modal-envoyer-password  #retour").html('<span class="alerte">'+data.message+'</span>');
        		} else {
        			$(".ui-dialog-buttonpane button:contains('Envoyer')").hide();
        			$( "#dialog-modal-envoyer-password  #retour").html('<span class="alerte">'+data.message+'</span>');
        			$( "#dialog-modal-envoyer-password form").hide();
        		}

             },
             error: function(jqXHR, textStatus, errorThrown){
             	alert('ERREUR : '+errorThrown+textStatus+jqXHR);
             }
    	});
	}



	// Envoyer une attestation

	function envoyerFichier() {
		$.ajax({
       		url: 'json/email.php',
       		type: 'post',
        	dataType: 'json',
        	data: $('#envoyer_fichier_unique').serialize(),
        	success: function(data) {

        		if (data.etat == false) {
        			$( "#dialog-modal-envoyer-fichier  #retour").html('<span class="alerte">'+data.message+'</span>');
        		} else {
        			$(".ui-dialog-buttonpane button:contains('Envoyer')").hide();
        			$( "#dialog-modal-envoyer-fichier  #retour").html('<span class="alerte">'+data.message+'</span>');
        			$( "#dialog-modal-envoyer-fichier form").hide();
        		}

             },
             error: function(jqXHR, textStatus, errorThrown){
             	alert('ERREUR : '+errorThrown+textStatus+jqXHR);
             }
    	});
	}


	// Validation des modifications

	$( document.body ).on('click','#action_valider',function(event) {
		var form = $(this).closest("form").attr('id');
		var validation = false;
		var pre_validation = true;

		// Pré-validation
		if ( $.isFunction($.fn.preValider) ) pre_validation = $.fn.preValider();

		// Formulaire multi étapes
		if ($("article.etape").length > 1) {

			if (false === $('#'+form).parsley({ excluded: "input[type=button], input[type=submit], input[type=reset],  [disabled]" }).validate('block' + window.etapeInscription))
				return;
			else validation = true;

			// Si la dernière étape n'est pas atteinte, on ne valide pas le formulaire
			if ($("article.etape").length != window.etapeInscription) {
				validation = false;
				avanceInscription ('suite');
			}
		}
		// Formulaire une seule étape
		else {
			if (false === $('#'+form).parsley({ excluded: "input[type=button], input[type=submit], input[type=reset],  [disabled]" }).validate())
					return;
			else validation = true;
		}

		if (( validation==false) || (pre_validation==false)) return false;


		$( "#dialog-modal-enregistrement" ).enregistre('ouvre');

		// Sauvegarde des données
		for ( instance in CKEDITOR.instances ) CKEDITOR.instances[instance].updateElement();

		$.ajax({
       		url: gestion+$('#destination_validation').val(),
       		type: 'post',
        	dataType: 'json',
        	data: $('#'+form).serialize(),
        	success: function(data) {
        		$("#contenu_formulaire").off( "click", "#action_pre_valider" );
				$("#contenu_formulaire").empty().off("*");
             		$( "#dialog-modal-enregistrement" ).enregistre('ferme');


             	if (data.action=='modifier') {
             		window.location.href='/'+data.section+'/detail/'+data.id;
             		/*
             		Simplification du retour : rechargement de la page

             		if (data.section=='distinctions') {
             			window.location.href='/distinctions/detail/'+data.id;
             		}

             		else if (data.section=='personnes') {
             			window.location.href='/personnes/detail/'+data.id;
             		}
             		else if (data.section=='associations') {
             			window.location.href='/associations/detail/'+data.id;
             		}
             		else {
             			$('#conteneur').show();
             			chargeContenu(data);
             		}
             		*/
             	}

             	else if (data.action=='gestion') {
             		window.location.href='/gestion';
             	}
             	else if ((data.action=='associations_inscription') || (data.action=='personnes_inscription')) {
             		window.location.href=data.destination;
             	}
             	else if (data.action=='ajouter') {
					if ( (data.section =='personnes_annonces') || (data.section =='associations_annonces') || (data.section =='annonces') ) {
						window.location.href='/annonces/?auto&retour';
					}
					else window.location.href='/'+data.section+'/detail/'+data.id;
					//$("#contenu_"+action).load('/controleurs/contenus/'+data.section+'/'+data.action+'.php?id='+data.id);
				}

				else if (data.action=='conseil_administration') {
					$("#contenu_formulaire").load('/controleurs/forms/associations/ca.php?id='+data.id+'&id_lien='+data.annee);
					$("#contenu_ca").load('/controleurs/contenus/associations/conseil_administration.php?id='+data.id);
				}

             },
             error: function(jqXHR, textStatus, errorThrown){
             	alert('ERREUR : '+errorThrown+textStatus+jqXHR);
             }
    	});
	});

	// Suppression d'un élément sur une page de modification

	$( document.body ).on('click','#action_supprimer',function(event) {
		var form = $(this).closest("form").attr('id');
		$( "#dialog-modal-supprimer p" ).html('Souhaitez-vous supprimer cet élément ?<br> La suppression sera définitive.');
		$( "#dialog-modal-supprimer" ).dialog({
			modal: true,
			 buttons: {
				"Supprimer": function() {

					$.ajax({
						url: 'json/supprime.php',
						type: 'post',
						dataType: 'json',
						data: $('#'+form).serialize(),
						success: function(data) {
							chargeContenu(data);


							if (data.action == 'ajouter') {
								if ( (data.section =='personnes_annonces') || (data.section =='associations_annonces')   || (data.section =='annonces')) {
									window.location.href='/annonces/?auto&retour';
								}
							}

							if (data.section =='distinctions') window.location.href=document.referrer;


							$("#dialog-modal-supprimer" ).dialog( "close" );
				 			$("#contenu_formulaire").empty().off("*");
							$('#conteneur').show();
						 },
						 error: function(jqXHR, textStatus, errorThrown){
							alert('ERREUR : '+errorThrown+textStatus+jqXHR);
						 }

					});
				  	$( this ).dialog( "close" );
				},
				"Annuler": function() {
				 	$( this ).dialog( "close" );
				 	$("#contenu_formulaire").empty().off("*");
					$('#conteneur').show();
					return false;
				}
			  }
		});
	});

	function chargeContenu(data) {

		// Header
		if ((data.section =='personnes') || (data.section =='associations')) {
			$("#detail").load('/controleurs/contenus/'+data.section+'/detail.php?id='+data.id);
			$("#header").load('/controleurs/contenus/'+data.section+'/header.php?id='+data.id);
		}

		else if (data.section =='personnes_associations')
			$("#contenu_associations").load('/controleurs/contenus/personnes/associations.php?id='+data.id+'&select_annee='+data.select_annee);

		else if (data.section =='associations_personnes')
			$("#contenu_associations").load('/controleurs/contenus/associations/associations.php?id='+data.id);

		else if (data.section =='personnes_amis')
			$("#contenu_amis").load('/controleurs/contenus/personnes/amis.php?id='+data.id);

		else if (data.section =='associations_amis')
			$("#contenu_amis").load('/controleurs/contenus/associations/amis.php?id='+data.id);

		else if (data.section =='associations_annonces')
			$("#contenu_annonces").load('/controleurs/contenus/associations/annonces.php?id='+data.id);

		else if (data.section =='personnes_annonces')
			$("#contenu_annonces").load('/controleurs/contenus/personnes/annonces.php?id='+data.id);

		else if (data.section =='distinctions')
			$("#contenu_distinctions").load('/controleurs/contenus/personnes/distinctions.php?id='+data.id);

		else if (data.section =='representant')
			$("#contenu_ca").load('/controleurs/contenus/associations/representants.php?id='+data.id);

	}


	// Clavier
	$(document).keyup(function(e) {
		// Echap
  		if (e.keyCode == 27) {
  			$('#action_annuler').trigger("click");
  		}
	});



});
