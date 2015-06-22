<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class FrameTest extends \PHPUnit_Framework_TestCase
{
    public function testAddThrowResultIsCallable()
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

    /**
     * @depends testPinsLeftArePinsOnLaneWithoutAnyThrow
     * @depends testAddThrowResultIsCallable
     */
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

    /**
     * @depends testFirstThrowIsActiveWhenNothingDoneYet
     */
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

    /**
     * @depends testFrameIsNotFinishedAtBegin
     */
    public function testFrameIsFinishedAfterMaxThrowsPerFrame()
    {
        $frame = $this->getFrameSubject();
        for ($i=Frame::MAX_THROWS_PER_FRAME; $i>=0; $i--) {
            $frame->addThrowResult(0);
        }
        $this->assertTrue($frame->isFinished());
    }

    /**
     * @depends testFrameIsFinishedAfterMaxThrowsPerFrame
     */
    public function testFrameIsFinishedAfterStrike()
    {
        $frame = $this->getFrameSubject();
        $frame->addThrowResult(Frame::PINS_ON_LANE);
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