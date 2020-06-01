<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;
use App\Http\Resources\HistoryResource;
class HistoryController extends Controller
{
    public function history(History $history){
        return new HistoryResource($history);
    }
}
