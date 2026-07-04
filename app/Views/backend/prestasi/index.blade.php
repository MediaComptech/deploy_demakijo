@extends('layouts.admin')
@section('title', 'Data Prestasi')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center py-3"
         style="background:linear-gradient(135deg,#003366,#0056b3);border-radius:.5rem .5rem 0 0;">
        <h5 class="mb-0 text-white fw-bold"><i class="fas fa-trophy me-2"></i>Data Prestasi</h5>
        <a href="{{ url('/admin/prestasi/create') }}" class="btn btn-light btn-sm fw-semibold">
            <i class="fas fa-plus me-1"></i>Tambah Prestasi
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th width="60">Foto</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tingkat</th>
                        <th>Tanggal</th>
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
                                     style="height:45px;width:45px;object-fit:cover;border-radius:6px;" class="shadow-sm">
                            @else
                                <div class="rounded bg-warning d-flex align-items-center justify-content-center"
                                     style="height:45px;width:45px;">
                                    <i class="fas fa-trophy text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $item->judul ?? '-' }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $item->kategori ?? 'Lainnya' }}</span>
                        </td>
                        <td>
                            @php
                                $tingkat = strtolower($item->tingkat ?? '');
                                $badgeClass = match(true) {
                                    str_contains($tingkat, 'nasional') => 'bg-danger',
                                    str_contains($tingkat, 'provinsi') => 'bg-warning text-dark',
                                    str_contains($tingkat, 'kabupaten') || str_contains($tingkat, 'kota') => 'bg-info text-dark',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $item->tingkat ?? '-' }}</span>
                        </td>
                        <td><span class="text-muted small">{{ $item->tanggal ?? '-' }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.prestasi.edit', $item->id) }}"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ url('admin.prestasi.destroy', $item->id) }}" method="POST"
                                  class="d-inline form-delete-confirm" data-label="prestasi '{{ addslashes($item->judul ?? '') }}'">
                                {!! csrf_field() !!} <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="99" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                            Belum ada data prestasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection