<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

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

    /**
     * Just to warm up
     */
    public function testThrowSomeThrows()
    {
        $game = $this->getGameSubject();
        $game->addThrow(5);
    }

    public function testActiveFrameStartsByOne()
    {
        $game = $this->getGameSubject();
        $this->assertSame(1, $game->getActiveFrame());
    }

    /**
     * @return Game
     */
    private function getGameSubject()
    {
        return new Game();
    }

}
