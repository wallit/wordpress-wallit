<?php
/**
 * Utilities provided to make some of the integration easier
 *
 * @author Aaron Saray
 */

namespace iMoneza;

/**
 * Class Helper
 * @package iMoneza
 */
class Helper
{
    /**
     * Gets the current IP address
     * @return string
     */
    public static function getCurrentIP()
    {
        $ip = '';

        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $headerKey) {
            if ($item = getenv($headerKey)) {
                foreach (array_filter(explode(',', $item), 'trim') as $ipAddress) {
                    if ($ip = filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        break 2;
                    }
                }
            }
        }

        return (string) $ip;
    }
}