<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Akun - MotoPart Store</title>
</head>
<body style="margin:0;padding:0;background-color:#F8FAFC;font-family:'Plus Jakarta Sans',Arial,sans-serif;color:#1E293B;line-height:1.6;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F8FAFC;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background-color:#FFFFFF;border-radius:12px;overflow:hidden;border:1px solid #E2E8F0;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#1E293B;border-bottom:3px solid #DC2626;padding:20px 24px;">
                            <div style="font-size:20px;font-weight:700;color:#FFFFFF;letter-spacing:-0.01em;">
                                MotoPart <span style="color:#DC2626;">Store</span>
                            </div>
                            <div style="font-size:12px;color:#CBD5E1;margin-top:2px;">
                                Suku Cadang Motor Original &amp; Booking Bengkel
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 12px 0;font-size:16px;font-weight:600;color:#0F172A;">
                                Halo {{ $user->name ?? 'Pelanggan' }},
                            </p>

                            <p style="margin:0 0 14px 0;font-size:14px;color:#334155;">
                                Kami menerima permintaan untuk mengatur ulang password akun MotoPart Store Anda. Klik tombol di bawah ini untuk membuat password baru.
                            </p>

                            <p style="margin:0 0 20px 0;font-size:13px;color:#475569;">
                                Link reset password ini berlaku selama <strong>{{ $expireMinutes }} menit</strong>.
                            </p>

                            <div style="text-align:center;margin:24px 0;">
                                <a href="{{ $resetUrl }}"
                                   style="display:inline-block;background-color:#DC2626;color:#FFFFFF;text-decoration:none;font-weight:700;font-size:14px;padding:12px 24px;border-radius:8px;">
                                    Atur Ulang Password
                                </a>
                            </div>

                            <div style="background-color:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;padding:12px 16px;margin:20px 0 14px 0;">
                                <div style="font-size:12px;font-weight:600;color:#475569;margin-bottom:4px;">
                                    Jika tombol di atas tidak berfungsi, salin dan buka tautan berikut di browser Anda:
                                </div>
                                <a href="{{ $resetUrl }}" style="font-size:12px;color:#B91C1C;word-break:break-all;">
                                    {{ $resetUrl }}
                                </a>
                            </div>

                            <p style="margin:0;font-size:13px;color:#64748B;">
                                Jika Anda tidak merasa meminta reset password, abaikan email ini. Password akun Anda tidak akan berubah.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#0F172A;padding:16px 24px;text-align:center;font-size:12px;color:#94A3B8;">
                            MotoPart Store | Jl. Otomotif Raya No. 88, Jakarta | support@motopartstore.id
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
