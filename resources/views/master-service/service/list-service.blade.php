@extends('common/template')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <div class="row align-items-center">
                <div class="col-2">
                  <h3 class="mb-0">{{$pageInfo}}</h3>
                </div>
                <div class="col-6 offset-4">
                    <form action="{{ route('service.index') }}">
                        <div class="row">
                            <div class="col-5">
                                <input name="keyword" class="form-control" placeholder="Cari service..." type="text" value="{{Request::get('keyword')}}">
                            </div>
                            <div class="col-5">
                                <select name="kategori-service" id="kategori-service" class="form-control select2" width="100%">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategori as $item)
                                        <option value="{{$item->id}}" {{Request::get('kategori-service') == $item->id ? 'selected' : ''}} > {{$item->kategori}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
              </div>
            </div>
            @if (session('status'))
                <br>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">#</th>
                    <th scope="col" class="sort" data-sort="budget">Service</th>
                    <th scope="col" class="sort" data-sort="name">Kategori</th>
                    <th scope="col" class="sort" data-sort="name">Icon</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    @php
                    $page = Request::get('page');
                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                    @endphp
                    @foreach ($service as $value)
                        <tr>
                            <td>{{$no}}</td>
                            <td>{{$value->service}}</td>
                            <td>{{$value->kategori->kategori}}</td>
                            <td>{{$value->icon}}</td>
                            <td class="text-right">
                                <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route('service.edit', $value->id) }}">Edit</a>
                                {{-- <form action="{{ route('service.destroy', $value->id) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="button" class="mr-1 dropdown-item" onclick="confirm('{{ __("Apakah anda yakin ingin menghapus?") }}') ? this.parentElement.submit() : ''">Hapus</button>
                                </form> --}}
                                </div>
                                </div>
                            </td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                  </tr>
                </tbody>
            </table>
            {{$service->appends(Request::all())->links()}}
            </div>
         </div>
    </div>
</div>
@endsection