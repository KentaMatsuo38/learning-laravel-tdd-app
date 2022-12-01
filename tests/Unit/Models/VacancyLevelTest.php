<?php

namespace Tests\Unit\Models;

use App\Models\Models\VacancyLevel;
use PHPUnit\Framework\TestCase;

class VacancyLevelTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }
    /**
     * @param int $remainingCount
     * @param string $expectedMark
     * @dataProvider dataMark
     */
    public function testMark(int $remainingCount,string $expectedMark)
    {
        $level = new VacancyLevel($remainingCount);
        $this->assertSame($expectedMark, $level->mark());

    }
    public function dataMark()
    {
        return [
            '空きなし' => [
                'remainingCount' => 0,
                'expectedMark' => '×',
            ],
            '残りわずか' => [
                'remainingCount' => 4,
                'expectedMark' => '△',
            ],
            '空き十分' => [
                'remainingCount' => 5,
                'expectedMark' => '◎',
            ],
        ];
    }

    /**
     * @param int $remainingCount
     * @param String $expectedResult
     * @dataProvider dataSlug
     */
    public function testSlug(int $remainingCount,String $expectedResult){
        $level = new VacancyLevel($remainingCount);
        $this->assertSame($expectedResult, $level->slug());
    }
    public function dataSlug(){
        return ['空きなし'=>[
                    'remainCount'=>0,
                    'expectedResult'=>'empty'
                    ],
                '残りわずか'=>[
                    'remainCount'=>4,
                    'expectedResult'=>'few'],
                '空き充分'=>[
                    'remainCount'=>5,
                    'expectedResult'=>'enough'],
        ];
    }
}
