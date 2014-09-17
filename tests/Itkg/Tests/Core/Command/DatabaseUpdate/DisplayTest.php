<?php

namespace Itkg\Tests\Core\Command\DatabaseUpdate;


use Itkg\Core\Command\DatabaseUpdate\Display;
use Itkg\Core\Command\DatabaseUpdate\Layout\Parser;
use Itkg\Core\Command\DatabaseUpdate\Query\Formatter;
use Itkg\Core\Command\DatabaseUpdate\Query;
use Itkg\Tests\Core\Command\DatabaseUpdate\Query\Output;

class DisplayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testDisplayWithNoOutput()
    {
        $this->createDisplay()->displayQueries(array());
    }

    public function testCreateStyle()
    {
        $queryCreate = new Query('CREATE TABLE MY_TABLE');

        $queryIns = new Query('INSERT INTO MY_TABLE (FIELD) VALUES (VALUE)');

        $display = $this->createDisplay();
        $display->setOutput(new Output());
        $display->displayQueries(array($queryCreate, $queryIns), true);

        $this->assertTrue($display->getOutput()->getFormatter()->hasStyle('create_table'));
        $this->assertTrue($display->getOutput()->getFormatter()->hasStyle('insert'));

        // If colors option = false, style will not be defined
        $display->displayQueries(array(new Query('UPDATE TABLE MY_TABLE')));
        $this->assertFalse($display->getOutput()->getFormatter()->hasStyle('update'));
    }

    public function testNoColorsAndNoTemplate()
    {
        $display = $this->createDisplay();
        $queryIns = new Query('INSERT INTO MY_TABLE (FIELD) VALUES (VALUE)');
        $queryUpd = new Query('UPDATE TABLE MY_TABLE');

        $display->setOutput(new Output());
        $display->displayQueries(array($queryIns, $queryUpd));

        $this->assertEquals(
            'INSERT INTO MY_TABLE (FIELD) VALUES (VALUE);'.PHP_EOL.'UPDATE TABLE MY_TABLE;'.PHP_EOL,
            $display->getOutput()->output
        );
    }

    public function testTemplate()
    {
        $display = $this->createDisplay();
        $queryIns = new Query('INSERT INTO MY_TABLE (FIELD) VALUES (VALUE)');
        $queryUpd = new Query('UPDATE TABLE MY_TABLE');

        $display->setOutput(new Output());
        $display->getTemplateParser()->setPath(TEST_BASE_DIR.'/data/templates/layout.php');
        $display->displayQueries(array($queryIns, $queryUpd), false, true);
        $result = <<<EOF
This is my template

INSERT INTO MY_TABLE (FIELD) VALUES (VALUE);
UPDATE TABLE MY_TABLE;


Grant query :
EOF;
        $this->assertEquals(
            $result,
            $display->getOutput()->output
        );
    }

    private function createDisplay()
    {
        return new Display(new Parser(), new Formatter());
    }
}
