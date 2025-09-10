<?php

namespace App\Enum\Remark;

enum Remark: string
{

    // Positive Remarks
    case Excellent = 'excellent';
    case VeryGood = 'very_good';
    case Good = 'good';
    case Satisfactory = 'satisfactory';
    case Improving = 'improving';

    // Needs Improvement
    case NeedsImprovement = 'needs_improvement';
    case Inconsistent = 'inconsistent';
    case Poor = 'poor';
    case Unfocused = 'unfocused';
    case Disruptive = 'disruptive';
}
