@if($course->materials->count())
    <h2 class="text-xl font-bold mt-8 mb-4">Materi Kursus</h2>
    <div class="space-y-4">
        @foreach($course->materials as $material)
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-lg font-semibold">{{ $material->title }}</h3>
                <p class="text-gray-700">{{ \Illuminate\Support\Str::limit($material->content, 150) }}</p>

                @if(auth()->user()->role === 'mentor')
                <div class="mt-2 flex gap-3">
                    <a href="{{ route('features.course.materials.edit', $material->id) }}" class="text-yellow-500 hover:underline">Edit</a>
                    <form action="{{ route('features.course.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <p class="text-gray-500">Belum ada materi kursus.</p>
@endif

@if(auth()->user()->role === 'mentor')
    <a href="{{ route('features.course.materials.create', $course->id) }}" class="inline-block mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Tambah Materi</a>
@endif
