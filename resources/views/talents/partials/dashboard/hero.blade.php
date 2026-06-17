<div class="talent-dashboard-hero mb-4">
    <div class="talent-dashboard-hero__glow"></div>
    <div class="row align-items-center g-3 position-relative">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="talent-dashboard-hero__avatar">
                    @if($talent?->avatarUrl())
                        <img src="{{ $talent->avatarUrl() }}" alt="{{ $user->name }}">
                    @else
                        <span>{{ $talent?->avatar_initial ?? mb_substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                <div>
                    <p class="talent-dashboard-hero__eyebrow mb-1">لوحة التقني</p>
                    <h2 class="talent-dashboard-hero__title mb-1">مرحباً {{ $user->name }}، أهلاً بعودتك!</h2>
                    <p class="talent-dashboard-hero__subtitle mb-2">
                        {{ $talent?->title ?: 'أكمل ملفك لزيادة فرص ظهورك أمام الشركات' }}
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @if($talent?->is_open_to_work || $publicHiringRequest)
                            <span class="talent-dashboard-hero__pill talent-dashboard-hero__pill--success">
                                <i class="ri-briefcase-line"></i> يبحث عن عمل
                            </span>
                        @endif
                        @if($talent?->is_remote)
                            <span class="talent-dashboard-hero__pill">
                                <i class="ri-global-line"></i> Remote
                            </span>
                        @endif
                        @if($talent?->is_verified)
                            <span class="talent-dashboard-hero__pill talent-dashboard-hero__pill--verified">
                                <i class="ri-verified-badge-line"></i> موثّق
                            </span>
                        @endif
                        @if($talent?->slug)
                            <a href="{{ route('talents.show', $talent) }}" class="talent-dashboard-hero__pill talent-dashboard-hero__pill--link" target="_blank">
                                <i class="ri-external-link-line"></i> الملف العام
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="talent-dashboard-hero__completion">
                <div class="talent-dashboard-hero__ring" style="--completion: {{ $profileCompletion }};">
                    <span>{{ $profileCompletion }}%</span>
                </div>
                <div class="talent-dashboard-hero__completion-body">
                    <p class="talent-dashboard-hero__completion-title">اكتمال الملف</p>
                    @if($profileCompletion >= 100)
                        <p class="talent-dashboard-hero__completion-hint">ملفك مكتمل وجاهز للظهور أمام الشركات.</p>
                        <a href="{{ route('talent.profile.edit') }}" class="talent-dashboard-hero__completion-btn">
                            <i class="ri-eye-line"></i> مراجعة الملف
                        </a>
                    @else
                        <p class="talent-dashboard-hero__completion-hint">أكمل بياناتك لزيادة فرص التوظيف.</p>
                        <a href="{{ route('talent.profile.edit') }}" class="talent-dashboard-hero__completion-btn">
                            <i class="ri-edit-line"></i> إكمال الملف
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
