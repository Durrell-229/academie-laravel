@php $completion = $profile->completionPercentage(); @endphp

{{-- Messages --}}
@if(session('status') === 'profile-updated')
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>✅ Profil mis à jour avec succès !
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('status') === 'password-updated')
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>✅ Mot de passe mis à jour !
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- ── Colonne gauche : Avatar + complétion ── --}}
    <div class="col-md-3">

        {{-- Avatar --}}
        <div class="card border-0 shadow-sm mb-3 text-center">
            <div class="card-body py-4">
                @if($profile->avatar)
                    <img src="{{ asset('storage/' . $profile->avatar) }}"
                         alt="Photo de profil" id="avatarPreview"
                         class="rounded-circle mb-3 border border-3 border-primary"
                         style="width:100px;height:100px;object-fit:cover;">
                @else
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3 border border-3 border-primary"
                         id="avatarPlaceholder"
                         style="width:100px;height:100px;font-size:2.5rem;color:white;font-weight:700;">
                        {{ strtoupper(substr($user->firstname,0,1)) }}{{ strtoupper(substr($user->lastname,0,1)) }}
                    </div>
                    <img src="" alt="" id="avatarPreview" class="rounded-circle mb-3 border border-3 border-primary d-none"
                         style="width:100px;height:100px;object-fit:cover;">
                @endif

                <h6 class="fw-bold mb-1">{{ $user->firstname }} {{ $user->lastname }}</h6>
                <span class="badge bg-success mb-2">{{ $user->role?->title }}</span>
                <p class="text-muted small mb-0">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Complétion du profil --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <span class="small fw-semibold">Profil complété</span>
                    <span class="small fw-bold text-primary">{{ $completion }}%</span>
                </div>
                <div class="progress mb-2" style="height:8px;">
                    <div class="progress-bar {{ $completion >= 80 ? 'bg-success' : ($completion >= 50 ? 'bg-warning' : 'bg-danger') }}"
                         style="width:{{ $completion }}%"></div>
                </div>
                @if($completion < 80)
                    <small class="text-muted">Complétez votre profil pour qu'il soit visible par l'admin.</small>
                @else
                    <small class="text-success"><i class="fas fa-check me-1"></i>Profil complet !</small>
                @endif
            </div>
        </div>

        {{-- Navigation rapide --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-2">
                <div class="list-group list-group-flush">
                    <a href="#compte" class="list-group-item list-group-item-action small py-2">
                        <i class="fas fa-user me-2 text-primary"></i>Compte
                    </a>
                    <a href="#identite" class="list-group-item list-group-item-action small py-2">
                        <i class="fas fa-id-card me-2 text-info"></i>Identité civile
                    </a>
                    <a href="#naissance" class="list-group-item list-group-item-action small py-2">
                        <i class="fas fa-certificate me-2 text-warning"></i>Acte de naissance
                    </a>
                    <a href="#famille" class="list-group-item list-group-item-action small py-2">
                        <i class="fas fa-users me-2 text-success"></i>Famille
                    </a>
                    <a href="#identifiant" class="list-group-item list-group-item-action small py-2">
                        <i class="fas fa-passport me-2 text-danger"></i>Pièce d'identité
                    </a>
                    <a href="#profession" class="list-group-item list-group-item-action small py-2">
                        <i class="fas fa-briefcase me-2 text-secondary"></i>Profession
                    </a>
                    <a href="#securite" class="list-group-item list-group-item-action small py-2">
                        <i class="fas fa-lock me-2 text-dark"></i>Sécurité
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Colonne droite : Formulaires ── --}}
    <div class="col-md-9">

        <form action="{{ route('profile.updateFull') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">@foreach($errors->all() as $e)<li class="small">{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- ══ PHOTO DE PROFIL ══ --}}
            <div class="card border-0 shadow-sm mb-4" id="compte">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-camera me-2 text-primary"></i>Photo de profil</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-4">
                        <div class="border rounded p-2" style="cursor:pointer;" onclick="document.getElementById('avatarInput').click()">
                            <i class="fas fa-upload fa-2x text-muted"></i>
                        </div>
                        <div>
                            <input type="file" name="avatar" id="avatarInput" class="d-none"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   onchange="previewAvatar(this)" />
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="document.getElementById('avatarInput').click()">
                                <i class="fas fa-upload me-2"></i>Choisir une photo
                            </button>
                            <p class="text-muted small mb-0 mt-1">JPG, PNG ou WebP — max 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ INFORMATIONS COMPTE ══ --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Informations du compte</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="firstname" form="accountForm" class="form-control"
                                   value="{{ old('firstname', $user->firstname) }}" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="lastname" form="accountForm" class="form-control"
                                   value="{{ old('lastname', $user->lastname) }}" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Biographie</label>
                            <textarea name="biography" class="form-control" rows="3"
                                      placeholder="Parlez de vous...">{{ old('biography', $profile->biography) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Religion</label>
                            <input type="text" name="religion" class="form-control"
                                   value="{{ old('religion', $profile->religion) }}" placeholder="Ex: Chrétien, Muslim..." />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ IDENTITÉ CIVILE ══ --}}
            <div class="card border-0 shadow-sm mb-4" id="identite">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2 text-info"></i>Identité civile</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Date de naissance</label>
                            <input type="date" name="date_of_birth" class="form-control"
                                   value="{{ old('date_of_birth', $profile->date_of_birth?->format('Y-m-d')) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Genre</label>
                            <select name="gender" class="form-select">
                                <option value="">-- Choisir --</option>
                                <option value="male"   {{ old('gender', $profile->gender) === 'male'   ? 'selected' : '' }}>Masculin</option>
                                <option value="female" {{ old('gender', $profile->gender) === 'female' ? 'selected' : '' }}>Féminin</option>
                                <option value="other"  {{ old('gender', $profile->gender) === 'other'  ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Situation matrimoniale</label>
                            <select name="marital" class="form-select">
                                <option value="">-- Choisir --</option>
                                <option value="single"   {{ old('marital', $profile->marital) === 'single'   ? 'selected' : '' }}>Célibataire</option>
                                <option value="married"  {{ old('marital', $profile->marital) === 'married'  ? 'selected' : '' }}>Marié(e)</option>
                                <option value="divorced" {{ old('marital', $profile->marital) === 'divorced' ? 'selected' : '' }}>Divorcé(e)</option>
                                <option value="widowed"  {{ old('marital', $profile->marital) === 'widowed'  ? 'selected' : '' }}>Veuf/Veuve</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nationalité</label>
                            <input type="text" name="nationality" class="form-control"
                                   value="{{ old('nationality', $profile->nationality) }}" placeholder="Ex: Béninoise" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Pays de naissance</label>
                            <input type="text" name="country_of_birth" class="form-control"
                                   value="{{ old('country_of_birth', $profile->country_of_birth) }}" placeholder="Ex: Bénin" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Ville de naissance</label>
                            <input type="text" name="city_of_birth" class="form-control"
                                   value="{{ old('city_of_birth', $profile->city_of_birth) }}" placeholder="Ex: Cotonou" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Pays de résidence</label>
                            <input type="text" name="country_of_residence" class="form-control"
                                   value="{{ old('country_of_residence', $profile->country_of_residence) }}" placeholder="Ex: Bénin" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Ville de résidence</label>
                            <input type="text" name="city_of_residence" class="form-control"
                                   value="{{ old('city_of_residence', $profile->city_of_residence) }}" placeholder="Ex: Cotonou" />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Adresse complète</label>
                            <textarea name="address" class="form-control" rows="2"
                                      placeholder="Quartier, rue, numéro...">{{ old('address', $profile->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ ACTE DE NAISSANCE ══ --}}
            <div class="card border-0 shadow-sm mb-4" id="naissance">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-certificate me-2 text-warning"></i>Acte de naissance</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Numéro de l'acte</label>
                            <input type="text" name="birth_certificate_number" class="form-control"
                                   value="{{ old('birth_certificate_number', $profile->birth_certificate_number) }}"
                                   placeholder="Ex: 001/2000" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Centre d'état civil</label>
                            <input type="text" name="birth_certificate_center" class="form-control"
                                   value="{{ old('birth_certificate_center', $profile->birth_certificate_center) }}"
                                   placeholder="Ex: Mairie de Cotonou" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Date de l'acte</label>
                            <input type="date" name="birth_certificate_date" class="form-control"
                                   value="{{ old('birth_certificate_date', $profile->birth_certificate_date?->format('Y-m-d')) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Pays d'émission</label>
                            <input type="text" name="birth_certificate_country" class="form-control"
                                   value="{{ old('birth_certificate_country', $profile->birth_certificate_country) }}"
                                   placeholder="Ex: Bénin" />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">
                                <i class="fas fa-upload me-1"></i>Joindre l'acte de naissance (PDF ou image)
                            </label>
                            <input type="file" name="birth_certificate_file" class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png" />
                            @if($profile->birth_certificate_file)
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>Fichier déjà uploadé —
                                    <a href="{{ asset('storage/'.$profile->birth_certificate_file) }}" target="_blank">Voir</a>
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ FAMILLE ══ --}}
            <div class="card border-0 shadow-sm mb-4" id="famille">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-users me-2 text-success"></i>Informations familiales</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Prénom du père</label>
                            <input type="text" name="father_firstname" class="form-control"
                                   value="{{ old('father_firstname', $profile->father_firstname) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nom du père</label>
                            <input type="text" name="father_lastname" class="form-control"
                                   value="{{ old('father_lastname', $profile->father_lastname) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Prénom de la mère</label>
                            <input type="text" name="mother_firstname" class="form-control"
                                   value="{{ old('mother_firstname', $profile->mother_firstname) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nom de la mère</label>
                            <input type="text" name="mother_lastname" class="form-control"
                                   value="{{ old('mother_lastname', $profile->mother_lastname) }}" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Nombre d'enfants</label>
                            <input type="number" name="children_count" class="form-control"
                                   value="{{ old('children_count', $profile->children_count ?? 0) }}" min="0" />
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Contact d'urgence</label>
                            <div class="row g-2">
                                <div class="col-5">
                                    <input type="text" name="emergency_contact_name" class="form-control form-control-sm"
                                           value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}"
                                           placeholder="Nom complet" />
                                </div>
                                <div class="col-4">
                                    <input type="text" name="emergency_contact_phone" class="form-control form-control-sm"
                                           value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}"
                                           placeholder="Téléphone" />
                                </div>
                                <div class="col-3">
                                    <input type="text" name="emergency_contact_relation" class="form-control form-control-sm"
                                           value="{{ old('emergency_contact_relation', $profile->emergency_contact_relation) }}"
                                           placeholder="Lien" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ PIÈCE D'IDENTITÉ ══ --}}
            <div class="card border-0 shadow-sm mb-4" id="identifiant">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-passport me-2 text-danger"></i>Pièce d'identité</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Type de pièce</label>
                            <select name="id_type" class="form-select">
                                <option value="">-- Choisir --</option>
                                <option value="cni"      {{ old('id_type', $profile->id_type) === 'cni'      ? 'selected' : '' }}>Carte Nationale d'Identité</option>
                                <option value="passport" {{ old('id_type', $profile->id_type) === 'passport' ? 'selected' : '' }}>Passeport</option>
                                <option value="permis"   {{ old('id_type', $profile->id_type) === 'permis'   ? 'selected' : '' }}>Permis de conduire</option>
                                <option value="autre"    {{ old('id_type', $profile->id_type) === 'autre'    ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Numéro</label>
                            <input type="text" name="id_number" class="form-control"
                                   value="{{ old('id_number', $profile->id_number) }}" placeholder="Ex: BJ001234" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Date d'expiration</label>
                            <input type="date" name="id_expiry_date" class="form-control"
                                   value="{{ old('id_expiry_date', $profile->id_expiry_date?->format('Y-m-d')) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Pays émetteur</label>
                            <input type="text" name="id_issuing_country" class="form-control"
                                   value="{{ old('id_issuing_country', $profile->id_issuing_country) }}" placeholder="Ex: Bénin" />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">
                                <i class="fas fa-upload me-1"></i>Joindre la pièce d'identité (PDF ou image)
                            </label>
                            <input type="file" name="id_document" class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png" />
                            @if($profile->id_document)
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>Fichier déjà uploadé —
                                    <a href="{{ asset('storage/'.$profile->id_document) }}" target="_blank">Voir</a>
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ PROFESSION ══ --}}
            <div class="card border-0 shadow-sm mb-4" id="profession">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2 text-secondary"></i>Situation professionnelle & réseaux</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Profession</label>
                            <input type="text" name="occupation" class="form-control"
                                   value="{{ old('occupation', $profile->occupation) }}" placeholder="Ex: Enseignant" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Employeur / Établissement</label>
                            <input type="text" name="employer" class="form-control"
                                   value="{{ old('employer', $profile->employer) }}" placeholder="Ex: Lycée National" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email professionnel</label>
                            <input type="email" name="professional_email" class="form-control"
                                   value="{{ old('professional_email', $profile->professional_email) }}"
                                   placeholder="pro@exemple.com" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="fab fa-facebook me-1 text-primary"></i>Facebook
                            </label>
                            <input type="url" name="facebook" class="form-control"
                                   value="{{ old('facebook', $profile->facebook) }}"
                                   placeholder="https://facebook.com/..." />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="fab fa-linkedin me-1 text-info"></i>LinkedIn
                            </label>
                            <input type="url" name="linkedin" class="form-control"
                                   value="{{ old('linkedin', $profile->linkedin) }}"
                                   placeholder="https://linkedin.com/in/..." />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="fab fa-twitter me-1 text-info"></i>Twitter / X
                            </label>
                            <input type="url" name="twitter" class="form-control"
                                   value="{{ old('twitter', $profile->twitter) }}"
                                   placeholder="https://twitter.com/..." />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">
                                <i class="fas fa-globe me-1"></i>Site web personnel
                            </label>
                            <input type="url" name="website" class="form-control"
                                   value="{{ old('website', $profile->website) }}"
                                   placeholder="https://monsite.com" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bouton de sauvegarde --}}
            <div class="d-flex gap-3 mb-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Enregistrer tout le profil
                </button>
                <a href="#" onclick="window.history.back()" class="btn btn-outline-secondary btn-lg">
                    Annuler
                </a>
            </div>

        </form>

        {{-- ══ SÉCURITÉ ══ --}}
        <div class="card border-0 shadow-sm mb-4" id="securite">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-lock me-2 text-dark"></i>Sécurité — Mot de passe</h5>
            </div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- ══ COMPTE ══ --}}
        <div class="card border-0 shadow-sm border-danger mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Zone dangereuse</h5>
            </div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('avatarPreview');
            const placeholder = document.getElementById('avatarPlaceholder');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
