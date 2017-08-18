<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Test;

/**
 * Class for running a test suite.
 *
 * @since __DEPLOY_VERSION__
 */
class Run extends Test
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Run all tests');
	}

    /**
    * Execute the command.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Test Suite'));

		$statusCS = (new Checkstyle)
			->setContainer($this->getContainer())
			->setExit(false)
			->execute();

		$statusUT = (new Phpunit)
			->setContainer($this->getContainer())
			->setExit(false)
			->execute();

		/*
	    * @todo language file checks are failing complaining about missing plural forms
	    * See: e.g. https://travis-ci.org/joomla/jissues/jobs/140221763
		$statusLang = (new Langfiles)
			->setContainer($this->getContainer())
			->setExit(false)
			->execute();
		*/

		$status = ($statusCS || $statusUT) ? 1 : 0;

		$this
			->out()
			->out(
				$status
					? '<error>' . g11n3t('Test Suite Finished with errors.') . '</error>'
					: '<ok>' . g11n3t('Test Suite Finished.') . '</ok>'
			);

		exit($status);
	}
}
