<?php

namespace OZiTAG\Tager\Backend\Core\Routing;

use Illuminate\Routing\ResourceRegistrar as BaseResourceRegistrar;
use Illuminate\Routing\Route;

class ResourceRegistrar extends BaseResourceRegistrar
{
    protected $resourceDefaults = ['index', 'store', 'show', 'update', 'destroy', 'move', 'count'];

    public function getResourceWildcard($value): string
    {
        return 'id';
    }

    protected function addResourceMove(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name) . '/{' . $base . '}' . '/move/{direction}';

        $action = $this->getResourceAction($name, $controller, 'move', $options);

        return $this->router->post($uri, $action);
    }

    protected function addResourceCount(string $name, string $base, string $controller, array $options): Route
    {
        $uri = $this->getResourceUri($name) . '/count';

        $action = $this->getResourceAction($name, $controller, 'count', $options);

        return $this->router->get($uri, $action);
    }


    /**
     * Add the update method for a resourceful route.
     *
     * @param string $name
     * @param string $base
     * @param string $controller
     * @param array $options
     * @return Route
     */
    protected function addResourceUpdate($name, $base, $controller, $options): Route
    {
        $name = $this->getShallowName($name, $options);

        $uri = $this->getResourceUri($name) . '/{' . $base . '}';

        $action = $this->getResourceAction($name, $controller, 'update', $options);

        return $this->router->put($uri, $action);
    }


    /**
     * Add the update method for a resourceful route.
     *
     * @param string $name
     * @param string $base
     * @param string $controller
     * @param array $options
     * @return Route
     */
    protected function addResourceDestroy($name, $base, $controller, $options): Route
    {
        $name = $this->getShallowName($name, $options);

        $uri = $this->getResourceUri($name) . '/{' . $base . '}';

        $action = $this->getResourceAction($name, $controller, 'delete', $options);

        return $this->router->delete($uri, $action);
    }

    /**
     * Add the show method for a resourceful route.
     *
     * @param string $name
     * @param string $base
     * @param string $controller
     * @param array $options
     * @return Route
     */
    protected function addResourceShow($name, $base, $controller, $options): Route
    {
        $name = $this->getShallowName($name, $options);

        $uri = $this->getResourceUri($name) . '/{' . $base . '}';

        $action = $this->getResourceAction($name, $controller, 'view', $options);

        return $this->router->get($uri, $action);
    }
}
