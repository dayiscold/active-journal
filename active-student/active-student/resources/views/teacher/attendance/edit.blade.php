@extends('layouts.app')
@section('title', 'Отметка посещаемости')

@section('content')

<div class="flex flex-wrap items-center justify-between gap-3 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-white">Отметка посещаемости</h1>
        <p class="text-sm text-white/40 mt-0.5">
            {{ $lesson->discipline->name }} ·
            Группа {{ $lesson->group->name }} ·
            {{ $lesson->date->format('d.m.Y') }} ·
            {{ $lesson->pair_number }}-я пара
        </p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <button type="button"
                x-data
                @click="$dispatch('open-csv-modal')"
                class="btn-violet btn-sm">
            <i class="fas fa-file-csv"></i>
            <span class="hidden sm:inline">Импорт из Teams CSV</span>
            <span class="sm:hidden">CSV</span>
        </button>
        <a href="{{ route('teacher.lessons.index') }}" class="btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>
</div>

{{-- ── Attendance form ─────────────────────────────────────────── --}}
<div class="glass-card overflow-hidden"
     x-data="attendanceTable(@js($students->mapWithKeys(fn($s) => [$s->id => $s->attendance?->status ?? 'absent'])->all()))">

    {{-- Bulk actions --}}
    <div class="glass-card-header">
        <span class="text-sm font-semibold text-white/70">Студентов: {{ $students->count() }}</span>
        <div class="flex items-center gap-2">
            <button type="button" @click="markAll('present')" class="btn-primary btn-sm">
                <i class="fas fa-check-double"></i>
                <span class="hidden sm:inline">Все присутствуют</span>
            </button>
            <button type="button" @click="markAll('absent')" class="btn-danger btn-sm">
                <i class="fas fa-xmark"></i>
                <span class="hidden sm:inline">Все отсутствуют</span>
            </button>
        </div>
    </div>

    <form id="attendance-form"
          action="{{ route('teacher.attendance.update', $lesson) }}"
          method="POST">
        @csrf @method('PUT')

        <div class="overflow-x-auto">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Студент</th>
                        <th>Номер</th>
                        <th>Статус</th>
                        <th>Причина</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($students as $student)
                <tr>
                    <td class="font-medium text-white/85">{{ $student->name }}</td>
                    <td class="text-white/40 text-xs">{{ $student->student_id ?? '—' }}</td>
                    <td>
                        <div class="inline-flex items-center rounded-xl overflow-hidden border" style="border-color:var(--border);">
                            <button type="button"
                                    @click="statuses[{{ $student->id }}] = 'present'"
                                    :class="statuses[{{ $student->id }}] === 'present' ? 'bg-teal-500/20 text-teal-400' : 'text-white/30 hover:bg-teal-500/10 hover:text-teal-400'"
                                    class="flex items-center gap-1.5 px-2.5 py-2 text-xs font-medium transition-all duration-150 border-r"
                                    style="border-color:var(--border);"
                                    title="Присутствовал" aria-label="Присутствовал">
                                <i class="fas fa-check w-3 text-center"></i>
                                <span class="hidden lg:inline">Присутствовал</span>
                            </button>
                            <button type="button"
                                    @click="statuses[{{ $student->id }}] = 'late'"
                                    :class="statuses[{{ $student->id }}] === 'late' ? 'bg-amber-500/20 text-amber-400' : 'text-white/30 hover:bg-amber-500/10 hover:text-amber-400'"
                                    class="flex items-center gap-1.5 px-2.5 py-2 text-xs font-medium transition-all duration-150 border-r"
                                    style="border-color:var(--border);"
                                    title="Опоздал" aria-label="Опоздал">
                                <i class="fas fa-clock w-3 text-center"></i>
                                <span class="hidden lg:inline">Опоздал</span>
                            </button>
                            <button type="button"
                                    @click="statuses[{{ $student->id }}] = 'absent'"
                                    :class="statuses[{{ $student->id }}] === 'absent' ? 'bg-rose-500/20 text-rose-400' : 'text-white/30 hover:bg-rose-500/10 hover:text-rose-400'"
                                    class="flex items-center gap-1.5 px-2.5 py-2 text-xs font-medium transition-all duration-150 border-r"
                                    style="border-color:var(--border);"
                                    title="Отсутствовал" aria-label="Отсутствовал">
                                <i class="fas fa-xmark w-3 text-center"></i>
                                <span class="hidden lg:inline">Отсутствовал</span>
                            </button>
                            <button type="button"
                                    @click="statuses[{{ $student->id }}] = 'sick'"
                                    :class="statuses[{{ $student->id }}] === 'sick' ? 'bg-sky-500/20 text-sky-400' : 'text-white/30 hover:bg-sky-500/10 hover:text-sky-400'"
                                    class="flex items-center gap-1.5 px-2.5 py-2 text-xs font-medium transition-all duration-150"
                                    title="Болезнь" aria-label="Болезнь">
                                <i class="fas fa-heart-pulse w-3 text-center"></i>
                                <span class="hidden lg:inline">Болезнь</span>
                            </button>
                        </div>
                        <input type="hidden"
                               name="attendance[{{ $student->id }}][status]"
                               :value="statuses[{{ $student->id }}]">
                    </td>
                    <td>
                        <input type="text"
                               name="attendance[{{ $student->id }}][reason]"
                               :disabled="statuses[{{ $student->id }}] === 'present'"
                               :placeholder="statuses[{{ $student->id }}] === 'present' ? 'Не требуется' : 'Причина отсутствия'"
                               value="{{ $student->attendance && $student->attendance->status !== 'present' ? $student->attendance->reason : '' }}"
                               class="input-glass"
                               style="min-width:200px;"
                               :class="statuses[{{ $student->id }}] === 'present' ? 'opacity-30 cursor-not-allowed' : ''"
                               aria-label="Причина {{ $student->name }}">
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 flex justify-end" style="border-top:1px solid var(--border-subtle);">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Сохранить посещаемость
            </button>
        </div>
    </form>
