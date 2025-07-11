<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('msg')
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Price</th>
                                <th scope="col" class="text-center">Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="collapse"
                                            data-bs-target="#note{{ $product->id }}">
                                            View
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#add_note{{ $product->id }}">
                                            Add Note
                                        </button>
                                    </td>
                                </tr>
                                <tr class="collapse" id="note{{ $product->id }}">
                                    <td colspan="4">
                                        <div class="p-3 bg-light rounded">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <h6>All Notes:</h6>
                                                    <ul class="list-group">
                                                        @forelse ($product->notes as $note)
                                                            <li class="list-group-item text-wrap text-break w-100">
                                                                <b>{{ $note->created_at }}</b> - {{ $note->body }}
                                                            </li>
                                                        @empty
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                There is no note
                                                            </li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                    </td>
                                </tr>
                                @include('includes.product_modal')
                            @empty
                                <tr>
                                    <td colspan="4">There is no data</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
