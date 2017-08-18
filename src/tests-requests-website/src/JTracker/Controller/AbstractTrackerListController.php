<?php
/**
 * Part of the Joomla Tracker Controller Package
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace JTracker\Controller;

use App\Tracker\Model\IssuesModel;

use Joomla\Uri\Uri;

use JTracker\Application;
use JTracker\Pagination\TrackerPagination;

/**
 * Abstract Controller class for the Tracker Application
 *
 * @since __DEPLOY_VERSION__
 */
abstract class AbstractTrackerListController extends AbstractTrackerController
{
    /**
    * Pagination object
    *
    * @var    TrackerPagination
    * @since __DEPLOY_VERSION__
    */
    protected $pagination;

    /**
    * Model object
    *
    * @var    IssuesModel
    * @since __DEPLOY_VERSION__
    */
    protected $model;

    /**
    * Initialize the controller.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function initialize()
	{
		parent::initialize();

		/* @type Application $application */
		$application = $this->getContainer()->get('app');

		$limit = $application->getUserStateFromRequest('list.limit', 'list_limit', 20, 'int');
		$page  = $application->input->getInt('page');

		$value = $page ? ($page - 1) * $limit : 0;
		$limitStart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);

		$state = $this->model->getState();

		$state->set('list.start', $limitStart);
		$state->set('list.limit', $limit);

		$this->model->setState($state);

		$this->model->setPagination(new TrackerPagination(new Uri($this->getContainer()->get('app')->get('uri.request'))));

		return $this;
	}
}
