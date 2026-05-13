<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerGroupRequest;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Repositories\CustomerGroupRepository;

class CustomerGroupController extends Controller
{
    public function index()
    {
        $customerGroups = CustomerGroupRepository::query()->where('shop_id', $this->mainShop()->id)->orderByDesc('id')->get();
        return view('customerGroup.index', compact('customerGroups'));
    }

    public function store(CustomerGroupRequest $request)
    {
        CustomerGroupRepository::storeByRequest($request);
        return back()->with('success', 'Customer group is created successfully!');
    }

    public function update(CustomerGroupRequest $request, CustomerGroup $customerGroup)
    {
        CustomerGroupRepository::updateByRequest($request, $customerGroup);
        return back()->with('success', 'Customer group is updated successfully!');
    }

    public function delete(CustomerGroup $customerGroup)
    {
        if ($customerGroup->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to delete this customer group!');
        }
        $customerGroup->delete();
        return back()->with('success', 'Customer group is deleted successfully!');
    }

    public function trash()
    {
        $customerGroups = CustomerGroupRepository::query()->onlyTrashed()->where('shop_id', $this->mainShop()->id)->orderByDesc('deleted_at')->get();
        return view('customerGroup.trash', compact('customerGroups'));
    }

    public function restore($id)
    {
        $customerGroup = CustomerGroup::onlyTrashed()->find($id);
        if (!$customerGroup) {
            return back()->with('error', 'Customer group not found');
        }
        if ($customerGroup->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to restore this customer group!');
        }
        $customerGroup->restore();
        return back()->with('success', 'Customer group restored successfully');
    }

    public function forceDelete($id)
    {
        $customerGroup = CustomerGroup::onlyTrashed()->find($id);
        if (!$customerGroup) {
            return back()->with('error', 'Customer group not found');
        }
        if ($customerGroup->shop_id !== $this->mainShop()->id) {
            return back()->with('error', 'You do not have permission to delete this customer group!');
        }
        
        // Check if customer group has any customers
        $customerCount = $customerGroup->customer()->count();
        if ($customerCount > 0) {
            return back()->with('error', 'Cannot delete this customer group! It has ' . $customerCount . ' customer(s) associated with it.');
        }
        
        $customerGroup->forceDelete();
        return back()->with('success', 'Customer group permanently deleted');
    }
}
