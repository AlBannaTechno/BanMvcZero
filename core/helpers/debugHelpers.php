<?php
namespace Helpers\Debug{
    function pretty_var_export($val) {
        echo '<pre>';
        /** @noinspection ForgottenDebugOutputInspection */
        var_export($val);
        echo '</pre>';
    }
}
