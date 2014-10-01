<?php

namespace Itkg\Core\Response\Processor;

use Itkg\Core\ConfigInterface;
use Itkg\Core\Response\PostProcessorInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DnsShardingProcessor
 *
 * Provide dns sharding for image media
 *
 * @package Itkg\Core\Response\Processor
 */
class DnsShardingProcessor implements PostProcessorInterface
{
    /**
     * Media host
     *
     * @var string
     */
    private $host;

    /**
     * List of DNS
     *
     * @var array
     */
    private $dns = array();

    /**
     * Constructor
     *
     * @param $host
     * @param array $dns
     */
    public function __construct($host, array $dns = array())
    {
        $this->host = $host;
        $this->dns = $dns;
    }
    /**
     * @param Response $response
     */
    public function process(Response $response)
    {
        /**
         * @fixme : Change this parameter by providing request
         */
        if ($_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') {

            if (! empty($this->dns)) {
                // le premier doit être le media standard
                if (! in_array($this->host, $this->dns)) {
                    $this->dns[] = $this->host;
                }
                // extract image link from media/image
                $pattern = '/src\=[\s\"\'`](http|https):\/\/(' . implode('|', str_replace('.', '\.', $this->dns)) . ')\/image\/([\w:?=@&\/#._;-]+)([\s\"`\'`]*[^>]*>)/i';
                // die();
                $response->setContent(
                    preg_replace_callback($pattern, array(
                        __CLASS__,
                        'rollDns'
                    ),
                    $response->getContent())
                );


            }
        }
    }

    /**
     * Replace src attributes
     *
     * @param  array $matches
     * @return string
     */
    public function rollDns($matches)
    {
        $indice = $this->extract_numbers($matches[3]);
        if (empty($indice)) {

            $return = 'src="' . $matches[1] . '://' . $this->host . '/image/' . $matches[3] . $matches[4] . '"';
        } else {
            $return = 'src="' . $matches[1] . '://' . $this->dns[$indice] . '/image/' . $matches[3] . $matches[4] . '"';
        }

        $return = str_replace(' />"', ' />', $return);
        $return = str_replace('">"', '">', $return);

        return $return;
    }

    /**
     * return a random number for dns sharding choice
     *
     * @param  string   $string
     * @return int
     */
    public function extract_numbers($string)
    {
        preg_match_all('/([\d]+)/', $string, $match);
        $return = intVal($match[0][1] / 3);
        if ($return > 2) {
            $return = 2;
        }

        return $return;
    }
}
