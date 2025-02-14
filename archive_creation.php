<?php
/**
 * @author 
 * @copyright 2012
 */

function create_zip($files = array(), $destination = '', $overwrite = false){
	//if the zip file already exists & overwrite is false, return false
	if(file_exists($destination) && !$overwrite){return false;}
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)){
		//cycle thru each file
		foreach($files as $file){
			//make sure the file exists
			if(file_exists($file)){
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files
	if(count($valid_files)){
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE:ZIPARCHIVE::CREATE) !== true){
			return false;
		}
		//add the files
		foreach($valid_files as $file){
			$zip->addFile($file, $file);
		}
		//close zip
		$zip->close();
		//check to make file exists
		return file_exists($destination);
	}
	else{
		return false;
	}
}
?>