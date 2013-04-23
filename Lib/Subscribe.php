<?php
/**
 *
 * Subscription Class for PubSubhubbub Protocal
 */
class Subscribe
{
    /**
     * @var string $_hub
     */
    protected $_hub;
    /**
     * @var string $_verifyToken
     */
    protected $_verifyToken;
    /**
     * @var string $_callBack
     */
    protected $_callBack;
    /**
     * @var int $_leaseSeconds
     */
    protected $_leaseSeconds;
    /**
     * @var string $_verify
     */
    protected $_verify = 'sync';
    
    /**
     *
     * Class contructor
     *
     * @param string $topic
     *
     * @return void
     */
    public function __construct($topic)
    {
        $this->_topic = PubSub::validate($topic);
    }
    
    /**
     *
     * Set Hub URL
     *
     */
    public function setHub($hub)
    {
        $this->_hub = PubSub::validate($hub);
        return $this;
    }
    
    /**
     *
     * Set call Back URL
     *
     * @param string $url
     *
     * @return obj
     */
    public function setCallBack($url)
    {
        $this->_callBack = PubSub::validate($url);
        return $this;
    }
    
    /**
     *
     * Set Secret
     *
     * @param string $secret
     *
     * @return obj
     */
    public function setSecret($secret)
    {
        $this->_secret = $secret;
        return $this;
    }
    
    /**
     *
     * Set Verification Token
     *
     * @param string $token
     *
     * @return obj
     */
    public function setVerifyToken($token)
    {
        $this->_verifyToken = $token;
        return $this;
    }
    
    /**
     *
     * Set Lease Seconds
     *
     * @param int $seconds
     *
     * @return obj
     */
    public function setLeaseSeconds($seconds)
    {
        $this->_leaseSeconds = $seconds;
        return $this;
    }
    
    /**
     *
     * Set Verification
     *
     * @param string $verify
     *
     * @return obj
     */
    public function setVerify($verify)
    {
        $this->_verify = $verify;
        return $this;
    }
    
    /**
     *
     * Subscribe to Hub
     *
     * @return void
     */
    public function subscribe()
    {
        if (!isset($this->_hub)) {
            $this->_findHub();
        }
        $data = $this->_getRequestString(__METHOD__);
        $this->_request($this->_hub, $data);
    }
    
    /**
     *
     * Unsubscribe to Hub
     *
     * @return void
     */
    public function unsubscribe($hub)
    {   
        $data = $this->_getRequestString(__METHOD__);
        $this->_request($hub, $data);
    }
    
    /**
     *
     * Create Post Data for HTTP Request
     *
     * @param string $mode
     *
     * @return string
     */
    protected function _getRequestString($mode)
    {
        $post = array(
            'hub.mode'          => $mode,
            'hub.callback'      => $this->_callBack,
            'hub.verify'        => $this->_verify,
            'hub.verify_token'  => $this->_verifyToken,
            'hub.lease_seconds' => $this->_leaseSeconds,
            'hub.topic'         => $this->_topic
        );
        
        $reqString = http_build_query($post);
        
        return $reqString;       
    }
    
    /**
     *
     * Find available hub for a feed
     *
     * @return void
     *
     * @throws RunTimeException
     */
    protected function _findHub()
    {
        $hubs = array();
        $xmlVals = array();
        $xml = $this->_request($this->_hub);
        
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($xml), $xmlVals);
        xml_parser_free($parser);

        foreach ($xmlVals as $v) {
            if ($v['attributes']['rel'] == 'hub') {
                $hubs[] = $v['attributes']['href'];
            }
        }
    
        if (PubSub::validate($hubs[0])) {
            $this->_hub = $hubs[0];
        } else {
            throw new RunTimeException('This feed does not reference a valid hub url');
        }
    }
}


