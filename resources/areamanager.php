<?php
	include "session.inc.dev.php";
?>
<head>

	<script src="assets/jquery-2.1.4.min.js"></script>
	<link rel="stylesheet" href="assets/jquery-ui.css">
 	<script src="assets/jquery-ui.min.js"></script>
	<script src="assets/jquery-nicefileinput-js.min.js"></script>
  	<link rel="stylesheet" href="assets/jquery-ui.theme.css">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title>Amia Area Manager</title>
	<link rel="stylesheet" href="subBlack2o.css" type="text/css" />
	<style>img.icon { border: 1px solid #FFFFCC; }
	.button:hover{
		border: 1px solid #ffaf0f;
		background: #f58400 url("assets/images/ui-bg_inset-soft_30_f58400_1x100.png") 50% 50% repeat-x;
		font-weight: bold;
		color: #151515;
	}
	.button:active{
		color: #FFFFCC;
		text-decoration: none;
	}
	</style>

  	<script>
	var didClick = false;
	// initialize jQuery ui when page loads
	$(function() {
		var blank = 'Select .are file: ';
		$( "#upload_are" ).nicefileinput({label : blank});
		blank = 'Select .gic file: ';
		$( "#upload_gic" ).nicefileinput({label : blank});
		blank = 'Select .git file: ';
		$( "#upload_git" ).nicefileinput({label : blank});
  	});

	function download(id,filename,type,path) {
		// set name display
		var areaFolderName = $('#'+id+'_areaFolderName').text();
		var areaShortName = $('#'+id+'_shortname').text();
		
		// build and set label
		var areaHTMLLabel = 		"<font style='font-weight: bold;" +
						"font-family:Verdana,Arial,Helvetica,sans-serif;" +
						"color:#CC9900;" +
						"font-size:14px;'>";

		areaHTMLLabel += areaShortName + '</font>';
		$( "#areaFolderNameDownload" ).html(areaHTMLLabel);

		// build download labels
		
		var are = "<a title='" + areaFolderName + "' href='download.php?path=" + path + "&download_file=" + filename + ".are'>";
		var gic = "<a title='" + areaFolderName + "' href='download.php?path=" + path + "&download_file=" + filename + ".gic'>";
		var git = "<a title='" + areaFolderName + "' href='download.php?path=" + path + "&download_file=" + filename + ".git'>";

		// set active pane
		$( "#download_are" ).html('Download: ' + are + filename + '.are');
		$( "#download_gic" ).html('Download: ' + gic + filename + '.gic');
		$( "#download_git" ).html('Download: ' + git + filename + '.git');		

		$( "#areadownload" ).dialog({
			height: "275",
			width: "400",
			position: { my: "left top", at: "left bottom", of: $( "#dlb_" + id ) },
			buttons: [
    			{
      				text: "Cancel",
      				click: function() {
        				$( this ).dialog( "close" );
      				}
    			}
	
			]
		});

	}	
	
  	function upload(id,filename,type,path) {
		// set name display
		var areaFolderName = $('#'+id+'_areaFolderName').text();
		
		// build and set label
		var areaHTMLLabel = "<font style='font-weight: bold;" +
						"font-family:Verdana,Arial,Helvetica,sans-serif;" +
						"color:#CC9900;" +
						"font-size:14px;'>";

		areaHTMLLabel += areaFolderName + '</font>';
		$( "#areaFolderNameUpload" ).html(areaHTMLLabel);
		// set hidden vars
		$( "#areaFileName" ).val(filename);
		$( "#areaType" ).val(type);
		$( "#areaFilePath" ).val(path);
		$( "#areaFolderNameIn" ).val(areaFolderName);

		$( "#areaupload" ).dialog({
			height: "275",
			width: "400",
			position: { my: "left top", at: "left bottom", of: $( "#upb_" + id ) },
  			buttons: [
			{	
				text: "Upload",
				click: function() {
					uploadValidator();
				}
			},
    			{
      				text: "Cancel",
      				click: function() {
        				$( this ).dialog( "close" );
      				}
    			}
	
			]
		});
  	}

	function uploadValidator() {
		var are = $('#upload_are').val();
		var gic = $('#upload_gic').val();
		var git = $('#upload_git').val();
		var error = "Validation Error! Please fix the following:";
		var error_bool = false;
		
		if(are === "" || gic === "" || git === "") {
			error = error + '\n- Ensure you have selected all three files before trying to upload.';
			error_bool = true;
		}

		// validate filename
		var areaFileName = $( "#areaFileName" ).val();

		if( are.indexOf(areaFileName) < 0 || gic.indexOf(areaFileName) < 0 || git.indexOf(areaFileName) < 0 ) {
			error = error + '\n- Ensure the filenames are correct and matches the existing area filenames.';
			error_bool = true;
		}

		// decipher extensions
		are = are.substr((~-are.lastIndexOf(".") >>> 0) + 2);
		gic = gic.substr((~-gic.lastIndexOf(".") >>> 0) + 2);
		git = git.substr((~-git.lastIndexOf(".") >>> 0) + 2);
				
		if(are !== "are" || gic !== "gic" || git !== "git") {
			error = error + '\n- Ensure the extensions are correct, and in the right order.';
			error_bool = true;	
		}
		
		if(error_bool === true) {
			alert(error); 
			return;
		}	
		$('#upload').submit();
	}

	function spawnChooseServer(id,filename,type,path,state) {
		if(state == 1 && didClick == false) {
			var tag = $('#asb_' + id);
			var appendStr = "<div class='ownerQuestion'><h4>Which server owns this area?</h4><br /><form>Amia A <input type='radio' name='radio' value='A' onclick='spawnChooseServer(" + id + ", &quot;" + filename + "&quot;, &quot;" + type + "&quot;, &quot;" + path + "&quot;, &quot;A&quot;);'  /> Amia B <input type='radio' name='radio' value='B' onclick='spawnChooseServer(" + id + ", &quot;" + filename + "&quot;, &quot;" + type + "&quot;, &quot;" + path + "&quot;, &quot;B&quot;);'/></form></div>";
			$('#td_as_' + id).append(appendStr);
			didClick = true;
		} else if (state == "A") {
			areaswitch(id,filename,type,path,"A");
		} else if (state == "B") {
			areaswitch(id,filename,type,path,"B");
		}
	}
	
	function areaswitch(id,filename,type,path,server) {
		// set name display
		var areaFolderName = $('#'+id+'_areaFolderName').text();
		var areaShortName = $('#'+id+'_shortname').text();

		// build and set label
		var areaHTMLLabel = 		"<font style='font-weight: bold;" +
						"font-family:Verdana,Arial,Helvetica,sans-serif;" +
						"color:#CC9900;" +
						"font-size:14px;'>";

		areaHTMLLabel += areaFolderName + '</font>';
		$( "#areaFolderNameSwitch" ).html(areaHTMLLabel);
		
		// build text
		type = type.toLowerCase();
		var switchLbl = "";
		if(type == "halfa" || type == "halfb") {
			switchLbl = "full</b> dynamic?<br /><br /><font style='color:#FF0000'>*</font> This will require NWNX to restart for this area to work.";
		}
		else if(type == "full") {
			$('.ownerQuestion').empty();
			didClick = false;
			switchLbl = "half</b> dynamic on Amia " + server + "?<br /><br /><font style='color:#FF0000'>*</font> This will require a server restart for this area to work.";
		}
		else { //?
			return;
		}

		$( '#switch_confirm' ).html("Are you sure you want to move " + areaShortName + " to being <b>" + switchLbl);
		
		// set hidden vars
		$( "#areaFileNameS" ).val(filename);
		$( "#areaTypeS" ).val(type);
		$( "#areaFilePathS" ).val(path);
		$( "#areaFolderNameInS" ).val(areaFolderName);
		$( "#server").val(server);

		$( "#areaswitch" ).dialog({
			height: "275",
			width: "400",
			position: { my: "left top", at: "left bottom", of: $( "#asb_" + id ) },
			buttons: [
				{	
				text: "Yes",
				click: function() {
						// submit js form
						$('#switchareaform').submit();
					}
				},
				{
      				text: "No",
      				click: function() {
        				$( this ).dialog( "close" );
      				}
    			}
	
			]
		});
		
	}
  	</script>
</head>

<div id="areaupload" title="Area Upload" style="display: none">
	<form id="upload" action="areamanager.php" method="POST" enctype="multipart/form-data">
	<div style="width: 100%" align="center">
		<div id="areaFolderNameUpload" style="width: 100%"></div><br />
		<table>
		<tr><td style="color:#FFFFCC;"><input type="file" id="upload_are" name="upload_are"/></td></tr>
		<tr><td style="color:#FFFFCC;"><input type="file" id="upload_gic" name="upload_gic"/></td></tr>
		<tr><td style="color:#FFFFCC;"><input type="file" id="upload_git" name="upload_git"/></td></tr>
		</table>
	</div>
	<input type="hidden" id="areaFileName" name="areaFileName" value=""/>
	<input type="hidden" id="areaType" name="areaType" value=""/>
	<input type="hidden" id="areaFilePath" name="areaFilePath" value=""/>
	<input type="hidden" id="areaFolderNameIn" name="areaFolderNameIn" value=""/>
	</form>
</div>

<div id="areadownload" title="Area Download" style="display: none">
	<div style="width: 100%" align="center">
		<div id="areaFolderNameDownload" style="width: 100%"></div><br />
		<table>
		<tr><td style="color:#FFFFCC;" id="download_are"></td></tr>
		<tr><td style="color:#FFFFCC;" id="download_gic"></td></tr>
		<tr><td style="color:#FFFFCC;" id="download_git"></td></tr>
		</table>
	</div>
	</form>
</div>

<div id="areaswitch" title="Area Switch" style="display: none">
	<div style="width: 100%" align="center">
		<div id="areaFolderNameSwitch" style="width: 100%"></div><br />
		<table>
			<tr><td style="color:#FFFFCC;" id="switch_confirm"></td></tr>
		</table>
	</div>
	<form id="switchareaform" action="areamanager.php" method="POST" style="display: none">
		<input type="hidden" id="areaFileNameS" name="areaFileNameS" value=""/>
		<input type="hidden" id="areaTypeS" name="areaTypeS" value=""/>
		<input type="hidden" id="areaFilePathS" name="areaFilePathS" value=""/>
		<input type="hidden" id="areaFolderNameInS" name="areaFolderNameInS" value=""/>
		<input type="hidden" id="areaSwitched" name="areaSwitched" value="true"/>
		<input type="hidden" id="server" name="server" value="true"/>
	</form>
</div>

<table style="min-width: 1200px;">
<h3>Area List</h3>
<?php
  ######## FILE INFO #########
  # name: areamanager.php
  # author: RaveN
  # date: Mar 13 2017
  ############################
  
  # resource to list dir and file info
  include 'files.php';
  
  ######## DIR CONFIG #########
  # production settings
  if($_SERVER["DOCUMENT_ROOT"] == "G:\Website") {
	# Amia A half dynamic
	$resourceDirAHalfDyn = 'G:\NWN_A\modules\Areas';
	# Amia B half dyanmic 
	$resourceDirBHalfDyn = 'G:\NWN_B\modules\Areas';
	# Amia A and B Full Dyanmic
	$resourceDirFullDyn = 'G:\Resources\Areas';
	# Action backup folder
	$resourceDirBackup = 'G:\Area_Backup';
  } else {
  # dev mode
  $resourceDirAHalfDyn = '/volume1/web/amia/resources_half';
  $resourceDirBHalfDyn = '/volume1/web/amia/resources_half2';
  $resourceDirFullDyn = '/volume1/web/amia/resources';
  $resourceDirBackup = '/volume1/web/amia/area_backup';
  }
  
  ##############################
  
  # linux uses '/' while windows uses '\'
  if(strpos($resourceDirAHalfDyn,'\\') === false && strpos($resourceDirBHalfDyn,'\\') === false && strpos($resourceDirFullDyn,'\\') === false) {
	$folderDelimiter = "/";
  }
  else if(strpos($resourceDirAHalfDyn,'/') === false && strpos($resourceDirBHalfDyn,'/') === false && strpos($resourceDirFullDyn,'/') === false) {
	$folderDelimiter = "\\";
  }
  else {
	  echo "Invalid folder nesting in 'areamanager.php'. Use only one '/' or '\' direction.";
  }

  # check and handle incoming upload 
  
  if(isset($_FILES['upload_are']) && isset($_FILES['upload_gic']) && isset($_FILES['upload_git']) ) {
    
	$uploadedFiles = array('upload_are','upload_gic','upload_git');
	# base 64 decode path from post
	$areaFilePath = base64_decode($_POST["areaFilePath"]);
	
	foreach($uploadedFiles as $uploadFileName ) {
    		# filesize validator 
    		if ($_FILES[$uploadFileName]['size'] > 1000000) {
        		echo "Error, exceeded file size limit!";
				return;
    		}
			
			# get path info
			$file_info = pathinfo($_FILES[$uploadFileName]['name']);
			$name = $file_info['filename'];
			$ext = $file_info['extension'];
			
    		# upload file
    		if (!move_uploaded_file($_FILES[$uploadFileName]['tmp_name'],"$areaFilePath$name.$ext")) {
        		echo "Error, failed to move file!";
				return;
    		}
			
	}
	if(isset($_POST["areaType"]) && $_POST["areaType"] === "Half") {
		echo "<font style='color: #33CC33'>*** Half Dynamic Area: {$_POST['areaFolderNameIn']} has been uploaded successfully! (Live After Next Reset or Area Respawn)</font><br />";
	}
	else {
		echo "<font style='color: #33CC33'>*** Full Dynamic Area: {$_POST['areaFolderNameIn']} has been uploaded successfully! (Live After Area Next Spawns)</font><br />";
	}
  } 
  
  # check for switched area types
  if(isset($_POST["areaSwitched"]) && $_POST["areaSwitched"] == true) {

	  # grab form var
	  if(isset($_POST["server"]) && $_POST["server"] != "") {
		  $server = $_POST["server"]; 
		  if($server == "A") {
			  $resourceDirHalfDyn = $resourceDirAHalfDyn;
		  } else if ($server == "B") {
			  $resourceDirHalfDyn = $resourceDirBHalfDyn;
		  }
	  }
	  else { 
		  echo "<font style='color:#FF0000'>*** Unknown Error (0)! ***</font>";
		  return;
	  } 

	  if(isset($_POST["areaFilePathS"]) && $_POST["areaFilePathS"] != "") {
		  $areaFilePath = base64_decode($_POST["areaFilePathS"]); 
	  }
	  else { 
		  echo "<font style='color:#FF0000'>*** Unknown Error (1)! ***</font>";
		  return;
	  } # nothing to do here missing form variables
	  
	  if(isset($_POST["areaFileNameS"]) && $_POST["areaFileNameS"] !== "") {
		  $areaFileName = $_POST["areaFileNameS"];
	  }
	  else { 
		  echo "<font style='color:#FF0000'>*** Unknown Error (2)! ***</font>";
		  return;
	  } # nothing to do here missing form variables
	  if(isset($_POST["areaTypeS"]) && $_POST["areaTypeS"] !== "") {
		  $areaType = $_POST["areaTypeS"];
	  }
	  else { 
		  echo "<font style='color:#FF0000'>*** Unknown Error (3)! ***</font>";
		  return;
	  } # nothing to do here missing form variables
	  
	  # get end of new path
	  if(strpos($areaFilePath,$resourceDirHalfDyn) === 0) {
		  $abbreviatedFolderName = rtrim(substr($areaFilePath, strlen($resourceDirHalfDyn)),$folderDelimiter);
	  }
	  else if(strpos($areaFilePath,$resourceDirFullDyn) === 0) {
		  $abbreviatedFolderName = rtrim(substr($areaFilePath, strlen($resourceDirFullDyn)),$folderDelimiter);
	  }
		
	  # make full dynamic
	  if($areaType == "halfa" || $areaType == "halfb") {
			$currentPath = $resourceDirHalfDyn . $abbreviatedFolderName;
			$futurePath = $resourceDirFullDyn . $abbreviatedFolderName;
			$cleanDir = $resourceDirHalfDyn;
			$areaType = "full";
			switchAreaType($currentPath,$futurePath,$areaFileName,$resourceDirBackup,$cleanDir,$folderDelimiter);
	  }
	  #make half dynamic
	  else if($areaType == "full") {
			$currentPath = $resourceDirFullDyn . $abbreviatedFolderName;
			$futurePath = $resourceDirHalfDyn . $abbreviatedFolderName; 
			$cleanDir = $resourceDirFullDyn;
			$areaType = "half" . $server;
			switchAreaType($currentPath,$futurePath,$areaFileName,$resourceDirBackup,$cleanDir,$folderDelimiter);
	  }
	  else { 
		  echo "<font style='color:#FF0000'>*** Unknown Error (3)! ***</font>";
		  return;
	  } # nothing to do here missing form variables
	  
  echo "<font style='color: #33CC33'>*** Area: {$_POST['areaFolderNameInS']} has been made " . $areaType ." dynamic successfully!</font> <font style='color:#FF0000'>* Requires NWNX Restart for area to work as normal.</font><br />";
  }

  # functions 
  function switchAreaType($currentPath,$futurePath,$areaFileName,$resourceDirBackup,$cleanDir,$folderDelimiter) {
	  
	  # determine if the folder exists
	  if(!is_dir($futurePath)) {
			mkdir($futurePath, 0777, true);
	  }
	  if(!is_dir($futurePath)) {
		  echo "<font style='color: #FF0000'>*** Error: Unable to create folder to move to the new dynamic type!</font>";
		  return;
	  }
	  
	  $fileTypeAR = array('.are','.gic','.git');
	  
	  # iterate files
	  foreach($fileTypeAR as $ext) {
			$source = $currentPath . $folderDelimiter . $areaFileName . $ext;
			$backup = $resourceDirBackup . $folderDelimiter . $areaFileName . $ext;
			$destination = $futurePath . $folderDelimiter . $areaFileName . $ext;
			
			# verify source file
			if(!is_file($source)) {
				echo "<font style='color: #FF0000'>*** Error: Unable to find source file {$areaFileName}{$ext}!</font>";
				return;
			}
			
			# copy to backup folder
			if(!copy($source,$backup)) {
				echo "<font style='color: #FF0000'>*** Error: Failed to backup file {$areaFileName}{$ext}!</font>";
				return;
			}
			
			# move to new folder
			if(!rename($source,$destination)) {
				echo "<font style='color: #FF0000'>*** Error: Failed to copy file {$areaFileName}{$ext}!</font>";
				return;
			}
			
			# clean source folder
			$emptyDir = is_dir_empty($currentPath);
			
			while($emptyDir) {
				# dangerous function here, if configured improperly
				rmdir($currentPath);
				
				# if not equal to base folder, keep trimming
				$currentPath = strrev($currentPath);
				$delimiter = strpos($currentPath,$folderDelimiter);
				$currentPath = substr($currentPath, $delimiter, strlen($currentPath) + 1);
				$currentPath = strrev($currentPath);
				
				# hit base folder, done
				if(strpos($currentPath,$cleanDir) !== 0) {
					break;
				}
				$emptyDir = is_dir_empty($currentPath);
			}
	  }
  }
  
  function is_dir_empty($dir) {
	  if (!is_readable($dir)) return NULL; 
	  $handle = opendir($dir);
	  while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
		  return FALSE;
		}
	  }
	  return TRUE;
  }
  
  function formatDateTime($dateLastModified) {
	$dateLastModified = gmdate("m/d/Y h:i A",$dateLastModified);
	return $dateLastModified;
  }
  
  function formatTimeHours($unixTime) {
		$date = new DateTime();
		$dateNow = $date->setTimestamp(time());
		$date = new DateTime();
		$dateFile = $date->setTimestamp($unixTime);
		
		$timeDistance = $dateNow->diff($dateFile);
		$timeDistance = $timeDistance->format('%a day(s) and %h hour(s)');
		
	return $timeDistance;  
  }

  # populate half dynamic array  
  $halfDynAAR = read_all_files($resourceDirAHalfDyn);
  $halfDynBAR = read_all_files($resourceDirBHalfDyn);
  
  # populate full dynamic array 
  $fullDynAR = read_all_files($resourceDirFullDyn);
  
  # merge list from both folders
  $mergeFileNameAR = array_merge($halfDynAAR["files"], $fullDynAR["files"], $halfDynBAR["files"]);

  
  $mergeFolderListAR = array_merge($halfDynAAR["dirs"],$halfDynBAR["dirs"],$fullDynAR["dirs"]);
  
  $resourcesAR = array();

  # build folder list with array 
  foreach($mergeFolderListAR as $foldername) {
  	# determine which folder length to trim
	if(strpos($foldername,$resourceDirAHalfDyn) === 0) {
		$abbreviatedFolderName = rtrim(substr($foldername, strlen($resourceDirAHalfDyn)),$folderDelimiter);
  	}
	else if(strpos($foldername,$resourceDirBHalfDyn) === 0) {
		$abbreviatedFolderName = rtrim(substr($foldername, strlen($resourceDirBHalfDyn)),$folderDelimiter);
	}
  	else if(strpos($foldername,$resourceDirFullDyn) === 0) {
		$abbreviatedFolderName = rtrim(substr($foldername, strlen($resourceDirFullDyn)),$folderDelimiter);
  	}

        $resourcesAR[$abbreviatedFolderName] = $foldername;
  }
  
  # sort merged list
  ksort($resourcesAR,SORT_REGULAR);

  # package array
  $areasAR = array();
  $i = 0;

  foreach($resourcesAR as $areaFolderName => $areaFilePath) {
	# loop through area files
	foreach($mergeFileNameAR as $areaFileName) {
		# compare strings
 		$testStr = strpos($areaFileName,$areaFilePath);
		if($testStr === 0) {
			# check if full match
			$testStr = substr($areaFileName,strlen($areaFilePath));
			# if a slash is present, it is not the parent
			if(strpos($testStr,$folderDelimiter) === false) {
				$extension = substr($testStr,strpos($testStr,'.') + 1);
				$areasAR[$i][$extension] = $areaFileName;
				# get shortname reverse
				$reverseCount = strpos(strrev($areaFolderName),$folderDelimiter);
				$areasAR[$i]["shortname"] = substr($areaFolderName, -$reverseCount);
				# parse reverse
				$reverseCount = strpos(strrev($areaFileName),$folderDelimiter);
				$areaFileName = substr($areaFileName, -$reverseCount);
				# strip extension
				$areaFileName = strrev($areaFileName);
				$reverseCount = strpos($areaFileName,'.') + 1;
				$areaFileName = substr($areaFileName, $reverseCount);
				$areaFileName = strrev($areaFileName);
				$areasAR[$i]["name"] = $areaFileName;
				# add folder name
				$areasAR[$i]["folder"] = $areaFolderName;
				# determine half or full dynamic 
				$testStrA = strpos($areaFolderName,$resourceDirAHalfDyn);
				if($testStrA === false) $testStrA = strpos($areaFolderName,$resourceDirBHalfDyn);
				$testStrB = strpos($areaFolderName,$resourceDirFullDyn);

				if($testStrA === false && $testStrB === false) {
					if(strpos($areaFilePath,$resourceDirAHalfDyn) === 0) {
						$areasAR[$i]["type"] = "HalfA";
  					}
					else if(strpos($areaFilePath,$resourceDirBHalfDyn) === 0) {
						$areasAR[$i]["type"] = "HalfB";
					}
  					else if(strpos($areaFilePath,$resourceDirFullDyn) === 0) {
						$areasAR[$i]["type"] = "Full";
  					}
				}
				# add file path
				$areasAR[$i]["path"] = $areaFilePath;
			}
		} 
	} 
	$i += 1;	  
  }

  # output 

  # build header
  echo "<tr>
		<td class='messenger4'><font style='font-size: 14px;'><b>Area Name</b></font></td>
		<td class='messenger4' style='text-align: center;'><font style='font-size: 14px;'><b>Manage Area Files</b></font></td>
		<td class='messenger4' style='text-align: center;'><font style='font-size: 14px;'><b>Area Type</b></font></td>
		<td class='messenger4' style='text-align: center;'><font style='font-size: 14px;'><b>Resref</b></font></td>
		<td class='messenger4' style='text-align: center;'><font style='font-size: 14px;'><b>*.are</b></font></td>
		<td class='messenger4' style='text-align: center;'><font style='font-size: 14px;'><b>*.gic</b></font></td>
		<td class='messenger4' style='text-align: center;'><font style='font-size: 14px;'><b>*.git</b></font></td>
		<td class='messenger4' style='text-align: center;'><font style='font-size: 14px;'><b>Date Last Modified</b></font></td>
	</tr>";

  # loop output
  $i = 0;
  
  foreach($areasAR as $area) {
		# definitions
		$area["date_unix"] = "";

		# determine if files are missing and build time of last modified dates
		if(array_key_exists('are',$area)) {
			$date_unix = filemtime($area['are']);
			$date_last_modified = formatDateTime($date_unix);
			$are_eval = "	<td class='messenger5' style='text-align:center; vertical-align:middle;' title='{$date_last_modified}'>
			         	<font style='color: #33CC33;>'>✓</font>
					</td>";
			$area["date_unix"] = $date_unix;
		}
		else {
			$are_eval = "	<td class='messenger5' style='text-align:center; vertical-align:middle;' title='Missing!'>
					<font style='color: #FF0000;'>x</font>
					</td>";
		}
		if(array_key_exists('gic',$area)) {
			$date_unix = filemtime($area['gic']);
			$date_last_modified = formatDateTime($date_unix);
			$gic_eval = "	<td class='messenger5' style='text-align:center; vertical-align:middle; ' title='{$date_last_modified}'>
			         	<font style='color: #33CC33;'>✓</font>
					</td>";
			if(array_key_exists('date_unix',$area) && ($date_unix > $area['date_unix'])) {
				$area["date_unix"] = $date_unix;
			} 
		}
		else {
			$gic_eval = "	<td class='messenger5' style='text-align:center; vertical-align:middle;' title='Missing!'>
					<font style='color: #FF0000;'>x</font>
					</td>";
		}	
		if(array_key_exists('git',$area)) {
			$date_unix = filemtime($area['git']);
			$date_last_modified = formatDateTime($date_unix);
			$git_eval = "	<td class='messenger5' style='text-align:center; vertical-align:middle;' title='{$date_last_modified}'>
			         	<font style='color: #33CC33;'>✓</font>
					</td>";
			if(array_key_exists('date_unix',$area) && ($date_unix > $area['date_unix'])) {
				$area["date_unix"] = $date_unix;
			} 
		}
		else {
			$git_eval = "	<td class='messenger5' style='text-align:center; vertical-align:middle;' title='Missing!'>
					<font style='color: #FF0000;x</font>
					</td>";
		}		
		# shortname title
		$areaFolderName = $area['folder']; 
		$area["date_last_modified"] = formatDateTime($area["date_unix"]);
		# get distance to now 
		$timeDistance = formatTimeHours($area["date_unix"]);
		
		# prepare to pass php vars to js	
		$name_eval = $area["name"];
		$type_eval = $area["type"];
		$module_no = 1;

		if($type_eval == "Full") {
			$areaSwitcher = "spawnChooseServer";
		} else if($type_eval == "HalfA") {
			$areaSwitcher = "areaswitch";
			$module_no = 1;
		} elseif ($type_eval == "HalfB") {
			$areaSwitcher = "areaswitch";
			$module_no = 2;
		}

		$path_eval = base64_encode($area["path"]);
		# do not pass path, get from html because of formatting

		# output row
		echo "<tr id='{$i}_row'>
			<td class='messenger5' style='vertical-align:middle' title='{$areaFolderName}' id='{$i}_shortname'><a href='show_area.php?resref=$name_eval&module=$module_no' target='_blank'>{$area['shortname']}</a></td>
			<td class='messenger5' style='text-align:center; vertical-align:middle'>
				<a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only button' role='button'><span id='dlb_$i' class='ui-button-text' onclick='download({$i},&quot;$name_eval&quot;,&quot;$type_eval&quot;,&quot;$path_eval&quot;);'>Download &#8681;</span></a>
				<a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only button' role='button'><span id='upb_$i' class='ui-button-text' onclick='upload({$i},&quot;$name_eval&quot;,&quot;$type_eval&quot;,&quot;$path_eval&quot;);'>Upload &#8679;</span></a>
			</td>
			<td id='td_as_{$i}' class='messenger5' style='text-align:center; vertical-align:middle;'>
				<a class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only button' role='button'><span id='asb_$i' class='ui-button-text' onclick='$areaSwitcher({$i},&quot;$name_eval&quot;,&quot;$type_eval&quot;,&quot;$path_eval&quot;,1)'>{$type_eval} &#9851;</span></a>
			</td>
			<td class='messenger5' style='text-align:center; vertical-align:middle;'>{$name_eval}</td>
			{$are_eval}
			{$gic_eval}
			{$git_eval}
			<td class='messenger5' style='text-align:center; vertical-align:middle;' title='$timeDistance'>{$area["date_last_modified"]}</td>
			<td class='messenger5' style='display: none' id='{$i}_areaFolderName'>{$area['folder']}</td>
	          </tr>";
		
		# iteration 
		$i += 1;
  }
  
  # debug lines
  if(isset($_GET["debug"])) {
	echo "<h1>DEBUG: Areas AR</h1>\n";
	var_dump($areasAR);
	echo "<h1>DEBUG: Resources AR</h1>\n";
	var_dump($resourcesAR);
	echo "<h1>DEBUG: Half Dynamic AR</h1>\n";
  	var_dump($halfDynAR);
	echo "<h1>DEBUG: Full Dynamic AR</h1>\n";
  	var_dump($fullDynAR);
	echo "<h1>DEBUG: Merged File Name AR</h1>\n";
  	var_dump($mergeFileNameAR);
	echo "<h1>DEBUG: Merged Folder List AR</h1>\n";
  	var_dump($mergeFolderListAR);
  }

?>
</table>

</body>
</html>
