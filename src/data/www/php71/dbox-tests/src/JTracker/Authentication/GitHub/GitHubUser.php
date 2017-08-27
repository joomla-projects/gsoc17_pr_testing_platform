<?php
/**
 * Part of the Joomla Tracker Authentication Package
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace JTracker\Authentication\GitHub;

use JTracker\Authentication\User;

/**
 * GitHub user class.
 *
 * @since __DEPLOY_VERSION__
 */
class GitHubUser extends User
{
    /**
    * Avatar url.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    public $avatar_url;

    /**
    * Avatar name.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    public $avatar;

    /**
    * Load user data from GitHub.
    *
    * @param   \stdClass  $data  A JSON string from GitHub containing user data.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function loadGitHubData(\stdClass $data)
	{
		if (!isset($data->login))
		{
			throw new \RuntimeException('Missing login');
		}

		foreach ($data as $k => $v)
		{
			if (property_exists($this, $k) && false == in_array($k, ['id']))
			{
				$this->$k = $v;
			}
		}

		$this->username = $data->login;

		return $this;
	}
}
