<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تصدير الأقسام</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

    <h2>قائمة الأقسام والكليات</h2>

    <table>
        <thead>
            <tr>
                <th>الرقم</th>
                <th>اسم الكلية</th>
                <th>اسم القسم بالعربية</th>
                <th>اسم القسم بالإنجليزية</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $dept)
            <tr>
                <td>{{ $dept->id }}</td>
                <td>{{ $dept->college ? $dept->college->name : 'بدون كلية' }}</td>
                <td>{{ $dept->name }}</td>
                <td dir="ltr" style="text-align: left;">{{ $dept->name_en }}</td>
                <td>
                    @if($dept->active)
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
