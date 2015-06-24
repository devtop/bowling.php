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
        for ($i=1;$i<=Game::FRAMES;$i++) {
            $frameCollection->setFrame(new Frame(), $i);
        }
        return new Game($frameCollection);
    }
}