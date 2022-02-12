<?php

namespace OZiTAG\Tager\Backend\Core\Traits;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use RuntimeException;

trait FeatureDispatcherTrait
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, UserAccess;

    protected function features(): array
    {
        return [];
    }

    private function serveFeatureByKey(string $key, array $arguments = []): mixed
    {
        $features = $this->features();

        $feature = $features[$key] ?? null;

        if (!$feature) {
            throw new RuntimeException('Feature Not Found');
        }

        if (is_string($feature)) {
            return $this->serve($feature, $arguments);
        }

        $featureName = array_shift($feature);

        $reflection = new \ReflectionClass($featureName);
        $constructorParams = $reflection->getConstructor()->getParameters();

        $featureParams = $arguments;
        foreach ($constructorParams as $ind => $param) {
            if ($ind < count($arguments)) continue;
            $featureParams[$param->getName()] = $feature[$ind - count($arguments)];
        }

        return $this->serve($featureName, $featureParams);
    }

    /**
     * @param string|object $feature
     * @param array $arguments
     * @return mixed
     */
    protected function serve(string|object $feature, array $arguments = []): mixed
    {
        if (is_object($feature)) {
            return $this->dispatchNow($feature);
        }

        if (!class_exists($feature)) {
            return $this->serveFeatureByKey($feature, $arguments);
        }

        return $this->dispatchNow(
            new $feature(...$arguments)
        );
    }
}
