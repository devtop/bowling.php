<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class Game
{
    /** @var FrameCollection */
    private $frames;

    /** @var FrameThrowHandler */
    private $frameThrowHandler;

    const FRAMES = 10;

    /**
     * @param FrameCollection $firstFrames
     */
    public function __construct(FrameCollection $frames, FrameThrowHandler $frameThrowHandler)
    {
        $this->frames = $frames;
        $this->frameThrowHandler = $frameThrowHandler;
    }

    /**
     * @return FrameThrowHandler
     */
    public function getFrameThrowHandler()
    {
        return $this->frameThrowHandler;
    }

    /**
     * @return FrameCollection
     */
    public function getFrames()
    {
        return $this->frames;
    }

    /**
     * @param int $knockedPins
     */
    public function addThrow($knockedPins)
    {
        $this->frameThrowHandler->addThrow($knockedPins);
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
