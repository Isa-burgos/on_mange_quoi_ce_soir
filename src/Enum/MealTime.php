<?php

namespace App\Enum;

enum MealTime: string
{
    case PETIT_DEJEUNER = 'petit_dejeuner';
    case DEJEUNER = 'dejeuner';
    case GOUTER = 'gouter';
    case DINER = 'diner';
}
