<?php

namespace OZiTAG\Tager\Backend\Core\Routing;

use Illuminate\Routing\ResourceRegistrar as BaseResourceRegistrar;
use Illuminate\Support\Str;

class ResourceRegistrar extends BaseResourceRegistrar {

    protected $resourceDefaults = ['index', 'store', 'show', 'update', 'destroy', 'move'];
    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceMove($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name).'/{'.$base.'}'.'/move/{direction}';

        $action = $this->getResourceAction($name, $controller, 'move', $options);

        return $this->router->post($uri, $action);
    }


    /**
     * Add the update method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceUpdate($name, $base, $controller, $options)
    {
        $name = $this->getShallowName($name, $options);

        $uri = $this->getResourceUri($name).'/{'.$base.'}';

        $action = $this->getResourceAction($name, $controller, 'update', $options);

        return $this->router->post($uri, $action);
    }


    /**
     * Add the update method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceDestroy($name, $base, $controller, $options)
    {
        $name = $this->getShallowName($name, $options);

        $uri = $this->getResourceUri($name).'/{'.$base.'}';

        $action = $this->getResourceAction($name, $controller, 'delete', $options);

        return $this->router->delete($uri, $action);
    }

    public function getResourceWildcard($value)
    {
        return 'id';
    }
}
