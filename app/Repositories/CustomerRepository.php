<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Traits\ShopTrait;

class CustomerRepository extends Repository
{
    use ShopTrait;
    public static function model()
    {
        return Customer::class;
    }
    public static function storeByRequest(CustomerRequest $request)
    {
        return self::create([
            'created_by' =>auth()->id(),
            'shop_id' => self::mainShop()->id,
            'customer_group_id' => $request->customer_group_id,
            'name' => $request->name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'tax_no' => $request->tax_no,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->post_code,
            'country' => $request->country
        ]);
    }

    public static function updateByRequest(CustomerRequest $request, Customer $customer)
    {
        $update = self::update($customer, [
            'customer_group_id' => $request->customer_group_id,
            'name' => $request->name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'tax_no' => $request->tax_no,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ]);

        return $update;
    }

    public static function search($search)
    {
        $products = self::query()->where('shop_id', self::mainShop()->id)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'Like', "%{$search}%");
                $query->orWhere('email', 'Like', "%{$search}%");
                $query->orWhere('phone_number', 'Like', "%{$search}%");
                $query->orWhere('company_name', 'Like', "%{$search}%");
                $query->orWhere('address', 'Like', "%{$search}%");
            })->get();

        return $products;
    }
}
