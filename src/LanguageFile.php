<?php
namespace Language;
class LanguageFile
{
	
	/**
	 * Starts the language file generation.
	 *
	 * @return void
	 */
	public static function generateLanguageFiles()
	{
		// The applications where we need to translate.
		try {
			$error = false;
			$apps = Config::get('system.translated_applications');
			if(!empty($apps)) {
				PrintMsg::displayMsg("\nGenerating language PHP files:");
				foreach ($apps as $application => $languages) {
					PrintMsg::displayMsg("[APPLICATION: " . $application . "]");
					foreach ($languages as $language) {
						if (self::getLanguageFile($application, $language)) {
							PrintMsg::displayMsg("\t[LANGUAGE: " . $language . "] OK");
						} else {
							$error = true;
							PrintMsg::displayMsg("\t[LANGUAGE: " . $language . "] NOK");
							throw new \Exception('Unable to generate language file!', 200);
						}
					}
				}
			} else {
				$error = true;
				throw new \Exception('Empty applications returned from method Config::get', 201);
			}
		} catch (\Exception $e) {
			PrintMsg::displayMsg("\n\n["."ERROR OCCURED PLEASE CONTACT ADMINISTRATOR".": (".$e->getCode().")]"
				." detected \n\tOn file: ".$e->getFile().","
				."\n\tAt line: ".$e->getLine().", with message: "
				.$e->getMessage()."\n\n");
		}
		if (!$error) {
			PrintMsg::displayMsg("Application language PHPs generated.\n");
		} else {
			PrintMsg::displayMsg("Error during language PHPs generation.\n");
		}
	}


	/**
	 * Gets the language file for the given language and stores it.
	 *
	 * @param string $application   The name of the application.
	 * @param string $language      The identifier of the language.
	 *
	 * @throws CurlException   If there was an error during the download of the language file.
	 *
	 * @return bool   The success of the operation.
	 */
	protected static function getLanguageFile($application, $language)
	{
		$result = false;
		$languageResponse = ApiCall::call(
			'system_api',
			'language_api',
			array(
				'system' => 'LanguageFiles',
				'action' => 'getLanguageFile'
			),
			array('language' => $language)
		);
		try {
			CheckApiError::checkForApiErrorResult($languageResponse);
		} catch (\Exception $e) {
			throw new \Exception('Error during getting language file: (' 
				. $application . '/' . $language . ')', 202);
		}
		// If we got correct data we store it.
		$path = FileOperation::getLanguageCachePath($application);
		$destination = FileOperation::checkIfFileExists($path, $language, '.php');
		$fullfilename = $path.'/'.$language.'.php';
		// Write language translation to destiantion file
		if(!empty($languageResponse)) {
			$result = FileOperation::storeLanguageFile($fullfilename, $languageResponse['data']);
			return $result;
		} else {
			return $result;
		}
	}
	
}

?>