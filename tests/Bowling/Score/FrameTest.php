<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class FrameTest extends \PHPUnit_Framework_TestCase
{
    public function testAddBallIsCallable()
    {
        $frame = $this->getFrameSubject();
        $frame->addThrowResult(5);
    }

    public function testUndefinedScoreReturnsNull()
    {
        $frame = $this->getFrameSubject();
        $this->assertNull($frame->getScore());
    }

    public function testPinsLeftArePinsOnLaneWithoutAnyThrow()
    {
        $frame = $this->getFrameSubject();
        $this->assertSame(Frame::PINS_ON_LANE, $frame->getPinsLeft());
    }

    public function testPinsLeftAreReducedByFirstThrow()
    {
        $knockedPins = 5;
        $frame = $this->getFrameSubject();
        $frame->addThrowResult($knockedPins);
        $this->assertSame(Frame::PINS_ON_LANE-$knockedPins, $frame->getPinsLeft());
    }

    public function testFirstThrowIsActiveWhenNothingDoneYet()
    {
        $frame = $this->getFrameSubject();
        $this->assertSame(1, $frame->getActiveThrow());
    }

    public function testActiveThrowIncreasesAfterThrow()
    {
        $frame = $this->getFrameSubject();
        $frame->addThrowResult(5);
        $this->assertSame(2, $frame->getActiveThrow());
    }

    public function testFrameIsNotFinishedAtBegin()
    {
        $frame = $this->getFrameSubject();
        $this->assertFalse($frame->isFinished());
    }

    public function testFrameIsFinishedAfterMaxThrowsPerFrame()
    {
        $frame = $this->getFrameSubject();
        for ($i=Frame::MAX_THROWS_PER_FRAME; $i>=0; $i--) {
            $frame->addThrowResult(0);
        }
        $this->assertTrue($frame->isFinished());
    }

    /**
     * @return Frame
     */
    private function getFrameSubject()
    {
        return new Frame();
    }
}