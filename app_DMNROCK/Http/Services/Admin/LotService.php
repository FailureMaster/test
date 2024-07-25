<?php

namespace App\Http\Services\Admin;

use App\Models\CoinPair;

class LotService {
    public function getPairData()
    {
        $query = CoinPair::select('id', 'type', 'listed_market_name','symbol')
            ->filter([ 'type' ])
            ->searchable(['listed_market_name', 'symbol'])
            ->with('marketData')
            ->orderBy('id', 'desc');

        return $query->paginate(getPaginate(10));
    }
}