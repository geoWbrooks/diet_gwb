<?php

//src/Components/RandomNumberComponent.php

namespace App\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent('random_number')]
class RandomNumberComponent
{

    use DefaultActionTrait;

    #[LiveAction]
    public function resetMax()
    {
        $this->max = 1000;
    }

    #[LiveProp(writable: true)]
    public int $max = 1000;

    public function getRandomNumber(): int
    {
        return rand(0, $this->max);
    }

}
