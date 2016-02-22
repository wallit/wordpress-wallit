<?php
/**
 * Main App
 *
 * @author Aaron Saray
 */

namespace iMonezaPRO;
use Pimple\Container;
use iMonezaPRO\Traits;


/**
 * Class App
 * @package iMonezaPRO
 */
class App
{
    use Traits\Options;

    /**
     * @var Container
     */
    protected $di;

    /**
     * App constructor.
     * @param Container $di
     */
    public function __construct(Container $di)
    {
        $this->di = $di;

        // this is scheduled hourly AFTER the first time we've kicked this off, or if we have this configured
        add_action('imoneza_hourly', function() use ($di) {
            /** @var \iMonezaPRO\Controller\RefreshOptions $controller */
            $controller = $di['controller.refresh-options'];
            $controller();
        });
    }
}