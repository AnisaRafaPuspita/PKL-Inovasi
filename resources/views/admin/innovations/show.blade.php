@extends('layouts.admin')
@section('title','Detail Innovation')

@section('content')
<h1 style="font-weight:900;color:#061a4d;">Kelola Inovasi</h1>
<p style="font-weight:700;color:#061a4d;">Detail</p>

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
  $photos = $innovation->images ?? collect();
  $primary = $innovation->primaryImage ?? ($photos->firstWhere('is_primary', true) ?? $photos->first());

  $innovatorList = $innovation->relationLoaded('innovators')
      ? ($innovation->innovators ?? collect())
      : collect();

  $firstInnovator = $innovatorList->first();

  $predefinedCategories = [
    'Energi',
    'Ekonomi Biru',
    'Kesehatan dan Farmasi',
    'Manufaktur dan Infrastruktur',
    'Pangan dan Teknologi Pertanian',
    'Teknologi Digital, AI, dan sejenisnya',
  ];

  $currentCategory = old('category', $innovation->category);
  $isOtherCategory = $currentCategory && !in_array($currentCategory, $predefinedCategories, true);

  $categorySelectValue = old('category', $isOtherCategory ? 'other' : $innovation->category);
  $categoryOtherValue = old('category_other', $isOtherCategory ? $innovation->category : '');

  $editOldPayload = old('innovators_payload', '');
  if (!$editOldPayload && $innovatorList->count()) {
    $tmp = [];
    foreach ($innovatorList as $inv) {
      $facultyName = optional($inv->faculty)->name ?? '-';
      $tmp[] = [
        'type' => 'existing',
        'id' => $inv->id,
        'label' => $inv->name,
        'faculty_id' => $inv->faculty_id,
        'faculty_name' => $facultyName,
      ];
    }
    $editOldPayload = json_encode($tmp);
  }

  $categoryDisplay = $innovation->category ?? '-';

  if (($innovation->category ?? '') === 'other') {
    $categoryDisplay = 'Inovasi Lainnya';
  }
@endphp

<div class="panel mb-4">
  <div class="d-flex justify-content-between align-items-start gap-3">
    <div>
      <h4 class="mb-2" style="font-weight:900;color:#061a4d;">
        {{ $innovation->title }}
      </h4>

      <div class="mb-1">
        <b>Nama Inovator:</b>
        @if($innovatorList->count())
          <ol style="margin:6px 0 0 18px;">
            @foreach($innovatorList as $inv)
              <li style="margin-bottom:4px;">
                {{ $inv->name ?? '-' }}
                <span style="color:#061a4d;opacity:.8;">- {{ $inv->faculty?->name ?? '-' }}</span>
              </li>
            @endforeach
          </ol>
        @else
          -
        @endif
      </div>

      <div class="mb-1"><b>Kategori:</b> {{ $categoryDisplay }}</div>
      <div class="mb-1"><b>Mitra:</b> {{ $innovation->partner ?? '-' }}</div>
      <div class="mb-1"><b>Status Paten:</b> {{ $innovation->hki_status ?? '-' }}</div>

      @if(in_array(($innovation->hki_status ?? ''), ['terdaftar','on_process'], true))
        <div class="mb-1"><b>Nomor Pendaftaran HKI:</b> {{ $innovation->hki_registration_number ?? '-' }}</div>
      @endif

      @if(($innovation->hki_status ?? '') === 'granted')
        <div class="mb-1"><b>Nomor Paten:</b> {{ $innovation->hki_patent_number ?? '-' }}</div>
      @endif

      <div class="mb-1"><b>Link Inovasi:</b> {{ $innovation->video_url ?? '-' }}</div>
      <div class="mb-1"><b>Deskripsi:</b> {{ $innovation->description ?? '-' }}</div>
      <div class="mb-1"><b>Keunggulan:</b> {{ $innovation->advantages ?? '-' }}</div>



      <div class="mb-1"><b>Keberdampakan:</b> {{ $innovation->impact ?? '-' }}</div>
    </div>

    <div style="width:180px;">
      <div style="border:2px solid #061a4d;border-radius:14px;overflow:hidden;background:#fff;">
        @if($primary)
          <img src="{{ asset('storage/'.$primary->image_path) }}" style="width:100%;height:180px;object-fit:cover;">
        @else
          <div style="height:180px;display:flex;align-items:center;justify-content:center;font-weight:800;">
            No Photo
          </div>
        @endif
      </div>
    </div>
  </div>

  @if($photos->count())
    <div class="mt-3">
      <div class="fw-bold mb-2" style="color:#061a4d;">Semua Foto</div>
      <div class="d-grid" style="grid-template-columns:repeat(6,1fr);gap:10px;">
        @foreach($photos as $img)
          <a href="{{ asset('storage/'.$img->image_path) }}" target="_blank"
             style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;display:block;">
            <img src="{{ asset('storage/'.$img->image_path) }}" style="width:100%;height:90px;object-fit:cover;">
          </a>
        @endforeach
      </div>
    </div>
  @endif

  <div class="mt-3 d-flex gap-2">
    <a href="{{ route('admin.innovations.index') }}" class="btn btn-outline-secondary">Kembali</a>

    <button id="btnToggleEdit" class="btn btn-navy" type="button">Edit</button>

    <form method="POST"
          action="{{ route('admin.innovations.destroy', $innovation->id) }}"
          onsubmit="return confirm('Yakin ingin menghapus inovasi ini?')"
          style="display:inline;">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-same-height">Hapus</button>
    </form>
  </div>
