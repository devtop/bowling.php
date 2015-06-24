<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */


namespace Bowling\Score;


use Traversable;

class FrameCollection implements \IteratorAggregate
{

    /**
     * @var Frame[]
     */
    private $frames = [];

    /**
     * @param FrameInterface $frame
     * @param int $id
     */
    public function setFrame(FrameInterface $frame, $id)
    {
        $this->frames[$id] = $frame;
    }

    /**
     * @param int $id
     * @return null|FrameInterface|Frame
     */
    public function getframe($id)
    {
        if (isset($this->frames[$id])) {
            return $this->frames[$id];
        }
        return null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->frames);
    }

}