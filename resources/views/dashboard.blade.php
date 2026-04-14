@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="row align-items-center mb-4 mb-md-5 mt-md-4">
    <div class="col-lg-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="h2 fw-bold mb-1 gradient-text">My QR Collections</h1>
                <p class="text-slate-400 mb-0">Manage and track your active digital identities.</p>
            </div>
            <div>
                <a href="{{ url('/') }}" class="btn btn-primary d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-plus me-2"></i>Create New QR
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="glass-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle border-0">
                    <thead class="bg-white bg-opacity-5">
                        <tr>
                            <th class="ps-4 py-4 text-slate-400 x-small text-uppercase fw-bold">Preview</th>
                            <th class="py-4 text-slate-400 x-small text-uppercase fw-bold">Identity Name</th>
                            <th class="py-4 text-slate-400 x-small text-uppercase fw-bold">Type</th>
                            <th class="py-4 text-slate-400 x-small text-uppercase fw-bold">Target</th>
                            <th class="py-4 text-slate-400 x-small text-uppercase fw-bold">Created</th>
                            <th class="pe-4 py-4 text-slate-400 x-small text-uppercase fw-bold text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($qrCodes as $qr)
                        <tr class="border-white border-opacity-10">
                            <td class="ps-4 py-4">
                                <div class="bg-white p-2 rounded-3 d-inline-block shadow-sm">
                                    <img src="{{ $qr->qr_image_url }}" alt="QR" width="45" class="rounded-1">
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-white">{{ $qr->name }}</div>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 text-uppercase x-small" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.2);">
                                    {{ $qr->type }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ $qr->destination_url }}" target="_blank" class="text-slate-400 small text-decoration-none d-inline-block text-truncate" style="max-width: 200px;">
                                    <i class="fa-solid fa-link me-1 x-small opacity-50"></i>{{ $qr->destination_url ?: 'Dynamic Track' }}
                                </a>
                            </td>
                            <td class="text-slate-400 small">{{ $qr->created_at->format('M d, Y') }}</td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-icon btn-warning" onclick="sendToEmail({{ $qr->id }})" title="Email to Me">
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-outline-light" onclick="previewQr('{{ $qr->qr_image_url }}', '{{ $qr->name }}', {{ $qr->id }})" title="Quick View">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <a href="{{ route('dashboard.analytics', $qr->id) }}" class="btn btn-icon btn-info" title="Analytics">
                                        <i class="fa-solid fa-chart-simple"></i>
                                    </a>
                                    <form action="{{ route('dashboard.qrcodes.destroy', $qr->id) }}" method="POST" onsubmit="return confirm('Delete this QR code?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-danger" title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fa-solid fa-layer-group fa-3x text-slate-800 mb-3 d-block"></i>
                                    <p class="text-slate-500">Your digital library is empty.</p>
                                    <a href="{{ url('/') }}" class="btn btn-primary mt-2">Generate Your First QR</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $qrCodes->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-white border-opacity-10 py-4">
                <h5 class="modal-title fw-bold text-white px-2" id="previewTitle">QR Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-5">
                <div class="bg-white p-4 rounded-5 d-inline-block shadow-2xl mb-4" style="border: 8px solid rgba(255,255,255,0.1);">
                    <img id="previewImage" src="" alt="QR Preview" class="img-fluid" style="max-width: 250px;">
                </div>
                <div class="px-4">
                    <p class="text-slate-400 small mb-0">Scan this code with any smartphone camera to verify your digital identity landing page.</p>
                </div>
            </div>
            <div class="modal-footer border-white border-opacity-10 py-3 d-flex justify-content-center gap-2">
                 <a id="modalDownloadLink" href="" download class="btn btn-primary px-4 rounded-pill">
                    <i class="fa-solid fa-download me-2"></i>PNG
                 </a>
                 <button type="button" id="modalEmailButton" class="btn btn-warning px-4 rounded-pill">
                    <i class="fa-solid fa-paper-plane me-2"></i>Email Me
                 </button>
            </div>
        </div>
    </div>
</div>

<script>
    function previewQr(url, name, id) {
        document.getElementById('previewImage').src = url;
        document.getElementById('previewTitle').innerText = name;
        document.getElementById('modalDownloadLink').href = url;
        document.getElementById('modalDownloadLink').download = name.replace(/\s+/g, '_') + '.png';
        
        const emailBtn = document.getElementById('modalEmailButton');
        emailBtn.onclick = () => sendToEmail(id);

        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    }

    async function sendToEmail(id) {
        Swal.fire({
            title: 'Sending QR...',
            text: 'Please wait while we prepare your email.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch(`/dashboard/qrcodes/${id}/send-email`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email Sent!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false,
                    background: 'rgba(15, 23, 42, 0.95)',
                    color: '#fff'
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: error.message || 'Something went wrong!',
                background: 'rgba(15, 23, 42, 0.95)',
                color: '#fff'
            });
        }
    }
</script>

<style>
    .x-small { font-size: 0.7rem; letter-spacing: 0.05em; }
    .table-dark { background: transparent !important; }
    .table-hover tbody tr:hover { background: rgba(255, 255, 255, 0.02) !important; }
    
    .btn-icon {
        width: 38px;
        height: 38px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-color: rgba(255, 255, 255, 0.1);
        color: var(--slate-400);
    }
    
    .btn-icon:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }

    .btn-outline-info.btn-icon:hover { background: rgba(13, 202, 240, 0.1); border-color: #0dcaf0; color: #0dcaf0; }
    .btn-outline-success.btn-icon:hover { background: rgba(25, 135, 84, 0.1); border-color: #198754; color: #198754; }
    .btn-outline-danger.btn-icon:hover { background: rgba(220, 53, 69, 0.1); border-color: #dc3545; color: #dc3545; }

    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
    
    .pagination { border-radius: 16px; overflow: hidden; gap: 5px; }
    .page-item .page-link { 
        background: rgba(255, 255, 255, 0.05); 
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--slate-400); 
        padding: 10px 18px;
        border-radius: 10px !important;
    }
    .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }
    .page-link:hover { background: rgba(255, 255, 255, 0.1); color: #fff; }
</style>
@endsection
