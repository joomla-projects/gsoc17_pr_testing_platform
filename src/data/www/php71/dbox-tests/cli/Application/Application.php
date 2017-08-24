<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application;

use Application\Command\Help\Help;
use App\Projects\TrackerProject;

use Application\Command\TrackerCommand;
use Application\Command\TrackerCommandOption;
use Application\Exception\AbortException;

use Elkuku\Console\Helper\ConsoleProgressBar;

use ElKuKu\G11n\G11n;
use ElKuKu\G11n\Support\ExtensionHelper;

use Joomla\Application\AbstractCliApplication;
use Joomla\Application\Cli\CliOutput;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Event\DispatcherAwareInterface;
use Joomla\Event\DispatcherAwareTrait;
use Joomla\Input;
use Joomla\Registry\Registry;

use JTracker\Authentication\GitHub\GitHubUser;
use JTracker\Helper\LanguageHelper;

/**
 * CLI application for installing the tracker application
 *
 * @since __DEPLOY_VERSION__
 */
class Application extends AbstractCliApplication implements ContainerAwareInterface, DispatcherAwareInterface
{
    use ContainerAwareTrait, DispatcherAwareTrait;

    /**
    * Quiet mode - no output.
    *
    * @var   boolean
    * @since __DEPLOY_VERSION__
    */
    private $quiet = false;

    /**
    * Verbose mode - debug output.
    *
    * @var   boolean
    * @since __DEPLOY_VERSION__
    */
    private $verbose = false;

    /**
    * Use the progress bar.
    *
    * @var   boolean
    * @since __DEPLOY_VERSION__
    */
    protected $usePBar;

    /**
    * Progress bar format.
    *
    * @var   string
    * @since __DEPLOY_VERSION__
    */
    protected $pBarFormat = '[%bar%] %fraction% %elapsed% ETA: %estimate%';

    /**
    * Array of TrackerCommandOption objects
    *
    * @var   TrackerCommandOption[]
    * @since __DEPLOY_VERSION__
    */
    protected $commandOptions = [];

    /**
    * The application input object.
    *
    * @var   \JTracker\Input\Cli
    * @since __DEPLOY_VERSION__
    */
    public $input;

    /**
    * Class constructor.
    *
    * @param   Input\Cli  $input   An optional argument to provide dependency injection for the application's
    *                              input object.  If the argument is a InputCli object that object will become
    *                              the application's input object, otherwise a default input object is created.
    * @param   Registry   $config  An optional argument to provide dependency injection for the application's
    *                              config object.  If the argument is a Registry object that object will become
    *                              the application's config object, otherwise a default config object is created.
    * @param   CliOutput  $output  The output handler.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct(Input\Cli $input = null, Registry $config = null, CliOutput $output = null)
	{
		parent::__construct($input, $config, $output);

		$this->loadLanguage();

		$this->commandOptions[] = new TrackerCommandOption(
			'quiet', 'q',
			g11n3t('Be quiet - suppress output.')
		);

		$this->commandOptions[] = new TrackerCommandOption(
			'verbose', 'v',
			g11n3t('Verbose output for debugging purpose.')
		);

		$this->commandOptions[] = new TrackerCommandOption(
			'nocolors', '',
			g11n3t('Suppress ANSI colours on unsupported terminals.')
		);

		$this->commandOptions[] = new TrackerCommandOption(
			'log', '',
			g11n3t('Optionally log output to the specified log file.')
		);

		$this->commandOptions[] = new TrackerCommandOption(
			'lang', '',
			g11n3t('Set the language used by the application.')
		);

		$this->usePBar = $this->get('cli-application.progress-bar');

		if ($this->input->get('noprogress'))
		{
			$this->usePBar = false;
		}
	}

    /**
    * Method to run the application routines.  Most likely you will want to instantiate a controller
    * and execute it, or perform some sort of task directly.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    protected function doExecute()
	{
		$this->quiet   = $this->input->get('quiet', $this->input->get('q'));
		$this->verbose = $this->input->get('verbose', $this->input->get('v'));

		$composerCfg = json_decode(file_get_contents(JPATH_ROOT . '/composer.json'));

		$this->outputTitle(g11n3t('Joomla! Tracker CLI Application'), $composerCfg->version);

		$args = $this->input->args;

		if (!$args || (isset($args[0]) && 'help' == $args[0]))
		{
			$command = 'help';
			$action  = 'help';
		}
		else
		{
			$command = $args[0];

			$action = (isset($args[1])) ? $args[1] : $command;
		}

		$className = 'Application\\Command\\' . ucfirst($command) . '\\' . ucfirst($action);

		if (false === class_exists($className))
		{
			$this->out()
				->out(sprintf(g11n3t('Invalid command: %s'), '<error> ' . (($command == $action) ? $command : $command . ' ' . $action) . ' </error>'))
				->out();

			$alternatives = $this->getAlternatives($command, $action);

			if (count($alternatives))
			{
				$this->out('<b>' . g11n3t('Did you mean one of this?') . '</b>')
					->out('    <question> ' . implode(' </question>    <question> ', $alternatives) . ' </question>');

				return;
			}

			$className = 'Application\\Command\\Help\\Help';
		}

		if (false === method_exists($className, 'execute'))
		{
			throw new \RuntimeException(sprintf('Missing method %1$s::%2$s', $className, 'execute'));
		}

		try
		{
			/* @type TrackerCommand $command */
			$command = new $className;

