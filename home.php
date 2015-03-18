<html>
<head>

<style>
.results1 {
  background-color: yellow;
  width: 600px;
  margin:30px;
  padding:15px;  
} 
.results2 {
  background-color: red;
  width: 600px;
  margin:30px;
  padding:15px;
} 

.getform {
  background-color: green;
  width: 600px;
  margin:30px;
  padding:15px;
  }

.left {
  background-color: gray;
  float: right;
  clear: left;
} 
</style>


<?php
$searchTerm = $_REQUEST['searchTerm'];
$site = $_REQUEST['site'];

//var_dump($_REQUEST);

$results = getPublicSolrSearch($searchTerm,$site);

switch ($_REQUEST['site']) {
  case 'site:http://nhlbi-public:8087/':
    $checked = 'public';
    break;

  case 'site:http://nhlbi:8087/':
    $checked = 'private';
    break;

  case '':
    $checked = 'both';
    break;

  default:
    # code...
    break;
}
?>


</HEAD>
<BODY>
<center>
<h1> <?php echo $_REQUEST['searchTerm'] ?> </h1>
<H4> <?php print $results->response->numFound; ?> results </H4>
<div class="getform">

<?php 
if ($checked = 'both'){ ?>
  <form action="home.php" enctype="multipart/form-data" method="post">
      <fieldset>  
      <div class="item">
      <h5>Enter Search Terms</h5>
      <input id="searchTerm" name="searchTerm" type="text" size="40" value="<?php echo $_REQUEST['searchTerm'] ?>" />
      </div>
      <br>
      <span>  
      <input type="radio" name="site" value="site:http://nhlbi-public:8087/" >Public
      <input type="radio" name="site"         value="site:http://nhlbi:8087/" >Private
      <input type="radio" name="site"         value="" >Both
      </span>
      <h5 style="clear: both; margin-top: 10px;">&nbsp;</h5>
      <input type="submit" value="SEARCH" />
      </fieldset>
      </form>
    </div>
<?php 
}
?>






<div class="getresults">

<?php

//print $_REQUEST['url'];
//print_r($results);
//print $_REQUEST['searchTerm'];
//print_r($results->response->docs[0]);

$i = 0;
//while ($results->response->docs[0]->site != '')
  while ($i < 100 && $i < $results->response->numFound)
{
  $defaulter = 'PUBLIC';
  $resultsClass = 'results1';

  if ($results->response->docs[$i]->site == 'http://nhlbi:8087/'){
      $defaulter = 'PRIVATE';
  $resultsClass = 'results2';
  }
      $results->response->docs[$i]->site = $defaulter;
  ?>

<div class='<?php print $resultsClass; ?>'>


<?php 
//print '<p><a href="'.$results->response->docs[$i]->url.'>'.$results->response->docs[$i]->label.'"</a></p>'; 
?>


<h3><?php print $results->response->docs[$i]->label; ?></h3>
<p><?php print $results->response->docs[$i]->teaser; ?></p>
<p><?php print array_slice($results->response->docs[$i]->content, 2, 30); ?></p>
<p><?php print $results->response->docs[$i]->bundle_name; ?></p>
<p><?php print $results->response->docs[$i]->ss_image_path; ?></p>
<p><a href='<?php print $results->response->docs[$i]->url; ?>'><?php print $results->response->docs[$i]->url; ?></a></p>
</div>
<?php
$i++;
}
?>

</div>
<div class = "left">
<?php
// print $results->response->docs[0]->site;
// print $results->response->docs[0]->bundle;
// print $results->response->docs[0]->content;
// echo 'ONE</br>';
// print $results->response->docs[1]->site;
// print $results->response->docs[1]->bundle;
// print $results->response->docs[1]->content;
// echo 'TWO</br>';
// print $results->response->docs[2]->site;
// print $results->response->docs[2]->bundle;
// print $results->response->docs[2]->content;
// echo 'THREE</br>';
//print $results->response->numFound;
?>

</div>

</center>
</body>


<?php

function getPublicSolrSearch($searchTerm,$siteFill)
{
  ##Settings
$site = "http://localhost:8983/solr/";
$action = "select?q=";
$term = "&wt=json&rows=100";
$url = $site.$action.$searchTerm.'+AND+'.$siteFill.$term;
//print $url;

  $ch = curl_init();
  //$timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);

$obj = json_decode($data);
  return $obj;
}
?>



