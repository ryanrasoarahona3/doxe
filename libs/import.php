<?php


function traite_nom($nom) {
	$nom = str_replace("\\'","'",$nom);
	return $nom;
}


function traite_pays($nom) {
	$nom =str_replace(' France','',$nom);
	$nom =str_replace(' - France','',$nom);
	$nom =str_replace('CALEDONIE','Calédonie',$nom);
	$nom =str_replace('\\','',$nom);
	
	if($nom=='F') $nom = 'France';
	if($nom=='f') $nom = 'France';
	if($nom=='FR') $nom = 'France';
	if($nom=='FRA') $nom = 'France';
	if(strtoupper($nom)=='GUADELOUPE') $nom = 'France';
	if($nom=='') $nom = 'France';
	if($nom=='Grand-Duché du Luxembourg') $nom = 'Luxembourg';
	if($nom=='GRAND DUCHE DU LUXEMBOURG') $nom = 'Luxembourg';
	if(substr($nom,0,6) =='GUYANE') $nom = 'France';
	if($nom=='grece') $nom = 'Grèce';
	if($nom=='ile de laréunion') $nom = 'France';
	if($nom=='réunion') $nom = 'France';
	if($nom=='ILE DE LA REUNION') $nom = 'France';
	if($nom=='La Réunion') $nom = 'France';
	if(strtoupper($nom)=='LA REUNION') $nom = 'France';
	if(strtoupper($nom)=='REUNION') $nom = 'France';
	if($nom=='ILE DE LA REUNION') $nom = 'France';
	if($nom=='KENYA') $nom = 'Kenya';
	if($nom=='LUXEMBOURG') $nom = 'Luxembourg';
	if(strtoupper($nom)=='MARTINIQUE') $nom = 'France';
	if(strtoupper($nom)=='Martinique (F.W.I.)') $nom = 'France';
	if(strtoupper($nom)=='MAYOTTE') $nom = 'France';
	if(strtoupper($nom)=='Mayotte (France)') $nom = 'France';
	if(strtoupper($nom)=='USA') $nom = 'États-Unis';
	if(strtoupper($nom)=='U.S.A.') $nom = 'États-Unis';
	if($nom=='Rd CONGO') $nom = 'République Démocratique du Congo';
	if($nom=='R. D . Congo') $nom = 'République Démocratique du Congo';
	if(strtoupper($nom)=='r d congo') $nom = 'République Démocratique du Congo';
	if($nom=='Principauté de Monaco') $nom = 'Monaco';
	if(strtoupper($nom)=='ANGLETERRE') $nom = 'Royaume-Uni';
	if(strtoupper($nom)=='ANGLETTERRE') $nom = 'Royaume-Uni';
	if($nom=='Centre afrique') $nom = 'République Centrafricaine';
	if($nom=='Centre Afrique') $nom = 'République Centrafricaine';
	if($nom=='ETATS UNIS') $nom = 'États-Unis';
	if($nom=='CONGO') $nom = 'République Démocratique du Congo';
	if($nom=='Colombia') $nom = 'Colombie';
	//echo$nom.'<br>';
	return $nom;
}

function traite_cp ($cp,$id='') {
	$cp = str_replace("\xC2",' ',$cp);
	$cp = str_replace("\xA0",' ',$cp);

	$cp = str_replace(' ','',$cp);
	$cp = str_replace('.','',$cp);
	$cp = str_replace('cedex','',$cp);
	$cp = trim($cp);
	
	$cp = str_replace('01012','01000',$cp);
	$cp = str_replace('56892','56890',$cp);
	
	if($id=='13926') $cp = '95300';
	if($id=='13726') $cp = '57770';
	if($id=='80080') $cp = '80000';
	if($id=='1929') $cp = '93260';
	if($id=='1933') $cp = '56450';
	if($id=='2100') $cp = '36100';
	if($id=='2404') $cp = '81100';
	if($id=='14555') $cp = '30310';
	if($id=='10613') $cp = '93140';
	if($id=='7031') $cp = '01003';
	if($id=='1848') $cp = '06750';
	if($id=='11463') $cp = '01000';
	if($id=='10324') $cp = '77600';
	if($id=='13118') $cp = '33950';
	if($id=='5475') $cp = '94220';
	if($id=='10874') $cp = '75011';
	if($id=='7209') $cp = '33950';
	
	if($cp=='FR93') $cp = '93300';
	
	if($cp=='75116') $cp = '75016';
	if($cp=='62200COMPIEGNE') $cp = '62200';
	if($cp=='34720CAUX') $cp = '34720';
	if($cp=='75700sp07') $cp = '75007';
	if($cp=='92708') $cp = '92700';
	if($cp=='62580jivenchy') $cp = '62580';
	if($cp=='13621') $cp = '13600';
	if($cp=='59272') $cp = '59242';
	if($cp=='30123') $cp = '30126';
	if($cp=='57232') $cp = '57230';
	if($cp=='75700') $cp = '75007';
	if($cp=='75012Paris') $cp = '75012';
	if($cp=='91760Itteville') $cp = '91760';
	if($cp=='33210fargues') $cp = '33210';
	if($cp=='37500CHINON') $cp = '37500';
	
	return $cp;
}


