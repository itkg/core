<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Tests\Core\Command\DatabaseUpdate\Query;

use Itkg\Core\Command\DatabaseUpdate\Query\Formatter;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class QueryFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $formatter = new Formatter();
        $query = <<<EOF
CREATE TABLE MYC_TEST_SCRIPT (
TEST_SCRIPT_ID INT,
TEST_NAME varchar(255)
)
EOF;

        $this->assertEquals('CREATE TABLE MYC_TEST_SCRIPT (TEST_SCRIPT_ID INT,TEST_NAME varchar(255));'.PHP_EOL, $formatter->format($query));
    }
}