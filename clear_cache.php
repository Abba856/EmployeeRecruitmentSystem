<?php
// Clear PHP OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared<br>";
} else {
    echo "OPcache not available<br>";
}

// Also try to clear APC cache if it exists
if (function_exists('apc_clear_cache')) {
    apc_clear_cache();
    echo "APC cache cleared<br>";
} else {
    echo "APC cache not available<br>";
}

echo "Cache clearing completed";
?>