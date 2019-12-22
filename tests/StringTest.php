<?php

declare(strict_types=1);

namespace Belca\Support\Tests;

use PHPUnit\Framework\TestCase;
use Belca\Support\Str;

final class StringTest extends TestCase
{
    public function testRemoveDuplicateSymbols()
    {
        $source1 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";
        $result1 = "ABC abbcc aaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";

        $source2 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";
        $result2 = "ABC abbcc aAAAA bbbbBBBBCCCC A A A B B B A A A C C C";

        $source3 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";
        $result3 = "ABC aabbcc aAAAA bbbbBBBBCCCC A A A B B B A A A C C C";

        $source4 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";
        $result4 = "ABC aabbcc aaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";

        $source5 = "ABC aabbcc A A aaaaAAAA bbbbBBBBCCCC A A A B B B A A A A C C C";
        $result5 = "ABC aabbcc A A aaaaAAAA bbbbBBBBCCCC A B B B A C C C";

        $source6 = "ABC aabbcc A A aaaaAAAA bbbbBBBBCCCC A A A B B B A A A A C C C";
        $result6 = "ABC aabbcc A A aaaaAAAA bbbbBBBBCCCC A B B B A A C C C";

        $this->assertEquals(Str::removeDuplicateSymbols($source1, 'a', 1, true), $result1);
        $this->assertEquals(Str::removeDuplicateSymbols($source2, 'a', 1, false), $result2);
        $this->assertEquals(Str::removeDuplicateSymbols($source3, 'a', 2, false), $result3);
        $this->assertEquals(Str::removeDuplicateSymbols($source4, 'a', 2, true), $result4);
        $this->assertEquals(Str::removeDuplicateSymbols($source5, 'A ', 2, false), $result5);
        $this->assertEquals(Str::removeDuplicateSymbols($source6, 'A ', 2, true), $result6);

        $source = "AABC aabbcc A A aaaaAAAA A A A B B";

        $result = Str::removeDuplicateSymbols($source, 'AA', 1, true);
        print_r($result);
    }

    public function testReduceDuplicateSymbols()
    {
        $source1 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";
        $number1 = 2;
        $result1 = "ABC aabbcc aaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";

        $source2 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";
        $number2 = 1;
        $result2 = "ABC abbcc aAAAA bbbbBBBBCCCC A A A B B B A A A C C C";

        $source3 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";
        $number3 = 3;
        $result3 = "ABC aabbcc aaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";

        $source4 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";
        $number4 = -1;
        $result4 = "ABC abbcc aAAAA bbbbBBBBCCCC A A A B B B A A A C C C";

        $source5 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";
        $number5 = 2;
        $result5 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A C C C";

        $source6 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A A B B B A A A";
        $number6 = 2;
        $result6 = "ABC aabbcc aaaaAAAA bbbbBBBBCCCC A A B B B A A A";

        $this->assertEquals(Str::reduceDuplicateSymbols($source1, 'a', $number1), $result1);
        $this->assertEquals(Str::reduceDuplicateSymbols($source2, 'a', $number2), $result2);
        $this->assertEquals(Str::reduceDuplicateSymbols($source3, 'a', $number3), $result3);
        $this->assertEquals(Str::reduceDuplicateSymbols($source4, 'a', $number4), $result4);
        $this->assertEquals(Str::reduceDuplicateSymbols($source5, 'Aa', $number5), $result5);
        $this->assertEquals(Str::reduceDuplicateSymbols($source6, 'A ', $number6), $result6);
    }

    public function testNormalizeFilePath()
    {
        $path1 = "/normal/path/to/file";
        $result1 = "/normal/path/to/file";

        $path2 = "/path//////to//dir#ectory/";
        $result2 = "/path/to/dir#ectory/";

        $path3 = "///////path//////to//////////directory//////////";
        $result3 = "/path/to/directory/";

        $this->assertEquals(Str::normalizeFilePath($path1), $result1);
        $this->assertEquals(Str::normalizeFilePath($path2), $result2);
        $this->assertEquals(Str::normalizeFilePath($path3), $result3);
    }

