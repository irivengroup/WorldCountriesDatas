<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;
use Iriven\WorldDatasets\Application\WorldDatasets;


interface Arrayable
{
    /**
     * @return array<mixed>
     */
    public function toArray(): array;
}
