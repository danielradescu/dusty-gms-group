<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🧑‍💼 {{ __('Edit User') }} — {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <div class="mb-4">
                        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.users.index') }}"
                           class="inline-flex items-center px-4 py-2 rounded-md bg-gray-200 text-gray-700
              hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600
              focus:outline-none focus:ring-2 focus:ring-gray-400 transition text-sm font-medium">
                            ← Back to Users
                        </a>
                    </div>
                    @if(session('success'))
                        <div class="bg-green-100 dark:bg-green-800/40 text-green-700 dark:text-green-200 px-4 py-3 rounded-md">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" value="Name"/>
                            <x-text-input id="name" name="name" type="text"
                                          class="mt-1 block w-full"
                                          value="{{ old('name', $user->name) }}" required/>
                            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" value="Email"/>
                            <x-text-input id="email" name="email" type="email"
                                          class="mt-1 block w-full"
                                          value="{{ old('email', $user->email) }}" required/>
                            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" value="Password (leave blank to keep current)"/>
                            <x-text-input id="password" name="password" type="password"
                                          class="mt-1 block w-full"/>
                            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <x-input-label for="phone_number" value="Phone Number"/>
                            <x-text-input id="phone_number" name="phone_number" type="text"
                                          class="mt-1 block w-full"
                                          value="{{ old('phone_number', $user->phone_number) }}"/>
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2"/>
                        </div>

                        <!-- Info -->
                        <div>
                            <x-input-label for="info" value="Info / Bio"/>
                            <textarea id="info" name="info" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                             bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100
                                             focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('info', $user->info) }}</textarea>
                            <x-input-error :messages="$errors->get('info')" class="mt-2"/>
                        </div>

                        <!-- Role -->
                        <div>
                            <x-input-label for="role" value="Role"/>
                            <select id="role" name="role"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                           dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach($roles as $role)
                                    <option value="{{ $role->value }}"
                                        {{ old('role', $user->role->value) == $role->value ? 'selected' : '' }}>
                                        {{ $role->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2"/>
                        </div>

                        @if (! $user->isAdmin())
                            <!-- Toggles -->
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="is_blocked" value="1"
                                           {{ old('is_blocked', $user->is_blocked) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span>Blocked</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_blocked')" class="mt-2"/>
                        @endif


                        <!-- Actions -->
                        <div class="pt-6 flex items-center justify-between">
                            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.users.index') }}"
                               class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 bg-gray-100 text-gray-700
                                  hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300
                                  dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-gray-800
                                  transition">
                                ← Back
                            </a>

                            <x-primary-button>
                                💾 Save Changes
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