</div>

<div id="editBox" class="panel" style="display:none;">
  <form id="editInnovationForm" method="POST"
        action="{{ route('admin.innovations.update', $innovation->id) }}"
        enctype="multipart/form-data">
    @csrf
    @method('PUT')

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

        @if($photos->count())
          <div class="mt-3">
            <div class="fw-bold mb-2" style="color:#061a4d;">Hapus Foto Lama</div>
            <div class="d-grid" style="grid-template-columns:repeat(2,1fr);gap:10px;">
              @foreach($photos as $img)
                <label style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;cursor:pointer;">
                  <img src="{{ asset('storage/'.$img->image_path) }}" style="width:100%;height:110px;object-fit:cover;">
                  <div style="padding:8px;display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="delete_image_ids[]" value="{{ $img->id }}">
                    <span style="font-weight:700;color:#061a4d;">Hapus</span>
                  </div>
                </label>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <div class="col-12 col-lg-8">
        <label class="fw-bold">Judul Inovasi</label>
        <input type="text" class="form-control mb-3" name="title" required
               value="{{ old('title', $innovation->title) }}">

        <label class="fw-bold">Inovator</label>

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
            <option value="">Atau pilih inovator yang sudah ada</option>
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

        <input type="hidden" name="innovators_payload" id="innovators_payload" value="{{ $editOldPayload }}">
        <input type="hidden" name="faculty_id" id="faculty_id" value="{{ old('faculty_id', optional($firstInnovator)->faculty_id) }}">

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

        <label class="fw-bold">Link Inovasi</label>
        <input type="text" class="form-control mb-3" name="video_url"
               value="{{ old('video_url', $innovation->video_url) }}">

        <label class="fw-bold">Deskripsi</label>
        <textarea class="form-control mb-3" rows="4"
                  name="description">{{ old('description', $innovation->description) }}</textarea>

        <label class="fw-bold">Keunggulan</label>
        <textarea class="form-control mb-3" rows="3"
                  name="advantages">{{ old('advantages', $innovation->advantages) }}</textarea>

        <label class="fw-bold">Keberdampakan</label>
        <input type="text" class="form-control mb-4" name="impact"
               value="{{ old('impact', $innovation->impact) }}"
               placeholder="Isi agar masuk Inovasi Berdampak">

        <div class="text-end">
          <button class="btn btn-navy px-4" type="submit">Simpan Perubahan</button>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
  const btn = document.getElementById('btnToggleEdit');
  const box = document.getElementById('editBox');

  btn.addEventListener('click', () => {
    const isOpen = box.style.display === 'block';
    box.style.display = isOpen ? 'none' : 'block';
    btn.textContent = isOpen ? 'Edit' : 'Tutup Edit';
    if(!isOpen) box.scrollIntoView({behavior:'smooth', block:'start'});
  });

  function handleHkiStatus(val){
    var reg = document.getElementById('hki_registration');
    var pat = document.getElementById('hki_patent');
    if (!reg || !pat) return;

    reg.style.display = 'none';
    pat.style.display = 'none';

    if (val === 'terdaftar' || val === 'on_process') reg.style.display = 'block';
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

  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('photosInput');
    const preview = document.getElementById('photoPreview');
    const empty = document.getElementById('photoEmpty');
    const clearBtn = document.getElementById('clearPhotosBtn');
    const form = document.getElementById('editInnovationForm');

    if (input && preview && empty && clearBtn && form) {
      let selectedFiles = [];

      function syncInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        input.files = dt.files;
      }

      function render() {
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

          const b = document.createElement('button');
          b.type = 'button';
          b.textContent = '×';
          b.style.position = 'absolute';
          b.style.top = '6px';
          b.style.right = '6px';
          b.style.width = '26px';
          b.style.height = '26px';
          b.style.borderRadius = '999px';
          b.style.border = '0';
          b.style.fontWeight = '900';
          b.style.cursor = 'pointer';
          b.style.background = 'rgba(0,0,0,0.6)';
          b.style.color = '#fff';
          b.addEventListener('click', () => {
            selectedFiles.splice(idx, 1);
            syncInputFiles();
            render();
          });

          wrap.appendChild(img);
          wrap.appendChild(b);
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
        render();
      });

      clearBtn.addEventListener('click', () => {
        selectedFiles = [];
        syncInputFiles();
        render();
      });

      form.addEventListener('submit', () => {
        syncInputFiles();
      });

      render();
    }

    const cat = document.getElementById('category');
    if (cat) handleCategory(cat.value);

    const sel = document.getElementById('hki_status');
    if (sel) handleHkiStatus(sel.value);

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

        var facultyHidden = document.getElementById('faculty_id');
        if (facultyHidden) {
          var first = items && items.length ? items[0] : null;
          var fid = first && first.faculty_id ? first.faculty_id : '';
          facultyHidden.value = fid ? String(fid) : '';
        }
      }

      function getFacultyNameById(id){
        const opt = facultySelect.querySelector('option[value="' + id + '"]');
        return opt ? opt.textContent.trim() : '';
      }

      function renderInnovators(){
        list.innerHTML = '';
        if (!items.length) return;

        items.forEach((it, idx) => {
          const div = document.createElement('div');
          div.className = 'innovator-chip';

          const left = document.createElement('div');
          left.className = 'meta';

          const nameSpan = document.createElement('span');
          nameSpan.textContent = (it.type === 'existing') ? (it.label || '-') : (it.name || '-');

          const facultySpan = document.createElement('span');
          facultySpan.className = 'faculty-inline';
          facultySpan.textContent = it.faculty_name ? it.faculty_name : '-';

          left.appendChild(nameSpan);
          left.appendChild(facultySpan);

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

      function addExisting(){
        const id = pickSelect.value;
        if (!id) return false;

        const opt = pickSelect.options[pickSelect.selectedIndex];
        if (!opt) return false;

        const name = opt.getAttribute('data-name') || '';
        const facultyId = opt.getAttribute('data-faculty') || '';
        const facultyName = opt.getAttribute('data-faculty-name') || '';

        const exists = items.some(x => x.type === 'existing' && String(x.id) === String(id));
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
          && (x.name || '').toLowerCase() === name.toLowerCase()
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

      var formEdit = document.getElementById('editInnovationForm');
      if (formEdit) {
        formEdit.addEventListener('submit', function () {
          setPayload();
        });
      }
    }
  });
</script>

<style>
.panel{
  border:2px solid #061a4d;
  border-radius:18px;
  padding:20px;
  background:#fff;
}
.btn-navy{
  background:#061a4d;
  color:#fff;
  font-weight:700;
  border-radius:10px;
}
.btn-navy,
.btn-same-height{
  padding: 0.375rem 1.5rem;
  font-weight: 700;
  border-radius: 10px;
  line-height: 1.5;
}
.btn-same-height{
  background-color:#dc3545;
  border:1px solid #dc3545;
  color:#fff;
}
.btn-same-height:hover{
  background-color:#bb2d3b;
  border-color:#b02a37;
  color:#fff;
}
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
@endsection
