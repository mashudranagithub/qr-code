@extends('layouts.app')
@section('title', 'Pro vCard QR Generator')

@section('content')
<div class="row align-items-center mb-5 mt-md-4">
    <div class="col-lg-12 text-center mb-3">
        <h1 class="display-4 fw-bold mb-2 gradient-text">Smart vCard Generator</h1>
        <p class="h5 fw-light accent-text mx-auto" style="max-width: 650px;">Digital business cards, perfected for the modern era.</p>
    </div>
</div>

<div class="row g-5">
    <!-- LEFT SIDE: Generator Form -->
    <div class="col-lg-7">
        <div class="glass-card p-4 p-md-5">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3" style="background: rgba(99, 102, 241, 0.1);">
                    <i class="fa-solid fa-wand-sparkles text-primary fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0 text-white">Identity Details</h4>
                    <p class="text-slate-400 small mb-0">Fill your information below</p>
                </div>
            </div>

            <div class="nav-tabs-wrapper mb-4">
                <ul class="nav nav-pills nav-justified p-1 rounded-4" id="qrTabs" role="tablist" style="background: rgba(255,255,255,0.05);">
                    <li class="nav-item">
                        <button class="nav-link active rounded-3 py-2" data-bs-toggle="pill" onclick="setType('vcard')"><i class="fa-solid fa-user-tag me-2"></i>vCard</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-3 py-2" data-bs-toggle="pill" onclick="setType('url')"><i class="fa-solid fa-globe me-2"></i>URL</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-3 py-2" data-bs-toggle="pill" onclick="setType('text')"><i class="fa-solid fa-pen-nib me-2"></i>Text</button>
                    </li>
                </ul>
            </div>

            <form id="qrForm" onsubmit="event.preventDefault(); downloadQr();">
                @csrf
                <input type="hidden" name="type" id="type" value="vcard">
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-slate-400 small text-uppercase tracking-wider mb-2">Campaign Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="John Doe Digital Card" required>
                </div>

                <div id="contactFields" class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label x-small text-uppercase fw-bold text-slate-400 mb-1">Full Name</label>
                        <input type="text" class="form-control" id="contact_name" placeholder="John Doe" oninput="debouncePreview()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label x-small text-uppercase fw-bold text-slate-400 mb-1">Job Title</label>
                        <input type="text" class="form-control" id="contact_title" placeholder="CEO / Designer" oninput="debouncePreview()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label x-small text-uppercase fw-bold text-slate-400 mb-1">Company</label>
                        <input type="text" class="form-control" id="contact_company" placeholder="Acme Inc." oninput="debouncePreview()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label x-small text-uppercase fw-bold text-slate-400 mb-1">Email</label>
                        <input type="email" class="form-control" id="contact_email" placeholder="john@domain.com" oninput="debouncePreview()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label x-small text-uppercase fw-bold text-slate-400 mb-1">Mobile</label>
                        <input type="text" class="form-control" id="contact_mobile" placeholder="+1234..." oninput="debouncePreview()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label x-small text-uppercase fw-bold text-slate-400 mb-1">Website</label>
                        <input type="url" class="form-control" id="contact_website" placeholder="https://..." oninput="debouncePreview()">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label x-small text-uppercase fw-bold text-slate-400 mb-1">Office Address</label>
                        <textarea class="form-control" id="contact_address" rows="1" placeholder="City, Country" oninput="debouncePreview()"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label x-small text-uppercase fw-bold text-slate-400 mb-1"><i class="fab fa-facebook-f me-1"></i> Facebook</label>
                        <input type="url" class="form-control" id="contact_facebook" placeholder="URL" oninput="debouncePreview()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label x-small text-uppercase fw-bold text-slate-400 mb-1"><i class="fab fa-youtube me-1"></i> YouTube</label>
                        <input type="url" class="form-control" id="contact_youtube" placeholder="URL" oninput="debouncePreview()">
                    </div>
                </div>

                <div id="standardField" class="d-none mb-4">
                    <label class="form-label fw-bold text-slate-400 small text-uppercase tracking-wider mb-2" id="contentLabel">Destination</label>
                    <textarea class="form-control" name="destination_url" id="destination_url" rows="3" placeholder="Paste link here..." oninput="debouncePreview()"></textarea>
                </div>

                <div class="row g-3 mb-4 pt-4 border-top border-white border-opacity-10">
                    <div class="col-md-6">
                        <label class="form-label fw-bold x-small text-uppercase text-slate-400 mb-2 d-block">QR Color</label>
                        <div class="d-flex align-items-center px-3 py-2 rounded-4 border border-white border-opacity-10" style="background: rgba(255, 255, 255, 0.05); height: 48px;">
                             <input type="color" class="form-control-color border-0 p-0 me-3" id="foreground_color" value="#000000" onchange="updateHex('fg', this.value); updatePreview()" style="width: 28px; height: 28px; border-radius: 6px; background: transparent; cursor: pointer;">
                             <span class="small font-monospace text-slate-200" id="fg_hex">#000000</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold x-small text-uppercase text-slate-400 mb-2 d-block">Background</label>
                        <div class="d-flex align-items-center px-3 py-2 rounded-4 border border-white border-opacity-10" style="background: rgba(255, 255, 255, 0.05); height: 48px;">
                             <input type="color" class="form-control-color border-0 p-0 me-3" id="background_color" value="#ffffff" onchange="updateHex('bg', this.value); updatePreview()" style="width: 28px; height: 28px; border-radius: 6px; background: transparent; cursor: pointer;">
                             <span class="small font-monospace text-slate-200" id="bg_hex">#FFFFFF</span>
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <label class="form-label fw-bold x-small text-uppercase text-slate-400 mb-2 d-block">Tracking Style</label>
                        <div class="form-check form-switch px-3 py-2 rounded-4 d-flex align-items-center justify-content-between border border-white border-opacity-10" style="background: rgba(255, 255, 255, 0.05); height: 48px;">
                            <label class="form-check-label text-slate-300 small mb-0 ms-0" for="is_dynamic">
                                <i class="fa-solid fa-chart-line text-primary me-2"></i>Dynamic Analytics
                            </label>
                            <input class="form-check-input mt-0" type="checkbox" id="is_dynamic" onchange="updatePreview()" style="width: 36px; height: 18px; cursor: pointer;">
                        </div>
                    </div>
                </div>

                <div class="d-grid pt-2">
                    @auth
                        <button type="submit" class="btn btn-primary">
                            <span id="btnText"><i class="fa-solid fa-cloud-arrow-down me-2"></i>GENERATE & DOWNLOAD</span>
                            <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none ms-2"></span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fa-solid fa-user-lock me-2"></i>SIGN IN TO SAVE
                        </a>
                    @endauth
                </div>
            </form>
        </div>
    </div>

    <!-- RIGHT SIDE: Preview -->
    <div class="col-lg-5 col-xl-5">
        <div class="sticky-lg-top" style="top: 130px; z-index: 10;">
            <div class="glass-card p-4 p-md-5 text-center shadow-2xl">
                <div class="mb-4">
                    <span class="badge rounded-pill px-4 py-2 border border-primary border-opacity-50" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; font-weight: 700; letter-spacing: 0.05em;">
                        <i class="fa-solid fa-satellite-dish fa-fade me-2"></i>LIVE RENDERING
                    </span>
                </div>

                <div class="preview-stage-wrapper position-relative mx-auto mb-5" style="max-width: 260px;">
                    <div id="qrGlow" class="position-absolute top-50 start-50 translate-middle w-100 h-100 rounded-circle opacity-0 transition-all" style="background: var(--primary); filter: blur(70px); z-index: 0;"></div>
                    <div class="bg-white p-4 rounded-5 position-relative shadow-2xl" style="transform: perspective(1000px) rotateY(-5deg); z-index: 1;">
                        <img id="qrPreview" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" class="img-fluid" alt="QR Preview" style="width: 100%; opacity: 0.05; transition: all 0.4s ease;">
                        <div id="previewPlaceholder" class="position-absolute top-50 start-50 translate-middle text-slate-200">
                            <i class="fa-solid fa-qrcode fa-5x"></i>
                        </div>
                    </div>
                </div>

                <div id="qrInfo" class="alert border-0 bg-primary bg-opacity-10 text-white small d-none py-3 px-4 mb-5 rounded-4 fade-in">
                    <i class="fa-solid fa-circle-check text-primary me-2"></i>DATA READY & VALIDATED
                </div>

                <div class="mt-auto pt-4 border-top border-white border-opacity-10 d-flex justify-content-center gap-3">
                    <div class="px-3 py-1 bg-white bg-opacity-5 rounded-pill x-small text-slate-200 border border-white border-opacity-10">
                        <i class="fa-solid fa-shield-halved me-1 text-primary"></i>Certified
                    </div>
                    <div class="px-3 py-1 bg-white bg-opacity-5 rounded-pill x-small text-slate-200 border border-white border-opacity-10" id="typeStatus">
                        Static
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let timeout = null;
    function updateHex(type, val) {
        document.getElementById(type + '_hex').innerText = val.toUpperCase();
    }
    function setType(type) {
        document.getElementById('type').value = type;
        const standard = document.getElementById('standardField');
        const contact = document.getElementById('contactFields');
        const label = document.getElementById('contentLabel');
        if(type === 'vcard') {
            standard.classList.add('d-none');
            contact.classList.remove('d-none');
        } else {
            standard.classList.remove('d-none');
            contact.classList.add('d-none');
            label.innerText = type === 'url' ? 'URL Destination' : 'Raw Text Content';
        }
        updatePreview();
    }

    function debouncePreview() {
        clearTimeout(timeout);
        timeout = setTimeout(updatePreview, 300);
    }

    function collectData() {
        const type = document.getElementById('type').value;
        const data = {
            _token: document.querySelector('input[name="_token"]').value,
            name: document.getElementById('name').value || 'Preview',
            type: type,
            foreground_color: document.getElementById('foreground_color').value,
            background_color: document.getElementById('background_color').value,
            is_dynamic: document.getElementById('is_dynamic').checked ? 1 : 0
        };
        if(type === 'vcard') {
            data.content_data = {
                name: document.getElementById('contact_name').value,
                title: document.getElementById('contact_title').value,
                company: document.getElementById('contact_company').value,
                email: document.getElementById('contact_email').value,
                mobile: document.getElementById('contact_mobile').value,
                website: document.getElementById('contact_website').value,
                address: document.getElementById('contact_address').value,
                facebook: document.getElementById('contact_facebook').value,
                youtube: document.getElementById('contact_youtube').value,
            };
        } else {
            data.destination_url = document.getElementById('destination_url').value;
        }
        return data;
    }

    async function updatePreview() {
        const data = collectData();
        const img = document.getElementById('qrPreview');
        const placeholder = document.getElementById('previewPlaceholder');
        const info = document.getElementById('qrInfo');
        const glow = document.getElementById('qrGlow');
        const typeStatus = document.getElementById('typeStatus');
        typeStatus.innerText = data.is_dynamic ? 'Dynamic' : 'Static';

        if(data.type !== 'vcard' && !data.destination_url) {
            img.style.opacity = 0.05;
            placeholder.classList.remove('d-none');
            info.classList.add('d-none');
            glow.style.opacity = 0;
            return;
        }
        if(data.type === 'vcard' && !data.content_data.name) {
             img.style.opacity = 0.05;
             placeholder.classList.remove('d-none');
             info.classList.add('d-none');
             glow.style.opacity = 0;
             return;
        }

        try {
            const res = await fetch('{{ url("/app-api/qrcodes/preview") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if(json.data) {
                img.src = json.data;
                img.style.opacity = 1;
                placeholder.classList.add('d-none');
                info.classList.remove('d-none');
                glow.style.opacity = 0.15;
            }
        } catch(e) { console.error(e); }
    }

    async function downloadQr() {
        const spinner = document.getElementById('loadingSpinner');
        const btnText = document.getElementById('btnText');
        spinner.classList.remove('d-none');
        btnText.classList.add('opacity-50');
        const data = collectData();
        try {
            const res = await fetch('{{ url("/app-api/qrcodes/download") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
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
            }
        } catch(e) { console.error(e); } finally {
            spinner.classList.add('d-none');
            btnText.classList.remove('opacity-50');
        }
    }
    window.onload = () => updatePreview();
</script>

<style>
    .x-small { font-size: 0.75rem; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
    .nav-pills .nav-link { color: var(--slate-400); background: transparent; transition: all 0.3s ease; }
    .nav-pills .nav-link:hover { color: #fff; }
    .nav-pills .nav-link.active { 
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; 
        color: #ffffff !important; 
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); 
    }
</style>
@endsection
