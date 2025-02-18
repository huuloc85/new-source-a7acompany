@extends('layouts.'.$layout)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Danh Sách Nguyên Liệu</h4></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="text-center text-uppercase">
                            <tr>
                                <th>STT</th>
                                <th>Mã Nguyên Liệu</th>
                                <th>Tổng Số Lượng</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($materials as $index => $material)
                                <tr>
                                    <td class="fw-bold">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ $material['material'] }}</td>
                                    <td>
                                        {{ number_format($material['total_quantity'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
