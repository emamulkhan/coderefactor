<?php
namespace Language;
class PrintMsg
{
	public static function displayMsg($message)
	{
		if(is_array($message)) {
			echo print_r($message)."\n";
		} else {
			echo $message."\n";
		}
	}
}
?>