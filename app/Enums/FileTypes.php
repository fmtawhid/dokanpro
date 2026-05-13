<?php

namespace App\Enums;

enum FileTypes: string
{
    case DOCUMENT = 'Document';
    case IMAGE = 'Image';
    case AUDIO = 'Audio';
    case VIDEO = 'Video';
}
