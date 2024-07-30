@extends('admin.master')
@section('title')
    Danh sachs
@endsection
@section('content')
  
    <div class="table-responsive">
       
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>

                    <th>Customer info</th>

                    <th>OrderDetails</th>
                    <th>total_amount</th>
                  
                </tr>
            </thead>
            <tbody>
               
                    <tr>
                        <td>{{ $order->id }} </td>
                        <td>
                            <ul>
                                <li>{{ $order->customer->name }} </li>
                                <li>{{ $order->customer->email }} </li>
                                <li>{{ $order->customer->address }} </li>
                                <li>{{ $order->customer->phone }} </li>
                            </ul>
                        </td>
                        <td>
                            @foreach ($order->details as $detail)
                                <h5>Name: {{ $detail->name }}</h5>
                                <p>Mô tả: {{$detail->decription}} </p>
                                <ul>
                                    <li>Giá: {{ number_format( $detail->pivot->price)}} </li>
                                    <li>Số lượng: {{ $detail->pivot->qty }}</li>
                                    @if ($detail->image && \Storage::exists($detail->image))
                                        <li> 
                                            <img src="{{ \Storage::url($detail->image) }}" width="80px" alt="">
                                        </li>
                                    @endif
                                </ul>
                            @endforeach
                        </td>
                        <td>
                            {{ number_format( $order->total_amount) }}
                        </td>
                        
                    </tr>
               
            </tbody>
        </table>
        <a href="{{route('orders.index')}}" class="btn btn-info">Trở về</a>
    </div>
@endsection
