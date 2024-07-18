<?php

function apply_negation_scoring($text, $positive_words, $negative_words, $negation_words) {
    // Split the text into words.
    $words = explode(' ', $text);
    $sentiment_score = 0;
    $negate_next_word = false;

    foreach ($words as $word) {
        $word_score = 0;

        // If the last word was a negation, reverse the score of this word.
        if ($negate_next_word) {
            $negate_next_word = false;  // Reset negation flag after using.
            
            if (isset($positive_words[$word])) {
                $word_score = -$positive_words[$word];
            } elseif (isset($negative_words[$word])) {
                $word_score = -$negative_words[$word];
            }
        }
        
        // Check for stand-alone negation words and set flag to negate the next word.
        if (in_array($word, $negation_words)) {
            $negate_next_word = true;
            continue; // Skip scoring for negation word itself.
        }

        // Check and handle the attached prefix negations.
        foreach ($negation_words as $negation) {
            if (strpos($word, $negation) === 0) {
                // Attach the negation to the word.
                $stripped_word = substr($word, strlen($negation));
                
                // Only if the stripped word is positive or negative, get the score and negate it
                if (isset($positive_words[$stripped_word])) {
                    $word_score = -$positive_words[$stripped_word];
                    break;
                } elseif (isset($negative_words[$stripped_word])) {
                    $word_score = -$negative_words[$stripped_word];
                    break;
                }
            }
        }

        // If no negation has occurred, continue with regular scoring
        if (!$negate_next_word && $word_score == 0) {
            if (isset($positive_words[$word])) {
                $word_score = $positive_words[$word];
            } elseif (isset($negative_words[$word])) {
                $word_score = $negative_words[$word];
            }
        }
        
        // Add to overall sentiment score.
        $sentiment_score += $word_score;
    }

    return $sentiment_score;
}




$negation_words = [
    'بی', 'بدون' , 'نا' , 'سوء' , 'بلا' , 'عدم' , 'غیر', 'کم']

?>