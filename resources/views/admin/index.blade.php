@extends('admin.master')
@section('title')
    Danh sachs
@endsection
@section('content')
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif


    <div class="table-responsive">
        <a href="{{route('orders.create')}}" class="btn btn-info">Thêm mới</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>

                    <th>Customer info</th>

                    <th>OrderDetails</th>
                    <th>total_amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->id }} </td>
                        <td>
                            <ul>
                                <li>{{ $item->customer->name }} </li>
                                <li>{{ $item->customer->email }} </li>
                                <li>{{ $item->customer->address }} </li>
                                <li>{{ $item->customer->phone }} </li>
                            </ul>
                        </td>
                        <td>
                            @foreach ($item->details as $detail)
                                <h5>Name: {{ $detail->name }}</h5>
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
                            {{ number_format( $item->total_amount) }}
                        </td>
                        <td>
                            <a href="{{ route('orders.edit', $item->id) }}" class="btn btn-warning" > Sửa</a>
                            <a href="{{ route('orders.show', $item->id) }}" class="btn btn-warning" > Show</a>
                            <form action="{{ route('orders.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('bạn chắc chắn muốn xoá?')" class="btn btn-danger" >xoá</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $data->links() }}
    </div>
@endsection
