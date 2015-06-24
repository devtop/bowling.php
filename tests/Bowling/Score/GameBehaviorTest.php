<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class GameBehaviorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function dpExpectedGames()
    {
        $strike = 10;
        return [
            [array_fill(0, 12, $strike), 300],
            [[9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0, 9,0], 90],
            [array_fill(0, 21, 5), 150]
        ];
    }

    /**
     * @param int[] $throws
     * @param int $result
     * @dataProvider dpExpectedGames
     */
    public function testGames($throws, $expectedResult)
    {
        $game = GameFactory::create();
        foreach ($throws as $throw) {
            $game->addThrow($throw);
        }
        $this->assertSame($expectedResult, $game->getScoreByFrameNumber(10));
    }
}