			if ($command instanceof ContainerAwareInterface)
			{
				$command->setContainer($this->container);
			}

			$this->checkCommandOptions($command);

			$command->execute();
		}
		catch (AbortException $e)
		{
			$this->out('')
				->out('<comment>' . g11n3t('Process aborted.') . '</comment>');
		}

		$this->out()
			->out(str_repeat('_', 40))
			->out(
				sprintf(
					g11n3t('Execution time: <b>%d sec.</b>'),
					time() - $this->get('execution.timestamp')
				)
			)
			->out(str_repeat('_', 40));
	}

    /**
    * Get alternatives for a not found command or action.
    *
    * @param   string  $command  The command.
    * @param   string  $action   The action.
    *
    * @return  array
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function getAlternatives($command, $action)
	{
		$commands = (new Help)->setContainer($this->getContainer())->getCommands();

		$alternatives = [];

		if (false === array_key_exists($command, $commands))
		{
			// Unknown command
			foreach (array_keys($commands) as $cmd)
			{
				if (levenshtein($cmd, $command) <= strlen($cmd) / 3 || false !== strpos($cmd, $command))
				{
					$alternatives[] = $cmd;
				}
			}
		}
		else
		{
			// Known command - unknown action
			$actions = (new Help)->setContainer($this->getContainer())->getActions($command);

			foreach (array_keys($actions) as $act)
			{
				if (levenshtein($act, $action) <= strlen($act) / 3 || false !== strpos($act, $action))
				{
					$alternatives[] = $command . ' ' . $act;
				}
			}
		}

		return $alternatives;
	}

    /**
    * Write a string to standard output.
    *
    * @param   string   $text     The text to display.
    * @param   boolean  $newline  True (default) to append a new line at the end of the output string.
    *
    * @return  $this
    *
    * @codeCoverageIgnore
    * @since   __DEPLOY_VERSION__
    */
    public function out($text = '', $newline = true)
	{
		return ($this->quiet) ? $this : parent::out($text, $newline);
	}

    /**
    * Write a string to standard output in "verbose" mode.
    *
    * @param   string  $text  The text to display.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function debugOut($text)
	{
		return ($this->verbose) ? $this->out('DEBUG ' . $text) : $this;
	}

    /**
    * Output a nicely formatted title for the application.
    *
    * @param   string   $title     The title to display.
    * @param   string   $subTitle  A subtitle.
    * @param   integer  $width     Total width in chars.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function outputTitle($title, $subTitle = '', $width = 60)
	{
		$this->out(str_repeat('-', $width));

		$this->out(str_repeat(' ', $width / 2 - (strlen($title) / 2)) . '<title>' . $title . '</title>');

		if ($subTitle)
		{
			$this->out(str_repeat(' ', $width / 2 - (strlen($subTitle) / 2)) . '<b>' . $subTitle . '</b>');
		}

		$this->out(str_repeat('-', $width));

		return $this;
	}

    /**
    * Get the command options.
    *
    * @return  array
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getCommandOptions()
	{
		return $this->commandOptions;
	}

    /**
    * Check if command options conflict with application options.
    *
    * @param   TrackerCommand  $command  The command.
    *
    * @return $this
    */
    private function checkCommandOptions(TrackerCommand $command)
	{
		// This error should only happen during development so the message might not be translated.
		$message = 'The command "%s" option "%s" already defined in the application.';

		// Check command options against application options.
		foreach ($command->getOptions() as $option)
		{
			foreach ($this->commandOptions as $commandOption)
			{
				if ($commandOption->longArg == $option->longArg)
				{
					throw new \UnexpectedValueException(sprintf($message, get_class($command), $option->longArg));
				}

				if ($commandOption->shortArg && $commandOption->shortArg == $option->shortArg)
				{
					throw new \UnexpectedValueException(sprintf($message, get_class($command), $option->shortArg));
				}
			}
		}

		// Check for unknown arguments from user input.
		$allOptions = array_merge($command->getOptions(), $this->commandOptions);

		foreach ($this->input->getArguments() as $argument)
		{
			foreach ($allOptions as $option)
			{
				if ($option->longArg == $argument || $option->shortArg == $argument)
				{
					continue 2;
				}
			}

			throw new \UnexpectedValueException(sprintf(g11n3t('The argument "%s" is not recognized.'), $argument));
		}

		return $this;
	}

    /**
    * Get a user object.
    *
    * Some methods check for an authenticated user...
    *
    * @return  GitHubUser
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getUser()
	{
		// Urgh..
		$user = new GitHubUser(
			new TrackerProject($this->container->get('db')),
			$this->container->get('db')
		);
		$user->isAdmin = true;

		return $user;
	}

    /**
    * Display the GitHub rate limit.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function displayGitHubRateLimit()
	{
		$this->out()
			->out('<info>' . g11n3t('GitHub rate limit:...') . '</info> ', false);

		$rate = $this->container->get('gitHub')->authorization->getRateLimit()->resources->core;

		$this->out(sprintf('%1$d (remaining: <b>%2$d</b>)', $rate->limit, $rate->remaining))
			->out();

		return $this;
	}

    /**
    * Get a progress bar object.
    *
    * @param   integer  $targetNum  The target number.
    *
    * @return  ConsoleProgressBar
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getProgressBar($targetNum)
	{
		return ($this->usePBar)
			? new ConsoleProgressBar($this->pBarFormat, '=>', ' ', 60, $targetNum)
			: null;
	}

    /**
    * This is a useless legacy function.
    *
    * Actually it's accessed by the \JTracker\Model\AbstractTrackerListModel
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    * @todo    Remove
    */
    public function getUserStateFromRequest()
	{
		return '';
	}

    /**
    * Load a foreign language.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function loadLanguage()
	{
		$languages = LanguageHelper::getLanguageCodes();

		// Get the language tag from user input.
		$lang = $this->input->get('lang');

		if ($lang)
		{
			if (false === in_array($lang, $languages))
			{
				// Unknown language from user input - fall back to default
				$lang = G11n::getDefault();
			}

			if (false === in_array($lang, $languages))
			{
				// Unknown default language - Fall back to British.
				$lang = 'en-GB';
			}
		}
		else
		{
			$lang = G11n::getCurrent();

			if (false === in_array($lang, $languages))
			{
				// Unknown current language - Fall back to British.
				$lang = 'en-GB';
			}
		}

		if ($lang)
		{
			// Set the current language if anything has been found.
			G11n::setCurrent($lang);
		}

		// Set language debugging.
		G11n::setDebug($this->get('debug.language'));

		// Set the language cache directory.
		if ('vagrant' == getenv('JTRACKER_ENVIRONMENT'))
		{
			ExtensionHelper::setCacheDir('/tmp');
		}
		else
		{
			ExtensionHelper::setCacheDir(JPATH_ROOT . '/cache');
		}

		// Load the CLI language file.
		ExtensionHelper::addDomainPath('CLI', JPATH_ROOT);
		G11n::loadLanguage('cli', 'CLI');

		return $this;
	}
}
