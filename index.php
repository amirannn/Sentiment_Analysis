<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Text Proccesing Website</title>
    <link rel="stylesheet" href="main.css" />
    <link rel="stylesheet" href="bootstrap/css/bootstrap.rtl.min.css">
    <?php
      Use Sentiment\Analyzer;
      use Sentiment\Config\Config;
      require_once('vendor/autoload.php');
      require_once('dict/positives.php');
      require_once('dict/negatives.php');
      require_once('func.php');

      $analyzer = new Analyzer();
      $analyzer->updatelexicon($positive_words);
      $analyzer->updatelexicon($negative_words);
      $totalscore = 0;
      $inputtype = 0;
      $temp_file = NULL;
    ?>
</head>
<body>
<header>
    <h1 id="header-h1">وبسایت تشخیص احساس متون مثبت و منفی</h1>
    <h3 id="header-h3">متن خود را وارد کنید</h3>
</header>
<div class="center">
<form method="post" enctype="multipart/form-data" style=" display: block; text-align: center;">
    <textarea placeholder="تایپ کنید..." class="textinput" name="inputText"></textarea>
    <div class="button">
    <br>
    <input type="submit" value="Submit" class="btn btn-primary">
</div>
<div>
    <p id="p-styles">یا</p>
    <p id="p-styles2">فایل خود را بارگذاری کنید:</p>
</div>
<div>
    <input type="file" name="textfile" class="form-control inputfile">

<?php   
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputtype = 1;
            $file = $_FILES['textfile'];
            $filename = $file['name'];
            $temp_file = $file['tmp_name'];
        if (move_uploaded_file($temp_file, 'uploads/' . $filename)) {
            $text = file_get_contents('uploads/' . $filename);
            $paragraphs = explode(".", $text);
        }
      }  
      if( ($temp_file == NULL) && isset($_POST['inputText'])){
          $paragraphs = explode(".", $_POST['inputText']);
          $inputtype = 2;
        }
          if($inputtype){
          foreach($paragraphs as $paragraph) {
            $output_text = $analyzer->getSentiment($paragraph);
            $sentiment_value = apply_negation_scoring($paragraph, $positive_words, $negative_words, $negation_words);
            $norm_score = round(Config::normalize($sentiment_value), 4);
            $score = $output_text['compound'] + 2*$norm_score;

            require_once('exceptions.php');
            if($score > 0) {
                $positives[] = $paragraph;
                $totalscore += $score;
            } elseif ($score < 0) {
                $negatives[] = $paragraph;
                $totalscore += $score;
            }
          }  if($totalscore > 0) {
            echo '<p style="color: green; font-size: 30px; margin-top: 19px; margin-bottom: -6px; font-family: Btitr; font-weight: bold;">احساس متن شما مثبت است.</p>';
            echo "<p style='font-size: 25px; margin-top: 10px; font-family: Bnazanin; font-weight: bold;'>نرخ احساس متن : $totalscore</p>";
          } elseif ($totalscore < 0) {
            echo '<p style="color: red; font-size: 30px; margin-top: 19px; margin-bottom: -6px; font-family: Btitr; font-weight: bold;">احساس متن شما منفی است.</p>';
            echo "<p style='font-size: 25px; margin-top: 10px; font-family: Bnazanin; font-weight: bold;'>نرخ احساس متن : $totalscore</p>";
          }}
?></form>
</div>
</div>
<div class="container">
  <div class="half-textbox">
  <span style="font-size: 30px; font-family: btitr; color: green;">جملات مثبت</span><br/>
  <span style="font-size: 20px;">
  <?php
        if (isset($positives)){  
        foreach($positives as $positive){
          echo $positive . "." . "<br>";
        }} else {echo "بدون پاراگراف مثبت!";}
?></span>
  </div>
  <div class="half-textbox">
    <span style="font-size: 30px; font-family: btitr; color: red;">جملات منفی</span><br/>
    <span style="font-size: 20px;">
  <?php
        if (isset($negatives)){       
        foreach($negatives as $negative){
          echo $negative . "." . "<br>";
        }} else {echo "بدون پاراگراف منفی!";}
?></span>
  </div>
</div>    
</body>
</html>