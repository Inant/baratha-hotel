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
                <div class="col-10">
                    <form action="{{ route('kamar.index') }}">
                        <div class="row justify-content-end">
                            <div class="col-5">
                                <input name="keyword" class="form-control" placeholder="Cari kamar..." type="text" value="{{Request::get('keyword')}}">
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
            <div class="row mt-3">
                <div class="col-12">
                @foreach ($kategori as $item)
                    <?php 
                        $selected = isset($_GET['id_kategori']) && $_GET['id_kategori']==$item->id ? "danger" : "success";
                    ?>
                    <a href="{{url('master-kamar/kamar?id_kategori='.$item->id)}}" class="btn btn-{{$selected}} btn-sm">{{$item->kategori_kamar}}</a>
                @endforeach
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
                    <th scope="col" class="sort" data-sort="budget">Nomor Kamar</th>
                    <th scope="col" class="sort" data-sort="name">Kategori</th>
                    <th scope="col" class="sort" data-sort="name">Status</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list">
                    @php
                    $page = Request::get('page');
                    $no = !$page || $page == 1 ? 1 : ($page - 1) * 10 + 1;
                    @endphp
                    @foreach ($kamar as $value)
                        <tr>
                            <td>{{$no}}</td>
                            <td>{{$value->no_kamar}}</td>
                            <td>{{$value->kategori->kategori_kamar}}</td>
                            <td>
                              @if ($value->status == 'Tersedia')
                                <span class="badge badge-success">{{$value->status}}</span>
                              @elseif($value->status == 'Tidak Tersedia')
                                <span class="badge badge-warning">{{$value->status}}</span>
                              @else
                                <span class="badge badge-secondary">{{$value->status}}</span>
                              @endif
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route('kamar.edit', $value->id) }}">Edit</a>
                                {{-- <form action="{{ route('kamar.destroy', $value->id) }}" method="post">
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
            </div>
         </div>
    </div>
</div>
@endsection