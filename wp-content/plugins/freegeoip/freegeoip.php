<?php
/*
Plugin Name: Free GeoIP
Plugin URI: http://ifoundmydoctor.com
Description: Retrieves GeoIP information based on IP address of visitor
Author: Will
Version: 1.0
Author URI: http://codeatwill.com
*/

class FreeGeoIP {

    /**
     * Retrieves the geoip info for the given IP. If no IP is passed, it will look up
     * the IP from the header information.
     * @param $ip_address String The IP address
     * @return Array
     */
    public static function fetch ($ip_address = '') {
        $ip = empty($ip_address) ? self::get_user_ip() : $ip_address;
        $geoIpResponse = wp_remote_get('http://freegeoip.net/json/' . $ip);

        if (!is_wp_error($geoIpResponse)) {
           $geoIp = json_decode(wp_remote_retrieve_body($geoIpResponse));
        } else {
            $error_msg = $geoIpResponse->get_error_message();

            $geoIp = (object) array(
                asn => '',
                offset => '',
                area_code => '',
                continent_code => '',
                dma_code => '',
                ip => '',
                country_code => '',
                country_code3 => '',
                country => '',
                region => '',
                region_code => '',
                city => '',
                postal_code => '',
                timezone => '',
                latitude => '',
                longitude => '',
                isp => '',
                error => $error_msg
            );
        }

        return $geoIp;
    }

    private static function get_user_ip () {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            // shared
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            // proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return apply_filters( 'wpb_get_ip', $ip );
    }
}

