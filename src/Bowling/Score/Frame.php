<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class Frame
{
    private $threwScores = [];

    private $throwCounter = 1;

    const PINS_ON_LANE = 10;
    const MAX_THROWS_PER_FRAME = 2;

    /**
     * @param int $hitPins
     */
    public function addThrowResult($knockedPins)
    {
        $this->threwScores[$this->throwCounter++] = $knockedPins;
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
        $pinsLeft = self::PINS_ON_LANE;

        for ($i=$this->getHighestThrowToCount(); $i>0; $i--) {
            $pinsLeft -= $this->threwScores[$i];
        }

        return $pinsLeft;
    }

    /**
     * @return int
     */
    public function getActiveThrow()
    {
        return $this->throwCounter;
    }

    /**
     * @return int
     */
    private function getHighestThrowToCount()
    {
        return $this->isFinished() ? self::MAX_THROWS_PER_FRAME : $this->throwCounter-1;
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return ($this->throwCounter > self::MAX_THROWS_PER_FRAME);
    }
}