<?php
echo "PHP Test: " . date('Y-m-d H:i:s');
echo "\nPHP Version: " . phpversion();
echo "\nMysql PDO Available: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No');
?>
