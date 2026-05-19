<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>السجل الطبي</title>
    <style>
        @page {
            margin: 20px;
            size: A4;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            direction: rtl;
            color: #01052d;
            line-height: 1.8;
            font-size: 12px;
        }

        .pdf-container {
            max-width: 750px;
            margin: 0 auto;
        }

        .pdf-header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 3px solid #0fb3a1;
            margin-bottom: 20px;
        }

        .pdf-header h1 {
            color: #0fb3a1;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .pdf-header p {
            color: #8a93ad;
            font-size: 11px;
            margin: 3px 0;
        }

        .pdf-section {
            margin-bottom: 18px;
        }

        .pdf-section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0fb3a1;
            padding: 8px 12px;
            background-color: #f0fdfa;
            border-right: 4px solid #0fb3a1;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            width: 50%;
            padding: 8px 12px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .info-table .label {
            font-size: 10px;
            font-weight: bold;
            color: #8a93ad;
            display: block;
            margin-bottom: 3px;
        }

        .info-table .value {
            font-size: 12px;
            font-weight: bold;
            color: #01052d;
        }

        .field-box {
            padding: 10px 14px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            margin-bottom: 8px;
            border-radius: 4px;
        }

        .field-box .field-label {
            font-size: 10px;
            font-weight: bold;
            color: #0fb3a1;
            margin-bottom: 4px;
            display: block;
        }

        .field-box .field-value {
            font-size: 12px;
            color: #01052d;
        }

        .field-empty {
            color: #8a93ad;
            font-style: italic;
        }

        .visits-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .visits-table th {
            background-color: #f0fdfa;
            padding: 8px 10px;
            text-align: right;
            font-weight: bold;
            color: #01052d;
            border-bottom: 2px solid #0fb3a1;
        }

        .visits-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .visits-table tr:last-child td {
            border-bottom: none;
        }

        .cell-muted {
            color: #8a93ad;
            font-size: 10px;
        }

        .pdf-footer {
            text-align: center;
            padding-top: 15px;
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #8a93ad;
            font-size: 10px;
        }

        .no-data {
            text-align: center;
            padding: 15px;
            color: #8a93ad;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="pdf-container">
        <div class="pdf-header">
            <h1>السجل الطبي للمريض</h1>
            <p>حجز المواعيد - DentalCare</p>
            <p>تاريخ الطباعة: {{ now()->format('Y/m/d - h:i A') }}</p>
        </div>

        <div class="pdf-section">
            <div class="pdf-section-title">بيانات المريض</div>
            <table class="info-table">
                <tr>
                    <td>
                        <span class="label">الاسم</span>
                        <span class="value">{{ $patient->name }}</span>
                    </td>
                    <td>
                        <span class="label">البريد الإلكتروني</span>
                        <span class="value">{{ $patient->email ?? '—' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">الهاتف</span>
                        <span class="value">{{ $patient->phone_number ?? '—' }}</span>
                    </td>
                    <td>
                        <span class="label">الجنس</span>
                        <span class="value">{{ $genderLabel }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">تاريخ الميلاد</span>
                        <span class="value">{{ $dobFormatted ?? '—' }}</span>
                    </td>
                    <td>
                        <span class="label">العنوان</span>
                        <span class="value">{{ $patient->address ?? '—' }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="pdf-section">
            <div class="pdf-section-title">السجل الطبي</div>

            <div class="field-box">
                <span class="field-label">الأمراض المزمنة</span>
                <span class="field-value {{ empty($medicalRecord->chronic_diseases) ? 'field-empty' : '' }}">
                    {{ $medicalRecord->chronic_diseases ?: '—' }}
                </span>
            </div>

            <div class="field-box">
                <span class="field-label">الحساسية</span>
                <span class="field-value {{ empty($medicalRecord->allergies) ? 'field-empty' : '' }}">
                    {{ $medicalRecord->allergies ?: '—' }}
                </span>
            </div>

            <div class="field-box">
                <span class="field-label">الأدوية الحالية</span>
                <span class="field-value {{ empty($medicalRecord->current_medications) ? 'field-empty' : '' }}">
                    {{ $medicalRecord->current_medications ?: '—' }}
                </span>
            </div>

            <div class="field-box">
                <span class="field-label">العمليات السابقة</span>
                <span class="field-value {{ empty($medicalRecord->past_surgeries) ? 'field-empty' : '' }}">
                    {{ $medicalRecord->past_surgeries ?: '—' }}
                </span>
            </div>

            <div class="field-box">
                <span class="field-label">التاريخ السني</span>
                <span class="field-value {{ empty($medicalRecord->dental_history) ? 'field-empty' : '' }}">
                    {{ $medicalRecord->dental_history ?: '—' }}
                </span>
            </div>

            <div class="field-box">
                <span class="field-label">ملاحظات</span>
                <span class="field-value {{ empty($medicalRecord->notes) ? 'field-empty' : '' }}">
                    {{ $medicalRecord->notes ?: '—' }}
                </span>
            </div>
        </div>

        <div class="pdf-section">
            <div class="pdf-section-title">سجل الزيارات</div>

            @if($visits->isEmpty())
                <div class="no-data">لا توجد زيارات مسجلة بعد.</div>
            @else
                <table class="visits-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">التاريخ</th>
                            <th style="width: 25%;">التشخيص</th>
                            <th style="width: 25%;">العلاج</th>
                            <th style="width: 35%;">ملاحظات / الخطة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visits as $visit)
                            <tr>
                                <td>{{ $visit->visit_date ?? '—' }}</td>
                                <td>{{ $visit->diagnosis ?? '—' }}</td>
                                <td class="cell-muted">{{ $visit->treatment ?? '—' }}</td>
                                <td class="cell-muted">{{ $visit->next_plan ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="pdf-footer">
            <p>تم إنشاء هذا التقرير تلقائيًا من نظام حجز المواعيد</p>
            <p>© {{ date('Y') }} DentalCare - جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
