@php
    $brand = $brand ?? config('jobportal.brand_name', config('app.name'));
    $name = $user?->name ?? 'there';
    $primaryColor = $primaryColor ?? '#2a7190';
    $homeUrl = $homeUrl ?? url('/');
    $logoUrl = $logoUrl ?? asset('images/nas-logo-cropped.webp');
    $content = $content ?? \App\Support\EmailContent::load()['welcome'];
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ $brand }}</title>
</head>
<body style="margin:0;background:#f3f6f8;color:#172033;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f6f8;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #dbe4ea;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#0f3f4f;padding:24px 28px;">
                            <a href="{{ $homeUrl }}" style="display:inline-block;">
                                <img src="{{ $logoUrl }}" alt="{{ $brand }}" width="132" style="display:block;max-width:132px;height:auto;border:0;">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 28px;">
                            <p style="margin:0 0 10px;font-size:14px;font-weight:700;color:{{ $primaryColor }};text-transform:uppercase;">{{ $content['eyebrow'] }}</p>
                            <h1 style="margin:0 0 16px;font-size:26px;line-height:1.25;color:#172033;">{{ str_replace('{name}', $name, $content['heading']) }}</h1>
                            <p style="margin:0 0 18px;font-size:16px;line-height:1.6;">{{ $content['intro'] }}</p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:20px 0;border:1px solid #dbe4ea;border-radius:8px;">
                                <tr>
                                    <td style="padding:16px;font-size:14px;line-height:1.6;color:#526173;">{{ $content['body'] }}</td>
                                </tr>
                            </table>
                            <p style="margin:26px 0;">
                                <a href="{{ $dashboardUrl }}" style="display:inline-block;background:{{ $primaryColor }};color:#ffffff;text-decoration:none;font-weight:700;padding:13px 20px;border-radius:6px;">{{ $content['button_label'] }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#eef4f6;padding:18px 28px;font-size:12px;line-height:1.6;color:#5b6776;">
                            {{ $content['footer'] }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
