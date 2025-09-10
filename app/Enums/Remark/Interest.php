<?php

namespace App\Enum\Remark;

enum Interest: string
{

    // Academic Interests
    case Science = 'science';
    case Mathematics = 'mathematics';
    case Literature = 'literature';
    case Technology = 'technology';
    case Arts = 'arts';

    // Extracurricular Interests
    case Sports = 'sports';
    case Music = 'music';
    case Drama = 'drama';
    case Debate = 'debate';
    case CommunityService = 'community_service';
}
