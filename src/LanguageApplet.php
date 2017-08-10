<?php
namespace Language;
class LanguageApplet
{
	/**
	 * Gets the language files for the applet and puts them into the cache.
	 *
	 * @throws Exception   If there was an error.
	 *
	 * @return void
	 */
	public static function generateAppletLanguageXmlFiles()
	{
		// List of the applets [directory => applet_id].
		$applets = array(
			'memberapplet' => 'JSM2_MemberApplet'
		);
		PrintMsg::displayMsg("\nGenerating applet language XMLs:");
		$error = false;
		try {
			foreach ($applets as $appletDirectory => $appletLanguageId) {
				PrintMsg::displayMsg("[APPLET: ".$appletLanguageId."]"
					." - [DIR: ".$appletDirectory."]\n");
				$languages = self::getAppletLanguages($appletLanguageId);
				if (empty($languages)) {
					$error = true;
					throw new \Exception('There is no available languages for the ' . $appletLanguageId . ' applet.', 100);
				}
				$path = FileOperation::getLanguageCachePath('flash');
				foreach ($languages as $language) {
					
						$xmlContent  = self::getAppletLanguageFile($appletLanguageId, $language);
						if(empty($xmlContent)) {
							$error = true;
							throw new \Exception('There is no XMLContent for applet: ('.$appletLanguageId.')'
								.' language: ('.$language.')!', 101);
						} else {
							$xmlFile = FileOperation::checkIfFileExists($path, '/lang_'.$language, '.xml');
							if (FileOperation::storeLanguageFile($xmlFile, $xmlContent)) {
								PrintMsg::displayMsg("\t[LANGUAGE: " .implode(', ', $languages) 
									. "] "."OK");
							} else {
								$error = true;
								PrintMsg::displayMsg("\t[LANGUAGE: " . implode(', ', $languages) 
									. "] "."NOK");
								throw new \Exception('Unable to save applet: ('.$appletLanguageId.')'
									.'language: ('.$language.') xml ('.$xmlFile.')!', 102);
							}
						}
					
				}
				
				if (!$error) {
					PrintMsg::displayMsg("\t[XML CACHED: ".$appletLanguageId."] "
						."OK");
				} else {
					PrintMsg::displayMsg("\t[XML CACHED: ".$appletLanguageId."] "
						."NOK");
				}
			}
		} catch (\Exception $e) {
			$error = true;
			PrintMsg::displayMsg("\n\n["."ERROR".": (".$e->getCode().")]"
				." detected \n\tOn file: ".$e->getFile().","
				."\n\tAt line: ".$e->getLine().", with message: "
				.$e->getMessage()."\n\n");
		}
		if (!$error) {
			PrintMsg::displayMsg("Applet language XMLs generated.\n");
		} else {
			PrintMsg::displayMsg("Error during language XMLs generation.\n");
		}
	}
	/**
	 * Gets the available languages for the given applet.
	 *
	 * @param string $applet   The applet identifier.
	 *
	 * @return array   The list of the available applet languages.
	 */
	protected static function getAppletLanguages($applet)
	{		
		$result = ApiCall::call(
			'system_api',
			'language_api',
			array(
				'system' => 'LanguageFiles',
				'action' => 'getAppletLanguages'
			),
			array('applet' => $applet)
		);
		try {	
			CheckApiError::checkForApiErrorResult($result);
		} catch (\Exception $e) {
			throw new \Exception('Getting languages for applet (' . $applet . ') was unsuccessful ', 103);
		}
		
		if (empty($result)) {
			return;
		} else {
			return $result['data'];
		}
	}
	/**
	 * Gets a language xml for an applet.
	 *
	 * @param string $applet      The identifier of the applet.
	 * @param string $language    The language identifier.
	 *
	 * @return string|false   The content of the language file or false if weren't able to get it.
	 */
	protected static function getAppletLanguageFile($applet, $language)
	{
		$result = ApiCall::call(
			'system_api',
			'language_api',
			array(
				'system' => 'LanguageFiles',
				'action' => 'getAppletLanguageFile'
			),
			array(
				'applet' => $applet,
				'language' => $language
			)
		);
		try {
			CheckApiError::checkForApiErrorResult($result);
		} catch (\Exception $e) {
			throw new \Exception('Getting language xml for applet: (' . $applet . ')'
				. ' on language: (' . $language . ') was unsuccessful: ', 104);
		}
		return $result['data'];
	}
}