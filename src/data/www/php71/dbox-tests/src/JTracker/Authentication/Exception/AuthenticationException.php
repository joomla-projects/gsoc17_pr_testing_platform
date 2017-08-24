<?php
/**
 * Part of the Joomla Tracker Authentication Package
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace JTracker\Authentication\Exception;

use JTracker\Authentication\User;

/**
 * AuthenticationException
 *
 * @since __DEPLOY_VERSION__
 */
class AuthenticationException extends \Exception
{
    /**
    * The user object.
    *
    * @var    User
    * @since __DEPLOY_VERSION__
    */
    protected $user;

    /**
    * The action the user tried to perform.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $action;

    /**
    * Constructor.
    *
    * @param   User    $user    The user object
    * @param   string  $action  The action the user tried to perform.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct(User $user, $action)
	{
		$this->user   = $user;
		$this->action = $action;

		parent::__construct('Authentication failure', 403);
	}

    /**
    * Get the critical action.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getAction()
	{
		return $this->action;
	}

    /**
    * Get the user object.
    *
    * @return  User
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getUser()
	{
		return $this->user;
	}
}
