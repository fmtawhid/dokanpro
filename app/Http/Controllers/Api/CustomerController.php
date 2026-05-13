<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerGroupResource;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Repositories\CustomerGroupRepository;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function search()
    {
        $request = request();
        $search = $request->search;

        $customers = CustomerRepository::search($search);
        return $this->json('Search Customer', [
            'customers' => CustomerResource::collection($customers),
        ]);
    }
    public function store(CustomerRequest $request)
    {
        $customer = CustomerRepository::storeByRequest($request);
        return $this->json('Customer successfully stored', [
            'customer' => CustomerResource::make($customer),
        ]);
    }
    public function details(Customer $customer)
    {
        return $this->json('Customer Details', [
            'customers' => CustomerResource::make($customer),
        ]);
    }
    public function customerGroups()
    {
        $customerGroups = CustomerGroupRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return $this->json('customer groups', [
            'customerGroups' => CustomerGroupResource::collection($customerGroups),
        ]);
    }
}
