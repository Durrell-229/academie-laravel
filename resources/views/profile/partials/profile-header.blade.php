@php
    $role = Auth::user()->role?->slug ?? '';
    $completion = $profile->completionPercentage() ?? 0;
@endphp

{{-- Banniere profil --}}
<div class="position-relative mb-4 rounded-3 overflow-hidden shadow-sm"
     style="background:linear-gradient(rgba(59,31,140,0.7),rgba(26,5,90,0.85)),
            url('https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=1400&auto=format&fit=crop')
            center/cover no-repeat;
            min-height:180px;">

    {{-- Contenu banniere --}}
    <div class="p-4 d-flex align-items-end gap-4" style="min-height:180px;">

        {{-- Avatar --}}
        <div class="position-relative flex-shrink-0">
            @if($profile->avatar)
                <img src="{{ asset('storage/'.$profile->avatar) }}"
                     class="rounded-circle border border-4 border-white shadow"
                     style="width:90px;height:90px;object-fit:cover;">
            @else
                <div class="rounded-circle border border-4 border-white shadow d-flex align-items-center justify-content-center"
                     style="width:90px;height:90px;background:linear-gradient(135deg,#7C3AED,#3B1F8C);font-size:2rem;font-weight:700;color:white;">
                    {{ strtoupper(substr(Auth::user()->firstname,0,1)) }}{{ strtoupper(substr(Auth::user()->lastname,0,1)) }}
                </div>
            @endif
        </div>

        {{-- Infos --}}
        <div class="flex-grow-1 pb-1">
            <h2 class="fw-bold text-white mb-1">
                {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
            </h2>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge px-3 py-2" style="background:rgba(255,255,255,0.2);color:white;">
                    {{ Auth::user()->role?->title ?? 'Utilisateur' }}
                </span>
                <span class="text-white-50 small">
                    <i class="fas fa-envelope me-1"></i>{{ Auth::user()->email }}
                </span>
            </div>

            {{-- Barre de progression --}}
            <div class="mt-2" style="max-width:300px;">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-white-50 small">Profil complété</span>
                    <span class="text-white small fw-bold">{{ $completion }}%</span>
                </div>
                <div class="progress" style="height:6px;background:rgba(255,255,255,0.2);">
                    <div class="progress-bar"
                         style="width:{{ $completion }}%;background:linear-gradient(90deg,#A78BFA,#7C3AED);border-radius:10px;"></div>
                </div>
                @if($completion < 100)
                    <div class="text-white-50 mt-1" style="font-size:0.7rem;">
                        Completez votre profil pour qu'il soit visible par l'admin.
                    </div>
                @else
                    <div class="text-success mt-1" style="font-size:0.7rem;">
                        <i class="fas fa-check-circle me-1"></i>Profil complet !
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>