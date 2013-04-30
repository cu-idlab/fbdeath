<?php
namespace User;

class Module
{
	public function init($moduleManager)
	{
		$moduleManager->loadModule('ZfcUser');
	}
}