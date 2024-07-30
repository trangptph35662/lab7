@extends('admin.master')

@section('title')
    đơn hàng
@endsection

@section('content')
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    <form action="{{ route('orders.update', $order) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-4">
                <h5>Tổng tiền: {{ number_format($order->total_amount) }}</h5>
                <ul>
                    <li>{{ $order->customer->name }} </li>
                    <li>{{ $order->customer->email }} </li>
                    <li>{{ $order->customer->address }} </li>
                    <li>{{ $order->customer->phone }} </li>
                </ul>
            </div>
            <div class="col-md-8">
                <h5>Danh sách sản phẩm</h5>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity(số lượng bán)</th>
                        </tr>
                        @foreach ($order->details as $detail)
                            <tr>
                                <td> {{ $detail->name }} </td>
                                <td>
                                    {{ number_format($detail->price) }}
                                </td>
                                <td>
                                    <input type="hidden" name="order_details[{{ $detail->id }}][price]"
                                        value="{{ $detail->pivot->price }}">
                                    <input type="number" class="form-control"
                                        name="order_details[{{ $detail->id }}][qty]" value="{{ $detail->pivot->qty }}">
                                    @error("order_details.$detail->id.qty")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

        </div>
        <button type="submit" class="btn btn-info">Submit</button>
    </form>
@endsection
