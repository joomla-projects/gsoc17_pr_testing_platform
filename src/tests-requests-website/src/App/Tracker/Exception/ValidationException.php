<?php
/**
 * Part of the Joomla Tracker's Tracker Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Tracker;

/**
 * Class ValidationException.
 *
 * @since __DEPLOY_VERSION__
 */
class ValidationException extends \Exception
{
    /**
    * Errors array
    *
    * @var    array|string
    * @since __DEPLOY_VERSION__
    */
    protected $errors = [];

    /**
    * Constructor.
    *
    * @param   array|string  $errors  The errors encountered during validation.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct($errors)
	{
		$this->errors = $errors;

		parent::__construct('Validation failure', 3);
	}

    /**
    * Get validation errors.
    *
    * @return  array|string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getErrors()
	{
		return $this->errors;
	}
}
