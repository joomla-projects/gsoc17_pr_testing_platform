<?php
/**
 * Part of the Joomla Tracker's GitHub Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\GitHub\Controller;

use App\GitHub\View\Stats\StatsHtmlView;
use App\Projects\Model\ProjectModel;

use Joomla\Github\Github;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to display project statistics
 *
 * @since __DEPLOY_VERSION__
 */
class Stats extends AbstractTrackerController
{
    /**
    * View object
    *
    * @var    StatsHtmlView
    * @since __DEPLOY_VERSION__
    */
    protected $view;

    /**
    * Initialize the controller.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function initialize()
	{
		parent::initialize();

		$alias = $this->getContainer()->get('app')->input->get('project_alias');

		if ($alias)
		{
			$project = (new ProjectModel($this->getContainer()->get('db')))
				->getByAlias($alias);
		}
		else
		{
			$project = $this->getContainer()->get('app')->getProject();
		}

		$data = (new Github)->repositories->statistics->getListContributors(
			$project->gh_user, $project->gh_project
		);

		$data = array_reverse($data);

		$this->view->setProject($project);
		$this->view->setContributors($data);

		return $this;
	}
}
