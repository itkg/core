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


use Itkg\Core\Exception\HttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ResponseExceptionListener
 * @package Itkg\Core\Listener
 */
class ResponseExceptionListener implements EventSubscriberInterface
{

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
            KernelEvents::EXCEPTION => 'onExceptionThrown'
        );
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onExceptionThrown(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $kernel = $event->getKernel();

        // Load errorCode Controller if exist else throw exception again
        if ($exception instanceof NotFoundHttpException) {
            $response = $kernel->handle(Request::create('_/Error/code404'), HttpKernelInterface::MASTER_REQUEST, false);
        } else {
            $response = $kernel->handle(Request::create('_/Error/code500'), HttpKernelInterface::MASTER_REQUEST, false);
        }

        $event->setResponse($response);
    }
}
