<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sale List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('sale.list') }}" class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="customer" class="form-label">Customer</label>
                            <input class="form-control" name="customer_name" id="customer">
                        </div>

                        {{-- Date From --}}
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">From</label>
                            <input type="date" class="form-control" name="date_from" id="date_from">
                        </div>

                        {{-- Date To --}}
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">To</label>
                            <input type="date" class="form-control" name="date_to" id="date_to">
                        </div>

                        {{-- Filter Button --}}
                        <div class="col-md-2 d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('sale.list') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                    @include('msg')
                    <a href="{{ route('sale.removed') }}" class="btn btn-sm btn-danger mt-2">Removed Sale</a>
                    <table class="table table-striped mt-2">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col" class="text-center">Sale Date</th>
                                <th scope="col" class="text-right">Total Amount</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td scope="col">{{ $loop->iteration }}</td>
                                    <td>{{ $sale->customer->name }}</td>
                                    <td class="text-center">{{ $sale->sale_date }}</td>
                                    <td class="text-right">{{ $sale->total_amount }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#details{{ $sale->id }}">
                                            Details
                                        </button>
                                        <a href="{{ route('sale.delete', $sale->id) }}"
                                            class="btn btn-sm btn-danger">Remove</a>
                                    </td>
                                </tr>
                                @include('includes.sale.details_modal')
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
