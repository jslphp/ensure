<?php

namespace Jsl\Ensure;

use Jsl\Ensure\Validators\Registry;

class EnsureFactory
{
    /**
     * @var Registry
     */
    protected Registry $registry;


    public function __construct()
    {
        $this->registry = new Registry;
        $this->registry->addMany(require __DIR__ . '/Validators/defaults.php');
    }


    /**
     * Create a new Ensure instance
     *
     * @param array $data
     * @param array $rules
     *
     * @return Ensure
     */
    public function create(array $data, array $rules = []): Ensure
    {
        return new Ensure($this->registry, $data, $rules);
    }
}
