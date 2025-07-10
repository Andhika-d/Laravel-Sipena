<?php

if (!function_exists('auto_asset')) {
    function auto_asset($path) {
        return (request()->isSecure() ? secure_asset($path) : asset($path));
    }
}
