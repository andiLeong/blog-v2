<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'customer' => 'nullable|string',
            'country' => 'nullable|string',
            'number' => 'nullable|string',
            'latest' => 'nullable',
            'direction' => 'nullable|string',
            'order_by' => 'nullable|string',
        ]);

        return Order::filters($data)->paginate(
            $this->page(10)
        );
    }

    public function destroy($ids)
    {
        return Order::whereIn('id',explode(',' , $ids))->delete();
    }
}
