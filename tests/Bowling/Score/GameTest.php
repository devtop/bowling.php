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
     * @covers \Bowling\Score\Game::addThrow
     */
    public function testThrowSomeThrows()
    {
        $game = $this->getGameSubject();
        $game->addThrow(5);
    }

    /**
     * @covers \Bowling\Score\Game::getActiveFrameNumber
     */
    public function testActiveFrameStartsByOne()
    {
        $game = $this->getGameSubject();
        $this->assertSame(1, $game->getActiveFrameNumber());
    }

    /**
     * @covers \Bowling\Score\Game::getActiveFrameNumber
     */
    public function testTwoThrowsIncreasesActiveFrame()
    {
        $game = $this->getGameSubject();
        $game->addThrow(1);
        $game->addThrow(1);
        $this->assertGreaterThan(1, $game->getActiveFrameNumber());
    }

    /**
     * @covers \Bowling\Score\Game::getScoreByFrameNumber
     */
    public function testGetScoreStartsByNull()
    {
        $game = $this->getGameSubject();
        $this->assertNull($game->getScoreByFrameNumber(1));
    }

    /**
     * @depends testTwoThrowsIncreasesActiveFrame
     * @covers \Bowling\Score\Game::getScoreByFrameNumber
     */
    public function testThrowAddsScoreToFirstFrame()
    {
        $game = $this->getGameSubject();
        $throws = [1,1];
        foreach ($throws as $throw) {
           $game->addThrow($throw);
        }
        $this->assertSame(array_sum($throws), $game->getScoreByFrameNumber(1));
    }

    /**
     * @depends testThrowAddsScoreToFirstFrame
     * @covers \Bowling\Score\Game::getScoreByFrameNumber
     */
    public function testThrowAddsScoreToSecondFrameGameScore()
    {
        $game = $this->getGameSubject();
        $throws = [1,1,2,3];
        foreach ($throws as $throw) {
            $game->addThrow($throw);
        }
        $this->assertSame(array_sum($throws), $game->getScoreByFrameNumber(2));
    }

    /**
     * @return array
     */
    public function dpThrowsAndExpectedScores()
    {
        return [
            [[4, 4, 5, 2, 0, 1], 3, 16],
            [[9, 0, 9], 1, 9],
            [[9, 0, 9], 2, null],
        ];
    }

    /**
     * @depends testThrowAddsScoreToSecondFrameGameScore
     * @covers \Bowling\Score\Game::getScoreByFrameNumber
     * @dataProvider dpThrowsAndExpectedScores
     * @param array $throws
     * @param number $frame
     * @param number $expectedScore
     */
    public function testThrowsAreAddedToScoreByFrame(array $throws, $frame, $expectedScore)
    {
        $game = $this->getGameSubject();
        foreach ($throws as $throw) {
            $game->addThrow($throw);
        }
        $this->assertSame($expectedScore, $game->getScoreByFrameNumber($frame));
    }

    /**
     * @depends testThrowsAreAddedToScoreByFrame
     * @covers \Bowling\Score\Game::getScoreByFrameNumber
     */
    public function testFrameScoreIsNullAfterSpare()
    {
        $game = $this->getGameSubject();
        $throws = [0, Frame::PINS_ON_LANE];
        foreach ($throws as $throw) {
            $game->addThrow($throw);
        }
        $this->assertNull($game->getScoreByFrameNumber(1));
    }

    /**
     * @depends testThrowsAreAddedToScoreByFrame
     * @covers \Bowling\Score\Game::getScoreByFrameNumber
     */
    public function testFrameScoreIsNullAfterStrike()
    {
        $game = $this->getGameSubject();
        $game->addThrow(Frame::PINS_ON_LANE);
        $this->assertNull($game->getScoreByFrameNumber(1));
        $game->addThrow(0);
        $this->assertNull($game->getScoreByFrameNumber(1));
    }

    /**
     * @return Game
     */
    private function getGameSubject()
    {
        return GameFactory::create();
    }

}
