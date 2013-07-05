<?php
/**
 * 
 *
 *
 */
class ResultText {

	public $results = array();

	public $wrapBefore = '<span style="background-color: #ffff00;"><strong>';

	public $wrapAfter = '</strong></span>';

	public $commonWords = array(
		'is', 'the', 'in', 'and', 'be', 'as', 'you'
	);

	/**
	 * Implement securty to prevent people uploading docs filled 
	 * with HTML / JS that are outputted not escaped
	 */
	public function __construct($files = array(), $words, $wordTrim = 10, $commonWords = false) {
		if (empty($files)) {
			return;
		}

		$words = $this->breakWords($this->removeNotSnippets($words));
		foreach ($words as $key => $word) {
			if ($commonWords) {
				//remove common words
				
			}

			//replace whitespace wit
			$words[$key] = preg_replace("/\s+/", '\s+', $word);
		}
		foreach ($files as $file) {
			$short = array_pop(explode('/', $file));
			$content = $this->getContent($file);
			$this->results[$short] = array();
			foreach ($words as $word) {
				$result = $this->searchContent($content, $word, $wordTrim);
				if (!empty($result)) {
					$this->results[$short][$word] = $result;
				}
			}
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
			default:
			case 'txt':
				return file_get_contents($fileLoc);
				break;
			case 'pdf':
				return shell_exec("pdftotext \"{$fileLoc}\" -");
				break;
			case 'doc':
				return shell_exec("catdoc \"{$fileLoc}\"");
				break;
			case 'docx':
				return shell_exec("sh ./catdocx.sh \"{$fileLoc}\"");
				break;
			case 'rtf':
				return shell_exec("unrtf --text \"{$fileLoc}\"");
				break;
		}
	}

	private function searchContent($content, $words, $wordTrim) {
		//break any keywords out
		$words = str_replace(array('*', '"', '(', ')'), array('.*?', '', '', ''), $words);
		$pattern = 
    		"/(\S+\s+){0,{$wordTrim}} # Match five (or less) 'words'
    		\S*             # Match (if present) punctuation before the search term
    		\b              # Assert position at the start of a word
    		({$words})           # Match the search term
    		\b              # Assert position at the end of a word
    		\S*             # Match (if present) punctuation after the search term
    		(\s+\S+){0,{$wordTrim}}   # Match five (or less) 'words'
    		/ix";
			
		$highlightPattern = "/\b({$words})\b/ix";
		preg_match_all($pattern, $content, $result, PREG_PATTERN_ORDER);
		
		if (!empty($result[0])) {
			//Wrap found results
			foreach ($result[0] as $key => $res) {
				//htmlentities, probably use a preg callback
				$result[0][$key] = preg_replace($highlightPattern, "{$this->wrapBefore}$0{$this->wrapAfter}", $result[0][$key]);
			}
			return $result[0];
		}
		return;
	}

	public function breakWords($words) {
		$brokenWords = array();
		//handle double quotes
		preg_match_all("/\"(.*?)\"/", $words, $quotes);
		$words = preg_replace("/\"(.*?)\"/", '', $words);
		if (!empty($quotes)) {
			$brokenWords = array_merge($brokenWords, $quotes[1]);
		}
		
		//remove AND / OR from words
		$words = str_replace(array(')', '('), array('', ''), $words);
		$words = trim(preg_replace(array('/\band\b/i', '/\bor\b/i', '/\bnot\b/i'), array('', '', ''), $words));
		$clean = preg_split('/\s+/', $words);
		if (!empty($words)) {
			$brokenWords = array_merge_recursive($clean, $brokenWords);
		}
		return $brokenWords;
	}

	public function removeNotSnippets($words) {
		//Remove NOT combos
		//NOT word
		//NOT "word"
		//word AND NOT "word"
		//word AND NOT word
		return preg_replace("/(?<=\bNOT\s)(\w+|\"(.*?)\")/i", null, $words);
	}

	public function getResults() {
		return $this->results;
	}

	public function setCommonWords($words) {
		$this->commonWords = $words;
	}
}
