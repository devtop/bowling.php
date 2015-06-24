<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class FrameThrowHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Bowling\Score\FrameThrowHandler::addThrow
     */
    public function testCanAddThrow()
    {
        $handler = $this->getHandlerSubject();
        $handler->addThrow(5);
    }

    /**
     * @covers \Bowling\Score\FrameThrowHandler::getActiveFrameNumber
     * @depends testCanAddThrow
     */
    public function testActiveFrameStartsByOne()
    {
        $handler = $this->getHandlerSubject();
        $this->assertSame(1, $handler->getActiveFrameNumber());
    }

    /**
     * @covers \Bowling\Score\FrameThrowHandler::getActiveFrameNumber
     * @depends testActiveFrameStartsByOne
     */
    public function testActiveFrameNumberIncreasesAfterThrows()
    {
        $throws = [0,1];
        $handler = $this->getHandlerSubject();
        foreach ($throws as $throw) {
            $handler->addThrow($throw);
        }
        $this->assertSame(2, $handler->getActiveFrameNumber());
    }

    /**
     * @return array
     */
    public function dpThrowsAndFrameExpectation()
    {
        return [
            [[1, 3, 4 ], 2],
            [[1, 3, 4, 5 ], 3],
            [[1, 3, 4, 2, 0 ], 3],
            [[1, 3, 4, 1, 8, 0 ], 4],
            [[1, 3, 4, 1, 8, 0, 9 ], 4],
            [[1, 3, 4, 2, 6, 0, 9, 0 ], 5],
            [[1, 3, 4, 2, 6, 0, 9, 0, 8], 5],
            [[1, 3, 4, 2, 6, 0, 9, 0, 8, 1], 6],
            [[1, 3, 4, 2, 6, 0, 9, 0, 8, 1, 5], 6],
            [[1, 3, 4, 2, 6, 0, 9, 0, 8, 1, 4, 1], 7],
            [[1, 3, 4, 2, 6, 0, 9, 0, 8, 1, 6, 2, 5], 7],
        ];
    }

    /**
     * @param int[] $throws
     * @param int $expectedActiveFrameNumber
     * @dataProvider dpThrowsAndFrameExpectation
     * @depends testActiveFrameNumberIncreasesAfterThrows
     * @covers \Bowling\Score\FrameThrowHandler::getActiveFrameNumber
     * @covers \Bowling\Score\FrameThrowHandler::addThrow
     */
    public function testActiveFrameNumberStepsThroughFrames($throws, $expectedActiveFrameNumber)
    {
        $handler = $this->getHandlerSubject();
        foreach ($throws as $throw) {
            $handler->addThrow($throw);
        }
        $this->assertSame($expectedActiveFrameNumber, $handler->getActiveFrameNumber());
    }

    /**
     * @depends testActiveFrameNumberStepsThroughFrames
     * @covers \Bowling\Score\FrameThrowHandler::getActiveFrameNumber
     * @covers \Bowling\Score\FrameThrowHandler::addThrow
     */
    public function testHandlerGivesLaterThrowToStrikeFrame()
    {
        $frameCollection = $this->getFrameCollection();

        $frameMock = $this->getMockBuilder('\Bowling\Score\Frame')
                        ->setMethods(array('addThrowResult', 'isFinished', 'getScore'))
                        ->getMock();
        $frameMock->expects($this->exactly(2))
            ->method('addThrowResult');
        $frameMock->method('isFinished')->willReturn(true);
        $frameMock->method('getScore')->willReturn(null);
        $frameCollection->setFrame($frameMock, 1);

        $handler = new FrameThrowHandler($frameCollection);

        $handler->addThrow(Frame::PINS_ON_LANE);
        $handler->addThrow(0);
    }

    /**
     * @return FrameThrowHandler
     */
    private function getHandlerSubject()
    {
        $frames = $this->getFrameCollection();
        return new FrameThrowHandler($frames);
    }

    /**
     * @return FrameCollection
     */
    private function getFrameCollection()
    {
        $frames = new FrameCollection();
        for ($i = 1; $i <= Game::FRAMES; $i++) {
            $frames->setFrame(new Frame(), $i);
        }
        return $frames;
    }
}