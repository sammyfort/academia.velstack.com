<?php

namespace App\Enum\Remark;

enum Attitude: string
{
    // Positive Attitudes
    case Motivated = 'motivated';
    case Cooperative = 'cooperative';
    case Respectful = 'respectful';
    case Curious = 'curious';
    case Responsible = 'responsible';

    // Negative Attitudes
    case Apathetic = 'apathetic';
    case Disruptive = 'disruptive';
    case Defiant = 'defiant';
    case Unmotivated = 'unmotivated';
    case Disrespectful = 'disrespectful';
}
