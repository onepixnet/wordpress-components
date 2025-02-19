<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

use OnePix\WordPressContracts\Action;
use OnePix\WordPressContracts\Filter;
use OnePix\WordPressContracts\Hook;

final class HooksManager implements \OnePix\WordPressContracts\HooksManager
{
    /**
     * @param  Hook[]  $hooks
     */
    public function __construct(
        private readonly array $hooks,
        private readonly \OnePix\WordPressContracts\ActionsRegistrar $actionsRegistrar,
        private readonly \OnePix\WordPressContracts\FiltersRegistrar $filtersRegistrar
    ) {
    }

    public function apply(): void
    {
        foreach ($this->hooks as $hook) {
            $func = match (true) {
                ($hook instanceof Action) => $this->actionsRegistrar->add(...),
                ($hook instanceof Filter) => $this->filtersRegistrar->add(...),
                default => hasFunctionReturnValue($hook->getCallback()) ?
                    $this->filtersRegistrar->add(...) :
                    $this->actionsRegistrar->add(...)
            };

            $func($hook->getHook(), $hook->getCallback(), $hook->getPriority(), $hook->getAcceptedArgs());
        }
    }
}
