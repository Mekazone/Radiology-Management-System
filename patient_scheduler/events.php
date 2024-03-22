<?php

/**
 * @author: Hanjors Global Ltd
 * @copyright 9th June 2011
 * @title: code that forces download of backup
 */

header('Content-disposition: attachment; filename=events.csv');
header('Content-type: application/csv');
readfile('events.csv');
?>