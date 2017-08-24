<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command;

/**
 * Class TrackerCommandOption.
 *
 * @since __DEPLOY_VERSION__
 */
class TrackerCommandOption
{
    /**
    * Long argument
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    public $longArg = '';

    /**
    * Short argument
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    public $shortArg = '';

    /**
    * Description argument
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    public $description = '';

    /**
    * Constructor.
    *
    * @param   string  $longArg      Long argument.
    * @param   string  $shortArg     Short argument.
    * @param   string  $description  Description
    */
    public function __construct($longArg, $shortArg, $description)
	{
		$this->longArg     = $longArg;
		$this->shortArg    = $shortArg;
		$this->description = $description;
	}
}
