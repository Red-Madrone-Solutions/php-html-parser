<?php

declare(strict_types=1);
require_once 'tests/data/MockNode.php';

use PHPHtmlParser\Dom\Node\HtmlNode;
use PHPHtmlParser\Dom\Node\MockNode as Node;
use PHPHtmlParser\Dom\Node\TextNode;
use PHPUnit\Framework\TestCase;

class NodeChildrenTest extends TestCase
{
    public function testGetParent()
    {
        $parent = new Node();
        $child = new Node();
        $child->setParent($parent);
        $this->assertEquals($parent->id(), $child->getParent()->id());
    }

    public function testSetParentTwice()
    {
        $parent = new Node();
        $parent2 = new Node();
        $child = new Node();
        $child->setParent($parent);
        $child->setParent($parent2);
        $this->assertEquals($parent2->id(), $child->getParent()->id());
    }

    public function testNextSibling()
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals($child2->id(), $child->nextSibling()->id());
    }

    public function testNextHtmlSibling()
    {
        $parent = new Node();
        $html_child_1 = new HtmlNode('div');
        $text_child = new TextNode('text');
        $html_child_2 = new HtmlNode('div');
        $html_child_1->setParent($parent);
        $text_child->setParent($parent);
        $html_child_2->setParent($parent);
        $this->assertEquals($html_child_2->id(), $html_child_1->nextHtmlSibling()->id());
    }

    /**
     * @expectedException \PHPHtmlParser\Exceptions\ChildNotFoundException
     */
    public function testNextSiblingNotFound()
    {
        $parent = new Node();
        $child = new Node();
        $child->setParent($parent);
        $this->expectException(\PHPHtmlParser\Exceptions\ChildNotFoundException::class);
        $child->nextSibling();
    }

    public function testNextHtmlSiblingNotFound()
    {
        $parent = new Node();
        $html_child = new HtmlNode('div');
        $html_child->setParent($parent);
        $this->assertNull($html_child->nextHtmlSibling());

        $text_child = new TextNode('text');
        $text_child->setParent($parent);
        $this->assertNull($html_child->nextHtmlSibling());
    }

    /**
     * @expectedException \PHPHtmlParser\Exceptions\ParentNotFoundException
     */
    public function testNextSiblingNoParent()
    {
        $child = new Node();
        $this->expectException(\PHPHtmlParser\Exceptions\ParentNotFoundException::class);
        $child->nextSibling();
    }

    public function testPreviousSibling()
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals($child->id(), $child2->previousSibling()->id());
    }

    /**
     * @expectedException \PHPHtmlParser\Exceptions\ChildNotFoundException
     */
    public function testPreviousSiblingNotFound()
    {
        $parent = new Node();
        $node = new Node();
        $node->setParent($parent);
        $this->expectException(\PHPHtmlParser\Exceptions\ChildNotFoundException::class);
        $node->previousSibling();
    }

    /**
     * @expectedException \PHPHtmlParser\Exceptions\ParentNotFoundException
     */
    public function testPreviousSiblingNoParent()
    {
        $child = new Node();
        $this->expectException(\PHPHtmlParser\Exceptions\ParentNotFoundException::class);
        $child->previousSibling();
    }

    public function testGetChildren()
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals($child->id(), $parent->getChildren()[0]->id());
    }

    public function testCountChildren()
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals(2, $parent->countChildren());
    }

    public function testIsChild()
    {
        $parent = new Node();
        $child1 = new Node();
        $child2 = new Node();

        $child1->setParent($parent);
        $child2->setParent($child1);

        $this->assertTrue($parent->isChild($child1->id()));
        $this->assertTrue($parent->isDescendant($child2->id()));
        $this->assertFalse($parent->isChild($child2->id()));
    }
}
