<div class="dashboard-welcome mb-4">
    <h4 class="dashboard-welcome__title mb-1">
        مرحباً {{ auth()->user()->name }}، أهلاً بعودتك!
    </h4>
    <p class="dashboard-welcome__subtitle mb-0">
        أنت مسجل الدخول كـ {{ $roleLabel }}
    </p>
</div>
