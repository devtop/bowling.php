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
        $frames = [];
        for ($i=0;$i<10;$i++) {
            $frames[] = new Frame();
        }
        return new Game($frames);
    }
}