<?php

namespace App\Data\Story;

enum Status: string
{
    case PUBLISHED = 'published';
    case DRAFT = 'draft';
}
