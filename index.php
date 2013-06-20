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
                			$h = new Swish("config/index.swish-e");
                			$results = $h->query($_POST['term']);
                			//var_dump($results->getParsedWords("config/index.swish-e"));
					echo "There are ", $results->hits, ' on ' . $_POST['term'] . '<br />';
                			while ($r = $results->nextResult()) {
                        			$file = file_get_contents($r->swishdocpath);
						$regex = '/([A-Za-z0-9.,-]+\s+){0,10}' . $_POST['term'] . '(\s+[A-Za-z0-9.,-]+){0,10}/i';
						preg_match($regex, $file, $matches);
						printf("in file %s, relevance %d <br />",
                                			$r->swishdocpath,
                                			$r->swishrank
                        			);
						if (!empty($matches)) {
							echo '<div class="span4"><small>' . $matches[0] . '</small></div>';
						}
                			}
        			}
				?>
    						<legend>Search file base</legend>
						<input name="term" type="text" class="input-large">
						<span class="help-block">OR, AND, * can be used in combination to find terms</span>
    						<button type="submit" class="btn">Search</button>
  					</fieldset>
				</form>
			</div>
  		</div>
	</div>
	<script src="http://code.jquery.com/jquery.js"></script>
   	<script src="js/bootstrap.min.js"></script>
  </body>
</html>
