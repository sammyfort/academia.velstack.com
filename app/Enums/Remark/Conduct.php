<?php

namespace App\Enum\Remark;

enum Conduct: string
{

    // Positive Conduct
    case Punctual = 'punctual';
    case Honest = 'honest';
    case Obedient = 'obedient';
    case Helpful = 'helpful';
    case Disciplined = 'disciplined';

    // Negative Conduct
    case Late = 'late';
    case Cheating = 'cheating';
    case Bullying = 'bullying';
    case Disruptive = 'disruptive';
    case Vandalism = 'vandalism';
}
