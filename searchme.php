
<?php
$searchTerm = $_REQUEST['searchTerm'];
$site = $_REQUEST['searchTerm'];

//echo "HAPPY DAYS";
//var_dump($_REQUEST);
//echo $site;

$results = getPublicSolrSearch($searchTerm,$site);
?>

<BODY>
<center>
<div id="submeet">
<h1> <?php echo $_REQUEST['searchTerm'] ?> </h1>
<H4> <?php print $results->response->numFound; ?> results </H4>

<div id="formResults">

<?php
$i = 0;
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








