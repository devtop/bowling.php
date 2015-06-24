<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Render;

use Bowling\Score\Game;

class GameConsole
{
    /** @var Game $game */
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function render()
    {
        ob_start();
        include __DIR__.'/view/game.phtml';
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

}