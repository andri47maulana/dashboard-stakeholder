@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">📅 Kalender Kegiatan</h5>
      <button class="btn btn-light btn-sm" id="btnAddEvent">
        <i class="bi bi-plus-circle"></i> Tambah Event
      </button>
    </div>
    
    <div class="card-body">
      <div class="row mb-3">
  <div class="col-md-4">
    <label><strong>Regional</strong></label>
    <select id="filterRegion" class="form-control">
      <option value="">Semua Region</option>
      @php
          $regions = collect($datakebun)->pluck('region')->unique();
      @endphp
      @foreach($regions as $region)
        <option value="{{ $region }}">{{ $region }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label><strong>Kategori Stakeholder</strong></label>
    <select id="filterStakeholder" class="form-control">
      <option value="">Semua Kategori</option>
      <option value="Governance">Governance</option>
      <option value="Non Governance">Non Governance</option>
    </select>
  </div>

  <div class="col-md-4">
    <label><strong>Tipe Kegiatan</strong></label>
    <select id="filterTipe" class="form-control">
      <option value="">Semua Tipe</option>
      <option value="Governance">Governance</option>
      <option value="Non Governance">Non Governance</option>
    </select>
  </div>
</div>

      <div id="calendar"></div>
    </div>
  </div>
</div>

<!-- Modal Tambah/Edit Event -->
<div class="modal fade" id="modalEvent" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Tambah Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEvent">
          @csrf
          <input type="hidden" name="id" id="id">

          <div class="row">
            <div class="col-md-8 mb-3">
              <label>Judul Event</label>
              <input type="text" class="form-control" name="judul" id="judul" required>
            </div>

            <div class="col-md-4 mb-3">
              <label>Tipe Kegiatan</label>
              <select name="tipe_event" id="tipe_event" class="form-control kegiatan-select" required>
                  <option value="">Pilih..</option>
                  <option value="Governance">Governance</option>
                  <option value="Non Governance">Non Governance</option>
                </select>
            </div>
          </div>

          <div class="mb-3">
            <label>Deskripsi</label>
            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Tanggal Mulai</label>
              <input type="date" class="form-control" name="start_event" id="start_event" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Tanggal Selesai</label>
              <input type="date" class="form-control" name="end_event" id="end_event">
            </div>
          </div>

          <div class="row">
              <div class="col-md-4 mb-3">
                <label><strong>Region</strong></label>
                <select name="region" id="region_modal" class="form-control region-select" required>
                  <option value="">Pilih..</option>
                  @php
                        $regions = collect($datakebun)->pluck('region')->unique();
                    @endphp
                  @foreach($regions as $region)
                    <option value="{{ $region }}">{{ $region }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-md-4 mb-3">
                <label><strong>Unit (Kebun)</strong></label>
                <select name="unit" id="unit_modal" class="form-control kebun-select" required>
                  <option value="">Pilih..</option>
                </select>
              </div>

            <div class="col-md-4 mb-3">
              <label>Kategori Stakeholder</label>
              <select name="kat_stekholder" id="kat_stekholder" class="form-control stake-select" required>
                  <option value="">Pilih..</option>
                  <option value="Governance">Governance</option>
                  <option value="Non Governance">Non Governance</option>
                </select>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100">Simpan Event</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="detailModalLabel">Detail Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tr><th>Judul</th><td id="judul"></td></tr>
          <tr><th>Deskripsi</th><td id="deskripsi"></td></tr>
          <tr><th>Mulai</th><td id="start"></td></tr>
          <tr><th>Selesai</th><td id="end"></td></tr>
          <tr><th>Region</th><td id="region"></td></tr>
          <tr><th>Unit</th><td id="unit"></td></tr>
          <tr><th>Kategori Stakeholder</th><td id="kat_stekholder"></td></tr>
          <tr><th>Tipe Event</th><td id="tipe_event"></td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" id="btnDeleteEvent">Hapus Event</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection
<style>
  /* Styling legenda kalender */
  #filterRegion, #filterStakeholder, #filterTipe {
  font-size: 0.9rem;
}

.calendar-legend {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 10px 20px;
  background: #f8f9fa;
  border: 1px solid #dee2e6;
}

.legend-item {
  display: flex;
  align-items: center;
  background: white;
  border-radius: 6px;
  padding: 6px 10px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  transition: transform 0.2s ease;
}

.legend-item:hover {
  transform: translateY(-2px);
}

.legend-color {
  width: 20px;
  height: 20px;
  border-radius: 4px;
  margin-right: 10px;
  flex-shrink: 0;
  border: 1px solid #ccc;
}

.legend-label {
  font-size: 0.9rem;
  color: #333;
  font-weight: 500;
}

</style>
@push('jstambahan')
<style>
#calendar {
  height: 700px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('calendar');
  const modalEvent = new bootstrap.Modal(document.getElementById('modalEvent'));
  const formEvent = document.getElementById('formEvent');
  const btnAdd = document.getElementById('btnAddEvent');
  const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

  // 🎨 Warna berdasarkan region
  const regionColors = {
    'PTPN I HO': '#6c757d',
    'PTPN I Regional 1': '#007bff',
    'PTPN I Regional 2': '#28a745',
    'PTPN I Regional 3': '#ffc107',
    'PTPN I Regional 4': '#dc3545',
    'PTPN I Regional 5': '#17a2b8',
    'PTPN I Regional 6': '#6610f2',
    'PTPN I Regional 7': '#fd7e14',
    'PTPN I Regional 8': '#20c997'
  };

  // 🧾 Tambahkan legenda warna di bawah kalender
  const legendContainer = document.createElement('div');
  legendContainer.classList.add('calendar-legend', 'mt-4', 'p-3', 'rounded', 'shadow-sm');

  Object.entries(regionColors).forEach(([region, color]) => {
    const legendItem = document.createElement('div');
    legendItem.classList.add('legend-item');
    legendItem.innerHTML = `
      <div class="legend-color" style="background:${color}"></div>
      <span class="legend-label">${region}</span>
    `;
    legendContainer.appendChild(legendItem);
  });

  // Letakkan legenda setelah kalender
  calendarEl.parentNode.insertBefore(legendContainer, calendarEl.nextSibling);

  // 🗓️ Inisialisasi FullCalendar
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    selectable: true,
    editable: false,
    locale: 'id',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,listMonth'
    },
    events: function(fetchInfo, successCallback, failureCallback) {
      const region = document.getElementById('filterRegion').value;
      const kat_stekholder = document.getElementById('filterStakeholder').value;
      const tipe_event = document.getElementById('filterTipe').value;

      $.ajax({
        url: '{{ route('events.fetch') }}',
        type: 'GET',
        data: {
          region: region,
          kat_stekholder: kat_stekholder,
          tipe_event: tipe_event
        },
        success: function(response) {
          successCallback(response);
        },
        error: function() {
          failureCallback();
          alert('Gagal memuat event dari server.');
        }
      });
    },

    // 🎨 Terapkan warna region
    eventDidMount: function(info) {
      const region = info.event.extendedProps.region;
      const color = regionColors[region] || '#999';
      info.el.style.backgroundColor = color;
      info.el.style.borderColor = color;
      info.el.style.color = 'white';
    },

    // Klik tanggal kosong → tambah event
    dateClick: function(info) {
      formEvent.reset();
      document.getElementById('start_event').value = info.dateStr;
      modalEvent.show();
    },

    // Klik event → tampilkan detail
    eventClick: function(info) {
      let eventId = info.event.id;

      $.ajax({
        url: '/event/' + eventId,
        type: 'GET',
        success: function(response) {
          if (response.success) {
            let e = response.event;
            $('#detailModal #judul').text(e.judul);
            $('#detailModal #deskripsi').text(e.deskripsi);
            $('#detailModal #start').text(e.start_event);
            $('#detailModal #end').text(e.end_event);
            $('#detailModal #region').text(e.region);
            $('#detailModal #unit').text(e.unit);
            $('#detailModal #kat_stekholder').text(e.kat_stekholder);
            $('#detailModal #tipe_event').text(e.tipe_event);
            $('#detailModal').data('event-id', e.id);
            detailModal.show();
          }
        },
        error: function() {
          alert('Gagal mengambil data event');
        }
      });
    }
  });

  calendar.render();

  // 🔁 Refetch event ketika filter berubah
  ['filterRegion', 'filterStakeholder', 'filterTipe'].forEach(id => {
    document.getElementById(id).addEventListener('change', () => {
      calendar.refetchEvents();
    });
  });

  // ➕ Tombol tambah event manual
  btnAdd.addEventListener('click', () => {
    formEvent.reset();
    modalEvent.show();
  });

  // 💾 Submit form event
  formEvent.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(formEvent);
    fetch('{{ route('events.store') }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: formData
    })
    .then(res => res.json())
    .then(() => {
      modalEvent.hide();
      calendar.refetchEvents();
    })
    .catch(err => console.error(err));
  });

  // 🗑️ Tombol Hapus Event
  $(document).on('click', '#btnDeleteEvent', function () {
    const eventId = $('#detailModal').data('event-id');
    if (!confirm('Hapus event ini?')) return;

    fetch(`/events/delete/${eventId}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(() => {
      detailModal.hide();
      calendar.refetchEvents();
    })
    .catch(err => console.error(err));
  });
});
</script>



<script>
  // --- Dropdown berantai Region → Unit ---
$(document).on('change', '.region-select', function () {
    const region = $(this).val();
    const kebunSelect = $(this).closest('form, .modal-body').find('.kebun-select');
    kebunSelect.html('<option value="">Memuat...</option>');

    $.get('{{ url("/get-kebun-by-region") }}', { region: region }, function (data) {
        let options = '<option value="">Pilih..</option>';
        data.forEach(function (item) {
            options += `<option value="${item.unit}">${item.unit}</option>`;
        });
        kebunSelect.html(options);
    });
});

</script>
@endpush
