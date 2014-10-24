<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Listener;

use Itkg\Core\Response\PostProcessorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;


/**
 * Post response processor
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ResponsePostRendererListener implements EventSubscriberInterface
{

    /**
     * Response post renderers
     *
     * @var array
     */
    private $processors = array();

    /**
     * Constructor
     *
     * @param array $processors
     */
    public function __construct(array $processors = array())
    {
        foreach ($processors as $processor) {
            $this->addPostProcessor($processor);
        }
    }

    /**
     * @param PostProcessorInterface $processor
     */
    public function addPostProcessor(PostProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array('onPostProcessing', 10)
        );
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onPostProcessing(FilterResponseEvent $event)
    {
        foreach ($this->processors as $processor) {
            $processor->process($event->getResponse());
        }
    }
}
