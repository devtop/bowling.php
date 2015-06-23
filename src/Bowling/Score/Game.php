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
     * @param number $frameNumber
     * @return int|null
     */
    public function getScoreByFrameNumber($frameNumber)
    {
        $score = null;
        for ($i=1;$i<=$frameNumber;$i++) {

            $frame = $this->getFrame($i);

            // Just one uncountable frame means, no score for requested frame yet
            if ($frame->getScore()===null) {
                return null;
            }

            $score += $frame->getScore();
        }
        return $score;
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