    public function testDifferenceSubstring()
    {
        $path11 = '/home/dios/directory/';
        $path12 = '/home/dios/directory/subdirecotry/filename';
        $result1 = 'subdirecotry/filename';

        $path21 = '/home/dios/';
        $path22 = '/dios/directory/subdirecotry/filename';
        $result2 = null;

        $path31 = '';
        $path32 = '/home/dios/directory/subdirecotry/filename';
        $result3 = '/home/dios/directory/subdirecotry/filename';

        $path41 = '/home/dios/directory/';
        $path42 = '';
        $result4 = '/home/dios/directory/';

        $path51 = '/home/dios/directory/';
        $path52 = '/';
        $result5 = 'home/dios/directory/';

        $path61 = '/';
        $path62 = '/home/dios/directory/subdirecotry/filename';
        $result6 = 'home/dios/directory/subdirecotry/filename';

        $path71 = ' ';
        $path72 = ' /home/dios/directory/subdirecotry/filename';
        $result7 = '/home/dios/directory/subdirecotry/filename';

        $path81 = '/home/dios/directory/subdirecotry/filename';
        $path82 = '/home/dios/directory/subdirecotry/filename';
        $result8 = '';

        $this->assertEquals(Str::differenceSubstring($path11, $path12), $result1);
        $this->assertEquals(Str::differenceSubstring($path21, $path22), $result2);
        $this->assertEquals(Str::differenceSubstring($path31, $path32), $result3);
        $this->assertEquals(Str::differenceSubstring($path41, $path42), $result4);
        $this->assertEquals(Str::differenceSubstring($path51, $path52), $result5);
        $this->assertEquals(Str::differenceSubstring($path61, $path62), $result6);
        $this->assertEquals(Str::differenceSubstring($path71, $path72), $result7);
        $this->assertEquals(Str::differenceSubstring($path81, $path82), $result8);
    }

    public function testfindPositionFromEnd()
    {
        $string1 = "text for search";

        $this->assertEquals(Str::findPositionFromEnd($string1, "search"), 9);
        $this->assertEquals(Str::findPositionFromEnd($string1, "for"), 5);
        $this->assertEquals(Str::findPositionFromEnd($string1, "next"), null);
        $this->assertEquals(Str::findPositionFromEnd($string1, "search", 5), null);
        $this->assertEquals(Str::findPositionFromEnd($string1, "for", 5), 5);
        $this->assertEquals(Str::findPositionFromEnd($string1, "search", 0, true), -6);
        $this->assertEquals(Str::findPositionFromEnd($string1, "for", 3, true), -10);
    }

    public function testfirstElementOfChain()
    {
        $chain1 = "path.to.file";
        $result1 = "path";

        $chain2 = "";
        $result2 = "";

        $chain3 = "...path..to...file";
        $result3 = "path";

        $chain4 = "/path/to/file";
        $result4 = "path";

        $chain5 = "path/to/file";
        $result5 = "path";

        $chain6 = ".............path.....to...file";
        $result6 = "path";

        $chain7 = "......./......path.....to...file";
        $result7 = "/";

        $chain8 = ".......................";
        $result8 = null;

        $chain9 = "......................9";
        $result9 = "9";

        $chain10 = "......................9.";
        $result10 = "9";

        $chain11 = "......................9.";
        $result11 = null;

        $chain12 = "11......................9.";
        $result12 = "11";

        $chain13 = "==.11==....................9.";
        $result13 = ".11";

        $chain14 = "==.11==....................9.";
        $result14 = null;

        $chain15 = "/path/to/file";
        $result15 = null;

        $chain16 = "file";
        $result16 = "file";

        $chain17 = "===.11==....................9.";
        $result17 = "=.11";

        $chain18 = "==1==....................9.";
        $result18 = "1";

        $chain19 = "==1==....................9.";
        $result19 = "1";

        $chain20 = "sd5f4s65df=4=s5df==1==....................9.";
        $result20 = "sd5f4s65df=4=s5df";

        $this->assertEquals(Str::firstElementOfChain($chain1), $result1);
        $this->assertEquals(Str::firstElementOfChain($chain2), $result2);
        $this->assertEquals(Str::firstElementOfChain($chain3), $result3);
        $this->assertEquals(Str::firstElementOfChain($chain4, '/'), $result4);
        $this->assertEquals(Str::firstElementOfChain($chain5, '/'), $result5);
        $this->assertEquals(Str::firstElementOfChain($chain6), $result6);
        $this->assertEquals(Str::firstElementOfChain($chain7), $result7);
        $this->assertEquals(Str::firstElementOfChain($chain8), $result8);
        $this->assertEquals(Str::firstElementOfChain($chain9), $result9);
        $this->assertEquals(Str::firstElementOfChain($chain10), $result10);
        $this->assertEquals(Str::firstElementOfChain($chain11, ".", true), $result11);
        $this->assertEquals(Str::firstElementOfChain($chain12, ".", true), $result12);
        $this->assertEquals(Str::firstElementOfChain($chain13, "=="), $result13);
        $this->assertEquals(Str::firstElementOfChain($chain14, "==", true), $result14);
        $this->assertEquals(Str::firstElementOfChain($chain15, "/", true), $result15);
        $this->assertEquals(Str::firstElementOfChain($chain16, "/", true), $result16);
        $this->assertEquals(Str::firstElementOfChain($chain16, "/"), $result16);
        $this->assertEquals(Str::firstElementOfChain($chain17, "=="), $result17);
        $this->assertEquals(Str::firstElementOfChain($chain18, "=="), $result18);
        $this->assertEquals(Str::firstElementOfChain($chain19, "==", true), null);
        $this->assertEquals(Str::firstElementOfChain($chain20, "==", true), $result20);
    }

