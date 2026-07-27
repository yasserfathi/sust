<x-mail::message>
    <x-mail::header :url="config('app.url')">
        <img src="{{ asset('images/gallery/sust-logo.png') }}" alt="شعار جامعة السودان للعلوم والتكنولوجيا"
            style="max-width: 180px; margin-bottom: 10px;">
    </x-mail::header>

    <div dir="rtl" style="text-align: right; line-height: 1.8; font-family: system-ui, -apple-system, sans-serif;">

        # أهلاً بك، {{ $student->name }}

        لقد تلقينا طلباً لاستعادة كلمة المرور الخاصة بحسابك في **بوابة الطالب** لجامعة السودان للعلوم والتكنولوجيا.

        لإعادة تعيين كلمة المرور، يرجى الضغط على الزر أدناه:

        <x-mail::button :url="config('app.url') . '/student/reset-password?token=' . $token . '&email=' . urlencode($student->email)" color="primary">
            إعادة تعيين كلمة المرور
        </x-mail::button>

        <span style="color: #6b7280; font-size: 12px;">
            **ملاحظة أمنية:** هذا الرابط صالح لمدة 60 دقيقة فقط. إذا لم تطلب إعادة تعيين كلمة المرور، فلا حاجة لاتخاذ أي
            إجراء.
        </span>

        ---
        مع خالص التحيات،<br>
        **{{ config('app.name', 'جامعة السودان للعلوم والتكنولوجيا') }}**

    </div>
</x-mail::message>