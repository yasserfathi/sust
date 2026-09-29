<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تصدير المدارس</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 20px;
            direction: rtl;
            text-align: right;
            background-color: #fff;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
        }
        th {
            background-color: #d65540;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            button {
                display: none !important;
            }
        }
        .print-btn {
            background-color: #d65540;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .print-btn:hover {
            background-color: #bd4834;
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="center">
        <button class="print-btn" onclick="window.print()">🖨️ طباعة / حفظ كـ PDF</button>
    </div>

    <h2>قائمة المدارس والكليات</h2>

    <table>
        <thead>
            <tr>
                <th>الرقم</th>
                <th>اسم الكلية</th>
                <th>اسم المدرسة بالعربية</th>
                <th>اسم المدرسة بالإنجليزية</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schools as $school)
            <tr>
                <td>{{ $school->id }}</td>
                <td>{{ $school->college ? $school->college->name : 'بدون كلية' }}</td>
                <td>{{ $school->name }}</td>
                <td dir="ltr" style="text-align: left;">{{ $school->name_en }}</td>
                <td>
                    @if($school->active)
                        <span style="color: green;">نشط</span>
                    @else
                        <span style="color: red;">غير نشط</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
