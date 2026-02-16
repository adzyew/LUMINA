<!doctype html>
<html class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    <title>Edit Profile | Lumina</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased min-h-screen flex flex-col transition-colors pt-16">

    @include('partials.navbar')

    <div class="grow container mx-auto px-4 sm:px-6 py-12 max-w-2xl">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-300 text-sm font-medium mb-8 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Dashboard
        </a>

        <h1 class="text-3xl font-playfair font-bold text-white mb-2">Edit Profile</h1>
        <p class="text-gray-400 mb-8">Update your name, photo, and contact info.</p>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-gray-900 rounded-2xl p-6 sm:p-8 border border-white/5 shadow-xl space-y-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative shrink-0">
                    @if($user->profile_photo_url)
                        <img src="{{ $user->profile_photo_url }}" alt="Profile" class="w-24 h-24 rounded-full object-cover border-2 border-amber-300/50">
                    @else
                        <div class="w-24 h-24 bg-gradient-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center text-black text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <label class="absolute bottom-0 right-0 w-8 h-8 bg-amber-300 rounded-full flex items-center justify-center cursor-pointer hover:bg-amber-400 transition-colors shadow-lg">
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13v7a2 2 0 01-2 2H7a2 2 0 01-2-2v-7"></path></svg>
                        <input type="file" name="profile_photo" accept="image/*" class="hidden" id="profile_photo">
                    </label>
                </div>
                <div class="text-center sm:text-left">
                    <p class="text-gray-400 text-sm">Click the camera icon to change your profile photo.</p>
                    <p class="text-gray-500 text-xs mt-1">JPG or PNG, max 2MB.</p>
                </div>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-400 mb-2">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-amber-300 focus:ring-1 focus:ring-amber-300 transition-colors">
            </div>

            <div>
                <label for="email_display" class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                <input type="text" id="email_display" value="{{ $user->email }}" disabled
                    class="w-full px-4 py-3 bg-black/30 border border-white/5 rounded-xl text-gray-500 cursor-not-allowed">
                <p class="text-gray-500 text-xs mt-1">Email cannot be changed.</p>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-400 mb-2">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required
                    class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-amber-300 focus:ring-1 focus:ring-amber-300 transition-colors">
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-white/5 text-gray-300 font-semibold rounded-xl hover:bg-white/10 border border-white/10 text-center transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    @include('partials.footer')

</body>
</html>
