<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Command\DatabaseUpdate;

/**
 * Class ReleaseChecker
 *
 * Check release health
 *
 * @package Itkg\Core\Command\DatabaseUpdate
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ReleaseChecker
{
    /**
     * Check a release scripts & rollbacks
     *
     * @param array $scripts
     * @param array $rollbacks
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function check(array $scripts, array $rollbacks, $recursiveCheck = false)
    {
        if (empty($scripts)) {
            throw new \RuntimeException(sprintf('No scripts were found in release'));
        }

        if (sizeof(array_diff_key($scripts, $rollbacks)) != 0) {
            throw new \LogicException('Scripts and rollbacks must correspond');
        }

        if ($recursiveCheck) {
            foreach ($scripts as $k => $script) {
                $this->checkScript($script, $rollbacks[$k]);
            }
        }
    }

    /**
     * @param $script
     * @param $rollbackScript
     * @throws \InvalidArgumentException
     */
    public function checkScript($script, $rollbackScript)
    {
        if (!file_exists($script) || !file_exists($rollbackScript)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "%s or %s does not exist",
                    $script,
                    $rollbackScript
                )
            );
        }
    }
} 