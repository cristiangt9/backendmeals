<?php
// app/Database/Query/Grammars/MySqlGrammar.php

namespace App\Query;

use Illuminate\Database\Query\Grammars\MySqlGrammar as GrammarsMySqlGrammar;

class MySqlGrammar extends GrammarsMySqlGrammar
{
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
}