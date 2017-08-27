<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Users\Controller\User;

use App\Users\Model\UserModel;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to save an item via the users component.
 *
 * @since __DEPLOY_VERSION__
 */
class Save extends AbstractTrackerController
{
    /**
    * Execute the controller.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function execute()
	{
		/* @type \JTracker\Application $application */
		$application = $this->getContainer()->get('app');

		$src = $application->input->get('item', [], 'array');

		if (!$src['id'])
		{
			throw new \UnexpectedValueException('No id given', 404);
		}

		if (!$application->getUser()->check('admin'))
		{
			if ($application->getUser()->id != $src['id'])
			{
				$application->enqueueMessage(
					g11n3t('You are not authorised to edit this user.'), 'error'
				);

				$application->redirect(
						$application->get('uri.base.path') . 'user/' . $src['id']
					);
			}
		}

		try
		{
			// Save the record.
			(new UserModel($this->getContainer()->get('db')))->save($src);

			$application->enqueueMessage(
				g11n3t('The changes have been saved.'), 'success'
			);
		}
		catch (\Exception $e)
		{
			$application->enqueueMessage($e->getMessage(), 'error');
		}

		$application->redirect(
			$application->get('uri.base.path') . 'user/' . $src['id'] . '/edit'
		);

		parent::execute();
	}
}
