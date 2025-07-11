<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of track_visitor
 *
 * @author https://roytuts.com
 */
class Track_Visitor {
    /*
     * Defines how many seconds a hit should be rememberd for. This prevents the
     * database from perpetually increasing in size. Thirty days (the default)
     * works well. If someone visits a page and comes back in a month, it will be
     * counted as another unique hit.
     */
    //private $HIT_OLD_AFTER_SECONDS = 2592000; // default: 30 days.

    /*
     * Don't count hits from search robots and crawlers. 
     */    
    private $IGNORE_SEARCH_BOTS = TRUE;

    /*
     * Don't count the hit if the browser sends the DNT: 1 header.
     */    
    private $HONOR_DO_NOT_TRACK = FALSE;

    /*
     * ignore controllers e.g. 'admin'
     */    
    private $CONTROLLER_IGNORE_LIST = array(
        'konten','sistem','non_login','beranda','logout','api'
    );

    /*
     * ignore ip address
     */    
    private $IP_IGNORE_LIST = array(
        '127.0.0.1','::1'
    );

    /*
     * visitor tracking table
     */    
    private $site_log = "yk_site_log";

    function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->library('user_agent','session');
        $this->ci->load->model('m_visitor');
    }

    function visitor_track() {
        $track_visitor = TRUE;
        
        if (isset($track_visitor) && $track_visitor === TRUE) {
            $proceed = TRUE;
            if ($this->IGNORE_SEARCH_BOTS && $this->is_search_bot()) {
                $proceed = FALSE;
            }
            if ($this->HONOR_DO_NOT_TRACK && !allow_tracking()) {
                $proceed = FALSE;
            }
            foreach ($this->CONTROLLER_IGNORE_LIST as $controller) {
                if (strpos(trim($this->ci->uri->segment(1)), $controller) !== FALSE) {
                    $proceed = FALSE;
                    break;
                }
            }
            if (in_array($this->ci->input->server('REMOTE_ADDR'), $this->IP_IGNORE_LIST)) {
                $proceed = FALSE;
            }
            if ($proceed === TRUE) {
                $this->init_visitor();
            }
        }
    }
    
    private function init_visitor() {
        $now_ip = $this->ci->input->server('REMOTE_ADDR');
        $page_name = $this->ci->router->fetch_class() . '/' . $this->ci->router->fetch_method();
        $page_length = strlen(trim($this->ci->router->fetch_class() . '/' . $this->ci->router->fetch_method()));
        $query_params = trim(substr($this->ci->uri->uri_string(), $page_length + 1));
        $query_string = strlen($query_params) ? $query_params : '';
        
        $data = array(
            'requested_url' => $this->ci->input->server('REQUEST_URI'),
            'referer_page' => $this->ci->agent->referrer(),
            'user_agent' => ip_agent(),
            'page_name' => $page_name,
            'query_string' => $query_string,
            'access_date' => date('Y-m-d H:i:s')
        );
        
        if ($this->track_session() === TRUE) {
            $tmp_ip = $this->ci->session->userdata('visitor_ip');
            
            $today = $this->ci->m_visitor->cekToday($tmp_ip, date('Y-m-d'));
            if($today['rows'] > 0){
                if($tmp_ip !== $now_ip){
                    $data['no_of_visits'] = intval($today['data']['no_of_visits']) + 1;
                    $this->ci->m_visitor->update($today['data']['site_log_id'], $data);
                }
            }else{
                $data['ip_address'] = $tmp_ip;
                $data['no_of_visits'] = 1;
                $this->ci->m_visitor->insert($data);
            }
            
        }else{
            $today = $this->ci->m_visitor->cekToday($now_ip, date('Y-m-d'));
            if($today['rows'] > 0){
                $data['no_of_visits'] = intval($today['data']['no_of_visits']) + 1;
                $result = $this->ci->m_visitor->update($today['data']['site_log_id'], $data);
                if ($result === FALSE) {
                    $this->ci->session->set_userdata('track_session', FALSE);
                } else {
                    $this->ci->session->set_userdata('track_session', TRUE);
                    $this->ci->session->set_userdata('visitor_ip', $now_ip);
                }
                
            }else{
                $data['ip_address'] = $now_ip;
                $data['no_of_visits'] = 1;
                $result = $this->ci->m_visitor->insert($data);
                if ($result === FALSE) {
                    $this->ci->session->set_userdata('track_session', FALSE);
                } else {
                    $this->ci->session->set_userdata('track_session', TRUE);
                    $this->ci->session->set_userdata('visitor_ip', $now_ip);
                }
            }
        }
    }
    private function log_visitor() {
        if ($this->track_session() === TRUE) {
            //update the visitor log in the database, based on the current visitor
            //id held in $_SESSION["visitor_id"]
            $temp_visitor_id = $this->ci->session->userdata('visitor_id');
            $visitor_id = isset($temp_visitor_id) ? $temp_visitor_id : 0;
            $no_of_visits = $this->ci->session->userdata('visits_count');
            $current_page = current_url();
            $temp_current_page = $this->ci->session->userdata('current_page');
            if (isset($temp_current_page) && $temp_current_page != $current_page) {
                $page_name = $this->ci->router->fetch_class() . '/' . $this->ci->router->fetch_method();
                $page_length = strlen(trim($this->ci->router->fetch_class() . '/' . $this->ci->router->fetch_method()));
                $query_params = trim(substr($this->ci->uri->uri_string(), $page_length + 1));
                $query_string = strlen($query_params) ? $query_params : '';
                $data = array(
                    'no_of_visits' => $no_of_visits,
                    'ip_address' => $this->ci->input->server('REMOTE_ADDR'),
                    'requested_url' => $this->ci->input->server('REQUEST_URI'),
                    'referer_page' => $this->ci->agent->referrer(),
                    'user_agent' => $this->ci->agent->agent_string(),
                    'page_name' => $page_name,
                    'query_string' => $query_string,
                    'access_date' => date('Y-m-d H:i:s')
                );
                $this->ci->db->insert($this->site_log, $data);
                $this->ci->session->set_userdata('current_page', $current_page);
            }
        } else {
            $page_name = $this->ci->router->fetch_class() . '/' . $this->ci->router->fetch_method();
            $page_length = strlen(trim($this->ci->router->fetch_class() . '/' . $this->ci->router->fetch_method()));
            $query_params = trim(substr($this->ci->uri->uri_string(), $page_length + 1));
            $query_string = strlen($query_params) ? $query_params : '';
            $data = array(
                'ip_address' => $this->ci->input->server('REMOTE_ADDR'),
                'requested_url' => $this->ci->input->server('REQUEST_URI'),
                'referer_page' => $this->ci->agent->referrer(),
                'user_agent' => $this->ci->agent->agent_string(),
                'page_name' => $page_name,
                'query_string' => $query_string
            );
            $result = $this->ci->db->insert($this->site_log, $data);
            if ($result === FALSE) {
                /**
                 * find the next available visitor_id for the database
                 * to assign to this person
                 */                
                $this->ci->session->set_userdata('track_session', FALSE);
            } else {
                /**
                 * find the next available visitor_id for the database
                 * to assign to this person
                 */                
                $this->ci->session->set_userdata('track_session', TRUE);
                $entry_id = $this->ci->db->insert_id();
                $query = $this->ci->db->query('select max(no_of_visits) as next from ' .
                        $this->site_log . ' limit 1');
                $count = 0;
                if ($query->num_rows() == 1) {
                    $row = $query->row();
                    if ($row->next == NULL || $row->next == 0) {
                        $count = 1;
                    } else {
                        $count++;
                    }
                }
                //update the visitor entry with the new visitor id
                //Note, that we do it in this way to prevent a "race condition"
                $this->ci->db->where('site_log_id', $entry_id);
                $data = array(
                    'no_of_visits' => $count
                );
                $this->ci->db->update($this->site_log, $data);
                //place the current visitor_id into the session so we can use it on
                //subsequent visits to track this person
                $this->ci->session->set_userdata('visits_count', $count);
                //save the current page to session so we don't track if someone just refreshes the page
                $current_page = current_url();
                $this->ci->session->set_userdata('current_page', $current_page);
            }
        }
    }

    /**
     * check track_session
     * 
     * @return	bool
     */    
    private function track_session() {
        return ($this->ci->session->userdata('track_session') === TRUE ? TRUE : FALSE);
    }

    /**
     * check whether bot
     * 
     * @return bool
     */    
    private function is_search_bot() {
        // Of course, this is not perfect, but it at least catches the major
        // search engines that index most often.
        $spiders = array(
            "abot",
            "dbot",
            "ebot",
            "hbot",
            "kbot",
            "lbot",
            "mbot",
            "nbot",
            "obot",
            "pbot",
            "rbot",
            "sbot",
            "tbot",
            "vbot",
            "ybot",
            "zbot",
            "bot.",
            "bot/",
            "_bot",
            ".bot",
            "/bot",
            "-bot",
            ":bot",
            "(bot",
            "crawl",
            "slurp",
            "spider",
            "seek",
            "accoona",
            "acoon",
            "adressendeutschland",
            "ah-ha.com",
            "ahoy",
            "altavista",
            "ananzi",
            "anthill",
            "appie",
            "arachnophilia",
            "arale",
            "araneo",
            "aranha",
            "architext",
            "aretha",
            "arks",
            "asterias",
            "atlocal",
            "atn",
            "atomz",
            "augurfind",
            "backrub",
            "bannana_bot",
            "baypup",
            "bdfetch",
            "big brother",
            "biglotron",
            "bjaaland",
            "blackwidow",
            "blaiz",
            "blog",
            "blo.",
            "bloodhound",
            "boitho",
            "booch",
            "bradley",
            "butterfly",
            "calif",
            "cassandra",
            "ccubee",
            "cfetch",
            "charlotte",
            "churl",
            "cienciaficcion",
            "cmc",
            "collective",
            "comagent",
            "combine",
            "computingsite",
            "csci",
            "curl",
            "cusco",
            "daumoa",
            "deepindex",
            "delorie",
            "depspid",
            "deweb",
            "die blinde kuh",
            "digger",
            "ditto",
            "dmoz",
            "docomo",
            "download express",
            "dtaagent",
            "dwcp",
            "ebiness",
            "ebingbong",
            "e-collector",
            "ejupiter",
            "emacs-w3 search engine",
            "esther",
            "evliya celebi",
            "ezresult",
            "falcon",
            "felix ide",
            "ferret",
            "fetchrover",
            "fido",
            "findlinks",
            "fireball",
            "fish search",
            "fouineur",
            "funnelweb",
            "gazz",
            "gcreep",
            "genieknows",
            "getterroboplus",
            "geturl",
            "glx",
            "goforit",
            "golem",
            "grabber",
            "grapnel",
            "gralon",
            "griffon",
            "gromit",
            "grub",
            "gulliver",
            "hamahakki",
            "harvest",
            "havindex",
            "helix",
            "heritrix",
            "hku www octopus",
            "homerweb",
            "htdig",
            "html index",
            "html_analyzer",
            "htmlgobble",
            "hubater",
            "hyper-decontextualizer",
            "ia_archiver",
            "ibm_planetwide",
            "ichiro",
            "iconsurf",
            "iltrovatore",
            "image.kapsi.net",
            "imagelock",
            "incywincy",
            "indexer",
            "infobee",
            "informant",
            "ingrid",
            "inktomisearch.com",
            "inspector web",
            "intelliagent",
            "internet shinchakubin",
            "ip3000",
            "iron33",
            "israeli-search",
            "ivia",
            "jack",
            "jakarta",
            "javabee",
            "jetbot",
            "jumpstation",
            "katipo",
            "kdd-explorer",
            "kilroy",
            "knowledge",
            "kototoi",
            "kretrieve",
            "labelgrabber",
            "lachesis",
            "larbin",
            "legs",
            "libwww",
            "linkalarm",
            "link validator",
            "linkscan",
            "lockon",
            "lwp",
            "lycos",
            "magpie",
            "mantraagent",
            "mapoftheinternet",
            "marvin/",
            "mattie",
            "mediafox",
            "mediapartners",
            "mercator",
            "merzscope",
            "microsoft url control",
            "minirank",
            "miva",
            "mj12",
            "mnogosearch",
            "moget",
            "monster",
            "moose",
            "motor",
            "multitext",
            "muncher",
            "muscatferret",
            "mwd.search",
            "myweb",
            "najdi",
            "nameprotect",
            "nationaldirectory",
            "nazilla",
            "ncsa beta",
            "nec-meshexplorer",
            "nederland.zoek",
            "netcarta webmap engine",
            "netmechanic",
            "netresearchserver",
            "netscoop",
            "newscan-online",
            "nhse",
            "nokia6682/",
            "nomad",
            "noyona",
            "nutch",
            "nzexplorer",
            "objectssearch",
            "occam",
            "omni",
            "open text",
            "openfind",
            "openintelligencedata",
            "orb search",
            "osis-project",
            "pack rat",
            "pageboy",
            "pagebull",
            "page_verifier",
            "panscient",
            "parasite",
            "partnersite",
            "patric",
            "pear.",
            "pegasus",
            "peregrinator",
            "pgp key agent",
            "phantom",
            "phpdig",
            "picosearch",
            "piltdownman",
            "pimptrain",
            "pinpoint",
            "pioneer",
            "piranha",
            "plumtreewebaccessor",
            "pogodak",
            "poirot",
            "pompos",
            "poppelsdorf",
            "poppi",
            "popular iconoclast",
            "psycheclone",
            "publisher",
            "python",
            "rambler",
            "raven search",
            "roach",
            "road runner",
            "roadhouse",
            "robbie",
            "robofox",
            "robozilla",
            "rules",
            "salty",
            "sbider",
            "scooter",
            "scoutjet",
            "scrubby",
            "search.",
            "searchprocess",
            "semanticdiscovery",
            "senrigan",
            "sg-scout",
            "shai'hulud",
            "shark",
            "shopwiki",
            "sidewinder",
            "sift",
            "silk",
            "simmany",
            "site searcher",
            "site valet",
            "sitetech-rover",
            "skymob.com",
            "sleek",
            "smartwit",
            "sna-",
            "snappy",
            "snooper",
            "sohu",
            "speedfind",
            "sphere",
            "sphider",
            "spinner",
            "spyder",
            "steeler/",
            "suke",
            "suntek",
            "supersnooper",
            "surfnomore",
            "sven",
            "sygol",
            "szukacz",
            "tach black widow",
            "tarantula",
            "templeton",
            "/teoma",
            "t-h-u-n-d-e-r-s-t-o-n-e",
            "theophrastus",
            "titan",
            "titin",
            "tkwww",
            "toutatis",
            "t-rex",
            "tutorgig",
            "twiceler",
            "twisted",
            "ucsd",
            "udmsearch",
            "url check",
            "updated",
            "vagabondo",
            "valkyrie",
            "verticrawl",
            "victoria",
            "vision-search",
            "volcano",
            "voyager/",
            "voyager-hc",
            "w3c_validator",
            "w3m2",
            "w3mir",
            "walker",
            "wallpaper",
            "wanderer",
            "wauuu",
            "wavefire",
            "web core",
            "web hopper",
            "web wombat",
            "webbandit",
            "webcatcher",
            "webcopy",
            "webfoot",
            "weblayers",
            "weblinker",
            "weblog monitor",
            "webmirror",
            "webmonkey",
            "webquest",
            "webreaper",
            "websitepulse",
            "websnarf",
            "webstolperer",
            "webvac",
            "webwalk",
            "webwatch",
            "webwombat",
            "webzinger",
            "wget",
            "whizbang",
            "whowhere",
            "wild ferret",
            "worldlight",
            "wwwc",
            "wwwster",
            "xenu",
            "xget",
            "xift",
            "xirq",
            "yandex",
            "yanga",
            "yeti",
            "yodao",
            "zao/",
            "zippp",
            "zyborg"
        );

        $agent = strtolower($this->ci->agent->agent_string());

        foreach ($spiders as $spider) {
            if (strpos($agent, $spider) !== FALSE)
                return TRUE;
        }

        return FALSE;
    }

}

/* End of file track_visitor.php */
/* Location: ./application/hooks/Track_Visitor.php */