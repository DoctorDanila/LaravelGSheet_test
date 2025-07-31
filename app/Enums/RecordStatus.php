<?php
namespace App\Enums;

enum RecordStatus: string
{
    case Allowed = 'Allowed';
    case Prohibited = 'Prohibited';
}
