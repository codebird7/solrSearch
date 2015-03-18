<html>
<head>

<style>
.results1 {
  background-color: yellow;
  width: 450px;
  margin:15px;
  padding:15px;
} 
.results2 {
  background-color: red;
  width: 450px;
  margin:15px;
  padding:15px;
} 

.getform {
  background-color: green;
  width: 90%;
  }
.getresults {
  width: 90%;
  background-color: gold;
} 

.fullOn {
  width: 100%;
  background-color: green;
} 
#colPrivate {
  background-color: gray;
  width: 530px;
  margin:10px;
  padding:10px;
  float:left;
} 
#colPublic {
  background-color: gray;
  width: 530px;
  margin:10px;
  padding:10px;
  float: right;
}

#colPrivate .results1 {display:none;}
#colPublic .results2 {display:none;}
</style>


<?php
$searchTerm = $_REQUEST['searchTerm'];
$site = $_REQUEST['site'];
//echo $site;
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
        <form action="home2.php" enctype="multipart/form-data" method="post">
            <fieldset>  
            <div class="item">
            <h5>Enter Search Terms</h5>
            <input id="searchTerm" name="searchTerm" type="text" size="40" value="<?php echo $_REQUEST['searchTerm'] ?>" />
            </div>
            <br>
            
            <h5 style="clear: both; margin-top: 10px;">&nbsp;</h5>
            <input type="submit" value="SEARCH" />
            </fieldset>
            </form>
</div>
<?php 
}
?>






<div class="getresults">
    <div id="colPrivate">
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
          print '<p><a href="'.$results->response->docs[$i]->url.'>'.$results->response->docs[$i]->label.'"</a></p>'; 
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
      <div id="colPublic">
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
          print '<p><a href="'.$results->response->docs[$i]->url.'>'.$results->response->docs[$i]->label.'"</a></p>'; 
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
    </div>
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



