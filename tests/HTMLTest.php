<?php

declare(strict_types=1);

namespace Belca\Support\Tests;

use PHPUnit\Framework\TestCase;
use Belca\Support\HTML;

final class HTMLTest extends TestCase
{
    public function testRemoveTags()
    {
        $html = "<b>Test</b> <p>Test test <a href='#' class='test'>link</a></p><img src='test'><img src='test'/><hr><br><script type='script/javascript'>alert('Test');</script>";
        $result = "Test Test test linkalert('Test');";

        $this->assertEquals(HTML::removeTags($html), $result);
    }

    public function testRemoveTagContent()
    {
        $html = "<?xml version=\"1.0\" encoding='UTF-8'?><!DOCTYPE><b>Test</b> <p>Test <I >test</i> <a href='#' class='test'>link</a></p><img src='test'><img src='test'/><hr><br><script type='script/javascript'>alert('Test');</script>";
        $result = "<b>Test</b> <p>Test  <a href='#' class='test'>link</a></p>";

        $this->assertEquals(HTML::removeTagContent($html, ['xml', 'doctype', 'script', 'i', 'hr', 'br', 'img']), $result);
    }

    public function testRemoveSingleTags()
    {
        $html = "<b>Test</b> <p>Test <I >test</i> <a href='#' class='test'>link</a></p><img src='test'><img src='test'/><hr><br><script type='script/javascript'>alert('Test');</script>";
        $result1 = "<b>Test</b> <p>Test <I >test</i> <a href='#' class='test'>link</a></p><script type='script/javascript'>alert('Test');</script>";
        $result2 = "<b>Test</b> <p>Test <I >test</i> <a href='#' class='test'>link</a></p><img src='test'><img src='test'/><script type='script/javascript'>alert('Test');</script>";

        $this->assertEquals(HTML::removeSingleTags($html), $result1);
        $this->assertEquals(HTML::removeSingleTags($html, ['IMG', 'a']), $result2);
    }

    public function testRemoveTagAttributes()
    {
        $html = "<b>Test</b> <b sdf> <b df=2> <b df='2'> <b df=''> <b data-f=''> <b checked='' backa=''> <input type=\"checkbox\" checked> <p>Test test <a href='#' class='test' id='ident'><b>li</b>nk</a></p><img src='test'><img src='test'/><hr><br />";
        $result = "<b>Test</b> <b> <b> <b> <b> <b> <b> <input> <p>Test test <a href='#'><b>li</b>nk</a></p><img src='test'><img src='test'><hr><br />";

        $this->assertEquals(HTML::removeTagAttributes($html, ['src', 'href']), $result);
    }
}
