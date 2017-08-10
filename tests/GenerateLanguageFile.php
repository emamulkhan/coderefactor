<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Language;
class GenerateLanguageFile extends PHPUnit_Framework_TestCase
{
    public function testGenerateLanguageFile() 
    {
		$lang   = new \Language\LanguageBatchBo();
		$lang_t = $lang->generateLanguageFiles();
		$this->assertEquals($lang_t, "file generated");
	}

	public function testGenerateApplet() 
    {
		$applet   = new \Language\LanguageApplet();
		$applet_1 = $applet->generateAppletLanguageXmlFiles();
		$this->assertEquals($applet_1, "applet xml file generated");
	}

}