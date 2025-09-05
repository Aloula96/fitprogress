<?php

namespace App\Enum;

enum GoalType: string
{
    case WEIGHT_LOSS = 'perte_de_poids';
    case MUSCLE_GAIN = 'prise_de_masse';
    case RESTART = 'reprise_du_sport';
}
