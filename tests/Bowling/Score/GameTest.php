<?php

namespace Bowling\Score;

class GameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * First dummy test, just to see, if the whole thing is running
     */
    public function testGameClassExists()
    {
        $this->assertTrue(class_exists('Bowling\Score\Game'));
    }
}
