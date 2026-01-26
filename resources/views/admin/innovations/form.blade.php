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
                'label' => $inv->name,
                'faculty_id' => $inv->faculty_id,
                'faculty_name' => $facultyName,
            ];
        }
        $oldPayload = json_encode($tmp);
    }

    $knownCategoryValues = [
        'Energi',
        'Ekonomi Biru',
        'Kesehatan dan Farmasi',
        'Manufaktur dan Infrastruktur',
        'Pangan dan Teknologi Pertanian',
        'Teknologi Digital, AI, dan sejenisnya',
    ];

    $categoryValue = old('category', $innovation->category);

    if ($categoryValue && !in_array($categoryValue, $knownCategoryValues, true) && $categoryValue !== 'other') {
        $categorySelectValue = 'other';
        $categoryOtherValue = old('category_other', $categoryValue);
    } else {
        $categorySelectValue = $categoryValue;
        $categoryOtherValue = old('category_other', '');
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
                        <option
                            value="{{ $inv->id }}"
                            data-name="{{ $inv->name }}"
                            data-faculty="{{ $inv->faculty_id }}"
                            data-faculty-name="{{ optional($inv->faculty)->name ?? '-' }}"
                        >
                            {{ $inv->name }} ({{ optional($inv->faculty)->name ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="innovatorList" class="mb-3"></div>

            <div class="mb-4">
              <button
                type="button"
                id="addInnovatorBtn"
                class="btn btn-navy"
                style="border-radius:999px;padding:8px 16px; display:inline-block;"
              >
                Tambah Innovator
              </button>
            </div>

            <input type="hidden" name="innovators_payload" id="innovators_payload" value="{{ $oldPayload }}">

            <input type="hidden" name="faculty_id" id="faculty_id" value="{{ old('faculty_id') }}">

            <label class="fw-bold">Kategori</label>
            <select name="category" id="category" class="form-select mb-2" onchange="handleCategory(this.value)">
                <option value="">Pilih Kategori</option>
                <option value="Energi" @selected($categorySelectValue == 'Energi')>Energi</option>
                <option value="Ekonomi Biru" @selected($categorySelectValue == 'Ekonomi Biru')>Ekonomi Biru</option>
                <option value="Kesehatan dan Farmasi" @selected($categorySelectValue == 'Kesehatan dan Farmasi')>Kesehatan dan Farmasi</option>
                <option value="Manufaktur dan Infrastruktur" @selected($categorySelectValue == 'Manufaktur dan Infrastruktur')>Manufaktur dan Infrastruktur</option>
                <option value="Pangan dan Teknologi Pertanian" @selected($categorySelectValue == 'Pangan dan Teknologi Pertanian')>Pangan dan Teknologi Pertanian</option>
                <option value="Teknologi Digital, AI, dan sejenisnya" @selected($categorySelectValue == 'Teknologi Digital, AI, dan sejenisnya')>
                    Teknologi Digital, AI, dan sejenisnya
                </option>
                <option value="other" @selected($categorySelectValue == 'other')>Inovasi Lainnya</option>
            </select>

            <input
                type="text"
                name="category_other"
                id="category_other"
                class="form-control mb-3"
                placeholder="Ketik kategori inovasi"
                value="{{ $categoryOtherValue }}"
                style="display:none;"
            >

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
  padding:14px 16px;
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
  display:flex;
  align-items:center;
  gap:18px;
  flex-wrap:wrap;
}

.innovator-chip .faculty-inline{
  font-size:13px;
  font-weight:700;
  color:rgba(6,26,77,.75);
  background:rgba(6,26,77,.06);
  border:1px solid rgba(6,26,77,.15);
  padding:5px 12px;
  border-radius:999px;
  margin-left:18px;
}

.innovator-chip .btn-remove{
  border:0;
  background:#ef4444;
  color:#fff;
  border-radius:12px;
  padding:10px 16px;
  font-weight:800;
  cursor:pointer;
}
</style>

<script>
function handleHkiStatus(val){
  var reg = document.getElementById('hki_registration');
  var pat = document.getElementById('hki_patent');
  if (!reg || !pat) return;

  reg.style.display = 'none';
  pat.style.display = 'none';

  if (val === 'terdaftar') reg.style.display = 'block';
  if (val === 'granted') pat.style.display = 'block';
}

function handleCategory(val){
  var other = document.getElementById('category_other');
  if (!other) return;

  if (val === 'other') {
    other.style.display = 'block';
  } else {
    other.style.display = 'none';
    other.value = '';
  }
}

document.addEventListener('DOMContentLoaded', function () {
  var input = document.getElementById('photosInput');
  var preview = document.getElementById('photoPreview');
  var empty = document.getElementById('photoEmpty');
  var clearBtn = document.getElementById('clearPhotosBtn');

  if (input && preview && empty && clearBtn) {
    var selectedFiles = [];

    function syncInputFiles() {
      var dt = new DataTransfer();
      selectedFiles.forEach(function (f) { dt.items.add(f); });
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

      selectedFiles.forEach(function (file, idx) {
        var url = URL.createObjectURL(file);

        var wrap = document.createElement('div');
        wrap.style.position = 'relative';
        wrap.style.border = '1px solid #e5e7eb';
        wrap.style.borderRadius = '12px';
        wrap.style.overflow = 'hidden';
        wrap.style.height = '110px';

        var img = document.createElement('img');
        img.src = url;
        img.style.width = '100%';
        img.style.height = '100%';
        img.style.objectFit = 'cover';

        var btn = document.createElement('button');
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
        btn.addEventListener('click', function () {
          selectedFiles.splice(idx, 1);
          syncInputFiles();
          renderPhotos();
        });

        wrap.appendChild(img);
        wrap.appendChild(btn);
        preview.appendChild(wrap);

        img.addEventListener('load', function(){ URL.revokeObjectURL(url); });
      });
    }

    input.addEventListener('change', function () {
      var files = Array.from(input.files || []);
      if (!files.length) return;

      files.forEach(function (f) {
        if (!f.type || !f.type.startsWith('image/')) return;

        var exists = selectedFiles.some(function (x) {
          return x.name === f.name && x.size === f.size && x.lastModified === f.lastModified;
        });

        if (!exists) selectedFiles.push(f);
      });

      syncInputFiles();
      renderPhotos();
    });

    clearBtn.addEventListener('click', function () {
      selectedFiles = [];
      syncInputFiles();
      renderPhotos();
    });

    renderPhotos();
  }

  var cat = document.getElementById('category');
  if (cat) handleCategory(cat.value);

  var nameInput = document.getElementById('new_innovator_name');
  var facultySelect = document.getElementById('new_innovator_faculty');
  var pickSelect = document.getElementById('pick_innovator_id');
  var addBtn = document.getElementById('addInnovatorBtn');
  var list = document.getElementById('innovatorList');
  var payload = document.getElementById('innovators_payload');
  var facultyHidden = document.getElementById('faculty_id');

  if (nameInput && facultySelect && pickSelect && addBtn && list && payload) {
    var items = [];

    function syncFacultyIdFromItems(){
      if (!facultyHidden) return;
      if (!items.length) {
        facultyHidden.value = '';
        return;
      }
      var first = items[0];
      if (first && first.faculty_id) {
        facultyHidden.value = String(first.faculty_id);
      } else {
        facultyHidden.value = '';
      }
    }

    function setPayload(){
      payload.value = JSON.stringify(items);
      syncFacultyIdFromItems();
    }

    function getFacultyNameById(id){
      var opt = facultySelect.querySelector('option[value="' + id + '"]');
      return opt ? opt.textContent.trim() : '';
    }

    function renderInnovators(){
      list.innerHTML = '';
      if (!items.length) return;

      items.forEach(function (it, idx) {
        var div = document.createElement('div');
        div.className = 'innovator-chip';

        var left = document.createElement('div');
        left.className = 'meta';

        var nameSpan = document.createElement('span');
        nameSpan.textContent = (it.type === 'existing') ? (it.label || '-') : (it.name || '-');

        var facultySpan = document.createElement('span');
        facultySpan.className = 'faculty-inline';
        facultySpan.textContent = it.faculty_name ? it.faculty_name : '-';

        left.appendChild(nameSpan);
        left.appendChild(facultySpan);

        var rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'btn-remove';
        rm.textContent = 'Hapus';
        rm.addEventListener('click', function () {
          items.splice(idx, 1);
          setPayload();
          renderInnovators();
        });

        div.appendChild(left);
        div.appendChild(rm);
        list.appendChild(div);
      });
    }

    function addExisting(){
      var id = pickSelect.value;
      if (!id) return false;

      var opt = pickSelect.options[pickSelect.selectedIndex];
      if (!opt) return false;

      var name = opt.getAttribute('data-name') || '';
      var facultyId = opt.getAttribute('data-faculty') || '';
      var facultyName = opt.getAttribute('data-faculty-name') || '';

      var exists = items.some(function (x) {
        return x.type === 'existing' && String(x.id) === String(id);
      });
      if (exists) return true;

      items.push({
        type: 'existing',
        id: Number(id),
        label: name,
        faculty_id: facultyId ? Number(facultyId) : null,
        faculty_name: facultyName
      });
      return true;
    }

    function addNew(){
      var name = (nameInput.value || '').trim();
      if (!name) return false;

      var facultyId = facultySelect.value;
      if (!facultyId) {
        alert('Pilih Fakultas untuk innovator baru.');
        return true;
      }

      var facultyName = getFacultyNameById(facultyId);

      var exists = items.some(function (x) {
        return x.type === 'new'
          && (x.name || '').toLowerCase() === name.toLowerCase()
          && String(x.faculty_id) === String(facultyId);
      });
      if (exists) return true;

      items.push({
        type: 'new',
        name: name,
        faculty_id: Number(facultyId),
        faculty_name: facultyName
      });
      return true;
    }

    addBtn.addEventListener('click', function () {
      var picked = addExisting();
      var created = addNew();

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
        var parsed = JSON.parse(payload.value);
        if (Array.isArray(parsed)) items = parsed;
      }catch(e){}
    }

    setPayload();
    renderInnovators();
  }

  var sel = document.getElementById('hki_status');
  if (sel) handleHkiStatus(sel.value);
  
});
</script>

@endsection
