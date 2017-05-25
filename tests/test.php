<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\PRTestingPlatform;

use Joomla\PRTestingPlatform\DockerSwarmHandler;

require '../vendor/autoload.php';

$swarm = new DockerSwarmHandler("Test1");

$swarm->generateSwarmInitScript();

$swarm->initSwarm();

$swarm->addWorkerNode();

//$swarm->cleanSwarm();
