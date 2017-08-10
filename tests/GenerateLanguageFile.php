<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Language;
class GenerateLanguageFile extends \PHPUnit_Framework_TestCase
{
    public function testGenerateLanguageFile() 
    {
		$lang   = new \Language\LanguageBatchBo();
		$lang_t = $lang->generateLanguageFiles();
		$this->assertEquals($lang_t, "file generated");
	}
}