<?php

namespace Tests\Unit;

use App\Practice\Turing\Turing;
use PHPUnit\Framework\TestCase;

class TuringTest extends TestCase
{
    /** @test */
    public function it_can_get_the_sum_of_any_2_number_sum_and_compare_to_x_if_all_larger_than_return_negative_one_else_get_the_max_value()
    {
        $turing = new Turing();

        $res = $turing->getAnyTwoNumberSumLessThanAnotherNumber([1,2,3,4],10);
        $res2 = $turing->getAnyTwoNumberSumLessThanAnotherNumber([20,30,10,50,89],10);
        $res3 = $turing->getAnyTwoNumberSumLessThanAnotherNumber([20,30,10,50,89],140);
        $res4 = $turing->getAnyTwoNumberSumLessThanAnotherNumber([5,90,13,25,56,10,2,8,9,99],200);
        $res5 = $turing->getAnyTwoNumberSumLessThanAnotherNumber([39,41,70,8],79);

        $this->assertEquals(7,$res);
        $this->assertEquals(-1,$res2);
        $this->assertEquals(139,$res3);
        $this->assertEquals(189,$res4);
        $this->assertEquals(78,$res5);
    }

    /** @test */
    public function it_can_a_repeated_number_and_correct_number()
    {
        $turing = new Turing();
        $res = $turing->getRepeatedAndCorrectNumber([1,2,3,2,2]);
        $res2 = $turing->getRepeatedAndCorrectNumber([1,2,3,4,4]);
        $res3 = $turing->getRepeatedAndCorrectNumber([5,6,7,6]);
        $res4 = $turing->getRepeatedAndCorrectNumber([4,5,6,4,4]);

        $this->assertEquals([2,4],$res);
        $this->assertEquals([4,5],$res2);
        $this->assertEquals([6,8],$res3);
        $this->assertEquals([4,7],$res4);
    }

    public function it_get_a_correct_baseball_game_score()
    {
        $turing = new Turing();
        $res = $turing->getBaseBallScore('2','5','D','+','C'); // 2,5,10
        $res2 = $turing->getBaseBallScore('10','D','+','1','C'); // 10,20,30
        $res3 = $turing->getBaseBallScore('20','C','10','3','+','D'); // 10,3,13,26

        $this->assertEquals(17,$res);
        $this->assertEquals(60,$res2);
        $this->assertEquals(52,$res3);
    }
}
