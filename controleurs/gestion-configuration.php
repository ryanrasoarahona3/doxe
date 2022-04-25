<?php
session_start();
require_once($_SESSION['ROOT'].'libs/requires.php');

$form = new stdClass;

$form->section = 'configuration';
$form->destination_validation = "json/sauve.php";
$form->action = 'gestion';
$form->label_validation = "Modifier";

// $configuration = new document($element);
// $document = $configuration;

$configuration = new Configuration($element);
echo $configuration -> getBalisesFromContenu();

include_once(ROOT.'/vues/'.$controlleur.'.php');
?>
