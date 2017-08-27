<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Get;

use Application\Command\TrackerCommandOption;

use ElKuKu\G11n\Support\ExtensionHelper;

use JTracker\Helper\LanguageHelper;

/**
 * Class for retrieving translations files.
 *
 * @since __DEPLOY_VERSION__
 */
class Languagefiles extends Get
{
    /**
    * Optional single language to process
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    private $language;

    /**
    * Array containing application languages to retrieve translations for
    *
    * @var    array
    * @since __DEPLOY_VERSION__
    */
    private $languages = [];

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Retrieve language files.');

		$this->addOption(
			new TrackerCommandOption(
				'language', '',
				g11n3t('Optionally specify a single language to fetch.')
			)
		)
		->addOption(
			new TrackerCommandOption(
				'provider', '',
				g11n3t('The translation service provider to use.')
			)
		);
	}

    /**
    * Execute the command.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Get Translations'));

		$this->languages = LanguageHelper::getLanguageCodes();
		$this->language  = $this->getOption('language');

		$this->logOut(g11n3t('Start fetching translations.'))
			->setupLanguageProvider()
			->fetchTranslations()
			->out()
			->logOut(g11n3t('Finished.'));
	}

    /**
    * Fetch translations.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    private function fetchTranslations()
	{
		LanguageHelper::addDomainPaths();

		defined('JDEBUG') || define('JDEBUG', 0);

		// Process CLI files
		$this->receiveFiles('cli', 'CLI');

		// Process core files
		$this->receiveFiles('JTracker', 'Core');

		// Process core JS files
		$this->receiveFiles('JTracker.js', 'CoreJS');

		// Process template files
		$this->receiveFiles('JTracker', 'Template');

		// Process app files
		/* @type \DirectoryIterator $fileInfo */
		foreach (new \DirectoryIterator(JPATH_ROOT . '/src/App') as $fileInfo)
		{
			if ($fileInfo->isDot())
			{
				continue;
			}

			$this->receiveFiles($fileInfo->getFilename(), 'App');
		}

		return $this;
	}

    /**
    * Receives language files from the translation provider
    *
    * @param   string  $extension  The extension to process
    * @param   string  $domain     The domain of the extension
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \Exception
    */
    private function receiveFiles($extension, $domain)
	{
		$this->out(g11n3t('Processing %domain% %extension%... ', ['%domain%' => $domain, '%extension%' => $extension]), false);

		$scopePath     = ExtensionHelper::getDomainPath($domain);
		$extensionPath = ExtensionHelper::getExtensionLanguagePath($extension);

		// Fetch the file for each language and place it in the file tree
		foreach ($this->languages as $language)
		{
			if ('en-GB' == $language)
			{
				continue;
			}

			// Check for a single language and only process that if specified
			if ($this->language && $this->language != $language)
			{
				continue;
			}

			$this->out($language . '... ', false);

			// Write the file
			$path = $scopePath . '/' . $extensionPath . '/' . $language . '/' . $language . '.' . $extension . '.po';

			if (false === is_dir(dirname($path)))
			{
				if (false === mkdir(dirname($path)))
				{
					throw new \Exception('Could not create the directory at: ' . str_replace(JPATH_ROOT, '', dirname($path)));
				}
			}

			switch ($this->languageProvider)
			{
				case 'transifex':
					$translation = $this->transifex->translations->getTranslation(
						$this->getApplication()->get('transifex.project'),
						strtolower(str_replace('.', '-', $extension)) . '-' . strtolower($domain),
						str_replace('-', '_', $language)
					);

					if (!file_put_contents($path, $translation->content))
					{
						throw new \Exception('Could not store language file at: ' . str_replace(JPATH_ROOT, '', $path));
					}

					break;

				case 'crowdin':
					$fileName = $this->getApplication()->get('crowdin.filepath', '')
						. strtolower(str_replace('.', '-', $extension)) . '-' . strtolower($domain) . '_en.po';
					$this->crowdin->file->export($fileName, LanguageHelper::getCrowdinLanguageTag($language), $path);
					break;
			}
		}

		$this->outOK();
	}
}
