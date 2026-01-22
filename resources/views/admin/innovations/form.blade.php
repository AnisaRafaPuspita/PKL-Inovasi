@extends('layouts.admin')
@section('title','Manage Innovations')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">
    Manage Innovations
</h1>
<p style="font-weight:700;color:#061a4d;">
    {{ $mode === 'create' ? 'Tambah Inovasi' : 'Edit Inovasi' }}
</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold mb-1">Gagal menyimpan:</div>
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $firstInnovator = $innovation->relationLoaded('innovators')
        ? $innovation->innovators->first()
        : null;

    $existingInnovators = $innovation->relationLoaded('innovators')
        ? $innovation->innovators
        : collect();

    $categories = $categories ?? (config('innovation.categories') ?? []);
    $extraCategories = [
        'Teknologi',
        'Digital',
        'AI',
        'Teknologi Informasi',
        'Internet of Things (IoT)',
        'Data Science',
        'Machine Learning',
        'Aplikasi / Software',
        'Hardware',
        'Sistem Cerdas',
        'Keamanan Siber',
        'Transformasi Digital',
    ];
    $categoriesMerged = array_values(array_unique(array_merge($categories, $extraCategories)));

    $oldPayload = old('innovators_payload', '');
    if (!$oldPayload && $existingInnovators->count()) {
        $tmp = [];
        foreach ($existingInnovators as $inv) {
            $facultyName = optional($inv->faculty)->name ?? '-';
            $tmp[] = [
                'type' => 'existing',
                'id' => $inv->id,
                'label' => $inv->name . ' (' . $facultyName . ')',
                'faculty_id' => $inv->faculty_id,
                'faculty_name' => $facultyName,
            ];
        }
        $oldPayload = json_encode($tmp);
    }
@endphp

<div class="panel">
<form
    method="POST"
    action="{{ $mode === 'create'
        ? route('admin.innovations.store')
        : route('admin.innovations.update', $innovation->id) }}"
    enctype="multipart/form-data"
