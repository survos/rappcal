<?php

namespace App\DTO;

class Story {
    public function __construct(
        public string $url,
        public string $html,
        public ?string $headline=null,
        public ?string $image=null
    ) {


    }
}
