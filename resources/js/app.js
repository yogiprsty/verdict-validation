import './bootstrap';
import 'flowbite';

import Alpine from 'alpinejs';

window.updateSubOptions = function() {
    const caseType = document.getElementById('case_type').value;
    const subCaseTypeSelect = document.getElementById('sub_case_type');

    // Clear the existing options
    subCaseTypeSelect.innerHTML = '';

    let options = [];
    if (caseType === 'Gugatan') {
        // Add options for 'Gugatan'
        options = [
            "Cerai Gugat",
            "Cerai Talak",
            "Ekonomi Syariah",
            "Ganti Rugi terhadap Wali",
            "Gugatan Memperoleh Akta Perdamaian atas Kesepakatan Perdamaian di Luar Pengadilan",
            "Hak-hak Bekas Istri/Kewajiban Bekas Suami",
            "Harta Bersama",
            "Hibah",
            "Izin Poligami",
            "Kelalaian atas Kewajiban Istri/Suami",
            "Kewarisan",
            "Nafkah Anak oleh Ibu karena Ayah Tidak Mampu",
            "Pembatalan Perkawinan",
            "Pencabutan Kekuasaan Orang Tua",
            "Pencabutan Kekuasaan Wali",
            "Pengesahan Anak",
            "Pengesahan Perkawinan/Istbat Nikah",
            "Penguasaan Anak",
            "Penunjukan Orang Lain sebagai Wali oleh Pengadilan",
            "Perkawinan Campuran",
            "Wakaf",
            "Wasiat",
            "Gugatan Lainnya"
          ];
    } else if (caseType === 'Permohonan') {
        // Add options for 'Permohonan'
        options = [
            "Dispensasi Kawin",
            "Perwalian",
            "Pengesahan Perkawinan/Istbat Nikah",
            "Izin Kawin",
            "Asal-usul Anak",
            "Ganti Rugi terhadap Wali",
            "P3HP/Penetapan Ahli Waris",
            "Pembatalan Arbitrase Syariah",
            "Pencegahan Perkawinan",
            "Penolakan Perkawinan",
            "Wali Adhol",
            "Permohonan Lainnya"
          ];
    }

    // Populate the subCaseType dropdown with the new options
    options.forEach(option => {
        const optionElement = document.createElement('option');
        optionElement.value = option;
        optionElement.textContent = option;
        subCaseTypeSelect.appendChild(optionElement);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    updateSubOptions();
});

window.Alpine = Alpine;

Alpine.start();
