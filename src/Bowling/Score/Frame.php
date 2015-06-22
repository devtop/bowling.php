<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class Frame
{
    /**
     * @var Int[] $throws collects the knocked pins threw in this frame
     */
    private $throws = [];

    /**
     * @var Int[] $throws collects the knocked pins threw in llater frames
     */
    private $laterThrows = [];

    const PINS_ON_LANE = 10;
    const MAX_THROWS_PER_FRAME = 2;

    /**
     * @param int $hitPins
     */
    public function addThrowResult($knockedPins)
    {
        if (!$this->isFinished()) {
            $this->throws[] = $knockedPins;
        }
        else {
            $this->laterThrows[] = $knockedPins;
        }
    }

    /**
     * @return null
     */
    public function getScore()
    {
        return null;
    }

    /**
     * return Int
     */
    public function getPinsLeft()
    {
        return (self::PINS_ON_LANE - $this->getKnockedPins());
    }

    /**
     * @return int
     */
    public function getActiveThrow()
    {
        return (count($this->throws)+1);
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        if (count($this->throws)===self::MAX_THROWS_PER_FRAME) {
            return true;
        }
        elseif ($this->getPinsLeft()===0) {
            return true;
        }
        return false;
    }

    /**
     * @return number
     */
    private function getKnockedPins()
    {
        return array_sum($this->throws);
    }
}