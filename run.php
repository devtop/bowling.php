<?php
require __DIR__.'/application/bootstrap.php';

//[array_fill(0, 12, $strike), 300],
//[[9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0], 90],
//[array_fill(0, 21, 5), 150]

$renderGame = function (array $throws) {
    $game = \Bowling\Score\GameFactory::create();
    foreach ($throws as $throw) {
        $game->addThrow($throw);
    }
    $render = new \Bowling\Render\GameConsole($game);
    echo $render->render();
};

$renderGame(array_fill(0, 12, 10));
$renderGame([9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0]);
$renderGame(array_fill(0, 21, 5));
$renderGame([9,0, 9,0, 9,0, 9,0, 9,0, 9,0]);
