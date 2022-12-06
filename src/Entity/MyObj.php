<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Color;
use Symfony\Component\Serializer\Annotation\Groups;

class MyObj
{
    public string $id;

    #[Groups(['test'])]
    public string $title;

    #[Groups(['test'])]
    public Color $color;

    public function __construct(string $id, string $title, Color $color)
    {
        $this->id = $id;
        $this->title = $title;
        $this->color = $color;
    }
}
