<?php


namespace Helpers\Layout{
    /**
     * Print Controller Title With Method[Action] Title Or Return SITE_NAME
     */

    function print_mvc_title(){
        if (isset($GLOBALS[__GLOB__CONTROLLER_TITLE__])) {
            echo $GLOBALS[__GLOB__CONTROLLER_TITLE__];
            if (isset($GLOBALS[__GLOB__CONTROLLER_ACTION_TITLE__])) {
                echo ' | ' . $GLOBALS[__GLOB__CONTROLLER_ACTION_TITLE__];
            }
        } else {
            echo SITE_NAME;
        }
    }
}

