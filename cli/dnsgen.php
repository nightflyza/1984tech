<?php

include ('api.1984tech.php');

$tech = new OrwellWorld();
print($tech->getBindZones());

$tech->saveBindZones();
?>
