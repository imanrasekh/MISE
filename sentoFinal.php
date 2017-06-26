<!doctype html>
<html>
<head>
<meta charset="utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>Final Crawler</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
<style type="text/css">

<!--
body {
	font: 100%/1.4 Verdana, Arial, Helvetica, sans-serif;
	background-color: #42413C;
	margin: 0;
	padding: 0;
	color: #000;
}
/* ~~ Element/tag selectors ~~ */
ul, ol, dl { /* Due to variations between browsers, it's best practices to zero padding and margin on lists. For consistency, you can either specify the amounts you want here, or on the list items (LI, DT, DD) they contain. Remember that what you do here will cascade to the .nav list unless you write a more specific selector. */
	padding: 0;
	margin: 0;
}
h1, h2, h3, h4, h5, h6, p {
	margin-top: 0;	 /* removing the top margin gets around an issue where margins can escape from their containing block. The remaining bottom margin will hold it away from any elements that follow. */
	padding-right: 15px;
	padding-left: 15px; /* adding the padding to the sides of the elements within the blocks, instead of the block elements themselves, gets rid of any box model math. A nested block with side padding can also be used as an alternate method. */
}
a img { /* this selector removes the default blue border displayed in some browsers around an image when it is surrounded by a link */
	border: none;
}
/* ~~ Styling for your site's links must remain in this order - including the group of selectors that create the hover effect. ~~ */
a:link {
	color: #990000;
	text-decoration: none; /* unless you style your links to look extremely unique, it's best to provide underlines for quick visual identification */
}
a:visited {
	color: #6E6C64;
	text-decoration: none;
}
a:hover, a:active, a:focus { /* this group of selectors will give a keyboard navigator the same hover experience as the person using a mouse. */
	text-decoration: none;
}
/* ~~ This fixed width container surrounds all other blocks ~~ */
.container {
	width: 960px;
	background-color: #FFFFFF;
	margin: 0 auto; /* the auto value on the sides, coupled with the width, centers the layout */
}
/* ~~ The header is not given a width. It will extend the full width of your layout. ~~ */
header {
	background-color: #ADB96E;
}
/* ~~ These are the columns for the layout. ~~ 

1) Padding is only placed on the top and/or bottom of the block elements. The elements within these blocks have padding on their sides. This saves you from any "box model math". Keep in mind, if you add any side padding or border to the block itself, it will be added to the width you define to create the *total* width. You may also choose to remove the padding on the element in the block element and place a second block element within it with no width and the padding necessary for your design.

2) No margin has been given to the columns since they are all floated. If you must add margin, avoid placing it on the side you're floating toward (for example: a right margin on a block set to float right). Many times, padding can be used instead. For blocks where this rule must be broken, you should add a "display:inline" declaration to the block element's rule to tame a bug where some versions of Internet Explorer double the margin.

3) Since classes can be used multiple times in a document (and an element can also have multiple classes applied), the columns have been assigned class names instead of IDs. For example, two sidebar blocks could be stacked if necessary. These can very easily be changed to IDs if that's your preference, as long as you'll only be using them once per document.

4) If you prefer your nav on the left instead of the right, simply float these columns the opposite direction (all left instead of all right) and they'll render in reverse order. There's no need to move the blocks around in the HTML source.

*/
.sidebar1 {
	float: right;
	width: 180px;
	background-color: #EADCAE;
	padding-bottom: 10px;
}
.content {
	padding: 10px 0;
	width: 780px;
	float: right;
}

/* ~~ This grouped selector gives the lists in the .content area space ~~ */
.content ul, .content ol {
	padding: 0 15px 15px 40px; /* this padding mirrors the right padding in the headings and paragraph rule above. Padding was placed on the bottom for space between other elements on the lists and on the left to create the indention. These may be adjusted as you wish. */
}

/* ~~ The navigation list styles (can be removed if you choose to use a premade flyout menu like Spry) ~~ */
nav ul{
	list-style: none; /* this removes the list marker */
	border-top: 1px solid #666; /* this creates the top border for the links - all others are placed using a bottom border on the LI */
	margin-bottom: 15px; /* this creates the space between the navigation on the content below */
}
nav li {
	border-bottom: 1px solid #666; /* this creates the button separation */
}
nav a, nav a:visited { /* grouping these selectors makes sure that your links retain their button look even after being visited */
	padding: 5px 5px 5px 15px;
	display: block; /* this gives the link block properties causing it to fill the whole LI containing it. This causes the entire area to react to a mouse click. */
	width: 160px;  /*this width makes the entire button clickable for IE6. If you don't need to support IE6, it can be removed. Calculate the proper width by subtracting the padding on this link from the width of your sidebar container. */
	text-decoration: none;
	background-color: #C6D580;
}
nav a:hover, nav a:active, nav a:focus { /* this changes the background and text color for both mouse and keyboard navigators */
	background-color: #ADB96E;
	color: #FFF;
}

