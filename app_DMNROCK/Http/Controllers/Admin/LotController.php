<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\LotService;
use App\Models\CoinPair;
use Illuminate\Http\Request;

class LotController extends Controller
{
    public function index()
    {
        $pageTitle = "Lot Management";
        $pairs = (new LotService())->getPairData();
        return view('admin.lot.lot-index', compact('pageTitle', 'pairs'));
    }
}
