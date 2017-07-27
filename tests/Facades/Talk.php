<?php

namespace Eyaylagul\Talk\Tests\Facades;

use GrahamCampbell\TestBenchCore\FacadeTrait;
use Eyaylagul\Talk\Tests\TestCase;

/**
 * This is the Talk facade test class.
 */
class Talk extends TestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'talk';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return \Eyaylagul\Talk\Facades\Talk::class;
    }

    /**
     * Get the facade root.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return \Eyaylagul\Talk\Talk::class;
    }
}
