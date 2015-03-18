<html>
<head>

  <head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>


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
  margin:5px;
  padding:5px;
  }

.left {
  background-color: gray;
  float: right;
  clear: left;
} 

#formResults {
  background-color: gray;
   padding-top: 15px;
} 

#submeet {
  padding-top: 25px;
}
</style>



<?php
//var_dump($_REQUEST['searchTerm']);

$searchTerm = $_REQUEST['searchTerm'];
$site = $_REQUEST['site'];
//echo $site;
if ($_REQUEST['searchTerm'] != null){
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
}
?>


</HEAD>
<BODY>
<center>

<div class="getform">

<?php 
if ($checked = 'both'){ ?>
  
      <fieldset>  
      <div class="item">
      <h5>Enter Search Terms</h5>
      <input id="searchTerm" name="searchTerm" type="text" size="40" value="" />
      </div>
      <br>
    
      <span>  
      <input id="searchSite2"  type="radio"   name="site"    value="site:http://nhlbi-public:8087/" >Filter Public
      <input id="searchSite1"  type="radio"   name="site"    value="site:http://nhlbi:8087/" >Filter Private
      <input id="searchSite3" type="radio"   name="site"    value="" >No Filter
      </span>

      <h5 style="clear: both; margin-top: 10px;">&nbsp;</h5>
      <div>
      <input id="submit" type="submit" value="SEARCH" />
      </div>
      </fieldset>
      
    </div>
<?php 
}
?>


<div id="submeet">
<h1> <?php echo 'TOP'; ?> </h1>
<H4> <?php print $results->response->numFound; ?> results </H4>
</div>




</center>
</body>
<script >
$(document).ready(function(){

// filter current search results by site
$("input:radio[id=searchSite1]").click(function(){
 $(".results1").hide("slow");
  $(".results2").show("slow");
});

$("input:radio[id=searchSite2]").click(function(){
 $(".results2").hide("slow");
  $(".results1").show("slow");
});

$("input:radio[id=searchSite3]").click(function(){
 $(".results1").show("slow");
  $(".results2").show("slow");
});




// new filtered search results by site
$("input:radio[id=searchSite1]").click(function(){
 $(".results1").hide("slow");
});

$("input:radio[id=searchSite2]").click(function(){
 $(".results2").hide("slow");
  $(".results1").show("slow");
});

$("input:radio[id=searchSite3]").click(function(){
 $(".results1").show("slow");
  $(".results2").show("slow");
});



$("#submit").click(function(){
var name = $("#searchTerm").val();

//alert(name);
//alert(name);

var site1 = $("#searchSite1").val();
var site2 = $("#searchSite2").val();
var site3 = $("#searchSite2").val();

// Returns successful data submission message when the entered information is stored in database.
var dataString = 'searchTerm='+ name + '&site1='+ site1 + '&site2='+ site2 + '&site3='+ site3;
if(name=='')
{
alert("Please Fill All Fields");
}
else
{
// AJAX Code To Submit Form.
$.ajax({
type: "POST",
url: "searchme.php",
data: dataString,
cache: false,
success: function(result){
//alert (result);
$happy = result; 

//$sad = $(happy).find('#content').html());
//{$("#formResults").replaceWith(result("#formResults"))};
//alert( $happy );

//$( "#testDiv" ).replaceWith( $happy );
$( "#submeet" ).replaceWith( $happy );

}
});
}
return false;
});
});


//$.ajax({ url: 'searchme.php', dataType: 'html', success: function(response) { $('#testDiv').html(jQuery(response).find('#formResults').html()); } });
$.ajax({ url: 'searchme.php', dataType: 'html', success: function(response) { $('#testDiv').html(jQuery(response).find('#formResults').html()); } });



</script>
</html>


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








