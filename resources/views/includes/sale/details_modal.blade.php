<div class="modal fade" id="details{{ $sale->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $sale->customer->name }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mx-3">
                <div class="row fw-bold mb-2">
                    <div class="col-6">Product Name</div>
                    <div class="col-3 text-center">Price</div>
                    <div class="col-3 text-right">Subtotal</div>
                </div>
                <hr>
                @php
                    $discount = 0;
                @endphp
                @foreach ($sale->sale_items as $item)
                    <div class="row py-2 {{ $loop->even ? 'bg-secondary bg-opacity-25' : '' }}">
                        <div class="col-6">{{ $item->product->name }}</div>
                        <div class="col-3 text-center">({{ $item->quantity }} x {{ $item->price }}) -
                            {{ $item->discount }}</div>
                        <div class="col-3 text-right">
                            {{ number_format($item->quantity * $item->price - $item->discount, 2) }}</div>
                    </div>
                    @php
                        $discount += $item->discount;
                    @endphp
                @endforeach
                <div class="mt-2 fw-bold text-right">Total:
                    {{ $sale->total_amount }}<br>
                    <small class="text-muted">
                        (Discount: {{ number_format($discount, 2) }})
                    </small>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





{{--
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
                                <li class="list-group-item d-flex justify-content-between">
                                    There is no note
                                </li>
                            @endforelse

                        </ul>
                    </div>
                </div>
            </div>
    </td>
</tr> --}}
