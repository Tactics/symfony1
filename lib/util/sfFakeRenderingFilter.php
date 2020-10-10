<?php

namespace Tactics\Symfony\util;

/**
 * Class sfFakeRenderingFilter
 *
 * @package Tactics\Symfony\util
 */
class sfFakeRenderingFilter extends sfFilter
{
    public function execute($filterChain)
    {
        $filterChain->execute();

        $this->getContext()->getResponse()->sendContent();
    }
}
