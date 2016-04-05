<?php
if ($this->success) {
    wp_send_json_success($this->data);
}
else {
    wp_send_json_error($this->data);
}