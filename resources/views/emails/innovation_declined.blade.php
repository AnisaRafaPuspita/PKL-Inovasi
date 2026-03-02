<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
</head>
<body style="margin:0;padding:0;background:#f6f8ff;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f6f8ff;padding:24px 0;">
    <tr>
      <td align="center" style="padding:0 12px;">
        <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;">

          <!-- Header -->
          <tr>
            <td style="background:#061a4d;border-radius:14px 14px 0 0;padding:18px 20px;">
              <div style="color:#ffffff;font-weight:800;font-size:16px;">
                Direktorat Inovasi dan Hilirisasi
              </div>
              <div style="color:#e9eeff;font-size:12px;margin-top:4px;">
                Notifikasi Sistem Inovasi
              </div>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="background:#ffffff;border:1px solid #dbe3ff;border-top:none;
                       border-radius:0 0 14px 14px;padding:20px;">

              @php
                $firstInnovatorName = optional($innovation->innovators->first())->name;
              @endphp

              <div style="font-size:18px;font-weight:800;color:#061a4d;margin-bottom:10px;">
                Perbaikan Diperlukan: Inovasi Ditolak
              </div>

              <div style="font-size:14px;line-height:1.6;margin-bottom:14px;">
                Yth. <b>{{ $firstInnovatorName ?? 'Inovator' }}</b>,
                <br><br>
                Pengajuan inovasi dengan judul
                <b style="color:#061a4d;">{{ $innovation->title }}</b>
                saat ini <b>ditolak</b> dan memerlukan perbaikan.
              </div>

              <div style="font-size:14px;font-weight:800;color:#061a4d;margin-bottom:8px;">
                Catatan:
              </div>

              <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;
                          padding:12px;font-size:14px;line-height:1.6;
                          white-space:pre-line;margin-bottom:16px;">
                {{ $reason }}
              </div>

              <div style="font-size:14px;line-height:1.6;">
                Silakan lakukan perbaikan sesuai catatan di atas,
                lalu ajukan kembali inovasi Anda.
                <br><br>
                Terima kasih.
              </div>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:14px 4px 0 4px;text-align:center;
                       font-size:11px;color:#64748b;">
              Email ini dikirim otomatis oleh sistem.
              Mohon tidak membalas email ini.
              <br>
              © {{ date('Y') }} Direktorat Inovasi dan Hilirisasi
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>