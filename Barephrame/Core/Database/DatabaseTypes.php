<?php

namespace Barephrame\Core\Database;

enum DatabaseTypes {
    case MYSQL;
    case FIREBIRD;
    case POSTGRES;
}