>
    @csrf
    @if($mode === 'edit')
        @method('PUT')
    @endif

    <div class="row g-4">

        <div class="col-12 col-lg-4">
            <div style="background:#7c879f;border-radius:24px;padding:20px;">

                <div style="background:#fff;border-radius:16px;min-height:200px;padding:12px;">
                    <div class="fw-bold mb-2">Foto Inovasi</div>

                    <div id="photoEmpty" style="height:160px;display:flex;align-items:center;justify-content:center;border:2px dashed #cbd5e1;border-radius:12px;">
                        <span class="text-muted fw-bold">Belum ada foto</span>
                    </div>

                    <div id="photoPreview" class="d-grid mt-2" style="display:none;grid-template-columns:repeat(2,1fr);gap:10px;"></div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <label class="btn btn-outline-dark w-100 mb-0">
                        Tambah Foto
                        <input id="photosInput" type="file" name="images[]" accept="image/*" multiple hidden>
                    </label>

                    <button type="button" id="clearPhotosBtn" class="btn btn-outline-secondary">Clear</button>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <label class="fw-bold">Judul Inovasi</label>
            <input type="text" class="form-control mb-3" name="title" required
                   placeholder="Masukkan judul inovasi"
                   value="{{ old('title', $innovation->title) }}">

            <label class="fw-bold">Innovator</label>

            <div class="row g-2 align-items-start mb-2">
                <div class="col-12 col-md-6">
                    <input type="text"
                           id="new_innovator_name"
                           class="form-control"
                           placeholder="Ketik nama innovator (jika baru)">
                </div>

                <div class="col-12 col-md-6">
                    <select id="new_innovator_faculty" class="form-select">
                        <option value="">Pilih Fakultas</option>
                        @foreach($faculties as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-2">
                <select id="pick_innovator_id" class="form-select">
                    <option value="">Atau pilih innovator yang sudah ada</option>
                    @foreach($innovators as $inv)
                        <option value="{{ $inv->id }}" data-faculty="{{ $inv->faculty_id }}">
                            {{ $inv->name }} ({{ optional($inv->faculty)->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="innovatorList" class="mb-3"></div>

            <button type="button" id="addInnovatorBtn" class="btn btn-navy mb-3" style="border-radius:999px;padding:8px 16px;">
                Tambah Innovator
            </button>

            <input type="hidden" name="innovators_payload" id="innovators_payload" value="{{ $oldPayload }}">

            <label class="fw-bold">Fakultas</label>
            <select name="faculty_id" class="form-select mb-3" required>
                <option value="">-- Pilih Fakultas --</option>
                @foreach($faculties as $f)
                    <option value="{{ $f->id }}"
                        @selected(old('faculty_id', optional($firstInnovator)->faculty_id) == $f->id)>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>

            <label class="fw-bold">Kategori</label>
            <select name="category" class="form-select mb-3">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categoriesMerged as $cat)
                    <option value="{{ $cat }}" @selected(old('category', $innovation->category) == $cat)>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>

            <label class="fw-bold">Mitra</label>
            <input type="text" class="form-control mb-3" name="partner"
                   placeholder="Contoh: PT ABC, UNDIP, dll"
                   value="{{ old('partner', $innovation->partner) }}">

            <label class="fw-bold">Status Paten</label>
            <select name="hki_status" id="hki_status" class="form-select mb-2" onchange="handleHkiStatus(this.value)">
                <option value="">Pilih Status</option>
                <option value="terdaftar" @selected(old('hki_status', $innovation->hki_status) === 'terdaftar')>Terdaftar</option>
                <option value="on_process" @selected(old('hki_status', $innovation->hki_status) === 'on_process')>On Process</option>
                <option value="granted" @selected(old('hki_status', $innovation->hki_status) === 'granted')>Granted</option>
            </select>

            <input
                type="text"
                name="hki_registration_number"
                id="hki_registration"
                placeholder="Nomor Pendaftaran HKI"
                class="form-control mb-2"
                value="{{ old('hki_registration_number', $innovation->hki_registration_number ?? '') }}"
                style="display:none;"
            >

            <input
                type="text"
                name="hki_patent_number"
                id="hki_patent"
                placeholder="Nomor Paten"
                class="form-control mb-3"
                value="{{ old('hki_patent_number', $innovation->hki_patent_number ?? '') }}"
                style="display:none;"
            >

            <label class="fw-bold">Link Inovasi (opsional)</label>
            <input type="url" class="form-control mb-3" name="video_url"
                   placeholder="https://..."
                   value="{{ old('video_url', $innovation->video_url) }}">

            <label class="fw-bold">Deskripsi</label>
            <textarea class="form-control mb-3" rows="4"
                      name="description"
                      placeholder="Masukkan deskripsi inovasi">{{ old('description', $innovation->description) }}</textarea>

            <label class="fw-bold">Keunggulan</label>
            <textarea class="form-control mb-3" rows="3"
                      name="advantages"
                      placeholder="Masukkan keunggulan">{{ old('advantages', $innovation->advantages) }}</textarea>

            <label class="fw-bold">Keberdampakan</label>
            <input type="text" class="form-control mb-4" name="impact"
                   value="{{ old('impact', $innovation->impact) }}"
                   placeholder="Masukkan keberdampakan (jika ada)">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.innovations.index') }}" class="btn btn-outline-secondary px-4">
                    Batal
                </a>
                <button type="submit" class="btn btn-navy px-4">
                    {{ $mode === 'create' ? 'Simpan Inovasi' : 'Simpan Perubahan' }}
                </button>
            </div>
        </div>

    </div>
</form>
</div>

<style>
.panel{ background:#fff; border:2px solid #061a4d; border-radius:18px; padding:20px; }
.btn-navy{ background:#061a4d; color:#fff; font-weight:700; border-radius:10px; border:1px solid #061a4d; }
.btn-navy:hover{ background:#04133a; border-color:#04133a; color:#fff; }

.innovator-chip{
  border:1px solid rgba(6,26,77,.25);
  border-radius:14px;
  padding:12px 14px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:10px;
  margin-bottom:12px;
  background:#f9fafb;
}
.innovator-chip .meta{
  font-weight:800;
  color:#061a4d;
}
.innovator-chip .sub{
  font-size:13px;
  color:rgba(6,26,77,.75);
}
.innovator-chip .btn-remove{
  border:0;
  background:#ef4444;
  color:#fff;
  border-radius:10px;
  padding:6px 10px;
  font-weight:800;
  cursor:pointer;
}
</style>

<script>
function handleHkiStatus(val){
  const reg = document.getElementById('hki_registration');
  const pat = document.getElementById('hki_patent');

  if (!reg || !pat) return;

  reg.style.display = 'none';
  pat.style.display = 'none';

  if (val === 'terdaftar' || val === 'on_process') {
    reg.style.display = 'block';
  }

  if (val === 'granted') {
    pat.style.display = 'block';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('photosInput');
  const preview = document.getElementById('photoPreview');
  const empty = document.getElementById('photoEmpty');
  const clearBtn = document.getElementById('clearPhotosBtn');

  if (input && preview && empty && clearBtn) {
    let selectedFiles = [];

    function syncInputFiles() {
      const dt = new DataTransfer();
      selectedFiles.forEach(f => dt.items.add(f));
      input.files = dt.files;
    }

    function renderPhotos() {
      preview.innerHTML = '';

      if (!selectedFiles.length) {
        preview.style.display = 'none';
        empty.style.display = 'flex';
        return;
      }

      empty.style.display = 'none';
      preview.style.display = 'grid';

      selectedFiles.forEach((file, idx) => {
        const url = URL.createObjectURL(file);

        const wrap = document.createElement('div');
        wrap.style.position = 'relative';
        wrap.style.border = '1px solid #e5e7eb';
        wrap.style.borderRadius = '12px';
        wrap.style.overflow = 'hidden';
        wrap.style.height = '110px';

        const img = document.createElement('img');
        img.src = url;
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.objectFit = 'cover';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = '×';
        btn.style.position = 'absolute';
        btn.style.top = '6px';
        btn.style.right = '6px';
        btn.style.width = '26px';
        btn.style.height = '26px';
        btn.style.borderRadius = '999px';
        btn.style.border = '0';
        btn.style.fontWeight = '900';
        btn.style.cursor = 'pointer';
        btn.style.background = 'rgba(0,0,0,0.6)';
        btn.style.color = '#fff';
        btn.addEventListener('click', () => {
          selectedFiles.splice(idx, 1);
          syncInputFiles();
          renderPhotos();
        });

        wrap.appendChild(img);
        wrap.appendChild(btn);
        preview.appendChild(wrap);

        img.addEventListener('load', () => URL.revokeObjectURL(url));
      });
    }

    input.addEventListener('change', () => {
      const files = Array.from(input.files || []);
      if (!files.length) return;

      files.forEach(f => {
        if (!f.type || !f.type.startsWith('image/')) return;

        const exists = selectedFiles.some(x =>
          x.name === f.name && x.size === f.size && x.lastModified === f.lastModified
        );
        if (!exists) selectedFiles.push(f);
      });

      syncInputFiles();
      renderPhotos();
    });

    clearBtn.addEventListener('click', () => {
      selectedFiles = [];
      syncInputFiles();
      renderPhotos();
    });

    renderPhotos();
  }

  const nameInput = document.getElementById('new_innovator_name');
  const facultySelect = document.getElementById('new_innovator_faculty');
  const pickSelect = document.getElementById('pick_innovator_id');
  const addBtn = document.getElementById('addInnovatorBtn');
  const list = document.getElementById('innovatorList');
  const payload = document.getElementById('innovators_payload');

  if (nameInput && facultySelect && pickSelect && addBtn && list && payload) {
    let items = [];

    function setPayload(){
      payload.value = JSON.stringify(items);
    }

    function renderInnovators(){
      list.innerHTML = '';
      if (!items.length) return;

      items.forEach((it, idx) => {
        const div = document.createElement('div');
        div.className = 'innovator-chip';

        const left = document.createElement('div');

        const meta = document.createElement('div');
        meta.className = 'meta';
        meta.textContent = it.type === 'existing' ? it.label : it.name;

        const sub = document.createElement('div');
        sub.className = 'sub';
        sub.textContent = it.faculty_name ? `Fakultas: ${it.faculty_name}` : 'Fakultas: -';

        left.appendChild(meta);
        left.appendChild(sub);

        const rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'btn-remove';
        rm.textContent = 'Hapus';
        rm.addEventListener('click', () => {
          items.splice(idx, 1);
          setPayload();
          renderInnovators();
        });

        div.appendChild(left);
        div.appendChild(rm);
        list.appendChild(div);
      });
    }

    function getFacultyNameById(id){
      const opt = facultySelect.querySelector(`option[value="${id}"]`);
      return opt ? opt.textContent.trim() : '';
    }

    function addExisting(){
      const id = pickSelect.value;
      if (!id) return false;

      const opt = pickSelect.options[pickSelect.selectedIndex];
      const label = opt ? opt.textContent.trim() : '';
      const facultyId = opt ? (opt.getAttribute('data-faculty') || '') : '';
      const facultyName = facultyId ? getFacultyNameById(facultyId) : '';

      const exists = items.some(x => x.type === 'existing' && String(x.id) === String(id));
      if (exists) return true;

      items.push({
        type: 'existing',
        id: Number(id),
        label: label,
        faculty_id: facultyId ? Number(facultyId) : null,
        faculty_name: facultyName
      });
      return true;
    }

    function addNew(){
      const name = (nameInput.value || '').trim();
      if (!name) return false;

      const facultyId = facultySelect.value;
      if (!facultyId) {
        alert('Pilih Fakultas untuk innovator baru.');
        return true;
      }

      const facultyName = getFacultyNameById(facultyId);

      const exists = items.some(x =>
        x.type === 'new'
        && x.name.toLowerCase() === name.toLowerCase()
        && String(x.faculty_id) === String(facultyId)
      );
      if (exists) return true;

      items.push({
        type: 'new',
        name: name,
        faculty_id: Number(facultyId),
        faculty_name: facultyName
      });
      return true;
    }

    addBtn.addEventListener('click', () => {
      const picked = addExisting();
      const created = addNew();

      if (!picked && !created){
        alert('Isi nama innovator baru + fakultas, atau pilih innovator yang sudah ada.');
        return;
      }

      nameInput.value = '';
      facultySelect.value = '';
      pickSelect.value = '';

      setPayload();
      renderInnovators();
    });

    if (payload.value) {
      try{
        const parsed = JSON.parse(payload.value);
        if (Array.isArray(parsed)) items = parsed;
      }catch(e){}
    }

    setPayload();
    renderInnovators();
  }

  const sel = document.getElementById('hki_status');
  if (sel) handleHkiStatus(sel.value);
});
</script>

@endsection
