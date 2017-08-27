<?php
/**
 * Part of the Joomla Tracker Controller Package
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace JTracker\Controller;

/**
 * AJAX response object
 *
 * @since __DEPLOY_VERSION__
 */
class AjaxResponse
{
    /**
    * Data object.
    *
    * @var    \stdClass
    * @since __DEPLOY_VERSION__
    */
    public $data;

    /**
    * Error message.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    public $error = '';

    /**
    * Message string.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    public $message = '';

    /**
    * Constructor
    *
    * @since __DEPLOY_VERSION__
    */
    public function __construct()
	{
		$this->data = new \stdClass;
	}
}
