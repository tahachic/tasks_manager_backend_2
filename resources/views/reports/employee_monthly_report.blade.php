<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقرير الشهري</title>
    <style>
        @font-face {
            font-family: Cairo;
            /* src: url("{{ asset('fonts/Cairo-Regular.ttf') }}"); */

        }

        body {
            font-family: Cairo;
            direction: rtl;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }

        th {
            background-color: #f2f2f2;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>



    @foreach ($tasksByDay as $date => $tasks)
        @if (!$loop->first)
            <div class="page-break"></div>
        @endif
        @if ($employee)
            <h2>التقرير اليومي الخاص ب{{ $employee->name }}</h2>
            <p>المعرف: {{ $employee->id }}</p>
            <p>القسم: {{ $employee->department->name ?? 'Non défini' }}</p>
        @else
            <p>لا يوجد موظف</p>
        @endif
        <h3>قائمة المهام الدائمة</h3>
        <table>
            <tr>
                <th>#</th>
                <th>العنوان</th>
            </tr>
            @forelse($dailyTasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->title }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">لا توجد مهام حاليا</td>
                </tr>
            @endforelse
        </table>

        <h3>قائمة المهام المسندة ليوم {{ $date }}</h3>
        <table>
            <tr>
                <th>#</th>
                <th>العنوان</th>
                <th>الحالة</th>
                <th>الأولوية</th>
                <th>تاريخ الإنشاء</th>
                <th>مصادق عليها</th>
            </tr>
            @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->status == 0
                        ? 'قيد الإنتظار'
                        : ($task->status == 1
                            ? 'قيد الإنجاز'
                            : ($task->status == 2
                                ? 'مشكل'
                                : ($task->status == 3
                                    ? 'تم الإنجاز'
                                    : 'غير محدد'))) }}
                    </td>
                    <td>{{ $task->priority == 0 ? 'غير مستعجلة' : 'مستعجلة' }}</td>
                    <td>{{ $task->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $task->validated == 0 ? 'لا' : 'نعم' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">لا توجد مهام</td>
                </tr>
            @endforelse
        </table>
    @endforeach

</body>

</html>