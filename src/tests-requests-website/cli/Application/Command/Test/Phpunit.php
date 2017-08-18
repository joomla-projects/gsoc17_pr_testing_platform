<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Test;

use PHPUnit\TextUI\Command;

/**
 * Class for running PHPUnit tests.
 *
 * @since __DEPLOY_VERSION__
 */
class Phpunit extends Test
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Run PHPUnit tests.');
	}

    /**
    * Execute the command.
    *
    * @return  integer  PHPUnit_TextUI_TestRunner exit status.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Test PHPUnit'));

		$command = new Command;

		$options = [
			'--configuration=' . JPATH_ROOT . '/phpunit.xml',
		];

		$returnVal = $command->run($options, false);

		$this
			->out()
			->out(
			$returnVal
				? '<error>' . g11n3t('Finished with errors.') . '</error>'
				: '<ok>' . g11n3t('Success') . '</ok>'
		);

		if ($this->exit)
		{
			exit($returnVal ? 1 : 0);
		}

		return $returnVal;
	}
}
