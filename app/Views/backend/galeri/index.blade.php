@extends('layouts.admin')
@section('title', 'Galeri Foto')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center py-3"
         style="background:linear-gradient(135deg,#003366,#0056b3);border-radius:.5rem .5rem 0 0;">
        <h5 class="mb-0 text-white fw-bold"><i class="fas fa-images me-2"></i>Data Galeri Foto</h5>
        <a href="{{ url('/admin/galeri/create') }}" class="btn btn-warning btn-sm fw-semibold">
            <i class="fas fa-cloud-upload-alt me-1"></i>Upload Foto
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="40">No</th>
                        <th width="90">Foto</th>
                        <th>Judul</th>
                        <th>Album</th>
                        <th width="80" class="text-center">Sumber</th>
                        <th width="110" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($data as $item)
                <tr>
                    <td class="text-muted small">{{ $loop->iteration }}</td>
                    <td>
                        @if($item->file)
                            <img src="{{ asset('storage/' . $item->file) }}"
                                 style="height:52px;width:72px;object-fit:cover;border-radius:6px;" class="shadow-sm"
                                 loading="lazy">
                        @elseif(!empty($item->url_gdrive))
                            <div class="rounded d-flex flex-column align-items-center justify-content-center bg-light border"
                                 style="height:52px;width:72px;" title="Google Drive">
                                <i class="fab fa-google-drive text-primary fa-lg"></i>
                                <span style="font-size:9px;" class="text-muted mt-1">GDrive</span>
                            </div>
                        @else
                            <div class="rounded d-flex align-items-center justify-content-center bg-light border"
                                 style="height:52px;width:72px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $item->judul }}</div>
                        @if(!empty($item->keterangan))
                            <small class="text-muted">{{ Str::limit($item->keterangan, 50) }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-dark">{{ $item->album->nama ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        @if(!empty($item->url_gdrive))
                            <a href="{{ $item->url_gdrive }}" target="_blank" rel="noopener noreferrer"
                               class="badge bg-primary text-decoration-none" title="Lihat di Google Drive">
                                <i class="fab fa-google-drive me-1"></i>GDrive
                            </a>
                        @elseif($item->file)
                            <span class="badge bg-success">
                                <i class="fas fa-server me-1"></i>Upload
                            </span>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ url('/admin/galeri/' . $item->id . '/edit') }}"
                           class="btn btn-warning btn-sm me-1" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ url('/admin/galeri/' . $item->id . '/delete') }}" method="POST"
                              class="d-inline form-delete-confirm"
                              data-label="foto '{{ addslashes($item->judul ?? '') }}'">
                            {!! csrf_field() !!}
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="btn btn-danger btn-sm" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                        Belum ada foto di galeri.
                        <br>
                        <a href="{{ url('/admin/galeri/create') }}" class="btn btn-primary btn-sm mt-3">
                            <i class="fas fa-cloud-upload-alt me-1"></i>Upload Foto Pertama
                        </a>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection