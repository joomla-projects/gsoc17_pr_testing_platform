<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Get\Project;

use App\Projects\Table\MilestonesTable;

use Application\Command\Get\Project;

use Joomla\Date\Date;

/**
 * Class for retrieving milestones from GitHub for selected projects.
 *
 * @since __DEPLOY_VERSION__
 */
class Milestones extends Project
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Retrieve project milestones from GitHub.');
	}

    /**
    * Execute the command.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Retrieve Milestones'));

		$this->logOut(g11n3t('Start retrieving Milestones'))
			->selectProject()
			->setupGitHub()
			->processMilestones()
			->out()
			->logOut(g11n3t('Finished.'));
	}

    /**
    * Get the project's milestones.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function processMilestones()
	{
		$this->out(g11n3t('Fetching milestones...'), false);

		/* @type \Joomla\Database\DatabaseDriver $db */
		$db = $this->getContainer()->get('db');

		$table = new MilestonesTable($db);

		$milestones = array_merge(
			$this->github->issues->milestones->getList(
				$this->project->gh_user, $this->project->gh_project, 'open'
			),
			$this->github->issues->milestones->getList(
				$this->project->gh_user, $this->project->gh_project, 'closed'
			)
		);

		$titles = [];

		$cntUpdated = 0;
		$cntNew = 0;

		foreach ($milestones as $milestone)
		{
			try
			{
				$table->milestone_id = null;

				// Check if the milestone exists
				$table->load(
					[
						'project_id' => $this->project->project_id,
						'milestone_number' => $milestone->number,
					]
				);

				// Values that may have changed
				$table->title = $milestone->title;
				$table->description = $milestone->description;
				$table->state = $milestone->state;
				$table->due_on = $milestone->due_on ? (new Date($milestone->due_on))->format('Y-m-d H:i:s') : null;

				$table->store(true);

				++ $cntUpdated;
			}
			catch (\RuntimeException $e)
			{
				// New milestone
				$table->milestone_number = $milestone->number;
				$table->project_id = $this->project->project_id;
				$table->title = $milestone->title;
				$table->description = $milestone->description;
				$table->state = $milestone->state;
				$table->due_on = $milestone->due_on ? (new Date($milestone->due_on))->format('Y-m-d H:i:s') : null;

				$table->store(true);

				++ $cntNew;
			}

			$titles[] = $milestone->title;
		}

		// Check for deleted milestones
		$ids = $db->setQuery(
			$db->getQuery(true)
				->from($db->quoteName($table->getTableName()))
				->select('milestone_id')
				->where($db->quoteName('project_id') . ' = ' . $this->project->project_id)
				->where($db->quoteName('title') . ' NOT IN (\'' . implode("', '", $titles) . '\')')
		)->loadRowList();

		if ($ids)
		{
			// Kill the orphans
			$db->setQuery(
				$db->getQuery(true)
					->delete($db->quoteName($table->getTableName()))
					->where($db->quoteName('milestone_id') . ' IN (' . implode(', ', $ids[0]) . ')')
			)->execute();
		}

		$cntDeleted = count($ids);

		return $this->out('ok')
			->logOut(
				sprintf(
					g11n3t('Milestones: %1$d new, %2$d updated, %3$d deleted.'),
					$cntNew, $cntUpdated, $cntDeleted
				)
			);
	}
}
