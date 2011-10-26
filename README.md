
# crossQuery

crossQuery is a bioinformatics tool to efficently, easily and flexibly query large-scale sequencing data. 
crossQuery is open source (publication pending), and a live system with sample data runs at http://crossquery.labhive.com . to get log-in data please email me at    toni @ linkcloud.org  .

It depends on the labhive.com core system (which is also made public with this).
On the clientside it heavily depends on jQuery and a series of plugins for it: jGritter, md5, jQueryUI, jqPlot
The server-side is realized with php and mySQL.

## Installation

	 -- clone this repository. 
	 -- add  /backend/xs.php with the database connection info like this:
	 <code>
		<?php 
			$_SESSION["CFG"]['db_name'] = 'labhive';
			$_SESSION["CFG"]['db_server'] = 'localhost';
			$_SESSION["CFG"]['db_username'] = 'aUserNameWithWriteAccessToTheDatabase';
			$_SESSION["CFG"]['db_password'] = 'thePasswordYouGaveTheUser';
		?>
	 </code>
	 -- have a webserver serve it 
	 -- have a mySQL server hold your data (import the schema from /db/ it contains our sample datasets)

## Usage

	we will refer to the published manuscript for details on the use-patterns.
