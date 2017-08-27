<?php
/**
 * Inline HTML diff generator for PHP DiffLib.
 *
 * PHP version 5
 *
 * Copyright (c) 2009 Chris Boulton <chris.boulton@interspire.com>
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *  - Neither the name of the Chris Boulton nor the names of its contributors
 *    may be used to endorse or promote products derived from this software
 *    without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright  (c) 2009 Chris Boulton
 * @license    New BSD License http://www.opensource.org/licenses/bsd-license.php
 * @link       http://github.com/chrisboulton/php-diff
 */

namespace App\Tracker\DiffRenderer\Html;

use Adaptive\Diff\Renderer\Html\ArrayRenderer;

/**
 * Class Inline
 *
 * @since __DEPLOY_VERSION__
 */
class Inline extends ArrayRenderer
{
    private $showLineNumbers = true;

    private $showHeader = true;

    /**
    * Render a and return diff with changes between the two sequences displayed inline (under each other).
    *
    * @return string The generated inline diff.
    *
    * @since __DEPLOY_VERSION__
    */
    public function render()
	{
		$changes = parent::render();
		$html = '';

		if (empty($changes))
		{
			return $html;
		}

		$html .= '<table class="Differences DifferencesInline">';

		if ($this->showHeader)
		{
			$html .= '<thead>';
			$html .= '<tr>';

			if ($this->showLineNumbers)
			{
				$html .= '<th>' . g11n3t('Old') . '</th>';
				$html .= '<th>' . g11n3t('New') . '</th>';
			}

			$html .= '<th>' . g11n3t('Differences') . '</th>';
			$html .= '</tr>';
			$html .= '</thead>';
		}

		foreach ($changes as $i => $blocks)
		{
			// If this is a separate block, we're condensing code so output ...,
			// indicating a significant portion of the code has been collapsed as it is the same
			if ($i > 0)
			{
				$html .= '<tbody class="Skipped">';
				$html .= '<th>&hellip;</th>';

				if ($this->showLineNumbers)
				{
					$html .= '<th>&hellip;</th>';
					$html .= '<td>&nbsp;</td>';
				}

				$html .= '</tbody>';
			}

			foreach ($blocks as $change)
			{
				$html .= '<tbody class="Change' . ucfirst($change['tag']) . '">';

				// Equal changes should be shown on both sides of the diff

				if ($change['tag'] == 'equal')
				{
					foreach ($change['base']['lines'] as $no => $line)
					{
						$html .= '<tr>';

						if ($this->showLineNumbers)
						{
							$html .= '<th>' . ($change['base']['offset'] + $no + 1) . '</th>';
							$html .= '<th>' . ($change['changed']['offset'] + $no + 1) . '</th>';
						}

						$html .= '<td class="Left">' . $line . '</td>';
						$html .= '</tr>';
					}
				}
				// Added lines only on the right side
				elseif ($change['tag'] == 'insert')
				{
					foreach ($change['changed']['lines'] as $no => $line)
					{
						$html .= '<tr>';

						if ($this->showLineNumbers)
						{
							$html .= '<th>&nbsp;</th>';
							$html .= '<th>' . ($change['changed']['offset'] + $no + 1) . '</th>';
						}

						$html .= '<td class="Right"><ins>' . $line . '</ins>&nbsp;</td>';
						$html .= '</tr>';
					}
				}
				// Show deleted lines only on the left side
				elseif ($change['tag'] == 'delete')
				{
					foreach ($change['base']['lines'] as $no => $line)
					{
						$html .= '<tr>';

						if ($this->showLineNumbers)
						{
							$html .= '<th>' . ($change['base']['offset'] + $no + 1) . '</th>';
							$html .= '<th>&nbsp;</th>';
						}

						$html .= '<td class="Left"><del>' . $line . '</del>&nbsp;</td>';
						$html .= '</tr>';
					}
				}
				// Show modified lines on both sides
				elseif ($change['tag'] == 'replace')
				{
					foreach ($change['base']['lines'] as $no => $line)
					{
						$html .= '<tr>';

						if ($this->showLineNumbers)
						{
							$html .= '<th>' . ($change['base']['offset'] + $no + 1) . '</th>';
							$html .= '<th>&nbsp;</th>';
						}

						$html .= '<td class="Left"><span>' . $line . '</span></td>';
						$html .= '</tr>';
					}

					foreach ($change['changed']['lines'] as $no => $line)
					{
						$html .= '<tr>';

						if ($this->showLineNumbers)
						{
							$html .= '<th>&nbsp;</th>';
							$html .= '<th>' . ($change['changed']['offset'] + $no + 1) . '</th>';
						}

						$html .= '<td class="Right"><span>' . $line . '</span></td>';
						$html .= '</tr>';
					}
				}

				$html .= '</tbody>';
			}
		}

		$html .= '</table>';

		return $html;
	}

    /**
    * Set showLineNumbers.
    *
    * @param   boolean  $showLineNumbers  Show the line numbers.
    *
    * @return  $this
    *
    * @since __DEPLOY_VERSION__
    */
    public function setShowLineNumbers($showLineNumbers)
	{
		$this->showLineNumbers = (bool) $showLineNumbers;

		return $this;
	}

    /**
    * Set showHeader.
    *
    * @param   boolean  $showHeader  Show the table header.
    *
    * @return  $this
    *
    * @since __DEPLOY_VERSION__
    */
    public function setShowHeader($showHeader)
	{
		$this->showHeader = (bool) $showHeader;

		return $this;
	}
}
