<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Pemulihan Kata Sandi</title>
</head>
<body style="margin: 0; padding: 24px; background-color: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1E293B;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 540px; margin: 0 auto; background-color: #FFFFFF; border-radius: 20px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05);">
        
        <!-- Header -->
        <tr>
            <td style="padding: 32px 32px 24px; background: linear-gradient(135deg, #1E40AF, #3730A3); text-align: center; color: #FFFFFF;">
                <img src="{{ isset($message) ? $message->embed(public_path('images/logo-white.png')) : asset('images/logo-white.png') }}" alt="Edubank SMKN 1 Bantul" width="52" height="52" style="display: block; width: 52px; height: 52px; margin: 0 auto 12px; object-fit: contain;" />
                <h1 style="margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.5px;">Edubank Bank Mini</h1>
                <p style="margin: 4px 0 0; font-size: 12px; color: #BFDBFE;">SMK Negeri 1 Bantul • Sistem Informasi E-Teller</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 32px;">
                <h2 style="margin: 0 0 12px; font-size: 16px; font-weight: 700; color: #0F172A;">Halo, {{ $user->name }}!</h2>
                <p style="margin: 0 0 20px; font-size: 13px; line-height: 1.6; color: #475569;">
                    Kami menerima permintaan untuk mereset kata sandi akun Edubank Anda. Gunakan kode verifikasi (OTP) berikut untuk melanjutkan proses pembuatan kata sandi baru:
                </p>

                <!-- OTP Box -->
                <div style="background-color: #F8FAFC; border: 2px dashed #93C5FD; border-radius: 16px; padding: 24px 16px; text-align: center; margin: 24px 0;">
                    <span style="display: block; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px;">Kode Verifikasi (OTP) Anda</span>
                    <div style="font-family: 'Courier New', Courier, monospace; font-size: 36px; font-weight: 800; color: #1D4ED8; letter-spacing: 10px; margin: 0; padding-left: 10px;">
                        {{ $otp }}
                    </div>
                    <span style="display: inline-block; margin-top: 10px; font-size: 11px; color: #DC2626; font-weight: 600; background: #FEF2F2; padding: 3px 10px; border-radius: 20px;">
                        Berlaku selama 15 menit
                    </span>
                </div>

                <p style="margin: 0 0 16px; font-size: 12px; line-height: 1.6; color: #64748B;">
                    Masukkan kode ini pada layar verifikasi di browser Anda. Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini dan akun Anda tetap aman.
                </p>

                <div style="border-top: 1px solid #F1F5F9; padding-top: 16px; margin-top: 24px; font-size: 11px; color: #94A3B8; line-height: 1.5;">
                    <strong style="color: #64748B;">Peringatan Keamanan:</strong> Jangan berikan kode OTP ini kepada siapapun, termasuk pihak yang mengaku sebagai petugas bank atau sekolah.
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="padding: 20px 32px; background-color: #F8FAFC; border-top: 1px solid #E2E8F0; text-align: center; font-size: 11px; color: #94A3B8;">
                &copy; {{ date('Y') }} Edubank SMKN 1 Bantul. Seluruh hak cipta dilindungi.
            </td>
        </tr>
    </table>
</body>
</html>
