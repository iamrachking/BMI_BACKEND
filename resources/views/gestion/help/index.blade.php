@extends('gestion.layouts.dashboard')

@section('title', 'Aide')
@section('header', 'Aide')

@section('content')
    <div class="max-w-3xl">
        <p class="text-gray-600 mb-6">
            Retrouvez ci-dessous des questions fréquentes et des réponses pour vous guider selon votre rôle
            @if($roleName)
                <span class="font-medium text-gray-800">({{ ucfirst($roleName) }})</span>.
            @endif
        </p>

        <div class="space-y-2">
            @forelse($faqs as $index => $faq)
                <details class="group rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden"
                         {{ $index === 0 ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 px-4 py-3 cursor-pointer list-none font-medium text-gray-800 hover:bg-gray-50 transition-colors [&::-webkit-details-marker]:hidden">
                        <i class="fas fa-chevron-right text-gray-400 group-open:rotate-90 transition-transform shrink-0 w-4 text-center"></i>
                        <span>{{ $faq['question'] }}</span>
                    </summary>
                    <div class="px-4 pb-4 pt-0 pl-11 text-gray-600 text-sm leading-relaxed border-t border-gray-100">
                        @php
                            $answer = $faq['answer'];
                            $answer = e($answer);
                            $answer = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $answer);
                            $answer = nl2br($answer);
                        @endphp
                        {!! $answer !!}
                    </div>
                </details>
            @empty
                <div class="rounded-lg border border-gray-200 bg-white p-6 text-center text-gray-500">
                    Aucune question d'aide disponible pour votre profil.
                </div>
            @endforelse
        </div>
    </div>
@endsection
