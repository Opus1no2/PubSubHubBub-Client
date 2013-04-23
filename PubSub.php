<?php
/**
 *
 * Base class for Pubsubhubbub protocal
 */
class PubSub
{
    /**
     *
     * Validate URL
     *
     * @param string $url
     *
     * @return string
     *
     * @throws RunTimeException
     */
    public static function validate($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        } else {
            throw new RunTimeException('Invalid URL provided');
        }
        
    }
    
    /**
     *
     * Excecute HTTP request
     *
     * @param mixed $data
     *
     * @return string
     *
     * @throws RunTimeException
     */
    protected function _request($hub, $data = null)
    {
        $header[] = 'Content-type: application/x-www-form-urlencoded';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $hub);
        curl_setopt($ch, CURLOPT_USERAGENT, 'foofoofoo');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        
        if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
            return $result;   
        }
        
        throw new RunTimeException('Invalid HTTP code');
    }
}