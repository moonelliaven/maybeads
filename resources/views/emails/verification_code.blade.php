<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kode Verifikasi Pendaftaran Maybeads</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f5f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #0a0a0f;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f5f8; padding: 40px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 520px; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(10, 10, 15, 0.06); border: 1px solid #e5e7eb;">
          
          <!-- Header -->
          <tr>
            <td style="padding: 36px 36px 20px; text-align: center; background: linear-gradient(135deg, #1f3bff 0%, #7c3aed 100%);">
              <h1 style="margin: 0; font-size: 28px; font-weight: 800; color: #ffffff; letter-spacing: -0.04em;">
                maybeads<span style="color: #ffe94a;">*</span>
              </h1>
              <p style="margin: 6px 0 0; color: rgba(255, 255, 255, 0.85); font-size: 14px; font-weight: 500;">
                Verifikasi Email Pendaftaran Akun
              </p>
            </td>
          </tr>

          <!-- Body Content -->
          <tr>
            <td style="padding: 36px 36px 28px;">
              <h2 style="margin: 0 0 14px; font-size: 20px; font-weight: 700; color: #0a0a0f;">
                Halo, {{ $name }}! 👋
              </h2>
              <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.6; color: #4b5563;">
                Terima kasih telah mendaftar di <strong>Maybeads</strong>. Untuk menyelesaikan pendaftaran dan mengaktifkan akun Anda, masukkan 6 digit kode verifikasi berikut:
              </p>

              <!-- OTP Code Box -->
              <div style="background-color: #f0f4ff; border: 2px dashed #1f3bff; border-radius: 16px; padding: 22px; text-align: center; margin: 24px 0;">
                <span style="display: block; font-size: 12px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #1f3bff; margin-bottom: 8px;">
                  Kode Verifikasi Anda
                </span>
                <span style="font-size: 38px; font-weight: 800; letter-spacing: 10px; color: #1f3bff; font-family: monospace;">
                  {{ $otp }}
                </span>
              </div>

              <p style="margin: 20px 0 8px; font-size: 14px; line-height: 1.5; color: #6b7280; text-align: center;">
                ⏳ Kode ini hanya berlaku selama <strong>15 menit</strong>.
              </p>

              <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 28px 0 20px;">

              <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #9ca3af;">
                Jika Anda tidak merasa mendaftar di Maybeads, Anda dapat mengabaikan email ini dengan aman. Jangan bagikan kode verifikasi ini kepada siapapun demi keamanan akun Anda.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #fafbfc; padding: 20px 36px; text-align: center; border-top: 1px solid #f1f5f9;">
              <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                &copy; {{ date('Y') }} Maybeads. Semua hak dilindungi undang-undang.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
