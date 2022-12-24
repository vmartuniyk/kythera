<?php
namespace Kythera\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @author virgilm
 *
 */
class DocumentUploader extends Eloquent
{
	public static function detectFileType($post)
	{
		$result = 'text';
		if (isset($post['files'])) {
			$files = static::getUploaderFiles2($post['files'], true);
			if (count($files['image']) > 0) $result = 'image';
			elseif (count($files['audio']) > 0) $result = 'audio';
			elseif (count($files['video']) > 0) $result = 'video';
		}
		return $result;
	}

	public static function getUploaderFiles($post, $group = false)
	{
		$result = array();

		//convert
		foreach ($post as $k=>$v) {
			if (preg_match('#^uploader_([0-9]+)_(.*)#', $k, $m)) {
				$result[$m[1]][$m[2]] = $v;
			}
		}

		//add paths
		foreach ($result as $i=>$file) {
			if ($file['status']=='done') {
				/*
				$ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
				if (in_array($ext, array('jpg','png','gif'))) {
					$result[$i]['type'] = 'image';
				} else
				if (in_array($ext, array('mp3','wav'))) {
					$result[$i]['type'] = 'audio';
				} else
				if (in_array($ext, array('mp4','flv'))) {
					$result[$i]['type'] = 'video';
				} else
					$result[$i]['type'] = 'unknown';
					*/

				if (DocumentEntity::isImage($file['name'])) {
					$result[$i]['type'] = 'image';
				} else
				if (DocumentEntity::isAudio($file['name'])) {
					$result[$i]['type'] = 'audio';
				} else
				if (DocumentEntity::isVideo($file['name'])) {
					$result[$i]['type'] = 'video';
				} else
					$result[$i]['type'] = 'unknown';


				if (!$file['kfnid']) {
					$path = sprintf('%s/tmp/%s/', public_path(), \Session::getId());
					if (file_exists($path.$file['name'])) {
						$result[$i]['path'] = $path;//sprintf('%s/tmp/%s', public_path(), \Session::getId());
						$result[$i]['uri']  = sprintf('/tmp/%s/%s', \Session::getId(), $file['name']);
					}
				} else {
					$path = sprintf('%s/%s', public_path(), $file['path']);
					if (file_exists($path.$file['name'])) {
						$result[$i]['path'] = $path;
						$result[$i]['uri']  = sprintf('/%s%s', $file['path'], $file['name']);
					}
				}
			}
		}

		//group by media type image/audio/video
		if ($group) {
			$tmp = array('image'=>array(), 'audio'=>array(), 'video'=>array());
			foreach ($result as $file) {
				/*
				$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
				if (in_array($ext, array('jpg','png','gif'))) {
					$tmp['image'][] = $file;
				} else
				if (in_array($ext, array('mp3','wav','wma'))) {
					$tmp['audio'][] = $file;
				} else
				if (in_array($ext, array('mp4','flv'))) {
					$tmp['video'][] = $file;
				} else
					$tmp['file'][] = $file;
				*/

				if (DocumentEntity::isImage($file['name'])) {
					$tmp['image'][] = $file;
				} else
				if (DocumentEntity::isAudio($file['name'])) {
					$tmp['audio'][] = $file;
				} else
				if (DocumentEntity::isVideo($file['name'])) {
					$tmp['video'][] = $file;
				} else
					$tmp['file'][] = $file;

			}
			$result = $tmp;
		}

		return $result;
	}


	public static function getUploaderFiles2($files, $group = true)
	{
		$result = array();

		//add paths
		foreach ($files as $i=>$file) {
			$name = pathinfo($file['f'], PATHINFO_BASENAME);
			if (!isset($file['id'])) {
				$path = sprintf('%s/tmp/%s/', public_path(), \Session::getId());
				$ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
				$size = 777;
				if (file_exists($path.$name)) {
					$files[$i]['name'] = $name;
					$files[$i]['path'] = $path;
					$files[$i]['uri']  = sprintf('/tmp/%s/%s', \Session::getId(), $name);
					$files[$i]['size'] = $size;
				}
			} else {
				$path = sprintf('%s%s/', public_path(), pathinfo($file['f'], PATHINFO_DIRNAME));
				$uri  = sprintf('%s', pathinfo($file['f'], PATHINFO_DIRNAME));
				$ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
				if (file_exists($path.$name)) {
					$files[$i]['name'] = $name;
					$files[$i]['path'] = $path;
					$files[$i]['uri']  = $uri;
					$files[$i]['size'] = $size;
				}
			}
		}

		//group by media type image/audio/video
		if ($group) {
			$tmp = array('image'=>array(), 'audio'=>array(), 'video'=>array());
			foreach ($files as $file) {
				//if (file_exists(public_path().$file['f'])) {
					/*
					$ext = strtolower(pathinfo($file['f'], PATHINFO_EXTENSION));
					if (in_array($ext, array('jpg','png','gif'))) {
						$tmp['image'][] = $file;
					} else
					if (in_array($ext, array('mp3','wav','wma'))) {
						$tmp['audio'][] = $file;
					} else
					if (in_array($ext, array('mp4','flv'))) {
						$tmp['video'][] = $file;
					} else
						$tmp['file'][] = $file;
					*/

					if (DocumentEntity::isImage($file['f'])) {
						$tmp['image'][] = $file;
					} else
					if (DocumentEntity::isAudio($file['f'])) {
						$tmp['audio'][] = $file;
					} else
					if (DocumentEntity::isVideo($file['f'])) {
						$tmp['video'][] = $file;
					} else
						$tmp['file'][] = $file;

				//}
			}
			$result = $tmp;
		}

		return $result;
	}


