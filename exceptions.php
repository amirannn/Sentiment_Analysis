<?php
            if(strpos($paragraph, 'نیست')){
              $score = -$score;
            }
            elseif(strpos($paragraph, 'نبود')){
              $score = -$score;
            }
            elseif(strpos($paragraph, 'نبوده')){
              $score = -$score;
            }
            elseif(strpos($paragraph, 'نخواهد بود')){
              $score = -$score;
            }
            elseif(strpos($paragraph, 'نمیباشد')){
              $score = -$score;
            }
            elseif(strpos($paragraph, 'ندارم')){
              $score = -$score;
            }
            elseif(strpos($paragraph, 'ندارد')){
              $score = -$score;
            }
            elseif(strpos($paragraph, 'نداری')){
              $score = -$score;
            }
            elseif(strpos($paragraph, 'ندارید')){
              $score = -$score;
            }
            elseif(strpos($paragraph, 'نمی باشد')){
              $score = -$score;
            };
            foreach($positive_words as $positive_word => $value){
            if (strpos($paragraph, 'خیلی '.$positive_word) !== FALSE) {
              $score = $score + 0.08; }}
            foreach($negative_words as $negative_word => $value){
            if (strpos($paragraph, 'خیلی '.$negative_word) !== FALSE) {
              $score = $score - 0.08; }}
?>