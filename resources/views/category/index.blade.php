@extends('master')
@section('content')
<style>
    .form-control {
        border: 1px solid #d2d6da !important;
        padding-left: 10px;
    }

    .active>.page-link {
        color: white !important
    }

    .href {
        color: blue !important;
    }

    .search-role {
        height: 37px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Danh Sách Danh Mục</h4>
                </div>
            </div>
            <div class="p-4 pb-0 d-flex">
                <a href="{{ route('admin.category.add') }}" type="button" class='btn btn-success'>Thêm Danh Mục</a>
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <form action="">
                        <div class="input-group input-group-outline">
                            <input name="key" value="{{ request()->key }}" type="text"
                                class="form-control search-role" placeholder="Nhận từ khóa...">
                            <button type="submit" class='btn btn-primary'>Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="ps-4 d-flex">
                Tổng : {{ count($categories) }}/{{ $total }}
            </div>
            <div class="px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-hover ">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STT</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-3">
                                    Tên danh mục</th>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 col-3">
                                    Ngày tạo</th>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 col-3">
                                    Ngày cập nhật</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $key => $category)
                            <tr>
                                <td>
                                    <div class="d-flex px-3 py-1">
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-3">{{ $category->name }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $category->formatTimeDMY($category->created_at) }}
                                    </p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $category->formatTimeDMY($category->updated_at) }}
                                    </p>
                                </td>
                                <td class="align-middle">
                                    <form action="{{ route('admin.category.delete', $category->id) }}"
                                        method="post" class="mb-0">
                                        @method('DELETE')
                                        @csrf
                                        <a href="{{ route('admin.category.edit', $category->id) }}"
                                            class="btn btn-primary mb-0">Cập nhật</a>
                                        <button
                                            onclick="return confirm('Hành động này không thể khôi phục! Bạn có chắc muốn xóa danh mục này không?');"
                                            class='btn btn-danger mb-0' type="submit">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if ($total == 0)
                            <tr>
                                <td colspan="6" class="text-center pt-4">Hiện tại chưa có danh mục nào. Vui lòng
                                    <a class="href" href="{{ route('admin.category.add') }}">Thêm danh mục</a>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                        <div>
                            {{ $categories->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection