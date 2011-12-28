<?php

/*
 * This file is part of the APYEasyAnnoationBundle.
 *
 * (c) Abhoryo <abhoryo@free.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace APY\EasyAnnotationBundle\Annotation\Examples;

use Symfony\Component\HttpKernel\KernelEvents;
use APY\EasyAnnotationBundle\Annotation\EasyAnnotation;

class Cache extends EasyAnnotation
{
    /**
     * The expiration date as a valid date for the strtotime() function.
     *
     * @var string
     */
    protected $expires;

    /**
     * The number of seconds that the response is considered fresh by a private
     * cache like a web browser.
     *
     * @var integer
     */
    protected $maxage;

    /**
     * The number of seconds that the response is considered fresh by a public
     * cache like a reverse proxy cache.
     *
     * @var integer
     */
    protected $smaxage;

    /**
     * Whether or not the response is public or not.
     *
     * @var integer
     */
    protected $public;


    public function process($annotationScope, $event, $class, $method)
    {
        if ($annotationScope != self::propertyScope) {
            $response = $event->getResponse();

            if (!$response->isSuccessful()) {
                return;
            }

            if (null !== $this->smaxage) {
                $response->setSharedMaxAge($this->smaxage);
            }

            if (null !== $this->maxage) {
                $response->setMaxAge($this->maxage);
            }

            if (null !== $this->expires) {
                $date = \DateTime::createFromFormat('U', strtotime($this->expires), new \DateTimeZone('UTC'));
                $response->setExpires($date);
            }

            if ($this->public) {
                $response->setPublic();
            }

            $event->setResponse($response);
        }
    }

    public function getEvent(){
        return KernelEvents::RESPONSE;
    }
}
