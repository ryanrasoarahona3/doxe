<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;

$form->section = 'documents';
$form->destination_validation = "json/sauve.php";
$form->action = 'gestion';
$form->label_validation = "Modifier";

$document = new document($element);

// echo "element : ", $element;

include_once(ROOT.'/vues/'.$controlleur.'.php');
?>
<script>
CKEDITOR.replace( 'contenus', {
    	language: 'fr',
	});
  	CKEDITOR.config.disableNativeSpellChecker = false;
  	CKEDITOR.config.extraPlugins = 'scayt';
    CKEDITOR.config.extraPlugins = 'menubutton';
    CKEDITOR.config.extraPlugins = 'dialog';
    CKEDITOR.config.scayt_autoStartup = true;
    CKEDITOR.config.scayt_sLang = 'fr_FR';
    CKEDITOR.config.removePlugins = 'elementspath';

	
    CKEDITOR.config.toolbar = [
    { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste',  '-', 'Undo', 'Redo' ] },
      { name: 'basicstyles', items: [ 'Bold', 'Italic' ] } ,
      { name: 'editing', groups: [ 'spellchecker' ], items: [  'Scayt' ] },
	
	];
</script>