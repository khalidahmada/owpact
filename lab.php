<?php

$dbhost = '127.0.0.1';
$dbuser = 'root';
$dbpass = 'root';
$dbname = 'maroc-etude';
$mysqldump=exec('which mysqldump');


$command = "$mysqldump --opt -h $dbhost -u $dbuser -p $dbpass $dbname > $dbname.sql";

exec($command);