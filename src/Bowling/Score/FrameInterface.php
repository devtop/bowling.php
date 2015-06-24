<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

interface FrameInterface
{

    /** @param number $knockedPins */
    public function addThrowResult($knockedPins);

    /**@return number */
    public function getScore();

    /** @return bool */
    public function isFinished();

    /** @param number $number */
    public function getThrowResult($number);

}