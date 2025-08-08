<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testWarehouse6()
    {
        // إعادة التوجيه بدون cache
        return redirect('/warehouses/6')->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
