<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\PRTestingPlatform;

namespace Joomla\PRTestingPlatform\loadTasks;

use Joomla\PRTestingPlatform\DockerfilesGenerator;

require __DIR__ . '/../vendor/autoload.php';

$generator = new DockerfilesGenerator('php.xml');

$generator->generateDockerfiles();

