<?php

namespace App\Enum;

enum WorkoutPlanType: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
}
