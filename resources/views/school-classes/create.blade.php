<x-app-layout>
    <div class="mx-auto max-w-96">
        <div class="rounded-lg bg-white p-6 shadow">
            <h3 class="mb-4 font-semibold">{{ __('schoolclasses.create.title') }}</h3>
            <form method="POST" action="{{ route('school-classes.store') }}">
                @csrf

                <x-input-label for="course_id" value="__('schoolclasses.create.label.course')" />
                <select
                    name="course_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="">{{ __('schoolclasses.create.placeholder.course') }}</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('course_id')" class="mt-2" />

                <x-input-label for="grade_id" value="__('schoolclasses.create.label.grade')" class="mt-4" />
                <select
                    name="grade_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="">{{ __('schoolclasses.create.placeholder.grade') }}</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('grade_id')" class="mt-2" />

                <x-input-label for="shift_id" value="__('schoolclasses.create.label.shift')" class="mt-4" />
                <select
                    name="shift_id"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300"
                >
                    <option value="">{{ __('schoolclasses.create.placeholder.shift') }}</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('shift_id')" class="mt-2" />

                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('school-classes.index') }}" class="rounded-md px-4 py-2"
                        >{{ __('schoolclasses.create.cancel') }}</a
                    >
                    <x-primary-button>{{ __('schoolclasses.create.submit') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
