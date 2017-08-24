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
 * Class for running phploc tests.
 *
 * @since __DEPLOY_VERSION__
 */
class Phploc extends Test
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Run Lines Of Code (LOC) for PHP code.');
	}

    /**
    * Execute the command.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Run PHP Lines Of Code'));

		$application = new Application;

		$application->setAutoExit(false);

		$application->run(
			new ArrayInput(
				[
					'values' => [
					JPATH_ROOT . '/cli',
					JPATH_ROOT . '/src',
					],
				]
			)
		);

		$this->out(g11n3t('Finished.'));

		return $this;
	}
}
