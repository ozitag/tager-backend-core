<?php

namespace OZiTAG\Tager\Backend\Core\Controllers;

use OZiTAG\Tager\Backend\Core\Traits\MarshalTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, MarshalTrait;

    protected function features()
    {
        return [];
    }

    private function serveFeatureByKey($key, $arguments = [])
    {
        $features = $this->features();

        if (!isset($features[$key])) {
            abort(404, 'Feature not found');
        }

        $feature = $features[$key];

        if (is_string($feature)) {
            return $this->serve($feature, $arguments);
        } else if (is_array($feature)) {
            $featureName = array_shift($feature);

            $reflection = new \ReflectionClass($featureName);
            $constructorParams = $reflection->getConstructor()->getParameters();

            $featureParams = $arguments;
            foreach ($constructorParams as $ind => $param) {
                if ($ind < count($arguments)) continue;
                $featureParams[$param->getName()] = $feature[$ind - count($arguments)];
            }

            return $this->serve($featureName, $featureParams);
        } else {
            return $this->dispatchNow($feature);
        }
    }

    /**
     * Serve the given feature with the given arguments.
     *
     * @param string $feature
     * @param array $arguments
     *
     * @return mixed
     * @throws \ReflectionException
     */
    protected function serve($feature, $arguments = [])
    {
        if (!class_exists($feature)) {
            return $this->serveFeatureByKey($feature, $arguments);
        }

        return $this->dispatchNow($this->marshal($feature, new Collection(), $arguments));
    }
}
