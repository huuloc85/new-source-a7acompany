@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Thêm Danh Mục</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.category.home') }}" type="button" class="btn btn-link">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <form action="{{ route('admin.category.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="category-name" class="form-label">
                                        Tên danh mục
                                    </label>
                                    <input type="text" id="category-name"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Tên danh mục"
                                        name="name" value="{{ old('name') }}" required />
                                    @error('name')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            Thêm mới
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
