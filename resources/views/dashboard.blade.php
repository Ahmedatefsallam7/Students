<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in! " . auth()->user()->name) }}
                </div>
            </div>
            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ route('subjects') }}">Show All Subjects</a></span>

            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ url('create-subject') }}">Create Subject</a></span>

            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ route('allSubjects') }}">Join To Subject</a></span>

            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ route('createdSubject', auth()->id()) }}">Show MyCreatedSubjects</a></span>

            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ route('joinedSubject', auth()->id()) }}">Show MyJoinedSubjects</a></span>

            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ route('selectSubject') }}">Generate Attend Code</a></span>

            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ route('attendMe', auth()->id()) }}">Make Me Attned</a></span>

            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ route('openTimer') }}">Open Timar</a></span>

            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ route('openTimer', ['close' => 1]) }}">Close Timar</a></span>
            <span style="margin: 10px"><a style="color: blue;text-decoration-line: underline"
                    href="{{ route('attendences') }}">Show Attendences</a></span>
        </div>

    </div>
</x-app-layout>
