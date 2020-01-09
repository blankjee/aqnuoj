<?php

require_once('config.inc.php');
require_once('my_func.inc.php');


$sql = "UPDATE users SET pass_ratio = solved/submited WHERE submited <> 0";
        
pdo_query($sql);

?>
