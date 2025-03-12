<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقرير الشهري</title>

    <!-- Load Bootstrap Locally -->
    <link href="{{ public_path('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/Cairo-Regular.ttf') }}") format('truetype'),
                 url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            text-align: right;
            background-color: #f8f9fa;
        }

        .container {
            background: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

<div class="container">

    @foreach ($tasksByDay as $date => $tasks)
        @if (!$loop->first)
            <div class="page-break"></div>
        @endif

        @if ($employee)
            <div class="mb-4 text-center">
                <h2 class="text-primary">التقرير اليومي الخاص ب{{ $employee->name }}</h2>
                <p><strong>المعرف:</strong> {{ $employee->id }}</p>
                <p><strong>القسم:</strong> {{ $employee->department->name ?? 'غير محدد' }}</p>
            </div>
        @else
            <p class="alert alert-warning text-center">لا يوجد موظف</p>
        @endif

        <!-- قائمة المهام الدائمة -->
        <h3 class="mt-4 text-secondary">قائمة المهام الدائمة</h3>
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>العنوان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailyTasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->title }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">لا توجد مهام حاليا</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- قائمة المهام ليوم معين -->
        <h3 class="mt-4 text-success">قائمة المهام المسندة ليوم {{ $date }}</h3>
        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>العنوان</th>
                    <th>الحالة</th>
                    <th>الأولوية</th>
                    <th>تاريخ الإنشاء</th>
                    <th>مصادق عليها</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->title }}</td>
                        <td>
                            @switch($task->status)
                                @case(0) <span class="badge bg-warning">قيد الإنتظار</span> @break
                                @case(1) <span class="badge bg-primary">قيد الإنجاز</span> @break
                                @case(2) <span class="badge bg-danger">مشكل</span> @break
                                @case(3) <span class="badge bg-success">تم الإنجاز</span> @break
                                @default <span class="badge bg-secondary">غير محدد</span>
                            @endswitch
                        </td>
                        <td>
                            @if ($task->priority == 0)
                                <span class="badge bg-secondary">غير مستعجلة</span>
                            @else
                                <span class="badge bg-danger">مستعجلة</span>
                            @endif
                        </td>
                        <td>{{ $task->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if ($task->validated == 0)
                                <span class="badge bg-danger">لا</span>
                            @else
                                <span class="badge bg-success">نعم</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">لا توجد مهام</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @endforeach

</div>

<!-- Load Bootstrap JS Locally -->
<script src="{{ public_path('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
