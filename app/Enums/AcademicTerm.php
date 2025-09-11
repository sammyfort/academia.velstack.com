<?php

namespace App\Enums;

enum AcademicTerm: string
{
    case TERM_2025_2026_TERM_THREE= '2025/26 TERM THREE';
    case TERM_2025_2026_TERM_TWO = '2025/26 TERM TWO';
    case TERM_2025_2026_TERM_ONE = '2025/26 TERM ONE';

    case TERM_2024_2025_TERM_THREE = '2024/25 TERM THREE';
    case TERM_2024_2025_TERM_TWO = '2024/25 TERM TWO';
    case TERM_2024_2025_TERM_ONE = '2024/25 TERM ONE';

    case TERM_2023_2024_TERM_THREE = '2023/24 TERM THREE';
    case TERM_2023_2024_TERM_TWO = '2023/24 TERM TWO';

    case TERM_2023_2024_TERM_ONE = '2023/24 TERM ONE';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

}
