<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class Game
{
    /**
     * @var Frame[]
     */
    private $frames;

    /**
     * @var int
     */
    private $activeFrameNumber = 1;

    const FRAMES = 10;

    /**
     * @param Frame[] $firstFrames
     */
    public function __construct(array $firstFrames)
    {
        $this->frames = $firstFrames;

    }

    /**
     * @param int $knockedPins
     */
    public function addThrow($knockedPins)
    {
        $activeFrame = $this->getFrame($this->activeFrameNumber);
        $activeFrame->addThrowResult($knockedPins);

        if ($activeFrame->isFinished()) {
            $this->activeFrameNumber++;
        }
    }

    /**
     * @return int
     */
    public function getActiveFrameNumber()
    {
        return $this->activeFrameNumber;
    }

    /**
     * @param int $frameNumber
     * @return Frame
     */
    private function getFrame($frameNumber)
    {
        return $this->frames[$frameNumber - 1];
    }
}
