<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset PIN Transaksi</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 480px; background-color: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding-bottom: 24px;">
                            <img src="{{ isset($message) ? $message->embed(public_path('images/logo-blue.png')) : asset('images/logo-blue.png') }}" alt="Edubank SMKN 1 Bantul" width="56" height="56" style="display: block; width: 56px; height: 56px; margin: 0 auto; object-fit: contain;" />
                            <h2 style="margin: 12px 0 2px 0; font-size: 18px; font-weight: 800; color: #0f172a; letter-spacing: -0.3px;">Edubank SMKN 1 Bantul</h2>
                            <p style="margin: 0; font-size: 11px; color: #64748b; font-weight: 500;">Sistem Bank Mini Sekolah</p>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="border-top: 1px solid #f1f5f9; padding-top: 24px;">
                            <p style="margin: 0 0 14px 0; font-size: 13px; line-height: 1.5; color: #334155;">
                                Halo <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 13px; line-height: 1.5; color: #475569;">
                                Kami menerima permintaan untuk mengatur ulang <strong>PIN Keamanan Transaksi (6 Digit)</strong> rekening tabungan Anda melalui aplikasi mobile. Gunakan kode verifikasi OTP berikut:
                            </p>
                            
                            <!-- OTP Box -->
                            <div style="background-color: #f1f5f9; border: 1px dashed #cbd5e1; border-radius: 14px; padding: 18px; text-align: center; margin: 22px 0;">
                                <span style="font-family: monospace; font-size: 32px; font-weight: 800; letter-spacing: 6px; color: #0f172a;">{{ $otp }}</span>
                            </div>

                            <p style="margin: 0 0 12px 0; font-size: 11px; color: #e11d48; line-height: 1.4;">
                                <strong>PENTING:</strong> Kode ini hanya berlaku selama <strong>15 menit</strong>. Jangan berikan kode ini kepada siapa pun termasuk petugas bank.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #94a3b8; line-height: 1.4;">
                                Jika Anda tidak meminta reset PIN, abaikan email ini. Rekening Anda tetap aman.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
