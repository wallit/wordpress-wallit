<?php
if ($success) {
    wp_send_json_success();
}
else {
    wp_send_json_error($error);
}