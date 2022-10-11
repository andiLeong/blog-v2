<?php

namespace App\Practice\Turing;

/**
 * Turing code challenge
 *
 */
class Turing
{
    /**
     * get the repeated number and correct number (based on the order)
     * eg : [1,2,3,4,3]
     * repeated is 3 , correct number is 5, return [3,5]
     *
     * eg : [1,2,3,4,5,6,4]
     * repeated is 4 , correct number is 7, return [4,7]
     *
     * eg : [1,2,3,4,5,4,4]
     * repeated is 4 , correct number is 6, return [4,6]
     * @param $array
     * @return array
     */
    public function getRepeatedAndCorrectNumber($array)
    {
        $repeatedValues = array_count_values($array);
        arsort($repeatedValues);
        $repeated = array_key_first($repeatedValues);

        foreach ($array as $key => $value) {

            $preKey = $key - 1;
            if (array_key_exists($preKey, $array)) {
                $pre = $array[$preKey];
                if ($pre + 1 !== $value) {
                    $correct = $pre + 1;
                    break;
                }
            }
        }

        return [
                $repeated ?? null,
                $correct ?? null,
        ];
    }

    /**
     * get any 2 numbers of sum inside array thant compare to x
     * if results of any 2 numbers all larger than x return -1
     * else get the largest number that is smaller than x  out of the result
     * eg [1,5,3] those are results of sum , than compare to x 99 , this should return 5
     * @param $numbers
     * @param $x
     * @return int|mixed
     */
    public function getAnyTwoNumberSumLessThanAnotherNumber($numbers, $x)
    {
        //let's find out the most 2 largest number in side the array
        //sum those 2 number and then compare with x

//        $max = max($numbers);
//        $rest = array_filter($numbers, fn($number) => $number != $max);
//        $secondMax = max($rest);
//
//        if (($sum = $max + $secondMax) > $x) {
//            return -1;
//        }
//
//        return $sum;

        $sum = [];
        foreach ($numbers as $key => $number) {
            $remaining = $numbers;
            unset($remaining[$key]);
            foreach ($remaining as $remain) {
                $sum[] = [$number, $remain];
            }
        }
        $sum = array_values(array_unique(array_map(fn($r) => array_sum($r), $sum)));
        $filteredSum = array_filter($sum,fn($s) => $s < $x);

        if(empty($filteredSum)){
            return -1;
        }

        return max($filteredSum);
    }

    /**
     * get a baseball game score based on the rule
     * if we see special symbol we need to sum | double | remove the score
     * + sum previous 2 scores
     * D double previous score
     * C remove previous score
     *
     * @param ...$scores
     * @return float|int
     */
    public function getBaseBallScore(...$scores)
    {
        $res = [];
        foreach ($scores as $value) {

            if (!in_array($value, ['D', 'C', '+'])) {
                $res[] = $value;
            } else {

                if ($value == 'D') {
                    $last = end($res);
                    $res[] = $last * 2;
                }

                if ($value == '+') {
                    $lastTwoScores = array_slice($res, -2);
                    $res[] = array_sum($lastTwoScores);
                }

                if ($value == 'C') {
                    array_pop($res);
//                    $unsetKey = array_slice(array_keys($res), -1)[0];
//                    unset($res[$unsetKey]);
                }
            }
        }

        return array_sum($res);
    }
}
