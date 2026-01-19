@extends('admin.layouts.app')

@section('title', 'Attempt Details')
@section('page-title', 'Attempt Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.exam-results.index') }}">Exam Results</a></li>
    <li class="breadcrumb-item active">Attempt #{{ $attempt->id }}</li>
@endsection

@section('content')
    <!-- Student & Exam Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Student Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 120px">Name:</th>
                            <td>{{ $attempt->student->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $attempt->student->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $attempt->student->phone ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt"></i> Exam Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th style="width: 120px">Exam Title:</th>
                            <td>{{ $attempt->exam->title }}</td>
                        </tr>
                        <tr>
                            <th>Attempt Number:</th>
                            <td>{{ $attempt->attempt_number }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $attempt->created_at->format('Y-m-d h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Summary -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Score Summary</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="p-3 border rounded">
                        <h6 class="text-muted">Total Questions</h6>
                        <h2 class="text-primary">{{ $totalQuestions }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded">
                        <h6 class="text-muted">Correct Answers</h6>
                        <h2 class="text-success">{{ $correctAnswers }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded">
                        <h6 class="text-muted">Wrong Answers</h6>
                        <h2 class="text-danger">{{ $wrongAnswers }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded">
                        <h6 class="text-muted">Final Score</h6>
                        <h2 class="{{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                            {{ $attempt->score }} / {{ $attempt->exam->total_marks }}
                        </h2>
                        <p class="mb-0">
                            <strong>{{ number_format($attempt->percentage, 2) }}%</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar {{ $attempt->passed ? 'bg-success' : 'bg-danger' }}"
                         role="progressbar"
                         style="width: {{ $attempt->percentage }}%;"
                         aria-valuenow="{{ $attempt->percentage }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                        <strong>{{ number_format($attempt->percentage, 2) }}%</strong>
                    </div>
                </div>
                <div class="mt-2 text-center">
                    @if($attempt->passed)
                        <h4><span class="badge bg-success"><i class="fas fa-check-circle"></i> ناجح</span></h4>
                    @else
                        <h4><span class="badge bg-danger"><i class="fas fa-times-circle"></i> راسب</span></h4>
                    @endif
                    <p class="text-muted">النسبة المطلوبة للنجاح: {{ $attempt->exam->passing_percentage }}%</p>
                </div>
            </div>

            <div class="alert alert-{{ $attempt->certificate_enabled ? 'success' : 'secondary' }} mt-3">
                <i class="fas fa-certificate"></i>
                <strong>حالة الشهادة:</strong>
                {{ $attempt->certificate_enabled ? 'مسموح' : 'غير مسموح' }}
                @if($attempt->certificate)
                    <div><small>رقم الشهادة: {{ $attempt->certificate->certificate_number }}</small></div>
                @endif
            </div>
        </div>
    </div>

    <!-- Questions & Answers -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-question-circle"></i> Detailed Questions & Answers</h5>
        </div>
        <div class="card-body">
            @foreach($attempt->answers as $index => $answer)
                <div class="card mb-3 {{ $answer->is_correct ? 'border-success' : 'border-danger' }}">
                    <div class="card-header {{ $answer->is_correct ? 'bg-success text-white' : 'bg-danger text-white' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><strong>Question {{ $index + 1 }}</strong></span>
                            <span>
                                @if($answer->is_correct)
                                    <i class="fas fa-check-circle"></i> Correct ({{ $answer->points_earned }} points)
                                @else
                                    <i class="fas fa-times-circle"></i> Wrong (0 points)
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">{{ $answer->question->question_text }}</h6>

                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <strong>Options:</strong>
                            </div>
                            @foreach($answer->question->options as $option)
                                <div class="col-md-6 mb-2">
                                    <div class="p-2 border rounded
                                        @if($option->is_correct)
                                            bg-success-subtle border-success
                                        @elseif($answer->selected_option_id == $option->id && !$answer->is_correct)
                                            bg-danger-subtle border-danger
                                        @endif">
                                        @if($answer->selected_option_id == $option->id)
                                            <i class="fas fa-arrow-right text-primary"></i>
                                        @endif
                                        {{ $option->option_text }}

                                        @if($option->is_correct)
                                            <span class="badge bg-success float-end">الإجابة الصحيحة</span>
                                        @elseif($answer->selected_option_id == $option->id && !$answer->is_correct)
                                            <span class="badge bg-danger float-end">اختيار الطالب</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($answer->question->explanation)
                            <div class="alert alert-info mt-3 mb-0">
                                <strong><i class="fas fa-info-circle"></i> Explanation:</strong><br>
                                {{ $answer->question->explanation }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.exam-results.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> رجوع للقائمة
                </a>
                <a href="{{ route('admin.exam-results.edit', $attempt->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> تعديل النتيجة
                </a>
                <form action="{{ route('admin.exam-results.recalculate', $attempt->id) }}"
                      method="POST" class="d-inline"
                      onsubmit="return confirm('هل أنت متأكد من إعادة حساب الدرجة؟');">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calculator"></i> إعادة حساب الدرجة
                    </button>
                </form>
                <form action="{{ route('admin.exam-results.destroy', $attempt->id) }}"
                      method="POST" class="d-inline"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المحاولة؟ سيتم حذف جميع الإجابات والشهادة المرتبطة بها.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> حذف المحاولة
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .bg-success-subtle {
        background-color: #d1e7dd !important;
    }
    .bg-danger-subtle {
        background-color: #f8d7da !important;
    }
</style>
@endpush
