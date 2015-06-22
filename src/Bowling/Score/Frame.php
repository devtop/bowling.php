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
     * @param $knockedPins
     * @throws UnprocessableThrowException
     */
    public function addThrowResult($knockedPins)
    {
        if (!$this->throwIsValid($knockedPins))
        {
            throw new UnprocessableThrowException('Too many throws.');
        }

        if (!$this->isFinished()) {
            $this->throws[] = $knockedPins;
        }
        elseif ($this->isSpare() && (count($this->laterThrows)<1)) {
            $this->laterThrows[] = $knockedPins;
        }
        elseif ($this->isStrike() && (count($this->laterThrows)<2)) {
            $this->laterThrows[] = $knockedPins;
        }
        else {
            throw new UnprocessableThrowException('Too many throws.');
        }
    }

    /**
     * @return null|int
     */
    public function getScore()
    {
        if (!$this->isFinished()) {
            return null;
        }
        if ($this->getPinsLeft()>0) {
            return $this->getKnockedPins();
        }
        if ($this->isStrike() && (count($this->laterThrows)<2)) {
            return null;
        }
        if ($this->isStrike() && (count($this->laterThrows)===2)) {
            return (array_sum($this->throws) + array_sum($this->laterThrows));
        }
        if ($this->isSpare() && (count($this->laterThrows)===1)) {
            return (array_sum($this->throws) + $this->laterThrows[0]);
        }
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

    /**
     * @return bool
     */
    private function isStrike()
    {
        return ($this->throws[0] === self::PINS_ON_LANE);
    }

    /**
     * @return bool
     */
    private function isSpare()
    {
        return ($this->getPinsLeft() === 0);
    }

    /**
     * @param $knockedPins
     * @return bool
     */
    private function throwIsValid($knockedPins)
    {
        if (!is_int($knockedPins)) {
            return false;
        }
        if ($knockedPins > self::PINS_ON_LANE) {
            return false;
        }
        if (!$this->isFinished() && ($knockedPins > $this->getPinsLeft())) {
            return false;
        }
        return true;
    }
}