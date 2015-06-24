<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class FinalFrameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Bowling\Score\FinalFrame::addThrowResult
     */
    public function testCanAddThrowResult()
    {
        $frame = $this->getFrameSubject();
        $frame->addThrowResult(5);
    }

    /**
     * @covers \Bowling\Score\FinalFrame::isFinished
     */
    public function testFrameStartsUnfinished()
    {
        $frame = $this->getFrameSubject();
        $this->assertFalse($frame->isFinished());
    }

    /**
     * @covers \Bowling\Score\FinalFrame::isFinished
     * @depends testFrameStartsUnfinished
     */
    public function testFrameThrowsFinishFrame()
    {
        $frame = $this->getFrameSubject();
        $throws = array_fill(0, Frame::MAX_THROWS_PER_FRAME, 0);
        foreach ($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        $this->assertTrue($frame->isFinished());
    }

    /**
     * @covers \Bowling\Score\FinalFrame::isFinished
     * @depends testFrameThrowsFinishFrame
     */
    public function testSpareAddsThrowToFrame()
    {
        $frame = $this->getFrameSubject();
        $throws = [0, Frame::PINS_ON_LANE];
        foreach ($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        $this->assertFalse($frame->isFinished());
    }

    /**
     * @covers \Bowling\Score\FinalFrame::isFinished
     * @depends testFrameThrowsFinishFrame
     */
    public function testStrikeAddsThrowToFrame()
    {
        $frame = $this->getFrameSubject();
        $throws = [Frame::PINS_ON_LANE, 0];
        foreach ($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        $this->assertFalse($frame->isFinished());
    }

    /**
     * @covers \Bowling\Score\FinalFrame::isFinished
     * @depends testStrikeAddsThrowToFrame
     * @depends testSpareAddsThrowToFrame
     */
    public function testMaxThrowsFinishFrame()
    {
        $frame = $this->getFrameSubject();
        $throws = array_fill(0, FinalFrame::MAX_THROWS_PER_FRAME, Frame::PINS_ON_LANE);
        foreach ($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        $this->assertTrue($frame->isFinished());
    }

    /**
     * @return array
     */
    public function dpUnfinishedFrameThrows()
    {
        return [
            [[0]],
            [[0, Frame::PINS_ON_LANE]],
            [[Frame::PINS_ON_LANE, 0]],
            [[Frame::PINS_ON_LANE-1]]
        ];
    }

    /**
     * @param int[] $throws
     * @dataProvider dpUnfinishedFrameThrows
     * @covers \Bowling\Score\FinalFrame::getScore
     * @depends testSpareAddsThrowToFrame
     * @depends testStrikeAddsThrowToFrame
     * @depends testFrameStartsUnfinished
     */
    public function testScoreReturnsNullWhenUnfinishedFrame($throws)
    {
        $frame = $this->getFrameSubject();
        foreach ($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        $this->assertNull($frame->getScore());
    }

    /**
     * @return array
     */
    public function dpFinishingThrows()
    {
        return [
            [[0, 0]],
            [[1, 1]],
            [[0, Frame::PINS_ON_LANE, 0]],
            [[0, Frame::PINS_ON_LANE, Frame::PINS_ON_LANE]],
            [[Frame::PINS_ON_LANE, Frame::PINS_ON_LANE, Frame::PINS_ON_LANE]]
        ];
    }

    /**
     * @param int[] $throws
     * @dataProvider dpFinishingThrows
     * @covers \Bowling\Score\FinalFrame::getScore
     * @depends testScoreReturnsNullWhenUnfinishedFrame
     */
    public function testFrameScoreAfterFinish($throws)
    {
        $frame = $this->getFrameSubject();
        foreach ($throws as $throw) {
            $frame->addThrowResult($throw);
        }
        $this->assertSame(array_sum($throws), $frame->getScore());
    }

    /**
     * @return FinalFrame
     */
    private function getFrameSubject()
    {
        $frame = new FinalFrame();
        return $frame;
    }

}