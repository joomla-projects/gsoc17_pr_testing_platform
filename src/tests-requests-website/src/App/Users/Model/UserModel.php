<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Users\Model;

use Joomla\Filter\InputFilter;

use JTracker\Authentication\GitHub\GitHubUser;
use JTracker\Authentication\Database\TableUsers;
use JTracker\Model\AbstractTrackerDatabaseModel;

/**
 * User model class for the Users component.
 *
 * @since __DEPLOY_VERSION__
 */
class UserModel extends AbstractTrackerDatabaseModel
{
    /**
    * Get an item.
    *
    * @param   integer  $itemId  The item id.
    *
    * @return  GitHubUser
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \Exception
    */
    public function getItem($itemId = null)
	{
		try
		{
			$user = new GitHubUser($this->getProject(), $this->db, $itemId);
		}
		catch (\RuntimeException $e)
		{
			// Load a blank user
			$user = new GitHubUser($this->getProject(), $this->db);
		}

		return $user;
	}

    /**
    * Save the item.
    *
    * @param   array  $src  The source.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function save(array $src)
	{
		$data = [
			'id' => (new InputFilter)->clean($src['id'], 'int'),
		];

		if (!$data['id'])
		{
			throw new \UnexpectedValueException('Missing ID');
		}

		$data['params'] = json_encode($src['params']);

		(new TableUsers($this->db))->save($data);

		return $this;
	}
}
