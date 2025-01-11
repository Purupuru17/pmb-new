#PMB UNIMUDA
app : 

Note :
1. Perbaiki nama variabel validation, load_view, load_file
2. Perbaiki icon aksi tampilan tabel

Cek : 
1. Perbaiki input tgl lahir cek NULL, di bawah tahun 15 tahun ke belakang - done
2. Update angkatan pas kapan - done (Register)
3. Selesai seleksi, tentukan prodi, nim, VA dan harus bayar 800rbu via VA - done
6. Nama, Ibu, Tempat Lahir, Alamat  : Filter kapitalis - done
7. Hilangkan Nilai Interview -done
8. Nama Lengkap sesuia Ijazah, Telepon Aktif - done
4. Field kelurahan & email nyusul, Kecamatan, Kabupaten - done

5. Biaya & Rincian Registrasi ulang

Status : 
1. Pendaftaran
2. Upload Bukti, Seleksi
3. Ikut Tes, Lulus dapat Prodi,NIM dkk | Belum Lulus
4. Cek sdh her registrasi apa blm, kalo sdh : Aktif

SQL DATA LAMA MHS
SELECT * FROM `m_mhs` m
LEFT JOIN m_berkas b ON b.mhs_id = m.id_mhs
LEFT JOIN m_ortu o ON o.mhs_id = m.id_mhs
LEFT JOIN m_profil p ON p.mhs_id = m.id_mhs
LEFT JOIN m_tmp t ON t.mhs_id = m.id_mhs
LEFT JOIN yk_user u ON u.id_user = t.user_id
WHERE m.id_mhs = 1545

410 email
410 tgl_lahir
Where email = 'novitaramber1510@gemail.com' - 409

SELECT m.id_mhs, m.nama_mhs, m.tempat_lahir, m.tgl_lahir, m.kelamin_mhs, m.agama, m.email_mhs, m.alamat_mhs, m.kelurahan, m.kecamatan, m.kabupaten,
 t.nama_mhs, t.tempat_lahir, t.tgl_lahir, t.kelamin_mhs, t.agama, t.email_mhs, t.alamat_mhs, t.kelurahan, t.kecamatan, t.kabupaten
FROM m_mhs m
INNER JOIN m_mhs_t t ON t.id_mhs = m.id_mhs

UPDATE m_mhs m
INNER JOIN m_mhs_bc t ON t.id_mhs = m.id_mhs
SET m.log_mhs = t.log_mhs
WHERE m.verify_by = '1'

UPDATE m_mhs m
INNER JOIN m_profil p ON m.id_mhs = p.mhs_id
SET m.atribut_mhs = p.reg_ulang

PMB V.2022
1. Ubah menu dan controller di mahasiswa - done
2. Upload berkas ke s3 with progress - done
3. Status berkas, get kode daftar+kode berkas - done
4. List berkas di master dan Mhs - done
5. Info soal upload pembayaran, rekening dan syarat beasiswa - done
6. Foto upload s3 with progress - done
7. Cek empty encode decode - done
8. Filter edit camat, bupati kalau bukan angka berarti empty - done
9. Buat NIM tambahkan tahun angkatan - done
10. Validasi berkas mhs - done
11. Migrasi database - done
12. Statistik fakultas, statistik sekolah dan filter grafik prodi
13. List yang sudah upload berkas - done
14. Ingat soal periode waktu input feeder (sdh via angkatan) - done
15. Posisi kolom Ibu Kandung dan Edit status ketika sekolah sdh terisi - done
16. Email tidak tersimpan di akun (Daftar_do, Profil edit) - done


Bug
- id bio input manual, jaga2 history pend dobel
- cek id mahasiswa ada nggk wkt insert - done
- angkatan post data/pilih periode - done
- kalo bio sudah ada, gmn cara input history
- input alih jenjang 1 baru, 2 alih jenjang : Insert sprt siakad
- UUID
- Orangtua ayah ibu jadi 1 tabel
- Export status KIP dan orang tua
- Tambah export seleksi dan daftar