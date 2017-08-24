<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Test;

use ElKuKu\G11n\Support\ExtensionHelper;

use JTracker\Helper\LanguageHelper;

use PHP_CodeSniffer_File;

/**
 * Class for checking language files.
 *
 * @since __DEPLOY_VERSION__
 */
class Langfiles extends Test
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Check language files');
	}

    /**
    * Execute the command.
    *
    * @return  integer
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Check language files'));

		LanguageHelper::addDomainPaths();

		$languages = LanguageHelper::getLanguageCodes();

		$languages[] = 'templates';

		$errors = false;

		foreach (LanguageHelper::getScopes() as $domain => $extensions)
		{
			foreach ($extensions as $extension)
			{
				$scopePath     = ExtensionHelper::getDomainPath($domain);
				$extensionPath = ExtensionHelper::getExtensionLanguagePath($extension);

				foreach ($languages as $language)
				{
					$path = $scopePath . '/' . $extensionPath . '/' . $language;

					$path .= ('templates' == $language)
						?  '/' . $extension . '.pot'
						:  '/' . $language . '.' . $extension . '.po';

					$this->debugOut(sprintf('Check: %s-%s %s in %s', $domain, $extension, $language, $path));

					if (false === file_exists($path))
					{
						$this->debugOut('not found');

						continue;
					}

					// Check if the language file has UNIX style line endings.
					if ("\n" != PHP_CodeSniffer_File::detectLineEndings($path))
					{
						$this->out($path)
							->out('<error>' . g11n3t('The file does not have UNIX style line endings!') . '</error>')
							->out();

						continue;
					}

					// Check the language file for errors.
					$output = shell_exec('msgfmt -c ' . $path . ' 2>&1');

					if ($output)
					{
						// If the command produces any output, that means errors.
						$errors = true;
						$this->out($output);
					}
					else
					{
						$this->debugOut('ok');
					}
				}
			}
		}

		$this->out(
			$errors
			? '<error>' . g11n3t('There have been errors.') . '</error>'
			: '<ok>' . g11n3t('Language file syntax OK') . '</ok>'
		);

		if ($this->exit)
		{
			exit($errors ? 1 : 0);
		}

		return $errors ? 1 : 0;
	}
}
