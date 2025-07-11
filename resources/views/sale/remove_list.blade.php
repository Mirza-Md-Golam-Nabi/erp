<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Remove Sale List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('msg')
                    <a href="{{ route('sale.list') }}" class="btn btn-sm btn-primary mt-2">Sale List</a>
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
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="collapse"
                                            data-bs-target="#details{{ $sale->id }}">
                                            Details
                                        </button>
                                        <a href="{{ route('sale.restore', $sale->id) }}"
                                            class="btn btn-sm btn-danger">Restore</a>
                                    </td>
                                </tr>
                                <tr class="collapse" id="details{{ $sale->id }}">
                                    <td colspan="5">
                                        <div class="p-3 bg-light rounded">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <h6>Sale Items:</h6>
                                                    <ul class="list-group">
                                                        @php
                                                            $discount = 0;
                                                        @endphp
                                                        @foreach ($sale->sale_items as $item)
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <span>{{ $item->product->name }}</span>
                                                                <span>({{ $item->quantity }} x {{ $item->price }}) -
                                                                    {{ $item->discount }} =
                                                                    {{ $item->quantity * $item->price - $item->discount }}</span>
                                                            </li>
                                                            @php
                                                                $discount += $item->discount;
                                                            @endphp
                                                        @endforeach
                                                    </ul>
                                                    <div class="mt-2 fw-bold text-right">Discount:
                                                        {{ number_format($discount, 2) }}</div>
                                                    <div class="mt-2 fw-bold text-right">Total:
                                                        {{ $sale->total_amount }}</div>
                                                </div>


                                                <div class="col-md-5">
                                                    <div class="p-3 bg-light rounded">
                                                        <h6>Note:</h6>
                                                        <ul class="list-group">
                                                            @forelse ($sale->notes as $note)
                                                                <li class="list-group-item text-wrap text-break w-100">
                                                                    {{ $note->body }}
                                                                </li>
                                                            @empty
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between">
                                                                    There is no note
                                                                </li>
                                                            @endforelse

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                    </td>
                                </tr>
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