</div>

{{-- ── Teams CSV import modal ───────────────────────────────────── --}}
<div x-data="{ open: false }"
     @open-csv-modal.window="open = true"
     @keydown.escape.window="open = false">

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,.65);backdrop-filter:blur(4px);"
         @click.self="open = false"
         role="dialog"
         aria-modal="true"
         aria-labelledby="csv-modal-title">

        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="glass-card w-full max-w-md p-0 overflow-hidden">

            <div class="glass-card-header">
                <h2 id="csv-modal-title" class="text-sm font-semibold text-white">
                    <i class="fas fa-file-csv text-violet-400 mr-2"></i>Импорт из Teams
                </h2>
                <button @click="open = false" class="text-white/30 hover:text-white transition-colors" aria-label="Закрыть">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('teacher.attendance.importCsv', $lesson) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="p-6">
                @csrf

                <p class="text-sm text-white/50 mb-5">
                    Загрузите CSV-файл выгрузки участников из Microsoft Teams.
                    Студенты будут отмечены автоматически на основе email и времени входа.
                </p>

                <div class="mb-5">
                    <label for="csv_file" class="label-text">
                        CSV-файл <span class="text-rose-400">*</span>
                    </label>
                    <input type="file"
                           id="csv_file"
                           name="csv_file"
                           accept=".csv,.txt"
                           required
                           class="input-glass file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-violet-500/15 file:text-violet-300 hover:file:bg-violet-500/25 file:cursor-pointer file:transition-colors">
                </div>

                <div class="glass-card p-3 mb-5" style="border-color:rgba(20,184,166,.15);">
                    <p class="text-xs text-white/40 leading-relaxed">
                        <i class="fas fa-circle-info text-teal-400/60 mr-1"></i>
                        Студенты из CSV отмечаются <span class="text-teal-400">присутствующими</span> или
                        <span class="text-amber-400">опоздавшими</span> (если вход позже +15 мин от начала пары).
                        Остальные — <span class="text-rose-400">отсутствующие</span>.
                    </p>
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" @click="open = false" class="btn-secondary">Отмена</button>
                    <button type="submit" class="btn-violet">
                        <i class="fas fa-upload"></i> Импортировать
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function attendanceTable(initial) {
    return {
        statuses: initial,
        markAll(status) {
            Object.keys(this.statuses).forEach(id => this.statuses[id] = status);
        }
    };
}
</script>
@endpush
@endsection