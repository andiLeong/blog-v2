<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return Order::filters()->paginate(
            $this->page(10)
        );
    }

    public function destroy($ids)
    {
        return Order::whereIn('id',explode(',' , $ids))->delete();
    }
}
