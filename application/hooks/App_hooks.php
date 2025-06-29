<?php defined('BASEPATH') OR exit('No direct script access allowed');

class App_hooks {

    private $ci;

    function __construct() {
        $this->ci = & get_instance();
    }

    public function redirect_ssl() {
        $class = $this->ci->router->fetch_class();
        $exclude = array(''); // Tambahkan controller yang tidak perlu HTTPS

        if (ENVIRONMENT === 'production' && php_sapi_name() !== 'cli') {
            // Deteksi apakah sudah HTTPS
            $is_https = (
                (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            );

            if (!in_array($class, $exclude)) {
                // Paksa redirect ke HTTPS
                $this->ci->config->set_item('base_url', str_replace('http://', 'https://', $this->ci->config->item('base_url')));
                if (!$is_https) {
                    redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 'location', 301);
                    exit;
                }
            } else {
                // Paksa redirect ke HTTP jika di-exclude
                $this->ci->config->set_item('base_url', str_replace('https://', 'http://', $this->ci->config->item('base_url')));
                if ($is_https) {
                    redirect('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 'location', 301);
                    exit;
                }
            }
        }
    }

    public function is_compress() {
        ini_set("pcre.recursion_limit", "16777");
        $buffer = $this->ci->output->get_output();
        // BUFFER 1
        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';
        $new_buffer = preg_replace($re, " ", $buffer);
        /*
        $search = array(
            '/\>[^\S ]+/s',    //strip whitespaces after tags, except space
            '/[^\S ]+\</s',    //strip whitespaces before tags, except space
            '/(\s)+/s'    // shorten multiple whitespace sequences
            );
        $replace = array(
            '>',
            '<',
            '\\1'
            );
        $new_buffer = preg_replace($search, $replace, $buffer);
        */
        if ($new_buffer === null || ENVIRONMENT === 'production') {
             $buffer = $new_buffer;
        }
        $this->ci->output->set_output($buffer);
        $this->ci->output->_display();
    }

    public function is_offline() {
        if (APP_STATUS == 0) {
            include (APPPATH . 'views/errors/html/error_offline.php');
            die();
        }
    }
}
