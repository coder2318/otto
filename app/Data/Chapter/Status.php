<?php

namespace App\Data\Chapter;

enum Status: string
{
    case PUBLISHED = 'published';
    case DRAFT = 'draft';
    case UNDONE = 'undone';
}
