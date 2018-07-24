<?php

class SculpinKernel extends \Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel
{
    /**
     * {@inheritDoc}
     */
    protected function getAdditionalSculpinBundles()
    {
        return array(
            'DevWorks\Sculpin\Bundle\ScssBundle\SculpinScssBundle',
            'EternalApps\Sculpin\Bundle\DlpMailBundle\DlpMailBundle',
        );
    }
}