    public function testlastElementOfChain()
    {
        $chain1 = "path.to.file";
        $result1 = "file";

        $chain2 = "";
        $result2 = "";

        $chain3 = "...path..to...file";
        $result3 = "file";

        $chain4 = "/path/to/file";
        $result4 = "file";

        $chain5 = "path/to/file";
        $result5 = "file";

        $chain6 = ".............path.....to...file";
        $result6 = "file";

        $chain7 = ".......f......path.....to.../";
        $result7 = "/";

        $chain8 = ".......................";
        $result8 = null;

        $chain9 = "......................9";
        $result9 = "9";

        $chain10 = "......................9.";
        $result10 = "9";

        $chain11 = "......................9.";
        $result11 = null;

        $chain12 = "11......................9";
        $result12 = "9";

        $chain13 = "==.11==1....................9.";
        $result13 = "1....................9.";

        $chain14 = "==.11==....................9==";
        $result14 = null;

        $chain15 = "/path/to/file/";
        $result15 = null;

        $chain16 = "file";
        $result16 = "file";

        $chain17 = "file...........";
        $result17 = "file";

        $chain18 = ".file...........";
        $result18 = "file";

        $chain19 = "..df..file...........";
        $result19 = "file";

        $chain20 = "..df..f...........";
        $result20 = "f";

        $chain21 = "==.11==....................9==";
        $result21 = "....................9";

        $chain22 = "==.11==....................9===";
        $result22 = "....................9=";

        $this->assertEquals(Str::lastElementOfChain($chain1), $result1);
        $this->assertEquals(Str::lastElementOfChain($chain2), $result2);
        $this->assertEquals(Str::lastElementOfChain($chain3), $result3);
        $this->assertEquals(Str::lastElementOfChain($chain4, '/'), $result4);
        $this->assertEquals(Str::lastElementOfChain($chain5, '/'), $result5);
        $this->assertEquals(Str::lastElementOfChain($chain6), $result6);
        $this->assertEquals(Str::lastElementOfChain($chain7), $result7);
        $this->assertEquals(Str::lastElementOfChain($chain8), $result8);
        $this->assertEquals(Str::lastElementOfChain($chain9), $result9);
        $this->assertEquals(Str::lastElementOfChain($chain10), $result10);
        $this->assertEquals(Str::lastElementOfChain($chain11, ".", true), $result11);
        $this->assertEquals(Str::lastElementOfChain($chain12, ".", true), $result12);
        $this->assertEquals(Str::lastElementOfChain($chain13, "=="), $result13);
        $this->assertEquals(Str::lastElementOfChain($chain14, "==", true), $result14);
        $this->assertEquals(Str::lastElementOfChain($chain15, "/", true), $result15);
        $this->assertEquals(Str::lastElementOfChain($chain16, "/", true), $result16);
        $this->assertEquals(Str::lastElementOfChain($chain16, "/"), $result16);
        $this->assertEquals(Str::lastElementOfChain($chain17), $result17);
        $this->assertEquals(Str::lastElementOfChain($chain18), $result18);
        $this->assertEquals(Str::lastElementOfChain($chain19), $result19);
        $this->assertEquals(Str::lastElementOfChain($chain20), $result20);
        $this->assertEquals(Str::lastElementOfChain($chain21, "=="), $result21);
        $this->assertEquals(Str::lastElementOfChain($chain22, "=="), $result22);
        $this->assertEquals(Str::lastElementOfChain("==", "=="), null);
        $this->assertEquals(Str::lastElementOfChain("", "=="), null);
    }
}