function traite_nom_ville($nom,$cp='') {
	
	$nom = str_replace("\xC2",' ',$nom);
	$nom = str_replace("\xA0",' ',$nom);
	$nom=trim($nom);
	
	
	$nom = str_replace('é','E',$nom);
	$nom = str_replace('ç','C',$nom);
	$nom = str_replace('î','I',$nom);
	$nom = str_replace('è','E',$nom);
	$nom = str_replace('ê','E',$nom);
	$nom = str_replace('ÿ','Y',$nom);
	$nom = str_replace('â','A',$nom);
	
	$nom = strtoupper($nom);
	$nom = str_replace(' ','-',$nom);
	$nom = str_replace('--','-',$nom);
	$nom = str_replace('---','-',$nom);
	
	$nom = str_replace('LE ROI','LE-ROI',$nom);
	$nom = str_replace('LE COMTE','LE-COMTE',$nom);
	$nom = str_replace('LE GRAND','LE-GRAND',$nom);
	
	
	if(substr($nom, 0, 2) == 'L-')
	{
	    $nom = "L'".substr($nom, 2);
	} 
	
	if(substr($nom, 0, 3) == 'LE-')
	{
	    $nom = "LE ".substr($nom, 3);
	} 
	
	if(substr($nom, 0, 3) == 'LA-')
	{
	    $nom = "LA ".substr($nom, 3);
	} 
	
	if(substr($nom, 0, 4) == 'LES-')
	{
	    $nom = "LES ".substr($nom, 4);
	}  
	
	if(substr($nom, 0,3) == 'ST-')
	{
	    $nom = "SAINT-".substr($nom, 3);
	}  
	if(substr($nom, 0,4) == 'STE-')
	{
	    $nom = "SAINTE-".substr($nom, 4);
	}  
	
	$nom = str_replace('-L-',"-L'",$nom);
	$nom = str_replace('-D-',"-D'",$nom);
	
	$nom = str_replace('-ST-','-SAINT-',$nom);
	$nom = str_replace('-STE-','-SAINTE-',$nom);
	$nom = str_replace(' ST-',' SAINT-',$nom);
	$nom = str_replace(' STE-',' SAINTE-',$nom);
	$nom = str_replace(' LES-','-LES-',$nom);
	$nom = str_replace('SAINT ','SAINT-',$nom);
	$nom = str_replace('LE BRETONNEUX','LE-BRETONNEUX',$nom);
	
	$nom = str_replace("CHAPMS",'CHAMPS',$nom);
	
	$nom = str_replace("ARJELES",'ARGELES',$nom);
	$nom = str_replace("BREC\'H",'BRECH',$nom);
	$nom = str_replace('D-OZON',"D'OZON",$nom);
	$nom = str_replace('D-AZIL',"D'AZIL",$nom);
	$nom = str_replace("\'","'",$nom);
	$nom = str_replace("\\","",$nom);
	$nom = str_replace('SAINT-ORENS','SAINT-ORENS-DE-GAMEVILLE',$nom);
	$nom = str_replace('ISLE SUR','ISLE-SUR',$nom);
	$nom = str_replace('LA CHAPELLE LES LUXEUIL','LA CHAPELLE-LES-LUXEUIL',$nom);
	$nom = str_replace('CHAMBON/LAC','CHAMBON-SUR-LAC',$nom);
	$nom = str_replace('MONT-D-E-MARSAN','MONT-DE-MARSAN',$nom);
	$nom = str_replace('-LABOUTARIE','LABOUTARIE',$nom);
	$nom = str_replace('OZOIR-LA FERRIèRE','OZOIR-LA-FERRIERE',$nom);
	$nom = str_replace('AUBOUÃ','AUBOUE',$nom);
	$nom = str_replace('BOLLENNE','BOLLENE',$nom);
	$nom = str_replace('NOTRE-DAME-DE-MESSAGES','NOTRE-DAME-DE-MESAGE',$nom);
	$nom = str_replace('OLéRON','OLERON',$nom);
	$nom = str_replace('LA BAULE','LA BAULE-ESCOUBLAC',$nom);
	$nom = str_replace('AVALLON------89200','AVALLON',$nom);
	$nom = str_replace('AVALLON---89200','AVALLON',$nom);
	
	$nom = str_replace('SAINT-SABINE-SUR-LONGEVE','SAINTE-SABINE-SUR-LONGEVE',$nom);
	
	$nom = str_replace('CANNES-LA-BOCCA','CANNES',$nom);
	$nom = str_replace('L-ESPOIR',"L'ESPOIR",$nom);
	$nom = str_replace('SAINT-JULIEN-LE-MONTAGNIER',"SAINT-JULIEN",$nom);
	$nom = str_replace('LES PENNE-MIRABEAU',"LES PENNES-MIRABEAU",$nom);
	$nom = str_replace('SAINT-JEAN-LE-VIEUX-64220',"SAINT-JEAN-LE-VIEUX",$nom);
	$nom = str_replace('NUAILLE-D-AUNIS',"NUAILLE-D'AUNIS",$nom);
	$nom = str_replace('MIMIZAN-PLAGE',"MIMIZAN",$nom);
	$nom = str_replace('LE GENESAINT---ST.-ISLE',"LE GENEST-SAINT-ISLE",$nom);
	$nom = str_replace('SAINT-ERME-OUTRE-RAMECOURT',"SAINT-ERME-OUTRE-ET-RAMECOURT",$nom);
	$nom = str_replace('LE COUX-ET-BIGAROQUE',"COUX-ET-BIGAROQUE",$nom);
	$nom = str_replace('GREOUX',"GREOUX-LES-BAINS",$nom);
	$nom = str_replace('LE-GRAND-LUCE',"LE GRAND-LUCE",$nom);
	$nom = str_replace('SARGES-LES-LE-MANS',"SARGE-LES-LE-MANS",$nom);
	$nom = str_replace('MONTREDON-DES-CORBERES',"MONTREDON-DES-CORBIERES",$nom);
	$nom = str_replace('D-ASCQ',"D'ASCQ",$nom);
	$nom = str_replace('MAZERES/SALAT',"MAZERES-SUR-SALAT",$nom);
	$nom = str_replace("COUZON-AU-MT-D\'OR","COUZON-AU-MONT-D'OR",$nom);
	$nom = str_replace("CHAMPS-SUR-TARENTAINE","CHAMPS-SUR-TARENTAINE-MARCHAL",$nom);
	$nom = str_replace("PARIS-11","PARIS",$nom);
	$nom = str_replace("PARIS-17EME","PARIS",$nom);
	
	$nom = str_replace("GRENADE-SUR-ADOUR","GRENADE-SUR-L'ADOUR",$nom);
	$nom = str_replace("LONTBELIARD","MONTBELIARD",$nom);
	$nom = str_replace("RUEI-MALMAISON","RUEIL-MALMAISON",$nom);
	$nom = str_replace("BOIS-GUILLAUME","BOIS-GUILLAUME-BIHOREL",$nom);
	$nom = str_replace("FERRIERES-D'AUNIS","FERRIERES",$nom);
	$nom = str_replace("74420","SAXEL",$nom);
	$nom = str_replace("FONTAINES-BELLENGER","FONTAINE-BELLENGER",$nom);
	$nom = str_replace("JONQIUERES-SAINT-VINCENT","JONQUIERES-SAINT-VINCENT",$nom);
	$nom = str_replace("CLERMONT-FD","CLERMONT-FERRAND",$nom);
	$nom = str_replace("VILLE-D\\'AVRAY","VILLE-D'AVRAY",$nom);
	$nom = str_replace("NOIRMOUTIER","NOIRMOUTIER-EN-L'ILE",$nom);
	$nom = str_replace("LUGON-ET-L'ILE-DU-CARNEY","LUGON-ET-L'ILE-DU-CARNAY",$nom);
	$nom = str_replace("YTUZ","YUTZ",$nom);
	$nom = str_replace("COLLONGES-AU-MONT-D4OR","COLLONGES-AU-MONT-D'OR",$nom);
	$nom = str_replace("GREOUX-LES-BAINS-LES-BAINS","GREOUX-LES-BAINS",$nom);
	$nom = str_replace("VANNES--GWENED","VANNES",$nom);
	$nom = str_replace("LES SAINTES-MARIES-DE-LA-MER","SAINTES-MARIES-DE-LA-MER",$nom);
	$nom = str_replace("PONT-DE-CLANS","CLANS",$nom);
	$nom = str_replace("ENTRAYGUES","ENTRAYGUES-SUR-TRUYERE",$nom);
	$nom = str_replace("SAINTJUST","SAINT-JUST",$nom);
	$nom = str_replace("ILLKIRCH","ILLKIRCH-GRAFFENSTADEN",$nom);
	//$nom = str_replace("DIVONNE","DIVONNE-LES-BAINS",$nom);
	$nom = str_replace("COURSEULLES-SUR-MER118","COURSEULLES-SUR-MER",$nom);
	$nom = str_replace("VILLENEUVE-LEZ-AVIGNON","VILLENEUVE-LES-AVIGNON",$nom);
	$nom = str_replace("SAINT-SENASTIEN-SUR-LOIRE","SAINT-SEBASTIEN-SUR-LOIRE",$nom);
	$nom = str_replace("VILLEDIEU/INDRE","VILLEDIEU-SUR-INDRE",$nom);
	$nom = str_replace("PONT-CHATEAU","PONTCHATEAU",$nom);
	$nom = str_replace("SAINT-SEVER-(40500)","SAINT-SEVER",$nom);
	$nom = str_replace("LES MOUTIERS-NE-RETZ","LES MOUTIERS-EN-RETZ",$nom);
	$nom = str_replace("CLERMONT-L-HERAULT","CLERMONT-L'HERAULT",$nom);
	$nom = str_replace("VILLERS-COTTERÃ?TS","VILLERS-COTTERETS",$nom);
	$nom = str_replace("VILLERS-ES-NANCY","VILLERS-LES-NANCY",$nom);
	$nom = str_replace("AOUSAINTE-SUR-SYE","AOUSTE-SUR-SYE",$nom);
	$nom = str_replace("A1UXERRE","AUXERRE",$nom);
	$nom = str_replace("LYON-6","LYON",$nom);
	$nom = str_replace("ADRIIERS","ADRIERS",$nom);
	$nom = str_replace("BONCHAMP","BONCHAMP-LES-LAVAL",$nom);
	$nom = str_replace("TASSIN-LA-1/2-LUNE","TASSIN-LA-DEMI-LUNE",$nom);
	$nom = str_replace("SY-CYR-EN-VAL","SAINT-CYR-EN-VAL",$nom);
	$nom = str_replace("STES-MARIES-DE-LA-MER","SAINTES-MARIES-DE-LA-MER",$nom);
	$nom = str_replace("ALFORVILLE","ALFORTVILLE",$nom);
	$nom = str_replace("CERGY-PONTOISE","CERGY",$nom);
	
	$nom = str_replace('57770-MOUSSEY','MOUSSEY',$nom);
	
	$nom = str_replace('MANDELIEU-LA-NAPOULE','MANDELIEU',$nom);
	$nom = str_replace('MANDELIEU','MANDELIEU-LA-NAPOULE',$nom);
	
	$nom = str_replace('SANARY-SUR-MER','SANARY',$nom);
	$nom = str_replace('SANARY','SANARY-SUR-MER',$nom);
	$nom = str_replace('BILLACOURT','BILLANCOURT',$nom);
	
	
	$nom = str_replace("PLAIMPIED","",$nom);
	
	if ($nom == 'PLAIMPIED') $nom = 'PLAIMPIED-GIVAUDINS';

	if (($nom == 'SAINT-MAUR') && ($cp=='94100')) $nom = 'SAINT-MAUR-DES-FOSSES';
	if (($nom == 'LAGARDE') && ($cp=='83130')) $nom = 'LA GARDE';
	if (($nom == 'ASNIERES') && ($cp=='92600')) $nom = 'ASNIERES-SUR-SEINE';
	if (($nom == 'MONTEREAU') && ($cp=='77130')) $nom = 'MONTEREAU-FAULT-YONNE';
	if (($nom == 'NEUILLY') && ($cp=='92200')) $nom = 'NEUILLY-SUR-SEINE';
	if (($nom == 'PIERREFITTE') && ($cp=='93380')) $nom = 'PIERREFITTE-SUR-SEINE';
	if (($nom == 'ESPARRON-DE-PALLIERES') && ($cp=='83560')) $nom = 'PIERREFITTE-SUR-SEINE';
	if (($nom == 'BOULOGNE') && ($cp=='92100')) $nom = 'BOULOGNE-BILLANCOURT';
	
	if (($nom == 'FERRIERES') && ($cp=='45210')) $nom = 'FERRIERES-EN-GATINAIS';

	if ($nom == 'BLANC-MESNIL')  $nom = 'LE BLANC-MESNIL';
	if ($nom == 'ALLEMONT')  $nom = 'ALLEMOND';
	if ($nom == 'AMELIE-LES-BAINS-/-PALALDA')  $nom = 'AMELIE-LES-BAINS-PALALDA';
	if ($nom == 'AMNEVILLE-LES-TERMES')  $nom = 'AMNEVILLE';
	if ($nom == 'ANGOULINS-SUR-MER')  $nom = 'ANGOULINS';
	if ($nom == 'ARNOUVILLE-LES-GONESSE')  $nom = 'ARNOUVILLE';
	if ($nom == 'AULNAY-DE-SAINTONGE')  $nom = 'AULNAY';
	if ($nom == 'AVALLON-89200')  $nom = 'AVALLON';
	if ($nom == 'BIARRITW')  $nom = 'BIARRITZ';
	if ($nom == 'BLIGNY-LESBEAUNE')  $nom = 'BLIGNY-LES-BEAUNE';
	if ($nom == "BOURG-D'OISANS")  $nom = "LE BOURG-D'OISANS";
	if ($nom == "BOURG-SUR-GIRONDE")  $nom = "BOURG";
	if ($nom == "BREC\\'H")  $nom = "BRECH";
	if ($nom == "BREC'H")  $nom = "BRECH";
	if ($nom == "BRUAY-LABUISSIERE")  $nom = "BRUAY-LA-BUISSIERE";
	if ($nom == "CABANES-DE-FLEURY")  $nom = "FLEURY";
	if ($nom == "CALUIRE")  $nom = "CALUIRE-ET-CUIRE";
	if ($nom == "CAP-FERRET")  $nom = "LEGE-CAP-FERRET";
	if ($nom == "CAMARET")  $nom = "CAMARET-SUR-AIGUES";
	if ($nom == "THIAUCOURT")  $nom = "THIAUCOURT-REGNIEVILLE";
	if ($nom == "BITSCHWILLER")  $nom = "BITSCHWILLER-LES-THANN";
	if ($nom == "THIAUCOURT-REGNEVILLE")  $nom = "THIAUCOURT-REGNIEVILLE";
	
	if ($nom == "MARSPICH")  $nom = "HAYANGE";
	if ($nom == "BOULOURIS")  $nom = "SAINT-RAPHAEL";
	
	if ($nom == "OURDENNE")  $nom = "OUDRENNE";
	if ($nom == "PLAINE-DES-PALMISTES")  $nom = "LA PLAINE-DES-PALMISTES";
	if ($nom == "VAND?UVRE-LES-NANCY")  $nom = "VANDOEUVRE-LES-NANCY";
	
	if ($nom == "BEAUMONT-SUR-L'OSSE")  $nom = "BEAUMONT";
	if ($nom == "¨PARIS")  $nom = "PARIS";
	if ($nom == "CO,DETTE")  $nom = "CONDETTE";
	if ($nom == "CHARENTON")  $nom = "CHARENTON-LE-PONT";
	
	if ($nom == "MONTREUIL-SOUS-BOIS")  $nom = "MONTREUIL";
	if ($nom == "MOUGINS-LE-HAUT")  $nom = "MOUGINS";
	
	if ($cp=='31830') $nom = 'PLAISANCE-DU-TOUCH';
	if (($cp=='59160') && (  $nom =='LOMME')) $nom = 'LILLE';
	
	if ($cp=='62200COMPIEGNE') $nom = 'COMPIEGNE';
	if ($cp=='62580jivenchy') $nom = 'JIVENCHY';
	if ($cp=='01340') $nom = 'MALAFRETAZ';
	if ($cp=='19100') $nom = 'BRIVE-LA-GAILLARDE';
	if ($nom=='A') $nom = 'ART-SUR-MEURTHE';
	if ($nom=='CLION-SUR-SEUGNE') $nom = 'CLION';
	if ($nom=='LA-ROCHELLE') $nom = 'LA ROCHELLE';
	if ($nom=='KREMLIN-BICETRE') $nom = 'LE KREMLIN-BICETRE';
	if ($nom=='MONTLEVEQUE') $nom = "MONT-L'EVEQUE";
	if ($nom=='MONTASTRUC') $nom = "MONTASTRUC-LA-CONSEILLERE";
	if ($nom=="MONT-D'E-MARSAN") $nom = "MONT-DE-MARSAN";
	if ($nom=="MEULAN") $nom = "MEULAN-EN-YVELINES";
	if ($nom=="MEUDON-LA-FORET") $nom = "MEUDON";
	if ($nom=="MERVILLE-FRANCEVILLE-PLAG") $nom = "MERVILLE-FRANCEVILLE-PLAGE";
	if ($nom=="MALZIEU-FORAIN") $nom = "LE MALZIEU-FORAIN";
	if ($nom=="MAGNY-LES-HX") $nom = "MAGNY-LES-HAMEAUX";
	if ($nom=="L'HOPITAL-CAMFROUT") $nom = "HOPITAL-CAMFROUT";
	if ($nom=="LILLE.") $nom = "LILLE";
	if ($nom=="LES GRANDE-VENTES") $nom = "LES GRANDES-VENTES";
	if ($nom=="LE PERREUX") $nom = "LE PERREUX-SUR-MARNE";
	if ($nom=="LE LUC-EN-PROVENCE") $nom = "LE LUC";
	if ($nom=="LE GUILVINEC") $nom = "GUILVINEC";
	if ($nom=="LE GENEST--ST.-ISLE") $nom = "LE GENEST-SAINT-ISLE";
	if ($nom=="LANEUVILLE-CHANT-D'OISEL") $nom = "LA NEUVILLE-CHANT-D'OISEL";
	if ($nom=="LA FERTE-SAITN-AUBIN") $nom = "LA FERTE-SAINT-AUBIN";
	if ($nom=="LA FERTE-FRESNEL") $nom = "LA FERTE-FRENEL";
	if ($nom=="LA CHAPELLE AUX FILTZMEEN") $nom = "LA CHAPELLE-AUX-FILTZMEENS";
	if ($nom=="LA BAULE-ESCOUBLAC-ESCOUBLAC") $nom = "LA BAULE-ESCOUBLAC";
	if ($nom=="JARVILLE") $nom = "JARVILLE-LA-MALGRANGE";
	if ($nom=="IVRY-SUR-SEINR") $nom = "IVRY-SUR-SEINE";
	if ($nom=="IVOIRY--EPINONVILLE") $nom = "EPINONVILLE";
	if ($nom=="HYERES-LES-PALMIERS") $nom = "HYERES";
	if ($nom=="HULTEHOUSE-57820") $nom = "HULTEHOUSE";
	if ($nom=="ILLKIRCH-GRAFFENSTADEN-GRAFFENSTADEN") $nom = "ILLKIRCH-GRAFFENSTADEN";
	if ($nom=="HENIN--BEAUMONT") $nom = "HENIN-BEAUMONT";
	if ($nom=="HADANCOURT-LE-HAUT-CLOCHE") $nom = "HADANCOURT-LE-HAUT-CLOCHER";
	if ($nom=="GRENADE-SUR-GARONNE") $nom = "GRENADE";
	if ($nom=="GOUVILLE-S/MER") $nom = "GOUVILLE-SUR-MER";
	if ($nom=="GEUDERTHEM") $nom = "GEUDERTHEIM";
	if ($nom=="GALARGUES-LE-MONTUEUX") $nom = "GALLARGUES-LE-MONTUEUX";
	if ($nom=="FRESNAY-EN-REZ") $nom = "FRESNAY-EN-RETZ";
	if ($nom=="FOUQUIERES-LEZ-LENS") $nom = "FOUQUIERES-LES-LENS";
	if ($nom=="FORT_DE-FRANCE") $nom = "FORT-DE-FRANCE";
	if ($nom=="FONS-OUTRE-GARDON") $nom = "FONS";
	if ($nom=="EZE-SUR-MER") $nom = "EZE";
	if ($nom=="CRE-SUR-LOIR") $nom = "CRE";
	if ($nom=="CLION-SUR-INDRE") $nom = "CLION";
	if ($nom=="COUZON-AU-MT-D'OR") $nom = "COUZON-AU-MONT-D'OR";
	if ($nom=="CLERMONT-DE-L'OISE") $nom = "CLERMONT";
	if ($nom=="CHERBOURG") $nom = "CHERBOURG-OCTEVILLE";
	if ($nom=="EQUEURDRTEVILLE") $nom = "EQUEURDREVILLE-HAINNEVILLE";
	if ($nom=="EQUEURDREVILLE") $nom = "EQUEURDREVILLE-HAINNEVILLE";
	if ($nom=="DURFORT-ET-SAINT-MARTIN") $nom = "DURFORT-ET-SAINT-MARTIN-DE-SOSSENAC";
	if ($nom=="CHATEAU--LANDON") $nom = "CHATEAU-LANDON";
	if ($nom=="KIRRWILLER-BOSSELSHAUSEN") $nom = "BOSSELSHAUSEN";
	if ($nom=="BAUMES-LES-DAMES") $nom = "BAUME-LES-DAMES";
	if ($nom=="MESNIL-LE-ROI") $nom = "LE MESNIL-LE-ROI";
	if ($nom=="BAN-SAINT-MARTIN") $nom = "LE BAN-SAINT-MARTIN";
	if ($nom=="LES DEUX-ALPES") $nom = "MONT-DE-LANS";
	if ($nom=="NEUNKIRCHEN-LES-BOUZONVIL") $nom = "NEUNKIRCHEN-LES-BOUZONVILLE";
	
	$nom = str_replace(' CEDEX','-CEDEX',$nom);
	$nom = str_replace('-CEDES','-CEDEX',$nom);
	
	$nom = str_replace('ST.','SAINT-',$nom);
	$nom = str_replace('--','-',$nom);

	return $nom;
}


