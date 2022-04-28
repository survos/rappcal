<?php

namespace App\DTO;

class Story {
    public function __construct(
        public string $url,
        public ?string $html=null,
        public ?string $headline=null,
        public ?string $description=null,
        public ?string $author=null,
        public ?string $date=null,
        public ?string $image=null
    ) {


    }
}
