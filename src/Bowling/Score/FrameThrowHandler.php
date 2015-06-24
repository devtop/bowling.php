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
        $this->giveThrowToListenFrames($knockedPins);
        $this->increaseActiveFrame();

        $this->updateThrowListeningFrames();
    }

    /**
     * @return int
     */
    public function getActiveFrameNumber()
    {
        return $this->activeFrameNumber;
    }

    /**
     * @param $knockedPins
     * @return Frame
     */
    private function giveThrowToListenFrames($knockedPins)
    {
        /* @var Frame $frame */
        foreach ($this->throwListenFrames as $frame) {
            $frame->addThrowResult($knockedPins);
        }
        return $frame;
    }

    private function increaseActiveFrame()
    {
        $activeFrame = $this->frames->getframe($this->activeFrameNumber);
        if ($activeFrame->isFinished()) {
            $this->activeFrameNumber++;
        }
    }

    private function updateThrowListeningFrames()
    {
        $newThrowListeners = [];
        for ($i = 1; $i <= $this->activeFrameNumber; $i++) {
            /* @var Frame $frame */
            $frame = $this->frames->getframe($i);
            if ($frame->getScore() === null) {
                $newThrowListeners[] = $frame;
            }
        }
        $this->throwListenFrames = $newThrowListeners;
    }
}