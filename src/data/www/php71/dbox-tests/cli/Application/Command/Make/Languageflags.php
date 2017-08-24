<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Make;

use Application\Command\TrackerCommandOption;

use JTracker\Helper\LanguageHelper;

/**
 * Class for compiling multiple images into a big one (CSS spriting).
 *
 * @since __DEPLOY_VERSION__
 */
class Languageflags extends Make
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->addOption(
			new TrackerCommandOption(
				'imagefile', '',
				g11n3t('Full path to the combined image file.')
			)
		)
			->addOption(
			new TrackerCommandOption(
				'cssfile', '',
				g11n3t('Full path to the CSS file.')
			)
		);

		$this->description = g11n3t('Compile multiple images into a big one (CSS spriting).');
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
		$this->getApplication()->outputTitle(g11n3t('Compile Language flag images'));

		// @test
		$basePath = JPATH_ROOT . '/htdocs/fff/flagimages';

		$resultImageFile = $this->getApplication()->input->getCmd('imagefile', JPATH_ROOT . '/cache/flags.png');
		$resultCssFile = $this->getApplication()->input->getCmd('cssfile', JPATH_ROOT . '/cache/flags.css');

		$flagWidth = 16;
		$flagHeight = 10;
		$imagesPerRow = 15;
		$flags = ['-verbose'];

		$fileNames = [];

		$cssLines = ['.flag {',
			'	width: ' . $flagWidth . 'px;',
			'	height: ' . $flagHeight . 'px;',
			'	background:url(flags.png) no-repeat',
			'}',
			'',
			'.ui.dropdown .menu > .item { padding: 5px 40px 5px 5px !important; }',
			'',
			'.ui.dropdown .menu > .item > img.flag { width:16px; height:10px; }',
			'',
		];

		$colCount = 0;
		$rowCount = 0;

		foreach (LanguageHelper::getLanguageCodes() as $code)
		{
			$fileNames[] = $basePath . '/' . LanguageHelper::getLanguageTagByCode($code) . '.png';

			$xPos = $colCount ? '-' . $colCount * $flagWidth . 'px' : '0';
			$yPos = $rowCount ? '-' . $rowCount * $flagHeight . 'px' : '0';

			$cssLines[] = sprintf('.flag.flag-%s {background-position: %s %s}', $code, $xPos, $yPos);

			$colCount++;

			if ($colCount >= $imagesPerRow)
			{
				$colCount = 0;
				$rowCount++;
			}
		}

		// See: https://www.imagemagick.org/Usage/montage/
		$command = sprintf(
			'montage %s -tile %sx -geometry +0+0 %s %s',
			implode(' ', $fileNames),
			$imagesPerRow,
			implode(' ', $flags),
			$resultImageFile
		);

		$this->out(sprintf(g11n3t('Generating the combined image for %1$d flag images in %2$s'), count($fileNames), $resultImageFile))
			->debugOut($command);

		$this->execCommand($command);

		$this->out()
			->out(sprintf(g11n3t('Writing the CSS file to %s'), $resultCssFile));

		file_put_contents($resultCssFile, implode("\n", $cssLines));

		$this->out()
			->out(g11n3t('Finished.'));
	}
}
