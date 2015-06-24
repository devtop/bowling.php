<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class GameFactory
{
    /**
     * @return Game
     */
    static public function create()
    {
        $frameCollection = new FrameCollection();
        self::createFrames($frameCollection);
        self::createFinalFrame($frameCollection);

        $frameThrowHandler = new FrameThrowHandler($frameCollection);
        return new Game($frameCollection, $frameThrowHandler);
    }

    /**
     * @param $frameCollection
     */
    private static function createFrames($frameCollection)
    {
        for ($i = 1; $i < Game::FRAMES; $i++) {
            $frameCollection->setFrame(new Frame(), $i);
        }
    }

    /**
     * @param $frameCollection
     */
    private static function createFinalFrame($frameCollection)
    {
        $frameCollection->setFrame(new FinalFrame(), Game::FRAMES);
    }
}