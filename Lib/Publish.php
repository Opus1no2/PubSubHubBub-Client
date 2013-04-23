<?php
/**
 *
 * Publishing class for PubSubhubbub protocal
 */
class Publisher
{
    /**
     * @var string $_hub
     */
    protected $_hub;
    /**
     * @var string $_topic
     */
    protected $_topic;
    
    /**
     *
     * Class Constructor
     *
     * @param string $hub
     * 
     * @return void
     */
    public function __construct($hub)
    {
        $this->_hub = PubSub::validate($hub);
    }
    
    /**
     *
     * Set topic URL for HTTP request
     *
     * @param string $url
     * 
     * @return void
     */
    public function setTopic($url)
    {
        $url = 'hub.mode=publish';
        
        if (is_array($url)) {
            foreach ($url as $k) {
                $url .= '&hub.url=' . $k; 
            }
            $this->_topic = $url;
        } else {
            $this->_topic .= '&hub.url=' . $url;
        }
    }
    
    /**
     *
     * Execute HTTP request to publish
     *
     * @return void
     */
    public function publish()
    {
        $this->_request($this->_hub, $this->_topic);
    }
}