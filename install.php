<?php

    // Composer Install
    $cmd1 = 'composer install';
    exec($cmd1, $output1);
    echo "Output Composer Install: " . implode("\n", $output1) . "\n";

    // Artisan Migrate
    $cmd2 = 'php artisan migrate --force';
    exec($cmd2, $output2);
    echo "Output Artisan Migrate: " . implode("\n", $output2) . "\n";

    // Generate Application Key
    $cmd3 = 'php artisan key:generate';
    exec($cmd3, $output3);
    echo "Output Generate Application Key: " . implode("\n", $output3) . "\n";

    // Generate Application Key
    $cmd4 = 'php artisan storage:link';
    exec($cmd4, $output4);
    echo "Output Generate Application Key: " . implode("\n", $output4) . "\n";

?>