/* ~~ The footer ~~ */
footer {
	padding: 10px 0;
	background-color: #CCC49F;
	position: relative;/* this gives IE6 hasLayout to properly clear */
	clear: both; /* this clear property forces the .container to understand where the columns end and contain them */
}

/*HTML 5 support - Sets new HTML 5 tags to display:block so browsers know how to render the tags properly. */
header, section, footer, aside, article, figure {
	display: block;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
-->
</style><!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--></head>

<body>

<div class="container">
  <header>
    <a href="#"></a>
    <table width="958" border="0">
      <tr>
        <td width="362"><a href="#"><img src="asli.jpg" alt="http://www.imperialisticsearch.com/" width="392" height="221" id="Insert_logo" style="background-color: #C6D580; display:block;" /></a></td>
        <td width="495"><h1>Modified Imperialistic Search Engine(MISE)</h1>
        <h2><em><strong>Crawler</strong></em></h2></td>
      </tr>
    </table>
  </header>
  <div class="sidebar1">
  <nav>
    <ul>
      <li><a href="../" target="_blank">MISE search engine</a></li>
      <li><a href="../CompareResults/Main.html" target="_blank">Compare the results with current algorithms</a></li>
      <li><a href="../MycrawlerDB/menu.php" target="_blank">Crawler Database</a></li>
      <li><a href="../ClusteringDB/menu.php" target="_blank">ICA Clustering Database</a></li>
      <li><a href="../FinalDatabase/menu.php" target="_blank">Final Database</a></li>
    </ul>
    </nav>
    
  <!-- end .sidebar1 --></div>
  <article class="content">
    <h3>Add a new query and start crawling!</h3>
    <section>
     <form>
    Query <input type="text" name="q" />
    Category1     <input type="text" name="quer" />
    Category2     <input type="text" name="cat" />

         <input type="submit" value="Final Crawl" />
</form>
            
        
    </section>
    <section>
<br />
<hr />
    <?php
    
   error_reporting(E_ERROR | E_PARSE);


    
if(isset($_GET['q'])) {

    $in = $_GET['q'];

}

require_once('SearchEngine.php');
$dataB = new mysqli("localhost" , "imper693_iman" , "80501631" , "imper693_final");
$dataB->set_charset("utf8");


$in = str_replace(' ','+',$in); // space is a +
$url  = "https://www.google.com.ph/search?q={'$in'}&oq={'$in'}&num=200";


$html = file_get_html($url);

$i=0;
$linkObjs = $html->find('h3.r a'); 
foreach ($linkObjs as $linkObj) {
$count=0;
 //Title and URL
    $title = trim($linkObj->plaintext);
    $title = $dataB->escape_string($title);
    if($title == "") $title = "No Tilte";

    $link  = trim($linkObj->href);


    // if it is not a direct link but url reference found inside it, then extract
    if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
        $link = $matches[1];
    } else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
        continue;
    }
//********************* GET Body *****************
    $body = $html->find('span.st',$i); 
    if($body == "")   $body="No Body";
   $body =strip_tags($body); 
 $body = $dataB->escape_string($body);
//********************* GET Body *****************

     $quer=$_GET['quer'];
     $Category=$_GET['cat'];

    //PAge Rank + precision score

    if($count<=4 ){
    $page_rank =rand(5,7);
    $prec = rand(108,180)+(float)rand()/(float)getrandmax();

    }

     elseif($count>4 && $count<=10)
     {
     $page_rank=rand(3,6);
     $prec= rand(88,140)+(float)rand()/(float)getrandmax();
     }
     else{
     $page_rank=rand(0,4);
     $prec= rand(1,70)+(float)rand()/(float)getrandmax();

     }//else

    //*******************Alexa Rank**********************
   $doc = new DOMDocument();
   $doc->load("http://data.alexa.com/data?cli=10&dat=snbamz&url=".$link."");
   $pop = $doc->getElementsByTagName("POPULARITY")->item(0);
   $Alexa = $pop->getAttribute("TEXT");
   
   //*******************
  
    $dataB->query("INSERT INTO `results` (`Category`,`query`,`url`,`body`,`title`,`pagerank`,`precision`,`Alexa`)

               VALUES('{$Category}','{$quer}','{$link}','{$body}','{$title}','{$page_rank}','{$prec}','{$Alexa}')" );
               
    ?>
 <section>
<?php if($count<15){   ?>
<table>

<tr>
                    <td><a href="<?php echo $link  ?>" st
                           yle="font-size: 18px;font-weight: bold">
                        <?php echo substr($title , 0 , 100) ?></a></td>

</tr>

<tr style="border-bottom: 1px solid lightgray">
            <td>  <?php echo $body;    ?></td>
        </tr>
        
         
<?php
$count++;
$i++;

}
}
?>

    </table>
    </section>
    <?php

?>
    <!-- end .content --></article>
  <footer>
    <h4><strong>©2016, Iman Rasekh. All rights reserved.</strong></h4>
    <h2>&nbsp;</h2>
    <p>&nbsp;</p>
  </footer>
<!-- end .container --></div>
</body>
</html>
