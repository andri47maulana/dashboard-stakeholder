// Ensure jQuery is loaded before executing
if (typeof jQuery === 'undefined') {
    console.error('jQuery is not loaded! TJSL Scripts cannot initialize.');
} else {
    $(document).ready(function() {
        console.log('TJSL Scripts loaded - Version 2.4 - ' + new Date().toISOString());

    // Global variables untuk data
    let allSubpilars = [];
    let programUnggulanMap = {};

    // Disable the edit button handler in tjsl-scripts.js to avoid conflicts
    // $(document).on('click', '.edit-program-btn', function(e) {
    //     e.preventDefault(); // Prevent default Bootstrap modal behavior
    //     e.stopPropagation(); // Stop event bubbling
    //
    //     const tjslId = $(this).data('id');
    //     console.log('Edit button clicked from tjsl-scripts.js, TJSL ID:', tjslId);
    //     console.log('Triggering custom event editButtonClicked');
    //
    //     // Trigger custom event that can be handled by index-backup.blade.php
    //     $(document).trigger('editButtonClicked', [tjslId]);
    //     console.log('Custom event triggered');
    // });
    let allUnits = [];

    // Global variables untuk dynamic forms
    let biayaIndex = 1;
    let publikasiIndex = 1;
    let dokumentasiIndex = 1;
    let feedbackIndex = 1;
    let editBiayaIndex = 1;
    let editPublikasiIndex = 1;
    let editDokumentasiIndex = 1;
    let editFeedbackIndex = 1;

    // Global variables untuk maps
    let lokasiMap = null;
    let editMap = null;
    let currentMarker = null;
    let editCurrentMarker = null;

    // Initialize data dari server
    function initializeData() {
        // Ambil data sub pilar dari server jika tersedia
        if (typeof window.subpilarsData !== 'undefined') {
            allSubpilars = window.subpilarsData;
        }

        // Ambil data units dari server jika tersedia
        if (typeof window.unitsData !== 'undefined') {
            allUnits = window.unitsData;
        }

        // Build program unggulan mapping
        $('#program_unggulan_id option, #edit_program_unggulan_id option').each(function() {
            const id = $(this).val();
            if (!id) return;

            let subPilars = $(this).data('subpilars');
            if (typeof subPilars === 'string') {
                try {
                    subPilars = JSON.parse(subPilars);
                } catch (e) {
                    subPilars = [];
                }
            }
            programUnggulanMap[id] = Array.isArray(subPilars) ? subPilars.map(String) : [];
        });

        console.log('Data initialized:', {
            subpilars: allSubpilars.length,
            programUnggulan: Object.keys(programUnggulanMap).length,
            units: allUnits.length
        });
    }

    // Initialize Leaflet Map untuk Create Modal
    function initializeLokasiMap() {
        if (lokasiMap) {
            lokasiMap.remove();
            lokasiMap = null;
        }

        if (!$('#lokasiMap').length) {
            console.warn('Element #lokasiMap not found');
            return;
        }

        console.log('Initializing lokasi map');

        try {
            lokasiMap = L.map('lokasiMap').setView([-6.2088, 106.8456], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
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

                console.log('Marker placed at:', lat, lng);
            });

            // Force map to resize after initialization
            setTimeout(function() {
                if (lokasiMap) {
                    lokasiMap.invalidateSize();
                }
            }, 100);

        } catch (error) {
            console.error('Error initializing lokasi map:', error);
        }
    }

    // Initialize Leaflet Map untuk Edit Modal
    function initializeEditMap() {
        if (editMap) {
            editMap.remove();
            editMap = null;
        }

        if (!$('#editMapContainer').length) {
            console.warn('Element #editMapContainer not found');
            return;
        }

        console.log('Initializing edit map');

        try {
            editMap = L.map('editMapContainer').setView([-6.2088, 106.8456], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
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

                console.log('Edit marker placed at:', lat, lng);
            });

            // Force map to resize after initialization
            setTimeout(function() {
                if (editMap) {
                    editMap.invalidateSize();
                }
            }, 100);

        } catch (error) {
            console.error('Error initializing edit map:', error);
        }
    }

    // Initialize Select2 dengan konfigurasi yang robust
    function initializeSelect2(selector, placeholder, parentModal) {
        const element = $(selector);

        if (!element.length) {
            console.warn('Element not found:', selector);
            return false;
        }

        // Destroy existing Select2 jika ada
        if (element.hasClass('select2-hidden-accessible')) {
            element.select2('destroy');
        }

        console.log('Initializing Select2 for:', selector);

        element.select2({
            placeholder: placeholder,
            allowClear: true,
            dropdownParent: $(parentModal),
            width: '100%',
            minimumInputLength: 0,
            language: {
                searching: function() {
                    return 'Mencari...';
                },
                noResults: function() {
                    return 'Tidak ada hasil ditemukan';
                },
                inputTooShort: function() {
                    return 'Ketik untuk mencari';
                }
            },
            escapeMarkup: function(markup) {
                return markup;
            }
        });

        // Event handler untuk memastikan search field berfungsi
        element.on('select2:open', function() {
            setTimeout(function() {
                const searchField = $('.select2-search__field');
                if (searchField.length) {
                    searchField.focus();
                    // Pastikan styling yang benar
                    searchField.css({
                        'color': '#495057',
                        'background-color': '#fff',
                        'opacity': '1',
                        'visibility': 'visible',
                        'border': '1px solid #ced4da',
                        'padding': '4px 8px'
                    });
                    console.log('Search field focused and styled');
                }
            }, 100);
        });

        return true;
    }

    // Update Sub Pilar options berdasarkan Program Unggulan
    function updateSubPilarOptions(selectId, allowedIds) {
        const $select = $(selectId);
        const currentValues = $select.val() || [];

        // Destroy Select2 sementara
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select.empty();

        // Filter sub pilars berdasarkan allowed IDs
        let filteredSubpilars = allSubpilars;
        if (allowedIds && allowedIds.length > 0) {
            filteredSubpilars = allSubpilars.filter(sp => allowedIds.includes(String(sp.id)));
        }

        // Sort berdasarkan ID
        filteredSubpilars.sort((a, b) => parseInt(a.id) - parseInt(b.id));

        // Tambahkan options
        filteredSubpilars.forEach(function(subPilar) {
            $select.append(`<option value="${subPilar.id}">${subPilar.id}.${subPilar.sub_pilar}</option>`);
        });

        // Pertahankan nilai yang masih valid
        const validValues = currentValues.filter(val =>
            filteredSubpilars.some(sp => String(sp.id) === String(val))
        );

        $select.val(validValues);

        // Re-initialize Select2
        const parentModal = selectId.includes('edit') ? '#editTjslModal' : '#tjslModal';
        initializeSelect2(selectId, 'Pilih TPB', parentModal);

        console.log('Sub pilar options updated for:', selectId, 'Filtered count:', filteredSubpilars.length);
    }

    // Program Unggulan change handlers
    $('#program_unggulan_id').on('change', function() {
        const selectedId = $(this).val();
        const allowedSubpilars = selectedId ? programUnggulanMap[selectedId] : [];

        console.log('Program unggulan changed:', selectedId, 'Allowed subpilars:', allowedSubpilars);
        updateSubPilarOptions('#sub_pilar', allowedSubpilars);
        updateBiayaSubPilarOptions(allowedSubpilars);
    });

    $('#edit_program_unggulan_id').on('change', function() {
        const selectedId = $(this).val();
        const allowedSubpilars = selectedId ? programUnggulanMap[selectedId] : [];

        console.log('Edit program unggulan changed:', selectedId, 'Allowed subpilars:', allowedSubpilars);
        updateSubPilarOptions('#edit_sub_pilar', allowedSubpilars);
        updateEditBiayaSubPilarOptions(allowedSubpilars);
    });

    // Update Biaya Sub Pilar options
    function updateBiayaSubPilarOptions(allowedIds) {
        $('.biaya-sub-pilar').each(function() {
            const $select = $(this);
            const currentValue = $select.val();

            $select.empty().append('<option value="">Pilih Sub Pilar</option>');

            let filteredSubpilars = allSubpilars;
            if (allowedIds && allowedIds.length > 0) {
                filteredSubpilars = allSubpilars.filter(sp => allowedIds.includes(String(sp.id)));
            }

            filteredSubpilars.sort((a, b) => parseInt(a.id) - parseInt(b.id));

            filteredSubpilars.forEach(function(subPilar) {
                $select.append(`<option value="${subPilar.id}">${subPilar.id}.${subPilar.sub_pilar}</option>`);
            });

            // Pertahankan nilai jika masih valid
            if (currentValue && filteredSubpilars.some(sp => String(sp.id) === String(currentValue))) {
                $select.val(currentValue);
            }
        });
    }

    function updateEditBiayaSubPilarOptions(allowedIds) {
        $('.edit-biaya-sub-pilar').each(function() {
            const $select = $(this);
            const currentValue = $select.val();

            $select.empty().append('<option value="">Pilih Sub Pilar</option>');

            let filteredSubpilars = allSubpilars;
            if (allowedIds && allowedIds.length > 0) {
                filteredSubpilars = allSubpilars.filter(sp => allowedIds.includes(String(sp.id)));
            }

            filteredSubpilars.sort((a, b) => parseInt(a.id) - parseInt(b.id));

            filteredSubpilars.forEach(function(subPilar) {
                $select.append(`<option value="${subPilar.id}">${subPilar.id}.${subPilar.sub_pilar}</option>`);
            });

            if (currentValue && filteredSubpilars.some(sp => String(sp.id) === String(currentValue))) {
                $select.val(currentValue);
            }
        });
    }

    // Modal event handlers
    $('#tjslModal').on('show.bs.modal', function() {
        console.log('Create modal opening');
        setTimeout(function() {
            initializeSelect2('#sub_pilar', 'Pilih TPB', '#tjslModal');
            initializeSelect2('#unit_id', 'Pilih Unit/Kebun', '#tjslModal');

            // Reset form dan filter
            $('#program_unggulan_id').val('').trigger('change');
        }, 200);
    });

    // Initialize map when program tab is shown
    $('#program-tab').on('shown.bs.tab', function() {
        console.log('Program tab shown, initializing map');
        setTimeout(function() {
            initializeLokasiMap();
        }, 100);
    });

    // Initialize map when modal is fully shown
    $('#tjslModal').on('shown.bs.modal', function() {
        console.log('Create modal fully shown');
        setTimeout(function() {
            initializeLokasiMap();
        }, 300);
    });

    $('#editTjslModal').on('show.bs.modal', function() {
        console.log('Edit modal opening');
        setTimeout(function() {
            initializeSelect2('#edit_sub_pilar', 'Pilih TPB', '#editTjslModal');
            initializeSelect2('#edit_unit_id', 'Pilih Unit/Kebun', '#editTjslModal');

            // Apply filter berdasarkan program unggulan yang terpilih
            const selectedProgramId = $('#edit_program_unggulan_id').val();
            if (selectedProgramId) {
                const allowedSubpilars = programUnggulanMap[selectedProgramId] || [];
                updateSubPilarOptions('#edit_sub_pilar', allowedSubpilars);
            }
        }, 200);
    });

    // Initialize edit map when modal is fully shown
    $('#editTjslModal').on('shown.bs.modal', function() {
        console.log('Edit modal fully shown');
        setTimeout(function() {
            initializeEditMap();

            // Set coordinates if they were stored from edit button click
            if (window.editCoordinates) {
                setTimeout(function() {
                    if (editMap) {
                        console.log('Setting stored coordinates on map:', window.editCoordinates);

                        // Force map to resize
                        editMap.invalidateSize();

                        // Set view and marker
                        editMap.setView([window.editCoordinates.lat, window.editCoordinates.lng], 15);

                        // Remove existing marker
                        if (editCurrentMarker) {
                            editMap.removeLayer(editCurrentMarker);
                        }

                        // Add new marker
                        editCurrentMarker = L.marker([window.editCoordinates.lat, window.editCoordinates.lng]).addTo(editMap);

                        console.log('Coordinates set on edit map successfully');
                    }
                }, 500); // Additional delay to ensure map is fully rendered
            }
        }, 300);
    });

    // Handle coordinate input for create modal
    $('#koordinat').on('input', function() {
        const koordinat = $(this).val();
        const parts = koordinat.split(',');

        if (parts.length === 2) {
            const lat = parseFloat(parts[0].trim());
            const lng = parseFloat(parts[1].trim());

            if (!isNaN(lat) && !isNaN(lng)) {
                $('#latitude').val(lat);
                $('#longitude').val(lng);

                if (lokasiMap) {
                    if (currentMarker) {
                        lokasiMap.removeLayer(currentMarker);
                    }
                    currentMarker = L.marker([lat, lng]).addTo(lokasiMap);
                    lokasiMap.setView([lat, lng], 15);
                }
            }
        }
    });

    // Handle coordinate input for edit modal
    $('#edit_koordinat_display').on('input', function() {
        const koordinat = $(this).val();
        const parts = koordinat.split(',');

        if (parts.length === 2) {
            const lat = parseFloat(parts[0].trim());
            const lng = parseFloat(parts[1].trim());

            if (!isNaN(lat) && !isNaN(lng)) {
                $('#edit_latitude').val(lat);
                $('#edit_longitude').val(lng);
                $('#edit_koordinat').val(`${lat}, ${lng}`);

                if (editMap) {
                    if (editCurrentMarker) {
                        editMap.removeLayer(editCurrentMarker);
                    }
                    editCurrentMarker = L.marker([lat, lng]).addTo(editMap);
                    editMap.setView([lat, lng], 15);
                }
            }
        }
    });

    // CSS untuk memastikan search field terlihat
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .select2-search__field {
                color: #495057 !important;
                background-color: #fff !important;
                border: 1px solid #ced4da !important;
                padding: 4px 8px !important;
                font-size: 14px !important;
                line-height: 1.5 !important;
                opacity: 1 !important;
                visibility: visible !important;
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .select2-search__field:focus {
                outline: none !important;
                border-color: #80bdff !important;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
            }
            .select2-dropdown .select2-search {
                padding: 4px !important;
            }
        `)
        .appendTo('head');

    // Event handler untuk input di search field
    $(document).on('input keyup paste', '.select2-search__field', function(e) {
        console.log('Input detected:', e.type, 'Value:', $(this).val());
        // Pastikan styling tetap benar
        $(this).css({
            'color': '#495057',
            'background-color': '#fff',
            'opacity': '1',
            'visibility': 'visible'
        });
    });

    // Initialize data saat document ready
    initializeData();

    // Wilayah Select2 Group Functions
    function resetSelect2(selector) {
        if ($(selector).hasClass('select2-hidden-accessible')) {
            $(selector).select2('destroy');
        }
        $(selector).empty();
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
        }).fail(function() {
            console.error('Failed to load provinsi data');
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
        initWilayahSelect2Group(prefix);
    }

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
                            <label class="form-label">Anggaran (Rp)</label>
                            <input type="number" class="form-control" name="biaya[${biayaIndex}][anggaran]" step="0.01" placeholder="0.00">
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
            <div class="publikasi-item border p-3 mb-3 rounded bg-light">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Media</label>
                        <input type="text" class="form-control" name="publikasi[${publikasiIndex}][media]" placeholder="Nama Media">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Link</label>
                        <input type="url" class="form-control" name="publikasi[${publikasiIndex}][link]" placeholder="https://...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-publikasi">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#publikasiContainer').append(html);
        publikasiIndex++;
        updateRemoveButtons();
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
                            <label class="form-label">Tanggal Upload</label>
                            <input type="date" class="form-control" name="dokumentasi[${dokumentasiIndex}][tanggal_upload]">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Link/URL</label>
                    <input type="url" class="form-control" name="dokumentasi[${dokumentasiIndex}][link_dokumen]">
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
        updateRemoveButtons();
    });

    $(document).on('click', '.remove-dokumentasi', function() {
        $(this).closest('.dokumentasi-item').remove();
    });

    $(document).on('click', '.remove-feedback', function() {
        $(this).closest('.feedback-item').remove();
    });

    // Update remove buttons for publikasi
    function updateRemoveButtons() {
        const publikasiItems = $('.publikasi-item');
        publikasiItems.each(function(index) {
            const removeBtn = $(this).find('.remove-publikasi');
            if (publikasiItems.length === 1) {
                removeBtn.prop('disabled', true);
            } else {
                removeBtn.prop('disabled', false);
            }
        });
    }

    function addEditPublikasiItem() {
        const html = `
            <div class="publikasi-item border p-3 mb-3 rounded bg-light">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Media</label>
                        <input type="text" class="form-control" name="publikasi[${editPublikasiIndex}][media]" placeholder="Nama Media">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Link</label>
                        <input type="url" class="form-control" name="publikasi[${editPublikasiIndex}][link]" placeholder="https://...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-edit-publikasi">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#editPublikasiContainer').append(html);
        editPublikasiIndex++;
        updateEditRemoveButtons();
    }

    function updateEditRemoveButtons() {
        const publikasiItems = $('#editPublikasiContainer .publikasi-item');
        publikasiItems.each(function(index) {
            const removeBtn = $(this).find('.remove-edit-publikasi');
            if (publikasiItems.length === 1) {
                removeBtn.prop('disabled', true);
            } else {
                removeBtn.prop('disabled', false);
            }
        });
    }

    // Dynamic form handlers for create modal
    $(document).on('click', '#addBiaya', function() {
        addBiayaItem();
    });

    $(document).on('click', '#addPublikasi', function() {
        addPublikasiItem();
    });

    $(document).on('click', '#addDokumentasi', function() {
        addDokumentasiItem();
    });

    $(document).on('click', '#addFeedback', function() {
        addFeedbackItem();
    });

    // Dynamic form handlers for edit modal
    $(document).on('click', '#editAddPublikasi', function() {
        addEditPublikasiItem();
    });

    $(document).on('click', '.remove-edit-publikasi', function() {
        $(this).closest('.publikasi-item').remove();
        updateEditRemoveButtons();
    });

    setTimeout(function() {
        if ($('#sub_pilar').length) {
            initializeSelect2('#sub_pilar', 'Pilih TPB', '#tjslModal');
        }
        if ($('#unit_id').length) {
            initializeSelect2('#unit_id', 'Pilih Unit/Kebun', '#tjslModal');
        }
        if ($('#edit_sub_pilar').length) {
            initializeSelect2('#edit_sub_pilar', 'Pilih TPB', '#editTjslModal');
        }
        if ($('#edit_unit_id').length) {
            initializeSelect2('#edit_unit_id', 'Pilih Unit/Kebun', '#editTjslModal');
        }

        // Initialize wilayah dropdowns
        if ($('#lokasi_provinsi').length) {
            initWilayahSelect2Group('lokasi');
        }
        if ($('#edit_lokasi_provinsi').length) {
            initWilayahSelect2Group('edit_lokasi');
        }
    }, 500);

    console.log('TJSL Scripts initialization complete');
    });
}
