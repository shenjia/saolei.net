<?php
class Uploader
{
    public $filePath;
    public $targetDir;
    
	public function __construct($targetDir = UPLOAD_TEMP_PATH)
	{
		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		// 5 minutes execution time
		@set_time_limit(5 * 60);
		
		// Uncomment this one to fake upload time
		// usleep(5000);

		$this->targetDir = $targetDir;
		$this->filePath = $targetDir . '/' . date('Y-m-d') . '-' . uniqid() . '.avf';
	}
	
	public function __destruct()
	{
	    if (file_exists($this->filePath))
	        @unlink($this->filePath);
	}
	
	public function getUploadFile()
	{
		$this->prepare();
		$this->uploadFile();
		return file_exists($this->filePath) ? $this->filePath : false;
	}
	
	public function success()
	{
		die('{"jsonrpc" : "2.0", "result" : "", "id" : "id" }');
	}
	
	public function error ( $code, $message )
	{
		die('{"jsonrpc" : "2.0", "error" : {"code": ' . $code . ', "message": "' . $message . '"}, "id" : "id" }');	
	}
	
	private function getContentType()
	{
		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
		
		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];
			
		return $contentType;
	}

	private function prepare()
	{
	    // Create target dir
    	if (!file_exists($this->targetDir))
		    @mkdir($this->targetDir, 0777, true);
		    
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		
		// Remove old temp files	
		if ($cleanupTargetDir && is_dir($this->targetDir) && ($dir = opendir($this->targetDir))) {
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $this->targetDir . DIRECTORY_SEPARATOR . $file;
		
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$this->filePath}.part")) {
					@unlink($tmpfilePath);
				}
			}
		
			closedir($dir);
		} else
			$this->error( 100, 'Failed to open temp directory.' );
	}
	
	private function uploadFile()
	{
		// Get parameters
		$contentType = $this->getContentType();
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = fopen("{$this->filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");
		
					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						$this->error( 101, 'Failed to open input stream.' );
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else
					$this->error( 102, 'Failed to open output stream.' );
			} else
				$this->error( 103, 'Failed to move uploaded file.' );
		} else {
			// Open temp file
			$out = fopen("{$this->filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");
		
				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					$this->error( 101, 'Failed to open input stream.' );
		
				fclose($in);
				fclose($out);
			} else
				$this->error( 102, 'Failed to open output stream.' );
		}
		
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$this->filePath}.part", $this->filePath);
		}
	}
}