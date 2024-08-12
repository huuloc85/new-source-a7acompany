@extends('master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách Nguyên Liệu</h4>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên Nguyên Liệu</th>
                                <th>Tổng Số Lượng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $index => $material)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $material->name }}</td>
                                    <td>{{ $material->total_quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
