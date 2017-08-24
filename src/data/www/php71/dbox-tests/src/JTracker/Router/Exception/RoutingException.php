<?php
/**
 * Part of the Joomla Tracker Router Package
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace JTracker\Router\Exception;

/**
 * RoutingException
 *
 * @since __DEPLOY_VERSION__
 */
class RoutingException extends \Exception
{
    /**
    * The raw route.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $rawRoute = '';

    /**
    * Constructor.
    *
    * @param   string      $rawRoute  The raw route.
    * @param   \Exception  $previous  The previous exception used for the exception chaining.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct($rawRoute, \Exception $previous = null)
	{
		$this->rawRoute = $rawRoute;

		parent::__construct('Bad Route', 404, $previous);
	}

    /**
    * Get the raw route.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getRawRoute()
	{
		return $this->rawRoute;
	}
}
