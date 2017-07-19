<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\PRTestingPlatform;

use Joomla\Testing\PRTestingPlatform\IssueComment;
use Joomla\Testing\PRTestingPlatform\JoomlaManualInstaller;

require __DIR__ . '/../vendor/autoload.php';

// Replace 'organization' and 'repository' with valid ones, and place a valid PR id
$issueComment = new IssueComment('organization', 'repository', 7);

// Replace 'username' and 'password' with a valid github username and password
$issueComment->createComment('username', 'password', 'Commenting this to test the github API!!');

