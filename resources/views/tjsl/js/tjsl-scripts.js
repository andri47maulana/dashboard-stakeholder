$(document).ready(function() {
    // Initialize variables
    let biayaIndex = 0;
    let publikasiIndex = 0;
    let dokumentasiIndex = 0;
    let feedbackIndex = 0;
    let editBiayaIndex = 0;
    let editPublikasiIndex = 0;
    let editDokumentasiIndex = 0;
    let editFeedbackIndex = 0;

    // Initialize maps
    let lokasiMap;
    let editMap;
    let currentMarker;
    let editCurrentMarker;

    // Program Unggulan Map
    const programUnggulanMap = {
        1: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        2: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        3: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        4: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        5: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        6: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        7: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        8: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        9: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        10: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        11: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        12: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        13: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        14: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        15: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        16: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        17: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]
    };

    // All subpilars data
    const allSubpilars = [
        { id: 1, sub_pilar: "Tanpa Kemiskinan" },
        { id: 2, sub_pilar: "Tanpa Kelaparan" },
        { id: 3, sub_pilar: "Kehidupan Sehat dan Sejahtera" },
        { id: 4, sub_pilar: "Pendidikan Berkualitas" },
        { id: 5, sub_pilar: "Kesetaraan Gender" },
        { id: 6, sub_pilar: "Air Bersih dan Sanitasi Layak" },
        { id: 7, sub_pilar: "Energi Bersih dan Terjangkau" },
        { id: 8, sub_pilar: "Pekerjaan Layak dan Pertumbuhan Ekonomi" },
        { id: 9, sub_pilar: "Industri, Inovasi dan Infrastruktur" },
        { id: 10, sub_pilar: "Berkurangnya Kesenjangan" },
        { id: 11, sub_pilar: "Kota dan Permukiman yang Berkelanjutan" },
        { id: 12, sub_pilar: "Konsumsi dan Produksi yang Bertanggung Jawab" },
        { id: 13, sub_pilar: "Penanganan Perubahan Iklim" },
        { id: 14, sub_pilar: "Ekosistem Lautan" },
        { id: 15, sub_pilar: "Ekosistem Daratan" },
        { id: 16, sub_pilar: "Perdamaian, Keadilan dan Kelembagaan yang Tangguh" },
        { id: 17, sub_pilar: "Kemitraan untuk Mencapai Tujuan" }
    ];

    // Utility functions
    function resetSelect2(selector) {
        if ($(selector).hasClass('select2-hidden-accessible')) {
            $(selector).select2('destroy');
        }
        $(selector).select2({
            placeholder: 'Pilih...',
            allowClear: true,
            width: '100%'
        });
    }

    function initWilayahSelect2Group(prefix) {
        const provinsiSelector = `#${prefix}_provinsi`;
        const kabupatenSelector = `#${prefix}_kabupaten`;
        const kecamatanSelector = `#${prefix}_kecamatan`;
        const desaSelector = `#${prefix}_desa`;

        resetSelect2(provinsiSelector);
        resetSelect2(kabupatenSelector);
        resetSelect2(kecamatanSelector);
        resetSelect2(desaSelector);

        // Load provinsi
        $.get('/api/provinsi', function(data) {
            $(provinsiSelector).empty().append('<option value="">Pilih Provinsi</option>');
            data.forEach(function(provinsi) {
                $(provinsiSelector).append(`<option value="${provinsi.id}">${provinsi.name}</option>`);
            });
        });

        // Handle provinsi change
        $(provinsiSelector).on('change', function() {
            const provinsiId = $(this).val();
            $(kabupatenSelector).empty().append('<option value="">Pilih Kabupaten/Kota</option>').prop('disabled', !provinsiId);
            $(kecamatanSelector).empty().append('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
            $(desaSelector).empty().append('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', true);

            if (provinsiId) {
                $.get(`/api/kabupaten/${provinsiId}`, function(data) {
                    data.forEach(function(kabupaten) {
                        $(kabupatenSelector).append(`<option value="${kabupaten.id}">${kabupaten.name}</option>`);
                    });
                    $(kabupatenSelector).prop('disabled', false);
                });
            }
        });

        // Handle kabupaten change
        $(kabupatenSelector).on('change', function() {
            const kabupatenId = $(this).val();
            $(kecamatanSelector).empty().append('<option value="">Pilih Kecamatan</option>').prop('disabled', !kabupatenId);
            $(desaSelector).empty().append('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', true);

            if (kabupatenId) {
                $.get(`/api/kecamatan/${kabupatenId}`, function(data) {
                    data.forEach(function(kecamatan) {
                        $(kecamatanSelector).append(`<option value="${kecamatan.id}">${kecamatan.name}</option>`);
                    });
                    $(kecamatanSelector).prop('disabled', false);
                });
            }
        });

        // Handle kecamatan change
        $(kecamatanSelector).on('change', function() {
            const kecamatanId = $(this).val();
            $(desaSelector).empty().append('<option value="">Pilih Desa/Kelurahan</option>').prop('disabled', !kecamatanId);

            if (kecamatanId) {
                $.get(`/api/desa/${kecamatanId}`, function(data) {
                    data.forEach(function(desa) {
                        $(desaSelector).append(`<option value="${desa.id}">${desa.name}</option>`);
                    });
                    $(desaSelector).prop('disabled', false);
                });
            }
        });
    }

    function initWilayahIfExists(prefix) {
        if ($(`#${prefix}_provinsi`).length) {
            initWilayahSelect2Group(prefix);
        }
    }

    // Initialize on tab shown
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        initWilayahIfExists('lokasi');
        initWilayahIfExists('edit_lokasi');
    });

    // Initialize on modal shown
    $('div[id$="Modal"]').on('shown.bs.modal', function() {
        initWilayahIfExists('lokasi');
        initWilayahIfExists('edit_lokasi');
    });

    // Render sub pilar options
    function renderSubPilarOptions(selectedSubpilars = []) {
        const subPilarSelect = $('#sub_pilar');
        subPilarSelect.empty();
        
        allSubpilars.forEach(function(subPilar) {
            const isSelected = selectedSubpilars.includes(subPilar.id);
            subPilarSelect.append(`<option value="${subPilar.id}" ${isSelected ? 'selected' : ''}>${subPilar.id}.${subPilar.sub_pilar}</option>`);
        });
        
        if (subPilarSelect.hasClass('select2-hidden-accessible')) {
            subPilarSelect.select2('destroy');
        }
        subPilarSelect.select2({
            placeholder: 'Pilih TPB',
            allowClear: true,
            width: '100%'
        });
    }

    // Program unggulan change handler
    $('#program_unggulan_id').on('change', function() {
        const selectedId = parseInt($(this).val());
        if (selectedId && programUnggulanMap[selectedId]) {
            renderSubPilarOptions(programUnggulanMap[selectedId]);
        } else {
            renderSubPilarOptions();
        }
    });

    // Edit program unggulan change handler
    $('#edit_program_unggulan_id').on('change', function() {
        const selectedId = parseInt($(this).val());
        const editSubPilarSelect = $('#edit_sub_pilar');
        
        editSubPilarSelect.empty();
        
        if (selectedId && programUnggulanMap[selectedId]) {
            const allowedSubpilars = programUnggulanMap[selectedId];
            allSubpilars.forEach(function(subPilar) {
                if (allowedSubpilars.includes(subPilar.id)) {
                    editSubPilarSelect.append(`<option value="${subPilar.id}">${subPilar.id}.${subPilar.sub_pilar}</option>`);
                }
            });
        } else {
            allSubpilars.forEach(function(subPilar) {
                editSubPilarSelect.append(`<option value="${subPilar.id}">${subPilar.id}.${subPilar.sub_pilar}</option>`);
            });
        }
        
        if (editSubPilarSelect.hasClass('select2-hidden-accessible')) {
            editSubPilarSelect.select2('destroy');
        }
        editSubPilarSelect.select2({
            placeholder: 'Pilih TPB',
            allowClear: true,
            width: '100%'
        });
    });

    // Initialize Select2 for kebun dropdown
    $('#kebun_id').select2({
        placeholder: 'Pilih Kebun',
        allowClear: true,
        width: '100%'
    });

    // Kebun change handler for filtering
    $('#kebun_id').on('change', function() {
        const selectedKebunId = $(this).val();
        
        if (selectedKebunId) {
            // Filter data based on selected kebun
            // This would typically make an AJAX call to filter the data
            console.log('Filtering by kebun:', selectedKebunId);
        }
    });

    // Edit kebun change handler
    $('#edit_kebun_id').on('change', function() {
        const selectedKebunId = $(this).val();
        
        if (selectedKebunId) {
            // Update latitude and longitude based on selected kebun
            // This would typically fetch the coordinates from the server
            $('#edit_latitude').val('');
            $('#edit_longitude').val('');
            
            // Update map if it exists
            if (editMap) {
                // Update map center or marker position
                console.log('Updating map for kebun:', selectedKebunId);
            }
        }
    });

    // Initialize maps when modals are shown
    $('#tjslModal').on('shown.bs.modal', function() {
        if (!lokasiMap) {
            initializeLokasiMap();
        }
    });

    $('#editTjslModal').on('shown.bs.modal', function() {
        if (!editMap) {
            initializeEditMap();
        }
    });

    // Map initialization functions
    function initializeLokasiMap() {
        if ($('#lokasiMap').length) {
            lokasiMap = L.map('lokasiMap').setView([-6.2, 106.8], 10);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(lokasiMap);

            lokasiMap.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                
                if (currentMarker) {
                    lokasiMap.removeLayer(currentMarker);
                }
                
                currentMarker = L.marker([lat, lng]).addTo(lokasiMap);
                
                $('#koordinat').val(`${lat}, ${lng}`);
                $('#latitude').val(lat);
                $('#longitude').val(lng);
            });
        }
    }

    function initializeEditMap() {
        if ($('#editMapContainer').length) {
            editMap = L.map('editMapContainer').setView([-6.2, 106.8], 10);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(editMap);

            editMap.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                
                if (editCurrentMarker) {
                    editMap.removeLayer(editCurrentMarker);
                }
                
                editCurrentMarker = L.marker([lat, lng]).addTo(editMap);
                
                $('#edit_koordinat_display').val(`${lat}, ${lng}`);
                $('#edit_koordinat').val(`${lat}, ${lng}`);
                $('#edit_latitude').val(lat);
                $('#edit_longitude').val(lng);
            });
        }
    }

    // Dynamic form handlers for create modal
    $('#addBiaya').on('click', function() {
        addBiayaItem();
    });

    $('#addPublikasi').on('click', function() {
        addPublikasiItem();
    });

    $('#addDokumentasi').on('click', function() {
        addDokumentasiItem();
    });

    $('#addFeedback').on('click', function() {
        addFeedbackItem();
    });

    // Dynamic form functions
    function addBiayaItem() {
        const html = `
            <div class="biaya-item border rounded p-3 mb-3" data-index="${biayaIndex}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Biaya ${biayaIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-biaya">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jenis Biaya</label>
                            <input type="text" class="form-control" name="biaya[${biayaIndex}][jenis_biaya]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nominal</label>
                            <input type="number" class="form-control" name="biaya[${biayaIndex}][nominal]" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea class="form-control" name="biaya[${biayaIndex}][keterangan]" rows="2"></textarea>
                </div>
            </div>
        `;
        $('#biayaContainer').append(html);
        biayaIndex++;
    }

    function addPublikasiItem() {
        const html = `
            <div class="publikasi-item border rounded p-3 mb-3" data-index="${publikasiIndex}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Publikasi ${publikasiIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-publikasi">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jenis Publikasi</label>
                            <input type="text" class="form-control" name="publikasi[${publikasiIndex}][jenis_publikasi]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Publikasi</label>
                            <input type="date" class="form-control" name="publikasi[${publikasiIndex}][tanggal_publikasi]">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Link/URL</label>
                    <input type="url" class="form-control" name="publikasi[${publikasiIndex}][link_publikasi]">
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="publikasi[${publikasiIndex}][deskripsi]" rows="2"></textarea>
                </div>
            </div>
        `;
        $('#publikasiContainer').append(html);
        publikasiIndex++;
    }

    function addDokumentasiItem() {
        const html = `
            <div class="dokumentasi-item border rounded p-3 mb-3" data-index="${dokumentasiIndex}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Dokumentasi ${dokumentasiIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-dokumentasi">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jenis Dokumen</label>
                            <input type="text" class="form-control" name="dokumentasi[${dokumentasiIndex}][jenis_dokumen]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">File Dokumen</label>
                            <input type="file" class="form-control" name="dokumentasi[${dokumentasiIndex}][file_dokumen]">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="dokumentasi[${dokumentasiIndex}][deskripsi]" rows="2"></textarea>
                </div>
            </div>
        `;
        $('#dokumentasiContainer').append(html);
        dokumentasiIndex++;
    }

    function addFeedbackItem() {
        const html = `
            <div class="feedback-item border rounded p-3 mb-3" data-index="${feedbackIndex}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Feedback ${feedbackIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-feedback">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Sumber Feedback</label>
                            <input type="text" class="form-control" name="feedback[${feedbackIndex}][sumber_feedback]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Feedback</label>
                            <input type="date" class="form-control" name="feedback[${feedbackIndex}][tanggal_feedback]">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Isi Feedback</label>
                    <textarea class="form-control" name="feedback[${feedbackIndex}][isi_feedback]" rows="3" required></textarea>
                </div>
            </div>
        `;
        $('#feedbackContainer').append(html);
        feedbackIndex++;
    }

    // Remove item handlers
    $(document).on('click', '.remove-biaya', function() {
        $(this).closest('.biaya-item').remove();
    });

    $(document).on('click', '.remove-publikasi', function() {
        $(this).closest('.publikasi-item').remove();
    });

    $(document).on('click', '.remove-dokumentasi', function() {
        $(this).closest('.dokumentasi-item').remove();
    });

    $(document).on('click', '.remove-feedback', function() {
        $(this).closest('.feedback-item').remove();
    });

    // Form submission handlers
    $('#tjslForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        let isValid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (isValid) {
            // Submit form
            this.submit();
        }
    });

    $('#editTjslForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        let isValid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (isValid) {
            // Submit form
            this.submit();
        }
    });

    // Initialize on page load
    initWilayahIfExists('lokasi');
    initWilayahIfExists('edit_lokasi');
});