<!DOCTYPE html>
<html>
  <head>
    <title>Swish-E Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
    
			</div>
			<div class="span10">
    				<h1>Swish-E <strong>/</strong> <small><a href="upload.php">Upload a File</a></small></h1>
				
				<form name="search" method="post" action="">
                                        <fieldset>

				<?php 
        			if (!empty($_POST)) {
                			require_once('result.php');
							try {
								$h = new Swish("config/index.swish-e");
								$results = $h->query($_POST['term']);
								echo "There are ", $results->hits, ' on ' . $_POST['term'] . '<br />';
								$fileLocs = array();
								while ($r = $results->nextResult()) {
									$fileLocs[] = $r->swishdocpath;
								}
								$search = new ResultText($fileLocs, $_POST['term'], 10);
								$results = $search->getResults();
								foreach ($fileLocs as $key => $loc) {
									echo '<div>' . $loc;
									if (!empty($results[$loc])) {
										foreach ($results[$loc] as $word) {
											foreach ($word as $result) {
												echo '<blockquote><small>' . $result . '</small></blockquote>';
											}
										}
									}
									echo '</div>';
								}
							} catch (Exception $e) {
								echo '<div class="span5 alert alert-error">' . $e->getMessage() . '</div>';
							}
					}
				?>
				<div>
    					<legend>Search file base</legend>
						<input name="term" type="text" class="input-large">
						<span class="help-block">OR, AND, * can be used in combination to find terms</span>
    						<button type="submit" class="btn">Search</button>
  				</div>
					</fieldset>
				</form>
			</div>
  		</div>
	</div>
	<script src="http://code.jquery.com/jquery.js"></script>
   	<script src="js/bootstrap.min.js"></script>
  </body>
</html>
