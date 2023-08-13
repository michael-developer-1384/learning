<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <div class="mt-10 sm:mt-0">
            
                <x-form-section submit="exportUsers">
                    <x-slot name="title">
                        {{ __('Export Users') }}
                    </x-slot>

                    <x-slot name="description">
                        {{ __('Export all users to an Excel file.') }}
                    </x-slot>

                    <x-slot name="form">
                        <!-- Hier können Sie zusätzliche Formularelemente hinzufügen, wenn Sie welche benötigen. -->
                    </x-slot>

                    <x-slot name="actions">

                    <a href="{{ route('event.export_users') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Start Export') }}
                    </a>


                    </x-slot>
                </x-form-section>
            </div>
            
            <x-section-border />
            
        </div>
    </div>
</x-app-layout>
