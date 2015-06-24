<?php
/**
 * Created by Tobias Ranft <coded@ranft.biz> 2015
 */

namespace Bowling\Score;

class FrameCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Bowling\Score\FrameCollection::setFrame
     */
    public function testCanSetFrame()
    {
        $collection = $this->getCollectionSubject();
        $collection->setFrame(new Frame(), 1);
    }

    /**
     * @covers \Bowling\Score\FrameCollection::setFrame
     * @depends testCanSetFrame
     */
    public function testCanGetFrame()
    {
        $collection = $this->getCollectionSubject();
        $frameMock = new Frame();
        $id = 1;
        $collection->setFrame($frameMock, $id);
        $this->assertSame($frameMock, $collection->getframe($id));
    }

    /**
     * @covers \Bowling\Score\FrameCollection::getIterator
     */
    public function testCanGetIterator()
    {
        $collection = $this->getCollectionSubject();
        $this->assertInstanceOf('Iterator', $collection->getIterator());
    }

    /**
     * @covers \Bowling\Score\FrameCollection::getIterator
     * @depends testCanSetFrame
     */
    public function testIteratorIsNotEmpty()
    {
        $collection = $this->getCollectionSubject();
        $frameMock = new Frame();
        $id = 1;
        $collection->setFrame($frameMock, $id);

        $this->assertGreaterThan(0, count($collection->getIterator()));
    }

    /**
     * @covers \Bowling\Score\FrameCollection::getIterator
     * @depends testIteratorIsNotEmpty
     */
    public function testIteratorIncludesSetFrame()
    {
        $collection = $this->getCollectionSubject();
        $frameMock = new Frame();
        $id = 1;
        $collection->setFrame($frameMock, $id);

        foreach($collection as $frame) {
            $this->assertSame($frameMock, $frame);
        }
    }

    /**
     * @covers \Bowling\Score\FrameCollection::getIterator
     * @depends testIteratorIncludesSetFrame
     */
    public function testIteratorPreservesId()
    {
        $collection = $this->getCollectionSubject();
        $frameMock = new Frame();
        $id = 1;
        $collection->setFrame($frameMock, $id);

        foreach ($collection as $key=>$frame) {
            $this->assertSame($id, $key);
        }
    }

    /**
     * @return FrameCollection
     */private function getCollectionSubject()
    {
        return new FrameCollection();
    }
}