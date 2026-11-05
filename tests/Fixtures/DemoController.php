<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Controllers\Controller;

class DemoController extends Controller
{
    protected function features(): array
    {
        return [
            // string form: no bound constructor arguments
            'static' => StaticFeature::class,
            // array form: leading entries are bound positionally after the
            // arguments supplied at call time
            'greet' => [GreetFeature::class, 'Hello', '?'],
            'greetDefaults' => [GreetFeature::class, 'Hi'],
        ];
    }

    public function callStatic(): string
    {
        return $this->serve('static');
    }

    public function callGreet(string $id): string
    {
        return $this->serve('greet', ['id' => $id]);
    }

    public function callGreetDefaults(string $id): string
    {
        return $this->serve('greetDefaults', ['id' => $id]);
    }

    public function callByClass(): string
    {
        return $this->serve(GreetFeature::class, ['id' => 'x', 'greeting' => 'Yo']);
    }

    public function callMissing(): string
    {
        return $this->serve('nope');
    }

    public function serveObject(object $feature): string
    {
        return $this->serve($feature);
    }
}
