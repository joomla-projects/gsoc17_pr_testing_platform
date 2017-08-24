<?php
/**
 * Part of the Joomla Tracker's Text Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Text\Controller\Article;

use App\Text\Table\ArticlesTable;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to delete an article.
 *
 * @since __DEPLOY_VERSION__
 */
class Delete extends AbstractTrackerController
{
    /**
    * The default view for the component
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $defaultView = 'articles';

    /**
    * Execute the controller.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		/* @type \JTracker\Application $application */
		$application = $this->getContainer()->get('app');

		$application->getUser()->authorize('admin');

		(new ArticlesTable($this->getContainer()->get('db')))
			->delete($application->input->getInt('id'));

		$application
			->enqueueMessage(g11n3t('The article has been deleted.'), 'success')
			->redirect($application->get('uri.base.path') . 'text');

		return parent::execute();
	}
}
