<!DOCTYPE html>
<html>
  <head>
    <title>Swish-E Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/style.css" rel="stylesheet">
  </head>
  <body>
    	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
    
			</div>
			<div class="span10">
				<h1><img src="http://www.expr3ss.com/expr3ss_images/expr3ssLogo.png" width="115px" height="115px" /> <strong>/</strong> <small><a href="upload.php">Upload a File</a></small></h1>
				<form name="search" method="post" action="">
				<fieldset>
					<?php 
        			if (!empty($_POST)) {
                			require_once('result.php');
							try {
								$h = new Swish("config/index.swish-e");
								$results = $h->query($_POST['term']);
								echo "There are ", $results->hits, ' files containing "<span id="term">' . $_POST['term'] . '</span>"<br />';
								$fileLocs = array();
								while ($r = $results->nextResult()) {
									$fileLocs[] = $r->swishdocpath;
								}
								
								echo '<div class="results">';
								foreach ($fileLocs as $key => $loc) {
									$short = array_pop(explode('/', $loc));
									echo '<div class="loc-holder"><div class="location">' . array_pop(explode('/', $short)) . '</div></div>';
								}
								echo '</div>';
							} catch (Exception $e) {
								echo '<div class="span5 alert alert-error">' . $e->getMessage() . '</div>';
							}
					}
					?>
			<div>
    		<legend>Search file base</legend>
						<input name="term" type="text" class="input-large">
						<span class="help-block">OR, AND, *, NOT can be used in combination to find terms</span>
    						<button type="submit" class="btn">Search</button>
  				</div>
					</fieldset>
				</form>
				<div class="span5">
					<h4>How To</h4>
					<p>A number of keywords can be used to build a "query", those keywords are exact to their meaning. Searches are <em>NOT</em> case sensitive. '*' can be used as a wildcard, ' " ' (double quotes) can be used for multiple words/phrases. '(' & ')' can be used to group conditions (brackets).</p>
					<h4>Examples</h4>
					<p>Search through documents that...</p>
					<ul>
						<li>contain the word 'computer' AND 'office' = <strong><em>computer AND office</em></strong></li>
						<li>do NOT contain the word 'computer' = <strong><em>NOT computer</em></strong></li>
						<li>contain the phrase 'customer service' AND 'approachable' OR 'positive' = <strong><em>"customer service" AND (approachable OR positive)</em></strong></li>
						<li>contains words that have 'approach' or similar = <strong><em>approach*</em></strong></li>
					</ul>
					<p>Any of these examples can be combined, a longer query may look like : ("customer* service*" OR positive) AND computer AND office</p>
					<p>This would search for any documents that have a term that looks like "customer* service*" '*' being a wild card AND that mentions the words "computer" AND "office".</p>
				</div>
			</div>
  		</div>
	</div>
	<script src="http://code.jquery.com/jquery.js"></script>
   	<script src="js/bootstrap.min.js"></script>
	<script src="js/demo.js"></script>
  </body>
</html>
