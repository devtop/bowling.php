<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */


namespace Bowling\Score;


class FrameThrowHandler
{
    /** @var FrameCollection */
    private $frames;

    /** @var int */
    private $activeFrameNumber = 1;

    /** @var array */
    private $throwListenFrames = [];

    public function __construct(FrameCollection $frames)
    {
        $this->frames = $frames;
        $this->throwListenFrames[] = $frames->getframe($this->activeFrameNumber);
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
}