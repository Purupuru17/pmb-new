#NOTE

Rev
- NIM status masih bug saat s1 jadi s2, validate ny jg bug -> (done)
- Edit NIM di Seleksi TAB -> (done)
- TAmbah VA masih bug saat ganti mhs -> (done)
- config kode reg -> (done)
- JS includes bagian register yg s2  -> (done)

- Hapus payment
- Edit Sesi
- Tambah Course
- Cek kembali/optimasi saat mulai pngerjaan, bug ragu2
- Grafik payment
- Menu home menumpuk tidak bs d klik
- Edit NIM kalau maba pindah prodi, lgkap sprt Neo ada kampus asal


UPDATE m_mhs m
JOIN (
    SELECT CONCAT('1486904253', LPAD(ROW_NUMBER() OVER (ORDER BY nama_mhs), 4, '0')) AS new_nim,
    SELECT CONCAT('1486904251', LPAD(ROW_NUMBER() OVER (ORDER BY kode_reg) + 850, 4, '0')) AS new_nim, (dari 850 k atas)
	id_mhs, nama_mhs, kode_reg, angkatan, prodi_id
    FROM m_mhs
    WHERE prodi_id = '2edd800d-c39c-4bb2-8c85-c1a1e3f3cf40' AND angkatan = '2025' AND status_mhs = 'VALID'
) t ON m.id_mhs = t.id_mhs
SET m.nim = t.new_nim;