 <?php
require('../libs/init.php');

// Define a destination
//$targetFolder = '/upload/'.$_POST['destination']; // oot
$targetFolder = $_POST['destination']; 
//$targetFolder = ''; // oot.$_POST['destination']; // oot

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	
	$DenyFileTypes = array('exe','bat','sys'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);	
	
	
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$nomFichier = $fileParts['filename'].'_'.time().'.'.$fileParts['extension'];
	$targetFile = rtrim($targetPath,'/') . '/' . $nomFichier;
	
	// Validate the file type
	$DenyFileTypes = array('exe','bat','sys'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);	
	
	if (!in_array($fileParts['extension'],$DenyFileTypes)) {
		if (move_uploaded_file($tempFile,$targetFile)) {
			echo $nomFichier;
		} else echo false;
	} else {
		echo 'Invalid file type.';
	}
	
	
	exit();
}
?>