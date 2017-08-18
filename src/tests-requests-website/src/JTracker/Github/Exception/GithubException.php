<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace JTracker\Github\Exception;

use Joomla\Http\Exception\UnexpectedResponseException;
use Joomla\Http\Response;

/**
 * Class GithubException
 *
 * @since __DEPLOY_VERSION__
 */
class GithubException extends UnexpectedResponseException
{
    /**
    * Constructor.
    *
    * @param   Response  $response  The response object.
    *
    * @since __DEPLOY_VERSION__
    */
    public function __construct(Response $response)
	{
		$error = isset($response->body) ? json_decode($response->body) : null;
		$code  = isset($response->code) ? $response->code : 1;

		$message = isset($error->message) ? $error->message : 'Invalid response received from GitHub.';

		parent::__construct($response, $message, $code);
	}
}
