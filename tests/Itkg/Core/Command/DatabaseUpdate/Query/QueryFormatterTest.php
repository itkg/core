<?php

namespace Itkg\Core\Command\DatabaseUpdate\Query;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class QueryFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $formatter = new QueryFormatter();
        $query = <<<EOF
CREATE TABLE MYC_TEST_SCRIPT (
TEST_SCRIPT_ID INT,
TEST_NAME varchar(255)
)
EOF;

        $this->assertEquals('CREATE TABLE MYC_TEST_SCRIPT (TEST_SCRIPT_ID INT,TEST_NAME varchar(255));'.PHP_EOL, $formatter->format($query));
    }
} 