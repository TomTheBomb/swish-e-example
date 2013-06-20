<?php

class ResultText {

	public function __construct($files = array(), $words, $wordTrim = 10) {

		if (empty($files)) {
			return;
		}

		foreach ($files as $file) {
			$t = $this->searchContent($this->getContent($file), $words, $wordTrim);
			var_dump($t);
		}

	}

	private function checkFileType($fileLoc) {
		$i = explode('.', $fileLoc);
		return strtolower(end($i));
	}

	private function getContent($fileLoc) {
		//Check filetype
		$type = $this->checkFileType($fileLoc);
		switch ($type) {
			case 'txt':
				return file_get_contents($fileLoc);
				break;
			case 'pdf':
				return shell_exec("pdftotext {$fileLoc} -");
				break;
			case 'doc':
				return shell_exec("catdoc {$fileLoc}");
			case 'docx':
				return null;
		}
	}

	private function searchContent($content, $words, $wordTrim) {
		preg_match_all(
    		"/(\S+\s+){0,{$wordTrim}} # Match five (or less) 'words'
    		\S*             # Match (if present) punctuation before the search term
    		\b              # Assert position at the start of a word
    		{$words}           # Match the search term
    		\b              # Assert position at the end of a word
    		\S*             # Match (if present) punctuation after the search term
    		(\s+\S+){0,{$wordTrim}}   # Match five (or less) 'words'
    		/ix", 
    	$content, $result, PREG_PATTERN_ORDER);
		if (!empty($result[0])) {
			return $result[0];
		}
		return;
	}	
}

$blah = new ResultText(array('/var/www/swish/files/bob.txt', '/var/www/swish/files/Inside_Retail_Ad.pdf', '/home/trothwell/Documents/doc1.doc'), 'contact', 4);
