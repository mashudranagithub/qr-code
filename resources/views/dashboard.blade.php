@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="row mt-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">My QR Codes</h2>
            <a href="{{ url('/') }}" class="btn btn-primary">+ Create New</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Preview</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Destination</th>
                                <th>Created</th>
                                <th class="pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($qrCodes as $qr)
                            <tr>
                                <td class="ps-4">
                                    <img src="{{ asset('storage/qrcodes/' . $qr->unique_code . '.png') }}" alt="QR" width="60" class="rounded border">
                                </td>
                                <td><span class="fw-bold">{{ $qr->name }}</span></td>
                                <td><span class="badge bg-secondary text-uppercase">{{ $qr->type }}</span></td>
                                <td><a href="{{ $qr->destination_url }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 250px;">{{ $qr->destination_url }}</a></td>
                                <td>{{ $qr->created_at->format('M d, Y') }}</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('dashboard.analytics', $qr->id) }}" class="btn btn-sm btn-outline-info me-1">Analytics</a>
                                    <a href="{{ asset('storage/qrcodes/' . $qr->unique_code . '.png') }}" download class="btn btn-sm btn-outline-success me-1">Download</a>
                                    
                                    <form action="{{ route('dashboard.qrcodes.destroy', $qr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this QR Code?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    You haven't generated any QR codes yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            {{ $qrCodes->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
