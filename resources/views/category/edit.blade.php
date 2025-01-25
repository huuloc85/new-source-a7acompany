@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Cập Nhật Danh Mục</h4>
                </div>
                <div class="card-body">
                    <a
                        href="{{ route('admin.category.home') }}"
                        type="button"
                        class="btn btn-link"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <form
                        action="{{ route('admin.category.update', $category->id) }}"
                        method="post"
                    >
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label
                                        for="category-name"
                                        class="form-label"
                                    >
                                        Tên danh mục
                                    </label>
                                    <input
                                        type="text"
                                        id="category-name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Tên danh mục"
                                        name="name"
                                        value="{{ old('name') ?? $category->name }}"
                                        required
                                    />
                                    @error('name')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