function traite_nom_ville_perso($nom,$cp='') {
	$nom = trim($nom);
	
	$nom = str_replace('Ã?','E',$nom);
	$nom = str_replace('-S/','-SUR-',$nom);
	$nom = str_replace('-S-','-SUR-',$nom);
	$nom = str_replace('-/-','-SUR-',$nom);
	$nom = str_replace('/','-SUR-',$nom);
	$nom = str_replace('--','-',$nom);
	$nom = str_replace('DOLERON',"D'OLERON",$nom);
	$nom = str_replace('.','-',$nom);
	$nom = str_replace('-BE-','-DE-',$nom);
	$nom = str_replace('-SUR-S-','-SUR-',$nom);
	
	$nom = str_replace('-CTE','-COMPTE',$nom);
	$nom = str_replace('FRONTIGNAN-LA-PEYRADE','FRONTIGNAN',$nom);
	$nom = str_replace('LE GOSIER-GUADELOUPE','LE GOSIER',$nom);
	
	
	if ($nom=="HALLENNES-LES-HAUBOURDIN") $nom = "HALLENNES-LEZ-HAUBOURDIN";
	if ($nom=="HALLENNES-HAUBOURDIN") $nom = "HALLENNES-LEZ-HAUBOURDIN";
	if ($nom=="HALLING-LES-BOULAY") $nom = "BOULAY-MOSELLE";
	if ($nom=="HAUMONT") $nom = "HAUTMONT";
	if ($nom=="SOLRE-LE-CHÃ‚TEAU") $nom = "SOLRE-LE-CHATEAU";
	if ($nom=="AJACCCIO") $nom = "AJACCCIO";
	if ($nom=="ALFORTTVILLE") $nom = "ALFORTVILLE";
	if ($nom=="ALTIRCH") $nom = "ALTKIRCH";
	if ($nom=="AMFREVILLE-LA-MIVOIE") $nom = "AMFREVILLE-LA-MI-VOIE";
	if ($nom=="AMPLEPLUIS") $nom = "AMPLEPUIS";
	if ($nom=="GRAU-D'AGDE") $nom = "AGDE";
	if ($nom=="ARCES SUR GIRONDE") $nom = "ARCES";
	if ($nom=="BARBEZIEUX") $nom = "BARBEZIEUX-SAINT-HILAIRE";
	if ($nom=="BASSE-RUPT") $nom = "BASSE-SUR-LE-RUPT";
	if ($nom=="BASSE-TERRRE") $nom = "BASSE-TERRE";
	if ($nom=="BELLERIVE-ALLIER") $nom = "BELLERIVE-SUR-ALLIER";
	if ($nom=="BELLERIVE-S-ALLIER") $nom = "BELLERIVE-SUR-ALLIER";
	if ($nom=="BISCAROSSE") $nom = "BISCARROSSE";
	if ($nom=="BONCHAMPS") $nom = "BONCHAMP-LES-LAVAL";
	if ($nom=="BONNE-SUR-MENOGE") $nom = "BONNE";
	if ($nom=="BRETEUIL-SUR-ITON") $nom = "BRETEUIL";
	if ($nom=="BRETIGNOLLE-MER") $nom = "BRETIGNOLLES-SUR-MER";
	if ($nom=="BRETIGNOLLES-MER") $nom = "BRETIGNOLLES-SUR-MER";
	if ($nom=="BURNHAUPT-LE-HT") $nom = "BURNHAUPT-LE-HAUT";
	if ($nom=="LE CAP-D'AGDE") $nom = "AGDE";
	if ($nom=="CAP-D'AGDE") $nom = "AGDE";
	if ($nom=="CHALON-EN-CHAMPAGNE") $nom = "CHALONS-EN-CHAMPAGNE";
	if ($nom=="AIX-EN-PCE") $nom = "AIX-EN-PROVENCE";
	if ($nom=="AJACCCIO") $nom = "AJACCIO";
	if ($nom=="AMBARES") $nom = "AMBARES-ET-LAGRAVE";
	if ($nom=="ANTEZANT") $nom = "ANTEZANT-LA-CHAPELLE";
	if ($nom=="ARZACQ") $nom = "ARZACQ-ARRAZIGUET";
	if ($nom=="ANDERNOS") $nom = "ANDERNOS-LES-BAINS";
	if ($nom=="ABYMES") $nom = "LES ABYMES";
	if ($nom=="ARCEUIL") $nom = "ARCUEIL";
	if ($nom=="CANET-EN-ROUSSILON") $nom = "CANET-EN-ROUSSILLON";
	if ($nom=="HERMENAULT-(L')") $nom = "L'HERMENAULT";
	if ($nom=="AIRE-SUR-ADOUR") $nom = "AIRE-SUR-L'ADOUR";
	if ($nom=="ARCES-SUR-GIRONDE") $nom = "ARCES";
	if ($nom=="ASNIERES-LES-BOURGES") $nom = "BOURGES";
	if ($nom=="BELLEVILLE-SUR-SAONE") $nom = "BELLEVILLE";
	if ($nom=="BICHHEIM") $nom = "BISCHHEIM";
	if ($nom=="BIRLENBACH") $nom = "DRACHENBRONN-BIRLENBACH";
	if ($nom=="BONCHAMP-LES-LAVALS") $nom = "BONCHAMP-LES-LAVAL";
	if ($nom=="BOSSEVAL") $nom = "BOSSEVAL-ET-BRIANCOURT";
	if ($nom=="ANGOULINS-S-MER") $nom = "ANGOULINS";
	if ($nom=="BLANCHEFOSSE") $nom = "BLANCHEFOSSE-ET-BAY";
	if ($nom=="ANDELYS-(LES)") $nom = "LES ANDELYS";
	if ($nom=="CARHAIX") $nom = "CARHAIX-PLOUGUER";
	if ($nom=="LE CHÂTEAU-D'OLONNE") $nom = "CHÂTEAU-D'OLONNE";
	if ($nom=="LE HOHWLAD") $nom = "LE HOHWALD";
	if ($nom=="LE LARDIN") $nom = "LE LARDIN-SAINT-LAZARE";
	if ($nom=="LEVALLOIS") $nom = "LEVALLOIS-PERRET";
	if ($nom=="MONTCEAU") $nom = "MONTCEAU-LES-MINES";
	if ($nom=="SAINT-LAURENT-SEVRE") $nom = "SAINT-LAURENT-SUR-SEVRE";
	if ($nom=="TAPONNAT") $nom = "TAPONNAT-FLEURIGNAC";
	if ($nom=="TASSIN") $nom = "TASSIN-LA-DEMI-LUNE";
	if ($nom=="TERRASSON") $nom = "TERRASSON-LAVILLEDIEU";
	if ($nom=="THONON") $nom = "THONON-LES-BAINS";
	if ($nom=="TIGNIEU") $nom = "TIGNIEU-JAMEYZIEU";
	if ($nom=="TRINITE") $nom = "LA TRINITE";
	if ($nom=="URMATT-(PROCHE-STRASBOURG)") $nom = "URMATT";
	if ($nom=="VANDOEUVRE") $nom = "VANDOEUVRE-LES-NANCY";
	if ($nom=="VAUCOULEUR") $nom = "VAUCOULEURS";
	if ($nom=="VELIZY") $nom = "VELIZY-VILLACOUBLAY";
	if ($nom=="VIC-BIGORRE") $nom = "VIC-EN-BIGORRE";
	if ($nom=="VIGNEULLES-LES-H-") $nom = "VIGNEULLES-LES-HATTONCHATEL";
	if ($nom=="VILLECRESNE") $nom = "VILLECRESNES";
	if ($nom=="VILLENEUVE-LES-AVIGNONS") $nom = "VILLENEUVE-LES-AVIGNON";
	if ($nom=="SAINT-RAMBERT") $nom = "SAINT-RAMBERT-D'ALBON";
	if ($nom=="SAINT-POURÇAIN-S-SIOULE") $nom = "SAINT-POURCAIN-SUR-SIOULE";
	if ($nom=="SAINT-ORENS-DE-GAMEVILLE-DE-GAMEVILLE") $nom = "SAINT-ORENS-DE-GAMEVILLE";
	if ($nom=="SAINT-LAURENT-SEVRE") $nom = "SAINT-LAURENT-SUR-SEVRE";
	if ($nom=="SAINT-JEAN-DILLAC") $nom = "SOULTZ-HAUT-RHIN";
	if ($nom=="SAVIGNY-BRAYE") $nom = "SAVIGNY-SUR-BRAYE";
	if ($nom=="SAUXEMESNIL") $nom = "SAUSSEMESNIL";
	if ($nom=="SANVIGNES") $nom = "SANVIGNES-LES-MINES";
	if ($nom=="SAINTE-MAURE-DE-TNE") $nom = "SAINTE-MAURE-DE-TOURAINE";
	if ($nom=="LEVONCOURT-68") $nom = "LEVONCOURT";
	if ($nom=="LEVALLOIS") $nom = "LEVALLOIS-PERRET";
	if ($nom=="LEUILLY-SOUS-LAON") $nom = "LAON";
	if ($nom=="LEPUIX-GY") $nom = "LEPUIX";
	if ($nom=="LEBLANC-MESNL") $nom = "LE BLANC-MESNL";
	if ($nom=="LE VAL-DAJOL") $nom = "LE VAL-D'AJOL";
	if ($nom=="GIRMONT-VAL-DAJOL") $nom = "GIRMONT-VAL-D'AJOL";
	if ($nom=="LE TOUQUET") $nom = "LE TOUQUET-PARIS-PLAGE";
	if ($nom=="LE POIR-SUR-VIE") $nom = "LE POIRE-SUR-VIE";
	if ($nom=="LE PASSAGE-D'AGEN") $nom = "LE PASSAGE";
	if ($nom=="LE LYAUD") $nom = "LYAUD";
	if ($nom=="LE LARDIN") $nom = "LE LARDIN-SAINT-LAZARE";
	if ($nom=="LE HOHWLAD") $nom = "LE HOHWALD";
	if ($nom=="LE GROS-MORNE") $nom = "GROS-MORNE";
	if ($nom=="CHÂTEAU-D'OLONNE") $nom = "LE CHÂTEAU-D'OLONNE";
	if ($nom=="LE CHAMBLAC") $nom = "CHAMBLAC";
	if ($nom=="LE BONO") $nom = "BONO";
	if ($nom=="LAROQUEBRUSSANNE") $nom = "LA ROQUEBRUSSANNE";
	if ($nom=="LANDES-GENUSSON-(LES)") $nom = "LES LANDES-GENUSSON";
	if ($nom=="LANCON-DE-PROVENCE") $nom = "LANCON-PROVENCE";
	if ($nom=="LAGNY-MARNE") $nom = "LAGNY-SUR-MARNE";
	if ($nom=="LABREDE") $nom = "LA BREDE";
	if ($nom=="LA TRESNE") $nom = "LATRESNE";
	if ($nom=="LA SALLE-AUBRY") $nom = "LA SALLE-ET-CHAPELLE-AUBRY";
	if ($nom=="LA MOTTE-BEUVRON") $nom = "LAMOTTE-BEUVRON";
	if ($nom=="IZEL-LES-HAMEAUX") $nom = "IZEL-LES-HAMEAU";
	if ($nom=="ISLE-SUR-LA-SORGUE") $nom = "L'ISLE-SUR-LA-SORGUE";
	if ($nom=="ISLE-JOURDAIN") $nom = "L'ISLE-JOURDAIN";
	if ($nom=="HOSSEGOR") $nom = "SOORTS-HOSSEGOR";
	if ($nom=="GUILHERAND") $nom = "GUILHERAND-GRANGES";
	if ($nom=="GRAND-QUEVILLY") $nom = "LE GRAND-QUEVILLY";
	if ($nom=="GOSIER") $nom = "LE GOSIER";
	if ($nom=="GESNES-LE-GANDELAIN") $nom = "GESNES-LE-GANDELIN";
	if ($nom=="FONTENAY-LE-COMPTE") $nom = "FONTENAY-LE-COMTE";
	if ($nom=="FONTAINES-LES-DIJON") $nom = "FONTAINE-LES-DIJON";
	if ($nom=="FOLSCHWILLER") $nom = "FOLSCHVILLER";
	if ($nom=="FLOCELLIERE-(LA)") $nom = "LA FLOCELLIERE";
	if ($nom=="FLEURY-D'AUDE") $nom = "FLEURY";
	if ($nom=="EVRY-GREGY-SUR-YERRES") $nom = "EVRY-GREGY-SUR-YERRE";
	if ($nom=="EQUEURDREVILLE-HAINNVILLE") $nom = "EQUEURDREVILLE-HAINNEVILLE";
	if ($nom=="ENTRAYGUES-SUR-TRUYERE-SUR-TRUYERE") $nom = "ENTRAYGUES-SUR-TRUYERE";
	if ($nom=="EGUZON") $nom = "EGUZON-CHANTOME";
	if ($nom=="DRACHENBRONN") $nom = "DRACHENBRONN-BIRLENBACH";
	if ($nom=="CHATILLON-CHALARONNE") $nom = "CHATILLON-SUR-CHALARONNE";
	if ($nom=="CHATELAILLON") $nom = "CHATELAILLON-PLAGE";
	if ($nom=="CHAMONIX") $nom = "CHAMONIX-MONT-BLANC";
	if ($nom=="CESSENON") $nom = "CESSENON-SUR-ORB";
	if ($nom=="CAZERES-SUR-GARONNE") $nom = "CAZERES";
	if ($nom=="CANET-D'AUDE") $nom = "CANET";
	if ($nom=="BREAL-SOUS-MONFORT") $nom = "BREAL-SOUS-MONTFORT";
	if ($nom=="BOURGOIN-JALLEEU") $nom = "BOURGOIN-JALLIEU";
	if ($nom=="BOURGNEUF-LA-FORET") $nom = "LE BOURGNEUF-LA-FORET";
	if ($nom=="BONCHAMP-LES-LAVAL-LES-LAVAL") $nom = "BONCHAMP-LES-LAVAL";
	if ($nom=="BITSCHWILLER-LES-THANN-LES-THANN") $nom = "BITSCHWILLER-LES-THANN";
	if ($nom=="BERGHOLTZ-ZELL") $nom = "BERGHOLTZZELL";
	if ($nom=="BALLANCOURT") $nom = "BALLANCOURT-SUR-ESSONNE";
	if ($nom=="AYZE") $nom = "AYSE";
	if ($nom=="AUZEVILLE") $nom = "AUZEVILLE-TOLOSANE";
	
	if (($cp=='65130') && ($nom == "AVEZAC")) $nom = "AVEZAC-PRAT-LAHITTE";
	
	if (($cp=='44610') && ($nom == "BASSE-INDRE")) $nom = "INDRE";
	if (($cp=='24440') && ($nom == "BEAUMONT")) $nom = "BEAUMONT-DU-PERIGORD";
	if (($cp=='91220') && ($nom == "BRETIGNY")) $nom = "BRETIGNY-SUR-ORGE";
	if (($cp=='24200') && ($nom == "CARSAC")) $nom = "CARSAC-AILLAC";
	if (($cp=='31320') && ($nom == "CASTANET")) $nom = "CASTANET-TOLOSAN";
	if (($cp=='94500') && ($nom == "CHAMPIGNY")) $nom = "CHAMPIGNY-SUR-MARNE";
	if (($cp=='87170') && ($nom == "ISLE-SUR-VIENNE")) $nom = "ISLE";
	if (($cp=='13300') && ($nom == "SALON")) $nom = "SALON-DE-PROVENCE";
	if (($cp=='33260') && ($nom == "LA TESTE")) $nom = "LA TESTE-DE-BUCH";
	if (($cp=='27270') && ($nom == "GRANDCAMP")) $nom = "GRAND-CAMP";
	if (($cp=='24200') && ($nom == "SARLAT")) $nom = "SARLAT-LA-CANEDA";
	if (($cp=='68360') && ($nom == "SOULTZ")) $nom = "LORIOL-SUR-DROME";
	if (($cp=='83140') && ($nom == "SIX-FOURS")) $nom = "SIX-FOURS-LES-PLAGES";
	if (($cp=='56390') && ($nom == "GRANDCHAMP")) $nom = "GRAND-CHAMP";
	if (($cp=='26270') && ($nom == "LORIOL")) $nom = "LORIOL-SUR-DROME";
	if (($cp=='50330') && ($nom == "ANGOVILLE")) $nom = "ANGOVILLE-SUR-AY";
	if (($cp=='40220') && ($nom == "BOUCAU")) $nom = 'VIEUX-BOUCAU-LES-BAINS';
	if (($cp=='57220') && ($nom == "BOULAY")) $nom = 'BOULAY-MOSELLE';
	if (($cp=='13100') && ($nom == "AIX")) $nom = "AIX-EN-PROVENCE";
	if (($cp=='13090') && ($nom == "AIX")) $nom = "AIX-EN-PROVENCE";
	if (($cp=='93600') && ($nom == "AULNAY")) $nom = "AULNAY-SOUS-BOIS";
	if (($cp=='94100') && ($nom == "SAINT-MAURE")) $nom = "SAINT-MAUR-DES-FOSSES";
	if (($cp=='01200') && ($nom == "BELLEGARDE")) $nom = "BELLEGARDE-SUR-VALSERINE";
	if (($cp=='70800') && ($nom == "SAINT-LOUP")) $nom = "SAINT-LOUP-SUR-SEMOUSE";
	if (($cp=='53700') && ($nom == "VILLAINES")) $nom = "VILLAINES-LA-JUHEL";
	if (($cp=='69400') && (substr($nom,0,12) == "VILLEFRANCHE")) $nom = "VILLEFRANCHE-SUR-SAONE";
	if (($cp=='62223') && ($nom == "SAINT-LAURENT")) $nom = "SAINT-LAURENT-BLANGY";
	
	return $nom;
}

function traite_tel($tel) {
	$tel=str_replace(' ','',$tel);
	$tel = str_replace('-','',$tel);
	$tel = str_replace('.','',$tel);
	$tel = str_replace('O2','02',$tel);
	$tel = str_replace('O1','01',$tel);
	$tel = str_replace('O3','03',$tel);
	$tel = str_replace('O4','04',$tel);
	$tel = str_replace('O5','05',$tel);
	$tel = str_replace('O6','06',$tel);
	$tel = str_replace('O7','07',$tel);
	return $tel;
}

?>