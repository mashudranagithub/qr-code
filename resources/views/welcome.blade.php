@extends('layouts.app')
@section('title', 'Free QR Code Generator')

@section('content')
<div class="row pt-5">
    <div class="col-md-7 mb-4">
        <h1 class="fw-bold mb-1">Create Custom QR Codes</h1>
        <p class="text-muted mb-4">Generate and customize your dynamic or static QR codes seamlessly.</p>
        
        <div class="glass-card p-4">
            <form id="qrForm" onsubmit="event.preventDefault(); downloadQr();">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">QR Name (for your dashboard)</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="e.g. Campaign Links" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Content Type</label>
                    <select class="form-select" name="type" id="type" onchange="toggleFields()">
                        <option value="url">URL / Website Link</option>
                        <option value="text">Plain Text</option>
                        <option value="vcard">Virtual Contact (vCard)</option>
                        <option value="bio_link">Contact Profile (Bio-Link)</option>
                    </select>
                </div>

                <!-- URL/Text Field -->
                <div id="standardField" class="mb-4">
                    <label class="form-label fw-bold" id="contentLabel">Destination URL</label>
                    <textarea class="form-control" name="destination_url" id="destination_url" rows="3" placeholder="https://..." oninput="debouncePreview()"></textarea>
                </div>

                <!-- Contact Fields -->
                <div id="contactFields" class="d-none">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" class="form-control" id="contact_name" placeholder="John Doe" oninput="debouncePreview()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Company Name</label>
                            <input type="text" class="form-control" id="contact_company" placeholder="Acme Inc." oninput="debouncePreview()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="contact_email" placeholder="john@example.com" oninput="debouncePreview()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Personal Website</label>
                            <input type="url" class="form-control" id="contact_website" placeholder="https://..." oninput="debouncePreview()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Mobile No</label>
                            <input type="text" class="form-control" id="contact_mobile" placeholder="+123456789" oninput="debouncePreview()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Company Website</label>
                            <input type="url" class="form-control" id="contact_company_website" placeholder="https://company.com" oninput="debouncePreview()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Facebook URL</label>
                            <input type="url" class="form-control" id="contact_facebook" placeholder="https://facebook.com/..." oninput="debouncePreview()">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Youtube URL</label>
                            <input type="url" class="form-control" id="contact_youtube" placeholder="https://youtube.com/..." oninput="debouncePreview()">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Address</label>
                            <textarea class="form-control" id="contact_address" rows="2" placeholder="Street, City, Country" oninput="debouncePreview()"></textarea>
                        </div>
                    </div>
                </div>
                
                <h5 class="fw-bold mb-3">Customization</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Foreground Color</label>
                        <input type="color" class="form-control form-control-color w-100" name="foreground_color" id="foreground_color" value="#000000" onchange="updatePreview()">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Background Color</label>
                        <input type="color" class="form-control form-control-color w-100" name="background_color" id="background_color" value="#ffffff" onchange="updatePreview()">
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    @auth
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                            Save & Download <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none ms-1"></span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary px-4 fw-bold">Login to Save & Download</a>
                    @endauth
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="glass-card p-4 text-center sticky-top" style="top: 2rem;">
            <h5 class="fw-bold text-muted mb-4">Live Preview</h5>
            <div class="bg-white p-4 rounded d-inline-block shadow-sm" style="border: 1px solid #ddd; min-width: 250px; min-height: 250px; display:flex; align-items:center; justify-content:center;">
                <img id="qrPreview" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" class="img-fluid rounded" alt="QR Preview" style="width: 100%; max-width: 280px; opacity: 0.3;">
            </div>
            <p class="text-muted small mt-3">Start typing to see your QR code magically appear.</p>
        </div>
    </div>
</div>

<script>
    let timeout = null;
    
    function toggleFields() {
        const type = document.getElementById('type').value;
        const standard = document.getElementById('standardField');
        const contact = document.getElementById('contactFields');
        const label = document.getElementById('contentLabel');

        if(type === 'vcard' || type === 'bio_link') {
            standard.classList.add('d-none');
            contact.classList.remove('d-none');
        } else {
            standard.classList.remove('d-none');
            contact.classList.add('d-none');
            label.innerText = type === 'url' ? 'Destination URL' : 'Plain Text';
        }
        updatePreview();
    }

    function debouncePreview() {
        clearTimeout(timeout);
        timeout = setTimeout(updatePreview, 500);
    }

    function collectData() {
        const type = document.getElementById('type').value;
        const data = {
            _token: document.querySelector('input[name="_token"]').value,
            name: document.getElementById('name').value || 'Preview',
            type: type,
            foreground_color: document.getElementById('foreground_color').value,
            background_color: document.getElementById('background_color').value,
            is_dynamic: (type === 'bio_link' ? 1 : 0)
        };

        if(type === 'vcard' || type === 'bio_link') {
            data.content_data = {
                name: document.getElementById('contact_name').value,
                company: document.getElementById('contact_company').value,
                email: document.getElementById('contact_email').value,
                mobile: document.getElementById('contact_mobile').value,
                website: document.getElementById('contact_website').value,
                company_website: document.getElementById('contact_company_website').value,
                facebook: document.getElementById('contact_facebook').value,
                youtube: document.getElementById('contact_youtube').value,
                address: document.getElementById('contact_address').value,
            };
        } else {
            data.destination_url = document.getElementById('destination_url').value;
        }

        return data;
    }

    async function updatePreview() {
        const data = collectData();
        // Check if any identifying data is present
        if(data.type !== 'vcard' && data.type !== 'bio_link' && !data.destination_url) return;
        if((data.type === 'vcard' || data.type === 'bio_link') && !data.content_data.name) return;

        try {
            const res = await fetch('{{ url("/app-api/qrcodes/preview") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if(json.data) {
                const img = document.getElementById('qrPreview');
                img.src = json.data;
                img.style.opacity = 1;
            }
        } catch(e) {
            console.error(e);
        }
    }

    async function downloadQr() {
        const spinner = document.getElementById('loadingSpinner');
        spinner.classList.remove('d-none');

        const data = collectData();

        try {
            const res = await fetch('{{ url("/app-api/qrcodes/download") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const json = await res.json();
            
            if(json.download_url) {
                const link = document.createElement('a');
                link.href = json.download_url;
                link.download = data.name + '.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                alert("QR Code saved & downloaded successfully!");
            } else if(json.message) {
                alert(json.message);
            }
        } catch(e) {
            console.error(e);
            alert("Something went wrong!");
        } finally {
            spinner.classList.add('d-none');
        }
    }
</script>
@endsection
