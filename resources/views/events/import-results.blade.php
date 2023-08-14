<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Import Result') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

            <!-- Import Results Section -->
            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-1 md:gap-6">
                    
                    <div class="mt-10 md:mt-0 md:col-span-1">
                        @php
                            $order = ['new', 'updated', 'missing', 'invalid'];
                            $subtitles = [
                                'new' => 'These are new users. Select if you want to create them as new users.',
                                'updated' => 'These users have updated information. Select if you want to update those informations.',
                                'missing' => 'These users are missing from the import. Select if you want do deactivate those user.',
                                'invalid' => 'These users have invalid data. They will be ignored. You can export a list for further investigation.'
                            ];
                        @endphp

                        @foreach($order as $testResult)
                            @if(isset($groupedUsers[$testResult]))
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-medium leading-6 text-gray-900">{{ ucfirst($testResult) }} Users</h4>
                                    @if($testResult == 'invalid')
                                        <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            {{ __('Export Invalid') }}
                                        </button>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mb-4">
                                    {{ $subtitles[$testResult] }}
                                </p>
                                <table class="min-w-full divide-y divide-gray-200 w-full">
                                    <thead>
                                        <tr>
                                            @if($testResult != 'invalid')
                                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    <input type="checkbox">
                                                </th>
                                            @endif
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                User
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Relations
                                            </th>
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Result
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($groupedUsers[$testResult] as $user)
                                            <tr>
                                                @if($testResult != 'invalid')
                                                    <td class="px-6 py-4 whitespace-nowrap align-top">
                                                        <input type="checkbox" value="{{ $user->id }}">
                                                    </td>
                                                @endif
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    Name: {{ $user->name ?? "-"}}<br>
                                                    Email: {{ $user->email ?? "-" }}<br>
                                                    Phone: {{ $user->phone ?? "-" }}<br>
                                                    Address: {{ $user->address ?? "-" }}<br>
                                                    Date Of Birth: {{ $user->date_of_birth ?? "-" }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    Company: {{ $user->company_name ?? "-" }}<br>
                                                    Role: {{ $user->role_name ?? "-" }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                                    {{ $user->test_result_description }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                <x-section-border />
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-end">
                <button class="mr-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    {{ __('Discard This Import') }}
                </button>&nbsp;
                <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    {{ __('Confirm This Import') }}
                </button>
            </div>
        </div>
    </div>
</x-app-layout>