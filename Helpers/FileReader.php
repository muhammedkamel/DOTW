<?php

namespace DOTW\Helpers;

class FileReader {

	private $handler;
	private $path;

	public function __construct($path) {
		$this->path = $path;
	}

	public function readFile() {
		if ($this->path) {
			return file_get_contents($this->path);
		}
	}

}