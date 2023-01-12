<?php

namespace App\Config;

enum PhoneType: string
{
    case Home = 'home';
    case Cell = 'cell';
    case Fax = 'fax';
}
