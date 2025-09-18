<?php

namespace App\Enums;

enum Subjects: string
{
    case ENGLISH_LANGUAGE = 'ENGLISH LANGUAGE';
    case MATHEMATICS = 'MATHEMATICS';
    case SCIENCE = 'SCIENCE';
    case COMPUTING = 'COMPUTING';
    case SOCIAL_STUDIES = 'SOCIAL STUDIES';

    case ICT = 'ICT';
    case RME = 'RME';

    case CREATIVE_ART = 'CREATIVE ART & DESIGN';
    case ASANTE_TWI  = 'ASANTE TWI';
    case FRENCH = 'FRENCH LANGUAGE';
    case CAREER_TECHNOLOGY = 'CAREER TECHNOLOGY';

    case HISTORY = 'HISTORY';

    case LITERACY = 'LITERACY';

    case NUMERACY = 'NUMERACY';

    case LANGUAGE_LITERACY = 'LANGUAGE & LITERACY';

    case OWOP = 'OWOP';


 public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }


}
