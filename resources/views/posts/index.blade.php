@extends('layouts.app')

@section('title', 'BlogSpace - Share Your Stories')

@section('content')
<!-- Hero Section -->
<section class="gradient-bg text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Welcome to <span class="text-blue-100">BlogSpace</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto">
                Platform berbagi cerita dan pengetahuan untuk komunitas Indonesia. 
                Temukan artikel menarik dan bergabunglah dengan diskusi yang bermakna.
            </p>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="py-8 bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-2">Cari Artikel</h3>
            <p class="text-gray-600">Temukan artikel yang Anda cari dengan mudah</p>
        </div>
        
            <!-- Search Form -->
            <form method="GET" action="{{ route('posts.index') }}" class="max-w-md mx-auto mb-6" id="search-form">
                <div class="relative">
                    <input type="text" name="q" id="search-input" value="{{ request('q') }}" placeholder="Cari artikel..." 
                           autocomplete="off"
                           class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </form>

            @push('scripts')
            <script>
            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }
            document.addEventListener('DOMContentLoaded', function() {
                const input = document.getElementById('search-input');
                const form = document.getElementById('search-form');
                if(input && form) {
                    input.addEventListener('input', debounce(function(e) {
                        if (this.value.length >= 2 || this.value.length === 0) {
                            form.submit();
                        }
                    }, 400));
                }
            });
            </script>
            @endpush
    </div>
</section>
        @if(isset($keyword) && $keyword)
            <div class="mb-8 text-center">
                <span class="text-gray-700">Hasil pencarian untuk: <span class="font-semibold text-blue-600">"{{ $keyword }}"</span></span>
            </div>
        @endif

<!-- Posts Section -->
<section id="posts" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Artikel Terbaru</h2>
            <p class="text-xl text-gray-600">Temukan insight dan pengetahuan baru dari artikel pilihan kami</p>
        </div>
        
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <article class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover border border-gray-100 flex flex-col h-full">
                        <!-- Post Image -->
                        <div class="relative">
                            @if($post->foto)
                                <img src="{{ asset('storage/' . $post->foto) }}" alt="Foto Artikel" class="w-full h-48 object-cover object-center">
                            @else
                                <div class="h-48 flex items-center justify-center" style="background-color: #1e40af;">
                                    <div class="text-center">
                                        <div class="w-16 h-16 rounded-lg flex items-center justify-center mx-auto mb-3" style="background-color: #3b82f6; border: 2px solid #60a5fa;">
                                            <i class="fas fa-laptop" style="font-size: 2rem; color: #ffffff;"></i>
                                        </div>
                                        <p style="color: #ffffff; font-size: 0.875rem; font-weight: 500;">{{ Str::limit($post->judul, 30) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Post Content & Footer -->
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex-1 flex flex-col">
                                <!-- Date & Author -->
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                    <span>{{ $post->tanggal_post->translatedFormat('d F Y') }}</span>
                                    @if($post->user)
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-user mr-1 text-blue-500"></i>
                                        <span>{{ $post->user->name }}</span>
                                    @endif
                                </div>
                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight hover:text-blue-600 transition-colors">
                                    <a href="{{ route('posts.show', $post) }}">{{ $post->judul }}</a>
                                </h3>
                                <!-- Excerpt -->
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    {{ Str::limit($post->konten, 100) }}
                                </p>
                            </div>
                            <!-- Footer -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center text-sm text-gray-500 h-6">
                                    <i class="fas fa-comments mr-2"></i>
                                    <span class="leading-none">{{ $post->comments->count() }} comments</span>
                                </div>
                                <a href="{{ route('posts.show', $post) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium text-sm transition-colors h-6">
                                    <span class="leading-none">Read More</span>
                                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $posts->links('vendor.pagination.custom') }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Belum Ada Artikel</h3>
                <p class="text-gray-600 mb-8">Belum ada artikel yang dipublikasikan. Silakan kembali lagi nanti.</p>
                <a href="{{ route('admin.login') }}" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>Tambah Artikel Pertama
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
