@extends('layouts.admin')
@section('title', 'Data Guru & Tendik')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center py-3"
         style="background:linear-gradient(135deg,#003366,#0056b3);border-radius:.5rem .5rem 0 0;">
        <h5 class="mb-0 text-white fw-bold"><i class="fas fa-chalkboard-teacher me-2"></i>Data Guru & Tendik</h5>
        <a href="{{ url('/admin/guru/create') }}" class="btn btn-warning btn-sm fw-semibold">
            <i class="fas fa-plus me-1"></i>Tambah Guru
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th width="60">Foto</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Kategori</th>
                        <th width="110" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($item->foto ?? null)
                                <img src="{{ asset('storage/' . $item->foto) }}"
                                     style="height:45px;width:45px;object-fit:cover;border-radius:50%;" class="shadow-sm border">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                                     style="height:45px;width:45px;font-size:.8rem;">
                                    {{ strtoupper(substr($item->nama ?? 'G', 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $item->nama ?? '-' }}</td>
                        <td>{{ $item->jabatan ?? '-' }}</td>
                        <td>
                             @if(($item->kategori ?? null) === 'kelas')
                                 <span class="badge bg-primary">Guru Kelas</span>
                             @elseif(($item->kategori ?? null) === 'mapel')
                                 <span class="badge bg-success">Guru Mapel</span>
                             @elseif(($item->kategori ?? null) === 'pendamping')
                                 <span class="badge bg-warning text-dark">Guru Pendamping</span>
                             @elseif(($item->kategori ?? null) === 'tendik')
                                 <span class="badge bg-info text-dark">Tenaga Kependidikan</span>
                             @else
                                 <span class="badge bg-secondary">-</span>
                             @endif
                         </td>
                        <td class="text-center">
                            <a href="{{ route('admin.guru.edit', $item->id) }}"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ url('admin.guru.destroy', $item->id) }}" method="POST"
                                  class="d-inline form-delete-confirm" data-label="guru '{{ addslashes($item->nama ?? '') }}'">
                                {!! csrf_field() !!} <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="99" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                            Belum ada data guru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection