<x-mail::message>
    <x-mail::header :url="config('app.url')">
        <img src="{{ versioned_asset('images/gallery/sust-logo.png') }}" alt="شعار جامعة السودان للعلوم والتكنولوجيا"
            style="max-width: 180px; margin-bottom: 10px;">
    </x-mail::header>

    <div dir="rtl" style="text-align: right; line-height: 1.8; font-family: system-ui, -apple-system, sans-serif;">

        # أهلاً بك، {{ $student->name }}

        يسعدنا إبلاغك بأنه قد تم تفعيل حسابك بنجاح في **بوابة الطالب** لجامعة السودان للعلوم والتكنولوجيا.

        لتسهيل دخولك إلى النظام، قمنا بإنشاء بيانات دخول مبدئية خاصة بك:

        <x-mail::panel>
            <div style="text-align: center; font-size: 16px;">
                **الرقم الجامعي:** <span dir="ltr">{{ $student->username }}</span><br><br>
                **كلمة المرور المؤقتة:** <span dir="ltr"
                    style="background-color: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-weight: bold; color: #e53e3e;">{{ $password }}</span>
            </div>
        </x-mail::panel>

        يرجى الضغط على الزر أدناه لتأكيد بريدك الإلكتروني وإعداد كلمة مرور جديدة خاصة بك:

        <x-mail::button :url="config('app.url') . '/student/confirmEmail'" color="primary">
            تأكيد البريد وإعداد كلمة المرور
        </x-mail::button>

        <span style="color: #6b7280; font-size: 12px;">
            **ملاحظة أمنية:** نوصي بشدة بعدم مشاركة بيانات الدخول الخاصة بك مع أي شخص، وتغيير كلمة المرور المؤقتة فور
            تسجيل دخولك.
        </span>

        ---
        مع خالص التحيات،<br>
        **{{ config('app.name', 'جامعة السودان للعلوم والتكنولوجيا') }}**

    </div>
</x-mail::message>