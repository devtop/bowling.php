<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class Game
{
    /**
     * @var FrameCollection
     */
    private $frames;

    /**
     * @var int
     */
    private $activeFrameNumber = 1;

    const FRAMES = 10;

    /**
     * @param FrameCollection $firstFrames
     */
    public function __construct(FrameCollection $frames)
    {
        $this->frames = $frames;
    }

    /**
     * @param int $knockedPins
     */
    public function addThrow($knockedPins)
    {
        $activeFrame = $this->frames->getframe($this->activeFrameNumber);
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
     * @param number $frameNumber
     * @return int|null
     */
    public function getScoreByFrameNumber($frameNumber)
    {
        $score = null;
        for($i=1; $i<=$frameNumber; $i++) {

            $frame = $this->frames->getframe($i);

            // Just one uncountable frame means, no score for requested frame yet
            if ($frame->getScore()===null) {
                return null;
            }

            $score += $frame->getScore();
        }
        return $score;
    }
}
