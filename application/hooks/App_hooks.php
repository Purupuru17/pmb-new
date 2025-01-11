<?php defined('BASEPATH') OR exit('No direct script access allowed');

class App_hooks {

    private $ci;

    function __construct() {
        $this->ci = & get_instance();
    }

    public function redirect_ssl() {
        $class = $this->ci->router->fetch_class();
        $exclude = array('');  // add more controller name to exclude ssl.
        if ($this->ci->config->item('app.debug') === 0) {
            if (!in_array($class, $exclude)) {
                // redirecting to ssl.
                $this->ci->config->config['base_url'] = str_replace('http://', 'https://', $this->ci->config->config['base_url']);
                if ($_SERVER['SERVER_PORT'] != 443)
                    redirect($this->ci->uri->uri_string());
            } else {
                // redirecting with no ssl.
                $this->ci->config->config['base_url'] = str_replace('https://', 'http://', $this->ci->config->config['base_url']);
                if ($_SERVER['SERVER_PORT'] == 443)
                    redirect($this->ci->uri->uri_string());
            }
        }
    }

    public function compress() {
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
        if ($new_buffer === null || $this->ci->config->item('app.debug') === 0) {
             $buffer = $new_buffer;
        }
        $this->ci->output->set_output($buffer);
        $this->ci->output->_display();
    }

    public function is_offline() {
        if ($this->ci->config->item('app.status') == 0) {
            include (APPPATH . 'views/errors/html/error_offline.php');
            die();
        }
    }

}
