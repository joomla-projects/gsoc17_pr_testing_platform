<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Test;

use SebastianBergmann\PHPLOC\CLI\Application;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class for running checkstyle tests.
 *
 * @since __DEPLOY_VERSION__
 */
class Copypaste extends Test
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Run Copy/Paste Detector (CPD) for PHP code.');
	}

    /**
    * Execute the command.
    *
    * @return  string  Number of errors.
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Run Copy/Paste Detector'));

		$application = new Application;

		$application->setAutoExit(false);

		$cloneCount = $application->run(
			new ArrayInput(
				['values' => [
					JPATH_ROOT . '/cli',
					JPATH_ROOT . '/src',
				]]
			)
		);

		$this->out(
			$cloneCount
				? sprintf('<error> %d clones found. </error>', $cloneCount)
				: '<ok>No CP errors</ok>'
		);

		if ($this->exit)
		{
			exit($cloneCount ? 1 : 0);
		}

		return $cloneCount;
	}
}
