<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Feature;

use OZiTAG\Tager\Backend\Core\Tests\Fixtures\DemoController;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\GreetFeature;
use OZiTAG\Tager\Backend\Core\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

/**
 * FeatureDispatcherTrait::serveFeatureByKey() does positional-to-named
 * argument mapping by hand. Every CrudController route goes through it, so
 * the index arithmetic is pinned here.
 */
class FeatureDispatcherTest extends TestCase
{
    private DemoController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new DemoController();
    }

    #[Test]
    public function it_serves_a_feature_registered_in_string_form(): void
    {
        $this->assertSame('static', $this->controller->callStatic());
    }

    #[Test]
    public function it_serves_a_feature_by_its_class_name_with_named_arguments(): void
    {
        $this->assertSame('Yo x!', $this->controller->callByClass());
    }

    #[Test]
    public function it_binds_array_form_arguments_after_the_call_time_arguments(): void
    {
        // features()['greet'] = [GreetFeature::class, 'Hello', '?']
        // serve('greet', ['id' => 'Bob']) => id=Bob, greeting=Hello, suffix=?
        $this->assertSame('Hello Bob?', $this->controller->callGreet('Bob'));
    }

    #[Test]
    public function it_leaves_unbound_constructor_parameters_on_their_defaults(): void
    {
        // features()['greetDefaults'] = [GreetFeature::class, 'Hi'] - suffix unbound
        $this->assertSame('Hi Ann!', $this->controller->callGreetDefaults('Ann'));
    }

    #[Test]
    public function it_throws_when_the_feature_key_is_not_registered(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Feature Not Found');

        $this->controller->callMissing();
    }

    #[Test]
    public function it_dispatches_an_already_instantiated_feature(): void
    {
        $controller = new DemoController();

        $this->assertSame('Hey z.', $controller->serveObject(new GreetFeature('z', 'Hey', '.')));
    }
}