	/**
	 * @param array $files
	 * @param string $type
	 * @return string
	 * jquery.ui.plupload.custom: addFiles: function() r:793
	 */
	public static function setUploaderFiles(array $files, $type = 'image')
	{
		//to array
		$files = json_decode(json_encode((array) $files), true);
		foreach ($files as $i=>$file) {
			$files[$i]['status'] = 'done';
			switch ($type) {
				case 'image':
					$files[$i]['path'] = sprintf('%s/%s%s', public_path(), $file['image_path'], $file['image_name']);
					$files[$i]['uri']  = sprintf('/%s%s', $file['image_path'], $file['image_name']);
					$files[$i]['v'] = '';
					$files[$i]['taken'] = date('d/m/Y', strtotime($file['taken']));
					$files[$i]['type'] = $type;
					$files[$i]['size'] = @filesize($files[$i]['path']);
					break;
				case 'audio':
				case 'video':
					$files[$i]['path'] = sprintf('%s/%s%s', public_path(), $file['audio_path'], $file['audio_name']);
					$files[$i]['uri']  = sprintf('/%s%s', $file['audio_path'], $file['audio_name']);
					$files[$i]['v'] = '';
					$files[$i]['taken'] = date('d/m/Y', strtotime($file['recorded']));
					$files[$i]['type'] = $type;
					$files[$i]['size'] = @filesize($files[$i]['path']);
					break;
			}
		}
		return $files;
	}


	/**
	 * Find already uploaded files. Accessed through back button un multi entry form
	 */
	public static function setUploaderTempFiles()
	{
		$files = array();
		$targetDir = public_path().'/tmp/'.\Session::getId().'/';
		if (is_dir($targetDir) && $dir = opendir($targetDir)) {
			while ((($file = readdir($dir)) !== false)) {
				if (DocumentEntity::isImage($file))
				$files[] = array(
					'image_path' => 'tmp/'.\Session::getId().'/',
					'image_name' => $file,
					'original_image_name' => $file,
					'type' => 'image',
					'kfnid'=> 0,
					'size' => @filesize($targetDir.$file)
				);
			}
			@closedir($dir);
		}
		return $files;
	}

	/**
	 * Process ajax requests made by plupload.
	 *
	 * Code taken from upload.php
	 *
	 * Copyright 2013, Moxiecode Systems AB
	 * Released under GPL License.
	 *
	 * License: http://www.plupload.com/license
	 * Contributing: http://www.plupload.com/contributing
	 */
	public static function processFile()
	{
		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Settings
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		// Create target dir
		$targetDir = public_path().'/tmp/';
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}
		$targetDir.= \Session::getId();
		if (!file_exists($targetDir)) {
			@mkdir($targetDir);
		}

		// Check
		if (!file_exists($targetDir)) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 110, "message": "Target dir does not exists: '.$targetDir.'"}}');
		}


		// Get a file name
		if (isset($_REQUEST["name"])) {
			$fileName = $_REQUEST["name"];
		} elseif (!empty($_FILES)) {
			$fileName = $_FILES["file"]["name"];
		} else {
			$fileName = uniqid("file_");
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

		// Remove old temp files
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}

			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}


		// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}

		if (!empty($_FILES)) {
			if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			}

			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		} else {
			if (!$in = @fopen("php://input", "rb")) {
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
		}

		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}

		@fclose($out);
		@fclose($in);

		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off
			rename("{$filePath}.part", $filePath);
		}

		// Return Success JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}


	public static function deleteFile($file)
	{
		$targetDir = public_path().'/tmp/'.\Session::getId().'/';
		if (file_exists($targetDir.$file)) {
			@unlink($targetDir.$file);
		}
	}

	/**
	 * Remove tmp session upload folder
	 */
	public static function clean()
	{
		$targetDir = public_path().'/tmp/'.\Session::getId().'/';
		if (is_dir($targetDir) && $dir = opendir($targetDir)) {
			while ((($file = readdir($dir)) !== false)) {
				if (in_array($file, array('.', '..'))) continue;
				@unlink($targetDir.$file);
			}
			@closedir($dir);
			@rmdir($targetDir);
		}
	}
}
