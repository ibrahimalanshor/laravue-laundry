<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};

use App\Models\Customer;
use App\Repositories\Customer\CustomerRepository;
use App\Http\Resources\Customer\{CustomerCollection, CustomerResource};
use App\Http\Requests\Customer\{CreateCustomerRequest, UpdateCustomerRequest};

class CustomerController extends Controller
{

    public function index(CustomerRepository $customerRepo, Request $request): CustomerCollection
    {
        $customers = $customerRepo->get($request->take, $request->name, $request->sort_by, $request->order_by ?? 'asc');

        return new CustomerCollection($customers);
    }

    public function store(CreateCustomerRequest $request): CustomerResource
    {
        $customer = Customer::create($request->all());

        return new CustomerResource($customer);
    }

    public function show(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource
    {
        $customer->update($request->all());

        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json(['message' => 'Customer dihapus']);
    }
}
