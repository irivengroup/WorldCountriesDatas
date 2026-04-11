<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;

interface Arrayable
{
    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
