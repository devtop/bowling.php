<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class FinalFrame implements FrameInterface
{
    /** @var array */
    private $throws = [];

    const MAX_THROWS_PER_FRAME = 3;
    /**
     * @param number $knockedPins
     */
    public function addThrowResult($knockedPins)
    {
        $this->throws[] = $knockedPins;
    }

    /**
     * @return number
     */
    public function getScore()
    {
        if (!$this->isFinished()) {
            return null;
        }

        return array_sum($this->throws);
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        if (count($this->throws)===self::MAX_THROWS_PER_FRAME) {
            return true;
        }
        if ((count($this->throws)==Frame::MAX_THROWS_PER_FRAME)
            && (array_sum($this->throws)<Frame::PINS_ON_LANE)) {
            return true;
        }
        return false;
    }

}