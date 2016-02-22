<?php
if ($success) {
    wp_send_json_success($data);
}
else {
    wp_send_json_error($data);
}