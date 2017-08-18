<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Test;

use App\Projects\TrackerProject;

use Application\Command\TrackerCommandOption;
use Application\Exception\AbortException;

use Joomla\Github\Github;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * Class for testing web hooks.
 *
 * @since __DEPLOY_VERSION__
 */
class Hook extends Test
{
    /**
    * Hook controller
    *
    * @var    \App\Tracker\Controller\AbstractHookController
    * @since __DEPLOY_VERSION__
    */
    protected $controller;

    /**
    * Joomla! Github object
    *
    * @var    Github
    * @since __DEPLOY_VERSION__
    */
    protected $github;

    /**
    * The project object.
    *
    * @var    TrackerProject
    * @since __DEPLOY_VERSION__
    */
    protected $project;

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Tests web hooks');

		$this->addOption(
			new TrackerCommandOption(
				'project', 'p',
				g11n3t('Process the project with the given ID.')
			)
		);
	}

    /**
    * Execute the command.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Test Hooks'));

		$this->logOut('Start testing hook');

		$this->selectProject()->selectHook();

		$this->getApplication()->input->set('project', $this->project->project_id);

		$this->setupGitHub();

		$this->controller->execute();
	}

    /**
    * Select the hook.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    * @throws  AbortException
    */
    protected function selectHook()
	{
		$paths = (new Filesystem(new Local(JPATH_ROOT . '/src/App/Tracker/Controller/Hooks')))->listContents();
		$hooks = [];

		foreach ($paths as $path)
		{
			if ('file' == $path['type'])
			{
				$hooks[] = str_replace(['Receive', 'Hook'], '', $path['filename']);
			}
		}

		$this->out()
			->out('<b>Available hooks:</b>')
			->out();

		$cnt = 1;

		$checks = [];

		foreach ($hooks as $hook)
		{
			$this->out('  <b>' . $cnt . '</b> ' . $hook);
			$checks[$cnt] = $hook;
			$cnt++;
		}

		$this->out()
			->out('<question>Select a hook:</question> ', false);

		$resp = (int) trim($this->getApplication()->in());

		if (!$resp)
		{
			throw new AbortException('Aborted');
		}

		if (false === array_key_exists($resp, $checks))
		{
			throw new AbortException('Invalid hook');
		}

		$classname = '\\App\\Tracker\\Controller\\Hooks\\Receive' . $checks[$resp] . 'Hook';

		// Initialize the hook controller
		$this->controller = new $classname;
		$this->controller->setContainer($this->getContainer());

		if ($this->project->project_id === '1' && $resp === 3)
		{
			$this->getApplication()->input->post->set('payload', file_get_contents(__DIR__ . '/data/cms-pull.json'));
		}

		$this->controller->initialize();

		return $this;
	}

    /**
    * Select the project.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    * @throws  AbortException
    */
    protected function selectProject()
	{
		/* @type \Joomla\Database\DatabaseDriver $db */
		$db = $this->getContainer()->get('db');

		$projects = $db->setQuery(
			$db->getQuery(true)
				->from($db->quoteName('#__tracker_projects'))
				->select(['project_id', 'title', 'gh_user', 'gh_project'])

		)->loadObjectList();

		$id = (integer) $this->getOption('project');

		if (!$id)
		{
			$this->out()
				->out('<b>Available projects:</b>')
				->out();

			$cnt = 1;

			$checks = [];

			foreach ($projects as $project)
			{
				if ($project->gh_user && $project->gh_project)
				{
					$this->out('  <b>' . $cnt . '</b> (id: ' . $project->project_id . ') ' . $project->title);
					$checks[$cnt] = $project;
					$cnt++;
				}
			}

			$this->out()
				->out('<question>Select a project:</question> ', false);

			$resp = (int) trim($this->getApplication()->in());

			if (!$resp)
			{
				throw new AbortException('Aborted');
			}

			if (false === array_key_exists($resp, $checks))
			{
				throw new AbortException('Invalid project');
			}

			$this->project = $checks[$resp];
		}
		else
		{
			foreach ($projects as $project)
			{
				if ($project->project_id == $id)
				{
					$this->project = $project;

					break;
				}
			}

			if (is_null($this->project))
			{
				throw new AbortException('Invalid project');
			}
		}

		$this->logOut('Processing project: <info>' . $this->project->title . '</info>');

		return $this;
	}

    /**
    * Setup the Github object.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    protected function setupGitHub()
	{
		$this->github = $this->getContainer()->get('gitHub');

		return $this;
	}
}
