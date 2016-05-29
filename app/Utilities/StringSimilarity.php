<?php


namespace App\Utilities;

/**
 * Class StringSimilarity by Dice Coefficient
 * @package App\Utilities
 */
class StringSimilarity
{
    /**
     * Get the letter pairs of each word in the string
     *
     * @param $str
     * @return array
     */
    private static function wordLetterPairs($str)
    {
        // Array that holds all our pairs from every word
        $allPairs = array();

        // Split string into words
        $words = explode(' ', $str);

        /*
         * As we split a string on spaces - the same letters arranged in more words
         * would have fewer letter pairings and consequently yield a higher
         * similarity.
         */

        // For each word
        for ($w = 0; $w < count($words); $w++) {
            
            // Get array of letter pairs
            $pairsInWord = static::letterPairs($words[$w]);

            // Push each pair in word up into our allPairs array
            for ($p = 0; $p < count($pairsInWord); $p++) {
                $allPairs[] = $pairsInWord[$p];
            }
        }

        return $allPairs;
    }

    /**
     * Get the letter pairs of a string (single word -no spaces)
     * 
     * @param $str
     * @return array
     */
    private static function letterPairs($str)
    {
        // How many pairs?
        $numPairs = strlen($str) - 1;
        $pairs = array();

        for ($i = 0; $i < $numPairs; $i++) {
            $pairs[$i] = substr($str, $i, 2);
        }

        return $pairs;
    }

    /**
     * Compare two given strings and return their similarity in
     * percentage
     *
     * @param $str1
     * @param $str2
     * @return float
     */
    public static function compare($str1, $str2)
    {
        $pairs1 = static::wordLetterPairs(strtoupper($str1));
        $pairs2 = static::wordLetterPairs(strtoupper($str2));

        // Count how many equal pairs
        $intersection = 0;

        // Total number of pairs
        $union = count($pairs1) + count($pairs2);

        // For each pair of string 1
        for ($i = 0; $i < count($pairs1); $i++) {
            // Get the pair
            $pair1 = $pairs1[$i];

            $pairs2 = array_values($pairs2);

            // For each pair in string 1 - loop through pairs of string 2 => O(m.n) 
            for ($j = 0; $j < count($pairs2); $j++) {
                $pair2 = $pairs2[$j];
                if ($pair1 === $pair2) {

                    // Increase our counter
                    $intersection++;

                    // Once a pair is matched in pairs2 - we'll remove it so we won't have another
                    // pair1 matching it.
                    unset($pairs2[$j]);

                    // If there's a match for pair1 - we also don't need to check for anymore matches
                    // in pairs2
                    break;
                }
            }
        }


        // Return percentage up to 2 decimal places
        return round(2.0 * $intersection / $union, 4) * 100;
    }
}