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
        for ($i=Frame::MAX_THROWS_PER_FRAME; $i>0; $i--) {
            $frame->addThrowResult(0);
        }
        $this->assertTrue($frame->isFinished());
    }

    /**
     * @depends testFrameIsFinishedAfterMaxThrowsPerFrame
     * @depends testUndefinedScoreReturnsNull
     */
    public function testScoreIsNotNullWhenFinishedWithoutStrikeOrSpare()
    {
        $frame = $this->getFrameSubject();
        for ($i=Frame::MAX_THROWS_PER_FRAME; $i>0; $i--) {
            $frame->addThrowResult(0);
        }
        $this->assertNotNull($frame->isFinished());
    }

    /**
     * @depends testScoreIsNotNullWhenFinishedWithoutStrikeOrSpare
     */
    public function testScoreCountsUsualThrows()
    {
        $frame = $this->getFrameSubject();
        $throws = [2, 3];
        foreach($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        $this->assertSame(array_sum($throws), $frame->getScore());
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
     * @depends testFrameIsNotFinishedAtBegin
     */
    public function testStrikeFinishesTheFrame()
    {
        $frame = $this->getFrameSubject();
        $frame->addThrowResult(Frame::PINS_ON_LANE);
        $this->assertTrue($frame->isFinished());
    }

    /**
     * @depends testUndefinedScoreReturnsNull
     */
    public function testScoreIsNullAfterStrike()
    {
        $frame = $this->getFrameSubject();
        $frame->addThrowResult(Frame::PINS_ON_LANE);
        $this->assertNull($frame->getScore(), 'Unexpected value after first throw.');
        $frame->addThrowResult(Frame::PINS_ON_LANE);
        $this->assertNull($frame->getScore(), 'Unexpected value after second throw.');
    }

    /**
     * @depends testStrikeFinishesTheFrame
     * @depends testScoreIsNullAfterStrike
     */
    public function testStrikeAddsNextTwoThrowsToScore()
    {
        $frame = $this->getFrameSubject();
        $throws = [Frame::PINS_ON_LANE, 3, 5];
        foreach($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        $this->assertSame(array_sum($throws), $frame->getScore());
    }

    /**
     * @dpeends testStrikeFinishesTheFrame
     */
    public function testSpareFinishesTheFrame()
    {
        $frame = $this->getFrameSubject();
        $frame->addThrowResult(0);
        $frame->addThrowResult(Frame::PINS_ON_LANE);
        $this->assertTrue($frame->isFinished());
    }

    /**
     * @depends testUndefinedScoreReturnsNull
     */
    public function testScoreIsNullAfterSpare()
    {
        $frame = $this->getFrameSubject();
        $frame->addThrowResult(0);
        $frame->addThrowResult(Frame::PINS_ON_LANE);
        $this->assertNull($frame->getScore());
    }

    /**
     * @depends testSpareFinishesTheFrame
     * @depends testScoreIsNullAfterSpare
     */
    public function testSpareAddsNextThrowToScore()
    {
        $frame = $this->getFrameSubject();
        $throws = [0, Frame::PINS_ON_LANE, 3];
        foreach($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        $this->assertSame(array_sum($throws), $frame->getScore());
    }

    /**
     * @expectedException \Bowling\Score\UnprocessableThrowException
     * @depends testAddThrowResultIsCallable
     */
    public function testTooManyThrows()
    {
        $frame = $this->getFrameSubject();
        $throws = array_fill(0,Frame::MAX_THROWS_PER_FRAME+1, 0);
        foreach($throws as $throw) {
            $frame->addThrowResult($throw);
        }
    }

    /**
     * @depends testAddThrowResultIsCallable
     */
    public function testGetThrowDefaultNull()
    {
        $frame = $this->getFrameSubject();
        for ($i=0; $i<Frame::MAX_THROWS_PER_FRAME; $i++) {
            $this->assertNull($frame->getThrowResult($i));
        }
    }

    /**
     * @return array
     */
    public function dpThrowCases()
    {
        return [
            [[0, 0]],
            [[Frame::PINS_ON_LANE]],
            [[0, Frame::PINS_ON_LANE]],
            [[5, 5]],
        ];
    }

    /**
     * @depends testGetThrowDefaultNull
     * @param array $throws
     * @dataProvider dpThrowCases
     */
    public function testCanGetAddThrow(array $throws)
    {
        $frame = $this->getFrameSubject();
        foreach ($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        foreach ($throws as $nr => $throw) {
            $this->assertSame($throw, $frame->getThrowResult($nr));
        }
    }

    /**
     * @return array
     */
    public function dpTooHighThrows()
    {
        return [
            [[Frame::PINS_ON_LANE+1, 0]],
            [[Frame::PINS_ON_LANE-1, 2]],
        ];
    }

    /**
     * @param Int[] $throws
     * @dataProvider dpTooHighThrows
     * @expectedException \Bowling\Score\UnprocessableThrowException
     * @depends testAddThrowResultIsCallable
     */
    public function testTooHighThrow($throws)
    {
        $frame = $this->getFrameSubject();
        foreach($throws as $throw) {
            $frame->addThrowResult($throw);
        }
    }

    /**
     * @return array
     */
    public function dpWeirdNonIntThrows()
    {
        return [
            ['A'],
            [null],
            [true],
        ];
    }

    /**
     * @dataProvider dpWeirdNonIntThrows
     * @param Int[] $throws
     * @param Int $expect
     * @expectedException \Bowling\Score\UnprocessableThrowException
     * @depends testAddThrowResultIsCallable
     */
    public function testOnlyIntThrowsAreAccepted($throw)
    {
        $frame = $this->getFrameSubject();
        $frame->addThrowResult($throw);
    }


    /**
     * @return Frame
     */
    private function getFrameSubject()
    {
        return new Frame();
    }
}